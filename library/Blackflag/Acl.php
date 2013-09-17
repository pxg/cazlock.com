<?php
class Blackflag_Acl extends Zend_Acl
{
	public function __construct(Zend_Auth $auth)
	{
		// Add Resources

		// Resource #1: Default Module
		$this->add(new Zend_Acl_Resource('default'));
		
		// Resource #2: Member Module
		$this->add(new Zend_Acl_Resource('member'));
		
		// Resource #3: Admin Module
		$this->add(new Zend_Acl_Resource('admin'));

		
		// Add Roles

		// Role #1: Guest
		$this->addRole(new Zend_Acl_Role('guest'));
		
		// Role #2: User (inherits from Guest)
		$this->addRole(new Zend_Acl_Role('member'), 'guest');
		
		// Role #3: User (inherits from Guest)
		$this->addRole(new Zend_Acl_Role('moderator'), 'member');
		
		// Role #4: Author (inherits from Member and Guest)
		$this->addRole(new Zend_Acl_Role('administrator'), 'moderator');

		
		// Assign Access Rules

		// Rule #1 & #2: Guests can access Default Module (admin inherits this)
		$this->allow('guest', 'default');

		// Rule #3 & #4: Members can access member Module (Guests denied by default) (admin inherits this)
		$this->allow('member', 'member');
		
		// Rule #5 & #6: Moderators can access Admin Module (Guests and members denied by default)
		$this->allow('moderator', 'admin');

		// Rule #5 & #6: Admins can access Admin Module (Guests and members denied by default)
		$this->allow('administrator', 'admin');
	}
	
	/**
	 * creates a specified resource and allows the given role to access it
	 */
	public function assignAccessRule($role, $resource, $allow = true)
	{
		// trying to add a resource that already exists causes errors
		if(!$this->has($resource)){
			$this->add(new Zend_Acl_Resource($resource));
		}
		
		// condition : allow access to the resource (the default behaviour)
		if ($allow) {
			$this->allow($role, $resource);
		} else {
			$this->deny($role, $resource);
		}
	}
}