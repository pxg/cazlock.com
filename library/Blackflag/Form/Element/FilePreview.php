<?php
/**
 * Blackflag_Form_Element 
 */
class Blackflag_Form_Element_FilePreview extends Zend_Form_Element_File
{ 
	/**
	 * @var string Default view helper
	 */
	public $helper = 'formFilePreview';
	
	/**
	 * @var string Track value for Decorator
	 */
	public $myValue = '';

	public function setValue($value)
	{
		$this->myValue = $value;		
		return $this;  
	}
}