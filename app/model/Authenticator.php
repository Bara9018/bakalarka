<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authenticator extends \Nette\Security\Permission {
	public function __construct() {
		$this->addRole('guest');
		$this->addRole('employee', 'guest');
		$this->addRole('expedient','employee');
		$this->addRole('admin','expedient');
		
		$this->addResource('Sign:registration');
		$this->addResource('Material:default');
		$this->addResource('Material:add');
		$this->addResource('Material:printmaterial');
		$this->addResource('Material:detail');
		
		$this->allow('admin','Sign:registration');
		$this->allow('admin','Material:default');
		$this->allow('admin','Material:add');
		$this->allow('admin','Material:printmaterial');
		$this->allow('admin','Material:detail');
	}
}
