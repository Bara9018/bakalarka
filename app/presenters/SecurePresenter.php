<?php

namespace App;

class SecurePresenter extends BasePresenter {

	public function startup() {
		parent::startup();

		$user = $this->user;
		if (!$user->isLoggedIn()) {
			$this->flashMessage('Užívateľ nie je prihlásený');
			$this->redirect('Homepage:');
		}
		
		if (!$user->isAllowed($this->name . ':' . $this->action)) {
			$this->flashMessage('Užívateľ nemá právo vykonať akciu');
			$this->redirect('Homepage:');
		}
	}

}
