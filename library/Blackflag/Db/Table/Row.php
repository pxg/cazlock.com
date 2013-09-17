<?php
class Blackflag_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{
	protected $_dataTypes = array(
		'bit' => 'int',
		'tinyint' => 'int',
		'bool' => 'bool',
		'boolean' => 'bool',
		'smallint' => 'int',
		'mediumint' => 'int',
		'int' => 'int',
		'integer' => 'int',
		'bigint' => 'float',
		'serial' => 'int',
		'float' => 'float',
		'real' => 'float',
		'numeric' => 'float',
		'money' => 'float',
		'double' => 'float',
		'double precision' => 'float',
		'decimal' => 'float',
		'dec' => 'float',
		'fixed' => 'float',
		'year' => 'int'
	);
	
	/**
	* Initialize object.  Casts values to correct php type.
	*
	* Called from {@link __construct()} as final step of object instantiation.
	*
	* @return void
	*/
	public function init() {
		$table = $this->getTable();
		if ($table) {
			$cols = $table->info(Zend_Db_Table_Abstract::METADATA);
			foreach ($cols as $name => $col) {
				$dataType = strtolower($col['DATA_TYPE']);
				if (array_key_exists($dataType, $this->_dataTypes)) {
					settype($this->_data[$name], $this->_dataTypes[$dataType]);
				}
			}
		}
	}
	
	/**
	 * Camelcase the column names
	 * 
	 * @param $columnName string The column name
	 * @return string
	 */	
	protected function _transformColumn($columnName)
	{ 	
		$inflector = new Zend_Filter_Inflector(':column');
		$inflector->setRules(array(
			':column'  => array(
				'Word_CamelCaseToUnderscore', 
				'StringToLower'
			),
		));
		$nativeColumnName = $inflector->filter(array('column' => $columnName));
		return $nativeColumnName;
	}
	
	/**
	 * Convert to array object
	 * @return object
	 */
	public function toArrayObject()
	{
		return new ArrayObject(parent::$this->toArray(), ArrayObject::ARRAY_AS_PROPS);
	}
}