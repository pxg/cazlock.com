<?php
/**
 * Service helper to use if you require services in your controller.
 * 
 * @author james.turner
 * 
 */
class Blackflag_Controller_Action_Helper_Service 
	extends Zend_Controller_Action_Helper_Abstract 
{
	/**
	 * An array of service objects.
	 * @var array
	 */
	protected $_services = array();
	
	/**
	 * 
	 * @param string $service
	 * @param string $module
	 * @return null|stdClass
	 */
	public function getService($service, $module){
		
		if(!isset($this->_services[$module][$service])){
			$class = implode('_', array(
				ucfirst($module),
				'Service',
				ucfirst($service),
			));
			
			$front = Zend_Controller_Front::getInstance();
			$classPath = $front->getModuleDirectory($module)
							. '/services/' . ucfirst($service) . '.php';
	
			if(!file_exists($classPath)){
				return false;
			}
			
			if(!class_exists($class, true)){
				throw new Zend_Exception("Class {$class} not found in " . basename($classPath));
			}
			
			$this->_services[$module][$service] = new $class();
			
		}
		return $this->_services[$module][$service];
	}
		
	/**
	 * 
	 * @param string $service
	 * @param string $module
	 * @return null|stdClass
	 */
	public function direct($service, $module){
		return $this->getService($service, $module);
	}
}