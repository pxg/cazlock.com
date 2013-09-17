<?php
/**
 * Controller for the index page
 */
class Admin_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
    	$this->_redirect('/admin/project/');
    }

}