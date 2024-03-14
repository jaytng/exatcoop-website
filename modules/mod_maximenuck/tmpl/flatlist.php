<?php
/**
 * @copyright	Copyright (C) 2011-2020 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
$tmpitem = reset($items);
$columnstylesbegin = isset($tmpitem->columnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($tmpitem->columnwidth) . ';float:left;"' : '';
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$start = (int) $params->get('startLevel');
$direction = $langdirection == 'rtl' ? 'right' : 'left';
?>
<!-- debut maximenu CK -->
<div class="<?php echo $orientation_class . ' ' . $langdirection ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" >
        <div class="maximenuck2"<?php echo $columnstylesbegin; ?>>
            <ul class="maximenuck2 <?php echo $params->get('class_sfx'); ?>">
<?php
$zindex = 12000;
$lastitem = '';
foreach ($items as $i => &$item) {
	$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
	$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
	if ($params->get('calledfromlevel')) {
		$itemlevel = $itemlevel + $params->get('calledfromlevel') - 1;
	}
	$createnewrow = (isset($item->createnewrow) AND $item->createnewrow) ? '<div style="clear:both;" class="ck-column-break"></div>' : '';
	$columnstyles = isset($item->columnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->columnwidth) . ';float:left;' . ($item->columnwidth == 'auto' ? 'flex: 1 1 auto;' : '') . '"' : '';
	 if (isset($item->colonne) AND (isset($items[$lastitem]) AND !$items[$lastitem]->deeper)) {
        echo '</ul><div class="ckclr"></div></div>'.$createnewrow.'<div class="maximenuck2" ' . $columnstyles . '><ul class="maximenuck2">';
     }
    if (isset($item->content) AND $item->content) {
        echo '<li class="maximenuck maximenuflatlistck '. $item->classe . ' level' . $itemlevel .' '.$item->liclass . '" data-level="' . $itemlevel . '" ' . $item->mobile_data . '>' . $item->content;
		$item->ftitle = '';
    }


    if ($item->ftitle != "") {
		$title = $item->anchor_title ? ' title="'.$item->anchor_title.'"' : '';
		$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
		// manage HTML encapsulation
		// $item->tagcoltitle = $item->fparams->get('maximenu_tagcoltitle', 'none');
		$classcoltitle = $item->fparams->get('maximenu_classcoltitle', '') ? ' class="'.$item->fparams->get('maximenu_classcoltitle', '').'"' : '';
		// if ($item->tagcoltitle != 'none') {
			// $item->ftitle = '<'.$item->tagcoltitle.$classcoltitle.'>'.$item->ftitle.'</'.$item->tagcoltitle.'>';
		// }
		$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<'.$item->tagcoltitle.$classcoltitle.'>' : '';
		$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</'.$item->tagcoltitle.'>' : '';

		// manage image
		require dirname(__FILE__) . '/_image.php';

		if ($params->get('imageonly', '0') == '1')
			$item->ftitle = '';
		echo '<li class="maximenuck maximenuflatlistck '. $item->classe . ' level' . $itemlevel .' '.$item->liclass . '" style="z-index : ' . $zindex . ';" data-level="' . $itemlevel . '" ' . $item->mobile_data . '>';
		require dirname(__FILE__) . '/_itemtype.php';
	}

			echo "\n\t\t</li>\n";

	$zindex--;
	$lastitem = $i;
}
?>
			</ul>
			<div style="clear:both;"></div>
		</div>
	</div>
    <!-- fin maximenuCK -->
