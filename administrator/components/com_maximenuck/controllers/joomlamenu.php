<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKController;
use Maximenuck\CKFof;

class MaximenuckControllerJoomlamenu extends CKController
{

	public function __construct() {
		CKFof::cleanCache('com_menus');
		parent::__construct();
	}

	public function ajaxPublish($model = null) {
		CKFof::checkAjaxToken(false);

		$state   = $this->input->get('state', 1, 'int');
		$ids   = $this->input->get('id', null, 'array');

		$model = $this->getModel();
		$ids = $model->publishItem($ids, 1 - $state);
		if ($ids === false)
		{
			echo '{"status": "0", "message": "' . CKText::_('CK_FAILED_PUBLISH') . '"}';
			exit;
		}
		
		echo '{"status": "1", "ids": "' . implode(',', $ids) . '"}';
		exit;
	}

	public function ajaxCheckin() {
		CKFof::checkAjaxToken(false);

		$id = $this->input->post->get('id', 0, 'int');

		// Get the model
		$model = $this->getModel();

		if ($model->checkin($id) === false) {
			echo '{"status": "0"}';
			exit;
		}

		echo '{"status": "1"}';
		exit;
	}

	public function ajaxSaveTitle() {
		CKFof::checkAjaxToken(false);
		
		// Get the input
		$id   = $this->input->post->get('id', 0, 'int');
		$title = $this->input->post->get('title', '', 'string');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveTitle($id, $title);

		if ($return)
		{
			echo '{"status": "1"}';
		} else
		{
			echo '{"status": "0"}';
		}

		exit;
	}


	public function ajaxSaveParam($id = 0, $param = '', $value = '') {
		CKFof::checkAjaxToken(false);

		$id = $this->input->post->get('id', $id, 'int');
		$param = $this->input->post->get('param', $param, 'string');
		$value = $this->input->post->get('value', $value, 'string');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveParam($id, $param, $value);

		if ($return)
		{
			echo '{"status": "1"}';
		} else
		{
			echo '{"status": "0"}';
		}

		exit;
	}

	public function validatePath() {
		CKFof::checkAjaxToken(false);

		$newpath = $this->input->post->get('newPath', null, 'array');
		$id = $this->input->post->get('id', null, 'int');

		if (!is_array($newpath)) return false;
		$newpath = array_reverse($newpath);

		// Get the model
		$model = $this->getModel();
		
		if (! $model->validateItemPath($newpath, $id)) {
			echo 'pathexists';
			exit;
		}
		
		echo '1';
		exit;
	}

	public function ajaxSaveLevel() {
		CKFof::checkAjaxToken(false);

		$pk   = $this->input->post->get('id', 0, 'int');
		$level = $this->input->post->get('level', 1, 'int');
		$parentid = $this->input->post->get('parentid', 1, 'int');

		// Get the model
		$model = $this->getModel();

		// Save the level
		$return = $model->saveLevel($pk, $level, $parentid);

		if ($return) {
			echo "1";
		} else {
			echo "0";
		}

		exit;
	}

	public function ajaxSaveOrder() {
		CKFof::checkAjaxToken(false);

		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		$lft = $this->input->post->get('lft', array(), 'array');
		$rgt = $this->input->post->get('rgt', array(), 'array');

		// Sanitize the input
//		\Joomla\Utilities\ArrayHelper::toInteger($pks);
//		\Joomla\Utilities\ArrayHelper::toInteger($order);
//		\Joomla\Utilities\ArrayHelper::toInteger($lft);
//		\Joomla\Utilities\ArrayHelper::toInteger($rgt);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveOrder($pks, $order, $lft, $rgt);

		if ($return) {
			echo "1";
		} else {
			echo "0";
		}

		exit;
	}
}
