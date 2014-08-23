<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Material
 *
 * @author Barbora
 */
class Material {
	private $database;
	
	public function __construct(DibiConnection $db) {
		$this->database=$db;
	}
	
	public function addMaterial($data){
		$result = $this->database->insert('material', $data);
		return $result->execute();
	}
	
	public function MaterialPrint(){
		return $this->database->select('* from material');
	}
}
