<?php
/**
 * Show source
 */
class Admin_View_Helper_Category
{
    /**
     * Show a source
     * @param string source id
     * @return string
     */
	public function category($category_id, $id, $fieldName)
    {  	
	
		if ($category_id == 0) { 
			return false; 
		}
	
		// find available categories
		$model = new Default_Model_Categories;
		$category = $model->findByCategoryId($category_id);
		return $category->category;
    }

}