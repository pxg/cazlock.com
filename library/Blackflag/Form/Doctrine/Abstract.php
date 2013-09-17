<?php

class Blackflag_Form_Doctrine_Abstract extends Zend_Form
{
    protected $_model;

    /**
     * @param Doctrine_Table $model
     */
    public function setModel(Doctrine_Table $model)
    {
        $this->_model = $model;
    }

    /**
     * @return Doctrine_Table
     */
    public function getModel()
    {
        return $this->_model;
    }
}