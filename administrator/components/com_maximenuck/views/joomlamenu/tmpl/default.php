<?php
// no direct access
defined('_JEXEC') or die;

use Maximenuck\CKFof;
use Maximenuck\Helper;
use Maximenuck\CKFramework;


$user		= \Joomla\CMS\Factory::getUser();
$canEdit    = $user->authorise('core.edit', 'com_maximenuck');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB" dir="ltr">
<head>
<?php 
require_once(MAXIMENUCK_PATH . '/helpers/defines.js.php');
// load everything inline
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/jqueryck.js');
CKFramework::loadInline();
CKFramework::loadFaIconsInline();
Helper::loadCkboxInline();

CKFof::addStylesheetInline(MAXIMENUCK_MEDIA_URI . '/assets/admin.css');
CKFof::addStylesheetInline(MAXIMENUCK_MEDIA_URI . '/assets/joomlamenu.css');

// fix until get better solution to work with the drag and drop
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/jquery.min.1.8.1.js');

CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/jscolor/jscolor.js');
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/admin.js');
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/jquery-ui-1.10.2.custom.min.js');
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/nestedsortable.js');
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/joomlamenu.js');
?>
<style>
body {
	padding-top: 66px;
}
</style>
</head>
<body class="ckinterface">
<?php 
require dirname(__FILE__) . '/default_mainmenu.php';
?>
<?php if ($canEdit) { ?>
<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_maximenuck&view=joomlamenu');?>" method="post" name="adminForm" id="adminForm">
	<div>
		<ol class="sortable" id="sortable">
			<?php
			foreach ($this->items as $i => $item) :
				$item->params = new \Joomla\Registry\Registry($item->params);
				$newcol = $item->params->get('maximenu_createcolumn', 0);
				$newrow = $item->params->get('maximenu_createnewrow', 0);
				$subwidth = $item->params->get('maximenu_submenucontainerwidth', '');
				$subheight = $item->params->get('maximenu_submenucontainerheight', '');
				$subleft = $item->params->get('maximenu_leftmargin', '');
				$subtop = $item->params->get('maximenu_topmargin', '');
				$tabwidth = $item->params->get('maximenu_tabwidth', '');

				// Parse the link arguments.
				$link = htmlspecialchars_decode($item->link);
				?>
				<li class="clearfix" data-alias="<?php echo $item->alias ?>" data-level="<?php echo $item->level ?>" data-id="<?php echo (int) $item->id; ?>" data-parent="<?php echo (int) $item->parent_id; ?>" data-newcol="<?php echo $item->params->get('maximenu_createcolumn', 0) ?>" data-colwidth="<?php echo $item->params->get('maximenu_colwidth', '180') ?>" data-home="<?php echo $item->home ?>">
					<div class="itemwrapper">
						<span class="disclose ckaction"><span></span></span>
						<div>
							<span class="ckaction" onmousedown="ckProOny()"><i class="fas fa-arrows-alt"></i></span>
							<span class="ckbutton-group">
								<?php if ($canEdit) : ?>
								<a id="publish<?php echo $item->id ?>" class="ckstatus cktip ckbutton active" rel="" title="<?php echo \Joomla\CMS\Language\Text::_('JSTATUS') ?>" data-state="<?php echo $item->published ?>" data-id="<?php echo $item->id ?>" onclick="ckPublish(this)" href="javascript:void(0);">
									<i class="fas fa-<?php echo ($item->published ? 'check' : 'times-circle') ?>"></i>
								</a>
								<?php else : ?>
									<a id="publish<?php echo $item->id ?>" class="ckstatus disabled cktip ckbutton active" rel="" title="<?php echo \Joomla\CMS\Language\Text::_('JSTATUS') ?>" data-state="<?php echo $item->published ?>" data-id="<?php echo $item->id ?>" href="javascript:void(0);">
										<i class="fas fa-<?php echo ($item->published ? 'check' : 'times-circle') ?>"></i>
									</a>
								<?php endif; ?>
								<?php if ($item->checked_out && $canEdit) : ?>
									<?php
									$text = $item->editor . '<br />' . \Joomla\CMS\HTML\HTMLHelper::_('date', $item->checked_out_time, \Joomla\CMS\Language\Text::_('DATE_FORMAT_LC')) . '<br />' . \Joomla\CMS\HTML\HTMLHelper::_('date', $item->checked_out_time, 'H:i');
									$active_title = \Joomla\CMS\Language\Text::_('JLIB_HTML_CHECKIN') . ' ' . $text;
									?>
									<a class="cktip ckbutton checkedouticon" onclick="ckCheckin(this,<?php echo $item->id ?>)" href="javascript:void(0);" title="<?php echo $active_title; ?>">
										<i class="fas fa-lock"></i>
									</a>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
								<a class="cktip ckbutton edititem" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_EDIT_ITEM'); ?>" onclick="ckEditItem(this)"><i class="fas fa-external-link-alt"></i></a>
								<a class="cktip ckbutton edittitle" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_EDIT_TITLE'); ?>" onclick="ckEditTitle(this)"><i class="fas fa-pencil-alt"></i></a>
								<?php endif; ?>
							</span>
								<?php if ($canEdit) : ?>
									<span class="cktip cktitle" ondblclick="ckEditTitle(this)" href="javascript:void(0)" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_DBLCLICK_TO_EDIT'); ?>" data-id="<?php echo (int) $item->id; ?>">
										<?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false); ?>
									</span>
									<span class="ckbutton-group">
										<span class="cktip ckbutton exittitle" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_SAVE'); ?>" onclick="ckUpdateTitle($ck('.cktitle', $ck(this).parent().parent()))" style="display:none;"><i class="fas fa-check"></i></span>
										<span class="cktip ckbutton exittitle" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_CANCEL'); ?>" onclick="ckExitTitle($ck('.cktitle', $ck(this).parent().parent()))" style="display:none;"><i class="fas fa-times"></i></span>
									</span>
								<?php else : ?>
									<?php echo addslashes($item->title); ?>
								<?php endif; ?>
									
								

								<span class="ckbutton-group columnbuttons">
									<a class="cktip ckbutton createcolumn<?php if ($newcol) echo ' active'; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_CREATE_COLUMN'); ?>" onclick="ckProOny()">
										<i class="fas fa-columns"></i>
									</a>
									<a class="cktip ckbutton colwidth btnvalue" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_COLUMN_WIDTH'); ?>" onclick="ckProOny()"><?php echo $item->params->get('maximenu_colwidth', '180'); ?></a>
									
									<a class="cktip ckbutton createnewrow<?php if ($newrow) echo ' active'; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_CREATE_ROW'); ?>" onclick="ckProOny()">
										<i class="fas fa-grip-lines"></i>
									</a>
								</span>
								<span class="ckbutton-group submenuwidthbuttons">
									<a class="cktip ckbutton submenuwidth btnvalue <?php echo ($subwidth ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_SUBMENUWIDTH'); ?>" onclick="ckProOny()"><i class="fas fa-arrows-alt-h"></i><span class="valuetxt"><?php echo $subwidth; ?></span></a>
									<a class="cktip ckbutton submenuheight btnvalue <?php echo ($subheight ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_SUBMENUHEIGHT'); ?>" onclick="ckProOny()"><i class="fas fa-arrows-alt-v"></i><span class="valuetxt"><?php echo $subheight; ?></span></a>
									<a class="cktip ckbutton submenuleftmargin btnvalue <?php echo ($subleft ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_SUBMENULEFTMARGIN'); ?>" onclick="ckProOny()"><i class="fas fa-angle-double-right"></i><span class="valuetxt"><?php echo $subleft; ?></span></a>
									<a class="cktip ckbutton submenutopmargin btnvalue <?php echo ($subtop ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_SUBMENUTOPMARGIN'); ?>" onclick="ckProOny()"><i class="fas fa-angle-double-down"></i><span class="valuetxt"><?php echo $subtop; ?></span></a>
									<a class="cktip ckbutton fullwidth<?php if (stristr($item->params->get('maximenu_liclass', ''), 'fullwidth')) echo ' active ckbutton-primary'; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_FULLWIDTH'); ?>" onclick="ckProOny()"><i class="fas fa-expand"></i></a>
									<a class="cktip ckbutton maximenucktab<?php if (stristr($item->params->get('maximenu_liclass', ''), 'maximenucktab')) echo ' active ckbutton-primary'; ?> btn-value <?php echo ($tabwidth ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_TAB'); ?>" onclick="ckProOny()"><i class="fas fa-border-all"></i><span class="valuetxt"><?php echo $tabwidth; ?></a>
								</span>
								<span class="ckbutton-group togglemobileoptions">
									<span class="togglescreen ckbutton cktip <?php echo ($item->params->get('maximenu_disabledesktop', '0') == '1' ? 'disable' : '') ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ENABLE_DESKTOP'); ?>" onclick="ckProOny()"><span class="fas fa-times"></span><i class="fas fa-desktop"></i></span>
									<span class="togglemobile ckbutton cktip <?php echo ($item->params->get('maximenu_disablemobile', '0') == '1' ? 'disable' : '') ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ENABLE_MOBILE'); ?>" onclick="ckProOny()"><span class="fas fa-times"></span><i class="fas fa-mobile-alt"></i></span>
								</span>

								<?php
								$imagepreview = $item->params->get('menu_image','') ? '<br /><img src=&quot;' . (\Joomla\CMS\Uri\Uri::root(true) . '/' . $item->params->get('menu_image','')) . '&quot; class=&quot;cktip-img-preview&quot; />' : '';
								?>
								<span class="ckbutton-group imageoptions">
									<span id="imageselect<?php echo $item->id ?>" class="imageselect ckbutton cktip <?php echo ($item->params->get('menu_image','') ? 'active' : '') ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_IMAGE') . $imagepreview ; ?>" onclick="ckSelectImage(this.id)"><i class="far fa-image"></i></span>
									<span id="imageremove" class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_IMAGE_REMOVE'); ?>" onclick="ckRemoveImage(this);"><i class="fas fa-times"></i></span>
								</span>
								<span class="ckbutton-group iconoptions">
									<span id="iconselect<?php echo $item->id ?>" class="iconselect ckbutton cktip <?php echo ($item->params->get('maximenu_icon','') ? 'active' : '') ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_ICON_DESC'); ?>" onclick="ckProOny()"><?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_ICON'); ?>&nbsp;<span class="<?php echo $item->params->get('maximenu_icon','') ?>"></span></span>
									<span id="iconremove" class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_ICON_REMOVE'); ?>" onclick="ckRemoveIcon(this);"><i class="fas fa-times"></i></span>
								</span>
								<?php
								$moduleid = $item->params->get('maximenu_module','');
								$modulebtnclass = $item->params->get('maximenu_insertmodule','') ? 'active' : '';
								$moduletitle = isset($this->modules[$moduleid]) ? $this->modules[$moduleid]->title : '';
								$modulename = $item->params->get('maximenu_insertmodule','') ? '<span class="moduleid">' . $item->params->get('maximenu_module','') . '</span>&nbsp;' . $moduletitle : '';
								?>
								<span class="moduleoptions ckbutton-group">
									<span id="moduleselect<?php echo $item->id ?>" class="moduleselect ckbutton cktip <?php echo $modulebtnclass ?> <?php echo ($modulename ? 'ckhastext' : ''); ?>" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_MODULE'); ?>" onclick="ckProOny()"><i class="fas fa-database"></i><span class="modulename"><?php echo $modulename; ?></span></span>
									<span id="moduleremove" class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_ITEM_MODULE_REMOVE'); ?>" onclick="ckRemoveModule(this);"><i class="fas fa-times"></i></span>
								</span>
								<span class="ckid cklabel cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ID') ?>"><?php echo $item->id ?></span>
						</div>
					</div>
				<?php
				// The next item is deeper.
				if ($item->deeper)
				{
					echo '<ol>';
				}
				// The next item is shallower.
				elseif ($item->shallower)
				{
					echo '</li>';
					echo str_repeat('</ol></li>', $item->level_diff);
				}
				// The next item is on the same level.
				else {
					echo '</li>';
				}
				endforeach; ?>
		</ol>
		<input type="hidden" name="menutype" id="menutype" value="<?php echo \Joomla\CMS\Factory::getApplication()->input->get('menutype'); ?>" />
		<input type="hidden" name="task" value="" />
		<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
	</div>
