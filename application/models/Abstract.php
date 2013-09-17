<?php
/**
 * Model
 */
abstract class Default_Model_Abstract extends Blackflag_Db_Table
{
	/**
	 * Database table name
	 * @var string
	 */
	protected $_name;

	/**
	 * The row class
	 * @var string
	 */
	protected $_rowClass = 'Blackflag_Db_Table_Row';

	/**
	 * Get result by id
	 * @param int $id Id
	 * @return Zend_Db_Object
	 */
	public function findById($id)
	{
		$select = $this->select()
			->from($this)
			->where('id = ?', $id);
			
		return $this->fetchRow($select);
	}
	
	/**
	 * Get results by next priority
	 * @param int $priority Current priority
	 * @return Zend_Db_Object
	 */
	public function findNextByPriority($priority)
	{
		$select = $this->select()
			->from($this)
			->where('priority > ?', $priority)
			->where('is_published = ?', 1)
			->order('priority ASC')
			->limit(1);

		$result = $this->fetchRow($select);

		// no result, then start at priority 0
		if (count($result) == 0) {
			$select->reset( Zend_Db_Select::WHERE )
			->where('is_published = ?', 1);
				
			return $this->fetchRow($select);
		} else {
			return $result;
		}
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
			->order('priority ASC');

		return $this->fetchAll($select);
	}
	
	/**
	 * Get results
	 * @return Zend_Db_Object
	 */
	public function fetchTrashed()
	{
		$select = $this->select()
			->from($this)
			->where('is_trashed = ?', 1)
			->order('id DESC');

		return $this->fetchAll($select);
	}
	
	/**
	 * Get results
	 * @return Zend_Db_Object
	 */
	public function fetchNotTrashedAndPublished()
	{
		$select = $this->select()
			->from($this)
			->where('is_trashed = ?', 0)
			->where('is_published = ?', 1)
			->order('priority ASC');

		return $this->fetchAll($select);
	}
			
	/**
	 * Get paginator
	 * @param Zend_Db_Table_Select $select
	 * @param int $page Page
	 * @return Zend_Paginator
	 */
	protected function _getPaginator($select, $page = 1, $itemCount = null)
	{
		
		if (!isset($itemCount)) {
			$itemCount = 50;
		}

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($select));
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage($itemCount);
			
		// return the result
		return $paginator;
	}
	
	
	/**
	 * Create hash
	 * @return string
	 */
	protected function createHash($id)
	{
		$config = Zend_Registry::get('configuration');
		$salt = $config->app->salt;
		$hash = sha1($salt . $id);
		return $hash;
	}
}