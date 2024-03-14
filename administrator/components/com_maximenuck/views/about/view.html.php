<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKInterface;

class MaximenuckViewAbout extends CKView {

	function display($tpl = 'default') {

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		\Joomla\CMS\Toolbar\ToolbarHelper::title('Maximenu CK');

		parent::display($tpl);
	}
}
