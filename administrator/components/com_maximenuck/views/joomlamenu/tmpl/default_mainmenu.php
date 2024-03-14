<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="ckheader">
	<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
	<div class="ckheadermenu">
		<div class="ckheadertitle">MAXIMENU CK
				<span class="ckpopuptitle"> - <?php echo \Joomla\CMS\Language\Text::_('CK_MENU_EDITION') . ' : <span style="color:#f5f5f5;">' . ucfirst($this->input->get('menutype', 'string', '')) ?></span></span>
		</div>
		<div id="ckmessage">
			<div></div>
		</div>
		<a href="javascript:void(0)" onclick="window.parent.CKBox.close()" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?></span>
		</a>
	</div>
</div>
