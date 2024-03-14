<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<textarea id="texteditor" class="joomla-editor-tinymce"></textarea>
<div id="ckeditorwraptmp"></div>
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<div>
		<span class="ckoption-label">
			<?php echo \Joomla\CMS\Language\Text::_('CK_TYPE'); ?>
		</span>
		<span class="ckoption-field">
			<input id="type" name="type" class=""  value="text" disabled type="hidden" />
		</span>
		<div class="clr"></div>
	</div>
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
			<select  id="imagealign" name="imagealign">
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
			<?php echo \Joomla\CMS\Language\Text::_('CK_CSS_CLASS'); ?>
		</span>
		<span class="ckoption-field">
			<input id="cssclass" name="cssclass" class="inputbox"  value="" type="text" />
		</span>
		<div class="clr"></div>
	</div>
	<?php include 'options_image.php'; ?>
	<?php include 'options_icon.php'; ?>
	<?php include 'options_responsive.php'; ?>
</div>
<script>
function ckLoadEditionItem() {
	var textID = 'texteditor';

	if ($ck('.ckitemfocus textarea').length) {
		content =  focus.find('.cktext').html();
		content = ckContentToEditor(content);
		$ck('#' + textID).val(content);
	}
	ckLoadEditorOnTheFly(textID);
}
ckLoadEditionItem();

function ckBeforeSaveItem() {
	var textID = 'texteditor';
	ckSaveEditorOnTheFly(textID);
	var content = $ck('#' + textID).val();
	content = ckEditorToContent(content);

	var focus = $ck('.editfocus');
	focus.find('.cktext').html(content);
}

</script>