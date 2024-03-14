<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKFof;
use Maximenuck\CKFramework;
use Maximenuck\Helper;

require_once(MAXIMENUCK_PATH . '/helpers/defines.js.php');

$imagespath = MAXIMENUCK_MEDIA_URI . '/' . trim(\Joomla\CMS\Component\ComponentHelper::getParams('com_maximenuck')->get('imagespath', 'images'), '/');
CKFramework::load();

CKFof::addStylesheet(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.css');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.js');
$input = \Joomla\CMS\Factory::getApplication()->input;

$returnFunc = $input->get('func', 'ckSelectFile', 'cmd');
$returnField = $input->get('field', '', 'string');
$type = $input->get('type', 'image', 'string');

switch ($type) {
	case 'video' :
		$fileicon = 'file_video.png';
		break;
	case 'audio' :
		$fileicon = 'file_audio.png';
		break;
	case 'folder' :
	case 'image' :
	default :
		$fileicon = 'file_image.png';
		break;
}
?>
<script type="text/javascript">
	var URIROOT = "<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>";
	var URIBASE = "<?php echo \Joomla\CMS\Uri\Uri::base(true); ?>";
	var MAXIMENUCK_MEDIA_URI = '<?php echo MAXIMENUCK_MEDIA_URI ?>';
	var MAXIMENUCK_ADMIN_URL = '<?php echo MAXIMENUCK_ADMIN_URL ?>';
	var MAXIMENUCK_URL = '<?php echo MAXIMENUCK_URL ?>';
	var CKTOKEN = '<?php echo \Joomla\CMS\Factory::getSession()->getFormToken() ?>=1';
</script>
<div id="ckbrowse" class="clearfix">
<div id="ckfolderupload">
	<div class="inner">
		<div class="upload">
			<h2 class="uploadinstructions"><?php echo \Joomla\CMS\Language\Text::_( 'CK_DROP_FILES_TO_UPLOAD' ); ?></h2>
			<p><?php echo \Joomla\CMS\Language\Text::_( 'CK_OR_SELECT_FILES' ); ?></p><input id="ckfileupload" type="file" class="" />
		</div>
	</div>
</div>
<div id="ckfoldertreelist">
<p><?php echo \Joomla\CMS\Language\Text::_('CK_BROWSE_INFOS') ?></p>
<h3><?php echo \Joomla\CMS\Language\Text::_('CK_FOLDERS') ?></h3>
<?php
$lastitem = 0;
foreach ($this->items as $i => $folder) {
	$submenustyle = '';
	$folderclass = '';
	if ($folder->level == 1) {
		$submenustyle = 'display: block;';
		$folderclass = 'ckcurrent';
	}
	$pathway = str_replace('/', '</span><span class="ckfoldertreepath">', Helper::utf8_encode($folder->basepath));
	?>
	<div class="ckfoldertree <?php echo $folderclass ?> <?php echo ($folder->deeper ? 'parent' : '') ?> <?php echo (count($folder->files) ? 'hasfiles' : '') ?>" data-level="<?php echo $folder->level ?>" data-path="<?php echo Helper::utf8_encode($folder->basepath) ?>">
		<?php if ($folder->level > 1) { ?><div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this)"></div><?php } ?>
		<div class="ckfoldertreename" onclick="ckShowFiles(this)"><span class="icon-folder"></span><?php echo Helper::utf8_encode($folder->name); ?>
		<div class="ckfoldertreecount"><?php echo count($folder->files); ?></div>
		</div>
		<div class="ckfoldertreefiles">
			<?php if ($type == 'folder') { ?>
			<div id="ckfoldertreelistfolderselection">
				<div class="ckbutton ckbutton-primary" style="font-size:20px;padding: 10px 20px;" onclick="ckSelectFolder('<?php echo Helper::utf8_encode($folder->basepath) ?>')"><i class="fas fa-check-square"></i> <?php echo \Joomla\CMS\Language\Text::_('CK_SELECT_FOLDER') ?><br /><small><?php echo $pathway ?></small></div>
			</div>
			<?php } ?>
			<div class="ckfoldertreepathway ckinterface">
				<span><?php echo $pathway; ?></span>
				<?php
				if (CKFof::userCan('create', 'com_media')) {
				?>
				<span class="ckfoldertreepathwayactions">
					<span class="ckfoldertreepathwayaddfolder ckbutton" onclick="ckAddFolder()"><?php echo \Joomla\CMS\Language\Text::_('CK_ADD_SUB_FOLDER') ?></span>
					<span class="ckfoldertreepathwayfoldername"><input type="text" class="ckfoldertreepathwayaddfoldername" /></span>
					<span class="ckfoldertreepathwaycreatefolder ckbutton" onclick="ckCreateFolder(this, '<?php echo Helper::utf8_encode($folder->basepath) ?>')"><?php echo \Joomla\CMS\Language\Text::_('CK_CREATE_FOLDER') ?></span>
				</span>
				<?php } ?>
			</div>
		<?php foreach ($folder->files as $j => $file) { 
		?>
			<div class="ckfoldertreefile ckwait" data-type="<?php echo $type ?>" onclick="ckSelectFile(this)" data-path="<?php echo Helper::utf8_encode($folder->basepath) ?>" data-filename="<?php echo Helper::utf8_encode($file) ?>">
				<div class="ckfakeimage" data-src="<?php echo \Joomla\CMS\Uri\Uri::root(true) . '/' . Helper::utf8_encode($folder->basepath) . '/' . Helper::utf8_encode($file) ?>" title="<?php echo Helper::utf8_encode($file); ?>" ></div>
				<div class="ckimagetitle"><?php echo Helper::utf8_encode($file); ?></div>
			</div>
		<?php } ?>
		</div>

	<?php
		if ($folder->deeper)
		{
			echo '<div class="cksubfolder" style="' . $submenustyle . '">';
		}
		elseif ($folder->shallower)
		{
			// The next item is shallower.
			echo '</div>'; // close ckfoldertree
			echo str_repeat('</div></div>', $folder->level_diff); // close cksubfolder + ckfoldertree
		} 
		else
		{
			// The next item is on the same level.
			echo '</div>'; // close ckfoldertree
		}
}

