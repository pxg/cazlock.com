<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Helps with uploads
 *
 * @uses	   Zend_Controller_Action_Helper_Abstract
 */
class Blackflag_Controller_Action_Helper_Upload extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Gets a unique filename
	 *
	 * @param string $source Source path
	 * @param string $preferredFilename
	 * @param array $formValues Array of form values
	 * @return string
	 */
	public function getUniqueFilename($source, $preferredFilename, $formValues)
	{   	
		$config = Zend_Registry::get('configuration');	
		$sourcePathParts = pathinfo($source);
		$count = 1;
		
		if (isset($formValues['id']) && $formValues['id'] > 0) {
			$id = $formValues['id'];
		} else {
			$id = "xx";
		}
					
		$dest = $config->uploads->publicPath.$preferredFilename."-".$id."-r.".$sourcePathParts['extension'];
		$pathParts = pathinfo($dest);
		
		while(file_exists($dest)) {			
			$dest = $pathParts['dirname'] . DIRECTORY_SEPARATOR . $pathParts['filename'] . $count . "." . $pathParts['extension'];
			$count++;
		}
		return $dest;
	}


	/**
	 * Gets the extension of a file
	 *
	 * @param  string $filename
	 * @return string
	 */
	public function getFileExtension($filename)
	{   	
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	}
}