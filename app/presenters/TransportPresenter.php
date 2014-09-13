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
 * Description of TransportPresenter
 *
 * @author Barbora
 */
class TransportPresenter extends SecurePresenter {
	
	private $material;
	
	public function renderOrder($id){
		$orderModel=  $this->context->OrderModel;  /* @var $orderModel OrderModel */
		$newModel=$orderModel->getDetailOrder($id);
		$this->template->newModel=$newModel;
	}
}
