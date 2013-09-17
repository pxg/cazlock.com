<?php
require_once 'AbstractController.php';

class Admin_TrashController extends Admin_AbstractController
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = 'Admin_Model_Trash';
	
	/**
	 * Models that can be trashed
	 * @var array
	 */
	protected $_models = array(
		'Default_Model_Projects',
		'Default_Model_ProjectImages',
		//'Default_Model_Ideas',
	);
	
	/*
	 * Page
	 * @var int
	 */
	protected $page = 1;
	
	
	/**
	 * Index action (handles list and edit)
	 * 
	 * @return void
	 */	
	public function init()
	{
		$acl = Zend_Controller_Front::getInstance()->getParam('acl');
		$role = Zend_Auth::getInstance()->getIdentity()->role;
		
		if ($role == "administrator") {
			//$this->_models[] = 'Default_Model_Sources';
		}	
		
		// find if a page have been set
		$params = $this->getRequest()->getParams();
		if (isset($params['page'])) {
			$this->page = $params['page'];
		}	
	}
	
	
	/**
	 * Index action
	 *
	 */	
	public function indexAction()
	{ 			
		$model = new $this->_primaryModel;
		$model->setModels($this->_models);
		$items = $model->fetchAllTrashedPaginated($this->page);
		$this->view->assign(
			array(
				'items' => $items
			)
		);
	}
	
	/**
	 * Delete action
	 *
	 */	
	public function deleteAction()
	{ 			
		$model = new $this->_primaryModel;

		if ($this->_hasParam('id')) {
			$result = $model->delete($this->_getParam('model'), $this->_getParam('id'));
			$this->view->wasDeleted = true;
		}
		
		$model->setModels($this->_models);
		$items = $model->fetchAllTrashedPaginated($this->page);
		$this->view->assign(
			array(
				'items' => $items
			)
		);
		
		$this->logInteraction($this->_getParam('model'), $this->_getParam('id'), '[DELETE]', 1);
		
		// use index view
		$this->_helper->viewRenderer('index');
	}
	
	/**
	 * Delete action
	 *
	 */	
	public function restoreAction()
	{ 			
		$model = new $this->_primaryModel;

		if ($this->_hasParam('id')) {
			$result = $model->restore($this->_getParam('model'), $this->_getParam('id'));
			$this->view->wasRestored = true;
		}
		
		$model->setModels($this->_models);
		$items = $model->fetchAllTrashedPaginated($this->page);
		$this->view->assign(
			array(
				'items' => $items
			)
		);
		
		$this->logInteraction($this->_getParam('model'), $this->_getParam('id'), 'is_trashed', 0);
		
		// use index view
		$this->_helper->viewRenderer('index');
	}
	
	

}