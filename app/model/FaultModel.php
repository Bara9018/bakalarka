<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FaultModel
 *
 * @author Barbora
 */
class FaultModel {
	private $database;
	
	public function __construct(DibiConnection $db) {
		$this->database=$db;
	}
	
	public function addFault($data){
		$result = $this->database->insert('fault', $data);
		return $result->execute();
	}
	
	public function FaultPrint(){
		return $this->database->select('* from fault');
	}
}
