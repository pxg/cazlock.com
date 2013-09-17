<?php
class Blackflag_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	protected $_auth = null;

	protected $_acl = null;

	public function __construct(Zend_Auth $auth, Zend_Acl $acl)
	{
		$this->_auth = $auth;
		$this->_acl = $acl;
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if ($this->_auth->hasIdentity()) {
			$identity = $this->_auth->getIdentity();
			$role = $identity->role;
		} else {
			$role = 'guest';
		}

		// condition : check if an ACL resource exists
		$resource = $this->findResource($request, $this->_acl);

		// condition : check if current user is allowed to see current resource
		if (!$this->_acl->isAllowed($role, $resource)) {
			if ($this->_auth->hasIdentity()) {
				// authenticated, denied access, forward to index
				$request->setModuleName($request->module);
				$request->setControllerName('index');
				$request->setActionName('index');

			} else {
				
				// not authenticated, forward to login form
				$request->setModuleName($request->module);
				$request->setControllerName('user');
				$request->setActionName('login');
			}
		}
	}
	
	
	/*
	 * Detect if an ACL resource exists
	 * Start from action level, and become more generic
	 */
	protected function findResource($request, $acl)
	{
		
		// condition -> resource = module:controller:action
		// e.g. admin:page:add
		$resource = $request->module.":".$request->controller.":".$request->action;
		if ($acl->has($resource)) {
			return $resource;
		}
		
		// condition -> resource = module:controller
		// e.g. admin:page
		$resource = $request->module.":".$request->controller;
		if ($acl->has($resource)) {
			return $resource;
		}
		
		// condition -> resource = module
		// e.g. admin
		$resource = $request->module;
		if ($acl->has($resource)) {
			return $resource;
		}
		
		// no resource found
		return null;
	}
}