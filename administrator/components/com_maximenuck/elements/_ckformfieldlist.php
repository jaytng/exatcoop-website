<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
\Joomla\CMS\Form\FormHelper::loadFieldClass('list');

// require_once 'ckformfield.php';
require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/defines.php';
require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/ckframework.php';

use Maximenuck\CKFof;

class CKFormFieldList extends \Joomla\CMS\Form\Field\ListField {

	public $mediaPath;

	public $moduleParams;

	protected $input;

	public function __construct() {
		$this->mediaPath = \Joomla\CMS\Uri\Uri::root(true) . '/media/com_maximenuck/images/';
		$this->input = \Joomla\CMS\Factory::getApplication()->input;

		// get the module settings
		$module = CKFof::dbLoad('#__modules', \Joomla\CMS\Factory::getApplication()->input->get('id', 0, 'int'));
		$moduleParams = new \Joomla\Registry\Registry;
		$moduleParams->loadString($module->params);
		$this->moduleParams = $moduleParams;

		// loads the language files from the frontend
		$lang	= \Joomla\CMS\Factory::getLanguage();
		$lang->load('com_maximenuck', JPATH_SITE . '/components/com_maximenuck', $lang->getTag(), false);
		$lang->load('com_maximenuck', JPATH_SITE, $lang->getTag(), false);
		parent::__construct();
	}
}
