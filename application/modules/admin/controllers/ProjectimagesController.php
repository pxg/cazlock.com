<?php
require_once 'AbstractController.php';

class Admin_ProjectimagesController extends Admin_AbstractController
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = 'Default_Model_ProjectImages';
	
	/**
	 * Add form
	 * @var string
	 */
	protected $_addForm = 'Admin_Form_ProjectImages_Add';
	
	/**
	 * Edit form
	 * @var string
	 */
	protected $_editForm = 'Admin_Form_ProjectImages_Edit';
	

	/**
	 * Session data
	 *
	 * @var Zend_Session_Namespace
	 */
	protected $_session = null;

	/**
	 * Standard uploads
	 * @var array ('fieldname' => 'preferredfilename')
	 */
	protected $_standardUploads = array(
		'img_src' => 'project-image',
		'flash_src' => 'project-flash',
	);

	/**
	 * Make session data available (if there is any)
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		// contains id of parent item
		$this->_session = new Zend_Session_Namespace('Admin_ProjectImages');
		$this->_session->id = $this->_getParam('project_id');
	}


	/**
	 * Get items from db and pass them to the view
	 *
	 * @return void
	 */
	protected function _getItems(){

		// get parent
		$model = new Default_Model_ProjectImages();
		$parentModel = new Default_Model_Projects();
		$parent = $parentModel->findById($this->_session->id);


		// get the item list
		$model = new $this->_primaryModel;
		$items = $model->fetchNotTrashedByParentId($this->_session->id);

		// assign specific vars to the view
		$this->view->assign(array (
			'items' => $items,
			'parent' => $parent,
//			'title' => $items->getRow(0)->title
			'title' => "TITLE"
		));
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
}