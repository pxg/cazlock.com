<?php
class Admin_Form_Project_Add extends Blackflag_Form
{
	public function init()
	{
		$translate = new Zend_Translate('array', array('faux' => 'faux'));
		self::setDefaultTranslator($translate);
		
		// get config for file dir
		$config = Zend_Registry::get('configuration');	
		
		// Display Group #1 : Entry Data
		
		$this->addElement('text', 'project_title', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Title',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => true
		));

		$this->addElement('text', 'project_slug', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Slug',
			'description' => '(URL)',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim', 'StringToLower', 'AlnumDash'),
			'required' => true
		));
		
		
		$this->addElement('filePreview', 'project_thumb', array(
				'decorators' => $this->_filePreviewElementDecorator,
				'label' => 'Project Thumbnail',
				'description' => '(60x60px image, jpg or png)',
				'validators' => array(
					//array('NotExists', true, $config->uploads->tempPath),
					array('Size', true, "80KB"),
					array('Extension', true, array('jpg', 'png')),
					array('ImageSize', false, array(
						'minwidth' => 60,
						'maxwidth' => 60,
						'minheight' => 60,
						'maxheight' => 60,
						)
					)
				),
				'destination' => $config->uploads->tempPath,
				'disableTranslator' => true
			));

		$this->addElement('filePreview', 'featured_image', array(
				'decorators' => $this->_filePreviewElementDecorator,
				'label' => 'Home page featured image',
				'description' => '(680x320px image, jpg or png)',
				'validators' => array(
					//array('NotExists', true, $config->uploads->tempPath),
					array('Size', true, "800KB"),
					array('Extension', true, array('jpg', 'png')),
					array('ImageSize', false, array(
						'minwidth' => 680,
						'maxwidth' => 680,
						'minheight' => 320,
						'maxheight' => 320,
						)
					)
				),
				'destination' => $config->uploads->tempPath,
				'disableTranslator' => true,
				'required' => false
			));

		
		$this->addElement('textarea', 'project_description', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Project Description',
			'filters' => array('Null'),	
			'required' => true, 	
		));
		
		$this->addElement('text', 'project_date', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Project Date',
			'class' => 'text date-picker-full',
			'description' => '(Only month and year will be displayed)',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(1,255))
			),
			'filters' => array('StringTrim'),
			'required' => true
		));
		

		$this->addDisplayGroup(
			array(	
				'project_title', 			
				'project_slug', 
				'project_description',	
				'project_date',	
				'project_thumb', 
				'featured_image',
			),
			'data',
			array(
				'disableLoadDefaultDecorators' => true,
				'decorators' => $this->_standardAdminGroupDecorator,
				'legend' => 'Add new'
			)
		);

		
		// Display Group #2 : Submit

		$this->addElement('submit', 'submit', array(
			'decorators' => $this->_buttonElementDecorator,
			'label' => 'Add new', 
			'ignore'   => true,
		));

		$this->addDisplayGroup(
			array('submit'), 'datasubmit',
			array(
				'disableLoadDefaultDecorators' => true,
				'decorators' => $this->_buttonGroupDecorator,
				'class' => 'submit'
			)
		);
		
		
		$this->setAttrib('enctype', 'multipart/form-data');
	}
}