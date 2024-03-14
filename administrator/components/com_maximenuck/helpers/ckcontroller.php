<?php
Namespace Maximenuck;

defined('CK_LOADED') or die;

// class CKController extends \Joomla\CMS\MVC\Controller\BaseController {
class CKController {

	protected $input;

	protected $model;

	protected $name;

	protected $prefix;

	protected $view;

	protected $redirect;

	protected static $instance;

	protected static $views;

	function __construct() {
		$this->input = CKFof::getInput();
		$isModal = $this->input->get('layout', '', 'string') == 'modal';
		$isModal = $this->input->get('from', $isModal, 'string') == 'modal';
		$appendUrl = $isModal ? '&layout=modal&tmpl=component' : '';
		$this->redirect = $this->redirect . $appendUrl;
	}

	static function getInstance($prefix) {
		if (is_object(self::$instance))
		{
			return self::$instance;
		}
		$basePath = MAXIMENUCK_BASE_PATH;
		// Check for a controller.task command.
		$input = CKFof::getInput();
		$cmd = $input->get('task', '', 'cmd');
		if (strpos($cmd, '.') !== false)
		{
			// Explode the controller.task command.
			list ($name, $task) = explode('.', $cmd);

			// Define the controller filename and path.
			$file = self::createFileName('controller', array('name' => $name));
			$path = $basePath . '/controllers/' . $file;
			$backuppath = $basePath . '/controller/' . $file;

			// Reset the task without the controller context.
			$input->set('task', $task);
		}
		else
		{
			// Base controller.
			$name = null;

			// Define the controller filename and path.
			$file       = self::createFileName('controller', array('name' => 'controller'));
			$path       = $basePath . '/' . $file;
		}

		// Get the controller class name.
		$class = ucfirst((string) $prefix) . 'Controller' . ucfirst((string) $name);

		// Include the class if not present.
		if (!class_exists($class))
		{
			// If the controller file path exists, include it.
			if (file_exists($path))
			{
				require_once $path;
			}
			else
			{
				throw new \InvalidArgumentException(\Joomla\CMS\Language\Text::sprintf('ERROR_INVALID_CONTROLLER', $type, $format));
			}
		}

		// Instantiate the class.
		if (!class_exists($class))
		{
			throw new \InvalidArgumentException(\Joomla\CMS\Language\Text::sprintf('ERROR_INVALID_CONTROLLER_CLASS', $class));
		}

		// Instantiate the class, store it to the static container, and return it
		return self::$instance = new $class();
	}

	protected static function createFileName($type, $parts = array())
	{
		$filename = '';

		switch ($type)
		{
			case 'controller':

				$filename = strtolower($parts['name'] . '.php');
				break;

			case 'view':

				$filename = strtolower($parts['name'] . '/view.html.php');
				break;
		}

		return $filename;
	}

	// public function getModel($base = '\Maximenuck\CKModel') {
		// if (empty($this->model)) {
			// $name = $this->getName();
			// require_once(MAXIMENUCK_BASE_PATH . '/helpers/ckmodel.php');
			// require_once(MAXIMENUCK_BASE_PATH . '/models/' . strtolower($name) . '.php');
			// $className = ucfirst($base) . ucfirst($name);
			// $this->model = new $className;
		// }
		// return $this->model;
	// }

	public function getView($name = '', $type = 'html', $prefix = '')
	{
		// @note We use self so we only access stuff in this class rather than in all classes.
		if (!isset(self::$views))
		{
			self::$views = array();
		}

		if (empty($name))
		{
			$name = $this->getName();
		}

		if (empty($prefix))
		{
			$prefix = $this->getPrefix() . 'View';
		}

		if (empty(self::$views[$name][$type][$prefix]))
		{
			if ($view = $this->createView($name, $prefix))
			{
				self::$views[$name][$type][$prefix] = & $view;
			}
			else
			{
				throw new \Exception(\Joomla\CMS\Language\Text::sprintf('ERROR_VIEW_NOT_FOUND', $name, $type, $prefix), 404);
			}
		}

		return self::$views[$name][$type][$prefix];
	}

	protected function createView($name, $prefix = '')
	{
		// Clean the view name
		$viewName = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);

		// Build the view class name
		$viewClass = $classPrefix . ucfirst($viewName);

		if (!class_exists($viewClass))
		{
			$path = MAXIMENUCK_BASE_PATH . '/views/' . $this->createFileName('view', array('name' => $viewName));

			if (!$path)
			{
				return null;
			}

			require_once $path;

			if (!class_exists($viewClass))
			{
				throw new \Exception(\Joomla\CMS\Language\Text::_('ERROR_VIEW_CLASS_NOT_FOUND : ' . $viewClass . ' - ' . $path), 500);
			}
		}

