<?php

namespace ucms\faq\controllers;

class Controller extends \ultimo\mvc\Controller {
  
  /**
   * @var ucms\bulletin\managers\FaqManager
   */
  protected $faqMgr;
  
  protected $config;
  
  protected $locales;
  protected $localeCount;
  protected $locale;
  
  protected function init() {
    $this->config = $this->module->getPlugin('config')->getConfig('general');
    
    $this->faqMgr = $this->module->getPlugin('uorm')->getManager('Faq');
    $this->locales = $this->module->getPlugin('translator')->getAvailableLocales();
    $this->localeCount = count($this->locales);
    
    switch (count($this->locales)) {
      case 0:
        $this->locale = 'xx';
        break;
      case 1:
        $this->locale = $this->locales[0];
        break;
      default:
        $this->locale = $this->module->getPlugin('translator')->getLocale();
        break;
    }
    
  }
  
  public function beforeAction($actionName) {
    $this->view->localeCount = $this->localeCount;
  }
}