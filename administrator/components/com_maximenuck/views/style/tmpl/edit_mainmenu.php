<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="ckheader">
	<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
	<div class="ckheadermenu">
		<div class="ckheadertitle">MAXIMENU CK</div>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckImportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-import cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_IMPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_IMPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckExportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-export cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_EXPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_EXPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckClearFields()">
			<i class="fa fas fa-broom cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_CLEAR_FIELDS') ?>"></i>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAR_FIELDS') ?></span>
		</a>
		<a href="javascript:void(0);" id="ckpopupstyleswizard_makepreview" class="ckheadermenuitem" onclick="ckPreviewStyles()">
			<i class="fa fas fa-eye cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PREVIEW') ?>"></i>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_PREVIEW') ?></span>
		</a>
		<?php /*if ($this->input->get('layout', '', 'string') === 'modal') {*/ ?>
		<a href="javascript:void(0)" onclick="window.parent.CKBox.close()" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?></span>
		</a>
		<?php /*} else { ?>
		<a href="<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>/index.php?option=com_maximenuck&view=styles" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?></span>
		</a>	
		<?php } */ ?>	
		<a href="javascript:void(0);" id="ckpopupstyleswizard_save" class="ckheadermenuitem cksave" onclick="ckSaveStyles()">
			<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?></span>
		</a>
	</div>
</div>