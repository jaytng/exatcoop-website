<?php
defined('_JEXEC') or die;

require_once(JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/defines.js.php');

use Maximenuck\Helper;
use Maximenuck\CKFramework;
use Maximenuck\CKFof;
use Maximenuck\CKText;

CKFramework::load();
CKFramework::loadFaIconsInline();
//Helper::loadCkbox();

CKFof::addStylesheetInline(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.css');
CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.js');

$imagespath = MAXIMENUCK_MEDIA_URI .'/images/';

CKFof::addStylesheet(MAXIMENUCK_MEDIA_URI . '/assets/admin.css');

CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/jscolor/jscolor.js');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/jquery-uick-custom.js');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/admin.js');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/maximenu.js');

$popupclass = ($this->input->get('layout', '', 'string') === 'modal') ? 'ckpopupwizard' : '';
$templateItem = new stdClass();
$templateItem->level = '--level--';
$templateItem->level_diff = 0;
$templateItem->title = '--title--';
$templateItem->desc = '--desc--';
$templateItem->id = '--id--';
$templateItem->deeper = false;
$templateItem->shallower = false;
$templateItem->settings = '--settings--';
$templateItem->type = '--type--';

$editor = 'tinymce';
switch ($editor) {
	case 'jce':
			?><script src="<?php echo MAXIMENUCK_MEDIA_URI ?>/assets/editors/jce.js" type="text/javascript"></script><?php
		break;
	case 'tinymce':
	default:
		if (version_compare(JVERSION, '3.7') >= 0) { // check if we are in Joomla 3.7
			?><script src="<?php echo MAXIMENUCK_MEDIA_URI ?>/assets/editors/tinymce2.js" type="text/javascript"></script><?php
		} else { // we are still in an old version
			?><script src="<?php echo MAXIMENUCK_MEDIA_URI ?>/assets/editors/tinymce1.js" type="text/javascript"></script><?php
		}
		break;
}
// Load the JS strings
//\Joomla\CMS\Language\Text::script('CK_DOWNLOAD');
?>
<div style="display:none;" id="ckeditorwrap">
	<form>
	<?php
	// Load the editor Tinymce or JCE
//	$editor = $conf->get('editor') == 'jce' ? 'jce' : 'tinymce';
	$editor = 'tinymce';
	$editor = \Joomla\CMS\Editor\Editor::getInstance($editor);
	echo $editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
	?>
	</form>
</div>

<div id="ckheader">
	<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
	<div class="ckheadermenu">
		<div class="ckheadertitle">MAXIMENU CK</div>
		<a href="javascript:void(0)" onclick="window.parent.CKBox.close()" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_EXIT') ?></span>
		</a>
		<a href="javascript:void(0);" id="cksave" onclick="ckSaveEdition()" class="ckheadermenuitem cksave" >
			<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?></span>
		</a>
	</div>
</div>
<div id="ckbody" class="ckinterface">
	<label for="name" style="display: inline-block;"><?php echo \Joomla\CMS\Language\Text::_('CK_NAME'); ?></label>
	<input type="text" id="name" name="name" value="d<?php echo $this->item->name; ?>" />
	<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />
	<?php  
//	CKFof::dump($this->item);
//	die;
	
	?>
	<div id="ckmenucreator">
	<?php foreach ($this->item->layouthtml as $item) {
		$settings = $item['settings'];
		$settings = Helper::decodeChars($settings);
		$settings = CKFof::convertObjectToArray(json_decode($settings));
		$item = array_merge($item, $settings);
		Helper::htmlTemplateItem($item);
	}
	?>
	</div>
	
</div>

<div id="ck-items-selection" style="display: none;">
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT_ITEM') ?></div>
	<div id="ck-items-selection-list">
		<div class="ck-item-selection" data-type="menuitem"><i class="fas fa-link"></i>Menu item</div>
		<div class="ck-item-selection" data-type="module"><i class="fas fa-cube"></i>Module</div>
		<div class="ck-item-selection" data-type="image"><i class="fas fa-image"></i>Image</div>
		<div class="ck-item-selection" data-type="heading"><i class="fas fa-heading"></i>Heading</div>
		<?php /*<div class="ck-item-selection" data-type="text"><i class="fas fa-font"></i>Text</div>
		<div class="ck-item-selection" data-type="hikashop">Hikashop ?</div>*/ ?>
		<?php
		// load the custom plugins
//		if (\Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'maximenuck')) {
			// load the custom plugins
//			require_once(JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/ckfof.php');
			Maximenuck\CKFof::importPlugin('maximenuck');
			$sources = Maximenuck\CKFof::triggerEvent('onMaximenuckGetTypeName');
//var_dump($sources);
			if (count($sources)) {
				foreach ($sources as $source) {
					echo '<div class="ck-item-selection" data-type="' . $source . '">' . CKText::_('MAXIMENUCK_' . strtoupper($source) . '_TYPE') . '</div>';
					/*if (! $this->findOption($source)) {
						$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
							'select.option', (string) $source, \Joomla\CMS\Language\Text::alt(trim((string) 'maximenuck_SOURCE_' . strtoupper($source)), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', '0'
						);
						// Add the option object to the result set.
						$this->options[] = $tmp;
					}*/
				}
			}
