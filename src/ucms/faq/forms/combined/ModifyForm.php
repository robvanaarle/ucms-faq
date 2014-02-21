<?php

namespace ucms\faq\forms\combined;

class ModifyForm extends \ultimo\form\Form {
  
  protected function init() {
    $this->appendValidator('question', 'StringLength', array(1, 255));
    $this->appendValidator('answer', 'NotEmpty');
  }
}