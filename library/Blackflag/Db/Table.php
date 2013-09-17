<?php
class Blackflag_Db_Table extends Zend_Db_Table_Abstract
{
	/**
	 * Cleans and inserts one record
	 * @param array $insertArr Data array
	 * @return array
	 */
	public function insert(array $insertArr)
	{
		return parent::insert($this->_cleanArray($insertArr));
	}

	/**
	 * Cleans amd updates one record
	 * @param array $updateArr Data array
	 * @param int $idValue Id of record
	 * @return bool
	 */
	public function updateOne($updateArr, $idValue)
	{
		return parent::update(
			$this->_cleanArray($updateArr),
			$this->getAdapter()->quoteInto($this->_getPrimaryKey().' = ?', $idValue)
		);
	}
	
	/**
	 * Deletes one record
	 * @param int $idValue Id of record
	 * @return bool
	 */
	public function deleteOne($idValue)
	{
		return $this->delete(
			$this->getAdapter()->quoteInto($this->_getPrimaryKey().' = ?', $idValue)
		);
	}
	
	/**
	 * Deletes
	 * @param Zend_Db_Select $where Where
	 * @return array
	 */
	function delete($where)
	{
		if (!is_numeric($where) && $this->exists($where)) {
			return parent::delete($where);
		}
	}

	/**
	 * Checks if record exists
	 * @param Zend_Db_Select $where Where
	 * @return array
	 */
	public function exists($where)
	{
		return $this->fetchAll($where)->count() > 0 ? true : false;
	}
	
	/**
	 * Gets last insert id
	 * @return int
	 */
	public function getLastInsertId()
	{
		return $this->getAdapter()->lastInsertId($this->_getName());
	}

	/**
	 * Get select box options
	 * @param string $idField Title of id field
	 * @param string $valueField Title of value field
	 * @param string $sortOn Title of field to order on
	 * @return array
	 */
	public function getOptions($idField = 'id', $valueField = 'title', $sortOn = 'title')
	{
		$select = $this->select()
			->from($this, array($idField, $valueField))
			->order($sortOn . ' ASC');
		$options = $this->getAdapter()->fetchPairs($select);
		return $options;
	}

	/**
	 * Removes all extra fields from data array
	 * @param array $arr Array to clean
	 * @return array
	 */
	protected function _cleanArray($arr) {
		return array_intersect_key($arr, array_combine($this->_getcols(), $this->_getcols()));
	}
	
	/**
	 * Gets primary key
	 * @return string
	 */
	protected function _getPrimaryKey() {
		$primaryField = $this->info('primary');
		return $primaryField[1];
	}
	
	/**
	 * Gets name
	 * @return string
	 */
	protected function _getName() {
		return $this->info('name');
	}
}