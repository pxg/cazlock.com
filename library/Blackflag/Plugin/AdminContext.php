<?php
class Blackflag_Plugin_AdminContext extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getModuleName() == 'admin') {
            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayout('admin');
        }
    }
}