<?php
/**
 * Custom label decorator.  Merges description and errors into label
 *
 */
class Blackflag_Form_Decorator_LabelError extends Zend_Form_Decorator_Label
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
			$label .= ' <span>(' . $description . ')</span>'; 
		}	
		$element->setLabel($label);
		
		// condition: no errors then return label as is
		if (empty($errors)) {
			return parent::getLabel();
		}
		
		// add errors to label
		$label = trim($element->getLabel());
		$label .= ' <strong>'
			. implode('</strong><br /><strong>', $errors)
			. '</strong>';  		
		$element->setLabel($label);
		
		return parent::getLabel();
	}
}