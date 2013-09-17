<?php
/**
 * 
 * @author alex.gemmell
 */
abstract class Blackflag_Filter_Adapter
{
    
    protected $_table;

    public function __contruct($tableName = null)
    {
        if ($tableName) {
            $this->setTable($tableName);
        }
    }

    /**
     * Sets the Adapter's table to be used in subsequent queries.
     * @param $tableName
     */
    public function setTable($tableName)
    {
        // implement in child classes
    }

    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Returns a unique slug
     * @param string $phrase
     * @param string $separator
     * @return string
     */
    abstract public function getSlug($phrase, $separator, $field);

}