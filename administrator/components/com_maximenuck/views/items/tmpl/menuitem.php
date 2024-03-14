<?php
// no direct access
defined('_JEXEC') or die;

require_once(JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/defines.js.php');

use Maximenuck\CKFramework;
use Maximenuck\CKFof;
use Maximenuck\Helper;

CKFramework::load();
CKFramework::loadFaIconsInline();

$imagespath = MAXIMENUCK_MEDIA_URI .'/images/';
$fieldid = $this->input->get('fieldid', '', 'string');
$returnFunc = $this->input->get('returnFunc', 'ckAppendNewItem', 'cmd');

CKFof::addStylesheet(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.css');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.js');

?>
<h3><?php echo \Joomla\CMS\Language\Text::_('CK_MENU_ITEMS') ?></h3>
<p><?php echo \Joomla\CMS\Language\Text::_('CK_MENU_ITEMS_DESC') ?></p>
<div id="ckfoldertreelist">
<?php
foreach ($this->items as $menu) {
	?>
	<div class="ckfoldertree parent">
		<div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this, 0)" data-menutype="<?php echo $menu->menutype; ?>"></div>
		<div class="ckfoldertreename"><i class="fas fa-folder"></i><?php echo Helper::utf8_encode($menu->title); ?></div>
	</div>
	<?php
}
?>
</div>
<style>
	.ckfoldertreename .fas {
		font-size: 16px;
		margin: 5px;
		vertical-align: sub;
	}
</style>
<script>
var $ck = window.$ck || jQuery.noConflict();
var URIROOT = window.URIROOT || '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>';
var cktoken = '<?php echo \Joomla\CMS\Session\Session::getFormToken() ?>';
//ckMakeTooltip();

function ckToggleTreeSub(btn, parentid) {
	var item = $ck(btn).parent();
	if (item.hasClass('ckopened')) {
		item.removeClass('ckopened');
	} else {
		item.addClass('ckopened');
		// load only the items if not already there
		if (! item.find('.cksubfolder').length) {
			var menutype = $ck(btn).attr('data-menutype');
			ckShowItems(btn, menutype, parentid);
		}
	}
}

function ckShowItems(btn, menutype, parentid) {
	if ($ck(btn).hasClass('empty')) return;
	ckAddWaitIcon(btn);
	var item = $ck(btn).parent();
	// ajax call to code and return items layout
	var myurl = MAXIMENUCK.BASE_URL + "&task=items.load&type=menuitem&func=ajaxShowMenuItems&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			menutype: menutype,
			parentid: parentid,
			returnFunc: '<?php echo $returnFunc ?>'
		}
	}).done(function(code) {
		if (code.trim().length == 0) {
			$ck(btn).css('opacity', 0).addClass('empty');
		} else {
			item.append(code);
			ckInitTooltips();
		}
		ckRemoveWaitIcon(btn);
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckSetMenuItemUrl(url) {
	window.parent.document.getElementById('<?php echo $fieldid ?>').value = url;
	$ck(window.parent.document.getElementById('<?php echo $fieldid ?>')).trigger('change');
	window.parent.CKBox.close('#ckmenusmodal .ckboxmodal-button');
}
</script>