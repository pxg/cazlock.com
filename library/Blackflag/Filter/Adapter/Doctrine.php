<?php
class Blackflag_Filter_Adapter_Doctrine extends Blackflag_Filter_Adapter
{

    /**
     * Returns a table
     * @param $tableName
     */
    public function setTable($tableName)
    {
        if (is_string($tableName) && strlen($tableName)) {
            $this->_table = $tableName;
        } else {
            throw new Zend_Exception("Cannot utilise table '" . (string) $tableName . "' for slug filtration.", 500);
        }
    }

    /**
     * Returns a unique slug
     * @param string $phrase
     * @param string $separator
     * @param string $field
     * @return string
     */
    public function getSlug($phrase, $separator, $field)
    {
        $identity = 0;
        // Perform DB lookup to determine if this is a unique slug.
        while (true) {
            $q = Doctrine_Query::create()->from($this->_table)->where($field . ' = ?', $phrase . (($identity) ? $separator . $identity : ""));
            $exists = $q->fetchOne(array(), Doctrine::HYDRATE_RECORD);
            
            if (!$exists) {
                break;
            }
            $identity++;
        }
        return $phrase . (($identity) ? $separator . $identity : "");
    }
}