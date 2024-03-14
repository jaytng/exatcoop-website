<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

class MaximenuckViewMenubuilder extends CKView {

	protected $interface;

	protected $joomlamenus;

	function display($tpl = null) {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// dislay the page title
		\Joomla\CMS\Toolbar\ToolbarHelper::title('Maximenu CK - ' . CKText::_('CK_EDITION'));

		// load the styles helper and the interface
		require_once(JPATH_SITE . '/administrator/components/com_maximenuck/helpers/ckinterface.php');

		$this->interface = new Maximenuck\CKInterface();
		$this->item = $this->get('Item');
		$this->joomlamenus = $this->get('JoomlaMenus');

		$this->input->set('tmpl', 'component');

		// set the beginning interface
		if ((int)$this->item->id === 0 && ! $this->input->get('startwith')) {
			$tpl = 'start';
		}

		parent::display($tpl);
	}
}