//		}
		?>
	</div>
</div>
<div id="ck-item-edition" style="display: none;">
</div>
<script type="text/javascript">
(function() {
	var strings = {
		"CK_SAVE": "<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?>"
		,"CK_IMAGE": "<?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE') ?>"
		,"CK_CONFIRM_DELETE": "<?php echo \Joomla\CMS\Language\Text::_('CK_CONFIRM_DELETE') ?>"
	};
	CKApi.Text.load(strings);
})();

//	var CKText = function(text) {
//		return text;
//	}


var cktemplateitem = '<?php Helper::htmlTemplateItem($templateItem) ?>';

jQuery(document).ready(function($){
	CKBox.initialize({});
	CKBox.assign($('a.modal'), {
		parse: 'rel'
	});

	// manage the tabs
//		ckInitTabs();
	ckInitParentItems();
	ckInitItems();
	ckInitSortable();
	CKApi.Tooltip('.cktip');
});


</script>
<style>
* {
	font-family: Segoe ui, Verdana;
	box-sizing: border-box;
}

body.contentpane {
	padding-top: 65px;
}

.ck-title {
	font-size: 20px;
	padding: 10px;
	font-weight: 600;
}

.ck-overlay {
	position: fixed;
	left: 0;
	top: 65px;
	width: 100%;
	height: 100%;
	/*display: none;*/
	background: rgba(255,255,255, 0.7);
	z-index: -1;
}

#ckmenucreator {
	position: relative;
	display: flex;
}

#ckmenucreator > div[data-level="1"] {
	flex: 1 1 auto;
	vertical-align: top;
}

.ck-menu-item {
	position: relative;
}

.ck-menu-item-row {
	padding: 10px;
	border: 1px solid #ccc;
	margin-right: 2px;
	transition: all 0.2s;
	cursor: pointer;
}

.ck-menu-item-move {
	width: 30px;
	height: 38px;
	float: left;
	/*background: rgba(0,0,0, 0.1);*/
	margin-right: 10px;
	line-height: 40px;
	text-align: center;
	cursor: move;
}

.ck-menu-item-action {
	position: absolute;
	right: 0;
	top: 0;
	height: 38px;
	width: 25px;
	text-align: center;
	line-height: 35px;
	cursor: pointer;
}

.ck-menu-item-action:hover {
	color: #2EA2CC;
}

.ckfocus ~ .ck-menu-item-action:hover {
	color: #fff;
}

.ck-menu-item-remove {
	right: 0;
}

.ck-menu-item-style {
	right: 25px;
}

.ck-menu-item-edit {
	right: 50px;
}

.ck-menu-item-remove:hover {
	color: red;
}

#ckmenucreator > div[data-level="1"] > .ck-menu-item-row {
	background: #f5f5f5;
	padding: 10px;
	border: 1px solid #ddd;
	margin-right: 2px;
	color: #000;
	font-weight: 600;
}

#ckmenucreator > div[data-level="1"] > .ck-menu-item-row:hover,
#ckmenucreator > div[data-level="1"] > .ck-menu-item-row.ckfocus {
	background: #2EA2CC;
	border: 1px solid transparent;
	position: relative;
	color: #fff;
}

#ckmenucreator div[data-level="2"] .ck-menu-item-row {
	background: #f5f5f5;
	border: none;
	margin: 2px;
	padding-right: 60px;
}

#ckmenucreator div[data-level="2"] .ck-menu-item-row:hover,
#ckmenucreator div[data-level="2"] .ck-menu-item-row.ckfocus {
	background: #2EA2CC;
	color: #fff;
}

#ckmenucreator div.ck-submenu {
	position: fixed;
	left: 0;
	top: 200px;
	margin-top: 5px;
	margin-bottom: 30px;
	border: 1px solid #ccc;
	width: calc(100% - 10px);
	margin-left: 5px;
	display: none;
	background: #fff;
	z-index: 10;
	min-height: 50px;
}

#ckmenucreator div[data-level="2"] div.ck-submenu {
	position: static;
	width: calc(100% - 40px);
	margin-left: 20px;
	box-shadow: #888 0 0 30px 10px;
}

.ck-submenu-toolbar {
	background: blueviolet;
	display: flex;
	border-radius: 2px;
}

.ck-submenu-toolbar-field {
	position: relative;
}

.ck-submenu-toolbar-edit {
	margin-left: auto;
}

.ck-submenu-toolbar-field-right,
.ck-submenu-toolbar-fullwidth label {
	height: 30px;
	width: 30px;
	text-align: center;
	color: #fff;
	font-size: 15px;
	line-height: 28px;
	transition: background 0.2s;
	cursor: pointer;
	margin-bottom: 0;
}

input[name="submenu-fullwidth"]:checked + i[class*="fa"] {
	color: orange;
}

.ck-submenu-toolbar-field-right:hover {
	background: rgba(0,0,0, 0.1);
}

