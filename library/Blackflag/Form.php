<?php
class Blackflag_Form extends Zend_Form
{
	public function __construct($options = null)
	{
		// Path setting for custom classes MUST ALWAYS be first!
		$this->addElementPrefixPath('Blackflag_Form_Decorator', 'Blackflag/Form/Decorator/', 'decorator');
		$this->addElementPrefixPath('Blackflag_Filter', 'Blackflag/Filter/', 'filter');
		$this->addElementPrefixPath('Blackflag_Validate', 'Blackflag/Validate/', 'validate');
		$this->addPrefixPath('Blackflag_Form_Element', 'Blackflag/Form/Element/', Zend_Form::ELEMENT);

		$this->_setupTranslation();

		parent::__construct($options);

		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setDecorators(array(
			'FormElements',
			'Form'
		));
	}
	
	protected $_standardElementDecorator = array(
		array('Errors'),
		'ViewHelper',
//		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'div', 'class' => 'input-container clearfix'))
	);
	
	protected $_standardAdminElementDecorator = array(
		array('Errors'),
		'ViewHelper',
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);

	protected $_multiElementDecorator = array(
		array('Errors'),
		'ViewHelper',	
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array(array('data'=>'HtmlTag'),array('tag'=>'div', 'class' => 'multi-form-wrapper')),
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);

	protected $_checkboxElementDecorator = array(
		array('Errors'),	
		'ViewHelper',
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);

	protected $_multiCheckboxElementDecorator = array(
		array('Errors'),
		array('ViewHelper'),	
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array(array('group' => 'HtmlTag'), array('tag'=>'div', 'class' => 'checkbox-group')),
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);
	
	protected $_fileElementDecorator = array(
		array('Errors'),	
		'File',
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);
	
	protected $_filePreviewElementDecorator = array(
		array('Errors'),	
		'FilePreview',
		array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),	
		array('LabelDescription', array('escape'=>false)),		
		array('HtmlTag', array('tag'=>'li', 'class' => 'clearfix'))
	);

	protected $_buttonElementDecorator = array(
		'ViewHelper'
	);

	protected $_standardGroupDecorator = array(
		'FormElements',
		//array('HtmlTag', array('tag'=>'ol')),
		'Fieldset'
	);
	
	protected $_standardAdminGroupDecorator = array(
		'FormElements',
		array('HtmlTag', array('tag'=>'ol')),
		'Fieldset'
	);

	protected $_buttonGroupDecorator = array(
		'FormElements',
		array('HtmlTag', array('tag'=>'div')),
		'Fieldset'
	);

	protected $_noElementDecorator = array(
		'ViewHelper'
	);

	protected function _setupTranslation()
	{
		/*$translations = array(
			Zend_Validate_NotEmpty::IS_EMPTY => 'Required',
		    Zend_Validate_StringLength::TOO_SHORT => 'Minimum Length of %min%',
		    Zend_Validate_StringLength::TOO_LONG => 'Maximum Length of %max%',
		    Zend_Validate_Date::INVALID => 'Not valid date',
		    Zend_Validate_Date::FALSEFORMAT => 'Invalid date format',
		    Zend_Validate_EmailAddress::INVALID => 'Invalid',
		    Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Invalid',
		    Zend_Validate_EmailAddress::INVALID_MX_RECORD => ' ',
		    Zend_Validate_EmailAddress::DOT_ATOM => ' ',
		    Zend_Validate_EmailAddress::QUOTED_STRING => ' ',
		    Zend_Validate_EmailAddress::INVALID_LOCAL_PART => ' '
		);
		   
		$translate = new Zend_Translate('array', $translations, 'en');
		self::setDefaultTranslator($translate);*/
    
		// Use default registry setup, if there is one.
		if(Zend_Registry::isRegistered('Zend_Translate')){
			// use translation
			$translate = Zend_Registry::get('Zend_Translate');
			self::setDefaultTranslator($translate);
		} else {
			// no translation.
			return;
		}
	}
}