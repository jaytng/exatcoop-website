<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="type" name="type" class=""  value="menuitem" disabled type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_OPTIONS'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_MENU_ITEM_ID'); ?>
		</span>
		<span class="ckoption-field">
			<input name="id" class=""  value="" disabled type="text" />
			<a class="ckbutton" href="javascript:void(0)" onclick="CKBox.open({id: 'ckitemsselectpopup', url: MAXIMENUCK.BASE_URL + '&view=items&tmpl=component&type=menuitem&returnFunc=ckUpdateItemId'});" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="window.open(MAXIMENUCK.URIROOT + '/administrator/index.php?option=com_menus&task=item.edit&id=' + document.querySelector('#ck-item-edition-item-params input[name=id]').value)" ><?php echo \Joomla\CMS\Language\Text::_('CK_EDIT'); ?></a>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_TITLE'); ?>
		</span>
		<span class="ckoption-field">
			<input name="title" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_DESCRIPTION'); ?>
		</span>
		<span class="ckoption-field">
			<input id="desc" name="desc" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ACCESSKEY'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_accesskey" name="maximenu_accesskey" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ANCHOR'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_anchor" name="maximenu_anchor" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_REL_ATTRIBUTE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_relattr" name="maximenu_relattr" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_URL_SUFFIX'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_urlsuffix" name="maximenu_urlsuffix" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_LI_CSS_CLASS'); ?>
		</span>
		<span class="ckoption-field">
			<input id="maximenu_liclass" name="maximenu_liclass" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<?php include 'options_image.php'; ?>
	<?php include 'options_icon.php'; ?>
	<?php include 'options_responsive.php'; ?>
</div>
