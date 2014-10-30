<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Nette\Utils\Strings;

class RegistrationModel extends \Nette\Object {

	/**
	 *
	 * @var DibiConnection
	 */
	private $database;

	public function __construct(DibiConnection $db) {
		$this->database = $db;
	}

	public function UserRegistration($data) {
		//$this->database->table('users')->insert($data);
//		$comm = dibi::insert('users', $data);
//		$comm->execute();
		$result = $this->database->insert('users', $data);
		return $result->execute();
	}
	
	public function edit($id,$data){
		$this->database->update('users', $data)->where('id = %i',$id)->execute();
	}

	public function createNickName($first, $last) {
		return $username = Strings::substring($first, 0, 1) . Strings::substring($last, 0, 3);
	}

	public function people() {
		return $this->database->table('users');
	}
	
	public function getById($id) {
		return $this->database->select('*')->from('users')->where(array('id' => $id))->fetch();
	}

}
