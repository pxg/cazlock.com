<?php

require_once 'Zend/Http/Client/Adapter/Exception.php';

/**
 * Shared Memory Storage class
 * @category   Blackflag
 * @package    Blackflag_Http_Client
 * @subpackage Blackflag_Http_Client_Storage
 * @author     Alex Gemmell <alexgemmell@theredfactory.co.uk>
 * 
 * For debugging these use:
 * 'ipcs' to view current memory use 
 * 'ipcrm -m {shmid}' to remove 
 * on some systems use 'ipcclean' to clean up unused memory if you don't want to do it by hand 
 */
class Blackflag_Http_Client_Storage_Shm implements Blackflag_Http_Client_Storage_Interface
{
    
    protected $shmKey;
    protected $contentsBuffer = '';
    protected $clearData = '';

    /**
     * Contructor method. Accepts optional configuration array.
     *
     * @param array $config Configuration key-value pairs.
     */
    public function __construct($config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }
        
        for ($x = 0; $x < $this->config['readbytes']; $x++) {
            $this->clearData .= ' ';
        }
        
        $this->logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream('sample.log');
        $this->logger->addWriter($writer);
        //$this->logger->log($contents, Zend_Log::INFO);
    }

    /**
     * Set configuration parameters for this HTTP client
     *
     * @param  Zend_Config | array $config
     * @return Zend_Http_Client
     * @throws Zend_Http_Client_Exception
     */
    public function setConfig($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        
        } elseif (!is_array($config)) {
            throw new Zend_Http_Client_Exception('Array or Zend_Config object expected, got ' . gettype($config));
        }
        foreach ($config as $k => $v) {
            $this->config[strtolower($k)] = $v;
        }
        return $this;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Get the number of bytes to read from the storage
     * @return integer
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * Set the bytes to read from the storage
     * @param integer $bytes
     * @return self
     */
    public function setBytes($bytes)
    {
        if (is_numeric($bytes)) {
            $this->bytes = (int) $bytes;
        }
        return $this;
    }

    /**
     * Get the Shared Memory Key, creating one if necessary.
     * @return string 
     */
    public function getKey()
    {
        if ($this->shmKey) {
            return $this->shmKey;
        }
        return $this->createKey();
    }

    /**
     * If the content begins with the same characters that are in validatestring then this passes.
     * If validatestring hasn't been set then this passes.
     * Uses config (storage.validatestring)
     * @param string $content
     */
    protected function validate($contents)
    {
        if (isset($this->config['validatestring'])) {
            if (strlen($contents) == strlen(stristr($contents, $this->config['validatestring']))) {
                $this->logger->log('RETURNING TRUE!', Zend_Log::INFO);
                return true;
            }
            $this->logger->log('RETURNING FALSE!', Zend_Log::INFO);
            return false;
        }
        $this->logger->log('NO VALIDATESTRING - RETURNING TRUE!', Zend_Log::INFO);
        return true;
    }

    /**
     * Creates a Shared Memory resource and returns the Key.
     * @return string; 
     */
    public function createKey()
    {
        if (!isset($this->config['path'])) {
            throw new Zend_Http_Client_Adapter_Exception('File path not set for data storage (storage.path)');
        }
        if (!isset($this->config['memsize'])) {
            throw new Zend_Http_Client_Adapter_Exception('Memory size not set for data storage (storage.memsize)');
        }
        if (!file_exists($this->config['path'])) {
            touch($this->config['path']);
        }
        $this->shmKey = @shmop_open(ftok($this->config['path'], 'R'), "c", 0644, $this->config['memsize']);
        if (!$this->shmKey) {
            return false;
        } else {
            return $this->shmKey;
        }
    }

    public function write($contents)
    {
        // check Content for closing tag </P>
        // if yes
        // -- extract data from Content up to and including that P tag and leave any remainder in Content
        // -- if there's a buffer then add it to whatever is in the buffer
        // -- search for what type of data (DefAtlas, DefTrafficLight, DefCamera
        // -- add that data to the storage and empty buffer
        // -- is there any remaining data in Content?
        // -- if yes
        // ---- goto 1
        // if no
        // -- add the data to buffer
        // endif
        // Get more Content
        if ($contents != "0") {
            if (($pPos = strpos($contents, '</P>')) !== false) {
                $data = substr($contents, 0, $pPos + 4);
                $this->contentsBuffer .= $data;
                $this->logger->log('About to write to storage: ' . $this->contentsBuffer, Zend_Log::INFO);
                $size = strlen($this->contentsBuffer);
                $shmBytesWritten = shmop_write($this->getKey(), $this->contentsBuffer, 0);
                if ($shmBytesWritten != $size) {
                    return false;
                } else {
                    $this->contentsBuffer = '';
                    if ($remainingData = substr($contents, $pPos + 4)) {
                        $this->logger->log('Remaining data - sending to write()!', Zend_Log::INFO);
                        $this->write($remainingData);
                    }
                    return $shmBytesWritten;
                }
            } else {
                $this->logger->log('No closing p tag so adding to contentsBuffer', Zend_Log::INFO);
                $this->contentsBuffer .= $contents;
            }
        }
        return false;
        
    /*        if ($this->validate($contents)) {
            
            if ($this->config['memcompress'] && function_exists('gzcompress')) {
                $contents = @gzcompress($contents, $this->config['memcompresslvl']);
            }
            
            $contents = str_pad($contents, strlen($contents) * 2);
            $this->logger->log('AFTER PADDING: '.$contents, Zend_Log::INFO);
            $size = strlen($contents);
            
            $shmBytesWritten = shmop_write($this->getKey(), $contents, 0);
            if ($shmBytesWritten != $size) {
                return false;
            } else {
                return $shmBytesWritten;
            }
        }*/
    
    }

    public function read($bytes = null)
    {
        if (is_null($bytes) && isset($this->config['readbytes'])) {
            $bytes = $this->config['readbytes'];
        } else {
            throw new Zend_Http_Client_Adapter_Exception('Readbytes not set for data storage (storage.readbytes)');
        }
        $this->setBytes($bytes);
        $content = @shmop_read($this->getKey(), 0, $this->getBytes());
        if ($this->config['memcompress'] && function_exists('gzuncompress')) {
            $content = @gzuncompress($content);
        }
        if (!$content) {
            return false;
        } else {
            return $content;
        }
    }

    public function exists()
    {
        // return is_writable($this->getPath());
    }

    public function clear()
    {
        if (@shmop_write($this->getKey(), $this->clearData, 0)) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        if (@shmop_delete($this->getKey())) {
            return true;
        }
        return false;
    }

    public function close()
    {
        @shmop_close($this->getKey());
        return true;
    }
}