</form>
<?php } else {
	if (!$canEdit) echo \Joomla\CMS\Language\Text::_('COM_MAXIMENUCK_NORIGHTS_TO_EDIT');
} ?>
<script>
(function() {
	var strings = {"CK_CONFIRM_DELETE": "<?php echo \Joomla\CMS\Language\Text::_('CK_CONFIRM_DELETE') ?>", 
		"CK_FAILED_SET_TYPE": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_SET_TYPE') ?>",
		"CK_FAILED_SAVE_ITEM_ERRORMENUTYPE": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_SAVE_ITEM_ERRORMENUTYPE') ?>",
		"CK_ALIAS_EXISTS_CHOOSE_ANOTHER": "<?php echo \Joomla\CMS\Language\Text::_('CK_ALIAS_EXISTS_CHOOSE_ANOTHER') ?>",
		"CK_FAILED_SAVE_ITEM_ERROR500": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_SAVE_ITEM_ERROR500') ?>",
		"CK_FAILED_SAVE_ITEM": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_SAVE_ITEM') ?>",
		"CK_FAILED_TRASH_ITEM": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_TRASH_ITEM') ?>",
		"CK_FAILED_CREATE_ITEM": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_CREATE_ITEM') ?>",
		"CK_UNABLE_UNPUBLISH_HOME": "<?php echo \Joomla\CMS\Language\Text::_('CK_UNABLE_UNPUBLISH_HOME') ?>",
		"CK_TITLE_NOT_UPDATED": "<?php echo \Joomla\CMS\Language\Text::_('CK_TITLE_NOT_UPDATED') ?>",
		"CK_LEVEL_NOT_UPDATED": "<?php echo \Joomla\CMS\Language\Text::_('CK_LEVEL_NOT_UPDATED') ?>",
		"CK_SAVE_LEVEL_FAILED": "<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE_LEVEL_FAILED') ?>",
		"CK_SAVE_ORDER_FAILED": "<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE_ORDER_FAILED') ?>",
		"CK_CHECKIN_NOT_UPDATED": "<?php echo \Joomla\CMS\Language\Text::_('CK_CHECKIN_NOT_UPDATED') ?>",
		"CK_CHECKIN_FAILED": "<?php echo \Joomla\CMS\Language\Text::_('CK_CHECKIN_FAILED') ?>",
		"CK_PARAM_NOT_UPDATED": "<?php echo \Joomla\CMS\Language\Text::_('CK_PARAM_NOT_UPDATED') ?>",
		"CK_PARAM_UPDATE_FAILED": "<?php echo \Joomla\CMS\Language\Text::_('CK_PARAM_UPDATE_FAILED') ?>",
		"CK_FIRST_CREATE_ROW": "<?php echo \Joomla\CMS\Language\Text::_('CK_FIRST_CREATE_ROW') ?>",
		"CK_NO_COLUMN_ON_ROOT_ITEM": "<?php echo \Joomla\CMS\Language\Text::_('CK_NO_COLUMN_ON_ROOT_ITEM') ?>",
		"CK_SAVE": "<?php echo \Joomla\CMS\Language\Text::_('JSAVE') ?>",
		"CK_FIRST_CLEAR_VALUE": "<?php echo \Joomla\CMS\Language\Text::_('CK_FIRST_CLEAR_VALUE') ?>",
		"CK_SAVE": "<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?>",
		"CK_SAVE_CLOSE": "<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE_CLOSE') ?>",
		"CK_IMAGE": "<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE') ?>",
		"CK_FAILED_VALIDATE_PATH": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_VALIDATE_PATH') ?>",
		"CK_FAILED_PATHEXISTS": "<?php echo \Joomla\CMS\Language\Text::_('CK_FAILED_PATHEXISTS') ?>",
		"CK_CONFIRM_DELETE": "<?php echo \Joomla\CMS\Language\Text::_('CK_CONFIRM_DELETE') ?>"
	};
	CKApi.Text.load(strings);
})();

jQuery(document).ready(function($){
	CKBox.initialize({});
	CKBox.assign($('a.modal'), {
		parse: 'rel'
	});

	CKApi.Tooltip('.cktip');
});

function ckProOny() {
	alert('<?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_ONLY_PRO_NO_LINK') ?>');
}
</script>
</body>
</html>