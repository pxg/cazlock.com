<?php
class Blackflag_Filter_Adapter_Factory
{

    /**
     * Factory to get the right Database Adapter
     * @param string $rawType
     * @return Blackflag_Filter_Adapter
     */
    public static function getAdapter($rawType = null)
    {
        if ($rawType === null) {
            $rawType = 'Zenddb';
        }
        $type = '';
        foreach (explode('-', $rawType) as $part) {
            $type .= ucfirst($part) . '_';
        }
        $type = rtrim($type, '_');
        
        if (class_exists($class = 'Blackflag_Filter_Adapter_' . $type)) {
            $adapter = new $class();
        } else {
            throw new Exception('Blackflag_Filter_Adapter_' . $type . ' does not exist.');
        }
        return $adapter;
    }
}