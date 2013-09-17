<?php

/**
* Application bootstrap
* 
* @uses    Zend_Application_Bootstrap_Bootstrap
* @package Application
*/
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	/**
	* Bootstrap autoloader for application resources
	* 
	* @return Zend_Application_Module_Autoloader
	*/
	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Default',
			'basePath'  => dirname(__FILE__),
		));
		
		$this->_resourceLoader = new Zend_Loader_Autoloader_Resource(array(
			'namespace' => 'Default_',
			'basePath' => dirname(__FILE__),
		));

		$this->_resourceLoader->addResourceTypes (array(
			'plugin' => array(
				'path' => 'plugins',
				'namespace' => 'Plugin',
			),
		));
		
		return $autoloader;
	}
	
	/**
	* Bootstrap the Registry
	* 
	* @return void
	*/
	protected function _initRegistry()
	{
		$registry = new Zend_Registry();
		return $registry;
	}
	
	/**
	 * Bootstrap the Config
	 * 
	 * @return void
	 */
	protected function _initConfiguration()
	{
		Zend_Registry::set('configuration', new Zend_Config($this->getOptions()));
	}

	/**
	 * Bootstrap cache
	 * 
	 * @return void
	 */
	protected function _initCache()
	{
		// only use cache for production
		//if ($this->_application->getEnvironment() == 'production') {
			$this->bootstrap('cachemanager');
			Zend_Registry::set('Cache', $this->getResource('cachemanager')->getCache('app'));
		
			Zend_Translate::setCache(Zend_Registry::get('Cache'));
			Zend_Locale::setCache(Zend_Registry::get('Cache'));
			Zend_Db_Table_Abstract::setDefaultMetadataCache(Zend_Registry::get('Cache'));
			Zend_Date::setOptions(array('cache' => Zend_Registry::get('Cache')));	
		//}
	}
		
	/**
	 * Bootstrap translations
	 * 
	 * @return void
	 */
	protected function _initTranslation() 
	{

		// Bootstrap dependencies
		$dependencies = array('registry', 'locale', 'translate');
		if ($this->_application->getEnvironment() == 'production') {
			array_push($dependencies, 'cache');
		}
		$this->bootstrap($dependencies);
		
		// get the language from the config file
		$config = Zend_Registry::get('configuration');
	
		$translate = $this->getResource('translate');
		$translate->addTranslation(APPLICATION_PATH . '/language/en-GB/default.mo', 'en');
		
		// Set up logging for unfound translations.
		//if ($this->_application->getEnvironment() != 'production') {
			
			// Create a log instance
			$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/data/logs/translations.log');
			$log    = new Zend_Log($writer);

			// Attach it to the translation instance
			$translate->setOptions(array(
			    'log'             => $log,
			    //'logUntranslated' => true));
				'logUntranslated' => false));
		//}

		// Put the translate class into the registry.
		Zend_Registry::set('Zend_Translate', $translate);
		
		// list of navigation and route translations for poedit
		$this->translate('Home');	
	}
	
	/**
	* Set the default application timezone
	*
	* @return void
	*/
	protected function _initDate()
	{
		// Bootstrap dependencies
		$this->bootstrap(array('registry', 'translation'));	
		date_default_timezone_set('Europe/London');		
	}

	/**
	* Set system locale
	*
	* @return void
	*/
	protected function _initSystemLocale()
	{
		$this->bootstrap('locale');
		setlocale(LC_CTYPE, $this->getResource('locale')->toString() . '.utf-8');
	}
	
	/**
	 * Bootstrap routes.
	 */
	protected function _initRoutes()
	{
		// Bootstrap dependencies
		$this->bootstrap(array('router', 'translation'));

		// set translator and locale (set in config)
		Zend_Controller_Router_Route::setDefaultTranslator(Zend_Registry::get('Zend_Translate'));
		Zend_Controller_Router_Route::setDefaultLocale(Zend_Registry::get('Zend_Locale'));
	}

	/**
	 * Bootstrap DbAdapter
	 * throughout the application.
	 */
	protected function _initDbAdapter()
	{
		$dependencies = array('db');
		if ($this->_application->getEnvironment() == 'production') {
			array_push($dependencies, 'cache');
		}
		$this->bootstrap($dependencies);
		$db = $this->getPluginResource('db');
			
		// set for easy access
		Zend_Registry::set('db', $db->getDbAdapter());
		
		// ensure UTF8 encoding
		$db->getDbAdapter()->query("SET NAMES 'utf8'");
	}
	
	/**
	 * Bootstrap the view placeholders
	 * 
	 * @return void
	 */
	protected function _initViewHelpers()
	{
		// Bootstrap dependencies
		$this->bootstrap(array('translate', 'view', 'configuration', 'registry'));
		
		$translate = $this->getResource('translate');
		$view = $this->getPluginResource('view')->getView();
		$config = Zend_Registry::get('configuration');

		// obscure mailto
		//$view->addFilter('ObfuscateMailto');
		
		$view->doctype($config->doctype);
		
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset='.$view->getEncoding());
		$view->headMeta()->appendHttpEquiv('imagetoolbar', 'no');
		//$view->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=8');
		$view->headMeta()->appendName('MSSmartTagsPreventParsing', 'true');
		$view->headMeta()->appendName('viewport', 'width=device-width, minimum-scale=1.0, maximum-scale=1.0');
		$view->headMeta()->appendName('description', $config->head->meta->description);
		$view->headMeta()->appendHttpEquiv('Content-Language', $translate->getLocale());

		$view->headTitle( $config->head->title, 'PREPEND');
		$view->headTitle()->setSeparator(' | ');
		
		// Add favicon
		$view->headLink()->headLink(array(
			'rel' => 'icon',
			'type' => 'image/x-icon',
			'href' => $view->baseUrl('/_includes/icons/favicon.ico')
		));
		
		$view->headLink()->headLink(array(
			'rel' => 'shortcut icon',
			'type' => 'image/x-icon',
			'href' => $view->baseUrl('/_includes/icons/favicon.ico')
		));

		return $view;	
	} 

	/**
	* Bootstrap the Acl
	* 
	* @return void
	*/
	protected function _initAcl()
	{
		$this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        
        $auth = Zend_Auth::getInstance();
		$acl = new Blackflag_Acl($auth);
		
		// register with front controller
		$frontController->setParam('auth', $auth);
		$frontController->setParam('acl', $acl);
		$frontController->registerPlugin(
			new Blackflag_Controller_Plugin_Acl($auth, $acl)
		);
	}  
	
	/**
	 * Initializes the logging on the application
	 *
	 * @return Zend_Log
	 */
	protected function _initLogger()
	{
		// Bootstrap dependencies ('log' defined in config)
		$this->bootstrap(array('registry', 'log'));
		
		// add to registry for easy access
		$registry = $this->getResource('registry');			
		Zend_Registry::set('logger', $this->getResource('log'));
	}
	
	/**
	 * Mock function so that routes can be translated
	 *
	 * @return string
	 */
	private function translate($phrase){
		// Requires translation.
		if(Zend_Registry::isRegistered('Zend_Translate')){
			$translate = Zend_Registry::get('Zend_Translate');
			return $translate->translate($phrase);
		}
		return $phrase;
	}
	
	
	/**
	* Checks path for containing 'index.php' and removes if found
	* return void;
	*/
	protected static function _initCheckPath()
	{
		$req_uri = $_SERVER["REQUEST_URI"];
		$host = $_SERVER['HTTP_HOST'];	
	
		if(strpos($req_uri, "index.php") !== FALSE){
			$clean_str = str_replace('index.php','',$req_uri);
			$clean_str = str_replace('//','/',$clean_str);
			$new_location = 'http://'.$_SERVER['HTTP_HOST'].$clean_str;
				
			Header("HTTP/1.1 301 Moved Permanently");
			Header("Location: $new_location");
			exit();
		}
	}
	
}