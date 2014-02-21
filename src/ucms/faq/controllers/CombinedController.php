<?php

namespace ucms\faq\controllers;

class CombinedController extends Controller {
  
  public function actionCreate() {
    $form = $this->module->getPlugin('formBroker')->createForm(
      'combined\CreateForm', $this->request->getParam('form', array())
    );
    
    if ($this->request->isPost()){
      if ($form->validate()) {
        $item = $this->faqMgr->create('Item');
        $item->label = $form['question'];
        $item->save();
        
        $question = $this->faqMgr->create('Question');
        $question->item_id = $item->id;
        $question->locale = $this->locale;
        $question->question = $form['question'];
        $question->answer = $form['answer'];
        $question->datetime = date('Y-m-d H:i:s');
        $question->save();
        
        return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'controller' => 'question', 'item_id' => $item->id));
      }
    }
    
    $this->view->form = $form;
  }
  
  public function actionUpdate() {
    $item_id = $this->request->getParam('item_id');
    $locale = $this->locale;

    $form = $this->module->getPlugin('formBroker')->createForm(
      'combined\UpdateForm',
      $this->request->getParam('form', array())
    );

    if ($this->request->isPost()) {
      if ($form->validate()) {
        $item = $this->faqMgr->get('Item', $item_id, true);
        $item->label = $form['question'];
        $item->save();
        
        $question = $this->faqMgr->get('Question', array('item_id' => $item_id, 'locale' => $locale), true);
        $question->question = $form['question'];
        $question->answer = $form['answer'];
        $question->datetime = date('Y-m-d H:i:s');
        $question->save();
      
        return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'controller' => 'question', 'item_id' => $question->item_id));
      }
    } else {
      $question = $this->faqMgr->getQuestion($item_id, $locale);
      //$item = $this->faqMgr->get('Item', array('id' => $item_id));
      
      if ($question === null) {
        throw new \ultimo\mvc\exceptions\DispatchException("Question with item_id '{$item_id}' and locale '{$locale}' does not exist.", 404);
      }
      
      $form->fromArray(array_merge($question, $question['item']));
    }
    
    $this->view->images = $this->module->getPlugin('helper')
                         ->getHelper('Visualiser')
                         ->getImages('ItemImage', $item_id);
    $this->view->imageForm = $this->module->getPlugin('formBroker')->createForm(
      'image\CreateForm'
    );
    
    $this->view->item_id = $item_id;
    $this->view->form = $form;
  }
  
  public function actionDelete() {
    $item_id = $this->request->getParam('item_id');
    $this->faqMgr->deleteQuestion($item_id, $this->locale);
    
    if ($this->faqMgr->getQuestionCount($item_id) == 0) {
      $this->faqMgr->deleteItem($item_id);
    }
    
    return $this->getPlugin('redirector')->redirect(array('action' => 'index', 'controller' => 'question'));
  }

}