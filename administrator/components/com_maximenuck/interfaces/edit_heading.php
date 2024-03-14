<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="type" name="type" class=""  value="heading" disabled type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_OPTIONS'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_TITLE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="title" name="title" class="inputbox"  value="" type="text" />
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
			<?php echo \Joomla\CMS\Language\Text::_('CK_HTML_TAG'); ?>
		</span>
		<span class="ckoption-field">
			<select class="inputbox" id="maximenu_tagcoltitle" name="params[maximenu_tagcoltitle]">
				<option value="none"><?php echo \Joomla\CMS\Language\Text::_('CK_NONE'); ?></option>
				<option value="div">DIV</option>
				<option value="p">P</option>
				<option value="h2">H2</option>
				<option value="h3">H3</option>
				<option value="h4">H4</option>
				<option value="h5">H5</option>
				<option value="h6">H6</option>
			</select>
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
