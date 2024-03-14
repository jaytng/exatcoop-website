<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access

defined('_JEXEC') or die('Restricted access');

$linkrollover = '';
$itemicon = '';
// manage icon
if ($item->fparams->get('maximenu_icon', '') && $params->get('useicons', '1') !== '0') {
	$loadfontawesome = true;
	$icon = $item->fparams->get('maximenu_icon', '');
	if ($params->get('fontawesomeversion', '5') == '4') {
		$search = array('far', 'fas', 'fab');
		$replace = array('fa', 'fa', 'fa');
		$icon = str_replace($search, $replace, $icon);
	}
	$itemicon = '<span class="maximenuiconck ' . $icon . '"></span>';
}
$datahover = $params->get('datahover', '1') == '1' ? ' data-hover="' . htmlspecialchars($item->ftitle, ENT_COMPAT, 'UTF-8', false) . '"' : '';
$texthtml = $itemicon . '<span class="titreck-text"><span class="titreck-title">' . $item->ftitle . '</span>' . $description . '</span>';
$texthtml = Maximenuck\Helper::decodeCharsAfterJson($texthtml);

// manage image
if ($item->menu_image && $params->get('useimages', '1') !== '0') {
	// manage image rollover
	$imagePaths = parse_url($item->menu_image);
	$imagePath = isset($imagePaths['path']) ? $imagePaths['path'] : $item->menu_image;
	$menu_image_split = explode('.', $imagePath);

	if (isset($menu_image_split[1])) {
		// manage active image
		if (isset($item->active) AND $item->active) {
			$menu_image_active = $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . '.' . $menu_image_split[1];
			if (file_exists(JPATH_ROOT . '/' . $menu_image_active)) {
				$item->menu_image = $menu_image_active;
			}
		}
		// manage hover image
		$menu_image_hover = $menu_image_split[0] . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1];
		if (isset($item->active) AND $item->active AND file_exists(JPATH_ROOT . '/' . $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1])) {
			$linkrollover = ' onmouseover="javascript:this.querySelector(\'img\').src=\'' . \Joomla\CMS\Uri\Uri::base(true) . '/' . $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1] . '\'" onmouseout="javascript:this.querySelector(\'img\').src=\'' . \Joomla\CMS\Uri\Uri::base(true) . '/' . $item->menu_image . '\'"';
		} else if (file_exists(JPATH_ROOT . '/' . $menu_image_hover)) {
			$linkrollover = ' onmouseover="javascript:this.querySelector(\'img\').src=\'' . \Joomla\CMS\Uri\Uri::base(true) . '/' . $menu_image_hover . '\'" onmouseout="javascript:this.querySelector(\'img\').src=\'' . \Joomla\CMS\Uri\Uri::base(true) . '/' . $item->menu_image . '\'"';
		}
	}

	$image_dimensions = ( $item->fparams->get('maximenuparams_imgwidth', '') != '' && ($item->fparams->get('maximenuparams_imgheight', '') != '') ) ? ' width="' . $item->fparams->get('maximenuparams_imgwidth', '') . '" height="' . $item->fparams->get('maximenuparams_imgheight', '') . '"' : '';
	$imagealt = addslashes($item->fparams->get('maximenuparams_imagealt', '')) ? $item->fparams->get('maximenuparams_imagealt', '') : $item->ftitle;
	$imagealt = Maximenuck\Helper::decodeCharsAfterJson($imagealt);
	$imagesalign = ($item->fparams->get('maximenu_images_align', 'moduledefault') != 'moduledefault') ? $item->fparams->get('maximenu_images_align', 'top') : $params->get('menu_images_align', 'top');

	if ($item->fparams->get('menu_text', 1) AND !$params->get('imageonly', '0')) {
		switch ($imagesalign) :
			default:
			case 'default':
				$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="left"' . $image_dimensions . '/><span class="titreck" ' . $datahover . '>' . $texthtml . '</span> ';
				break;
			case 'bottom':
				$linktype = '<span class="titreck" ' . $datahover . '>' . $texthtml . '</span><img src="' . $item->menu_image . '" alt="' . $imagealt . '" style="display: block; margin: 0 auto;"' . $image_dimensions . ' /> ';
				break;
			case 'top':
				$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '" style="display: block; margin: 0 auto;"' . $image_dimensions . ' /><span class="titreck" ' . $datahover . '>' . $texthtml . '</span> ';
				break;
			case 'rightbottom':
				$linktype = '<span class="titreck" ' . $datahover . '>' . $texthtml . '</span><img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="top"' . $image_dimensions . '/> ';
				break;
			case 'rightmiddle':
				$linktype = '<span class="titreck" ' . $datahover . '>' . $texthtml . '</span><img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="middle"' . $image_dimensions . '/> ';
				break;
			case 'righttop':
				$linktype = '<span class="titreck" ' . $datahover . '>' . $texthtml . '</span><img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="bottom"' . $image_dimensions . '/> ';
				break;
			case 'leftbottom':
				$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="top"' . $image_dimensions . '/><span class="titreck" ' . $datahover . '>' . $texthtml . '</span> ';
				break;
			case 'leftmiddle':
				$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="middle"' . $image_dimensions . '/><span class="titreck" ' . $datahover . '>' . $texthtml . '</span> ';
				break;
			case 'lefttop':
				$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '" align="bottom"' . $image_dimensions . '/><span class="titreck" ' . $datahover . '>' . $texthtml . '</span> ';
				break;
		endswitch;
	} else {
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $imagealt . '"' . $image_dimensions . '/>';
	}
} else {
	$linktype = '<span class="titreck" ' . $datahover . '>' . $texthtml . '</span>';
}


// add the togler icon on click
if ($item->deeper && $params->get('behavior', 'mouseover') === 'click' && $params->get('clicktoggler', '0') === '1') {
	$item->classe .= ' has-maximenuck-toggler';
}
