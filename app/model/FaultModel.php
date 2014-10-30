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
	
	public function FaultPrint($spz){
		return $this->database->select('*')->from('fault')->where('spz = %s', $spz);
	}
	
	public function FaultDetail($id){
		return $this->database->select('*')->from('fault')->where('id=%i',$id)->fetch();
	}

		public function FaultUpdate($id,$data){
		$this->database->update('fault', $data)->where('id=%i',$id)->execute();
	}
}
