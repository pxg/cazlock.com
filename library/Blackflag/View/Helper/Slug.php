<?php
/**
 * 
 * @author james.turner
 *
 */
class Blackflag_View_Helper_Slug extends Zend_View_Helper_Abstract
{
	
	public function __construct(){
		$this->slugger = new Blackflag_Filter_Slug();	
	}
	
	public function slug(){
		return $this;
	}

	public function slugify($phrase)
	{
		return $this->slugger->filter($phrase);
	}
	
	public function direct($phrase = null){
		if(null !== $phrase){
			return $this->slugify($phrase);
		}
		return null;
	}
}