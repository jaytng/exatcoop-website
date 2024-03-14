<?php
/**
 * @copyright	Copyright (C) 2011-2020 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */

// no direct access
defined('_JEXEC') or die('Restricted access');
// $close = '<span class="maxiclose">' . \Joomla\CMS\Language\Text::_('MAXICLOSE') . '</span>';
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$direction = $langdirection == 'rtl' ? 'right' : 'left';
$start = (int) $params->get('startLevel');
?>
<div class="<?php echo $orientation_class . ' ' . $langdirection ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" >
<?php require dirname(__FILE__) . '/_mobile.php'; ?>
<ul<?php echo $microdata_ul ?> class="menu<?php echo $params->get('class_sfx'); ?> maximenuck"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
include dirname(__FILE__) . '/_logo.php';
$zindex = 12000;

foreach ($items as $i => &$item) :
	$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
	$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
	// load a module
	if (isset($item->content) AND $item->content) {
		echo '<li data-level="' . $itemlevel . '" class="maximenuck maximenuckmodule' . $item->classe . ' level' . $item->level .' '.$item->liclass . '" ' . $item->mobile_data . '>' . $item->content;
		$item->ftitle = '';
	}
	if ($item->ftitle != "") {
		$title = $item->anchor_title ? ' title="'.$item->anchor_title.'"' : '';
		$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
		// manage HTML encapsulation
		$item->tagcoltitle = $item->fparams->get('maximenu_tagcoltitle', 'none');
		$classcoltitle = $item->fparams->get('maximenu_classcoltitle', '') ? ' class="'.$item->fparams->get('maximenu_classcoltitle', '').'"' : '';
		$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<'.$item->tagcoltitle.$classcoltitle.'>' : '';
		$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</'.$item->tagcoltitle.'>' : '';

		// manage image
		require dirname(__FILE__) . '/_image.php';

		if ($params->get('imageonly', '0') == '1')
			$item->ftitle = '';
		echo '<li'. $microdata_li .' data-level="' . $itemlevel . '" class="maximenuck ' . $item->classe . ' level' . $item->level .' '.$item->liclass . '" style="z-index : ' . $zindex . ';" ' . $item->mobile_data . '>';
		require dirname(__FILE__) . '/_itemtype.php';
	}

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul>';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul></div>
