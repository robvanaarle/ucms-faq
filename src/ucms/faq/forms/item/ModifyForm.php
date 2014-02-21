<?php

namespace ucms\faq\forms\item;

class ModifyForm extends \ultimo\form\Form {
  
  protected function init() {
    $this->appendValidator('label', 'StringLength', array(1, 255));
  }
}