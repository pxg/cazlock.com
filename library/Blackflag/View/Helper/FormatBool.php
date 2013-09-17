<?php
/**
 * View helper to convert "1/0" into "yes/no"
 */
class Blackflag_View_Helper_FormatBool
{
	/**
	 * Convert bool
	 * @param int $int 1 or 0
	 * @return string
	 */
	public function formatBool($int)
	{  	
		return ($int==1) ? 'Yes' : 'No';
	}

}