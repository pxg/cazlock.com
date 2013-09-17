<?php
/**
 * Controller to display latest messages
 */
class ProjectController extends Zend_Controller_Action
{
	
	/*
	 *
	 */
	public function init() {

	}
	
	
	/**
	 * Index action
	 * Users should never be able to reach this section
	 * 
	 * @return void;
	 */
	public function indexAction()
	{
		// redirect to home page
    	$this->_redirect("/");
	}
	
	
	/**
	 * Index action
	 * @return void;
	 */
	public function projectAction()
	{
		$projects = new Default_Model_Projects;

		$request = $this->getRequest();
		if (!isset($request->slug)) {
			$this->_redirect("/");
		}

		$this->view->projects = $projects->fetchProjects();
		$this->view->project = $projects->fetchProject($request->slug);
		$this->view->projectImages = $projects->fetchProjectImages($request->slug);
	
		$this->view->placeholder('body')->id ='project'; 
		$this->view->placeholder('body')->class ='projects'; 
	}
	

}