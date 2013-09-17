<?php

class Blackflag_Publisher
{
    protected $_logger;
    
    /**
     * The last known comment_id
     * @var integer
     */
    protected $nextCommentId = 0;
    
    /**
     * Data array to be sent via JSON to NGINX
     * @var array
     */
    protected $data = array();
    protected $reconnectForever = false;
    protected $reconnectAttempts = 0;
    public $doReconnectTimeout = false;

    public function __construct($config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }
        
        if (!isset($this->config['storage']['terminating_string'])) {
            throw new Exception('Terminating string not set in config (terminating_string)');
        }
        
        if (!isset($this->config['nginx']['pubUrl'])) {
            throw new Exception('NGINX publish URL not set (nginx.pubUrl)');
        }
        
        if (!isset($this->config['nginx']['reconnect']['timeout'])) {
            throw new Exception('NGINX reconnectin timeout not set (nginx.reconnect.timeout)');
        }
        
        if (!isset($this->config['nginx']['reconnect']['attempts']) || $this->config['nginx']['reconnect']['attempts'] == 0) {
            $this->reconnectForever = true;
        } else {
            $this->reconnectAttempts = $this->config['nginx']['reconnect']['attempts'];
        }
        
        if (isset($this->config['storage']['log']['enabled']) && $this->config['storage']['log']['enabled']) {
            $this->setupLogger();
        }
        
