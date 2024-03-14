<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="type" name="type" class=""  value="image" disabled type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_PREVIEW'); ?>
		</span>
		<span class="ckoption-field">
			<img id="ckimagepreview" src="" style="max-height: 150px;margin: 10px 0;"/>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="imageurl" name="imageurl" class="inputbox"  value="" type="text" onchange="ckUpdateImagePreview();"/>
			<a class="ckbutton" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', id: 'ckimagemanager', url: MAXIMENUCK.BASE_URL + '&view=browse&type=image&func=ckSelectFile&field=imageurl&tmpl=component'})" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="$ck('#imageurl').val('');ckUpdateImagePreview();"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAN'); ?></a>
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_WIDTH'); ?>
		</span>
		<span class="ckoption-field">
			<input id="imagewidth" name="imagewidth" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_HEIGHT'); ?>
		</span>
		<span class="ckoption-field">
			<input id="imageheight" name="imageheight" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_ALT_TAG'); ?>
		</span>
		<span class="ckoption-field">
			<input id="imagealt" name="imagealt" class="inputbox"  value="" type="text" />
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
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_LINK'); ?></div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_LINK'); ?>
		</span>
		<span class="ckoption-field">
			<input id="anchor" name="anchor" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_REL_ATTRIBUTE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="relattr" name="relattr" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_CSS_CLASS'); ?>
		</span>
		<span class="ckoption-field">
			<input id="linkcssclass" name="linkcssclass" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<?php include 'options_responsive.php'; ?>
</div>
<script>
function ckLoadEditionItem() {
	ckUpdateImagePreview();
}

function ckUpdateImagePreview(selector = '#ckimagepreview') {
	if ($ck('#imageurl').val()) {
		var imageurl = '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>' +  '/' + $ck('#imageurl').val();
	} else {
		var imageurl = '';
	}
	$ck(selector).attr('src', imageurl);
}

</script>