<?php
/**
 * View helper to convert "1/0" into checkbox
 */
class Admin_View_Helper_Bookmark
{
    /**
     * Convert bool
     * @param int $int 1 or 0
     * @return string
     */
	public function bookmark($int, $id, $fieldName)
    {  	
		$return = '<label for="'.$fieldName.'-'.$id.'" class="bookmarker';
		if ($int == 1) {
			$return .= ' selected';
		}
		$return .= '"><input type="checkbox" class="checkbox bookmark '.$fieldName.'" name="'.$fieldName.'-'.$id.'" id="'.$fieldName.'-'.$id.'"';
		if ($int == 1) {
			$return .= ' checked="checked"';
		}
		$return .= ' /></label>';
		return $return;
    }

}