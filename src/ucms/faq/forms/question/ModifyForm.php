<?php

namespace ucms\faq\forms\question;

class ModifyForm extends \ultimo\form\Form {
  
  protected function init() {
    $this->appendValidator('locale', 'InArray', array($this->getConfig('availableLocales')));
    $this->appendValidator('question', 'StringLength', array(1, 255));
    $this->appendValidator('answer', 'NotEmpty');
  }
}