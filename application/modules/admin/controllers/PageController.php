<?php
require_once 'AbstractController.php';

class Admin_PageController extends Admin_AbstractController
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = 'Default_Model_Pages';
	
	/**
	 * Add form
	 * @var string
	 */
	protected $_addForm = 'Admin_Form_Page_Add';
	
	/**
	 * Edit form
	 * @var string
	 */
	protected $_editForm = 'Admin_Form_Page_Edit';
	
}