<?php
/**
 * Truncate helper
 */
class Blackflag_View_Helper_Truncate
{
    /**
     * Truncates a text to a specific length
     *
     * @param string $text
     * @param int $maxLength
     * @param bool $stripTags if html tags are to be stripped
     * @return string
     */
    public function truncate($text, $maxLength = 50, $stripTags = true)
    {   	
    	if (true == $stripTags) {
    		$filterStripTags = new Zend_Filter_StripTags();
			$text = $filterStripTags->filter($text);
		}
    	
    	if (strlen($text) <= $maxLength) {
            return $text;
        } else {
            return substr($text, 0, strrpos(substr($text, 0, $maxLength), ' ')).' [...]';
        }
    }
}