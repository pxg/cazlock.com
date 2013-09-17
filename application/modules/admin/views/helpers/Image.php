<?php
/**
 * View helper to show image
 */
class Admin_View_Helper_Image
{
    /**
     * Show image
     * @return string
     */
	public function image($value, $id, $fieldName)
    {  	
		return (isset($value) && !empty($value)) ? '<img src="/_includes/uploads/'.$value.'" alt="'.$value.'" />' : 'no image';
    }

}