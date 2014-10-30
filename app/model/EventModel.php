<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventModel
 *
 * @author Barbora
 */
class EventModel {

	private $database;

	public function __construct(DibiConnection $db) {
		$this->database = $db;
	}

	public function addEvents($data) {
		$result = $this->database->insert('evenement', $data);
		return $result->execute();
	}

	public function getEvent($start, $end) {
		$events = $this->database->select('*')->from('evenement');
		$result = array();
		foreach ($events as $event) {
			$event = new Event($event, NULL);
			if ($event->isWithinDayRange($start, $end)) {
				$result[] = $event->toArray();
			}
		}
		return $result;
	}

	public function updateEvents($data) {
		$id = $data['id'];
		unset($data['id']);
		$this->database->update('evenement', $data)->where('id =%i', $id)->execute();
	}

}
