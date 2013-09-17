<?php

class Blackflag_Controller_Plugin_DisableMagicQuotes extends Zend_Controller_Plugin_Abstract
{

	public function dispatchLoopStartup(
		Zend_Controller_Request_Abstract $request)
	{
		$_GET = $this->_stripSlashes($_GET);
		$_POST = $this->_stripSlashes($_POST);
		$_REQUEST = $this->_stripSlashes($_REQUEST);
		$_COOKIE = $this->_stripSlashes($_COOKIE);
	}

	protected function _stripSlashes($value) 
	{
		if (is_array($value)) {
			$value = array_map(array($this, '_stripSlashes'), $value);
		} else {
			$value = stripslashes($value);
		}
		return $value;
	}
}