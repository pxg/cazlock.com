<?php
/**
 * View helper to convert "1/0" into published state
 */
class Admin_View_Helper_FormatPublished
{
    /**
     * Convert bool
     * @param int $int 1 or 0
     * @return string
     */
	public function formatPublished($int)
    {  	
        return ($int==1) ? '<span class="published">Published</span>' : '<span class="unpublished">Unpublished</span>';
    }

}