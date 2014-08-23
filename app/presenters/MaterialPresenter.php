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
class MaterialPresenter extends SecurePresenter{

//	public function beforeRender() {
//		parent::beforeRender();
//		$this->template->material=  $this->getMaterial();
//	}
	
	public function actionMaterial(){
		
	}

	protected function createComponentSaveMaterialForm() {
		$form = new Nette\Application\UI\Form();

		$form->addText('id', 'id');
		$form->addText('name', 'Nazov');
		$form->addText('quantity', 'Množstvo');
		$form->addText('section', 'Sekcia');
		$form->addText('description', 'Popis');
		$form->addText('weight', 'Váha');

		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->saveMaterialFormSucceeded;
		return $form;
	}

	public function saveMaterialFormSucceeded($form) {
		$values = $form->getValues();
		
		$material=array(
			'id'=>$values->id,
			'name'=>$values->name,
			'quantity'=>$values->quantity,
			'section'=>$values->section,
			'description'=>$values->description,
			'weight'=>$values->weight
		);

		try {
			$materialModel=  $this->context->MaterialModel; /* @var $materialModel \MaterialModel */
			$newMaterial=$materialModel->addMaterial($material);
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
		$this->flashMessage('OK');
		$this->redirect('Material:');
	}

}
