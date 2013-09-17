<?php
/**
 * @category   Blackflag
 * @package    Blackflag_Http_Client
 * @subpackage Blackflag_Http_Client_Storage
 * @author     Alex Gemmell <alexgemmell@theredfactory.co.uk>
 */
interface Blackflag_Http_Client_Storage_Interface
{

    /**
     * Returns true if and only if storage exists
     *
     * @throws Zend_Http_Client_Exception If it is impossible to determine whether storage exists
     * @return boolean
     */
    public function exists();

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws Zend_Http_Client_Exception If reading contents from storage is impossible
     * @return mixed
     */
    public function read();

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws Zend_Http_Client_Exception If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents);

    /**
     * Clears contents from storage
     *
     * @throws Zend_Http_Client_Exception If clearing contents from storage is impossible
     * @return void
     */
    public function clear();
}