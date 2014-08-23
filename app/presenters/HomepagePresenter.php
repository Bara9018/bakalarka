<?php

namespace App;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

//	private $user;
//	
//	public function actionDefault($id) {
//		$model = $this->context->RegistrationModel; /* @var $model \RegistrationModel */
//		$user = $model->getById($id);
//		if (!is_object($user)) {
//			$this->flashMessage('Zvoleny uzivatel neexistuje', 'error');
//			$this->redirect(':sign:in');
//		}
//		$this->user = $user;
//	}

	public function renderDefault()
	{
//		$this->template->user = $this->user;
		$this->template->anyVariable = 'any value';
	}

}
