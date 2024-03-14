<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldMaximenuckbackground extends \Joomla\CMS\Form\FormField {

	protected $type = 'maximenuckbackground';

	protected function getInput() {
		$styles = $this->element['styles'];
		$background = $this->element['background'] ? 'background: url(' . MAXIMENUCK_MEDIA_URI . '/images/' . $this->element['background'] . ') left top no-repeat;' : '';

		$html = '<p style="' . $background . $styles . '" ></p>';
		return $html;
	}

	protected function getLabel() {
		return '';
	}
}
