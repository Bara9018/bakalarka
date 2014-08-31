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
class CarsPresenter extends SecurePresenter {

	private $list;

	protected function createComponentCarsForm() {
		$form = new Nette\Application\UI\Form();

		$preparedData = array(
			'spz' => NULL,
			'years' => NULL,
			'capacity' => NULL,
			'fuel' => NULL,
			'created' => FALSE,
		);

		if (is_object($this->list)) {
			$preparedData = array(
				'spz' => $this->list->spz,
				'years' => $this->list->years,
				'capacity' => $this->list->capacity,
				'fuel' => $this->list->fuel,
				'created' => TRUE,
			);
		}

		$form->addText('spz', 'Špz')->setDefaultValue($preparedData['spz']);
		$form->addText('years', 'Years')->setDefaultValue($preparedData['years']);
		$form->addText('capacity', 'Nosnosť')->setDefaultValue($preparedData['capacity']);
		$form->addText('fuel', 'Palivo')->setDefaultValue($preparedData['fuel']);
		$form->addHidden('created',$preparedData['created']);

		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->carsFormSucceeded;
		return $form;
	}

	public function carsFormSucceeded($form) {
		$values = $form->getValues();

		$cars = array(
			'spz' => $values->spz,
			'years' => $values->years,
			'capacity' => $values->capacity,
			'fuel' => $values->fuel
		);

		try {
			$carsModel = $this->context->CarsModel; /* @var $carsModel \CarsModel */
			if ($values->created == FALSE) {
				$newCar = $carsModel->addCar($cars);
			} else {
				$newCar=$carsModel->CarsUpdate($values->spz, $cars);
			}
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Cars:');
	}

	public function renderPrintCars() {
		$carsModel = $this->context->CarsModel; /* @var $carsModel \CarsModel */
		$list = $carsModel->CarsPrint();
		$this->template->list = $list;
	}

	public function renderDetail($spz) {
		$carsModel = $this->context->CarsModel; /* @var $carsModel \CarsModel */
		$list = $carsModel->CarsPrint($spz);
		$this->template->list = $list;
	}

	protected function createComponentAddFaultForm() {
		$form = new Nette\Application\UI\Form();

		$form->addText('spz', 'Spz');
		$form->addText('description', 'Popis');
		$form->addText('parts', 'Náhradné diely');
		$form->addText('service', 'Servis');
		$form->addText('sum', 'Suma');
		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->addFaultSucceeded;
		return $form;
	}

	public function addFaultSucceeded($form) {
		$values = $form->getValues();

		$fault = array(
			'spz' => $values->spz,
			'description' => $values->description,
			'parts' => $values->parts,
			'service' => $values->service,
			'sum' => $values->sum
		);

		try {
			$faultModel = $this->context->FaultModel; /* @var $faultModel \FaultModel */
			$newFault = $faultModel->addfault($fault);
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Cars:detail');
	}

	public function renderFaultDetail($spz) {
		$faultModel = $this->context->FaultModel; /* @var $carsModel \CarsModel */
		$list = $faultModel->FaultPrint($spz);
		$this->template->list = $list;
	}

	public function actionEditCars($spz) {
		$carsModel = $this->context->CarsModel;  /* @var $carsModel \CarsModel */
		$list = $carsModel->DetailPrint($spz);
		if (!is_object($list)) {
			$this->flashMessage('Dany parameter neexistuje');
			$this->redirect('Cars:');
		}
		$this->list = $list;
	}

}
