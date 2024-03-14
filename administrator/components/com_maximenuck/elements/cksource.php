<?php
/**
 * @copyright	Copyright (C) 2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

class JFormFieldCksource extends \Joomla\CMS\Form\Field\ListField
{

	protected $type = 'cksource';

	private $options;

	function __construct($form = null) {
		parent::__construct($form);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
				'select.option', (string) $option['value'], \Joomla\CMS\Language\Text::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', ((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		$this->options = $options;

		// load the custom plugins
		if (\Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'maximenuck')) {
			// load the custom plugins
			require_once(JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/ckfof.php');
			Maximenuck\CKFof::importPlugin('maximenuck');
			$sources = Maximenuck\CKFof::triggerEvent('onMaximenuckGetSourceName');

			if (count($sources)) {
				foreach ($sources as $source) {

					if (! $this->findOption($source)) {
						$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
							'select.option', (string) $source, \Joomla\CMS\Language\Text::alt(trim((string) 'MAXIMENUCK_SOURCE_' . strtoupper($source)), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', '0'
						);
						// Add the option object to the result set.
						$this->options[] = $tmp;
					}
				}
			}
		}

		reset($this->options);

		return $this->options;
	}

	public function findOption($source) {
		foreach ($this->options as $o) {
			if ($o->value == $source) return true;
		}
		return false;
	}
}
