<?php
/**
 * Show source
 */
class Admin_View_Helper_Date
{
    /**
     * Date format
     * @param int date
     * @return string
     */
	public function date($date)
    {  	
	
		// condition : date is already a timestamp (comment)
		if (!strtotime($date)) {
			return date("j/n/Y, g:ia", intval($date));

		// date is a string (idea)
		} else {
			return date("j/n/Y, g:ia", strtotime($date));
		}
    }

}