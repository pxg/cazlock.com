<?php
/**
 * View helper to trim text to a space then adds ellipses if desired
 */
class Blackflag_View_Helper_UrlTrim
{
	/**
	 * trims a URL to display just the domain name and tld
	 * @param string $input url to trim
	 * @return string
	 */
	function urlTrim($input) {

		// remove http(s)://
		$input = str_replace('http://','', $input);
		$input = str_replace('https://','', $input);
		
		// remove www.
		$input = str_replace('www.','', $input);
		
		// check for content after a final slash
		$strpos = strpos($input, '/');
		
		// condition : remove everything after the first forward slash / if it exists
		if ($strpos > 0) {
			$input = substr($input, 0, $strpos);
		}
		
		return $input;
	}
}
