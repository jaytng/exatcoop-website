<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

class MaximenuckViewModules extends CKView {

	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		Helper::checkDbIntegrity();
		
		$this->items = $this->get('Items');
		$this->toolbar = $this->getToolbar();

		parent::display($tpl);
	}

	private function getToolbar() {
		\Joomla\CMS\Toolbar\ToolbarHelper::title('Maximenu CK - ' . CKText::_('CK_MODULES_LIST'));

		// \Joomla\CMS\Toolbar\ToolbarHelper::custom('module.edit', 'edit', 'edit', 'CK_EDIT');

		if (CKFof::userCan('core.admin')) {
			 \Joomla\CMS\Toolbar\ToolbarHelper::preferences('com_maximenuck');
		}
	}
}
