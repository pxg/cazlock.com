<?php
/**
* GoogleAnalytics.php
*
* See http://www.scribd.com/doc/2261328/InstallingGATrackingCode
*
* @author     Harold Th�tiot (hthetiot)  - Original
* @author     Richard - Modified to use Async GA code
*/

class BlackFlag_View_Helper_GoogleAnalytics {
	
	/**
	* Tracker options instance
	*/
	static protected $_trackerOptionsByIds = array();

	/**
	* Available Trackers options
	*/
	static protected $_availableOptions = array
	(
		// Standard Options
		'trackPageview',
		'setVar',

		// ECommerce Options
		'addItem',
		'addTrans',
		'trackTrans',

		// Tracking Options
		'setClientInfo',
		'setAllowHash',
		'setDetectFlash',
		'setDetectTitle',
		'setSessionTimeOut',
		'setCookieTimeOut',
		'setDomainName',
		'setAllowLinker',
		'setAllowAnchor',

		// Campaign Options
		'setCampNameKey',
		'setCampMediumKey',
		'setCampSourceKey',
		'setCampTermKey',
		'setCampContentKey',
		'setCampIdKey',
		'setCampNoKey',

		// Other
		'addOrganic',
		'addIgnoredOrganic',
		'addIgnoredRef',
		'setSampleRate',
	);

	/**
	*
	* @param string $trackerId the google analytics tracker id
	* @param array
	*
	* @return $this for more fluent interface
	*/
	public function GoogleAnalytics($trackerId = null, array $options = array())
	{
		if (!is_null($trackerId)) {
			$this->trackPageview($trackerId);

			if (!empty($options)) {
				$this->addTrackerOptions($trackerId, $options);
			}
		}

		return $this;
	}

	/**
	* Alias to _addTrackerOption
	*
	* @param string $optionsName
	* @param array $optionsArgs
	*
	* @return $this for more fluent interface
	*/
	public function __call($optionsName, $optionsArgs)
	{
		if (in_array($optionsName, self::$_availableOptions) === false) {
			throw new Exception('Unknown "' . $optionFunc . '" GoogleAnalytics options');
		}

		if (empty($optionsArgs)) {
			throw new Exception('Missing TrackerId has first Argument on "$this->GoogleAnalytics->' . $optionFunc . '()" function call');
		}

		$trackerId = array_shift($optionsArgs);

		$this->_addTrackerOption($trackerId, $optionsName, $optionsArgs);

		return $this;
	}

	/**
	* Add options from array
	*
	* @param string $trackerId the google analytics tracker id
	* @param array of array option with first value has option name
	*
	* @return $this for more fluent interface
	*/
	public function addTrackerOptions($trackerId, array $options)
	{
		foreach ($options as $optionsArgs) {

			$optionsName = array_shift($optionsArgs);

			$this->_addTrackerOption($trackerId, $optionsName, $optionsArgs);
		}

		return $this;
	}

	/**
	* Add a tracker option
	*
	* @param string $trackerId the google analytics tracker id
	* @param string $optionsName option name
	* @param array $optionsArgs option arguments
	*
	* @return $this for more fluent interface
	*/
	protected function _addTrackerOption($trackerId, $optionsName, array $optionsArgs = array())
	{
		$trackerOptions = &$this->_getTrackerOptions($trackerId);

		array_unshift($optionsArgs, $optionsName);

		$trackerOptions[] = $optionsArgs;

		return $this;
	}

	/**
	* Get tracker's options by tracker id
	*
	* @param string $trackerId the google analytics tracker id
	*
	* @return array an array of options for requested tracker id
	*/
	protected function &_getTrackerOptions($trackerId)
	{
		if (!isset(self::$_trackerOptionsByIds[$trackerId])) {
			self::$_trackerOptionsByIds[$trackerId] = array();
		}

		return self::$_trackerOptionsByIds[$trackerId];
	}

	//
	// Render
	//

	/**
	* Cast to string representation
	*
	* @return string
	*/
	public function __toString()
	{
		return $this->toString();
	}

	/**
	* Rendering Google Anaytics Tracker script
	*/
	public function toString()
	{
		$xhtml = array();
	
		$xhtml[] = '<script type="text/javascript">';
		$xhtml[] = '	//<![CDATA[';
		$xhtml[] = "	var _gaq = _gaq || [];";

		foreach (self::$_trackerOptionsByIds as $trackerId => $options) {

			// set trackerid		
			$xhtml[] = "	_gaq.push(['_setAccount', '".$trackerId."']);";

			// add options
			foreach ($options as $optionsData) {

				// build tracker func call
				$optionName = '_' . array_shift($optionsData);

				// escape options arg
				$optionArgs = array();
				foreach ($optionsData as $arg) {
					$optionArgs[] = is_numeric($arg) ? $arg : "'" . addslashes($arg) . "'";
				}
				
				if (count($optionArgs) > 0) {
					$optionsValues = ", ". implode(',', $optionArgs);
				} else {
					$optionsValues = "";
				}

				// add options
				$xhtml[]  = "	_gaq.push(['".$optionName."'".$optionsValues."]);";
				
			}
		}
  
		$xhtml[] = "	(function() {";
		$xhtml[] = "		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
		$xhtml[] = "		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
		$xhtml[] = "		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
		$xhtml[] = "	})();";
		$xhtml[] = "	//]]>";
		$xhtml[] = " </script>";
		
		return implode("\n\t\t", $xhtml);
	}
}