.ck-submenu-toolbar-field input[type="text"] + i[class*="fa"],
.ck-fields input[type="text"] + i[class*="fa"] {
	position: absolute;
	top: 5px;
	right: 5px;
	font-size: 16px;
}

.ck-submenu-toolbar-field label > input[type="checkbox"] {
	display: none;
}

.ck-submenu-toolbar-field input[type="text"],
.ck-fields input[type="text"] {
	margin: 2px;
	padding-right: 20px;
	width: 80px;
	border-radius: 2px;
	border: none;
	height: 25px;
}

.ck-submenu-pathway {
	height: 30px;
	display: flex;
}

.ck-submenu-pathway-parent:after,
.ck-submenu-pathway-parent:before {
	display: block;
	content: "";
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 15px 0 15px 15px;
	border-color: transparent transparent transparent #666;
	float: right;
	margin-right: -43.4px;
	padding: 0 10px;
	position: relative;
	z-index: 1;
}

.ck-submenu-pathway-parent:before {
	display: block;
	content: "";
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 15px 0 15px 15px;
	border-color: transparent transparent transparent #fff;
	float: right;
	transform: translate(1px, 0);
}

.ck-submenu-pathway-home:after {
	display: block;
	content: "";
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 15px 0 15px 15px;
	border-color: transparent transparent transparent #fff;
	float: right;
	margin-right: -43.4px;
	padding: 0 10px;
	position: relative;
	z-index: 1;
}

.ck-submenu-pathway-home {
	padding: 0 10px;
	line-height: 30px;
}

.ck-submenu-pathway-parent {
	background: #666;
	color: #fff;
	line-height: 25px;
	padding: 0 10px 0 25px;
}

.ck-columns {
	display: flex;
	flex-wrap: wrap;
	border: 1px solid #ddd;
	margin: 2px;
	border-radius: 3px;
}

.ck-column {
	flex: 1 1 auto;
	position: relative;
	min-height: 40px;
}

.ck-column:not(:first-child) {
	border-left: 1px solid #ddd;
}

.ck-column-add-item,
.ck-column-break-remove-item {
	position: absolute;
	left: 50%;
	bottom: -15px;
	margin-left: -15px;
	width: 30px;
	height: 30px;
	border-radius: 20px;
	background: #d5d5d5;
	text-align: center;
	line-height: 28px;
	cursor: pointer;
	z-index: 1;
	display: none;
}

.ck-column-break-remove-item {
	bottom: -7px;
	background: orchid;
}

.ck-column:hover > .ck-column-add-item,
.ck-column-break:hover > .ck-column-break-remove-item {
	background: orange;
	color: #fff;
	display: block;
}

.ck-column-break:hover > .ck-column-break-remove-item {
	background: orchid;
}

.ck-placeholder {
	background: #2EA2CC;
	min-height: 40px;
	min-width: 150px;
	margin: 0;
	padding: 0;
}

.ck-column:hover:after {
	content: "";
	position: absolute;
	top: 0px;
	bottom: 0px;
	left: 0px;
	right: 0px;
	border: 2px solid orange;
	border-radius: 4px;
	pointer-events: none;
}

.ck-column-break:hover {
	border: 2px solid orchid;
}

.ck-column > .editorck > .ck-fields {
	display: flex;
	background: orange;
	height: 30px;
	border-radius: 3px 3px 0 0;
	width: 170px;
	margin: -28px 0 0 10px;
}

.ck-column-break {
	flex-basis: 100%;
	height: 0;
	border-top: 1px solid #ddd;
	height: 20px;
	border-bottom: 1px solid #ddd;
	position: relative;
}

.ck-field {
	width: 30px;
	height: 30px;
	line-height: 30px;
	text-align: center;
	cursor: pointer;
}

.ck-field:hover {
	background: rgba(0,0,0, 0.2);
}

.ck-field-input {
	position: relative;
}

#ck-items-selection-list {
	background: #fff;
	border: 1px solid #e1e1e1;
	border-radius: 5px;
	margin: 10px;
}

.ck-item-selection {
	background: #fff;
	color: #210048;
	font-size: 16px;
	letter-spacing: 1px;
	padding: 10px 20px;
	transition: all 0.2s;
	cursor: pointer;
}

.ck-item-selection:not(:first-child) {
	border-top: 1px solid #e1e1e1;
}

.ck-item-selection:hover {
	color: #000;
	background: #f1f1f1;
}

.ck-item-selection i {
	color: #aaa;
	margin-right: 10px;
}

#ck-item-edition {
	position: relative;
	height: 100%;
}

#ck-item-edition > .fa-spin {
	font-size: 50px;
	display: block;
	margin: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	margin: -35px 0 0 -35px;
}

.ck-menu-item-desc {
	margin: 5px;
	font-style: italic;
	color: #777;
}

.ck-menu-item-img img {
	max-height: 35px;
	margin: -15px 0 -10px 0;
}

#ckmenucreator .ck-menu-item-row:hover .ck-menu-item-desc {
	color: #dedede;
}

.ck-menu-item-row > i {
	color: cadetblue;
}
.ck-menu-item-row:hover > i {
	color: white;
}

.ck-helper-placeholder {
	box-shadow: #888 0 0 10px;
}

</style>