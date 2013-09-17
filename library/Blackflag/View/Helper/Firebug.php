<?php
class BlackFlag_View_Helper_Fb extends Zend_View_Helper_Abstract{

    public function fb($message, $label=null){
        if ($label!=null) {
            $message = array($label,$message);
        }
        Zend_Registry::get('logger')->err($message);
    }

}

