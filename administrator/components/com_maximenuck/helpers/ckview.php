<?php
Namespace Maximenuck;

defined('CK_LOADED') or die;

class CKView {

	protected $name;

	protected $model;

	protected $input;

	protected $item;

	protected $items;

	protected $state;

	protected $pagination;

	protected $toolbar;

	public function __construct() {
		// check if the user has the rights to access this page
		if (!CKFof::userCan('edit')) {
			CKFof::_die();
		}
		$this->input = CKFof::getInput();
	}

	public function display($tpl = 'default') {
		if ($tpl === null) $tpl = 'default';
		if ($this->model) {
			$this->state = $this->model->getState();
			$this->pagination = $this->model->getPagination();
		}

		require_once MAXIMENUCK_PATH . '/views/' . strtolower($this->name) . '/tmpl/' . $tpl . '.php';
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function get($func, $params = array()) {
		$model = $this->getModel();
		if ($model === false) return false;
		$funcName = 'get' . ucfirst($func);
		return $model->$funcName($params);
	}

	public function getModel() {
		if (empty($this->model)) {
			$file = MAXIMENUCK_PATH . '/models/' . strtolower($this->name) . '.php';
			if (! file_exists($file)) return false;
			require_once($file);
			$className = '\Maximenuck\CKModel' . ucfirst($this->name);
			$this->model = new $className;
		}
		return $this->model;
	}
}