<?php
class Blackflag_Filter_Adapter_Zenddb extends Blackflag_Filter_Adapter
{

    /**
     * Returns a table
     * @param $tableName
     */
    public function setTable($tableName)
    {
        if (is_string($tableName) AND strlen($tableName)) {
            $table = new Zend_Db_Table($tableName);
        }
        if ($table instanceof Zend_Db_Table_Abstract) {
            $this->_table = $table;
        } else {
            throw new Zend_Exception("Cannot utilise table '" . (string) $tableName . "' for slug filtration.", 500);
        }
    }

    /**
     * Returns a unique slug
     * @param string $phrase
     * @param string $separator
     * @return string
     */
    public function getSlug($phrase, $separator, $field)
    {
        $identity = 0;
        // Perform DB lookup to determine if this is a unique slug.
        while (true) {
            $select = $this->_table->select()->//->from($this->_name, new Zend_Db_Expr('COUNT(*) "count"'))
            where($field . ' = ?', $phrase . (($identity) ? $separator . $identity : ""));
            
            // Will return null if no row with this slug exists.
            $exists = $this->_table->fetchRow($select);
            
            if (!$exists) {
                break;
            }
            $identity++;
        }
        return $phrase . (($identity) ? $separator . $identity : "");
    }
}