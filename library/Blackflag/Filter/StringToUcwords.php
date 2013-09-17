<?php
class Blackflag_Filter_StringToUcwords implements Zend_Filter_Interface
{
	/**
	 * Defined by Zend_Filter_Interface
	 *
	 * Uppercase the first character of each word in a string, $value
	 *
	 * @param  string $value
	 * @return string
	 */
	public function filter($value)
	{
		$value = ucwords($value);

		return $value;
	}
}
