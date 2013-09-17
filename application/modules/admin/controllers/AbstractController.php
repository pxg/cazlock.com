<?php

class Admin_AbstractController extends Zend_Controller_Action
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = '';
	
	/**
	 * Add form
	 * @var string
	 */
	protected $_addForm = '';
	
	/**
	 * Edit form
	 * @var string
	 */
	protected $_editForm = '';
	
	/**
	 * Standard uploads
	 * @var array
	 */
	protected $_standardUploads = array();
	
	/**
	 * Is exportable as CSV?
	 * @var bool
	 */
	protected $exportable = false;
	
	/**
	 * Index action (handles list and edit)
	 * 
	 * @return void
	 */	
	public function init()
	{
		$this->view->assign(array (
			'controllerName' => $this->getRequest()->getControllerName(),
		));
	}

	/**
	 * Index action (handles list and edit)
	 * 
	 * @return void
	 */	
	public function indexAction()
	{
		
		// condition: first time page load
		if (!$this->getRequest()->isPost()) {

			// condition: edit 
			if ($this->_hasParam('id') && !$this->_hasParam('wasTrashed')) {
				
				// grab data
				$model = new $this->_primaryModel;
				$row = $model->find($this->_getParam('id'))->current();
				
				// populate and return form
				$form = new $this->_editForm;  
				$form->populate($row->toArray());
				$this->view->isEditForm = true;
				
			// condition: new
			} else {
				
				$form = new $this->_addForm; 
			}
		
		// condition: posted
		} else {
			
			// get post
			$post = $this->getRequest()->getPost();

			// condition: edit or add
			if (array_key_exists('id', $post)) {
				$form = new $this->_editForm;
				$this->view->isEditForm = true;  
			} else {
				$form = new $this->_addForm; 		 		
			}
			
			// condition: not valid
			if (!$form->isValid($post)) {	
	
				// load existing values into model (required for uploads)
				if ($form instanceof $this->_editForm) {
					$model = new $this->_primaryModel;
					$row = $model->find($post['id'])->current();
					$values = $this->_prepareValuesForForm(array(), $row);
					$form->populate($values);
				}	
				
				// set status
				$this->view->failedValidation = true;
			
			// condition: valid - process form
			} else {
				
				// specific code to process uploads
				$values = $this->_processUploads($form, $post);
						
				// get model		
				$model = new $this->_primaryModel;
				
				// load existing values into model
				if ($form instanceof $this->_editForm) {
					$row = $model->find($values['id'])->current();
					$row->setFromArray($values);
				} else {
					$row = $model->createRow($values);
				}

				$values['id'] = $row->save();
				
				// prepare values for form (required for uploads)
				$values = $this->_prepareValuesForForm($values, $row);
				
				$this->logInteraction($this->_primaryModel, $values['id'], '[UPDATE]', 1);

				// always return edit form
				$form = new $this->_editForm;				
				$form->populate($values);

				// set status
				$this->view->wasSaved = true;
			}
		}

		// allow overloading of item fetching
		if (method_exists($this, '_getItems')) {
			$this->view->assign(array (
				'form' => $form
			));
			$this->_getItems();
		} else {
		
			// start the model to find available options
			$model = new $this->_primaryModel;


			// find which admin status filters (if any) are available
			if (method_exists($model, 'getAdminStatusFilters')) {
				$adminStatusFilters = $model->getAdminStatusFilters();
			} else {
				$adminStatusFilters = array();
			}
		
			// find if an admin date filter field is available
			if (method_exists($model, 'getAdminDateFilterField')) {
				$adminDateFilterField = $model->getAdminDateFilterField();
			} else {
				$adminDateFilterField = null;
			}
		
			// find which admin source filters (if any) are available
			if (method_exists($model, 'getAdminSourceFilters')) {
				$adminSourceFilters = $model->getAdminSourceFilters();
			} else {
				$adminSourceFilters = array();
			}
		
			// find which admin source filters (if any) are available
			if (method_exists($model, 'getAdminLocationFilters')) {
				$adminLocationFilters = $model->getAdminLocationFilters();
			} else {
				$adminLocationFilters = array();
			}
		
		
			// find if any filters have been set
			$params = $this->getRequest()->getParams();


			// condition : status filter set?
			if (isset($params['filter-status']) && !empty($params['filter-status'])) {
			
				// check available filters
				foreach ($adminStatusFilters as $filter => $method) {
					if ($method['search_field'].'|'.$method['search_value'] == $params['filter-status']) {
				
						// set the status filter
						$adminStatusFilter = $method;
					}
				}
			}
			
			// condition : if no match found, use default method
			if (!isset($adminStatusFilter)) {
				$adminStatusFilter = null;
			}

		
			// condition : is a source set?
			if (isset($params['filter-source']) && !empty($params['filter-source'])) {
				// check available filters
				foreach ($adminSourceFilters as $filter => $sourceType) {
					foreach($sourceType as $key => $source) {
						if ($source['search_field'].'|'.$source['search_value'] == $params['filter-source']) {

							// set the source filter
							$adminSourceFilter = $source;
						}	
					}
				}
			}
		
			// condition : if no match found, use default
			if (!isset($adminSourceFilter)) {
				$adminSourceFilter = null;
			}
				
			// condition : is a location set?
			if (isset($params['filter-location']) && !empty($params['filter-location'])) {
				// check available filters
				foreach ($adminLocationFilters as $type => $location) {
					if ($location['application_location'] == $params['filter-location']) {
						// set the filter
						$adminLocationFilter = $location['application_location'];
					}
				}
			}
		
			// condition : if no match found, use default
			if (!isset($adminLocationFilter)) {
				$adminLocationFilter = null;
			}
		
		
			// condition : is a date range set?
			if (isset($params['filter-date-start']) && !empty($params['filter-date-start'])) {
				$adminDateFilterStart = $params['filter-date-start'];
			}
			if (isset($params['filter-date-end']) && !empty($params['filter-date-end'])) {
				$adminDateFilterEnd = $params['filter-date-end'];
			}
		
			// condition : if no match found, use default
			if (!isset($adminDateFilterStart)) {
				$adminDateFilterStart = null;
			}
			if (!isset($adminDateFilterEnd)) {
				$adminDateFilterEnd = null;
			}
		
		
			// condition : is a search term set?
			if (isset($params['q']) && !empty($params['q'])) {
				// set the search term
				$searchTerm = $params['q'];
			} else {
				$searchTerm = null;
			}
		
		
			// condition : is a page set?
			if (isset($params['page'])) {
				$page = $params['page'];
			} else {
				$page = 1;
			}

			// fetch items
			$items = $model->fetchNotTrashed($page, $adminStatusFilter, $adminDateFilterStart, $adminDateFilterEnd, $adminSourceFilter, $adminLocationFilter, $searchTerm);
	
					
			// send content to view
			$this->view->assign(array (
				'items' => $items,
				'form' => $form,
				'adminStatusFilter' => $adminStatusFilter,
				'adminStatusFilters' => $adminStatusFilters,
				'adminDateFilterField' => $adminDateFilterField,
				'adminDateFilterStart' => $adminDateFilterStart,
				'adminDateFilterEnd' => $adminDateFilterEnd,
				'adminSourceFilter' => $adminSourceFilter,
				'adminSourceFilters' => $adminSourceFilters,
				'adminLocationFilter' => $adminLocationFilter,
				'adminLocationFilters' => $adminLocationFilters,
				'page' => $page,
				'searchTerm' => $searchTerm
			));
		}
	}



	/**
	 * Export action (creates a .csv)
	 * 
	 * @return void;
	 */
	public function exportAction()
	{
		// start the model to find available options
		$model = new $this->_primaryModel;
		
		// fetch items
		$items = $model->fetchNotTrashed()->toArray();
						
		// fetch field names
		$fields = $model->info(Zend_Db_Table_Abstract::COLS);
				
		// construct filename
		$filename = str_replace("Default_Model_", "", $this->_primaryModel);
		$filename .= date("_Y-m-d_H-i");
		$filename .= ".csv";
		
		// create file
		$file = APPLICATION_PATH . '/data/exports/'.$filename;
		$fp = fopen($file, 'w');
		chmod($file, 0755);
				
		// add field names to start of file
		fputcsv($fp, $fields);		
		
		// loop through results
		foreach ($items as $key => $values) {
			fputcsv($fp, $values);
		}

		
		// Both layout and view renderer should be disabled
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);

		// no need for the layout
		$this->_helper->layout->disableLayout();
		$this->_response
			->setHeader('Content-Type', 'text/plain; charset=utf-8')
			->setHeader("Expires"," Mon, 26 Jul 1997 05:00:00 GMT")
			->setHeader("Last-Modified", gmdate("D,d M YH:i:s") . " GMT")
			->setHeader("Cache-Control","no-cache, must-revalidate")
			->setHeader("Pragma","no-cache")
			->setHeader("Content-type","text/plain")
			->setHeader("Content-Disposition","attachment; filename=\"" . $filename . "\"" )
			->setHeader("Content-Description","PHP/INTERBASE Generated Data");
	    readfile($file);
	}	


	/**
	 * Trash action (moves to trash)
	 * 
	 * @return void;
	 */
	public function trashAction()
	{
		// trash
		if ($this->_hasParam('id')) {
			$model = new $this->_primaryModel;
			$row = $model->find($this->_getParam('id'))->current();
			$row->isTrashed = 1;
			$row->save();
			$this->view->wasTrashed = true;
		}
		
		$this->logInteraction($this->_primaryModel, $this->_getParam('id'), 'is_trashed', true);

		$this->_forward('index', null, null, array('wasTrashed' => true));
	}
	

	/**
	 * Update order (via ajax)
	 * 
	 * @return void;
	 */
	public function updatePrioritiesAction()
	{
		// get ajax data
		$ids = $this->_getParam('item');

		// foreach id, loop through and update priority
		foreach ($ids as $priority => $id) {

			$model = new $this->_primaryModel;
			$row = $model->find($id)->current();
			$row->priority = $priority;
			$row->save();
			
			//$this->logInteraction($this->_primaryModel, $id, 'priority', $priority);
		}

		// use json helper to display view render
		$this->_helper->json(Zend_Json::encode(true));
	}
	
	
	
	/**
	 * Update a field (via ajax)
	 * 
	 * @return void;
	 */
	public function testAction()
	{
		$this->updateFieldAction();
	}

	/**
	 * Update a field (via ajax)
	 * 
	 * @return void;
	 */
	public function updateFieldAction()
	{
		// get ajax data
		$field = $this->_getParam('field');
		$id = intval($this->_getParam('id'));
		$value = $this->_getParam('value');
		
		// condition : if bool, translate
		if ($value == "true" || $value == "false") {
			$value = ('true' == $value) ? 1 : 0;
		}

		$model = new $this->_primaryModel;
		$row = $model->find($id)->current();
		$row->$field = $value;
		$row->save();
		
		$this->logInteraction($this->_primaryModel, $id, $field, $value);
		
		// condition : if we've just shown an idea, send a confirmation email
		if ($this->_primaryModel == 'Default_Model_Ideas' && $field == 'is_hidden' && $value == 0) {
			$model->sendVisibleIdeaEmail($id);
		}
		

		// use json helper to display view render
		$this->_helper->json(Zend_Json::encode(array(
			'value' => $value,
			'id' => $id,
			'field' => $field,
			'success' => true
		)));
	}
	
	
	/**
	 * Update a bookmark (via ajax)
	 * 
	 * @return void;
	 */
	public function updateBookmarkAction()
	{
		// get ajax data
		$field = $this->_getParam('field');
		$id = intval($this->_getParam('id'));
		$value = $this->_getParam('value');
		
		// condition : if bool, translate
		if ($value == "true" || $value == "false") {
			$value = ('true' == $value) ? 1 : 0;
		}

		$model = new $this->_primaryModel;
		
		// remove all bookmarks
		$model->update(array("is_bookmarked" => 0), 'id != "'.$id.'"');
		
		// add current bookmark
		$row = $model->find($id)->current();
		$row->$field = $value;
		$row->save();
		
		$this->logInteraction($this->_primaryModel, $id, $field, $value);

		// use json helper to display view render
		$this->_helper->json(Zend_Json::encode(array(
			'value' => $value,
			'id' => $id,
			'field' => $field,
			'success' => true
		)));
	}
	
	
	/**
	 * Show log?
	 * 
	 * @return void;
	 */
	public function logAction()
	{
		$zlog = @file_get_contents(APPLICATION_PATH . '/data/logs/admin.log');

		// Both layout and view renderer should be disabled
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);

		// no need for the layout
		$this->_helper->layout->disableLayout();
		$this->_response->setHeader('Content-Type', 'application/json; charset=utf-8')->setBody($zlog);
	}
	
	
	
	/**
	 * Process uploads (overload if needed)
	 *
	 * @param Zend_Form $form
	 * @param array $post
	 * @return array;
	 */	
	protected function _processUploads($form, $post)
	{ 			
		// get config
		$config = Zend_Registry::get('configuration');	
		
		// get values (uploads)
		$values = $form->getValues();	
		
		// process each standard upload
		foreach ($this->_standardUploads as $fieldName => $preferredName) {
			
			// image
			if ($form->$fieldName->isUploaded()) {
				$source = $form->$fieldName->getFileName($fieldName, true);
				$destination = $this->_helper->upload->getUniqueFilename(
					$source, 
					$preferredName,
					$values
				);	
				rename($source, $destination);
				$values[$fieldName] = basename($destination);				
			} else if (isset($post[$fieldName.'-delete']) && 1 == $post[$fieldName.'-delete']) {
				$values[$fieldName] = null;
			} else {
				unset($values[$fieldName]);
			}
		}
		
		return $values;
	}
	
	/**
	 * Gets an extra values for the form from the model (overload if needed)
	 * 
	 * @param array $values
	 * @param Default_Model_* $model
	 * @return array;
	 */	
	protected function _prepareValuesForForm($values, $row)
	{ 
		foreach ($this->_standardUploads as $fieldName => $preferredName) {
			$values[$fieldName] = $row->$fieldName;	
		}
		return $values;	
	}
	
	
	/**
	 * Log updates
	 * 
	* @param string $type The model
	* @param string $id The row id
	 * @param string $type The field name
	 * @param string $type The new value
	 *
	 * @return void
	 */
	protected function logInteraction($type, $id, $field, $value)
	{
		
		$acl = Zend_Controller_Front::getInstance()->getParam('acl');
		$user = Zend_Auth::getInstance()->getIdentity()->username;
		
		
		// only write to the log if it's not stopped in the config
		$config = Zend_Registry::get('configuration');
		if (!isset($config->app->logAdmin) || $config->app->logAdmin != "false") {
		
			$info = "USER: ".$user."; ";
			$info .= "TYPE: ".str_replace('Default_Model_','',$type)."; ";
			$info .= "ID: ".$id."; ";
			$info .= "FIELD/ACTION: ".$field."; ";
			$info .= "VALUE: ".$value.".";
		
			// write to log
			$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/data/logs/admin.log');
			$log    = new Zend_Log($writer);
			$log->info($info);
		}
	}
}