<?php

namespace ucms\faq\models;

class Question extends \ultimo\orm\Model {
  public $item_id;
  public $locale = '';
  public $question = '';
  public $answer = '';
  public $datetime = '';
  
  static protected $fields = array('item_id', 'locale', 'question', 'answer', 'datetime');
  static protected $primaryKey = array('item_id', 'locale');
  static protected $relations = array(
    'item' => array('Item', array('item_id' => 'id'), self::MANY_TO_ONE)
  );

}