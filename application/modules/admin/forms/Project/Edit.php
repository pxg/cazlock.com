<?php
class Admin_Form_Project_Edit extends Admin_Form_Project_Add 
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
						'table' => 'projects',
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