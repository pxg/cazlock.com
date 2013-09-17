<?php
/**
 * Show source
 */
class Admin_View_Helper_ImageType
{
    /**
     * Show a source
     * @param string source id
     * @return string
     */
	public function imageType($value, $id, $fieldName)
    {  	
		$model = new Default_Model_ProjectImageTypes;
		$type = $model->findById($value);
		return $type->image_type;
    }

}