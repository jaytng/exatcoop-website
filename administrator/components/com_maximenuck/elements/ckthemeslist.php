<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

/**
* Deprecated since V9
*/
class JFormFieldCkthemeslist extends \Joomla\CMS\Form\Field\ListField
{

	public $type = 'ckthemeslist';

	protected function getLabel() {

		return parent::getLabel();
	}

	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		// Initialize some field attributes.
		$filter			= (string) $this->element['filter'];
		$exclude		= (string) $this->element['exclude'];
		$hideNone		= (string) $this->element['hide_none'];
		$hideDefault	= (string) $this->element['hide_default'];

		// Get the path in which to search for file options.
		$path = (string) $this->element['directory'];
		if (!is_dir($path)) {
			$path = JPATH_ROOT.'/'.$path;
		}

		// Prepend some default options based on field attributes.
		if (!$hideNone) {
			$options[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', '-1', \Joomla\CMS\Language\Text::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}
		if (!$hideDefault) {
			$options[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', '', \Joomla\CMS\Language\Text::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		// Get a list of folders in the search path with the given filter.
		$folders = \Joomla\CMS\Filesystem\Folder::folders($path, $filter);

		// Build the options list from the list of folders.
		if (is_array($folders)) {
			foreach($folders as $folder) {

				// Check to see if the file is in the exclude mask.
				if ($exclude) {
					if (preg_match(chr(1).$exclude.chr(1), $folder)) {
						continue;
					}
				}

				$options[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', $folder, $folder);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	protected function getInput() {

		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		// check if the theme is for the latest version of maximenu
		if ( file_exists(JPATH_ROOT . '/modules/mod_maximenuck/themes/' . $this->value) && ! file_exists(JPATH_ROOT . '/modules/mod_maximenuck/themes/' . $this->value . '/css/maximenuck.php') ) {
			$theme_checking_text = '<img src="' . MAXIMENUCK_MEDIA_URI . '/images/' . 'error.png" />' . \Joomla\CMS\Language\Text::_('MOD_MAXIMENUCK_THEME_OBSOLETE');
		} else {
			$theme_checking_text = '';
		}

		$html = $icon ? '<div class="maximenuck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . MAXIMENUCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="maximenuck-field-suffix">' . $suffix . '</span>';

		if ( $theme_checking_text !== '' ) {
			$html .= '<style>.maximenuckthemechecking {background:#e1e1e1;border: none;
	border-radius: 3px;
	color: #333;
	font-weight: normal;
	line-height: 24px;
	padding: 5px;
	margin: 3px 0;
	text-align: left;
	text-decoration: none;
}
.maximenuckthemechecking img {
	margin: 0 5px;
}</style>';
			$html .= '<div class="maximenuckthemechecking">' . $theme_checking_text . '</div>';
		}

		return $html;
	}
}
