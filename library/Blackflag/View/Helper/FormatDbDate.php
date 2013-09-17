<?php

class Blackflag_View_Helper_FormatDbDate
{

	public function formatDbDate($datetime, $format='dd/MM/yy')
	{
		$locale = Zend_Registry::get('Zend_Translate');
		$date = new Zend_Date($datetime, Zend_Date::ISO_8601, $locale->getLocale());
		return $date->toString($format);
	}

}