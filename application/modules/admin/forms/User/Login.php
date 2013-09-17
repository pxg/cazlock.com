<?php

class Admin_Form_User_Login extends Blackflag_Form
{
    public function init()
    {
        $translate = new Zend_Translate('array', array('faux' => 'faux'));
		self::setDefaultTranslator($translate);
		
    	$this->setAction('/admin/user/login');

        // Display Group #1 : Credentials

        $this->addElement('text', 'name', array(
            'decorators' => $this->_standardAdminElementDecorator,
            'label' => 'Username',
            'attribs' => array(
                'maxlength' => 120,
                'size' => 40
            ),
            'validators' => array(
                array('StringLength', false, array(3,120))
            ),
            'required' => true
        ));

        $this->addElement('password', 'password', array(
            'decorators' => $this->_standardAdminElementDecorator,
            'label' =>'Password',
        	'attribs' => array(
        		'class' => 'text'
        	), 
            'required' => true
        ));

        $this->addDisplayGroup(
            array('name', 'password'), 'data',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => $this->_standardAdminGroupDecorator,
                'legend' => 'Credentials'
            )
        );

        // Display Group #2 : Submit

        $this->addElement('submit', 'submit', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Sign in'
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