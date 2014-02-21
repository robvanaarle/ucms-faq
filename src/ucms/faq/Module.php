<?php

namespace ucms\faq;

class Module extends \ultimo\mvc\Module implements \ultimo\security\mvc\AuthorizedModule {
	protected function init() {
    $this->setAbstract(true);
    $this->addPartial($this->application->getModule('ucms\visualiser'));
  }
  
  public function getAcl() {
    $acl = new \ultimo\security\Acl();
    $acl->addRole('faq.guest');
    $acl->addRole('faq.admin', array('faq.guest'));
    
    $acl->allow('faq.guest', array('question.index', 'question.read'));
    $acl->allow('faq.admin');
    return $acl;
  }
}