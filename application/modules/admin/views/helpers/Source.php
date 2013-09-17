<?php
/**
 * Show source
 */
class Admin_View_Helper_Source
{
    /**
     * Show a source
     * @param string source id
     * @return string
     */
	public function source($source_id)
    {  	
		$model = new Default_Model_Sources;
		$source = $model->findBySourceId($source_id);
		return '<abbr title="'.$source->source_name.' ('.$source->parameters.')">'.$source_id.'</abbr>';
    }

}