<?php
// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Maximenuck\CKModel;
use Maximenuck\CKFof;


class MaximenuckModelStyle extends CKModel {

	protected $table = '#__maximenuck_styles';

	var $item = null;

	function __construct() {
		parent::__construct();
	}

	public function save($row) {
		$id = CKFof::dbStore($this->table, $row);

		return $id;
	}

	public function getItem() {
		if (empty($this->item)) {
			$id = $this->input->get('id', 0, 'int');
			$this->item = CKFof::dbLoad($this->table, $id);
		}
		if (! isset($this->item->customcss)) $this->item->customcss = '';

		return $this->item;
	}

}