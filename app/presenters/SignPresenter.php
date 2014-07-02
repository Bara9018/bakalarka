<?php

namespace App;

use Nette,
    Model;
use Nette\Forms\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter {

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm() {
	$form = new Nette\Application\UI\Form;
	$form->addText('username', 'Username:')
		->setRequired('Please enter your username.');

	$form->addPassword('password', 'Password:')
		->setRequired('Please enter your password.');

	$form->addCheckbox('remember', 'Keep me signed in');

	$form->addSubmit('send', 'Sign in');

	// call method signInFormSucceeded() on success
	$form->onSuccess[] = $this->signInFormSucceeded;
	return $form;
    }

    public function signInFormSucceeded($form, $values) {
	$values = $form->getValues();
	if ($values->remember) {
	    $this->getUser()->setExpiration('14 days', FALSE);
	} else {
	    $this->getUser()->setExpiration('20 minutes', TRUE);
	}

	try {
	    $this->getUser()->login($values->username, $values->password);
	    $this->redirect('Homepage:');
	} catch (Nette\Security\AuthenticationException $e) {
	    $form->addError($e->getMessage());
	}
    }

    public function createComponentRegistrationForm() {
	$form = new Nette\Application\UI\Form();

	$form->addText('firstname', 'Meno:');
	$form->addText('lastname', 'Priezvisko:')
		->setRequired('Priezvisko je povinné');
	$form->addText('email', 'E-mail')
		->setRequired('E-mail je povinný')
		->addRule(\Nette\Application\UI\Form::EMAIL, 'Neplatná emailová adresa');
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

	$user = array(
	    'firstname' => $values->firstname,
	    'lastname' => $values->lastname,
	    'email' => $values->email,
	    'password' => $values->password
	);
	$meno = $values->firstname;
	$priezvisko = $values->lastname;

	try {

	    $registrationModel = $this->getContext()->getService('registrationModel');
	    $registrationModel->userRegistration($user);
	    $nickname = $this->getContext()->getService('registrationModel')->createNickName($meno, $priezvisko);
	    $user['username'] = $nickname;
	    $registrationModel->userRegistration($user);
	} catch (Exception $e) {
	    $this->flashMessage('Vyskytla sa chyba: ' . $e->getMessage());
	    $this->redirect('this');
	}
	$this->flashMessage('OK');
	$this->redirect('Homepage:');
    }

    public function renderVypis() {
	$registrationModel = $this->getContext()->getService('registrationModel'); /* @var $registrationModel \RegistrationModel */
	$person = $registrationModel->osoby();
	$this->template->person = $person;
    }

    public function actionOut() {
	$this->getUser()->logout();
	$this->flashMessage('You have been signed out.');
	$this->redirect('in');
    }

}
