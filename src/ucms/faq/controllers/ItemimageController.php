<?php

namespace ucms\faq\controllers;


class itemimageController extends \ucms\visualiser\controllers\ImageController {
	protected function getVisualised($visualised_id) {
    $manager = $this->module->getPlugin('uorm')->getManager('Faq');
    return $manager->get('Item', $visualised_id);
	}
}