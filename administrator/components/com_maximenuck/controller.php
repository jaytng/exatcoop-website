<?php
/**
 * @name		Maximenu CK
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKController;
use Maximenuck\CKFof;
use Maximenuck\CKText;

class MaximenuckController extends CKController {

	static function getInstance($prefix = '') {
		return parent::getInstance('Maximenuck');
	}

	public function display($cachable = false, $urlparams = false) {
		$view = $this->input->get('view', 'modules');
		$this->input->set('view', $view);

		parent::display();

		return $this;
	}

	/**
	 * Ajax method to clean the name of the google font
	 */
	public function cleanGfontName() {
		$gfont = $this->input->get('gfont', '', 'string');

		// <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
		// Open+Sans+Condensed:300
		// Open Sans
		if ( preg_match( '/family=(.*?) /', $gfont . ' ', $matches) ) {
			if ( isset($matches[1]) ) {
				$gfont = $matches[1];
			}
		}

		$gfont = str_replace(' ', '+', ucwords (trim($gfont)));
		echo trim(trim($gfont, "'"));
		die;
	}
}
