<?php
/**
 * Controller to handle projects
 */
class DebugController extends Zend_Controller_Action
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = 'Pages';

	/**
	 * Index action
	 * @return void;
	 */
	public function indexAction()
	{
		$model = new Default_Model_Pages;
		$item = $model->findByName('debug');	
		
		$this->view->assign(array (
			'item' => $item,
//			'items' => $items,
		));
		
		$this->view->placeholder('body')->id ='debug'; 
		$this->view->placeholder('body')->class ='debug';
	}
}