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
	
	public function materialPrint(){
		return $this->database->select('* from material');
	}
	
	public function detailPrint($id){
		return $this->database->select('*')->from('material')->where('id = %i', $id)->fetch();
	}
	
	public function materialUpdate($id,$data){
		$this->database->update('material', $data)->where('id = %i',$id)->execute();
	}
	
	public function getOrder(){
		return $this->database->select('*')->from('material')->where('offer=1');
	}
	
	public function getOffer(array $id){
		return $this->database->select('name, id')->from('material')->where('id IN (?)', $id);
	}
}
