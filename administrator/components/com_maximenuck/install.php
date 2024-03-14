<?php

defined('_JEXEC') or die('Restricted access');
/*
	preflight which is executed before install and update
	install
	update
	uninstall
	postflight which is executed after install and update
	*/

class com_maximenuckInstallerScript {

	function install($parent) {
		
	}
	
	function update($parent) {
		
	}

	function uninstall($parent) {
		// disable all plugins and modules
		$db = \Joomla\CMS\Factory::getDbo();
		$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%maximenuck%'");
		$db->execute();

		$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%maximenuck%' AND `folder` NOT LIKE '%maximenuck%'");
		$db->execute();
		return true;
	}

	function preflight($type, $parent) {
		// disable the install on Joomla 3
		if (version_compare(JVERSION, '4') === -1) {
			throw new RuntimeException('This version of Maximenu CK can not be installed on Joomla 3. Please use the version 10.0.12.');
		}

		// check for V8 modules
		$db = \Joomla\CMS\Factory::getDbo();
		$db->setQuery("SELECT id FROM #__modules WHERE `module` = 'mod_maximenuck' AND `params` LIKE '%\"isv9\":\"0\"%'");
		$oldItems = $db->loadObjectList();

		if (! empty($oldItems)) {
			foreach ($oldItems as $oldItem) {
				throw new RuntimeException('<div>Installation error : You have an old module in V8 mode, ID <b>' . $oldItem->id . '. You must convert your module, please check the infos on https://forum.joomlack.fr</b>.<br/> This check is made to help you to update your website safely.</div>');
			}
			return;
		}
		
		// check if a pro version already installed
		$xmlPath = JPATH_ROOT . '/administrator/components/com_maximenuck/maximenuck.xml';

		// if no file already exists
		if (! file_exists($xmlPath)) return true;

		$xmlData = $this->getXmlData($xmlPath);
		$isProInstalled = ((int)$xmlData->ckpro);

		if ($isProInstalled) {
			throw new RuntimeException('Maximenu CK Light cannot be installed over Maximenu CK Pro. Please install Maximenu CK Pro. To downgrade, please first uninstall Maximenu CK Pro. <a href="https://www.joomlack.fr/en/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9" target="_blank">Read more</a>');
			// return false;
		}

		// check if a V1 version is installed with the params (needs the pro)
		$xmlPath = JPATH_ROOT . '/modules/mod_maximenuck/mod_maximenuck.xml';

		// if no file already exists
		if (! file_exists($xmlPath)) return true;

		$xmlData = $this->getXmlData($xmlPath);
		$installedVersion = ((int)$xmlData->version );
		// if the installed version is the V1
		if(version_compare($installedVersion, '9.0.0', '<')) {
			// if the params is also installed
			if (file_exists(JPATH_ROOT . '/plugins/system/maximenuckparams/maximenuckparams.xml')) {
				throw new RuntimeException('Maximenu CK Light cannot be installed over Maximenu CK V8 + Params. Please install Maximenu CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Maximenu CK Params. <a href="https://www.joomlack.fr/en/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9" target="_blank">Read more</a>');
				// return false;
			}
		}

		return true;
	}

	public function getXmlData($file) {
		if ( ! is_file($file))
		{
			return '';
		}

		$xml = simplexml_load_file($file);

		if ( ! $xml || ! isset($xml['version']))
		{
			return '';
		}

		return $xml;
	}

	// run on install and update
	function postflight($type, $parent) {
		// install modules and plugins
		jimport('joomla.installer.installer');
		$db = \Joomla\CMS\Factory::getDbo();
		$status = array();
		$src_ext = dirname(__FILE__).'/administrator/extensions';
		$installer = new \Joomla\CMS\Installer\Installer;

		// module
		$result = $installer->install($src_ext.'/mod_maximenuck');
		$status[] = array('name'=>'Maximenu CK - Module','type'=>'module', 'result'=>$result);

		// disable the old update site
		$db->setQuery("UPDATE #__update_sites SET enabled = '0' WHERE `location` = 'http://update.joomlack.fr/mod_maximenuck_update.xml'");
		$result3 = $db->execute();
		// disable the old update site
		$db->setQuery("UPDATE #__update_sites SET enabled = '0' WHERE `location` = 'http://update.joomlack.fr/com_maximenuck_update.xml'");
		$result4 = $db->execute();

		foreach ($status as $statu) {
			if ($statu['result'] == true) {
				$alert = 'success';
				$icon = 'icon-ok';
				$text = 'Successful';
			} else {
				$alert = 'warning';
				$icon = 'icon-cancel';
				$text = 'Failed';
			}
			echo '<div class="alert alert-' . $alert . '"><i class="icon ' . $icon . '"></i>Installation and activation of the <b>' . $statu['type'] . ' ' . $statu['name'] . '</b> : ' . $text . '</div>';
		}

		// check for table creation
		require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/helper.php';
		Maximenuck\Helper::checkDbIntegrity();

		return true;
	}
}
