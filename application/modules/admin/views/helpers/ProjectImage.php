<?php
/**
 * View helper to show image
 */
class Admin_View_Helper_projectImage
{
    /**
     * Show image
     * @return string
     */
	public function projectImage($value, $id, $fieldName)
    {  	
		return (isset($value) && !empty($value)) ? '<img src="/_includes/uploads/'.$value.'" alt="'.$value.'" style="max-width:200px;" />' : 'no image';
    }

}