<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;

class MaximenuckViewItems extends CKView {

	protected $menus;

	/**
	 * Display the view
	 */
	public function display($tpl = 'default') {
		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->items = $this->get('Items');

		$type = $this->input->get('type', 'menu', 'cmd');
		$tpl = file_exists('/tmpl/' . $type . '.php') ? $type : 'default';
		$tpl = $type; // TEST
		parent::display($tpl);
	}
}
