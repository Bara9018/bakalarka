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
 * Description of EmployeePresenter
 *
 * @author Barbora
 */
class EmployeePresenter extends SecurePresenter {
	
	public function createComponentRegistrationForm() {
	$form = new Nette\Application\UI\Form();

	$form->addText('firstname', 'Meno:');
	$form->addText('lastname', 'Priezvisko:')
		->setRequired('Priezvisko je povinné');
	$form->addText('email', 'E-mail')
		->setRequired('E-mail je povinný')
		->addRule(\Nette\Application\UI\Form::EMAIL, 'Neplatná emailová adresa');
	$form->addText('role','Rola')
			->setRequired('Rola je povinná');
	$form->addPassword('password', 'Heslo:')
		->setRequired('Zadajte heslo');
	$form->addPassword('passwordConfirm', 'Potvrdenie hesla:')
		->setRequired('Zadajte heslo pre potvrdenie');

	$form->addSubmit('submit', 'Registruj');
	$form->onSuccess[] = callback($this, 'registrationFormSubmitted');

	return $form;
    }

    public function registrationFormSubmitted(Nette\Application\UI\Form $form) {
	$values = $form->getValues();

	// $registrationModel = $this->getService('registrationModel');

	if ($values->password !== $values->passwordConfirm) {
	    $this->flashMessage('Heslá sa nezhodujú.');
	    $this->redirect('this');
	}

	$values->password= \Nette\Security\Passwords::hash($values->password);
	
	$user = array(
	    'firstname' => $values->firstname,
	    'lastname' => $values->lastname,
	    'email' => $values->email,
		'role'=>$values->role,
	    'password' => $values->password
	);
	$meno = $values->firstname;
	$priezvisko = $values->lastname;

	try {

	    $registrationModel = $this->context->RegistrationModel; /* @var $registrationModel \RegistrationModel */
	    $nickname = $registrationModel->createNickName($meno, $priezvisko);
	    $user['username'] = $nickname;
	    $newUser = $registrationModel->userRegistration($user);
	} catch (Exception $e) {
	    $this->flashMessage('Vyskytla sa chyba: ' . $e->getMessage());
	    $this->redirect('this');
	}
	$this->flashMessage('OK');
	$this->redirect('Homepage:');
    }
	
	public function renderGetperson(){
		$employeeModel=  $this->context->EmployeeModel;  /* @var $employeeModel \EmployeeModel */
		$list=$employeeModel->getPeople();
		$this->template->list=$list;
	}
	
	public function createComponentEmployeeInfo() {
		$form=new Nette\Application\UI\Form();
		
		//$form->addText('birthday','Datum narodenia');
		$form->addText('address','Adresa');
		$form->addText('phone','Telefonne cislo');
		$form->addText('account','Cislo uctu');
		
		$form->addSubmit('submit','Odoslat');
		$form->onSuccess[]=  callback($this,'employeeInfoSubmitted');
		return $form;
	}
	
	public function employeeInfoSubmitted($form){
		$values=$form->getValues();
		
		$id=  $this->getUser()->id;
		
		$info=array(
			//'birthday'=>$values->birthday,
			'address'=>$values->address,
			'phone'=>$values->phone,
			'account'=>$values->account,
		);
		
		try{
			$registrationModel=$this->context->RegistrationModel;
			$list=$registrationModel->edit($id,$info);
		}catch(Exception $e){
			$this->flashMessage('Vyskytla sa chyba: ' . $e->getMessage());
			$this->redirect('this');
		}
	}
}
