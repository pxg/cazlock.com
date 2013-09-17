<?php
class Admin_Form_ProjectImages_Edit extends Admin_Form_ProjectImages_Add
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
						'table' => 'project_images',
						'field' => 'id'
					),
				),
			),
			'required' => true,
			'disableTranslator' => true
		));

		$this->getElement('submit')->setLabel('Save changes');
		$this->getDisplayGroup('data')->setLegend('Edit');
	}
}