<?php
/**
 * This file holds the class for handling assets.
 */

require_once 'Blackflag/Image/Abstract.php';

/**
 */
class Blackflag_Image_Gd extends Blackflag_Image_Abstract
{

	/**
   * Sets the source image to be resized
	 *
	 * @param String $source The absolute path of the image to be resized
	 *
	 * @access public
   */
	public function setSource($source)
	{
		parent::setSource($source);
		
		switch ($this->type) 
		{
			case IMAGETYPE_GIF: 
				$this->image = imagecreatefromgif($this->source); 
				break;   
			case IMAGETYPE_JPEG:  
				$this->image = imagecreatefromjpeg($this->source); 
				break;   
			case IMAGETYPE_PNG:  
				$this->image = imagecreatefrompng($this->source);
				break; 
			default:
				throw new Exception('Unrecognized image type ' . $this->type);
		}
	}

	/**
   * Saves images via gd command
	 * 
	 * @returns string The path to the newly created file
	 * @access private
   */
	public function saveImage()
	{
		// use parent's functionality
		parent::saveImage();
	
		// create new empty canvas to copy our new image to
		$this->newImage = imagecreatetruecolor($this->newWidth, $this->newHeight);
		
		// preserve transparency for gifs and pngs
		imagealphablending($this->newImage, false);
		imagesavealpha($this->newImage, true);
	
		// resize image and copy to new canvas
		imagecopyresampled($this->newImage, $this->image, $this->newX, $this->newY, $this->x, $this->y, $this->newWidth, $this->newHeight, $this->width, $this->height);

		switch ($this->type) 
		{
			case IMAGETYPE_GIF: 
				$saved = imagegif($this->newImage, $this->destination) ? true : false;
				break;
			case IMAGETYPE_JPEG:  				
				$saved = imagejpeg($this->newImage, $this->destination, $this->jpegQuality) ? true : false;
				break;   
			case IMAGETYPE_PNG: 
				$saved = imagepng($this->newImage, $this->destination) ? true : false;
				break;
			default:
				$saved = false;
		}

		// clean resources
		imagedestroy($this->newImage);
		imagedestroy($this->image);
		
		// return destination
		if ($saved == true) {
			return $this->destination;
		} else {
			return false;
		}
	}

	
}

?>