<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKController;
use Maximenuck\CKFof;

class MaximenuckControllerItems extends MaximenuckController {

	public function load() {
		// security check
		CKFof::checkAjaxToken($json = false);

		$func = $this->input->get('func', '', 'cmd');
		$type = $this->input->get('type', 'menu', 'cmd');
		if (! $func) return;

		require_once(MAXIMENUCK_PATH . '/helpers/source/' . $type . '.php');
		$className = 'MaximenuckHelpersource' . ucfirst($type);

		return $className::$func();
	}
}
