<?php

namespace App;

use Nette,
    Model;
use Nette\Forms\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends SecurePresenter {

    public function renderVypis() {
	$registrationModel = $this->getContext()->getService('registrationModel'); /* @var $registrationModel \RegistrationModel */
	$person = $registrationModel->people();
	$this->template->person = $person;
    }

}
