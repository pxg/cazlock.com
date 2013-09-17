<?php
/**
 * View helper to show project image count
 */
class Admin_View_Helper_ProjectImageCount
{
    /**
     * Show image
     * @return string
     */
	public function projectImageCount($value, $id, $fieldName)
    {  	
		$model = new Default_Model_ProjectImages;
		$images = $model->fetchNotTrashedByParentId($id);

		return count($images);
    }

}