?>
</div>
<div id="ckfoldertreepreview">
	<div class="inner">
		<?php if ($type == 'image') { ?>
		<div id="ckfoldertreepreviewimage">
		</div>
		<?php } ?>
	</div>
</div>

</div>
<script>
var $ck = window.$ck || jQuery.noConflict();
var URIROOT = window.URIROOT || '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>';
var cktoken = '<?php echo \Joomla\CMS\Session\Session::getFormToken() ?>';

function ckToggleTreeSub(btn) {
	var item = $ck(btn).parent();
	if (item.hasClass('ckopened')) {
		item.removeClass('ckopened');
	} else {
		item.addClass('ckopened')
		// item.find('> .cksubfolder, > .ckfoldertreefiles').css('opacity','0').animate({'opacity': '1'}, 300);
	}
}

function ckShowFiles(btn) {
	// show the image in place of divs
	var fakeImages = $ck(btn).find('~ .ckfoldertreefiles .ckfakeimage');
	if (fakeImages.length) {
		fakeImages.each(function() {
			$fakeImage = $ck(this);
			var source = $fakeImage.parent().attr('data-type') == 'image' || $fakeImage.parent().attr('data-type') == 'folder' ? $fakeImage.attr('data-src') : '<?php echo $imagespath . $fileicon ?>';
			$fakeImage.after('<img src="' + source + '" title="' + $fakeImage.attr('title') + '" />');
			$fakeImage.parent().removeClass('ckwait');
			$fakeImage.remove();
		});
	}
	// set the current state on the folder
	var item = $ck(btn).parent();
	$ck('.ckcurrent').not(btn).removeClass('ckcurrent');
	if (item.hasClass('ckcurrent')) {
		item.removeClass('ckcurrent');
	} else {
		item.addClass('ckcurrent')
	}
}

function ckSelectFile(btn) {

	try {
		if (typeof(window.parent.<?php echo $returnFunc ?>) != 'undefined') {
			window.parent.<?php echo $returnFunc ?>($ck(btn).attr('data-path') + '/' + $ck(btn).attr('data-filename'), '<?php echo $returnField ?>');
			if (typeof(window.parent.CKBox) != 'undefined') {
				window.parent.CKBox.close('#ckimagemanager');
			}
		} else {
			alert('ERROR : The function <?php echo $returnFunc ?> is missing in the parent window. Please contact the developer');
		}
	}
	catch(err) {
		alert('ERROR : ' + err.message + '. Please contact the developper.');
	}
}

function ckSelectFolder(path) {
	try {
		if (typeof(window.parent.<?php echo $returnFunc ?>) != 'undefined') {
			window.parent.<?php echo $returnFunc ?>(path, '<?php echo $returnField ?>');
			if (typeof(window.parent.CKBox) != 'undefined') window.parent.CKBox.close();
		} else {
			alert('ERROR : The function <?php echo $returnFunc ?> is missing in the parent window. Please contact the developer');
		}
	}
	catch(err) {
		alert('ERROR : ' + err.message + '. Please contact the developper.');
	}
}

// display the images in the root folder
ckShowFiles($ck('.ckfoldertreename').first()[0]);
</script>
