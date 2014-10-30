<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransportModel
 *
 * @author Barbora
 */
class TransportModel {

	private $delivery;
	private $total_quantity;

	public function calculate($data) {
		$from = $data['start_date'];
		$to = $data['end_date'];
		$period = $data['period'];
		$day = $from->diff($to);
		$days = $day->days++;
		$this->delivery = round($days / $period);
		return $this->delivery;
	}

	public function amount($data) {
		$quantity = $data->quantity;
		$total_quantity = $quantity * $this->delivery;
		$this->total_quantity=$total_quantity;

		return $this->total_quantity;
	}
	
	public function dateArray($data){
		$dates=array();
		$date=clone $data['start_date'];
		$dates[0]=$data['start_date'];
		for ($i=1;$i<=$this->delivery;$i++){
			$dates[$i]=$date->add(new DateInterval('P' . $data['period'] . 'D'));
			$date=clone $dates[$i];
		}
		return $dates;
	}

}
