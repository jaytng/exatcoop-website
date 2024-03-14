<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKModel;
use Maximenuck\CKFof;

class MaximenuckModelItems extends CKModel {

	public function getItems() {
		$type = $this->input->get('type', 'menu', 'cmd');
		$className = 'MaximenuckHelpersource' . ucfirst($type);

		require_once(MAXIMENUCK_PATH . '/helpers/source/' . $type . '.php');
		return $className::getItems();
	}
}
