<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authenticator extends \Nette\Security\Permission {
	public function __construct() {
		$this->addRole('guest');
		$this->addRole('customer', 'guest');
		$this->addRole('employee', 'guest');
		$this->addRole('expedient','employee');
		$this->addRole('admin','expedient');
		
		$this->addResource('Sign:registration');
		$this->addResource('Material:default');
		$this->addResource('Material:add');
		$this->addResource('Material:printmaterial');
		$this->addResource('Material:detail');
		$this->addResource('Cars:default');
		$this->addResource('Cars:add');
		$this->addResource('Cars:printcars');
		$this->addResource('Cars:detail');
		$this->addResource('Cars:addfault');
		$this->addResource('Cars:faultdetail');
		$this->addResource('Course:add');
		$this->addResource('Course:default');
		$this->addResource('Course:detail');
		$this->addResource('Course:getcourse');
		$this->addResource('Material:edit');
		$this->addResource('Cars:editcars');
		$this->addResource('Cars:editfault');
		$this->addResource('Course:editcourse');
		$this->addResource('Employee:default');
		$this->addResource('Employee:add');
		$this->addResource('Employee:getperson');
		$this->addResource('Order:default');
		$this->addResource('Order:ourorder');
		$this->addResource('Order:specialorder');
		$this->addResource('Employee:setInfo');
		$this->addResource('Order:getorder');
		$this->addResource('Order:getdetailorder');
		$this->addResource('Transport:default');
		$this->addResource('Transport:order');
		$this->addResource('Transport:event');
		$this->addResource('Transport:addEvents');
		$this->addResource('Transport:updateEvent');
		$this->addResource('Transport:email');
		$this->addResource('Homepage:default');
		
		$this->allow('admin','Sign:registration');
		$this->allow('admin','Material:default');
		$this->allow('admin','Material:add');
		$this->allow('admin','Material:printmaterial');
		$this->allow('admin','Material:detail');
		$this->allow('admin','Cars:default');
		$this->allow('admin','Cars:add');
		$this->allow('admin','Cars:printcars');
		$this->allow('admin','Cars:detail');
		$this->allow('admin','Cars:addfault');
		$this->allow('admin','Cars:faultdetail');
		$this->allow('admin','Course:add');
		$this->allow('admin','Course:default');
		$this->allow('admin','Course:detail');
		$this->allow('admin','Course:getcourse');
		$this->allow('admin','Material:edit');
		$this->allow('admin','Cars:editcars');
		$this->allow('admin','Cars:editfault');
		$this->allow('admin','Course:editcourse');
		$this->allow('admin','Employee:default');
		$this->allow('admin','Employee:add');
		$this->allow('admin','Employee:getperson');
		$this->allow('customer','Order:default');
		$this->allow('customer','Order:ourorder');
		$this->allow('customer','Order:specialorder');
		$this->allow('guest','Transport:event');
		$this->allow('guest','Transport:addEvents');
		$this->allow('guest','Transport:updateEvent');
		$this->allow('guest','Homepage:default');
		$this->allow('expedient','Material:printmaterial');
		$this->allow('expedient','Material:default');
		$this->allow('expedient','Material:detail');
		$this->allow('employee','Employee:setInfo');
		$this->allow('employee','Employee:default');
		$this->allow('expedient','Order:getorder');
		$this->allow('expedient','Order:default');
		$this->allow('expedient','Order:getdetailorder');
		$this->allow('expedient','Transport:default');
		$this->allow('expedient','Transport:order');
		$this->allow('expedient','Transport:email');
	}
}
