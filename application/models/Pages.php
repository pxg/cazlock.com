<?php
/**
 * Model
 */
class Default_Model_Pages extends Default_Model_Abstract
{
	/**
	 * Database table name
	 * @var string
	 */
	protected $_name = 'pages';

	/**
	 * Get result by name
	 * @param int $name String
	 * @return Zend_Db_Object
	 */
	public function findByName($name)
	{
		$select = $this->select()
			->from($this)
			->where('name = ?', $name)
			->limit(1);

		return $this->fetchRow($select);
	}
	
	/**
	 * Get results
	 * @return Zend_Db_Object
	 */
	public function fetchNotTrashed()
	{
		$select = $this->select()
			->from($this)
			->where('is_trashed = ?', 0)
			->order('name ASC');

		return $this->fetchAll($select);
	}
}