<?php
/**
 * @copyright	Copyright (C) 2011-2020 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
//$tmpitem = reset($items);
//$columnstylesbegin = isset($tmpitem->columnwidth) ? ' style="width:' . $tmpitem->columnwidth . 'px;float:left;"' : '';
$close = '<span class="maxiclose">' . \Joomla\CMS\Language\Text::_('MAXICLOSE') . '</span>';
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$maximenufixedclass = ($params->get('menuposition', '0') == 'bottomfixed') ? ' maximenufixed' : '';
$start = (int) $params->get('startLevel');
$direction = $langdirection == 'rtl' ? 'right' : 'left';
?>
<!-- debut Maximenu CK -->
	<div class="<?php echo $orientation_class . ' ' . $langdirection ?><?php echo $maximenufixedclass ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" style="z-index:<?php echo $params->get('zindexlevel', '10'); ?>;">
			<?php require dirname(__FILE__) . '/_mobile.php'; ?>
			<ul<?php echo $microdata_ul ?> class="<?php echo $params->get('class_sfx'); ?> maximenuck">
				<?php
				include dirname(__FILE__) . '/_logo.php';

				$zindex = 12000;
				$tmpitems = array();
				$tmpitems['sub'] = '';
				$tmpitems['main'] = '';

				foreach ($items as $i => &$item) {
					$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
					$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
					$closeHtml = ($itemlevel > 1) ? '' : ( (($params->get('clickclose', '0') == '1' && ($params->get('behavior', 'mouseover') == 'clickclose' || $params->get('behavior', 'mouseover') == 'click')) || stristr($item->liclass, 'clickclose') != false) ? $close : '' );
					$indexer = $itemlevel == 1 ? 'main' : 'sub';
					$stopdropdown = $params->get('stopdropdownlevel', '0');
					$stopdropdownclass = ($stopdropdown != '0' && $item->level >= $stopdropdown) ? ' nodropdown' : '';
					$createnewrow = (isset($item->createnewrow) AND $item->createnewrow) ? '<div style="clear:both;" class="ck-column-break"></div>' : '';
					$columnstyles = isset($item->columnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->columnwidth) . ';float:left;' . ($item->columnwidth == 'auto' ? 'flex: 1 1 auto;' : '') . '"' : '';
					$nextcolumnstyles = isset($item->nextcolumnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->nextcolumnwidth) . ';float:left;' . ($item->nextcolumnwidth == 'auto' ? 'flex: 1 1 auto;' : '') . '"' : '';

					if (isset($item->colonne) AND (isset($previous) AND !$previous->deeper)) {
						$tmpitems[$indexer] .= '</ul><div class="ckclr"></div></div>' . $createnewrow . '<div class="maximenuck2" ' . $columnstyles . '><ul class="maximenuck2">';
					}
					if (isset($item->content) AND $item->content) {
						$tmpitems[$indexer] .= '<li data-level="' . $itemlevel . '" class="maximenuck maximenuckmodule' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" ' . $item->mobile_data . '>' . $item->content;
						$item->ftitle = '';
					}


					if ($item->ftitle != "") {
						$title = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
						$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
						// manage HTML encapsulation
						$classcoltitle = $item->fparams->get('maximenu_classcoltitle', '') ? ' class="' . $item->fparams->get('maximenu_classcoltitle', '') . '"' : '';
						$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<' . $item->tagcoltitle . $classcoltitle . '>' : '';
						$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</' . $item->tagcoltitle . '>' : '';

						require dirname(__FILE__) . '/_image.php';

						// echo '<li data-level="' . $itemlevel . '" class="maximenuck' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" style="z-index : ' . $zindex . ';" ' . $item->mobile_data . '>';
						$tmpitems[$indexer] .= '<li'. $microdata_li .' data-level="' . $itemlevel . '" class="maximenuck' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" style="z-index : ' . $zindex . ';" ' . $item->mobile_data . '>';
						switch ($item->type) :
							default:
								$tmpitems[$indexer] .= $opentag . '<a' . $microdata_a . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
								break;
							case 'separator':
								$tmpitems[$indexer] .= $opentag . '<span' . $linkrollover . ' class="separator ' . $item->anchor_css . '">' . $linktype . '</span>' . $closetag;
								break;
							case 'heading':
								$tmpitems[$indexer] .= $opentag . '<span' . $linkrollover . ' class="nav-header ' . $item->anchor_css . '">' . $linktype . '</span>' . $closetag;
								break;
							case 'url':
							case 'component':
								switch ($item->browserNav) :
									default:
									case 0:
										$tmpitems[$indexer] .= $opentag . '<a' . $microdata_a . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
									case 1:
										// _blank
										$tmpitems[$indexer] .= $opentag . '<a' . $microdata_a . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" target="_blank" ' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
									case 2:
										// window.open
										$tmpitems[$indexer] .= $opentag . '<a' . $microdata_a . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes\');return false;" ' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
								endswitch;
								break;
						endswitch;
					}

					if ($item->deeper) {
						// set the styles for the submenus container
						if (isset($item->submenuswidth) || $item->leftmargin || $item->topmargin || $item->colbgcolor || isset($item->submenucontainerheight)) {
							$item->styles = "style=\"";
							$item->innerstyles = "style=\"";
							$item->innerstyles .= "width: inherit;";
							if ($item->leftmargin)
								$item->styles .= "margin-".$direction.":" . modMaximenuckHelper::testUnit($item->leftmargin) . ";";
							if ($item->topmargin)
								$item->styles .= "margin-top:" . modMaximenuckHelper::testUnit($item->topmargin) . ";";
							if (isset($item->submenuswidth))
								// $item->innerstyles .= "width:" . modMaximenuckHelper::testUnit($item->submenuswidth) . ";";
							if (isset($item->colbgcolor) && $item->colbgcolor)
								$item->styles .= "background:" . $item->colbgcolor . ";";
							if (isset($item->submenucontainerheight) && $item->submenucontainerheight)
								$item->innerstyles .= "height:" . modMaximenuckHelper::testUnit($item->submenucontainerheight) . ";";
							$item->styles .= "\"";
							$item->innerstyles .= "\"";
						} else {
							$item->styles = "";
							$item->innerstyles = "";
						}
						$itemlevel == 1 ? $tmpitems['main'] .=  "\n\t\t</li>" : '';
						$tmpitems['sub'] .= "\n\t<div class=\"floatck submenuck" . $item->id . "\" " . $item->styles . ">" . $closeHtml . "<div class=\"maxidrop-main\" " . $item->innerstyles . "><div class=\"maximenuck2 first \" " . $nextcolumnstyles . ">\n\t<ul class=\"maximenuck2\">";
						// if (isset($item->coltitle))
						// echo $item->coltitle;
					}
					// The next item is shallower.
					elseif ($item->shallower) {
						$tmpitems['sub'] .= "\n\t</li>";
						$tmpitems['sub'] .=  str_repeat("\n\t</ul>\n\t<div class=\"ckclr\"></div></div>\n\t<div class=\"ckclr\"></div></div></div>\n\t</li>", $item->level_diff-1);
						$tmpitems['sub'] .=  "\n\t</ul>\n\t<div class=\"ckclr\"></div></div>\n\t<div class=\"ckclr\"></div></div></div>";
					}
					// the item is the last.
					elseif ($item->is_end) {
						$tmpitems[$indexer] .=  str_repeat("</li>\n\t</ul>\n\t<div class=\"ckclr\"></div></div><div class=\"ckclr\"></div></div></div>", $item->level_diff);
						$itemlevel == 1 ? $tmpitems['main'] .=  "\n\t\t</li>" : '';
					}
					// The next item is on the same level.
					else {
						//if (!isset($item->colonne))
						$tmpitems[$indexer] .=  "\n\t\t</li>";
					}

					$zindex--;
					$previous = $item;
				}
				echo( $tmpitems['main'] );
				?>
			</ul>
		<div class="maxipushdownck"><?php echo $tmpitems['sub'] ?></div>
    </div>
    <!-- fin maximenuCK -->
