<?php
class Admin_Form_Page_Edit extends Admin_Form_Page_Add 
{
	public function init()
	{
		parent::init();
			
		$this->addElement('hidden', 'id', array(
			'decorators' => $this->_noElementDecorator,
			'validators' => array(
				'Digits',
				array('Db_RecordExists', false, 
					array(
						'table' => 'pages',
						'field' => 'id'
					),
				),
			),
			'required' => true
		));
		
		$this->getElement('submit')->setLabel('Save changes');		
		$this->getDisplayGroup('data')->setLegend('Edit');
	}  
}