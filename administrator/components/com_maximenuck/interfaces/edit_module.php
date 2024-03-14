<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="type" name="type" class=""  value="module" disabled type="hidden" />
	<input name="title" class="inputbox"  value="" type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_OPTIONS'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_MODULE_ID'); ?>
		</span>
		<span class="ckoption-field">
			<input name="id" class=""  value="" disabled type="text" />
			<a class="ckbutton" href="javascript:void(0)" onclick="CKBox.open({id: 'ckitemsselectpopup', url: MAXIMENUCK.BASE_URL + '&view=items&tmpl=component&type=module&returnFunc=ckUpdateModuleId'});" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="window.open(MAXIMENUCK.URIROOT + '/administrator/index.php?option=com_modules&task=module.edit&id=' + document.querySelector('#ck-item-edition-item-params input[name=id]').value)" ><?php echo \Joomla\CMS\Language\Text::_('CK_EDIT'); ?></a>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_TITLE'); ?>
		</span>
		<span class="ckoption-field">
			<input name="title" class="inputbox" disabled value="" type="text" />
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
	<?php include 'options_responsive.php'; ?>
</div>
