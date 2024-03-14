<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKController;

/**
 * Styles list controller class.
 */
class MaximenuckControllerStyles extends CKController {

	public function getModel($name = 'style', $prefix = 'MaximenuckModel', $config = array()) {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

}