<?php
/**
 * Controller to handle projects
 */
class IndexController extends Zend_Controller_Action
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
		$item = $model->findByName('home');	
		
		$projects = new Default_Model_Projects;
		$this->view->projects = $projects->fetchProjects();
		$this->view->featuredProjects = $projects->fetchFeaturedProjects();
		
		
		$this->view->assign(array(
			'item' => $item,
//			'items' => $items,
		));
		
		$this->view->placeholder('body')->id ='home'; 
		$this->view->placeholder('body')->class ='home';
	}
	
	
	/**
	 * Index action
	 * @return void;
	 */
	public function aboutAction()
	{
		$model = new Default_Model_Pages;
		$item = $model->findByName('about');	

		$projects = new Default_Model_Projects;
		$this->view->projects = $projects->fetchProjects();

		$this->view->assign(array (
			'item' => $item,
//			'items' => $items,
		));

		$this->view->placeholder('body')->id ='about'; 
		$this->view->placeholder('body')->class ='about';
	}
	
	
}