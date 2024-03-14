<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKFof;
use Maximenuck\CKFramework;

CKFramework::load();
CKFramework::loadFaIconsInline();

$returnFunc = $this->input->get('func', 'ckSelectFaIcon', 'cmd');

$list = file_get_contents(MAXIMENUCK_MEDIA_PATH . '/assets/faiconsv5.json');
$list = json_decode($list);
$qty = count($list);
?>
<style>
.container {
	color: #333;
}

.fontawesome-icon-list {
	display: flex;
	flex-wrap: wrap;
}

.fontawesome-icon-list a {
	color: #000;
}

div.fa-hover {
	float: left;
	height: 30px;
	margin: 10px;
	padding: 10px;
	width: 14%;
	text-align: center;
}

div.fa-hover:hover a {
	transform: scale(2);
	background: #fff;
	z-index: 1;
	display: block;
}

div.fa-hover a {
	text-decoration: none;
}

.faicon5 {
	text-align: center;
	width: 100px;
	margin: 10px 0;
	padding: 10px;
	display: inline-block;
	vertical-align: top;
	color: rgb(73, 80, 87);
}

.faicon5:hover {
	transform: scale(1.3);
	background: #fff;
	z-index: 1;
	box-shadow: #888 0 0 20px;
	cursor: pointer;
}
.faicon5 i {
	font-size: 38px;
}
</style>

<div class="ckinterface">
	<div id="search" class="filter-parent">
		<label for="filter-by"></label>
		<div>
			<input type="text" tabindex="1" class="" id="filter-by" placeholder="Search <?php echo $qty ?> icons" style="box-sizing: border-box;height: 28px;">
			<span class="ckbutton-group">
				<button class="ckbutton" id="filter-submit" onclick="ckSearchIcons()"><i class="fa fa-search"></i></button>
				<button class="ckbutton" id="filter-clear" onclick="ckClearSearchIcons()"><i class="fa fa-times"></i></button>
			</span>
		</div>
	</div>
	<div>
		<h3><?php echo \Joomla\CMS\Language\Text::_('CK_FILTER_BY_GROUP') ?></h3>
		<button class="ckbutton filter-category" onclick="ckFilterIconsByCat('fas')">Solid</button>
		<button class="ckbutton filter-category" onclick="ckFilterIconsByCat('far')">Regular</button>
		<button class="ckbutton filter-category" onclick="ckFilterIconsByCat('fab')">Brands</button>
	</div>
	<script>
	function ckSearchIcons() {
		jQuery('.faicon5').hide();
		jQuery('i[class*=' + jQuery('#filter-by').val() + ']').parent().show();
	}

	function ckClearSearchIcons() {
		jQuery('section').show();
		jQuery('.faicon5').show();
		jQuery('#filter-by').val('');
	}

	function ckFilterIconsByCat(cat) {
		jQuery('.faicon5').hide();
		jQuery('.' + cat).parent().show();
	}

	jQuery(document).ready(function() {
		jQuery('.faicon5').click(function(e) {
			e.preventDefault();
			window.parent.<?php echo $returnFunc ?>(jQuery(this).find('i').attr('class'), '<?php echo $this->input->get('field') ?>');
			window.parent.CKBox.close('#ckiconmanager');
		});
	});
	</script>
	<?php

	foreach ($list as $i => $icon) {
		$name = str_replace('"', '', $icon);
		$name = trim(str_replace('fas', '', $name));
		$name = trim(str_replace('far', '', $name));
		$name = trim(str_replace('fab', '', $name));
		$name = trim(str_replace('fal', '', $name));
		?>
		<div class="faicon5">
		<i class="<?php echo $icon ?>"></i>
		<div class="faicon5-title"><?php echo $name ?></div>
		</div>
		<?php
	}
	?>
</div>