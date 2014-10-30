<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderModel
 *
 * @author Barbora
 */
class OrderModel {

	private $database;

	public function __construct(DibiConnection $db) {
		$this->database = $db;
	}

	public function addOrder($data) {
		$result = $this->database->insert('order_info', $data)->execute(dibi::IDENTIFIER);
		return $result;
	}

	public function addMaterial($data, $id) {
		foreach ($data as $row => $item) {
			$item['order_id'] = $id;
			$item['material_id']=$row;
			$result = $this->database->insert('order_material', $item)->execute();
		}
	}

	public function getOrder() {
		return $this->database->select('*')->from('order_info');
	}
	
	public function getOrderId($id){
		return $this->database->select('*')->from('order_info')->where('id =%i',$id)->fetch();
	}

	public function getDetailOrder($id) {
		return $this->database->select('*')->from('order_material')
				->join('material')->on('order_material.material_id=material.id')
				->where('order_id=%i', $id);
	}
	
	public function updateOrder($data,$id){
		$this->database->update('order_info', $data)->where('id=%i',$id)->execute();
	}
	
}
