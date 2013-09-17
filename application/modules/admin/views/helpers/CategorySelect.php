<?php
/**
 * Show source
 */
class Admin_View_Helper_CategorySelect
{
    /**
     * Show a source
     * @param string source id
     * @return string
     */
	public function categorySelect($category_id, $id, $fieldName)
    {  	
	
		$return = '
			<select class="liveupdate" name="category_id" id="category_id-'.$id.'">
		';

		// find available categories
		$model = new Default_Model_Categories;
		$categories = $model->fetchCategories();
		
		// loop through all comments
		foreach ($categories as $key => $category) {

			// condition : is this the current category?
			if (isset($category_id) && !empty($category_id) && intval($category_id) == $category->id) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			
			$return .= '
				<option value="'.$category->id.'"'.$selected.'>'.$category->category.'</option>
			';
		}
		
		$return .= '
			</select>
		';
		
		return $return;
    }

}