<?php
/**
 * @author james.turner
 */
class Blackflag_Filter_Slug implements Zend_Filter_Interface
{
    
    /**
     * @var Zend_Db_Table_Abstract|string
     */
    private $_table;
    
    /**
     * @var string
     */
    private $_field;
    
    /**
     * @var string
     */
    private $_separator;
    
    /**
     * @var Blackflag_Filter_Slug_Adapter
     */
    protected $_adapter;

    public function __construct(array $options = array())
    {
        
        if (!isset($options['table'])) {
            $options['table'] = '';
        }
        if (!isset($options['field'])) {
            $options['field'] = 'slug';
        }
        if (!isset($options['separator'])) {
            $options['separator'] = '-';
        }
        if (!isset($options['adapter'])) {
        	$options['adapter'] = null;
        }
        
        $this->_table = $options['table'];
        $this->_field = $options['field'];
        $this->_separator = $options['separator'];
        $this->_loadAdapter($options['adapter'], $this->_table);
    
    }

    protected function _loadAdapter($adapter = null, $tableName = null)
    {
        if ($tableName) {
            $this->_adapter = Blackflag_Filter_Adapter_Factory::getAdapter($adapter);
            $this->_adapter->setTable($tableName);
        }
    }

    protected function _getAdapter()
    {
        if (isset($this->_adapter) && $this->_adapter instanceof Blackflag_Filter_Adapter) {
            return $this->_adapter;
        }
        return false;
    }

    public function filter($phrase)
    {
        $strToLower = new Zend_Filter_StringToLower();
        $strToLower->setEncoding('UTF-8');
        $inflector = new Zend_Filter_Inflector(':phrase');
        $inflector->addRules(array(
            ':phrase' => array(
                // @todo 'and' requires translation.
                new Zend_Filter_PregReplace('#&#', 'and'),  // Turn ampersands into "and".
                // It appears that performing this causes the unicode to fail.
                //new Zend_Filter_Word_SeparatorToSeparator($this->_separator, ' '), // Reverse already placed separators.
                new Zend_Filter_Alnum(true),  // Allow whitespace but trim everything which isn't an alphanumeric.
                new Zend_Filter_Word_SeparatorToSeparator(' ', $this->_separator),  // Turn all space separators into chosen separator.
                $strToLower // Make everything lower case.
            )
        ));
        
        $phrase = $inflector->filter(array(
            ':phrase' => $phrase
        ));
        
        // If $this->_table is 0 or '' or null then just return the phrase.
        if (!$this->_table) {
            return $phrase;
        }
        
        return $this->_getAdapter()->getSlug($phrase, $this->_separator, $this->_field);
    }

}