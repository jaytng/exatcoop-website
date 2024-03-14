<?php
defined('_JEXEC') or die;

use Maximenuck\CKText;

require_once(JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/defines.js.php');

use Maximenuck\Helper;
use Maximenuck\CKFramework;

CKFramework::load();
CKFramework::loadFaIconsInline();
Helper::loadCkbox();

$imagespath = MAXIMENUCK_MEDIA_URI .'/images/';
//\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
$doc = \Joomla\CMS\Factory::getDocument();
$doc->addStylesheet(MAXIMENUCK_MEDIA_URI . '/assets/admin.css');
$doc->addScript(MAXIMENUCK_MEDIA_URI . '/assets/admin.js');
?>
<form id="ck-start-page" action="<?php echo MAXIMENUCK_ADMIN_URL ?>&view=menubuilder&layout=edit&id=0" method="post">
	<div id="ckheader">
		<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
		<div class="ckheadermenu">
			<div class="ckheadertitle">Maximenu CK</div>
		</div>
	</div>
	<div class="ck-start-page-title"><?php echo CKText::_('CK_START_WITH'); ?></div>
	<div>
		<div class="ck-start-page-option">
			<input type="radio" name="startwith" id="startwithjoomla" value="joomla" checked />
			<label for="startwithjoomla"><?php echo CKText::_('CK_JOOMLA_MENU'); ?></label>
			<span><select id="joomlamenu" name="joomlamenu">
			<?php
			foreach ($this->joomlamenus as $id => $m) {
				echo '<option value=' . $m->menutype . '>' . $m->title . '</option>';
			}
			?>
			</select>
			</span>
			<div class="ck-start-page-option-desc"><?php echo CKText::_('CK_START_JOOMLA_MENU_DESC'); ?></div>
		</div>
		<div class="ck-start-page-separate ckclr">- <?php echo CKText::_('CK_OR'); ?> -</div>
		<div class="ck-start-page-option">
			<input type="radio" name="startwith" id="startwithblank" value="blank" />
			<label for="startwithblank"><?php echo CKText::_('CK_BLANK_MENU'); ?></label>
			<div class="ck-start-page-option-desc"><?php echo CKText::_('CK_START_BLANK_MENU_DESC'); ?></div>
		</div>
		<div class="ck-start-page-button ckclr">
			<button type="submit"><?php echo CKText::_('CK_OK'); ?></button>
		</div>
	</div>
</form>
<style>
body.contentpane {
	background: #f2f2f2;
	padding: 0;
}

#ckheader, 
#ckheader .ckheadermenu {
	min-width: 0;
}

.ckheadertitle,
.ck-start-page-title,
.ck-start-page-separate {
	text-transform: uppercase;
}

#ck-start-page {
	position: absolute;
	left: 50%;
	top: 50%;
	background: #fff;
	box-shadow: #888 0 0 20px;
	padding: 65px 20px 20px 20px;
	width: 500px;
	min-height: 350px;
	max-width: calc(100% - 20px);
	transform: translate(-50%, -50%);
	box-sizing: border-box;
	font-family: Segoe UI, Verdana;
}

.ck-start-page-title,
.ck-start-page-separate {
	font-size: 20px;
	text-align: center;
	padding: 20px;
}

input[name="startwith"] {
	float: left;
	margin: 5px;
}

.ck-start-page-option {
	margin: 20px;
}

.ck-start-page-option label {
	font-size: 14px;
	vertical-align: top;
	display: inline-block;
}

.ck-start-page-button {
	text-align: center;
}

.ck-start-page-button button {
	text-align: center;
	font-size: 16px;
	color: #666;
	background: #f1f1f1;
	border: 1px solid #ccc;
	padding: 15px 20px;
	transition: all 0.2s;
	text-transform: uppercase;
}

.ck-start-page-button button:hover {
	background: #eee;
	color: #333;
	border: 1px solid #aaa;
}

#joomlamenu {
	width: 250px;
	margin-left: 30px;
}

.ck-start-page-option-desc {
	color: #888;
	font-style: italic;
}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($){
		CKBox.initialize({});
		CKBox.assign($('a.modal'), {
			parse: 'rel'
		});
		CKApi.Tooltip('.cktip');
	});
</script>
