<?php
/**
 * @copyright	Copyright (C) 2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

use Maximenuck\CKFof;
use Maximenuck\CKFolder;
use Maximenuck\CKFile;
use Maximenuck\CKText;

require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckfof.php';
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckfolder.php';
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckfile.php';
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/cktext.php';

class JFormFieldCkmigrate extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'ckmigrate';

	private $options;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getLabel()
	{
		return '';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 */
	protected function getInput()
	{
		$input = \Joomla\CMS\Factory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$doMigration = $input->get('domigration', 0, 'int');
		if (! $id) return '';

		$options = $this->getModuleOptions($id);

		$params = json_decode($options->params);

		// si ancienne version détectée
		if ($params && ! isset($params->source)) {
			$this->makeBackup($id, $options->params);
			if ($doMigration) {
				$this->doMigration($id, $options->params);
			} else {
				CKfof::enqueueMessage(CKText::_('MAXIMENUCK_MIGRATION_NEEDED'), 'warning');
				CKfof::enqueueMessage('<a href="' . CKFof::getCurrentUri() . '&domigration=1">' . CKText::_('MAXIMENUCK_MIGRATION_ACTION') . '</a>', 'warning');
			}
		}

		if ($this->isPluginEnabled()) {
			CKFof::dbExecute("UPDATE #__extensions SET enabled = 0 WHERE element = 'maximenuckparams'");
			CKfof::enqueueMessage(CKText::_('MAXIMENUCK_PARAMS_UNPUBLISHED_INFO'), 'warning');
			CKfof::enqueueMessage('<a href="https://www.joomlack.fr/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9" target="_blank">' . CKText::_('MAXIMENUCK_PARAMS_MIGRATION_LINK') . '</a>', 'warning');
			CKfof::redirect();
		}

		$this->alertObsoletePlugin($params, 'hikashop');
		$this->alertObsoletePlugin($params, 'k2');
		$this->alertObsoletePlugin($params, 'joomshopping');
		$this->alertObsoletePlugin($params, 'virtuemart');
		$this->alertObsoletePlugin($params, 'adsmanager');
	}

	protected function alertObsoletePlugin($params, $plugin) {
		if (isset($params->source) && $params->source == $plugin && $this->isPluginEnabled('maximenuck' . $plugin)) {
			CKfof::enqueueMessage(CKText::_('MAXIMENUCK_WARNING_PLUGIN_OBSOLETE') . ' : ' . '<b>maximenuck' . $plugin . '</b>', 'warning');
			CKfof::enqueueMessage('<a href="index.php?option=com_plugins&view=plugins&filter_element=maximenuck' . $plugin . '" target="_blank">' . CKText::_('MAXIMENUCK_DISABLE_PLUGIN') . '</a>', 'warning');
		}
	}

	protected function isPluginEnabled($plugin = 'maximenuckparams') {
		if (file_exists(JPATH_ROOT . '/plugins/system/' . $plugin)) {
			$isEnabled = CKFof::dbLoadResult("SELECT enabled FROM #__extensions WHERE element = '" . $plugin . "'");
			return (bool)$isEnabled;
		}
		return false;
	}

	protected function getModuleOptions($id) {
		if (empty($this->options)) {
			$this->options = CKFof::dbLoadObject("SELECT * FROM #__modules WHERE id = " . (int)$id);
		}
		return $this->options;
	}

	protected function doMigration($id, $params) {
		$find = array('thirdparty');
		$replace = array('source');
		$newparams = str_replace($find, $replace, $params);

		$paramsObj = new \Joomla\Registry\Registry($newparams);
		if ($paramsObj->get('source', 'menu') == 'none') $paramsObj->set('source', 'menu');
		$paramsObj->set('isv9', '0');
		$newparams = json_encode($paramsObj);

		$data = CKFof::dbLoad('#__modules', $id);
		$data->id = $id;
		$data->params = $newparams;

		$return = CKFof::dbStore('#__modules', $data);

		if ($return) {
			CKfof::enqueueMessage(CKText::_('MAXIMENUCK_MIGRATION_SUCCESS'), 'success');
		} else {
			CKfof::enqueueMessage(CKText::_('MAXIMENUCK_MIGRATION_ERROR'), 'error');
		}
		CKfof::redirect();
	}

	protected function makeBackup($id, $params) {
		$path = JPATH_ROOT . '/administrator/components/com_maximenuck/backup/';

		// create the folder
		if (! CKFolder::exists($path)) {
			CKFolder::create($path);
		}

		$exportfiledest = $path . '/backup_' . $id . '_' . date("d-m-Y-G-i-s") . '.ssck';
		CKFile::write($exportfiledest, $params);
	}
}
