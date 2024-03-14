<?php

/**
 * @copyright	Copyright (C) 2011-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

include_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/defines.php';

class JFormFieldMaximenucklist extends \Joomla\CMS\Form\Field\ListField {

	protected $type = 'maximenucklist';

	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		$html = $icon ? '<div class="maximenuck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . MAXIMENUCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="maximenuck-field-suffix">' . $suffix . '</span>';
		return $html;
	}

}
