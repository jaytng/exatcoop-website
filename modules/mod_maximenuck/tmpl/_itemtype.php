<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
// add the togler icon on click
if ($item->deeper && $params->get('behavior', 'mouseover') === 'click' && $params->get('clicktoggler', '0') === '1') {
	$toggler = '<span class="maximenuck-toggler"></span>';
	$linktype .= $toggler;
} else {
	$toggler = '';
}
$imagesalign = ($item->fparams->get('maximenu_images_align', 'moduledefault') != 'moduledefault') ? $item->fparams->get('maximenu_images_align', 'top') : $params->get('menu_images_align', 'top');
$access_key = (isset($item->access_key) && $item->access_key) ? ' accesskey="' . $item->access_key . '"' : '';
switch ($item->type) :
	default:
		echo $opentag . '<a' . $microdata_a . $linkrollover . $access_key . ' ' . $datahover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . ' data-align="' . $imagesalign . '">' . $linktype . '</a>' . $closetag;
		break;
	case 'separator':
		echo $opentag . '<span' . $linkrollover . ' ' . $datahover . ' class="separator ' . $item->anchor_css . '" data-align="' . $imagesalign . '">' . $linktype . '</span>' . $closetag;
		break;
	case 'heading':
		echo $opentag . '<span' . $linkrollover . ' ' . $datahover . ' class="nav-header ' . $item->anchor_css . '" data-align="' . $imagesalign . '">' . $linktype . '</span>' . $closetag;
		break;
	case 'url':
	case 'component':
		if ($item->type == 'url' && $item->flink == '') {
			$item->flink = 'javascript:void(0);';
		}
		switch ($item->browserNav) :
			default:
			case 0:
				echo $opentag . '<a' . $microdata_a . $linkrollover . $access_key . ' ' . $datahover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . ' data-align="' . $imagesalign . '">' . $linktype . '</a>' . $closetag;
				break;
			case 1:
				// _blank
				echo $opentag . '<a' . $microdata_a . $linkrollover . $access_key . ' ' . $datahover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" target="_blank" ' . $title . $item->rel . ' data-align="' . $imagesalign . '">' . $linktype . '</a>' . $closetag;
				break;
			case 2:
				// window.open
				echo $opentag . '<a' . $microdata_a . $linkrollover . $access_key . ' ' . $datahover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes\');return false;" ' . $title . $item->rel . ' data-align="' . $imagesalign . '">' . $linktype . '</a>' . $closetag;
				break;
		endswitch;
		break;
endswitch;