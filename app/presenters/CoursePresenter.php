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
	
	private $list;

	protected function createComponentCourseForm(){
		$form = new Nette\Application\UI\Form();
		
		$preparedData=array(
			'name'=>NULL,
			'id'=>NULL,
			'created'=>FALSE,
		);
		
		if(is_object($this->list)){
			$preparedData=array(
				'name'=> $this->list->name,
				'id'=> $this->list->id,
				'created'=> TRUE,
			);
		}
		
		$form->addText('name','Nazov')->setDefaultValue($preparedData['name']);
		$form->addHidden('id','id')->setDefaultValue($preparedData['id']);
		$form->addHidden('created',$preparedData['created']);
		$form->addSubmit('submit', 'Uložiť');

		$form->onSuccess[] = $this->courseFormSucceeded;
		return $form;
	}
	
	public function courseFormSucceeded($form){
		$values=$form->getValues();
		
		$course=array(
			'name'=>$values->name,	
			'id'=>$values->id,
		);
		
		try {
			$courseModel=  $this->context->CourseModel; /* @var $courseModel \CourseModel */
			if($values->created==FALSE){
			$newCourse=$courseModel->addCourse($course);
			}else{
				$newCourse=$courseModel->courseUpdate($values->id, $course);
			}
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
		}
//		$this->flashMessage('OK');
		$this->redirect('Course:');
	}
	
	public function renderGetcourse(){
		$courseModel= $this->context->CourseModel;  /* @var $courseModel \CourseModel */
		$list=$courseModel->coursePrint();
		if (!is_object($list)) {
			$this->flashMessage('Zadane ID neexistuje.');
			$this->redirect('Course:');
		}
		$this->template->list=$list;
	}
	
	public function renderDetail($id){
		$courseModel=  $this->context->CourseModel;  /* @var $courseModel \CourseModel */
		$list=$courseModel->detailPrint($id);
		if (!is_object($list)) {
			$this->flashMessage('Zadane ID neexistuje.');
			$this->redirect('Course:');
		}
		$this->template->list=$list;
	}
	
	public function actionEditCourse($id){
		$courseModel=  $this->context->CourseModel; /* @var $courseModel \CourseModel */
		$list=$courseModel->detailPrint($id);
		if (!is_object($list)) {
			$this->flashMessage('Dany parameter neexistuje');
			$this->redirect('Cars:');
		}
		$this->list=$list;
	}
}
