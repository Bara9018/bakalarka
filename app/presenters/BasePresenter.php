<?php

namespace App;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
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

	$form->addCheckbox('remember','Remember me');

	$form->addSubmit('send', 'Sign in');

	// call method signInFormSucceeded() on success
	$form->onSuccess[] = $this->signInFormSucceeded;
	return $form;
    }

    public function signInFormSucceeded($form) {
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
}
