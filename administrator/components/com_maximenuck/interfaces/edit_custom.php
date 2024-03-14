<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="type" name="type" class="" value="custom" disabled type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_OPTIONS'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_URL'); ?>
		</span>
		<span class="ckoption-field">
			<input id="link" name="link" class="inputbox"  value="" type="text" />
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
			<?php echo \Joomla\CMS\Language\Text::_('CK_LINK_TARGET'); ?>
		</span>
		<span class="ckoption-field">
			<select id="maximenu_targetattr" name="maximenu_targetattr" class="inputbox" >
				<option value=""><?php echo \Joomla\CMS\Language\Text::_('CK_DEFAULT'); ?></option>
				<option value="_blank"><?php echo \Joomla\CMS\Language\Text::_('CK_LINK_TARGET_NEW'); ?></option>
			</select>
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
