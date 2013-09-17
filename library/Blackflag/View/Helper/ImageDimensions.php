<?php
/**
 * Return Image Dimensions
 */
class Blackflag_View_Helper_ImageDimensions
{
	/**
	 * Return Image Dimensions
	 * @param string $image An image name in the upload directory
	 * @return array
	 */
	public function imageDimensions($image)
	{  	
		// get the upload directory
		$config = Zend_Registry::get('configuration');
		$serverPath = $config->uploads->dir;
		
		// get the image dimensions
		$imageDimensions = @getimagesize($serverPath . $image);
		
		// condition : if the image isn't a cms asset, it might be a static image... 
		if ($imageDimensions === false) {
			$imageDimensions = @getimagesize($serverPath . "../images/site/" . $image);
		}
		
		// create friendly associative array of dimensions
		$return = array(
			"width" => $imageDimensions[0],
			"height" => $imageDimensions[1],
			"html" => $imageDimensions[3]
		);
		
		return $return;
		
		// DEBUG
		// Zend_Registry::get('logger')->log($imageDimensions, Zend_Log::INFO);
	}

}