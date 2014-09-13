<?php

namespace App;

use Nette,
	App\Model;
//	Nella\Forms\Controls;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderPresenter
 *
 * @author Barbora
 */
class OrderPresenter extends SecurePresenter {

	private $setInfo;
	private $selectId;
	private $order;

	const
			width = 'width',
			height = 'height',
			depth = 'depth';

	public function createComponentSetInfoForm() {
		$form = new Nette\Application\UI\Form();

		$form->addText('company', 'Firma');
		$form->addText('residence', 'Sidlo');
		$form->addText('ico', 'ICO');
		$form->addText('address','Adresa dodania');
		$form->addText('date','Datum')->setAttribute('class', 'datepicker');

		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'setInfoSubmitted');

		return $form;
	}

	public function setInfoSubmitted($form) {
		$values = $form->getValues();

		$info = array(
			'company' => $values->company,
			'residence' => $values->residence,
			'ico' => $values->ico,
			'address'=>$values->address,
			'date'=>$values->date,
		);

		$this->setInfo = $info;
		$this->template->infoSubmit = TRUE;
		$this['selectMaterialForm'];
	}

	public function createComponentSelectMaterialForm() {
		$form = new Nette\Application\UI\Form();

		$order = $this->context->MaterialModel;  /* @var $order \MaterialModel */
		$offer = $order->getOrder();

		foreach ($offer as $row) {
			$form->addCheckbox($row->id, $row->name);
		}
		$form->addHidden('info', base64_encode(serialize($this->setInfo)));

		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'selectMaterialSubmitted');
		return $form;
	}

	public function selectMaterialSubmitted($form) {
		$values = $form->getValues();

		$selectedMaterialId = array();
		$this->setInfo = $values->info;
		unset($values->info);

		foreach ($values as $key => $hodnota) {
			if ($hodnota == TRUE) {
				$selectedMaterialId[] = $key;
			}
		}

		$this->selectId = $selectedMaterialId;
		$this->template->selectMaterial = TRUE;
		$this['setMeasurements'];
	}

	public function createComponentSetMeasurements() {
		$form = new Nette\Application\UI\Form();

		$post = $this->getHttpRequest()->getPost();
		if (isset($post['setMeasurementsSubmitted'])) {
			$this->separateId($post);
		}
		$selectedId = $this->context->MaterialModel;
		$array = $selectedId->getOffer($this->selectId);
		$this->template->id=$array;

		foreach ($array as $row) {
			$form->addText($row->id . self::width, 'Sirka');
			$form->addText($row->id . self::height, 'Vyska');
			$form->addText($row->id . self::depth, 'Hlbka');
		}
		$form->addHidden('setMeasurementsSubmitted');
		$form->addHidden('info',  $this->setInfo);
		$form->addHidden('id',base64_encode(serialize($this->selectId)));

		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'setMeasurementsSubmitted');

		return $form;
	}

	public function setMeasurementsSubmitted($form) {
		$values = $form->getValues();
		$order=$this->createArray($values);
		$this->order=$order;

		$this->setInfo=$values->info;
		$this->selectId=$values->id;
		$this->template->info= unserialize(base64_decode($this->setInfo));
		$this->template->selectId= unserialize(base64_decode($this->selectId));
		$this->template->order=$order;
		$this->template->setMeasurements=TRUE;
	}

	private function separateId($post) {
		foreach ($post as $key => $value) {
			$matches = array();
			preg_match('/^[\d]+/', $key, $matches);
			if (isset($matches[0])) {
				$this->selectId[$matches[0]] = $matches[0];
			}
		}
	}
	
	private function createArray($values){
		$offer=array();
		foreach ($values as $key => $value) {
			$id=array();
			$measure=array();
			preg_match('/^[\d]+/', $key,$id);
			if(!isset($id[0])){
				continue;
			}
			preg_match('/[a-z]+$/', $key,$measure);
			if(!isset($measure[0])){
				continue;
			}
			$offer[$id[0]][$measure[0]]=$value;
		}
		return $offer;
	}
	
	public function createComponentSpecialOrder() {
		$form=new Nette\Application\UI\Form();
		
		$form->addText('name','Nazov');
		$form->addText('measure','Rozmery');
		$form->addText('description','Popis');
		$form->addImage('foto','Foto');
		$form->addSubmit('submit','Odoslat');
		$form->onSuccess[]=  callback($this,'specialOfferSubmitted');
		return $form;
	}
	
	public function specialOfferSubmitted($form){
		$values=$form->getValues();
	}
	
	protected function createComponentSendOrder() {
		$form=new Nette\Application\UI\Form();
		
		$form->addSubmit('submit','Odoslat');
		$form->addHidden('info',  $this->setInfo);
		$form->addHidden('id',  $this->selectId);
		$form->addHidden('order',base64_encode(serialize($this->order)));
		
		$form->onSuccess[]=  callback($this,'sendOrderSubmitted');
		return $form;
	}
	
	public function sendOrderSubmitted($form){
		$values=$form->getValues();
		$this->setInfo=  $values->info;
		$this->selectId=  $values->id;
		$this->order=$values->order;
		
		$orderInfo=  $this->context->OrderModel; /* @var $orderInfo \OrderModel */
		$newOrderId=$orderInfo->addOrder(unserialize(base64_decode($this->setInfo)));
		$orderInfo->addMaterial(unserialize(base64_decode($this->order)),$newOrderId);
	}
	
	public function renderGetOrder(){
		$orderModel=  $this->context->OrderModel;  /* @var $orderModel \OrderModel */
		$newModel=$orderModel->getOrder();
		$this->template->newModel=$newModel;
	}
	
	public function renderGetDetailOrder($id){
		$orderModel=  $this->context->OrderModel;  /* @var $orderModel OrderModel */
		$newModel=$orderModel->getDetailOrder($id);
		$this->template->newModel=$newModel;
	}

}
