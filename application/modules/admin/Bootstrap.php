<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initAppAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Admin',
			'basePath' => dirname(__FILE__),
		));
	
		return $autoloader;
	}


	/*
	 * set up ACL - limit pages for certain user types (e.g. moderators)
	 *
	 * Warning: this method seems to be called twice 
	 */
	protected function _initAdminAcl()
	{
	
		// use ACL created initially in the main bootstrap
		$acl = Zend_Controller_Front::getInstance()->getParam('acl');

		// get defined resources for admin section from site config
		$config = Zend_Registry::get('configuration');
		$adminPages = $config->resources->navigation->pages->admin->pages->toArray();		
		
		// set privileges for each resource (if not set already)
		foreach($adminPages as $adminPage) {
			if (isset($adminPage['resource'])) {
					
				// use blackflag ACL method
				$acl->assignAccessRule('administrator', $adminPage['resource']);
				$acl->assignAccessRule('moderator', $adminPage['resource'], false);
			}
		}
	}
}