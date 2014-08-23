<?php

namespace App;

use Nette,
	App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	public function beforeRender() {
		parent::beforeRender();
		$this->template->user = $this->getUser();
	}

	protected function createComponentSignInForm() {
		$form = new Nette\Application\UI\Form;
		$form->addText('username', 'Username:')
				->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
				->setRequired('Please enter your password.');

		$form->addCheckbox('remember');

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
			if ($this->getUser()->isLoggedIn()) {
				$this->flashMessage('Si prihlaseny');
			} else {
				$this->flashMessage('Nie si prihlaseny');
			}
		} catch (Nette\Security\AuthenticationException $e) {
			$this->flashMessage($e->getMessage());
		}
		$this->redirect('Homepage:');
	}

	protected function createComponentLogOutForm() {
		$form = new Nette\Application\UI\Form;
		$form->addSubmit('logout','Odhlasit');
		$form->onSuccess[] = $this->logOutFormSucceeded;
		return $form;
	}

	public function logOutFormSucceeded($form){
		$values=$form->getValues();
		try{
			$this->getUser()->logout();
		}  catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
		$this->redirect('Homepage:');
	}
	
}
