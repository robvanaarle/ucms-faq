<?php

namespace ucms\faq\models;

class Item extends \ultimo\orm\Model {
	public $id;
  public $label = '';
  
  static protected $fields = array('id', 'label');
  static protected $primaryKey = array('id');
  static protected $autoIncrementField = 'id';
  static protected $relations = array(
    'questions' => array('Question', array('id' => 'item_id'), self::ONE_TO_MANY),
    'images' => array('ItemImage', array('id' => 'visualised_id'), self::ONE_TO_MANY)
  );
}