<?php
class Admin_Form_Page_Add extends Blackflag_Form
{
	public function init()
	{
		$translate = new Zend_Translate('array', array('faux' => 'faux'));
		self::setDefaultTranslator($translate);
		
		// get config for file dir
		$config = Zend_Registry::get('configuration');	
		
		// Display Group #1 : Entry Data
		
		$this->addElement('text', 'name', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Name',
			'description' => 'developer only',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(3,255))
			),
			'filters' => array('StringTrim'),
			'required' => true
		));
		

		$this->addElement('text', 'title', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Title',
			'attribs' => array(
				'maxlength' => 255,
				'size' => 60
			),
			'validators' => array(
				array('StringLength', false, array(3,255))
			),
			'filters' => array('StringTrim'),
			'required' => true
		));
		
		$this->addElement('textarea', 'body', array(
			'decorators' => $this->_standardAdminElementDecorator,
			'label' => 'Body',
			'filters' => array('Null'),	
			'required' => false, 	
		));
	
		$this->addDisplayGroup(
			array(	
				'name', 			
				'title',
				'body',					
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
	}
}