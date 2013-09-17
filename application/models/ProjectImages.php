<?php
/**
 * Model
 */
class Default_Model_ProjectImages extends Default_Model_Abstract
{

	/**
	 * Database table name
	 * @var string
	 */
	protected $_name = 'project_images';
	

	/**
	 * Admin Status Filters available for the admin section
	 * @var array
	 */
	protected $adminStatusFilters = array(
		array(
			'display_name' => 'Visible Only',
			'search_field' => 'is_hidden',
			'search_value' => 0
		),
		array(
			'display_name' => 'Hidden',
			'search_field' => 'is_hidden',
			'search_value' => 1
		),
	);
	
	
	/**
	 * Admin Date Filter field available for the admin section
	 * @var string
	 */
	protected $adminDateFilterField = 'creation_date';
	
	
	/*
	 * 
	 */
	public function init() {
	}

	
	
	
	/**
	 * Return available admin status filters
	 * @return array
	 */
	public function getAdminStatusFilters() {
		return $this->adminStatusFilters;
	}


	/**
	 * Return available admin date filter field
	 * @return string
	 */
	public function getAdminDateFilterField() {
		return $this->adminDateFilterField;
	}
	
	

	/**
	 * Get results for admin section
	 * @return Zend_Db_Object
	 */
	public function fetchNotTrashed( $page = null, $adminStatusFilter = null , $adminDateFilterStart = null , $adminDateFilterEnd = null , $searchTerm = null )
	{
		$select = $this->select()
			->from($this)
			->where('is_trashed = ?', 0)
			->order('priority ASC');
			// ->order(new Zend_Db_Expr('RAND()'));
			
		// condition : apply a status filter?
		if (isset($adminStatusFilter)) {
			$select->where($adminStatusFilter['search_field'] . '= ?', $adminStatusFilter['search_value']);
		}
		
		// condition : apply a date filter?
		if (isset($adminDateFilterStart)) {
			$relativeDate = strtotime(date($adminDateFilterStart." 00:00:00"));
			$select->where($this->adminDateFilterField . ' > ?', $relativeDate);
		}
		if (isset($adminDateFilterEnd)) {
			$relativeDate = strtotime(date($adminDateFilterEnd." 23:59:59"));
			$select->where($this->adminDateFilterField . ' < ?', $relativeDate);
		}
		
		// condition : search term specified?
		if (isset($searchTerm)) {
			$select
			->where('project_title LIKE ?', '%'.$searchTerm.'%')
			->orWhere('project_description LIKE ?', '%'.$searchTerm.'%');
		}
			
		if (isset($page)) {
			return $this->_getPaginator($select, $page);
		} else {
			return $this->fetchAll($select);
		}
	}
	
	
	
	/**
	 * Get results
	 * @return Zend_Db_Object
	 */
	public function fetchNotTrashedByParentId($parent_id)
	{
		$select = $this->select()
			->setIntegrityCheck(false)
			->from($this)
			->where('is_trashed = ?', 0)
			->where('project_id = ?', $parent_id)
			->order('priority ASC');

		return $this->fetchAll($select);
	}
}