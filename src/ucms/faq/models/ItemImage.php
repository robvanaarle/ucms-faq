<?php

namespace ucms\faq\models;

class ItemImage extends \ucms\visualiser\models\Image {
	static protected $relations = array(
	  'item' => array('Item', array('visualised_id' => 'id'), self::MANY_TO_ONE)
  );
}