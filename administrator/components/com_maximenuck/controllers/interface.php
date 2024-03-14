<?php
// No direct access
defined('_JEXEC') or die;
require_once MAXIMENUCK_PATH . '/controller.php';

use Maximenuck\CKFof;

class MaximenuckControllerInterface extends MaximenuckController {

	protected $imagespath;

	/**
	 * Load the needed interface
	 * 
	 * @return void
	 */
	public function load() {
		// security check
		CKFof::checkAjaxToken(false);

		$layout = $this->input->get('layout', '', 'cmd');
		if (! $layout) return;

		$this->imagespath = MAXIMENUCK_MEDIA_URI . '/images/menustyles/';
		$plugin = str_replace('edit_', '', $layout);

		// loads the language files from the frontend
		$lang	= \Joomla\CMS\Factory::getLanguage();
		$lang->load('plg_maximenuck_' . $plugin, JPATH_SITE . '/plugins/maximenuck/' . $plugin, $lang->getTag(), false);
		$lang->load('plg_maximenuck_' . $plugin, JPATH_SITE, $lang->getTag(), false);

		if (file_exists(MAXIMENUCK_PATH . '/interfaces/' . $layout . '.php')) {
			require_once(MAXIMENUCK_PATH . '/interfaces/' . $layout . '.php');
		} else {
			require_once(JPATH_SITE . '/plugins/maximenuck/' . $plugin . '/layouts/' . $layout . '.php');
		}
		exit;
	}

	/**
	 * Load the item data and then the interface
	 * 
	 * @return void
	 */
	public function editmenubuilder() {
		// security check
		CKFof::checkAjaxToken(false);

		$customid = $this->input->get('customid', '', 'string');
		$model = CKFof::getModel('Menubuilder');
		$item = $model->getMenubuilderItem($customid);

		?>
		<input class="itemdata" name="id" type="hidden" value="<?php echo $item->id ?>" />
		<input class="itemdata" name="fields" type="hidden" value="<?php echo $item->styles ?>" />
		<input class="itemdata" name="params" type="hidden" value="<?php echo $item->params ?>"/>
		<input class="itemdata" name="customid" type="hidden" value="<?php echo $item->customid ?>"/>
		<script>
			function ckLoadEditionItem() {} // needed to clean the function on load
			function ckBeforeSaveItem() {} // needed to clean the function on save
		</script>
		<?php
		$this->load();
}
}