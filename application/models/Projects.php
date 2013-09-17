<?php
/**
 * Model
 */
class Default_Model_Projects extends Default_Model_Abstract
{

	/**
	 * Database table name
	 * @var string
	 */
	protected $_name = 'projects';


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
	 * Get all visible projects for live section
	 *
	 * @return Zend_Db_Object
	 */
	public function fetchProjects()
	{
		$select = $this->select()
			->from(
				array("projects" => $this->_name),
				array("id", "project_title", "project_slug", "project_thumb")
			)
			->where('is_trashed = ?', 0)
			->where('is_hidden = ?', 0)
			->order('priority ASC');

		// request!
		return $this->fetchAll($select);
	}


	/**
	 * Get project details
	 *
	 * @return Zend_Db_Object
	 */
	public function fetchProject( $slug )
	{
		$select = $this->select();

		$select->from(
				array("projects" => $this->_name),
				array("id", "project_title", "project_slug", "project_thumb", "project_description", "project_date")
			)
			->where('project_slug = ?', $slug)
			->where('is_trashed = ?', 0)
			->where('is_hidden = ?', 0)
			->order('priority ASC');

		// request!
		return $this->fetchRow($select);
	}


	/**
	 * Get project images
	 *
	 * @return Zend_Db_Object
	 */
	public function fetchProjectImages( $slug )
	{
		$select = $this->select();
		$select->setIntegrityCheck(false);

		$select->from(
				array("p" => $this->_name),
				array("id", "project_title", "project_slug", "project_thumb", "project_description", "project_date")
			)
			->join(
				array('pi' => 'project_images'),
				'p.id = pi.project_id',
				array('img_src', 'flash_src', 'vimeo_url', 'iframe_url', 'width', 'height','priority', 'image_type_id'))
			->where('p.project_slug = ?', $slug)
			->where('p.is_trashed = ?', 0)
			->where('p.is_hidden = ?', 0)
			->where('pi.is_hidden = ?', 0)
			->where('pi.is_trashed = ?', 0)
			->order('p.priority ASC');

		// request!
		return $this->fetchAll($select);
	}


	/**
	 * Get all featured projects for home page
	 *
	 * @return Zend_Db_Object
	 */
	public function fetchFeaturedProjects()
	{
		$select = $this->select();
		$select->setIntegrityCheck(false);

		$select->from(
				array("p" => $this->_name),
				array("id", "project_title", "project_slug", "featured_image")
			)
			->where('p.is_trashed = ?', 0)
			->where('p.is_hidden = ?', 0)
			->where('p.is_featured = ?', 1)
			->where('p.featured_image != ?', '')
			->order('p.priority ASC');

		// request!
		return $this->fetchAll($select);
	}



	/**
	 * Count visible projects
	 *
	 * @return Zend_Db_Object
	 */
	public function countVisibleProjects()
	{
		$select = $this->select()
			->from($this, 'COUNT(id) AS projects')
			->where('is_trashed = ?', 0)
			->where('is_hidden = ?', 0);

		// request!
        return intval($this->fetchRow($select)->projects);
	}

}