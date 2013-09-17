<?php

require_once 'Zend/Http/Client/Adapter/Exception.php';

/**
 * Internal Memory Storage class
 * @category   Blackflag
 * @package    Blackflag_Http_Client
 * @subpackage Blackflag_Http_Client_Storage
 * @author     Alex Gemmell <alexgemmell@theredfactory.co.uk>
 */
class Blackflag_Http_Client_Storage_Internal implements Blackflag_Http_Client_Storage_Interface
{
    
    protected $_logger;
    
    /**
     * Temporary buffer used to store incomplete data packets
     * @var string - unicode string
     */
    protected $contentsBuffer = '';
    
    /**
     * Data chuncks ready to be sent to the publisher 
     * @var array
     */
    protected $dataPackage = array();

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
        
        if (!isset($this->config['terminating_string'])) {
            throw new Exception('Terminating string not set in config (terminating_string)');
        }
        
        if (!isset($this->config['filter_types'])) {
            throw new Exception('Filter types not set in config (filter_types)');
        }
        $this->config['type_filters'] = explode(' ', $this->config['filter_types']);
        
        if (isset($this->config['log']['enabled']) && $this->config['log']['enabled']) {
            $this->setupLogger();
        }
    }

    protected function setupLogger()
    {
        $this->_logger = new Zend_Log();
        $this->_writer = new Zend_Log_Writer_Stream('sample.log');
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

    /**
     * Filters $content for desired data and adds it to dataPackage array
     * @param string $content
     */
    protected function addToDataPackage($content)
    {
        if (preg_match('/(?:Params,)(\w+?),/im', $content, $matches)) {
            if (in_array($matches[1], $this->config['type_filters'])) {
                $this->dataPackage[] = array(
                    'type' => $matches[1], 
                    'content' => $content
                );
                if ($matches[1] == 'DefCamera') {
                    $content = '<<Image data removed for brevity>>';
                }
                $this->log("\n".'Adding to dataPackage:' . $content."\n", Zend_Log::INFO);
            }
        }
    }

    /**
     * Returns the contents of dataPackage and clears it.
     * @return string
     */
    protected function flushDataPackage()
    {
        $this->log('Flushing dataPackage', Zend_Log::INFO);
        if ($this->dataPackage) {
            $dataPackage = $this->dataPackage;
            $this->dataPackage = array();
            return $dataPackage;
        }
        return false;
    }

    /**
     * Returns the contents of dataPackage
     * @return string|false
     */
    protected function getDataPackage()
    {
        if ($this->dataPackage) {
            return $this->dataPackage;
        }
        return false;
    }

    /**
     * Appends $content to contentsBuffer string
     * @param string $content
     */
    protected function addToContentsBuffer($content)
    {
        $this->log('Appending content to contentsBuffer', Zend_Log::INFO);
        $this->contentsBuffer .= $content;
    }

    /**
     * Returns the contents of contentsBuffer and clears it.
     * @return string
     */
    protected function flushContentsBuffer()
    {
        $this->log('Flushing contentsBuffer', Zend_Log::INFO);
        $contentsBuffer = $this->contentsBuffer;
        $this->contentsBuffer = '';
        return $contentsBuffer;
    }

    /**
     * Writes $contents to the dataPackage or contentBuffer as necessary 
     * @param string $contents
     * @return void
     */
    public function write($contents)
    {
        $this->log('Begining writing content', Zend_Log::INFO);
        if ($contents != "0") {
            if (($pPos = strpos($contents, $this->config['terminating_string'])) !== false) {
                $this->log('Found terminating string', Zend_Log::INFO);
                $this->addToContentsBuffer(substr($contents, 0, $pPos + strlen($this->config['terminating_string'])));
                $this->addToDataPackage($this->flushContentsBuffer());
                if ($remainingData = substr($contents, $pPos + strlen($this->config['terminating_string']))) {
                    $this->write($remainingData);
                }
            } else {
                $this->log('No terminating string found', Zend_Log::INFO);
                $this->addToContentsBuffer($contents);
            }
        }
    }

    /**
     * Returns the contents of the dataPackage
     * @param boolean $flush
     * @return string|false
     */
    public function read($flush = false)
    {
        if ($flush) {
            return $this->flushDataPackage();
        }
        return $this->getDataPackage();
    }

    /**
     * Check if the dataPackage exists and contains data
     * @return boolean
     */
    public function exists()
    {
        if (isset($this->dataPackage) && count($this->dataPackage) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Wipes the contents of the dataPackage
     * @return void
     */
    public function clear()
    {
        $this->flushDataPackage();
    }

}