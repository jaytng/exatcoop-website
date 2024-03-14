<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access

defined('_JEXEC') or die('Restricted access');
if (isset($loadModuleMobileIcon) && $loadModuleMobileIcon == true) {
	if ($params->get('maximenumobile_enable') === '1' && !$isMaximenuMobilePluginActive) {
		echo '<label for="' . $params->get('menuid', 'maximenuck') . '-maximenumobiletogglerck" class="maximenumobiletogglericonck" style="display:none;">&#x2261;</label>'
				. '<a href="#" class="maximenuck-toggler-anchor" aria-label="Open menu" >Open menu</a>'
				. '<input id="' . $params->get('menuid', 'maximenuck') . '-maximenumobiletogglerck" class="maximenumobiletogglerck" type="checkbox" style="display:none;"/>';
	}
}