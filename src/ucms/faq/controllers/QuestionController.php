<?php

namespace ucms\faq\controllers;

class QuestionController extends Controller { 
	public function actionIndex() {
  	$locale = $this->request->getParam('locale', $this->locale);
  	$this->view->questions = $this->faqMgr->getQuestions($locale);
  }
  
  public function actionRead() {
  	$locale = $this->request->getParam('locale', $this->locale);
  	$item_id = $this->request->getParam('item_id', 0);

  	$question = $this->faqMgr->getQuestion($item_id, $locale);
  	
  	if ($question === null) {
  		throw new \ultimo\mvc\exceptions\DispatchException("Question with item_id '{$item_id}' and locale '{$locale}' does not exist.", 404);
  	}
  	
    $this->view->questions = $this->faqMgr->getQuestions($locale);
  	$this->view->question = $question;
  }
  
  public function actionCreate() {
  	$item_id = $this->request->getParam('item_id');
  	$this->view->item_id = $item_id;
  	
  	$availableLocales = $this->faqMgr->getItemAvailableLocales($item_id, $this->locales);
  	if ($availableLocales === null) {
  		throw new \ultimo\mvc\exceptions\DispatchException("Item with item_id '{$item_id}' does not exist.", 404);
  	}
    sort($availableLocales);
    if (empty($availableLocales)) {
    	$this->view->form = null;
    	return;
    }
    
    
  	$form = $this->module->getPlugin('formBroker')->createForm(
      'question\CreateForm',
  	 $this->request->getParam('form', array()),
      array(
        'availableLocales' => $availableLocales
      )
    );
    
    if ($this->request->isPost()){
    	if ($form->validate()) {
	    	$question = $this->faqMgr->create('Question');
	    	$question->item_id = $item_id;
	    	$question->locale = $form['locale'];
	    	$question->question = $form['question'];
	      $question->answer = $form['answer'];
	      $question->datetime = date('Y-m-d H:i:s');
	      $question->save();
	      
	      return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'item_id' => $question->item_id, 'locale' => $question->locale));
    	}
    }
    
    $this->view->form = $form;
  }
  
  public function actionUpdate() {
  	$item_id = $this->request->getParam('item_id');
  	$locale = $this->request->getParam('locale');

    $availableLocales = array_merge($this->faqMgr->getItemAvailableLocales($item_id, $this->locales), array($locale));
    sort($availableLocales);
    $form = $this->module->getPlugin('formBroker')->createForm(
      'question\UpdateForm',
      $this->request->getParam('form', array()),
      array(
        'availableLocales' => $availableLocales
      )
    );

    if ($this->request->isPost()) {
    	if ($form->validate()) {
	      $question = $this->faqMgr->get('Question', array('item_id' => $item_id, 'locale' => $locale), true);
	    	$question->locale = $form['locale'];
	    	$question->question = $form['question'];
	      $question->answer = $form['answer'];
	      $question->datetime = date('Y-m-d H:i:s');
	      $question->save();
      
        return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'item_id' => $question->item_id, 'locale' => $question->locale));
    	}
    } else {
	    $question = $this->faqMgr->get('Question', array('item_id' => $item_id, 'locale' => $locale));
	    
	    if ($question === null) {
	      throw new \ultimo\mvc\exceptions\DispatchException("Question with item_id '{$item_id}' and locale '{$locale}' does not exist.", 404);
	    }
	    
	    $form->fromArray($question->toArray());
    }
    
    $this->view->item_id = $item_id;
    $this->view->locale = $locale;
    $this->view->form = $form;
  }
  
  public function actionDelete() {
  	$item_id = $this->request->getParam('item_id');
    $locale = $this->request->getParam('locale');
    $this->faqMgr->deleteQuestion($item_id, $locale);
    return $this->getPlugin('redirector')->redirect(array('action' => 'index', 'locale' => $locale));
  }
}