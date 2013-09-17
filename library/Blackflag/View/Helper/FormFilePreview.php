<?php
/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a "file" element with preview of asset
 */
class Blackflag_View_Helper_FormFilePreview extends Zend_View_Helper_FormElement
{
	/**
	 * Generates a 'file' element.
	 *
	 * @access public
	 *
	 * @param string|array $name If a string, the element name.  If an
	 * array, all other parameters are ignored, and the array elements
	 * are extracted in place of added parameters.
	 *
	 * @param array $attribs Attributes for the element tag.
	 *
	 * @return string The element XHTML.
	 */
	public function formFilePreview($name, $value, $attribs = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info); // name, id, value, attribs, options, listsep, disable

		// is it disabled?
		$disabled = '';
		if ($disable) {
			$disabled = ' disabled="disabled"';
		} 

		// XHTML or HTML end tag?
		$endTag = ' />';
		if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
			$endTag= '>';
		}
		
		// build the file element
		$file = '<input type="file"'
				. ' name="' . $this->view->escape($name) . '"'
				. ' id="' . $this->view->escape($id) . '"'
				. $disabled
				//. $this->_htmlAttribs($attribs) 
				. $endTag;
 
		// get extension
		$extension = pathinfo($value, PATHINFO_EXTENSION);
		
		// get paths from config
		$config = Zend_Registry::get('configuration');	
		$imagesPath = $config->admin->assetPaths->images;
		$uploadsPath = $config->admin->assetPaths->uploads;
		
		// condition: got file?
		if ($extension != "") {
		
			// build delete
 			$delete = '
 				<span class="file-delete">
 					<span class="delete-label">Delete?</span>'.
 					$this->view->formCheckbox($name.'-delete').'
 				</span>
 			';
	  	
			// condition : display images or load icons
			switch ($extension) {
				case "jpg":
				case "jpeg":
				case "gif":
				case "png":
				case "tiff":
				case "tif":
					// display thumb
					$image = '<img alt="" class="file-image" src="'.$this->view->baseUrl().$uploadsPath.$value.'" '.$endTag;
					break;
				case "pdf":
				case "doc":
				case "xls":
				case "csv":
				case "psd":
				case "ppt":
				case "ai":
				case "ttf":
				case "as":
				case "asc":
				case "txt":
				case "zip":
				case "swf":
					// all undisplayable filetypes, attach icon and short description
					$image =  '
						<a href="'.$this->view->baseUrl().'/_includes/cms-assets/'.$value.'" title="View file">
							<img class="file-icon" src="'.$this->view->baseUrl().$imagesPath.'generic-icon-'.$extension.'.gif" alt="'.strtoupper($extension).' file" />
						</a>
						<span class="file-description">' . basename($value) . '</span>
					';
					break;
				default:
					// unknown file type
					$image =  '
						<a href="'.$this->view->baseUrl().'/_includes/cms-assets/'.$value.'" title="View file">
							<img class="file-icon" src="'.$this->view->baseUrl().$imagesPath.'generic-icon-xxx.gif" alt="'.strtoupper($extension).' file" />
						</a>
						<span class="file-description">' . basename($value) . '</span>
					';
					break;
			}
		} else {
			$image =  "No file uploaded";
			$delete = '';
		}

		// build container
		$xhtml = '<div class="file-upload">
					<div class="file-elements clearfix">
						<span class="file">'.$file.'</span>
						'.$delete.'
					</div>
					
					<div class="asset">
						'.$image.'
						'.$this->view->formHidden($name.'-filename', $value).'
					</div>
				</div>
				';
	
		return $xhtml;
	}
}
