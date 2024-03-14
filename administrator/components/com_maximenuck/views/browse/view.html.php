<?php
// No direct access
defined('_JEXEC') or die;

use \Maximenuck\CKView;
use \Maximenuck\CKFof;

class MaximenuckViewBrowse extends CKView {

	function display($tpl = 'default') {

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// load the items
		require_once MAXIMENUCK_PATH . '/helpers/ckbrowse.php';
		$this->items = CKBrowse::getItemsList();

		parent::display($tpl);
	}
}
