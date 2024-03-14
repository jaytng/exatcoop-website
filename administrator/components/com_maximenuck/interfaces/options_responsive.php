<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_RESPONSIVE_SETTINGS'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_MOBILE_ENABLE'); ?>
		</span>
		<span class="ckoption-field">
			<select class="inputbox" type="list" value="1" name="maximenu_disablemobile" id="maximenu_disablemobile" style="width:auto;">
				<option value="0"><?php echo \Joomla\CMS\Language\Text::_('JYES'); ?></option>
				<option value="1"><?php echo \Joomla\CMS\Language\Text::_('JNO'); ?></option>
			</select>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_DESKTOP_ENABLE'); ?>
		</span>
		<span class="ckoption-field">
			<select class="inputbox" type="list" value="1" name="maximenu_disabledesktop" id="maximenu_disabledesktop" style="width:auto;">
				<option value="0"><?php echo \Joomla\CMS\Language\Text::_('JYES'); ?></option>
				<option value="1"><?php echo \Joomla\CMS\Language\Text::_('JNO'); ?></option>
			</select>
		</span>
		<div class="clr"></div>
	</div>