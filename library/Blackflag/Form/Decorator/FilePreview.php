<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Blackflag_Form_Decorator_FilePreview
 */
class Blackflag_Form_Decorator_FilePreview extends Zend_Form_Decorator_File
{
   /**
     * Render a form preview file (same as Zend file, only calls $view->formFilePreview
     * 
     * @param  string $content 
     * @return string
     */
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof Zend_Form_Element) {
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			return $content;
		}

		$name      = $element->getName();
		$attribs   = $this->getAttribs();
		if (!array_key_exists('id', $attribs)) {
			$attribs['id'] = $name;
		}

		$separator = $this->getSeparator();
		$placement = $this->getPlacement();
		$markup    = array();
		$size      = $element->getMaxFileSize();
		if ($size > 0) {
			$element->setMaxFileSize(0);
			$markup[] = $view->formHidden('MAX_FILE_SIZE', $size);
		}

		if (Zend_File_Transfer_Adapter_Http::isApcAvailable()) {
			$markup[] = $view->formHidden(ini_get('apc.rfc1867_name'), uniqid(), array('id' => 'progress_key'));
		} else if (Zend_File_Transfer_Adapter_Http::isUploadProgressAvailable()) {
			$markup[] = $view->formHidden('UPLOAD_IDENTIFIER', uniqid(), array('id' => 'progress_key'));
		}

		if ($element->isArray()) {
			$name .= "[]";
			$count = $element->getMultiFile();
			for ($i = 0; $i < $count; ++$i) {
				$htmlAttribs        = $attribs;
				$htmlAttribs['id'] .= '-' . $i;
				//$markup[] = $view->formFile($name, $htmlAttribs);
				$value = $element->myValue;  // Oats
                $markup[] = $view->formFilePreview($name, $value, $htmlAttribs); // Oats
			}
		} else {
			//$markup[] = $view->formFile($name, $attribs);
			$value = $element->myValue; // Oats
			$markup[] = $view->formFilePreview($name, $value, $attribs); // Oats
		}

		$markup = implode($separator, $markup);

		switch ($placement) {
			case self::PREPEND:
				return $markup . $separator . $content;
			case self::APPEND:
			default:
				return $content . $separator . $markup;
		}
	}
}
	