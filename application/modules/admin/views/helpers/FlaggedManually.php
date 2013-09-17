<?php
/**
 * View helper to convert "1/0" into YES or NO
 */
class Admin_View_Helper_FlaggedManually
{
    /**
     * Convert bool
     * @param int $int 1 or 0
     * @return string
     */
	public function flaggedManually($int, $id, $fieldName)
    {  	
		return ($int==1) ? '<span class="published remove-flag" id="flagged_manually-'.$id.'">Yes</span>' : '<span class="unpublished">No</span>';
    }

}