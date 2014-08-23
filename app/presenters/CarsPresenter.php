<?php

namespace App;

use Nette,
	App\Model;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cars
 *
 * @author Barbora
 */
class CarsPresenter extends SecurePresenter{
	//put your code here
	protected function createComponentCarsForm() {
		$form = new Nette\Application\UI\Form();
		
		$form->addText('spz', 'Špz');
		$form->addText('years','Years');
		$form->addText('capacity','Nosnosť');
		$form->addText('fuel','Palivo');
		
		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->carsFormSucceeded;
		return $form;
	}
	
	public function carsFormSucceeded($form){
		$values=$form->getValues();
		
		$cars=array(
			'spz'=>$values->spz,
			'years'=>$values->years,
			'capacity'=>$values->capacity,
			'fuel'=>$values->fuel
		);
		
		try {
			$carsModel=  $this->context->CarsModel; /* @var $carsModel \CarsModel */
			$newCar=$carsModel->addCar($cars);
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Cars:');
	}
}
