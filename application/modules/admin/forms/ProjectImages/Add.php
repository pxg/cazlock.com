<?php
class Admin_Form_ProjectImages_Add extends Blackflag_Form
{

	/*
	 * The fields used in the data group
	 *
	 * @var $_dataGroupFields array
	 */
	public $_dataGroupFields =  array(
		'project_id',
		'image_type_id',
		'img_src',
		'flash_src',
		'vimeo_url',
		'iframe_url',
		'image_name',
		'width',
		'height',
	);

	public function init()
	{
		$translate = new Zend_Translate('array', array('faux' => 'faux'));
		self::setDefaultTranslator($translate);

		// get config for file dir
		$config = Zend_Registry::get('configuration');



		// add project id
		$session = new Zend_Session_Namespace('Admin_ProjectImages');
		$this->addElement('hidden', 'project_id', array(
			'decorators' => $this->_noElementDecorator,
			'validators' => array(
				'Digits'
			),
			'required' => true,
			'value' => $session->id,
			'disableTranslator' => true
		));


		// types
		$typesModel = new Default_Model_ProjectImageTypes();
		$types = $typesModel->getOptions('id', 'image_type', 'image_type');

		// create element
		$this->addElement('select', 'image_type_id', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Image type',
			'description' => '',
			'validators' => array(
				array('StringLength', false, array(1,50))
			),
			'filters' => array('StringTrim'),
			'required' => true,
            'multioptions'   => $types,
			'disableTranslator' => true
		));


		// Display Group #1 : Entry Data

		$this->addElement('text', 'image_name', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Image Name',
			'description' => '(Used only in the admin section)',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => false,
			'disableTranslator' => true
		));


		$this->addElement('text', 'width', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Width',
			'description' => '',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => true,
			'disableTranslator' => true
		));

		$this->addElement('text', 'height', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Height',
			'description' => '',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => true,
			'disableTranslator' => true
		));


		$this->addElement('filePreview', 'img_src', array(
				'decorators' => $this->_filePreviewElementDecorator,
				'label' => 'Image',
				'description' => '(Recommended size 680x400px, jpg or png)',
				'validators' => array(
					//array('NotExists', true, $config->uploads->tempPath),
					array('Size', true, "3800KB"),
					array('Extension', true, array('jpg', 'png')),
					array('ImageSize', false, array(
						'minwidth' => 0,
						'maxwidth' => 1500,
						'minheight' => 0,
						'maxheight' => 1500,
						)
					)
				),
				'destination' => $config->uploads->tempPath,
				'disableTranslator' => true,
				'required' => false,
			));


		$this->addElement('filePreview', 'flash_src', array(
				'decorators' => $this->_filePreviewElementDecorator,
				'label' => 'Flash file',
				'description' => '(Recommended size 680x400px, swf)',
				'validators' => array(
					//array('NotExists', true, $config->uploads->tempPath),
					array('Size', true, "10000KB"),
					array('Extension', true, array('swf')),
				),
				'destination' => $config->uploads->tempPath,
				'disableTranslator' => true,
				'required' => false,
			));


		$this->addElement('text', 'vimeo_url', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Vimeo ID',
			'description' => '',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => false,
			'disableTranslator' => true
		));


		$this->addElement('text', 'iframe_url', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'iframe URL',
			'description' => '(don\'t forget the http://, if it doesn\'t work first time edit and re-enter url',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => false,
			'disableTranslator' => true
		));



		$this->addDisplayGroup(
			$this->_dataGroupFields,
			'data',
			array(
				'disableLoadDefaultDecorators' => true,
				'decorators' => $this->_standardAdminGroupDecorator,
				'legend' => 'Add new',
				'disableTranslator' => true
			)
		);


		// Display Group #2 : Submit

		$this->addElement('submit', 'submit', array(
			'decorators' => $this->_buttonElementDecorator,
			'label' => 'Add new',
			'ignore'   => true,
			'disableTranslator' => true
		));

		$this->addDisplayGroup(
			array('submit'), 'datasubmit',
			array(
				'disableLoadDefaultDecorators' => true,
				'decorators' => $this->_buttonGroupDecorator,
				'class' => 'submit',
				'order' => 100,
				'disableTranslator' => true
			)
		);

		$this->setAttrib('enctype', 'multipart/form-data');
	}
}