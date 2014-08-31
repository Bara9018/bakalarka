<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CarsModel
 *
 * @author Barbora
 */
class CarsModel {
	private $database;
	
	public function __construct(DibiConnection $db) {
		$this->database=$db;
	}
	
	public function addCar($data){
		$result = $this->database->insert('cars', $data);
		return $result->execute();
	}
	
	public function CarsPrint(){
		return $this->database->select('* from cars');
	}
	
	public function DetailPrint($spz){
		return $this->database->select('*')->from('cars')->where('spz = %s', $spz)->fetch();
	}
	
	public function CarsUpdate($spz,$data){
		$this->database->update('cars', $data)->where('spz = %s',$spz)->execute();
	}
}
