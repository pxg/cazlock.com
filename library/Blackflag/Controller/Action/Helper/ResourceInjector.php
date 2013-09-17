<?php 
/*
 * Injects bootstrap resources into controller
 * 
 * Example (in controller):
 * 
 * public $dependencies = array(
 * 	'db',
 *  'layout',
 *	'navigation',
 *   );
 *   
 *   then in function $this->navigation etc
 *   
 *   @see http://weierophinney.net/matthew/archives/235-A-Simple-Resource-Injector-for-ZF-Action-Controllers.html
 */
class Blackflag_Controller_Action_Helper_ResourceInjector extends Zend_Controller_Action_Helper_Abstract
{
	protected $_resources;

	public function preDispatch()
	{
		$bootstrap  = $this->getBootstrap();
		$controller = $this->getActionController();

		if (!isset($controller->dependencies)
			|| !is_array($controller->dependencies)
		) {
			return;
		}

		foreach ($controller->dependencies as $name) {
			if (!$bootstrap->hasResource($name)) {
				throw new DomainException("Unable to find dependency by name '$name'");
			}
			$controller->$name = $bootstrap->getResource($name);
		}
	}

	public function getBootstrap()
	{
		return $this->getFrontController()->getParam('bootstrap');
	}
}