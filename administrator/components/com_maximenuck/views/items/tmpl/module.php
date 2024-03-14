<?php
// no direct access
defined('_JEXEC') or die;


use Maximenuck\CKFramework;
use Maximenuck\CKFof;

CKFramework::load();
CKFramework::loadFaIconsInline();

CKFof::addStylesheet(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.css');
CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/ckbrowse.js');

$returnFunc = $this->input->get('returnFunc', 'ckAppendNewItem', 'cmd');
// BC for 2 vars
$returnFunc = $this->input->get('func', $returnFunc, 'cmd');
$returnField = $this->input->get('field', '', 'string');
?>
<style>
* {
	box-sizing: border-box;
}

.ckinterface .ckbutton, .ckinterface input[type="text"] {
	min-height: 30px;
}
</style>
<div class="ckinterface">
	<h3><?php echo \Joomla\CMS\Language\Text::_('CK_MODULE') ?></h3>
	<div id="search-title" class="filter-parent" style="margin: 10px;">
		<div style="position: relative; height: 46px; display: inline-block;" class="">
			<input type="text" tabindex="1" class="" id="filter-by-title" placeholder="Search by title" onchange="searchby('title')" >
			<span class="ckbutton-group">
				<button class="ckbutton" id="filter-by-title-submit" onclick="searchby('title')"><i class="fas fa-search"></i></button>
				<button class="ckbutton" id="filter-by-title-clear" onclick="clearsearch('title')"><i class="fas fa-times"></i></button>
			</span>
		</div>

		<div style="position: relative; height: 46px; display: inline-block;" class="">
			<input type="text" tabindex="1" class="" id="filter-by-module" placeholder="Search by type" onchange="searchby('module')" style="height:auto;margin:0;background-color: transparent; position: relative;">
			<span class="ckbutton-group">
				<button class="ckbutton" id="filter-by-module-submit" onclick="searchby('module')"><i class="fas fa-search"></i></button>
				<button class="ckbutton" id="filter-by-module-clear" onclick="clearsearch('module')"><i class="fas fa-times"></i></button>
			</span>
		</div>

		<div style="position: relative; height: 46px; display: inline-block;" class="">
			<input type="text" tabindex="1" class="" id="filter-by-position" placeholder="Search by position" onchange="searchby('position')" style="height:auto;margin:0;background-color: transparent; position: relative;">
			<span class="ckbutton-group">
				<button class="ckbutton" id="filter-by-position-submit" onclick="searchby('position')"><i class="fas fa-search"></i></button>
				<button class="ckbutton" id="filter-by-position-clear" onclick="clearsearch('position')"><i class="fas fa-times"></i></button>
			</span>
		</div>
	</div>
<script>
function searchby(type) {
	if (jQuery('#filter-by-'+type).val() == '') return;
	jQuery('.modulerow:not([data-'+type+'*=' + jQuery('#filter-by-'+type).val().toLowerCase() + '])').addClass('filteredck').hide();
	if (jQuery('.filteredck').length) {
		jQuery('.modulerow[data-'+type+'*=' + jQuery('#filter-by-'+type).val().toLowerCase() + ']:not(.filteredck)').show();
	} else {
		jQuery('.modulerow[data-'+type+'*=' + jQuery('#filter-by-'+type).val().toLowerCase() + ']').addClass('filteredck').show();
	}
}

function clearsearch(type) {
	jQuery('.modulerow').removeClass('filteredck').show();
	jQuery('#filter-by-' + type).val('');
	if (jQuery('#filter-by-title').val()) searchby('title');
	if (jQuery('#filter-by-module').val()) searchby('module');
	if (jQuery('#filter-by-position').val()) searchby('position');
	
}

jQuery(document).ready(function() {
	jQuery('.modulerow').click(function(e) {
		e.preventDefault();
		window.parent.<?php echo $returnFunc ?>('module', jQuery(this).attr('data-id'), jQuery(this).attr('data-title-real'), '<?php echo $returnField ?>');
//		window.parent.jModalClose();
	});
});
</script>
<table class="table table-striped table-hover">
<thead>
	<tr>
		<th class="" style="width:20px;"><?php echo \Joomla\CMS\Language\Text::_('CK_ID') ?></th>
		<th class="" style="min-width:200px;text-align:left;"><?php echo \Joomla\CMS\Language\Text::_('CK_TITLE') ?></th>
		<th class="" style="min-width:200px;"><?php echo \Joomla\CMS\Language\Text::_('CK_TYPE') ?></th>
		<th class="" style="min-width:200px;"><?php echo \Joomla\CMS\Language\Text::_('CK_POSITION') ?></th>
	</tr>
</thead>
<?php foreach($this->items as $module) { ?>
	<tr class="modulerow" style="cursor:pointer;" data-id="<?php echo strtolower($module->id) ?>" data-title="<?php echo strtolower($module->title) ?>" data-title-real="<?php echo ($module->title) ?>" data-module="<?php echo strtolower($module->module) ?>" data-position="<?php echo strtolower($module->position) ?>">
		<td class="" style="width:20px;"><?php echo $module->id ?></td>
		<td class="" style="min-width:200px;text-align:left;color:#3071a9;"><?php echo $module->title ?></td>
		<td class="" style="min-width:200px;"><?php echo $module->module ?></td>
		<td class="" style="min-width:200px;"><?php echo $module->position ?></td>
	</tr>
<?php } ?>
<table>
</div>

