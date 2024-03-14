<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

$close = '<span class="maxiclose">' . \Joomla\CMS\Language\Text::_('MAXICLOSE') . '</span>';
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$maximenufixedclass = ($params->get('menuposition', '0') == 'bottomfixed') ? ' maximenufixed' : '';
$start = (int) $params->get('startLevel');
$direction = $langdirection == 'rtl' ? 'right' : 'left';
$column_width = new stdClass();
?>
<!-- debut Maximenu CK -->
	<div class="<?php echo $orientation_class . ' ' . $langdirection ?><?php echo $maximenufixedclass ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" style="z-index:<?php echo $params->get('zindexlevel', '10'); ?>;">
			<?php require dirname(__FILE__) . '/_mobile.php'; ?>
			<ul<?php echo $microdata_ul ?> class="<?php echo $params->get('class_sfx'); ?> maximenuck">
				<?php
				include dirname(__FILE__) . '/_logo.php';

				$zindex = 12000;
				foreach ($items as $i => &$item) {
					$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
					$ulstyles = (isset($item->submenucontainerheight) && $item->submenucontainerheight) ? "height:" . modMaximenuckHelper::testUnit($item->submenucontainerheight) . ";" : "";
					if ($item->level == 1) $ulstyles .= "position: static !important;";
					// test if need to be dropdown
					//    $stopdropdown = ($item->level > 120) ? '-nodrop' : '';
					$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
					$closeHtml = ($itemlevel > 1) ? '' : ( (($params->get('clickclose', '0') == '1' && ($params->get('behavior', 'mouseover') == 'clickclose' || $params->get('behavior', 'mouseover') == 'click')) || stristr($item->liclass, 'clickclose') != false) ? $close : '' );
					$stopdropdown = $params->get('stopdropdownlevel', '0');
					$stopdropdownclass = ($stopdropdown != '0' && $item->level >= $stopdropdown) ? ' nodropdown' : '';

					$createnewrow = (isset($item->createnewrow) AND $item->createnewrow) ? '<div style="clear:both;" class="ck-column-break"></div>' : '';
					$columnstyles = isset($item->columnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->columnwidth) . ';float:left;"' : '';
					$nextcolumnstyles = isset($item->nextcolumnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->nextcolumnwidth) . ';float:left;"' : '';

					if (isset($item->colonne) AND (isset($previous) AND !$previous->deeper)) {
						echo '</ul><div class="ckclr"></div></div>' . $createnewrow . '<div class="maximenuck2" ' . $columnstyles . '><ul class="maximenuck2" style="' . $ulstyles . '">';
					}
					if (isset($item->content) AND $item->content) {
						echo '<li data-level="' . $itemlevel . '" class="maximenuck maximenuckmodule' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" ' . $item->mobile_data . '>' . $item->content;
						$item->ftitle = '';
					}


					if ($item->ftitle != "") {
						$title = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
						$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
						// manage HTML encapsulation
						$classcoltitle = $item->fparams->get('maximenu_classcoltitle', '') ? ' class="' . $item->fparams->get('maximenu_classcoltitle', '') . '"' : '';
						$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<' . $item->tagcoltitle . $classcoltitle . '>' : '';
						$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</' . $item->tagcoltitle . '>' : '';

						// manage image
						require dirname(__FILE__) . '/_image.php';

						echo '<li'. $microdata_li .' data-level="' . $itemlevel . '" class="maximenuck' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" style="z-index : ' . $zindex . ';" ' . $item->mobile_data . '>';
						require dirname(__FILE__) . '/_itemtype.php';
					}

					if ($item->deeper) {
						// set the styles for the submenus container
						// if (isset($item->submenuswidth) || $item->leftmargin || $item->topmargin || $item->colbgcolor || isset($item->submenucontainerheight)) {
							$item->styles = "style=\"";
							// $item->innerstyles = "style=\"";
							// if ($item->leftmargin)
								$item->styles .= ($item->leftmargin) ? "margin-".$direction.":" . modMaximenuckHelper::testUnit($item->leftmargin) . ";" : "margin:0;";
							if ($item->topmargin)
								$item->styles .= "margin-top:" . modMaximenuckHelper::testUnit($item->topmargin) . ";";
							if (isset($item->submenuswidth))
								$item->styles .= "width:" . modMaximenuckHelper::testUnit($item->submenuswidth) . ";";
							if (isset($item->colbgcolor) && $item->colbgcolor)
								$item->styles .= "background:" . $item->colbgcolor . ";";
							// if (isset($item->submenucontainerheight) && $item->submenucontainerheight)
								// $item->innerstyles .= "height:" . modMaximenuckHelper::testUnit($item->submenucontainerheight) . ";";
							
							if ($item->level > 1) $item->styles .= "top:0;bottom:0;";
							if (isset($previous) && $previous->deeper && $item->level ==2) {
								$item->styles .= "display:block;";
							}
							if ($item->level >= 2) {
								if (isset($item->parent_id) && !isset($column_width->{$item->parent_id})) {
									$column_width->{$item->parent_id} = (isset($item->columnwidth)) ? modMaximenuckHelper::testUnit($item->columnwidth)  : "100%";
								}
								if (isset($item->parent_id) && isset($column_width->{$item->parent_id})) {
									$item->styles .=  "left:" . $column_width->{$item->parent_id} . ";";
								} else {
									$item->styles .=  "left:100%;";
								}
							}
							$item->styles .= "\"";
							// $item->innerstyles .= "\"";
						// } else {
							// $item->styles = "";
							// $item->innerstyles = "";
						// }
						

						echo "\n\t<div class=\"floatck\" " . $item->styles . ">" . $closeHtml . "<div class=\"maxidrop-main\" style=\"width:auto;\"><div class=\"maximenuck2 first \" " . $nextcolumnstyles . ">\n\t<ul class=\"maximenuck2\" style=\"" . $ulstyles . "\">";
						// if (isset($item->coltitle))
						// echo $item->coltitle;
					}
					// The next item is shallower.
					elseif ($item->shallower) {
						echo "\n\t</li>";
						echo str_repeat("\n\t</ul>\n\t</div></div</div>\n\t</li>", $item->level_diff);
					}
					// the item is the last.
					elseif ($item->is_end) {
						echo str_repeat("</li>\n\t</ul>\n\t</div></div></div>", $item->level_diff);
						echo "</li>";
					}
					// The next item is on the same level.
					else {
						//if (!isset($item->colonne))
						echo "\n\t\t</li>";
					}

					$zindex--;
					$previous = $item;
				}
				?>
			</ul>
	</div>
	<!-- fin maximenuCK -->