        /*
         * Store the latest comment_id
         */
        $this->fetchNextCommentIdFromDb();
    }

    /**
     * Set configuration parameters
     * @param  Zend_Config | array $config
     * @return Blackflag_Publisher
     * @throws Exception
     */
    public function setConfig($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        
        } elseif (!is_array($config)) {
            throw new Exception('Array or Zend_Config object expected, got ' . gettype($config));
        }
        foreach ($config as $k => $v) {
            $this->config[strtolower($k)] = $v;
        }
        return $this;
    }

    protected function setupLogger()
    {
        $this->_logger = new Zend_Log();
        $this->_writer = new Zend_Log_Writer_Stream('publisher.log');
        $this->_logger->addWriter($this->_writer);
    }

    protected function isLoggerSet()
    {
        if (isset($this->_logger) && $this->_logger instanceof Zend_Log) {
            return true;
        }
        return false;
    }

    protected function getLogger()
    {
        if ($this->isLoggerSet()) {
            return $this->_logger;
        }
        return false;
    }

    protected function log($message, $priority)
    {
        if ($logger = $this->getLogger()) {
            $logger->log($message, $priority);
        }
    }

    /**
     * Gets the reconnect timeout value in seconds
     * @return integer
     */
    public function getReconnectTimeout()
    {
        return $this->config['nginx']['reconnect']['timeout'];
    }

    /**
     * Add to the data array
     * @param string|false $name - Associative name of array to add - false will add $data with an index
     * @param mixed $data - Data to be added to array
     * @param boolean $merge - True = recursively merge any data already in the data array; False = overwrite
     */
    protected function addToData($name, $data, $merge = true)
    {
        if (isset($this->data[$name])) {
            if ($merge) {
                $this->data[$name] = array_merge_recursive($this->data[$name], $data);
            } else {
                // Replace only the part of the array that has been supplied if it already exists
                foreach ($data as $key => $newDataArray) {
                    if (isset($this->data[$name][$key])) {
                        $existingKeys = array_intersect_key($this->data[$name][$key], $newDataArray);
                        if (count($existingKeys)) {
                            foreach ($existingKeys as $eKey => $value) {
                                $this->data[$name][$key][$eKey] = $newDataArray[$eKey];
                            }
                            unset($existingKeys);
                            unset($eKey);
                        } else {
                            $this->data[$name][$key] = array_merge($this->data[$name][$key], $newDataArray);
                        }
                    } else {
                        $this->data[$name][$key] = $newDataArray;
                    }
                }
                unset($key);
                unset($newDataArray);
            }
        } else {
            $this->data[$name] = $data;
        }
        unset($name);
        unset($data);
        unset($merge);
    }

    /**
     * Get the data array
     * @return array
     */
    protected function getData()
    {
        if (isset($this->data) && $this->data) {
            return $this->data;
        }
        return false;
    }

    /**
     * Wipes the data array ready for new data
     */
    protected function clearData()
    {
        //$this->data = array();
        unset($this->data);
    }

    protected function addDriverData($chunk)
    {
        $driver = array();
        $chunks = explode(',', $chunk);
        $driverCode = $chunks[4];
        $driver[$driverCode]['code'] = $chunks[4];
        $driver[$driverCode]['telemetry']['timestamp'] = $chunks[1];
        $driver[$driverCode]['telemetry']['nEngine'] = (int) $chunks[5];
        $driver[$driverCode]['telemetry']['NGear'] = (int) $chunks[6];
        $driver[$driverCode]['telemetry']['rThrottlePedal'] = (int) $chunks[7];
        $driver[$driverCode]['telemetry']['pBrakeF'] = (int) $chunks[8];
        $driver[$driverCode]['telemetry']['gLat'] = (int) $chunks[9];
        $driver[$driverCode]['telemetry']['gLong'] = (int) $chunks[10];
        $driver[$driverCode]['telemetry']['sLap'] = (int) $chunks[11];
        $driver[$driverCode]['telemetry']['vCar'] = (float) $chunks[12];
        $driver[$driverCode]['telemetry']['NGPSLatitude'] = (float) $chunks[13];
        $driver[$driverCode]['telemetry']['NGPSLongitude'] = (float) $chunks[14];
        
        $this->addToData('drivers', $driver, false);
        $this->insertExtraTelemetry($driverCode);
        unset($driver);
        unset($driverCode);
        unset($chunks);
    }

    /**
     * Processes Camera data.  Saves image to file.
     * @todo Push to CDN?
     * @param string $chunk
     */
    protected function addCameraData($chunk)
    {
        /*
         * <P>ATLAS,00:00:00,Params,DefCamera,CC2,17471,[data]</P>
         */
        $camera = array();
        $chunks = explode(',', $chunk);
        $cameraName = strtolower($chunks[4]);
        $camera[$cameraName]['camera'] = $chunks[4];
        $camera[$cameraName]['length'] = (int) $chunks[5];
        
        // save image to camera image file
        for ($i = 0; $i < 6; $i++) {
            unset($chunks[$i]);
        }
        $image = implode(',', $chunks);
        $image = rtrim($image, ',</P>');
        $filename = 'camera_' . $cameraName . '.jpg';
        $fp = fopen($this->config['imagepath'] . '/' . $filename, 'w+b');
        fwrite($fp, $image, $camera[$cameraName]['length']);
        fclose($fp);
        $camera[$cameraName]['image'] = '/' . $filename;
        
        $this->addToData('cameras', $camera, false);
        unset($camera);
        unset($chunks);
        unset($cameraName);
        unset($filename);
        unset($image);
        unset($fp);
    }

    /**
     * Adds the endLap data for that driver
     * @param string $chunk
     */
    protected function addEndLapData($chunk)
    {
        /*
         * <P>ATLAS,,ParamStructure,DefAtlasEndLap,4,1,1,LapTime,MaxSpeed,MeanSpeed,MaxLateralG,</P>
         * <P>ATLAS,{time},Params, DefAtlasEndLap,{driver},{lap time/s},{max speed/kph},{mean speed/kph},{max lateral G/g},</P>
         * <P>ATLAS,12:38:47.930,Params,DefAtlasEndLap,BUT,201.041,285,78,5,</P>
         */
        $endLapData = array();
        $chunks = explode(',', $chunk);
        $driver = $chunks[4];
        $endLapData[$driver]['lapTimeS'] = $chunks[5];
        $endLapData[$driver]['maxSpeedKph'] = (int) $chunks[6];
        $endLapData[$driver]['meanSpeedKph'] = (int) $chunks[7];
        $endLapData[$driver]['maxLateralG'] = (int) $chunks[8];
        
        $this->addToData('drivers', $endLapData, false);
        unset($endLapData);
        unset($chunks);
        unset($driver);
    }

    /**
     * Looks up any drivers in the data['drivers'] array and adds any addtional database data for them
     * @return void
     */
    protected function insertExtraTelemetry($driverCode)
    {
        // get lap / pos from db for the drivers
        $results = Doctrine::getTable('Driver')->getDriverStats($driverCode);
        if ($results) {
            $drivers = array();
            foreach ($results as $result) {
                $drivers[$result->code]['additional']['lap'] = $result->lap;
                $drivers[$result->code]['additional']['position'] = $result->position;
                $drivers[$result->code]['additional']['is_racing'] = $result->is_racing;
            }
            unset($result);
            $this->addToData('drivers', $drivers, false);
        }
        unset($results);
        unset($drivers);
    }

    /**
     * Gets all comments after $nextCommentId
     * @param integer $nextCommentId
     */
    protected function getCommentaryFromDatabase($nextCommentId)
    {
        $results = Doctrine::getTable('Comment')->getCommentsGreaterThanId($nextCommentId, true);
        
        $comments = array();
        $x = 0;
        foreach ($results as $result) {
            // NOTE: For now only ONE tag per comment and it relates to a video for the Dashboard
            if (isset($result->CommentsTag) && count($result->CommentsTag) > 0) {
                $comments[$x]['video'] = $result->CommentsTag[0]->Tag->url;
            }
            
            if (isset($result->Driver) && count($result->Driver) > 0) {
                $comments[$x]['name'] = $result->Driver->code;
                $comments[$x]['initials'] = $result->Driver->initials;
            }
            $comments[$x]['text'] = stripslashes($result->body);
            $comments[$x]['timestamp'] = $result->created_at;
            $x++;
        }
        
        if ($comments) {
            // Update the next comment_id
            $this->fetchNextCommentIdFromDb();
            $this->addToData('commentary', $comments);
            unset($comments);
        }
        unset($results);
        unset($x);
        unset($nextCommentId);
    }

    /**
     * Gets the last known comment_id from the Comments table
     * @return void
     * @throws Exception
     */
    protected function fetchNextCommentIdFromDb()
    {
        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $stmt = $conn->prepare("SHOW TABLE STATUS LIKE 'comments'");
        $stmt->execute();
        $results = $stmt->fetchAll();
        if (isset($results[0]['Auto_increment'])) {
            $this->setNextCommentId($results[0]['Auto_increment']);
        } else {
            throw new Exception('Cannot find last comment_id');
        }
        unset($conn);
        unset($stmt);
        unset($results);
    }

    /**
     * Set the nextCommentId
     * @param integer $id
     * @return void 
     */
    protected function setNextCommentId($id)
    {
        $this->nextCommentId = (int) $id;
    }

    /**
     * Returns the last known comment_id
     * @return integer
     */
    protected function getNextCommentId()
    {
        return $this->nextCommentId;
    }

    /**
     * Create a useful data array from the raw telemetry
     * @param array $telemetry
     * @return void
     */
    protected function parseTelemetry($telemetry = null)
    {
        if ($telemetry && is_array($telemetry)) {
            foreach ($telemetry as $chunk) {
                switch ($chunk['type']) {
                    case 'DefAtlas':
                        $this->addDriverData($chunk['content']);
                        break;
                    case 'DefCamera':
                        $this->addCameraData($chunk['content']);
                        break;
                    case 'DefAtlasEndLap':
                        $this->addEndLapData($chunk['content']);
                        break;
                }
            }
            unset($chunk);
            unset($telemetry);
        }
    }

    /**
     * Push latest telemetry and commentary to NGINX
     * @param array $telemetry
     * @return void
     */
    public function publish($telemetry = null)
    {
        $this->doReconnectTimeout = false;
        $this->clearData();
        
        // Process telemetry and add to $this->data
        $this->parseTelemetry($telemetry);
        unset($telemetry);
        
        // gather latest commentry data from a queue
        $this->getCommentaryFromDatabase($this->getNextCommentId());
        
        if ($this->getData()) {
            $this->log('Sending Data:' . print_r($this->getData(), true), Zend_Log::INFO);
            // combine the two in to one JSON array
            $json = Zend_Json::encode($this->getData());
            
            //            if (class_exists(System_Daemon)) {
            //                System_Daemon::log(System_Daemon::LOG_INFO, $json);
            //            }
            

            // post to NGIX
            $client = new Zend_Http_Client($this->config['nginx']['pubUrl'], array(
                'maxredirects' => 0, 
                'timeout' => 2
            ));
            
            try {
                //$client->setParameterPost('json', $json)->request('POST');
                $client->setRawData('Dashboard.jsonCallback(\'' . $json . '\');')->request('POST');
                // Log the response (specifically to log "active supscribers" which NGINX reports)
                $response = $client->getLastResponse();
                $this->log($response, Zend_Log::INFO);
                unset($response);
            } catch (Zend_Http_Client_Exception $e) {
                $this->log('CAUGHT EXCEPTION.  Trying to reconnect - here is the exception that was caught:' . "\n" . $e, Zend_Log::ERR);
                if (!$this->reconnectForever) {
                    if ($this->reconnectAttempts > 0) {
                        $this->reconnectAttempts--;
                    } else {
                        $this->log('Max reconnection attempts (' . $this->config['nginx']['reconnect']['attempts'] . ') exceeded. Throwing fatal error and exiting.', Zend_Log::ERR);
                        throw $e;
                    }
                }
                $this->doReconnectTimeout = true;
            }
            unset($json);
            unset($client);
        }
    }

}