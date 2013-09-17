<?php
/**
 * Custom label decorator.  Merges description into label
 *
 */
class Blackflag_Form_Decorator_LabelDescription extends Zend_Form_Decorator_Label
{
	public function getLabel() 
	{
		// get data
		$element = $this->getElement();
		$errors = $element->getMessages();
		$description = $element->getDescription();
		
		// add description to label
		$label = trim($element->getLabel());
		if ($description != '') {
			$label .= ' <span>' . $description . '</span>'; 
		}	
		$element->setLabel($label);
			
		return parent::getLabel();
	}
}