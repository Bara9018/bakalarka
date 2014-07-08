<?php

use Nette\Security\Permission;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authorizator extends Permission{
    public function __construct() {
	$this->addRole('guest');
	$this->addRole('employee', 'guest');
	$this->addRole('expedient','employee');
	$this->addRole('odoberatel', 'employee');
	$this->addRole('admin', 'expedient');
    }
}
