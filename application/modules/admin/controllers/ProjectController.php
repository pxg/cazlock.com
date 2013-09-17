<?php
require_once 'AbstractController.php';

class Admin_ProjectController extends Admin_AbstractController
{
	/**
	 * Primary model
	 * @var string
	 */
	protected $_primaryModel = 'Default_Model_Projects';
	
	/**
	 * Add form
	 * @var string
	 */
	protected $_addForm = 'Admin_Form_Project_Add';
	
	/**
	 * Edit form
	 * @var string
	 */
	protected $_editForm = 'Admin_Form_Project_Edit';
	
	/**
	 * Standard uploads
	 * @var array ('fieldname' => 'preferredfilename')
	 */
	protected $_standardUploads = array(
		'project_thumb' => 'uploaded-thumb',
		'featured_image' => 'featured-image',
	);
	
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