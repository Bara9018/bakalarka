<?php

namespace App;

use Nette,
	App\Model,
	Gmaps,
	Nette\Mail\Message,
	Nette\Mail\SendmailMailer;

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
	private $address;
	private $days;
	private $total_quantity;
	private $date_delivery;
	private $map;
	private $id;
	private $newModel;
	private $carsAray;
	private $cars;
	private $employeeAray;
	private $employee;

	public function actionOrder($id) {
		$this->id = $id;
	}

	public function renderOrder($id) {
		$total_quantity = array();
		$orderModel = $this->context->OrderModel;  /* @var $orderModel OrderModel */
		$newModel = $orderModel->getDetailOrder($id);
		$newOrder = $orderModel->getOrderId($id);

		foreach ($newModel as $row) {
			$name[$row->id] = $row->quantity;
		}

		$transport = $this->context->TransportModel; /* @var $date TransportModel */
		$days = $transport->calculate($newOrder);
		foreach ($newModel as $row) {
			$total_quantity[$row->material_id] = $transport->amount($row);
		}
		$date_delivery = $transport->dateArray($newOrder);
		$newDate = $this->context->EventModel;

		$this->days = $days + 1;

		$this->total_quantity = $total_quantity;
		$this->date_delivery = $date_delivery;
		$this->newModel = $name;

		$this->address = $newOrder->address;
		$this->template->newModel = $newModel;
		$this->template->newOrder = $newOrder;
		$this->template->days = $this->days;
		$this->template->total_quantity = $total_quantity;
		$this->template->date_delivery = $date_delivery;
	}

	public function createComponentDetailForm() {
		$form = new Nette\Application\UI\Form();

//		$form->addHidden('days', $this->days);
		$form->addHidden('newModel', base64_encode(serialize($this->newModel)));

		$form->addHidden('total_quantity', base64_encode(serialize($this->total_quantity)));
		$form->addHidden('date_delivery', base64_encode(serialize($this->date_delivery)));
		$form->addHidden('id', $this->id);

		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'detailFormSubmitted');

		return $form;
	}

	public function detailFormSubmitted($form) {
		$values = $form->getValues();
//		$this->days = $values->days;
		$this->newModel = $values->newModel;
		$this->total_quantity = $values->total_quantity;
		$this->date_delivery = $values->date_delivery;

		$this->id = $values->id;

//		$date_delivery=unserialize(base64_decode($values->date_delivery));
//		foreach ($date_delivery as $row){
//			$prepareData = array(
//				'title' => $this->id,
//				'start' => $row,
//				
//			);
//			$newDate->addEvents($prepareData);
//		}

		$this->template->detailOrder = TRUE;
	}

	public function actionEvent() {
		$eventModel = $this->context->EventModel;  /* @var $eventModel EventModel */
		$get = $this->getHttpRequest()->getQuery();
		$event = $eventModel->getEvent($get['start'], $get['end']);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($event));
	}

	public function actionAddEvents() {
		$post = $this->getHttpRequest()->getPost();
		$eventModel = $this->context->EventModel;
		$eventModel->addEvents($post);
	}

	public function actionUpdateEvent() {
		$post = $this->getHttpRequest()->getPost();
		$eventModel = $this->context->EventModel;
		$eventModel->updateEvents($post);
	}

	public function createComponentCalendarForm() {
		$form = new Nette\Application\UI\Form();

//		$form->addHidden('days', $this->days);
		$form->addHidden('newModel', base64_encode(serialize($this->newModel)));

		$form->addHidden('total_quantity', base64_encode(serialize($this->total_quantity)));
		$form->addHidden('date_delivery', base64_encode(serialize($this->date_delivery)));
		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'calendarFormSubmitted');

		return $form;
	}

	public function calendarFormSubmitted($form) {
		$values = $form->getValues();

//		$this->days = $values->days;
		$this->newModel = $values->newModel;

		$this->date_delivery = $values->date_delivery;
		$this->total_quantity = $values->total_quantity;

		$this->template->calendar = TRUE;
	}

	public function createComponentRouteForm() {
		$form = new Nette\Application\UI\Form();

		$form->addText('from', 'Odkiaľ')->setDefaultValue('Slovensko,Orlov')->setAttribute('id', 'address1');
		$form->addHidden('to')->setDefaultValue($this->address)->setAttribute('id', 'address2');
		$form->addText('via', 'Cez')->setAttribute('id', 'address_via');
		$form->addText('km')->setAttribute('id', 'totalkm');
		$form->addHidden('time')->setAttribute('id', 'time_hidden');
//
//		$form->addHidden('days', $this->days);
		$form->addHidden('newModel', base64_encode(serialize($this->newModel)));
		$form->addHidden('total_quantity', base64_encode(serialize($this->total_quantity)));
		$form->addHidden('date_delivery', base64_encode(serialize($this->date_delivery)));
		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'routeFormSubmitted');

		return $form;
	}

	public function routeFormSubmitted($form) {
		$values = $form->getValues();

		$map = array(
			'from' => $values->from,
			'to' => $values->to,
			'via' => $values->via,
			'km' => $values->km,
			'time' => $values->time,
		);

//		$this->days = $values->days;
		$this->newModel = $values->newModel;
		$this->date_delivery = $values->date_delivery;
		$this->total_quantity = $values->total_quantity;

		$this->map = $map;
		$this->template->route = TRUE;
	}

	public function createComponentEmployeeTransportForm() {
		$form = new Nette\Application\UI\Form();
		$carsAray = array();
		$employeeArray = array();

		$employeeModel = $this->context->EmployeeModel; /* @var $employeeModel EmployeeModel */
		$employee = $employeeModel->getEmployee();

		$carsModel = $this->context->CarsModel; /* @var $carsModel CarsModel */
		$cars = $carsModel->CarsPrint();

		foreach ($employee as $row) {
			$employeeArray[$row['id']] = $row['lastname'];
		}

		foreach ($cars as $row) {
			$carsAray[] = $row['spz'];
		}
		$this->carsAray = $carsAray;
		$this->employeeAray = $employeeArray;
		$form->addSelect('employee', 'Zamestnanci', $employeeArray);
		$form->addSelect('cars', 'Auta:', $carsAray);

//		$form->addHidden('days', $this->days);
		$form->addHidden('newModel', base64_encode(serialize($this->newModel)));
		$form->addHidden('total_quantity', base64_encode(serialize($this->total_quantity)));
		$form->addHidden('date_delivery', base64_encode(serialize($this->date_delivery)));
		$form->addSubmit('submit', 'Next');
		$form->onSuccess[] = callback($this, 'selectEmployeeSubmitted');
		return $form;
	}

	public function selectEmployeeSubmitted($form) {
		$values = $form->getValues();

//		$this->days = $values->days;
		$this->cars = $values->cars;
		$this->newModel = $values->newModel;
		$this->date_delivery = $values->date_delivery;
		$this->total_quantity = $values->total_quantity;
		$this->employee=$values->employee;
		$this->cars=$values->cars;

		$this->template->employee = TRUE;
		$this->template->cars = $this->cars;
		$this->template->carsAray = $this->carsAray;
		$this->template->employees = $values->employee;
		$this->template->employeeAray = $this->employeeAray;
	}

	protected function createComponentFinishForm() {
		$form = new Nette\Application\UI\Form();

		$form->addText('email', 'Odoslať na email');
		$form->addHidden('cars', $this->cars);
		$form->addHidden('carsAray', base64_encode(serialize($this->carsAray)));
		$form->addHidden('employee', $this->employee);
		$form->addHidden('employeeAray', base64_encode(serialize($this->employeeAray)));
		$form->addHidden('newModel', base64_encode(serialize($this->newModel)));
		$form->addHidden('total_quantity', base64_encode(serialize($this->total_quantity)));
		$form->addHidden('date_delivery', base64_encode(serialize($this->date_delivery)));

		$form->addSubmit('submit', 'Finish');
		$form->onSuccess[] = callback($this, 'finishSubmmitted');

		return $form;
	}

	public function finishSubmmitted($form) {
		$values = $form->getValues();
		$model=  $this->context->OrderModel;
		
		$data=array();
		
		$employee= $values->employee;
		$employeeAray=  unserialize(base64_decode($values->employeeAray));

		$cars=  $values->cars;
		$carsAray=  unserialize(base64_decode($values->carsAray));

		$data['employee_id']=$employee;
		$data['car_id']=$carsAray[$cars];
		$new=$model->updateOrder($data,  $this->id);
		
		$template=  $this->createTemplate();
		$template->setFile(__DIR__.'/../templates/Transport/email.latte');
		$template->newModel=  unserialize(base64_decode($values->newModel));
		$template->date_delivery = unserialize(base64_decode($values->date_delivery));

		$mail = new Message;
		$from = $this->getUser()->getIdentity()->data['email'];
		$to = $values->email;
		$mail->setFrom($from)
				->addTo($to)
				->setSubject('Objednavka')
				->setHtmlBody($template);

		$mailer = new Nette\Mail\SmtpMailer(array(
			'host' => 'smtp.gmail.com',
			'username' => 'bakalarka868@gmail.com',
			'password' => '3e9heb77',
			'secure' => 'ssl',
		));

		$this->newModel = unserialize(base64_decode($values->newModel));
		$this->total_quantity = unserialize(base64_decode($values->total_quantity));
		
		$mailer->send($mail);
	}

}
