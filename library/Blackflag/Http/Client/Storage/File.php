<?php

require_once 'Zend/Http/Client/Adapter/Exception.php';

/**
 * File Storage class
 * @category   Blackflag
 * @package    Blackflag_Http_Client
 * @subpackage Blackflag_Http_Client_Storage
 * @author     Alex Gemmell <alexgemmell@theredfactory.co.uk>
 */
class Blackflag_Http_Client_Storage_File implements Blackflag_Http_Client_Storage_Interface
{
    
    protected $handle;
    protected $bytes;

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
        
        $this->logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream('sample.log');
        $this->logger->addWriter($writer);
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
     * If the content begins with the same characters that are in validatestring then this passes.
     * If validatestring hasn't been set then this passes.
     * Uses config (storage.validatestring)
     * @param string $content
     */
    protected function validate($contents)
    {
        $this->logger->log('BEFORE VALIDATE: ' . $contents, Zend_Log::INFO);
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

    public function write($contents)
    {
        
        if ($contents != "0") {
            if (($pPos = mb_strpos($contents, '</P>')) !== false) {
                if (!isset($this->config['path'])) {
                    throw new Zend_Http_Client_Adapter_Exception('File path not set for data storage (storage.path)');
                }
                if (!$this->handle = fopen($this->config['path'], 'a+b')) {
                    throw new Zend_Http_Client_Adapter_Exception("Cannot open file ({$this->config['path']})");
                }
                if ($this->exists()) {
                    
                    $data = mb_substr($contents, 0, $pPos + 4);
                    $this->contentsBuffer .= $data;
                    $this->logger->log('About to write to storage: ' . $this->contentsBuffer, Zend_Log::INFO);
                    
                    if (fwrite($this->handle, $this->contentsBuffer) === FALSE) {
                        throw new Zend_Http_Client_Adapter_Exception("Cannot write to file ({$this->config['path']})");
                    }
                    
                    $this->contentsBuffer = '';
                    if ($remainingData = substr($contents, $pPos + 4)) {
                        $this->logger->log('Remaining data - sending to write()!', Zend_Log::INFO);
                        $this->write($remainingData);
                    }
                    $this->close();
                }
            } else {
                $this->logger->log('No closing p tag so adding to contentsBuffer', Zend_Log::INFO);
                $this->contentsBuffer .= $contents;
            }
        }
        return false;
        
    /*		if ($this->validate ( $contents )) {
			if (! isset ( $this->config ['path'] )) {
				throw new Zend_Http_Client_Adapter_Exception ( 'File path not set for data storage (storage.path)' );
			}
			if (! $this->handle = fopen ( $this->config ['path'], 'w+b' )) {
				throw new Zend_Http_Client_Adapter_Exception ( "Cannot open file ({$this->config['path']})" );
			}
			if ($this->exists ()) {
				if (fwrite ( $this->handle, $contents ) === FALSE) {
					throw new Zend_Http_Client_Adapter_Exception ( "Cannot write to file ({$this->config['path']})" );
				}
			}
			$this->close ();
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
        
        if (!$this->handle = fopen($this->config['path'], 'rb')) {
            throw new Zend_Http_Client_Adapter_Exception("Cannot open file ({$this->config['path']})");
        }
        
        return fread($this->handle, $this->getBytes());
    }

    public function exists()
    {
        return is_writable($this->config['path']);
    }

    public function clear()
    {
    
    }

    public function close()
    {
        fclose($this->handle);
    }
}