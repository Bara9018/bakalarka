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
 * Description of CoursePresenter
 *
 * @author Barbora
 */
class CoursePresenter extends SecurePresenter {

	protected function createComponentCourseForm(){
		$form = new Nette\Application\UI\Form();
		
		$form->addText('name','Nazov');
		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->courseFormSucceeded;
		return $form;
	}
	
	public function courseFormSucceeded($form){
		$values=$form->getValues();
		
		$course=array(
			'name'=>$values->name,	
		);
		
		try {
			$courseModel=  $this->context->CourseModel; /* @var $courseModel \CourseModel */
			$newCourse=$courseModel->addCourse($course);
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Course:');
	}
	
	public function renderDetail(){
		$courseModel= $this->context->CourseModel;  /* @var $courseModel \CourseModel */
		$list=$courseModel->detailPrint();
		if (!is_object($list)) {
			$this->flashMessage('Zadane ID neexistuje.');
			$this->redirect('Course:');
		}
		$this->template->list=$list;
	}
}
