<?php

namespace ucms\faq\managers;

class FaqManager extends \ultimo\orm\Manager {
  protected function init() {
    $this->registerModelNames(array('Item', 'Question', 'ItemImage'));
  }
  
  public function getItems($locale=null) {
  	$query = $this->selectAssoc('Item')
  	              ->order('id', 'ASC');
  	
  	$params = array();
  	if ($locale !== null) {
  	  $params[':locale'] = $locale;
  	  $query->with('@questions', '@questions.locale = :locale')
  	        ->where('@questions.locale IS NOT NULL');
  	}
    
  	return $query->fetch($params);
  }
  
  public function getItem($id) {
  	return $this->selectAssoc('Item')
  	            ->with('@questions')
  	            ->where('@id = :id')
  	            ->order('@questions.locale')
  	            ->fetchFirst(array(
  	              ':id' => $id
  	            ));
  }
  
  public function deleteItem($id) {
  	return $this->selectAssoc('Item')
                ->with('@questions')
                ->where('@id = :id')
                ->delete(array(
                  ':id' => $id
                ));
  }
  
  public function getQuestions($locale) {
    $localeObj = new \ultimo\util\locale\Locale($locale);
    $language = $localeObj->getLanguage();
    
  	$params = array(':locale' => $locale, ':language' => $language);
  	
  	$query = $this->selectAssoc('Question')
	  	            ->with('@item')
	  	            ->where('@locale = :locale OR @locale = :language')
	  	            ->order('@item.id');
	  
	  // filter double entries, because translations are present in both locale and
	  // language
	  $questions = array();
	  foreach ($query->fetch($params) as $question) {
	    $itemId = $question['item_id'];
	    if (!isset($questions[$itemId]) || $questions['locale'] == $locale) {
	      $questions[$itemId] = $question;
	    }
	  }
	  
	  return $questions;
  }
  
  public function getItemLocales($item_id) {
  	$item = $this->selectAssoc('Item')
  	                ->with('@questions')
  	                ->where('@id = :item_id')
  	                ->fetchFirst(array(
			                ':item_id' => $item_id
			              ));
		if ($item === null) {
			return null;
		}
		
	  $locales = array();
		foreach ($item['questions'] as $question) {
			$locales[] = $question['locale'];
		}
		return $locales;
  }
  
  public function getItemAvailableLocales($item_id, array $locales) {
  	$itemLocales = $this->getItemLocales($item_id);
  	if ($itemLocales === null) {
  		return null;
  	}
  	
  	return array_diff($locales, $itemLocales);
  }
  
  public function getQuestionCount($locale_id) {
    return $this->selectAssoc('Question')
                ->where('@locale_id = :locale_id')
                ->count(array(':locale_id' => $locale_id));
  }
  
  public function getQuestion($item_id, $locale) {
  	return $this->selectAssoc('Question')
                ->with('@item')
                ->with('@item.images')
                ->where('@locale = :locale')
                ->where('@item_id = :item_id')
                ->fetchFirst(array(':locale' => $locale, ':item_id' => $item_id));
  }
  
  public function deleteQuestion($item_id, $locale) {
    return $this->selectAssoc('Question')
                ->where('@locale = :locale')
                ->where('@item_id = :item_id')
                ->delete(array(
                  ':item_id' => $item_id,
                  ':locale' => $locale
                ));
  }
}