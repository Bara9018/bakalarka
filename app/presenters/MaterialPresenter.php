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
			'description' => null,
			'technical_drawing'=>NULL,
			'price'=>NULL,
			'created' => FALSE,
			'offer'=> NULL,
		);

		if (is_object($this->list)) {
			$preparedData = array(
				'id' => $this->list->id,
				'name' => $this->list->name,
				'description' => $this->list->description,
				'technical_drawing'=> $this->list->technical_drawing,
				'price'=>  $this->list->price,
				'created' => TRUE,
				'offer'=>  $this->list->offer,
			);
		}

		$form->addText('id', 'id')->setDefaultValue($preparedData['id']);
		$form->addText('name', 'Nazov')->setDefaultValue($preparedData['name']);
		$form->addText('description', 'Popis')->setDefaultValue($preparedData['description']);
		$form->addText('technical_drawing','Technicke parametre')->setDefaultValue($preparedData['technical_drawing']);
		$form->addText('price', 'Cena')->setDefaultValue($preparedData['price']);
		$form->addCheckbox('offer','Pridat do ponuky');
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
			'description' => $values->description,
			'technical_drawing'=>$values->technical_drawing,
			'price'=>$values->price,
			'offer'=>$values->offer,
		);

		try {
			$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
			if ($values->created == FALSE) {
				$newMaterial = $materialModel->addMaterial($material);
			}else{
				$newMaterial = $materialModel->materialUpdate($values->id,$material);
			}
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Material:');
	}

	public function renderPrintmaterial() {
		$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
		$list = $materialModel->materialPrint();
		$this->template->list = $list;
	}

	public function renderDetail($id) {
		$materialModel = $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
		$list = $materialModel->detailPrint($id);
		$this->template->list = $list;
	}

	public function actionEdit($id) {
		$materialModel = $this->context->MaterialModel;  /* @var $materialModel \Material */
		$list = $materialModel->detailPrint($id);
		if (!is_object($list)) {
			$this->flashMessage('Daný parameter neexistuje');
			$this->redirect('Material');
		}
		$this->list = $list;
	}

}
