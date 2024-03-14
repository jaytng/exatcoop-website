<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\Helper;


class MaximenuckViewStyles extends CKView {

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

		// $this->input->set('tmpl', 'component');
//		 $this->input->set('layout', 'default');
		if ($this->input->get('layout', 'default') === 'modal') {
			$this->input->set('layout', 'default');
			$this->input->set('from', 'modal');
		}

		parent::display();
	}

	private function getToolbar() {
		\Joomla\CMS\Toolbar\ToolbarHelper::title('Maximenu CK - ' . \Joomla\CMS\Language\Text::_('CK_STYLES'));
		// Get the toolbar object instance
		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
		if (CKFof::userCan('create')) {
			// \Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			if ($this->input->get('layout') == 'modal') {
				\Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			} else {
				// Render the popup button
				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . \Joomla\CMS\Uri\Uri::root(true) . '/administrator/index.php?option=com_maximenuck&view=style&layout=edit&tmpl=component&id=0\'})">
						<span class="icon-new icon-white"></span>
						' . \Joomla\CMS\Language\Text::_('CK_NEW') . '
						</button>';
				$bar->appendButton('Custom', $html);
			}
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
		}
		if (CKFof::userCan('edit')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
		}
		if (CKFof::userCan('delete')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::trash('style.delete', 'CK_REMOVE');
		}

		return $bar;
	}
}
