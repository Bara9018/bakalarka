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
	
	public function renderPrintCars(){
		$carsModel= $this->context->CarsModel; /* @var $carsModel \CarsModel */
		$list=$carsModel->CarsPrint();
		$this->template->list=$list;
	}
	
	public function renderDetail(){
		$carsModel= $this->context->CarsModel; /* @var $carsModel \CarsModel */
		$list=$carsModel->CarsPrint();
		$this->template->list=$list;
	}
	
	protected function createComponentAddFaultForm(){
		$form = new Nette\Application\UI\Form();
		
		$form->addText('description','Popis');
		$form->addText('parts','Náhradné diely');
		$form->addText('service','Servis');
		$form->addText('sum','Suma');
		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->addFaultSucceeded;
		return $form;
	}
	
	public function addFaultSucceeded($form){
		$values=$form->getValues();
		
		$fault=array(
			'description'=>$values->description,
			'parts'=>$values->parts,
			'service'=>$values->service,
			'sum'=>$values->sum
		);
		
		try {
			$faultModel=  $this->context->FaultModel; /* @var $faultModel \FaultModel */
			$newFault=$faultModel->addfault($fault);
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Cars:detail');
	}
	
	public function renderFaultDetail(){
		$faultModel= $this->context->FaultModel; /* @var $carsModel \CarsModel */
		$list=$faultModel->FaultPrint();
		$this->template->list=$list;
	}
}
