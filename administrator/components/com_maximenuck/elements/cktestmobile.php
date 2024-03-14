<?php
/**
 * @copyright	Copyright (C) 2011-2020 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldCktestmobile extends \Joomla\CMS\Form\FormField {

    protected $type = 'cktestmobile';

    protected function getLabel() {
        return '';
    }

    protected function getInput() {
		$app = \Joomla\CMS\Factory::getApplication();
		$html = array();
		$class = $this->element['class'] ? (string) $this->element['class'] : '';

		$style = $this->element['style'];

		// check for mobile menu ck instead of maximenu mobile
		// if mobile menu ck active and not maximenu mobile, then hide maximenu mobile options
		if (function_exists('loadMobileMenuCK')) {
			$db = \Joomla\CMS\Factory::getDbo();
			$db->setQuery("SELECT enabled FROM #__extensions WHERE `element` = 'maximenuckmobile' AND `type` = 'plugin'");
			$enabled = $db->loadResult();
			if (! $enabled) {
				$html[] = '<style>a[href*="mobileparams"], #attrib-maximenu_mobileparams, [aria-controls="attrib-maximenu_mobileparams"] { display: none !important; }</style>';
				return implode('', $html);
			}
		}
        // $html[] = '<div class="maximenuckchecking">';
        // $html[] = '<span class="before"></span>';
        // $html[] = '<span class="' . $class . '">';
        if ((string) $this->element['hr'] == 'true') {
            $html[] = '<hr class="' . $class . '" />';
        } else {
            $label = '';
            // Get the label text from the XML element, defaulting to the element name.
            $text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
            $text = $this->translateLabel ? \Joomla\CMS\Language\Text::_($text) : $text;
            // Test to see if the patch is installed
            $testpatch = $this->testPatch('maximenuckmobile');
            $text = $testpatch ? $testpatch : $text;
            // set the icon
            $icon = $this->element['icon'];

            // Build the class for the label.
            $class = !empty($this->description) ? 'hasTip' : '';
            $class = $this->required == true ? $class . ' required' : $class;

            // Add the opening label tag and main attributes attributes.
            // $label .= '<label id="' . $this->id . '-lbl" class="' . $class . '"';

            // If a description is specified, use it to build a tooltip.
            if (!empty($this->description)) {
                $label .= ' title="' . htmlspecialchars(trim($text, ':') . '::' .
                    ($this->translateDescription ? \Joomla\CMS\Language\Text::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8') . '"';
            }

            // Add the label text and closing tag.
            // $label .= $styles . '>';
            $label .= $icon ? '<img src="' . MAXIMENUCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" />' : '';
            $label .= $text;
			// $label .= '</label>';
            // $html[] = $label;
			$html[] = '<div class="ckinfo"><i class="fas fa-' . $icon . '" style="color:' . $this->element['iconcolor'] . '"></i>' . $text . '</div>';
        }
        // $html[] = '</span>';
        // $html[] = '<span class="after"></span>';
        // $html[] = '</div>';
		
        return implode('', $html);
    }

	protected function testPatch($component) {
		if (file_exists(JPATH_ROOT . '/plugins/system/' . $component .'/'. $component . '.php')
				&& \Joomla\CMS\Plugin\PluginHelper::isEnabled('system',$component)) {
			$this->element['icon'] = 'info';
			$this->element['iconcolor'] = 'green';
			return \Joomla\CMS\Language\Text::_('MOD_MAXIMENUCK_SPACER_' . strtoupper($component) . '_PATCH_INSTALLED');
		} else {
			$this->element['icon'] = 'exclamation';
			$this->element['iconcolor'] = 'orange';
			return \Joomla\CMS\Language\Text::_('MOD_MAXIMENUCK_CHECK_MOBILEMENUCK') . ' <a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank">Mobile Menu CK</a>';
		}
		return false;
	}
}