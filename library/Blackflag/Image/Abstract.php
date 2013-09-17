<?php

/**
 * Class for SQL table interface.
 *
 * @category   Blackflag
 * @package	Blackflag_Db
 */
abstract class Blackflag_Image_Abstract
{
	protected $jpegQuality = 95;
	protected $source;
	protected $image;
	protected $width;
	protected $height;
	protected $x = 0;
	protected $y = 0;
	protected $type;

	protected $destination;
	protected $newImage;
	protected $newWidth;
	protected $newHeight;
	protected $newX = 0;
	protected $newY = 0;


	/**
	 *
	 */
	public function __construct($source = NULL, $destination = NULL, $override = false)
	{
		if(isset($source)) { $this->setSource($source); }
		if(isset($destination)) { $this->setDestination($destination); }
		if(isset($override)) { $this->setOverride($override); }
	}


	/**
	 * Set method for source
	 *
	 * @param string $source Path to source
	 *
	 * @access public
	 */
	public function setSource($source)
	{
		// create object var
		$this->source = $source;

		// set dimensions of source
		$stats = getimagesize($this->source);
		$this->setSourceDimensions($stats[0], $stats[1]);
		$this->setType($stats[2]);
	}


	/**
	 * Get method for source
	 *
	 * @param string $source Path to source
	 *
	 * @access public
	 */
	public function getSource()
	{
		// create object var
		return $this->source;
	}
	

	/**
	 * Set method for type of image (file extension)
	 *
	 * @param string $type Filetype of source and destination image
	 *
	 * @access public
	 */
	public function setType($type)
	{
		// set image type
		$this->type = $type;
	}

	/**
	 * Set method for destination
	 *
	 * @param string $destination Path to destination
	 */
	 public function setDestination($output)
	{
		if(is_string($output)) {
			$this->destination = $output;
		}
	}

	/**
	 * Set method for override
	 *
	 * @param bool $override Override existing files?
	 */
	 public function setOverride($override)
	{
		$this->override = $override;

	}

