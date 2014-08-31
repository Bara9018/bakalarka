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
 * Description of MaterialPresenter
 *
 * @author Barbora
 */
class MaterialPresenter extends SecurePresenter {

	private $list;

	public function actionMaterial() {
		
	}

	protected function createComponentSaveMaterialForm() {
		$form = new Nette\Application\UI\Form();

		$preparedData = array(
			'id' => NULL,
			'name' => NULL,
			'quantity' => null,
			'section' => null,
			'description' => null,
			'weight' => null,
			'created' => FALSE,
		);

		if (is_object($this->list)) {
			$preparedData = array(
				'id' => $this->list->id,
				'name' => $this->list->name,
				'quantity' => $this->list->quantity,
				'section' => $this->list->section,
				'description' => $this->list->description,
				'weight' => $this->list->weight,
				'created' => TRUE,
			);
		}

		$form->addText('id', 'id')->setDefaultValue($preparedData['id']);
		$form->addText('name', 'Nazov')->setDefaultValue($preparedData['name']);
		$form->addText('quantity', 'Množstvo')->setDefaultValue($preparedData['quantity']);
		$form->addText('section', 'Sekcia')->setDefaultValue($preparedData['section']);
		$form->addText('description', 'Popis')->setDefaultValue($preparedData['description']);
		$form->addText('weight', 'Váha')->setDefaultValue($preparedData['weight']);
		$form->addHidden('created',$preparedData['created']);

		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->saveMaterialFormSucceeded;
		return $form;
	}

	public function saveMaterialFormSucceeded($form) {
		$values = $form->getValues();

		$material = array(
			'id' => $values->id,
			'name' => $values->name,
			'quantity' => $values->quantity,
			'section' => $values->section,
			'description' => $values->description,
			'weight' => $values->weight,
		);

		try {
			$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
			if ($values->created == FALSE) {
				$newMaterial = $materialModel->addMaterial($material);
			}else{
				$newMaterial = $materialModel->MaterialUpdate($values->id,$material);
			}
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Material:');
	}

	public function renderPrintmaterial() {
		$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
		$list = $materialModel->MaterialPrint();
		$this->template->list = $list;
	}

	public function renderDetail($id) {
		$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
		$list = $materialModel->DetailPrint($id);
		$this->template->list = $list;
	}

	public function actionEdit($id) {
		$materialModel = $this->context->MaterialModel;  /* @var $materialModel \Material */
		$list = $materialModel->DetailPrint($id);
		if (!is_object($list)) {
			$this->flashMessage('Daný parameter neexistuje');
			$this->redirect('Material');
		}
		$this->list = $list;
	}

}
