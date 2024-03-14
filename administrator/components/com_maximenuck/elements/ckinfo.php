<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/helper.php';
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckframework.php';

use Maximenuck\CKFramework;
use Maximenuck\Helper;

class JFormFieldCkinfo extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'ckinfo';

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
		$doc = \Joomla\CMS\Factory::getDocument();
		$styles = '.ckinfo {position:relative;background:#efefef;border: none;border-radius: px;color: #333;font-weight: normal;line-height: 24px;padding: 5px 5px 5px 35px;margin: 3px 0;text-align: left;text-decoration: none;}
.ckinfo > .fas {
	font-size: 15px;
	padding: 3px 5px;
	background: rgba(0, 0, 0, 0.1);
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	line-height: 25px;
	width: 20px;
	text-align: center;
}
.ckinfo img {margin: 0 10px 0 0;}
.control-label:empty, .controls:empty {display: none;}
.control-label:empty + .controls {margin: 0;}
';
		$doc->addStyleDeclaration($styles);

		// get the extension version
		$current_version = $this->getCurrentVersion(JPATH_SITE .'/administrator/components/com_maximenuck/maximenuck.xml');
		$html = '';
		$html .= CKFramework::loadFaIconsInline();

		if (Helper::isV9() == '0') {
			$html .= '<div class="ckinfo"><i class="fas fa-exclamation-triangle" style="color:red;"></i>' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_V8_ALERT') . ' <a target="_blank" href="https://www.joomlack.fr/en/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9#v8legacy">' . \Joomla\CMS\Language\Text::_('CK_READMORE') . '</a></div>';
		}
		$html .= '<div class="ckinfo"><i class="fas fa-thumbs-up"></i><a href="https://extensions.joomla.org/extension/maxi-menu-ck/" target="_blank">' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_VOTE_JED') . '</a></div>';
		$html .= '<div class="ckinfo"><i class="fas fa-info"></i><b>MAXIMENU CK</b> - ' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_CURRENT_VERSION') . '</b> : <span class="label">' . $current_version . '</span></div>';
		$html .= '<div class="ckinfo"><i class="fas fa-file-alt"></i><a href="https://www.joomlack.fr/en/documentation/maximenu-ck" target="_blank">' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_DOCUMENTATION') . '</a></div>';

		return $html;
	}

	/*
	 * Get a variable from the manifest file
	 * 
	 * @return the current version
	 */
	public static function getCurrentVersion($file_url) {
		// get the version installed
		$installed_version = 'UNKOWN';
		if ($xml_installed = simplexml_load_file($file_url)) {
			$installed_version = (string)$xml_installed->version;
		}

		return $installed_version;
	}
}
