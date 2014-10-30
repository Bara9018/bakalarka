<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeModel
 *
 * @author Barbora
 */
class EmployeeModel {
	private $database;
	
	public function __construct(DibiConnection $db) {
		$this->database=$db;
	}
	
	public function getPeople(){
		return $this->database->select('*')->from('users');
	}
	
	public function getEmployee(){
		$role=3;
		return $this->database->select('*')->from('users')->where('role = %i',  $role);
	}

}
