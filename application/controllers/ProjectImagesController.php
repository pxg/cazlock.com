<?php
/**
 * Controller to display latest messages
 */
class ProjectImagesController extends Zend_Controller_Action
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
}