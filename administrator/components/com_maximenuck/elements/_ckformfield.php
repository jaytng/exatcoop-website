<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

// require_once 'ckformfield.php';
require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/defines.php';
require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/ckframework.php';

use Maximenuck\CKFof;

class CKFormField extends \Joomla\CMS\Form\FormField {

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

	protected function getInput() {
		return parent::getInput();
	}

	protected function getLabel() {
		return parent::getLabel();
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	/*protected function getOptions() {
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
							'select.option', (string) $option['value'],
							\Joomla\CMS\Language\Text::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
							((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}*/
}
