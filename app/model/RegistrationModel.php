<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Nette\Utils\Strings;

class RegistrationModel{
    private $database;
    
    public function __construct(\Nette\Database\Context $db) {
	$this->database = $db;
    }
    
    public function UserRegistration($data){
	$this->database->table('users')->insert($data);
    }
    
    public function createNickName($first, $last) {
	return $username = Strings::truncate($last, 5);
    }
    
    public function people(){
	return $this->database->table('users');
    }
}

