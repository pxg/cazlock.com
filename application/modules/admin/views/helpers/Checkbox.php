<?php
/**
 * View helper to convert "1/0" into checkbox
 */
class Admin_View_Helper_Checkbox
{
    /**
     * Convert bool
     * @param int $int 1 or 0
     * @return string
     */
	public function checkbox($int, $id, $fieldName)
    {  	
		$return = '<input type="checkbox" class="checkbox liveupdate '.$fieldName.'" name="'.$fieldName.'-'.$id.'" id="'.$fieldName.'-'.$id.'"';
		if ($int == 1) {
			$return .= ' checked="checked"';
		}
		$return .= ' />';
		return $return;
    }

}