		return new $viewClass();
	}

	public function display() {
		$viewName = $this->input->get('view', $this->getName());
		$viewLayout = $this->input->get('layout', 'default', 'string');

		$view = $this->getView($viewName, 'html', '');
		$view->setName($viewName);

		// Get/Create the model
		if ($model = $this->getModel($viewName))
		{
			// Push the model into the view (as default)
			$view->setModel($model);
		}

		$view->display();

		return $this;
	}

	public function getModel($name = '', $prefix = '', $config = array())
	{
		if (empty($name))
		{
			$name = ucfirst($this->getName());
		}

		if (empty($prefix))
		{
			$prefix = ucfirst($this->getPrefix());
		}

		$model = $this->createModel($name, $prefix, $config);

		return $model;
	}

	protected function createModel($name, $prefix = '', $config = array())
	{
		// Clean the model name
		$modelName = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);

		return CKModel::getInstance($modelName, $classPrefix, $config);
	}


	public function execute($task) {
		if (! $task) $task = 'display';
		if (is_callable(array($this, $task))) {
			return $this->$task();
		}
		else
		{
			throw new \Exception(\Joomla\CMS\Language\Text::sprintf('ERROR_TASK_NOT_FOUND', $task), 404);
		}

		return;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName()
	{
		if (empty($this->name))
		{
			$r = null;

			if (!preg_match('/Controller(.*)/i', get_class($this), $r))
			{
				throw new \Exception(\CKText::_('Error : Can not get controller name'), 500);
			}

			$this->name = strtolower($r[1]);
		}

		return $this->name;
	}

	public function getPrefix()
	{
		if (empty($this->prefix))
		{
			$r = null;

			if (!preg_match('/(.*)Controller/i', get_class($this), $r))
			{
				throw new \Exception(\CKText::_('Error : Can not get controller name'), 500);
			}

			$this->prefix = strtolower($r[1]);
		}

		return $this->prefix;
	}

	public function add() {
		return $this->edit(0);
	}

	public function edit($id = null, $appendUrl = '') {
		$editIds = $this->input->get('cid', $id, 'array');
		if (is_array($editIds)) {
			$editId = (int) $editIds[0];
		} else {
			$editId = (int) $this->input->get('id', $id, 'int');
		}

		$model = $this->getModel($this->getName());
		$model->checkout($editId);

		// Redirect to the edit screen.
		CKFof::redirect(MAXIMENUCK_URL . '&view=' . $this->getName() . '&layout=edit&id=' . $editId . $appendUrl);
	}

	public function copy() {
		$editIds = $this->input->get('cid', null, 'array');
		if (count($editIds)) {
			$id = (int) $editIds[0];
		} else {
			$id = (int) $this->input->get('id', null, 'int');
		}

		$model = $this->getModel($this->getName());
		if ($model->copy($id)) {
			CKFof::enqueueMessage('Item copied with success');
		} else {
			CKFof::enqueueMessage('Error : Item not copied', 'error');
		}

		if (! $this->redirect) $this->redirect = MAXIMENUCK_URL;

		// Redirect to the edit screen.
		CKFof::redirect($this->redirect);
	}

	public function delete() {
		$ids = $this->input->get('cid', null, 'array');
		if (count($ids)) {
			$ids = (array) $ids;
		} else {
			$ids = (array) $this->input->get('id', null, 'array');
		}
		$model = $this->getModel($this->getName());
		foreach($ids as $id) {
			$return = $model->delete($id);
			if ($return) {
			CKFof::enqueueMessage('Item deleted with success');
		} else {
			CKFof::enqueueMessage('Error : Item not deleted', 'error');
		}
		}

		if (! $this->redirect) $this->redirect = MAXIMENUCK_URL . $appendUrl;

		// Redirect to the edit screen.
		CKFof::redirect($this->redirect);
	}

	public function trash() {
		$ids = $this->input->get('cid', null, 'array');
		if (count($ids)) {
			$ids = (array) $ids;
		} else {
			$ids = (array) $this->input->get('id', null, 'array');
}
		$model = $this->getModel($this->getName());
		foreach($ids as $id) {
			$return = $model->trash($id);
			if ($return) {
				CKFof::enqueueMessage('CK_ITEM_TRASH_SUCCESS');
			} else {
				CKFof::enqueueMessage('CK_ITEM_TRASH_FAILED', 'error');
			}
		}

		// Redirect to the edit screen.
		if (! $this->redirect) $this->redirect = MAXIMENUCK_ADMIN_GENERAL_URL;

		// Redirect to the edit screen.
		CKFof::redirect($this->redirect);
	}

	public function publish() {
		$ids = $this->input->get('cid', null, 'array');
		if (count($ids)) {
			$ids = (array) $ids;
		} else {
			$ids = (array) $this->input->get('id', null, 'array');
		}
		if (! empty($ids)) {
			$model = $this->getModel($this->getName());
			foreach($ids as $id) {
				if ($model->publish($id)) {
					CKFof::enqueueMessage('Item published with success');
				} else {
					CKFof::enqueueMessage('Error : Item not published', 'error');
				}
			}
		}

		// Redirect to the edit screen.
		if (! $this->redirect) $this->redirect = MAXIMENUCK_ADMIN_GENERAL_URL;

		// Redirect to the edit screen.
		CKFof::redirect($this->redirect);
	}

	public function unpublish() {
		$ids = $this->input->get('cid', null, 'array');var_dump($ids);
		if (count($ids)) {
			$ids = (array) $ids;
		} else {
			$ids = (array) $this->input->get('id', null, 'array');
		}
//		var_dump($ids);die;
		if (! empty($ids)) {
			$model = $this->getModel($this->getName());
			foreach($ids as $id) {
				if ($model->unpublish($id)) {
					CKFof::enqueueMessage('Item unpublished with success');
				} else {
					CKFof::enqueueMessage('Error : Item not unpublished', 'error');
				}
			}
		}

		// Redirect to the edit screen.
		if (! $this->redirect) $this->redirect = MAXIMENUCK_ADMIN_GENERAL_URL;

		// Redirect to the edit screen.
		CKFof::redirect($this->redirect);
	}
}
