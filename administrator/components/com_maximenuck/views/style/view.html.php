<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKInterface;

class MaximenuckViewStyle extends CKView
{
	protected $params;

	protected $interface;

	protected $imagespath;

	protected $colorpicker_class;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// load the module params
		$moduleId = $this->input->get('frommoduleid', 0, 'int');
		$module = CKFof::dbLoad('#__modules', $moduleId);
		$this->params = new \Joomla\Registry\Registry($module->params);

		// get the interface
		require_once(JPATH_SITE . '/administrator/components/com_maximenuck/helpers/ckinterface.php');
		$this->interface = new \Maximenuck\CKInterface();

		$this->item = $this->get('Item');

		// get the custom css from the V8 module if needed
		if ($moduleId && !$this->item->customcss) {
			$this->item->customcss = $this->params->get('customcss');
		}

		// force the layout
		$this->input->set('tmpl', 'component');

		$tpl = $this->input->get('layout', $tpl);
		$this->input->set('layout', 'modal');

		parent::display($tpl);
		// for rendering the ajax layout
		if ($tpl == 'edit_render_menu_module') exit();
	}

	/**
	 * B/C function to load the layout file
	 * @param type $file
	 */
	public function loadTemplate($file) {
		require dirname(__FILE__) . '/tmpl/edit_' . $file . '.php';
	}
}