	/**
	 * Set method for image dimensions
	 *
	 * @param int $width Width of source image
	 * @param int $height Height of source image
	 *
	 * @access public
	 */
	public function setSourceDimensions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}


	/**
	 * Set method for image dimensions
	 *
	 * @param int $width Width of detination image
	 * @param int $height Height of destination image
	 *
	 * @access public
	 */
	public function setDestinationDimensions($width, $height)
	{
		$this->newWidth = $width;
		$this->newHeight = $height;
	}

	/**
	 * Set method for image dimensions
	 *
	 * @param int $width Width of detination image
	 * @param int $height Height of destination image
	 *
	 * @access public
	 */
	public function setSourceCoordinates($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * Set method for image dimensions
	 *
	 * @param int $width Width of detination image
	 * @param int $height Height of destination image
	 *
	 * @access public
	 */
	public function setDestinationCoordinates($newX, $newY)
	{
		$this->newX = $newX;
		$this->newY = $newY;
	}
	

	/**
	 * Method to write image to filesystem
	 *
	 */
	public function saveImage()
	{

	}
	
	/**
	 * Resizes (and crops) to max/min dimensions
	 *
	 * @param int $wx New max width
	 * @param int $hx New max height
	 * @param int $wx New min width
	 * @param int $hx New min height
	 *
	 * @see http://www.martienus.com
	 * @author Martin Withaar
	 *
	 * @return boolean
	 */
	public function resize($wx, $hx, $wm = 0, $hm = 0) {
		
		// ratios
		$r = $this->width / $this->height;
		$rx = $wx / $hx;
		
		if($wm == 0 || $hm == 0) {
			$rm = $rx;
		} else {
			$rm = $wm / $hm;
		}

		// init
		$dx=0; $dy=0; $sx=0; $sy=0; $dw=0; $dh=0; $sw=0; $sh=0; $w=0; $h=0;

		if($r > $rx && $r > $rm) {
			$w = $wx;
			$h = $hx;
			$sw = $this->height * $rx;
			$sh = $this->height;
			$sx = ($this->width - $sw) / 2;
			$dw = $wx;
			$dh = $hx;
		} elseif($r < $rm && $r < $rx) {
			$w = $wx;
			$h = $hx;
			$sh = $this->width / $rx;
			$sy = ($this->height - $sh) / 2;
			$sw = $this->width;
			$dw = $wx;
			$dh = $hx;
		} elseif($r >= $rx && $r <= $rm) {
			$w = $wx;
			$h = $wx / $r;
			$dw = $wx;
			$dh = $wx / $r;
			$sw = $this->width;
			$sh = $this->height;
		} elseif($r <= $rx && $r >= $rm) {
			$w = $hx * $r;
			$h = $hx;
			$dw = $hx * $r;
			$dh = $hx;
			$sw = $this->width;
			$sh = $this->height;
		}

		// set source and destination coordinates
		$this->setSourceCoordinates((int)$sx, (int)$sy);
		$this->setDestinationCoordinates((int)$dx, (int)$dy);

		// set source and destination dimensions, select from source image matching destination image
		$this->setSourceDimensions((int)$sw, (int)$sh);
		$this->setDestinationDimensions((int)$dw, (int)$dh);

		// save image to filesystem
		return $this->saveImage();
	}


	/**
	 * Resizes a file to fixed dimensions and stores it in the destination directory.
	 *
	 * @param int $width New width
	 * @param int $height New height
	 *
	 * @return string Path to saved image
	 * @return true
	 */
	public function resizeToFixedDimensions($width, $height) {

		// set destination size
		$this->setDestinationDimensions($width, $height);

		// save image to filesystem
		return $this->saveImage();
	}


	/**
	 * Resizes a file to fixed height and stores it in the destination directory.
	 * If the new height is less than existing height, no changes will be made
	 *
	 * @param int $height New height
	 *
	 * @return boolean
	 * @return true
	 */
	public function resizeToFixedHeight($height) {

		// resize image only if new height ($height) is less than existing height ($this->height)
		if ($height < $this->height) {
			// calculate height scalar
			$scalar = (float)($height / $this->height);
			$width = ceil($this->width * $scalar);
			$height = ceil($this->height * $scalar);
		} else {
			$height = $this->height;
			$width = $this->width;
		}
			// set destination size
			$this->setDestinationDimensions($width, $height);

			// save new smaller image to filesystem
			return $this->saveImage();


	}

	/**
	 * Resizes a file to fixed width and stores it in the destination directory.
	 * If the new width is less than existing width, no changes will be made
	 *
	 * @param int $width New width
	 *
	 * @return boolean
	 * @return true
	 */
	public function resizeToFixedWidth($width) {

		// condition : resize image only if new width ($width) is less than existing width ($this->width)
		if ($width < $this->width) {

			// calculate width scalar
			$scalar = (float)($width / $this->width);
			$width = ceil($this->width * $scalar);
			$height = ceil($this->height * $scalar);
		} else {
			$width = $this->width;
			$height = $this->height;
		}

		// set destination size
		$this->setDestinationDimensions($width, $height);

		// save new smaller image to filesystem
		return $this->saveImage();

	}

	/**
	 * Resizes a file to a max width or max height and stores it in the destination directory.
	 *
	 * @param int $maxwidth New max width
	 * @param int $maxheight New max height
	 *
	 * @return boolean
	 */
	public function resizeToMaxWidthOrHeight($maxwidth, $maxheight) {

		// condition : landscape or portrait?
		if ($this->width > $this->height) {

			// condition : is new width smaller than current?
			if ($this->width > $maxwidth) {
				$newheight = (int)floor($this->height * ($maxwidth / $this->width));
				$newwidth = $maxwidth;
			} else {
				// image is too small to be resized
				$newwidth = $this->width;
				$newheight = $this->height;
			}

		} else {

			// condition : is new height smaller than current?
			if ($this->height > $maxheight) {
				$newwidth = (int)floor($this->width * ($maxheight / $this->height));
				$newheight = $maxheight;
			} else {
				// image is too small to be resized
				$newheight = $this->height;
				$newwidth = $this->width;
			}
		}

		// set destination size
		$this->setDestinationDimensions($newwidth, $newheight);

		// save image to filesystem
		return $this->saveImage();

	}

	/**
	 * Resize to a min width AND height.
	 * 
	 * Basically this will scale down the image to whichever side (width or height)
	 * that fits inside the bounding box first.
	 * e.g.
	 * If you set minwidth = 100 and minheight = 100
	 * and you supply an image which is 500x200
	 * then it will change the image down until either the height or width
	 * fits into the minimum specifications first.
	 * So the resultant image will in fact be 250x100 .
	 * Obviously this doesn't fit the minwidth of 100 but does fit the min
	 * height of 100, therefore leaving you with some area of picture outside
	 * the box you set as the boundary.
	 * 
	 * @param integer $minwidth
	 * @param integer $minheight
	 * @return void
	 */
	public function resizeToMinWidthAndHeight($minwidth, $minheight){
		
		$widthRatio = $minwidth / $this->width;
		$heightRatio = $minheight / $this->height;
		
		$ratio = max($heightRatio, $widthRatio);
		
		$newwidth = (int)floor($this->width * $ratio);
		$newheight = (int)floor($this->height * $ratio);
		
		// set destination size
		$this->setDestinationDimensions($newwidth, $newheight);

		// save image to filesystem
		return $this->saveImage();
		
	}
	
	/**
	 * Resizes a file to a min width or min height and stores it in the destination directory.
	 *
	 * @param int $minwidth New min width
	 * @param int $minheight New min height
	 *
	 * @return boolean
	 */
	public function resizeToMinWidthOrHeight($minwidth, $minheight) {

		// condition : landscape or portrait?
		if ($this->width > $this->height) {
			
			// condition : is new height smaller than current?
			if ($this->height > $minheight) {
				$newwidth = (int)floor($this->width * ($minheight / $this->height));
				$newheight = $minheight;
			} else {
				// image is too small to be resized
				$newheight = $this->height;
				$newwidth = $this->width;
			}

		} else {

			// condition : is new width smaller than current?
			if ($this->width > $minwidth) {
				$newheight = (int)floor($this->height * ($minwidth / $this->width));
				$newwidth = $minwidth;
			} else {
				// image is too small to be resized
				$newwidth = $this->width;
				$newheight = $this->height;
			}

		}

		// set destination size
		$this->setDestinationDimensions($newwidth, $newheight);

		// save image to filesystem
		return $this->saveImage();

	}


	/**
	 * Crops from the centre of an image.  resizes image and stores it in the destination directory.
	 *
	 * @param int $newwidth Width of the new file
	 * @param int $newheight Height of the new file
	 * @param int $newx starting coordinate on new image
	 * @param int $newy starting coordinate on newimage
	 * @param int $x starting coordinate on source image
	 * @param int $y starting coordinate on source image
	 *
	 */
	public function cropImageFromCentre($newwidth="", $newheight="", $newx="0", $newy="0", $x="", $y="") {

		// if source_x and newwidth not set, assume source_x is zero
		if (empty($x) && empty($newwidth)) {
			$x = 0;
		//if crop co-ordinate not given then work one out, going from centre of the image
		} elseif (empty($x)) {
			// find centre of image
			$centre_x = $this->width / 2;
			// move x half of the new width back from the centre
			$x = $centre_x - (ceil($newwidth / 2));
		}

		// if new width not set then assume same as old width
		if (empty($newwidth)) {
			$newwidth = $this->width;
		}

		// if source_y and newheight not set, assume source_y is zero
		if (empty($y) && empty($newheight)) {
			$y = 0;
		//if crop co-ordinate not given then work one out, going from centre of the image
		} elseif (empty($y)) {
			// find centre of image
			$centre_y = $this->height / 2;
			// move y half of the new height back from the centre
			$y = $centre_y - (ceil($newheight / 2));
		}

		// if new height not set then assume same as old height
		if (empty($newheight)) {
			$newheight = $this->height;
		}

		// condition : only widen if original width is larger than new width
		if ($this->width < $newwidth) {
			$newwidth = $this->width;
			$x = $this->x;
		// condition : only heighten if original height is larger than new height
		} elseif ($this->height < $newheight) {
			$newheight = $this->height;
			$y = $this->y;
		}

		// set source and destination coordinates
		$this->setSourceCoordinates($x, $y);
		$this->setDestinationCoordinates($newx, $newy);

		// set source and destination dimensions, select from source image matching destination image
		$this->setSourceDimensions($newwidth, $newheight);
		$this->setDestinationDimensions($newwidth, $newheight);

		// save image to filesystem
		return $this->saveImage();
	}
	
	/**
	 * Given a corner radius, this will round the corners of an image and convert to png
	 *
	 * @param int $radius Radius of the corners on image
	 *
	 */
	public function roundCornersToRadius($radius = 5) {
		// create an image with a circle in it, to use as the rounded corners
		$maskCorners = imageCreateTrueColor($radius * 6, $radius * 6);
		$transparentColor = imageColorAllocate($maskCorners, 255, 255, 255);
		imageFilledEllipse($maskCorners, $radius * 3, $radius * 3, $radius * 4, $radius * 4, $transparentColor);
		
		// create a mask, the same size as the source image
		$maskProper = imageCreateTrueColor($this->width, $this->height);
		imageFilledRectangle($maskProper, 0, 0, $this->width, $this->height, $transparentColor);

		// copy circle quadrants to each of the corners of $maskProper
		imageCopyResampled($maskProper, $maskCorners, 0, 					  0, 					   $radius,		$radius, 	 $radius, $radius, 	   $radius * 2, $radius * 2);
		imageCopyResampled($maskProper, $maskCorners, 0, 					  $this->height - $radius, $radius, 	$radius * 3, $radius, $radius, 	   $radius * 2, $radius * 2);
		imageCopyResampled($maskProper, $maskCorners, $this->width - $radius, $this->height - $radius, $radius * 3, $radius * 3, $radius, $radius, 	   $radius * 2, $radius * 2);
		imageCopyResampled($maskProper, $maskCorners, $this->width - $radius, 0, 					   $radius * 3, $radius, 	 $radius, $radius, 	   $radius * 2, $radius * 2);

		// clean resources
		imageDestroy($maskCorners);
		
		// now we need a placeholder image to merge the source and the mask into
		$maskBlenderTemp = imageCreateTrueColor($this->width, $this->height);
		$backgroundColor = imageColorAllocate($maskBlenderTemp, 0, 0, 0);
		imageFilledRectangle($maskBlenderTemp, 0, 0, $this->width, $this->height, $backgroundColor);

		// preserve transparency
		imageAlphaBlending($maskBlenderTemp, false);
		imageSaveAlpha($maskBlenderTemp, true);
		
		// loop through the mask and the image.  for each pixel we want the rgb value from the image, and the alpha value from the mask 
		for ($x = 0; $x < $this->width; $x++) {
			for ($y = 0; $y < $this->height; $y++) {
				$realPixel = $this->getPixelColor($this->image, $x, $y);
				$maskPixel = $this->grayscalePixel($this->getPixelColor($maskProper, $x, $y));
				$maskAlpha = 127 - (floor($maskPixel['red'] / 2) * (1 - ($realPixel['alpha'] / 127)));
				$newColor = imageColorAllocateAlpha($maskBlenderTemp, $realPixel['red'], $realPixel['green'], $realPixel['blue'], intval($maskAlpha));
				imageSetPixel($maskBlenderTemp, $x, $y, $newColor);
			}
		}

		// tidy
		imageDestroy($this->image);
		imageDestroy($maskProper);

		// done!  save the image
		$saved = imagePng($maskBlenderTemp, $this->destination) ? true : false;

		// tidy
		imageDestroy($maskBlenderTemp);
		
		return $saved;
	}


	/**
	 * Given values for red, green, and blue returns a grayscale equivalent
	 *
	 * @param int $red Red colour value
	 * @param int $green Green colour value
	 * @param int $blue Blue colour value
	 *
	 */
	function grayscaleValue($red, $green, $blue) 
	{
		return round(($red * 0.30) + ($green * 0.59) + ($blue * 0.11));
	}


	/**
	 * Given a pixel with rgb values, converts to grayscale
	 *
	 * @param array $pixel contains rgb values
	 *
	 */
	function grayscalePixel($pixel)
	{
		$gray = $this->grayscaleValue($pixel['red'], $pixel['green'], $pixel['blue']);
		return array('red'=>$gray, 'green'=>$gray, 'blue'=>$gray);
	}


	/**
	 * Gets the pixel colour of an image at the given xy coordinates
	 *
	 * @param resource $img reference to an image resource
	 * @param int $x x co-ordinate
	 * @param int $y y co-ordinate
	 */	
	function getPixelColor(&$img, $x, $y) {
		return imageColorsForIndex($img, imageColorAt($img, $x, $y));
	}
}
