<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="menu_image" name="menu_image" class="inputbox"  value="" type="text" />
			<a class="ckbutton" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', id: 'ckimagemanager', url: MAXIMENUCK.BASE_URL + '&view=browse&type=image&func=ckSelectFile&field=menu_image&tmpl=component'})" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="$ck('#menu_image').val('');"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAN'); ?></a>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_ALIGN'); ?>
		</span>
		<span class="ckoption-field">
			<select  id="maximenu_images_align" name="maximenu_images_align">
				<option value="moduledefault"><?php echo \Joomla\CMS\Language\Text::_('CK_MODULEDEFAULT'); ?></option>
				<option value="default"><?php echo \Joomla\CMS\Language\Text::_('CK_DEFAULT'); ?></option>
				<option value="top"><?php echo \Joomla\CMS\Language\Text::_('CK_TOP'); ?></option>
				<option value="bottom"><?php echo \Joomla\CMS\Language\Text::_('CK_BOTTOM'); ?></option>
				<option value="lefttop"><?php echo \Joomla\CMS\Language\Text::_('CK_TOPLEFT'); ?></option>
				<option value="leftmiddle"><?php echo \Joomla\CMS\Language\Text::_('CK_MIDDLELEFT'); ?></option>
				<option value="leftbottom"><?php echo \Joomla\CMS\Language\Text::_('CK_BOTTOMLEFT'); ?></option>
				<option value="righttop"><?php echo \Joomla\CMS\Language\Text::_('CK_TOPRIGHT'); ?></option>
				<option value="rightmiddle"><?php echo \Joomla\CMS\Language\Text::_('CK_MIDDLERIGHT'); ?></option>
				<option value="rightbottom"><?php echo \Joomla\CMS\Language\Text::_('CK_BOTTOMRIGHT'); ?></option>
			</select>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_WIDTH'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenuparams_imgwidth" name="maximenuparams_imgwidth" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_HEIGHT'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenuparams_imgheight" name="maximenuparams_imgheight" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ALT_TAG'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenuparams_imagealt" name="maximenuparams_imagealt" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>