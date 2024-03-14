<?php
/**
 * @copyright	Copyright (C) 2020 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

use Maximenuck\Helper;
use Maximenuck\CKFramework;

\Joomla\CMS\Language\Text::script('MAXIMENUCK_SAVE_CLOSE');
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/helper.php';
require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckframework.php';

CKFramework::load();
Helper::loadCkbox();

class JFormFieldCkmenu extends \Joomla\CMS\Form\FormField {

	protected $type = 'ckmenu';

	protected function getInput() {
		// loads the language files from the frontend
		$lang	= \Joomla\CMS\Factory::getLanguage();
		$lang->load('com_maximenuck', JPATH_SITE . '/components/com_maximenuck', $lang->getTag(), false);
		$lang->load('com_maximenuck', JPATH_SITE, $lang->getTag(), false);

		$doc = \Joomla\CMS\Factory::getDocument();
		// Initialize some field attributes.
		$js = 'function ckSelectMenu(id, name, close) {
			if (!close && close != false) close = true;
			jQuery("#' . $this->id . '").val(id);
			jQuery("#' . $this->id . 'name").val(name);
			if (close) CKBox.close();
		}
		function ckEditMenu() {
			var menutype = jQuery(\'#' . $this->id . '\').val();
			if (! menutype) {
				alert("' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_PLEASE_SELECT_MENU') .'");
				return;
			}
			CKBox.open({url: \'index.php?option=com_maximenuck&view=joomlamenu&tmpl=component&layout=modal&menutype=\'+menutype+\'\'})
		}';
		$doc->addScriptDeclaration($js);
		
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$defautlwidth = $suffix ? '128px' : '150px';
		$styles = ' style="width:'.$defautlwidth.';'.$this->element['styles'].'"';

		if ($this->value) {
			$menuName = Helper::getJoomlaMenuNameById($this->value);
		} else {
			$allMenus = Helper::getJoomlaMenus();
			$this->value = $allMenus[0]->menutype;
			$menuName = $allMenus[0]->title;
		}

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . MAXIMENUCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';		

		$html .= '<div class="ckbutton-group">';
		$html .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$html .= '<input type="text" disabled name="' . $this->name . 'name" id="' . $this->id . 'name"' . ' value="'
			. htmlspecialchars($menuName) . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$footerHtml = '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\'test\')">' . \Joomla\CMS\Language\Text::_('CK_CREATE_NEW') . '</a>';
		$html .= '<div class="ckbutton" onclick="CKBox.open({url: \'index.php?option=com_maximenuck&view=joomlamenus&tmpl=component&layout=modal\', style: {padding: \'0px\'}})"><i class="fas fa-mouse-pointer "></i> ' . \Joomla\CMS\Language\Text::_('CK_SELECT') . '</div>';
		$html .= '<div class="ckbutton" onclick="ckEditMenu()"><i class="fas fa-edit"></i> ' . \Joomla\CMS\Language\Text::_('CK_EDIT') . '</div>';
		// $html .= '<div class="ckbutton cktip" onclick="jQuery(\'#' . $this->id . '\').val(\'\');jQuery(\'#' . $this->id . 'name\').val(\'\');" title="' . \Joomla\CMS\Language\Text::_('CK_REMOVE') . '"><i class="fas fa-times"></i></div>';
		$html .= '</div>';

		return $html;
	}
}
