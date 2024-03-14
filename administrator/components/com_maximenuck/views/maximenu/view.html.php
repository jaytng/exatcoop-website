<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

class MaximenuckViewMaximenu extends CKView {

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
		// require_once JPATH_SITE . '/administrator/components/com_maximenuck/helpers/ckstyles.php';
		require_once(JPATH_SITE . '/administrator/components/com_maximenuck/helpers/ckinterface.php');

		$this->interface = new Maximenuck\CKInterface();
		$this->item = $this->get('Item');
		$this->joomlamenus = $this->get('JoomlaMenus');

		$this->input->set('tmpl', 'component');
		$this->input->set('layout', 'modal');

		// set the beginning interface
		if ((int)$this->item->id === 0 && ! $this->input->get('startwith')) {
			$tpl = 'start';
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		Helper::loadCkbox();

		\Joomla\CMS\Factory::getApplication()->input->set('hidemainmenu', true);
		$user		= \Joomla\CMS\Factory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$state = $this->get('State');
		$canDo = Helper::getActions();

		// For new records, check the create permission.
		if ($isNew && $user->authorise('core.create', 'com_maximenuck'))
		{
			\Joomla\CMS\Toolbar\ToolbarHelper::apply('maximenu.apply');
			\Joomla\CMS\Toolbar\ToolbarHelper::save('maximenu.save');
			// \Joomla\CMS\Toolbar\ToolbarHelper::save2new('page.save2new');
			\Joomla\CMS\Toolbar\ToolbarHelper::cancel('maximenu.cancel');
		} else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					\Joomla\CMS\Toolbar\ToolbarHelper::apply('maximenu.apply');
					\Joomla\CMS\Toolbar\ToolbarHelper::save('maximenu.save');
//					\Joomla\CMS\Toolbar\ToolbarHelper::custom('maximenu.restore', 'archive', 'archive', 'CK_RESTORE', false);
					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						// \Joomla\CMS\Toolbar\ToolbarHelper::save2new('page.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				// \Joomla\CMS\Toolbar\ToolbarHelper::save2copy('page.save2copy');
			}

			\Joomla\CMS\Toolbar\ToolbarHelper::cancel('maximenu.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
