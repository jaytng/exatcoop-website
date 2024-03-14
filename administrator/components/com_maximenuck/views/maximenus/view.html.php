<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

class MaximenuckViewMaximenus extends CKView {

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
		// $this->input->set('layout', 'modal');

		parent::display();
	}

	private function getToolbar() {
		\Joomla\CMS\Toolbar\ToolbarHelper::title('Maximenu CK - ' . CKText::_('CK_MENUS'));
		// Get the toolbar object instance
		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
		if (CKFof::userCan('create')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::addNew('maximenu.add', 'CK_NEW');
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('maximenu.copy', 'copy', 'copy', 'CK_COPY');
			// Render the popup button
//				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . \Joomla\CMS\Uri\Uri::root(true) . '/administrator/index.php?option=com_maximenuck&view=maximenu&layout=modal&tmpl=component&id=0\'})">
//						<span class="icon-new icon-white"></span>
//						' . \Joomla\CMS\Language\Text::_('JTOOLBAR_NEW') . '
//						</button>';
//				$bar->appendButton('Custom', $html);
			
		}
		if (CKFof::userCan('edit')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('maximenu.edit', 'edit', 'edit', 'CK_EDIT');
			\Joomla\CMS\Toolbar\ToolbarHelper::trash('maximenu.delete', 'CK_REMOVE');
		}

		return $bar;
	}
}
