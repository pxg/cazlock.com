<?php
/**
 * Based on Zend_Http_Client but striped down to for raw TCP connections.
 * To be used with the Blackflag_Http_Client_Adapter_Mclaren 
 * @author Alex Gemmell
 * @category   Blackflag
 * @package    Blackflag_Http
 * @subpackage Client
 */
class Blackflag_Http_Client_Raw
{
    /**
     * Configuration array, set using the constructor or using ::setConfig()
     *
     * @var array
     */
    protected $config = array(
        'maxredirects' => 0, 
        'useragent' => 'Blackflag_Http_Client_Raw', 
        'timeout' => 0, 
        'adapter' => 'Blackflag_Http_Client_Adapter_Mclaren', 
        'keepalive' => false, 
        'storeresponse' => true, 
        'strict' => true
    );
    
    /**
     * The adapter used to preform the actual connection to the server
     *
     * @var Blackflag_Http_Client_Adapter_Mclaren
     */
    protected $adapter = null;

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
            /** @see Zend_Http_Client_Exception */
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('Array or Zend_Config object expected, got ' . gettype($config));
        }
        
        foreach ($config as $k => $v) {
            $this->config[strtolower($k)] = $v;
        }
        
        if (isset($this->config['adapter']) && !$this->adapter) {
            $this->setAdapter($this->config['adapter']);
        }
        
        // Pass configuration options to the adapter if it exists
        if ($this->adapter instanceof Zend_Http_Client_Adapter_Interface) {
            $this->adapter->setConfig($config);
        }
        
        return $this;
    }

    /**
     * Load the connection adapter
     *
     * While this method is not called more than one for a client, it is
     * seperated from ->request() to preserve logic and readability
     *
     * @param Zend_Http_Client_Adapter_Interface|string $adapter
     * @return null
     * @throws Zend_Http_Client_Exception
     */
    public function setAdapter($adapter)
    {
        if (is_string($adapter)) {
            if (!class_exists($adapter)) {
                try {
                    require_once 'Zend/Loader.php';
                    Zend_Loader::loadClass($adapter);
                } catch (Zend_Exception $e) {
                    /** @see Zend_Http_Client_Exception */
                    require_once 'Zend/Http/Client/Exception.php';
                    throw new Zend_Http_Client_Exception("Unable to load adapter '$adapter': {$e->getMessage()}");
                }
            }
            
            $adapter = new $adapter();
        }
        
        if (!$adapter instanceof Zend_Http_Client_Adapter_Interface) {
            /** @see Zend_Http_Client_Exception */
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('Passed adapter is not a HTTP connection adapter');
        }
        
        $this->adapter = $adapter;
        $config = $this->config;
        unset($config['adapter']);
        $this->adapter->setConfig($config);
    }

    /**
     * 
     */
    public function request()
    {
        $this->adapter->connect($this->config['host'], $this->config['port']);
        $this->adapter->startStream();
    }

    public function read()
    {
        return $this->adapter->read();
    }

    public function writeStore($data)
    {
        return $this->adapter->getStorage()->write($data);
    }

    public function readStore($flush = null)
    {
        return $this->adapter->getStorage()->read($flush);
    }

    public function close()
    {
        return $this->adapter->close();
    }

    public function getSocket()
    {
        return $this->adapter->getSocket();
    }

}
?>