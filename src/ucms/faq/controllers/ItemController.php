<?php

namespace ucms\faq\controllers;

class ItemController extends Controller {
  public function actionIndex() {
    $locale = null;
    if ($this->localeCount <= 1) {
      $locale = $this->locale;
    }
  	$this->view->items = $this->faqMgr->getItems($locale);
  }
  
  public function actionRead() {
  	$id = $this->request->getParam('id');
  	$item = $this->faqMgr->getItem($id);
  	if ($item === null) {
  		throw new \ultimo\mvc\exceptions\DispatchException("Item with id '{$id}' does not exist.", 404);
  	}
  	$this->view->item = $item;
  }
  
  public function actionCreate() {
  	$form = $this->module->getPlugin('formBroker')->createForm(
      'item\CreateForm', $this->request->getParam('form', array())
    );
    
    if ($this->request->isPost()){
      if ($form->validate()) {
        $item = $this->faqMgr->create('Item');
        $item->label = $form['label'];
        $item->save();
        
        return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'id' => $item->id));
      }
    }
    
    $this->view->form = $form;
  }
  
  public function actionUpdate() {
  	$id = $this->request->getParam('id');

    $form = $this->module->getPlugin('formBroker')->createForm(
      'item\UpdateForm', $this->request->getParam('form', array())
    );

    if ($this->request->isPost()) {
      if ($form->validate()) {
        $item = $this->faqMgr->get('Item', $id, true);
        $item->label = $form['label'];
        $item->save();
      
        return $this->getPlugin('redirector')->redirect(array('action' => 'read', 'id' => $item->id));
      }
    } else {
      $item = $this->faqMgr->get('Item', $id);
      
      if ($item === null) {
        throw new \ultimo\mvc\exceptions\DispatchException("Item with id '{id}' does not exist.", 404);
      }
      
      $form->fromArray($item->toArray());
    }
    
    $this->view->id = $id;
    $this->view->form = $form;
  }
  
  public function actionDelete() {
  	$id = $this->request->getParam('id');
  	$this->faqMgr->deleteItem($id);
  	return $this->getPlugin('redirector')->redirect(array('action' => 'index'));
  }
}