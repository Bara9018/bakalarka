<?php

namespace App;

use Nette,
    Model;
use Nette\Forms\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends SecurePresenter {

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

    public function renderVypis() {
	$registrationModel = $this->getContext()->getService('registrationModel'); /* @var $registrationModel \RegistrationModel */
	$person = $registrationModel->people();
	$this->template->person = $person;
    }

}
