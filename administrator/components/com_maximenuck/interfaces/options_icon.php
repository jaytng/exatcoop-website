<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_ICON'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ICON'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_icon" name="maximenu_icon" class="inputbox"  value="" type="text" placeholder="Ex : fa fa-eye"/>
			<a class="ckbutton" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', id: 'ckiconmanager', url: MAXIMENUCK.BASE_URL + '&view=icons&func=ckSelectFaIcon&field=maximenu_icon&tmpl=component'})" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="$ck('#maximenu_icon').val('');"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAN'); ?></a>
		</span>
		<div class="clr"></div>
	</div>
	<?php /*<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ICON_POSITION'); ?> !!!
		</span>
		<span class="ckoption-field">
			<input id="iconpos" name="iconpos" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ICON_SIZE'); ?> !!!
		</span>
		<span class="ckoption-field">
			<input id="iconsize" name="iconsize" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div> */ ?>