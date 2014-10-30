<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseModel
 *
 * @author Barbora
 */
class CourseModel {
	private $database;
	
	public function __construct(DibiConnection $db) {
		$this->database=$db;
	}
	
	public function addCourse($data){
		$result = $this->database->insert('course', $data);
		return $result->execute();
	}
	
	public function coursePrint(){
		return $this->database->select('*')->from('course');
	}
	
	public function detailPrint($id){
		return $this->database->select('*')->from('course')->where('id = %i',$id)->fetch();
	}
	
	public function courseUpdate($id,$data){
		$this->database->update('course', $data)->where('id=%i',$id)->execute();
	}
}
