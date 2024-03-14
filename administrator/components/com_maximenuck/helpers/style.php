<?php
Namespace Maximenuck;

// No direct access
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/defines.php';

use Maximenuck\CKInput;
use Maximenuck\CKFof;
use Maximenuck\CKText;

/**
 * Helper Class.
 */
class Style {

	static $cssreplacements;

	static function createModuleCss($params, $menuID) {
		$document = \Joomla\CMS\Factory::getDocument();

		// set the prefixes for all xml fieldset
		$prefixes = array('menustyles',
			'level1itemnormalstyles',
			'level1itemdescstyles',
			'level1itemhoverstyles',
			'level1itemactivestyles',
			'level1itemactivehoverstyles',
			'level1itemparentstyles',
			'level2menustyles',
			'level2itemnormalstyles',
			'level2itemhoverstyles',
			'level2itemactivestyles',
			'level2itemactivehoverstyles',
			'level2itemparentstyles',
			'level1itemnormalstylesicon',
			'level1itemhoverstylesicon',
			'level2itemnormalstylesicon',
			'level2itemhoverstylesicon',
			'level3menustyles',
			'level3itemnormalstyles',
			'level3itemhoverstyles',
			'fancystyles',
			'headingstyles');

		$css = new \stdClass();
		$csstoinject = '';
		$important = false;
		$fields = Array();

		// create the css rules for each prefix
		foreach ($prefixes as $prefix) {
			$param = $params->get($prefix, '[]');
			$objs = json_decode(str_replace("|qq|", "\"", $param));
			$fields[$prefix] = new CkCssParams();

			if (!$objs)
				continue;

			foreach ($objs as $obj) {
				$fieldid = str_replace($prefix . "_", "", $obj->id);
				$fields[$prefix]->$fieldid = isset($obj->value) ? $obj->value : null;
			}

			if ($prefix == 'headingstyles') {
				$important = true;
			}
			$css->$prefix = self::createCss($menuID, $fields[$prefix], $prefix, $important, '');
		}

		$csstoinject = '';

		// get the css suffix for the module
		$menu_class = ( $params->get('orientation', 'horizontal') === 'horizontal' ) ? '.maximenuckh' : '.maximenuckv';

		switch (trim($params->get('layout', 'default'), '_:')) {
			case 'flatlist':
				$menu_begin = ' ul.maximenuck2';
				break;
			case 'nativejoomla':
				$menu_begin = ' ul';
				break;
			case 'dropselect':
				$menu_begin = '';
				$menu_class = '';
				break;
			default:
			case 'default':
				$menu_begin = ' ul.maximenuck';
				break;
		}

		// set the specific menu ID to give more weight to the css rule
		$menuCSSID = $menuID . $menu_class . $menu_begin;
		$topfixedmenuCSSID = $menuID . '.maximenufixed' . $menu_class . $menu_begin;
		$level1 = $params->get('calledfromlevel','') ? 'level' . (string)$params->get('calledfromlevel') : 'level1';
		$level2 = $params->get('calledfromlevel','') ? 'level' . (string)($params->get('calledfromlevel') + 1) : 'level2';

		// load the google font
		$gfont = $fields['menustyles']->get('menustylestextgfont', '');
		$isGfont = $fields['menustyles']->get('menustylestextisgfont', '1');

		if ($gfont) {
			$gfontfamily = self::get_gfontfamily($gfont);
			if ($isGfont) $document->addStylesheet('https://fonts.googleapis.com/css?family=' . $gfont);
			$csstoinject .= "div#" . $menuID . " li > a, div#" . $menuID . " li > span { font-family: '" . $gfontfamily . "';}";
		}
		$gfont = $fields['level2menustyles']->get('level2menustylestextgfont', '');
		$isGfont = $fields['level2menustyles']->get('level2menustylestextisgfont', '1');
		if ($gfont) {
			$gfontfamily = self::get_gfontfamily($gfont);
			if ($isGfont) $document->addStylesheet('https://fonts.googleapis.com/css?family=' . $gfont);
			$csstoinject .= "div#" . $menuID . " ul.maximenuck2 li > a, div#" . $menuID . " ul.maximenuck2 li > span { font-family: '" . $gfontfamily . "';}";
		}

		// set the styles for the global menu
		$submenuwidth = $fields['menustyles']->get('menustylessubmenuwidth', '');
		$submenuheight = $fields['menustyles']->get('menustylessubmenuheight', '');
		$submenu1marginleft = $fields['menustyles']->get('menustylessubmenu1marginleft', '');
		$submenu1margintop = $fields['menustyles']->get('menustylessubmenu1margintop', '');
		$submenu2marginleft = $fields['menustyles']->get('menustylessubmenu2marginleft', '');
		$submenu2margintop = $fields['menustyles']->get('menustylessubmenu2margintop', '');
		
		if ($submenuwidth)
			$csstoinject .= "\ndiv#" . $menuCSSID . " div.maxidrop-main, div#" . $menuCSSID . " li div.maxidrop-main { width: " . Helper::testUnit($submenuwidth) . "; } ";
		if ($submenuheight)
			$csstoinject .= "\ndiv#" . $menuCSSID . " div.maxidrop-main, div#" . $menuCSSID . " li.maximenuck div.maxidrop-main { height: " . Helper::testUnit($submenuheight) . "; } ";
		if ($submenu1marginleft)
			$csstoinject .= "\ndiv#" . $menuCSSID . " div.floatck, div#" . $menuCSSID . " li.maximenuck div.floatck { margin-left: " . Helper::testUnit($submenu1marginleft) . "; } ";
		if ($submenu1margintop)
			$csstoinject .= "\ndiv#" . $menuCSSID . " div.floatck, div#" . $menuCSSID . " li.maximenuck div.floatck { margin-top: " . Helper::testUnit($submenu1margintop) . "; } ";
		if ($submenu2marginleft)
			$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck div.floatck div.floatck { margin-left: " . Helper::testUnit($submenu2marginleft) . "; } ";
		if ($submenu2margintop)
			$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck div.floatck div.floatck { margin-top: " . Helper::testUnit($submenu2margintop) . "; } ";

		$level1itemnormalstylesparentarrowcolor = $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowcolor', '') ? $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowcolor', '') : $fields['level1itemnormalstyles']->get('level1itemnormalstylesfontcolor', '');
		if ($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') != 'none'
				&& $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') != 'image'
				&& ($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') == 'triangle' || $level1itemnormalstylesparentarrowcolor)
						){
			// for parent arrow normal state
//			if ($level1itemnormalstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent > span.separator:after, div#" . $menuCSSID . " li.maximenuck.level1.parent > .maximenuck-toggler:after { " 
					. ($level1itemnormalstylesparentarrowcolor ? ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemnormalstylesparentarrowcolor . ";" : "border-top-color: " . $level1itemnormalstylesparentarrowcolor . ";" )
					. "color: " . $level1itemnormalstylesparentarrowcolor . ";" : "")
						. "display:block;"
						. "position:absolute;"
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmargintop', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginright', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginbottom', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginleft', '')) . ";" : "")
						. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositiontop', '')) . ";" : "")
						. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionright', '')) . ";" : "")
						. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionbottom', '')) . ";" : "")
						. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionleft', '')) . ";" : "")
					. "} ";
//			}

			$level1itemhoverstylesparentarrowcolor = $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentarrowcolor', '') ? $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentarrowcolor', '') : $fields['level1itemhoverstyles']->get('level1itemhoverstylesfontcolor', '');
			// for parent arrow hover state
			if ($level1itemhoverstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent:hover > span.separator:after, div#" . $menuCSSID . " li.maximenuck.level1.parent:hover > .maximenuck-toggler:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemhoverstylesparentarrowcolor . ";" : "border-top-color: " . $level1itemhoverstylesparentarrowcolor . ";" )
					. "color: " . $level1itemhoverstylesparentarrowcolor . ";"
					. "} ";
			}
			$level1itemactivestylesparentarrowcolor = $fields['level1itemactivestyles']->get('level1itemactivestylesparentarrowcolor', '') ? $fields['level1itemactivestyles']->get('level1itemactivestylesparentarrowcolor', '') : '';
			// for parent active state
			if ($level1itemactivestylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent.active > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent.active > span.separator:after, div#" . $menuCSSID . " li.maximenuck.level1.parent.active > .maximenuck-toggler:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemactivestylesparentarrowcolor . ";" : "border-top-color: " . $level1itemactivestylesparentarrowcolor . ";" )
					. "color: " . $level1itemactivestylesparentarrowcolor . ";"
					. "} ";
			}
			$level1itemactivehoverstylesparentarrowcolor = $fields['level1itemactivehoverstyles']->get('level1itemactivehoverstylesparentarrowcolor', '') ? $fields['level1itemactivehoverstyles']->get('level1itemactivehoverstylesparentarrowcolor', '') : '';
			// for parent arrow active hover state
			if ($level1itemactivehoverstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent.active:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent.active:hover > span.separator:after, div#" . $menuCSSID . " li.maximenuck.level1.parent.active:hover > .maximenuck-toggler:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemactivehoverstylesparentarrowcolor . ";" : "border-top-color: " . $level1itemactivehoverstylesparentarrowcolor . ";" )
					. "color: " . $level1itemactivehoverstylesparentarrowcolor . ";"
					. "} ";
			}
		} else if ($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') == 'image') {
			// for parent arrow normal state
			$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent > span.separator:after { " 
					// . ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemnormalstylesparentarrowcolor . ";" : "border-top-color: " . $level1itemnormalstylesparentarrowcolor . ";" )
					. "border: none;"
					. "display:block;"
					. "position:absolute;"
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimage', '') . ");" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagepositionx', '') != '' && $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagepositiony', '') != '') ? "background-position: " . $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagepositionx', '') . " " . $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagepositiony', '') . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentitemimagerepeat', '') . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowwidth', '') != '') ? "width: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowwidth', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowheight', '') != '') ? "height: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowheight', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmargintop', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginright', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginbottom', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowmarginleft', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositiontop', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionright', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionbottom', '')) . ";" : "")
					. (($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowpositionleft', '')) . ";" : "")
					. "} ";
			// for parent arrow hover state
			if ($fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimage', '')) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent:hover > span.separator:after { " 
//					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level1itemhoverstylesparentarrowcolor . ";" : "border-top-color: " . $level1itemhoverstylesparentarrowcolor . ";" )
					. (($fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimage', '') . ");" : "")
					. (($fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagepositionx', '') != '' && $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagepositiony', '') != '') ? "background-position: " . $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagepositionx', '') . " " . $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagepositiony', '') . ";" : "")
					. (($fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $fields['level1itemhoverstyles']->get('level1itemhoverstylesparentitemimagerepeat', '') . ";" : "")
					. "} ";
			}
		} else if ($fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') == 'none') {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1.parent > span.separator:after { " 
					. "display: none;"
					. "}";
		}

		$level2itemnormalstylesparentarrowcolor = $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowcolor', '') ? $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowcolor', '') : $fields['level2itemnormalstyles']->get('level2itemnormalstylesfontcolor', '');
		if ($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowtype', '') != 'none'
				&& $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowtype', '') != 'image'
				&& ($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowtype', '') == 'triangle' || $level2itemnormalstylesparentarrowcolor) 
				) {
			// for parent arrow normal state
//			if ($level2itemnormalstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > span.separator:after,
	div#" . $menuID . " .maxipushdownck li.maximenuck.parent > a:after, div#" . $menuID . " .maxipushdownck li.maximenuck.parent > span.separator:after { " 
					. ($level2itemnormalstylesparentarrowcolor ? "border-left-color: " . $level2itemnormalstylesparentarrowcolor . ";"
					. "color: " . $level2itemnormalstylesparentarrowcolor . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmargintop', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginright', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginbottom', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginleft', '')) . ";" : "")
					. "} ";
//			}

			$level2itemhoverstylesparentarrowcolor = $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentarrowcolor', '') ? $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentarrowcolor', '') : $fields['level2itemhoverstyles']->get('level2itemhoverstylesfontcolor', '');
			// for parent arrow hover state
			if ($level2itemhoverstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent:hover > span.separator:after,
	div#" . $menuID . " .maxipushdownck li.maximenuck.parent:hover > a:after, div#" . $menuID . " .maxipushdownck li.maximenuck.parent:hover > span.separator:after { " 
					. "border-color: transparent transparent transparent " . $level2itemhoverstylesparentarrowcolor . ";"
					. "color: " . $level2itemhoverstylesparentarrowcolor . ";"
					. "} ";
			}
			$level2itemactivestylesparentarrowcolor = $fields['level2itemactivestyles']->get('level2itemactivestylesparentarrowcolor', '') ? $fields['level2itemactivestyles']->get('level2itemactivestylesparentarrowcolor', '') : '';
			// for parent active state
			if ($level2itemactivestylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent.active > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent.active > span.separator:after { " 
					. "border-left-color: " . $level2itemactivestylesparentarrowcolor . ";"
					. "color: " . $level2itemactivestylesparentarrowcolor . ";"
					. "} ";
			}
			$level2itemactivehoverstylesparentarrowcolor = $fields['level2itemactivehoverstyles']->get('level2itemactivehoverstylesparentarrowcolor', '') ? $fields['level2itemactivehoverstyles']->get('level2itemactivehoverstylesparentarrowcolor', '') : '';
			// for parent arrow active hover state
			if ($level2itemactivehoverstylesparentarrowcolor) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent.active:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent.active:hover > span.separator:after { " 
					. "border-left-color: " . $level2itemactivehoverstylesparentarrowcolor . ";"
					. "color: " . $level2itemactivehoverstylesparentarrowcolor . ";"
					. "} ";
			}
		} else if ($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowtype', '') == 'image') {
			// for parent arrow normal state
			$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > span.separator:after,
	div#" . $menuID . " .maxipushdownck li.maximenuck.parent > a:after, div#" . $menuID . " .maxipushdownck li.maximenuck.parent > span.separator:after { " 
					// . ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $level2itemnormalstylesparentarrowcolor . ";" : "border-top-color: " . $level2itemnormalstylesparentarrowcolor . ";" )
					. "border: none;"
					. "display:block;"
					. "position:absolute;"
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimage', '') . ");" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagepositionx', '') != '' && $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagepositiony', '') != '') ? "background-position: " . $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagepositionx', '') . " " . $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagepositiony', '') . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $fields['level2itemnormalstyles']->get('level2itemnormalstylesparentitemimagerepeat', '') . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowwidth', '') != '') ? "width: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowwidth', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowheight', '') != '') ? "height: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowheight', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmargintop', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginright', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginbottom', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowmarginleft', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositiontop', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionright', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionbottom', '')) . ";" : "")
					. (($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowpositionleft', '')) . ";" : "")
					. "} ";
			// for parent arrow hover state
			if ($fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimage', '')) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent:hover > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent:hover > span.separator:after,
	div#" . $menuID . " .maxipushdownck li.maximenuck.parent:hover > a:after, div#" . $menuID . " .maxipushdownck li.maximenuck.parent:hover > span.separator:after { " 
					. (($fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimage', '') . ");" : "")
					. (($fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagepositionx', '') != '' && $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagepositiony', '') != '') ? "background-position: " . $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagepositionx', '') . " " . $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagepositiony', '') . ";" : "")
					. (($fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $fields['level2itemhoverstyles']->get('level2itemhoverstylesparentitemimagerepeat', '') . ";" : "")
					. "} ";
			}
		} else if ($fields['level2itemnormalstyles']->get('level2itemnormalstylesparentarrowtype', '') == 'none') {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > a:after, div#" . $menuCSSID . " li.maximenuck.level1 li.maximenuck.parent > span.separator:after,
	div#" . $menuID . " .maxipushdownck li.maximenuck.parent > a:after, div#" . $menuID . " .maxipushdownck li.maximenuck.parent > span.separator:after { " 
					. "display: none;"
					. "}";
		}

		// for item icon level1
		if (isset($css->level1itemnormalstylesicon)) {
			$level1itemiconwidth = isset($fields['level1itemnormalstylesicon']) && $fields['level1itemnormalstylesicon']->get('level12itemnormalstylesiconfontsize') ? "width:" . Helper::testUnit($fields['level1itemnormalstylesicon']->get('level1itemnormalstylesiconfontsize')) . ";" : "";
			if ($css->level1itemnormalstylesicon['margin'] || $css->level1itemnormalstylesicon['fontsize'] || $css->level1itemnormalstylesicon['line-height'] || $css->level1itemnormalstylesicon['fontcolor']) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.level1 > *:not(div) .maximenuiconck { "
						. "float: left;"
						. $level1itemiconwidth
						. $css->level1itemnormalstylesicon['margin'] . $css->level1itemnormalstylesicon['fontsize'] . $css->level1itemnormalstylesicon['line-height'] . $css->level1itemnormalstylesicon['fontcolor']
						. "}";
			}
		}

		if (isset($css->level1itemhoverstylesicon) && $css->level1itemhoverstylesicon['fontcolor']) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.level1:hover > *:not(div) .maximenuiconck { "
					. $css->level1itemhoverstylesicon['fontcolor']
					. "}";
		}
		
		// for item icon level2
		if (isset($css->level2itemnormalstylesicon)) {
			$level2itemiconwidth = isset($fields['level2itemnormalstylesicon']) && $fields['level2itemnormalstylesicon']->get('level2itemnormalstylesiconfontsize') ? "width:" . Helper::testUnit($fields['level2itemnormalstylesicon']->get('level2itemnormalstylesiconfontsize')) . ";" : "";
			if ($css->level2itemnormalstylesicon['margin'] || $css->level2itemnormalstylesicon['fontsize'] || $css->level2itemnormalstylesicon['line-height'] || $css->level2itemnormalstylesicon['fontcolor']) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.level1 li > *:not(div) .maximenuiconck { "
						. "float: left;"
						. $level2itemiconwidth
						. $css->level2itemnormalstylesicon['margin'] . $css->level2itemnormalstylesicon['fontsize'] . $css->level2itemnormalstylesicon['line-height'] . $css->level2itemnormalstylesicon['fontcolor']
						. "}";
			}
		}
		if (isset($css->level2itemhoverstylesicon) && $css->level2itemhoverstylesicon['fontcolor']) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.level1 li:hover > *:not(div) .maximenuiconck { "
					. $css->level2itemhoverstylesicon['fontcolor']
					. "}";
		}

		// root styles
		if (isset($css->menustyles)) {
			if ($css->menustyles['padding'] || $css->menustyles['margin'] || $css->menustyles['background'] || $css->menustyles['gradient'] || $css->menustyles['borderradius'] || $css->menustyles['shadow'] || $css->menustyles['border'] || $css->menustyles['text-align'] || $css->menustyles['backgroundfilter']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . ", #" . $menuCSSID . " { " . $css->menustyles['padding'] . $css->menustyles['margin'] . $css->menustyles['background'] . $css->menustyles['gradient'] . $css->menustyles['borderradius'] . $css->menustyles['shadow'] . $css->menustyles['border'] . $css->menustyles['text-align'] . $css->menustyles['backgroundfilter'] . " } ";
			}
			if (trim($params->get('layout', 'default'), '_:') === 'dropselect') {
				$csstoinject .= "#" . $menuCSSID . " { height: auto; }";
			}
			if ($css->menustyles['fontcolor'] || $css->menustyles['fontsize'] || $css->menustyles['textshadow']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck > a span.titreck, div#" . $menuCSSID . " li.maximenuck > span.separator span.titreck,
div#" . $menuID . " .maxipushdownck li.maximenuck > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck > span.separator span.titreck, select#" . $menuCSSID . " { " . $css->menustyles['fontcolor'] . $css->menustyles['fontsize'] . $css->menustyles['textshadow'] . " } ";
			}
			if ($css->menustyles['descfontcolor'] || $css->menustyles['descfontsize']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck > a span.descck, div#" . $menuCSSID . " li.maximenuck > span.separator span.descck,
div#" . $menuID . " .maxipushdownck li.maximenuck > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck > span.separator span.descck { " . $css->menustyles['descfontcolor'] . $css->menustyles['descfontsize'] . " } ";
			}
			// top fixed effect
			if ($css->menustyles['topfixedbackground'] || $css->menustyles['topfixedgradient'] || $css->menustyles['topfixedbackgroundfilter']
			) {
				$csstoinject .= "\ndiv#" . $topfixedmenuCSSID . ", #" . $topfixedmenuCSSID . " { " . $css->menustyles['topfixedbackground'] . $css->menustyles['topfixedgradient'] . $css->menustyles['topfixedbackgroundfilter'] . " } ";
			}
		}

		// level1 normal items styles
		if (isset($css->level1itemnormalstyles)) {
			if ($css->level1itemnormalstyles['padding'] || $css->level1itemnormalstyles['margin'] || $css->level1itemnormalstyles['background'] || $css->level1itemnormalstyles['gradient'] || $css->level1itemnormalstyles['borderradius'] || $css->level1itemnormalstyles['shadow'] || $css->level1itemnormalstyles['border']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ", div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent { " . $css->level1itemnormalstyles['margin'] . $css->level1itemnormalstyles['background'] . $css->level1itemnormalstyles['gradient'] . $css->level1itemnormalstyles['borderradius'] . $css->level1itemnormalstyles['shadow'] . $css->level1itemnormalstyles['border'] . " } ";
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . " > span.separator { " . $css->level1itemnormalstyles['padding'] . " } ";
			}
			if ($css->level1itemnormalstyles['fontcolor'] || $css->level1itemnormalstyles['fontsize'] || $css->level1itemnormalstyles['fontweight'] || $css->level1itemnormalstyles['fontunderline'] || $css->level1itemnormalstyles['textshadow'] || $css->level1itemnormalstyles['text-transform']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " > span.separator span.titreck, select#" . $menuCSSID . " { " . $css->level1itemnormalstyles['fontcolor'] . $css->level1itemnormalstyles['fontsize'] . $css->level1itemnormalstyles['fontweight'] . $css->level1itemnormalstyles['fontunderline'] . $css->level1itemnormalstyles['textshadow'] . $css->level1itemnormalstyles['text-transform'] . " } ";
			}
			if ($css->level1itemnormalstyles['descfontcolor'] || $css->level1itemnormalstyles['descfontsize']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " > span.separator span.descck { " . $css->level1itemnormalstyles['descfontcolor'] . $css->level1itemnormalstyles['descfontsize'] . " } ";
			}
		}

		// level1 description items styles
		if (isset($css->level1itemdescstyles)) {
			if ($css->level1itemdescstyles['padding'] || $css->level1itemdescstyles['margin'] || $css->level1itemdescstyles['background'] || $css->level1itemdescstyles['gradient'] || $css->level1itemdescstyles['borderradius'] || $css->level1itemdescstyles['shadow'] || $css->level1itemdescstyles['border']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " > * .descck { " . $css->level1itemdescstyles['margin'] . $css->level1itemdescstyles['padding'] . $css->level1itemdescstyles['textshadow'] . " } ";
			}
		}

		// level1 hover items styles
		if (isset($fields['level1itemactivestyles']) && $fields['level1itemactivestyles']->get('level1itemactivestylesidemhover') == '1') {
			$level1active_li = "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent.active, ";
			$level1active_li_a = "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span, ";
			$level1active_titreck = "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator span.titreck, ";
			$level1active_descck = "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator span.descck, ";
		} else {
			$level1active_li = "";
			$level1active_li_a = "";
			$level1active_titreck = "";
			$level1active_descck = "";
		}
		if (isset($css->level1itemhoverstyles)) {
			if ($css->level1itemhoverstyles['padding'] || $css->level1itemhoverstyles['margin'] || $css->level1itemhoverstyles['background'] || $css->level1itemhoverstyles['gradient'] || $css->level1itemhoverstyles['borderradius'] || $css->level1itemhoverstyles['shadow'] || $css->level1itemhoverstyles['border']
			) {
				$csstoinject .= $level1active_li . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent:hover { " . $css->level1itemhoverstyles['margin'] . $css->level1itemhoverstyles['background'] . $css->level1itemhoverstyles['gradient'] . $css->level1itemhoverstyles['borderradius'] . $css->level1itemhoverstyles['shadow'] . $css->level1itemhoverstyles['border'] . " } ";
				$csstoinject .= $level1active_li_a . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > span.separator { " . $css->level1itemhoverstyles['padding'] . " } ";
			}
			if ($css->level1itemhoverstyles['fontcolor'] || $css->level1itemhoverstyles['fontsize'] || $css->level1itemhoverstyles['textshadow']
			) {
				$csstoinject .= $level1active_titreck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > span.separator span.titreck, select#" . $menuCSSID . ":hover { " . $css->level1itemhoverstyles['fontcolor'] . $css->level1itemhoverstyles['fontsize'] . $css->level1itemhoverstyles['fontweight'] . $css->level1itemhoverstyles['textshadow'] . " } ";
			}
			if ($css->level1itemhoverstyles['descfontcolor'] || $css->level1itemhoverstyles['descfontsize']
			) {
				$csstoinject .= $level1active_descck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ":hover > span.separator span.descck { " . $css->level1itemhoverstyles['descfontcolor'] . $css->level1itemhoverstyles['descfontsize'] . " } ";
			}
		}

		// level1 item active styles
		if (isset($fields['level1itemactivestyles']) && $fields['level1itemactivestyles']->get('level1itemactivestylesidemhover') == '0') {
			// level1 active items styles
			if (isset($css->level1itemactivestyles)) {
				if ($css->level1itemactivestyles['padding'] || $css->level1itemactivestyles['margin'] || $css->level1itemactivestyles['background'] || $css->level1itemactivestyles['gradient'] || $css->level1itemactivestyles['borderradius'] || $css->level1itemactivestyles['shadow'] || $css->level1itemactivestyles['border']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active { " . $css->level1itemactivestyles['margin'] . $css->level1itemactivestyles['background'] . $css->level1itemactivestyles['gradient'] . $css->level1itemactivestyles['borderradius'] . $css->level1itemactivestyles['shadow'] . $css->level1itemactivestyles['border'] . " } ";
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator { " . $css->level1itemactivestyles['padding'] . " } ";
				}
				if ($css->level1itemactivestyles['fontcolor'] || $css->level1itemactivestyles['fontsize'] || $css->level1itemactivestyles['textshadow']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator span.titreck { " . $css->level1itemactivestyles['fontcolor'] . $css->level1itemactivestyles['fontsize'] . $css->level1itemactivestyles['fontweight'] . $css->level1itemactivestyles['textshadow'] . " } ";
				}
				if ($css->level1itemactivestyles['descfontcolor'] || $css->level1itemactivestyles['descfontsize']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator span.descck { " . $css->level1itemactivestyles['descfontcolor'] . $css->level1itemactivestyles['descfontsize'] . " } ";
				}
			}
		}
		
		// level1 item active hover styles
		if (isset($fields['level1itemactivehoverstyles'])) {
			// level1 active items styles
			if (isset($css->level1itemactivehoverstyles)) {
				if ($css->level1itemactivehoverstyles['padding'] || $css->level1itemactivehoverstyles['margin'] || $css->level1itemactivehoverstyles['background'] || $css->level1itemactivehoverstyles['gradient'] || $css->level1itemactivehoverstyles['borderradius'] || $css->level1itemactivehoverstyles['shadow'] || $css->level1itemactivehoverstyles['border']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover { " . $css->level1itemactivehoverstyles['margin'] . $css->level1itemactivehoverstyles['background'] . $css->level1itemactivehoverstyles['gradient'] . $css->level1itemactivehoverstyles['borderradius'] . $css->level1itemactivehoverstyles['shadow'] . $css->level1itemactivehoverstyles['border'] . " } ";
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active > span.separator { " . $css->level1itemactivehoverstyles['padding'] . " } ";
				}
				if ($css->level1itemactivehoverstyles['fontcolor'] || $css->level1itemactivehoverstyles['fontsize'] || $css->level1itemactivehoverstyles['textshadow']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover > span.separator span.titreck { " . $css->level1itemactivehoverstyles['fontcolor'] . $css->level1itemactivehoverstyles['fontsize'] . $css->level1itemactivehoverstyles['fontweight'] . $css->level1itemactivehoverstyles['textshadow'] . " } ";
				}
				if ($css->level1itemactivehoverstyles['descfontcolor'] || $css->level1itemactivehoverstyles['descfontsize']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".active:hover > span.separator span.descck { " . $css->level1itemactivehoverstyles['descfontcolor'] . $css->level1itemactivehoverstyles['descfontsize'] . " } ";
				}
			}
		}

		// level1 item parent styles
		if (isset($css->level1itemparentstyles) && $fields['level1itemnormalstyles']->get('level1itemnormalstylesparentarrowtype', '') != 'none') {
			if ($css->level1itemparentstyles['padding'] || $css->level1itemparentstyles['margin'] || $css->level1itemparentstyles['background'] || $css->level1itemparentstyles['gradient'] || $css->level1itemparentstyles['borderradius'] || $css->level1itemparentstyles['shadow'] || $css->level1itemparentstyles['border']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent { " . $css->level1itemparentstyles['margin'] . $css->level1itemparentstyles['background'] . $css->level1itemparentstyles['gradient'] . $css->level1itemparentstyles['borderradius'] . $css->level1itemparentstyles['shadow'] . $css->level1itemparentstyles['border'] . " } ";
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > span.separator { " . $css->level1itemparentstyles['padding'] . " } ";
			}
			if ($css->level1itemparentstyles['fontcolor'] || $css->level1itemparentstyles['fontsize'] || $css->level1itemparentstyles['textshadow']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > span.separator span.titreck { " . $css->level1itemparentstyles['fontcolor'] . $css->level1itemparentstyles['fontsize'] . $css->level1itemparentstyles['fontweight'] . $css->level1itemparentstyles['textshadow'] . " } ";
			}
			if ($css->level1itemparentstyles['descfontcolor'] || $css->level1itemparentstyles['descfontsize']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . ".parent > span.separator span.descck { " . $css->level1itemparentstyles['descfontcolor'] . $css->level1itemparentstyles['descfontsize'] . " } ";
			}
		}

		// submenu styles
		if (isset($css->level2menustyles)) {
			if ($css->level2menustyles['padding'] || $css->level2menustyles['margin'] || $css->level2menustyles['background'] || $css->level2menustyles['gradient'] || $css->level2menustyles['borderradius'] || $css->level2menustyles['shadow'] || $css->level2menustyles['border']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck div.floatck, div#" . $menuCSSID . " li.maximenuck div.floatck div.floatck,
div#" . $menuID . " .maxipushdownck div.floatck, select#" . $menuCSSID . " option { " . $css->level2menustyles['padding'] . $css->level2menustyles['margin'] . $css->level2menustyles['background'] . $css->level2menustyles['gradient'] . $css->level2menustyles['borderradius'] . $css->level2menustyles['shadow'] . $css->level2menustyles['border'] . " } ";
			}
		}

		// level2 normal items styles
		if (isset($css->level2itemnormalstyles)) {
			if ($css->level2itemnormalstyles['padding'] || $css->level2itemnormalstyles['margin'] || $css->level2itemnormalstyles['background'] || $css->level2itemnormalstyles['gradient'] || $css->level2itemnormalstyles['borderradius'] || $css->level2itemnormalstyles['shadow'] || $css->level2itemnormalstyles['border'] || $css->level2itemnormalstyles['text-align']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . "):not(.headingck),
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck), select#" . $menuCSSID . " option { " . $css->level2itemnormalstyles['margin'] . $css->level2itemnormalstyles['background'] . $css->level2itemnormalstyles['gradient'] . $css->level2itemnormalstyles['borderradius'] . $css->level2itemnormalstyles['shadow'] . $css->level2itemnormalstyles['border'] . $css->level2itemnormalstyles['text-align'] . " } ";
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:not(.headingck) > a, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . "):not(.headingck) > a,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck) > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:not(.headingck) > span.separator, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . "):not(.headingck) > span.separator,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck) > span.separator { " . $css->level2itemnormalstyles['padding'] . " } ";
			}
			if ($css->level2itemnormalstyles['fontcolor'] || $css->level2itemnormalstyles['fontsize'] || $css->level2itemnormalstyles['fontweight'] || $css->level2itemnormalstyles['fontunderline'] || $css->level2itemnormalstyles['textshadow'] || $css->level2itemnormalstyles['text-transform']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") span.titreck,
div#" . $menuID . " .maxipushdownck li.maximenuck > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck > span.separator span.titreck, select#" . $menuCSSID . " option { " . $css->level2itemnormalstyles['fontcolor'] . $css->level2itemnormalstyles['fontsize'] . $css->level2itemnormalstyles['fontweight'] . $css->level2itemnormalstyles['fontunderline'] . $css->level2itemnormalstyles['textshadow'] . $css->level2itemnormalstyles['text-transform'] . " } ";
			}
			if ($css->level2itemnormalstyles['descfontcolor'] || $css->level2itemnormalstyles['descfontsize']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") span.descck,
div#" . $menuID . " .maxipushdownck li.maximenuck > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck > span.separator span.descck { " . $css->level2itemnormalstyles['descfontcolor'] . $css->level2itemnormalstyles['descfontsize'] . " } ";
			}
		}

		// level2 hover items styles
		if (isset($fields['level2itemactivestyles']) && $fields['level2itemactivestyles']->get('level2itemactivestylesidemhover') == '1') {
			$level2active_li = "\ndiv#" . $menuCSSID . " li.maximenuck.level2.active:not(.headingck), div#" . $menuCSSID . " li.maximenuck.level2.parent.active:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . "):not(.headingck),";
			$level2active_li_a = "\ndiv#" . $menuCSSID . " li.maximenuck.level2.active:not(.headingck), div#" . $menuCSSID . " li.maximenuck.level2.parent.active:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . "):not(.headingck),";
			$level2active_titreck = "\ndiv#" . $menuCSSID . " li.maximenuck.level2.active > a span.titreck, div#" . $menuCSSID . " li.maximenuck.level2.active > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . ") span.titreck,";
			$level2active_descck = "\ndiv#" . $menuCSSID . " li.maximenuck.level2.active > a span.descck, div#" . $menuCSSID . " li.maximenuck.level2.active > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . ") span.descck,";
		} else {
			$level2active_li = "";
			$level2active_li_a = "";
			$level2active_titreck = "";
			$level2active_descck = "";
		}
		if (isset($css->level2itemhoverstyles)) {
			if ($css->level2itemhoverstyles['padding'] || $css->level2itemhoverstyles['margin'] || $css->level2itemhoverstyles['background'] || $css->level2itemhoverstyles['gradient'] || $css->level2itemhoverstyles['borderradius'] || $css->level2itemhoverstyles['shadow'] || $css->level2itemhoverstyles['border']
			) {
				$csstoinject .= $level2active_li . "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck:not(.headingck):hover, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . "):not(.headingck):hover,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck):hover { " . $css->level2itemhoverstyles['margin'] . $css->level2itemhoverstyles['background'] . $css->level2itemhoverstyles['gradient'] . $css->level2itemhoverstyles['borderradius'] . $css->level2itemhoverstyles['shadow'] . $css->level2itemhoverstyles['border'] . " } ";
				$csstoinject .= $level2active_li_a . "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck:not(.headingck):hover > a, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . "):not(.headingck):hover > a,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck):hover > a, div#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck:not(.headingck):hover > span.separator, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . "):not(.headingck):hover > span.separator,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck):hover > span.separator { " . $css->level2itemhoverstyles['padding'] . " } ";
			}
			if ($css->level2itemhoverstyles['fontcolor'] || $css->level2itemhoverstyles['fontsize'] || $css->level2itemhoverstyles['textshadow']
			) {
				$csstoinject .= $level2active_titreck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:hover > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:hover > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . ") span.titreck,
div#" . $menuID . " .maxipushdownck li.maximenuck:hover > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck:hover > span.separator span.titreck { " . $css->level2itemhoverstyles['fontcolor'] . $css->level2itemhoverstyles['fontsize'] . $css->level2itemhoverstyles['fontweight'] . $css->level2itemhoverstyles['textshadow'] . " } ";
			}
			if ($css->level2itemhoverstyles['descfontcolor'] || $css->level2itemhoverstyles['descfontsize']
			) {
				$csstoinject .= $level2active_descck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:hover > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:hover > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . ") span.descck,
div#" . $menuID . " .maxipushdownck li.maximenuck:hover > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck:hover > span.separator span.descck { " . $css->level2itemhoverstyles['descfontcolor'] . $css->level2itemhoverstyles['descfontsize'] . " } ";
			}
		}

		if (isset($fields['level2itemactivestyles']) && $fields['level2itemactivestyles']->get('level2itemactivestylesidemhover') == '0') {
			// level2 active items styles
			if (isset($css->level2itemactivestyles)) {
				if ($css->level2itemactivestyles['padding'] || $css->level2itemactivestyles['margin'] || $css->level2itemactivestyles['background'] || $css->level2itemactivestyles['gradient'] || $css->level2itemactivestyles['borderradius'] || $css->level2itemactivestyles['shadow'] || $css->level2itemactivestyles['border']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:not(.headingck),
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:not(.headingck) { " . $css->level2itemactivestyles['margin'] . $css->level2itemactivestyles['background'] . $css->level2itemactivestyles['gradient'] . $css->level2itemactivestyles['borderradius'] . $css->level2itemactivestyles['shadow'] . $css->level2itemactivestyles['border'] . " } ";
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:not(.headingck) > a,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:not(.headingck) > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:not(.headingck) > span.separator,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:not(.headingck) > span.separator { " . $css->level2itemactivestyles['padding'] . " } ";
				}
				if ($css->level2itemactivestyles['fontcolor'] || $css->level2itemactivestyles['fontsize'] || $css->level2itemactivestyles['textshadow']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active > span.separator span.titreck,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck.active > span.separator span.titreck { " . $css->level2itemactivestyles['fontcolor'] . $css->level2itemactivestyles['fontsize'] . $css->level2itemactivestyles['fontweight'] . $css->level2itemactivestyles['textshadow'] . " } ";
				}
				if ($css->level2itemactivestyles['descfontcolor'] || $css->level2itemactivestyles['descfontsize']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active > span.separator span.descck,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck.active > span.separator span.descck { " . $css->level2itemactivestyles['descfontcolor'] . $css->level2itemactivestyles['descfontsize'] . " } ";
				}
			}
		}

		if (isset($fields['level2itemactivehoverstyles']) && $fields['level2itemactivehoverstyles']->get('level2itemactivehoverstylesidemhover') == '0') {
			// level2 active items styles
			if (isset($css->level2itemactivehoverstyles)) {
				if ($css->level2itemactivehoverstyles['padding'] || $css->level2itemactivehoverstyles['margin'] || $css->level2itemactivehoverstyles['background'] || $css->level2itemactivehoverstyles['gradient'] || $css->level2itemactivehoverstyles['borderradius'] || $css->level2itemactivehoverstyles['shadow'] || $css->level2itemactivehoverstyles['border']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover:not(.headingck),
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover:not(.headingck) { " . $css->level2itemactivehoverstyles['margin'] . $css->level2itemactivehoverstyles['background'] . $css->level2itemactivehoverstyles['gradient'] . $css->level2itemactivehoverstyles['borderradius'] . $css->level2itemactivehoverstyles['shadow'] . $css->level2itemactivehoverstyles['border'] . " } ";
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover:not(.headingck) > a,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover:not(.headingck) > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover:not(.headingck) > span.separator,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover:not(.headingck) > span.separator { " . $css->level2itemactivehoverstyles['padding'] . " } ";
				}
				if ($css->level2itemactivehoverstyles['fontcolor'] || $css->level2itemactivehoverstyles['fontsize'] || $css->level2itemactivehoverstyles['textshadow']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover > span.separator span.titreck,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover > span.separator span.titreck { " . $css->level2itemactivehoverstyles['fontcolor'] . $css->level2itemactivehoverstyles['fontsize'] . $css->level2itemactivehoverstyles['fontweight'] . $css->level2itemactivehoverstyles['textshadow'] . " } ";
				}
				if ($css->level2itemactivehoverstyles['descfontcolor'] || $css->level2itemactivehoverstyles['descfontsize']
				) {
					$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck.active:hover > span.separator span.descck,
	div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck.active:hover > span.separator span.descck { " . $css->level2itemactivehoverstyles['descfontcolor'] . $css->level2itemactivehoverstyles['descfontsize'] . " } ";
				}
			}
		}

		// sub submenu styles
		if (isset($css->level3menustyles)) {
			if ($css->level3menustyles['padding'] || $css->level3menustyles['margin'] || $css->level3menustyles['background'] || $css->level3menustyles['gradient'] || $css->level3menustyles['borderradius'] || $css->level3menustyles['shadow'] || $css->level3menustyles['border']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck div.floatck div.floatck,
div#" . $menuID . " .maxipushdownck div.floatck div.floatck { " . $css->level3menustyles['padding'] . $css->level3menustyles['margin'] . $css->level3menustyles['background'] . $css->level3menustyles['gradient'] . $css->level3menustyles['borderradius'] . $css->level3menustyles['shadow'] . $css->level3menustyles['border'] . " } ";
			}
		}

		// level3 normal items styles
		if (isset($css->level3itemnormalstyles)) {
			if ($css->level3itemnormalstyles['padding'] || $css->level3itemnormalstyles['margin'] || $css->level3itemnormalstyles['background'] || $css->level3itemnormalstyles['gradient'] || $css->level3itemnormalstyles['borderradius'] || $css->level3itemnormalstyles['shadow'] || $css->level3itemnormalstyles['border'] || $css->level3itemnormalstyles['text-align']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") li.maximenuck:not(.headingck),
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck) { " . $css->level3itemnormalstyles['margin'] . $css->level3itemnormalstyles['background'] . $css->level3itemnormalstyles['gradient'] . $css->level3itemnormalstyles['borderradius'] . $css->level3itemnormalstyles['shadow'] . $css->level3itemnormalstyles['border'] . $css->level3itemnormalstyles['text-align'] . " } ";
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck:not(.headingck) > a, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") li.maximenuck:not(.headingck) > a,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:not(.headingck) > a, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:not(.headingck) > span.separator, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") li.maximenuck:not(.headingck) > span.separator,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:not(.headingck) > span.separator { " . $css->level3itemnormalstyles['padding'] . " } ";
			}
			if ($css->level3itemnormalstyles['fontcolor'] || $css->level3itemnormalstyles['fontsize'] || $css->level3itemnormalstyles['textshadow'] || $css->level3itemnormalstyles['text-transform']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") li.maximenuck span.titreck,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck > span.separator span.titreck { " . $css->level3itemnormalstyles['fontcolor'] . $css->level3itemnormalstyles['fontsize'] . $css->level3itemnormalstyles['fontweight'] . $css->level3itemnormalstyles['textshadow'] . $css->level3itemnormalstyles['text-transform'] . " } ";
			}
			if ($css->level3itemnormalstyles['descfontcolor'] || $css->level3itemnormalstyles['descfontsize']
			) {
				$csstoinject .= "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck:not(." . $level1 . ") li.maximenuck span.descck,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck > span.separator span.descck { " . $css->level3itemnormalstyles['descfontcolor'] . $css->level3itemnormalstyles['descfontsize'] . " } ";
			}
		}

		// level3 hover items styles
		if (isset($fields['level3itemactivestyles']) && $fields['level3itemactivestyles']->get('level3itemactivestylesidemhover') == '1') {
			$level3active_li = "\ndiv#" . $menuCSSID . " li.maximenuck.level3.active:not(.headingck), div#" . $menuCSSID . " li.maximenuck.level3.parent.active:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . "):not(.headingck),";
			$level3active_li_a = "\ndiv#" . $menuCSSID . " li.maximenuck.level3.active:not(.headingck), div#" . $menuCSSID . " li.maximenuck.level3.parent.active:not(.headingck), div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . "):not(.headingck),";
			$level3active_titreck = "\ndiv#" . $menuCSSID . " li.maximenuck.level3.active > a span.titreck, div#" . $menuCSSID . " li.maximenuck.level3.active > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . ") span.titreck,";
			$level3active_descck = "\ndiv#" . $menuCSSID . " li.maximenuck.level3.active > a span.descck, div#" . $menuCSSID . " li.maximenuck.level3.active > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck.active:not(." . $level1 . ") span.descck,";
		} else {
			$level3active_li = "";
			$level3active_li_a = "";
			$level3active_titreck = "";
			$level3active_descck = "";
		}
		if (isset($css->level3itemhoverstyles)) {
			if ($css->level3itemhoverstyles['padding'] || $css->level3itemhoverstyles['margin'] || $css->level3itemhoverstyles['background'] || $css->level3itemhoverstyles['gradient'] || $css->level3itemhoverstyles['borderradius'] || $css->level3itemhoverstyles['shadow'] || $css->level3itemhoverstyles['border']
			) {
				$csstoinject .= $level3active_li . "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:not(.headingck):hover, div#" . $menuID . " li.maximenuck.maximenuflatlistck li.maximenuck:hover:not(." . $level1 . "):not(.headingck):hover,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck):hover { " . $css->level3itemhoverstyles['margin'] . $css->level3itemhoverstyles['background'] . $css->level3itemhoverstyles['gradient'] . $css->level3itemhoverstyles['borderradius'] . $css->level3itemhoverstyles['shadow'] . $css->level3itemhoverstyles['border'] . " } ";
				$csstoinject .= $level3active_li_a . "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck:not(.headingck) li.maximenuck:hover > a, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . ") li.maximenuck:not(.headingck):hover > a,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck) li.maximenuck:hover > a, div#" . $menuID . " ul.maximenuck li.maximenuck." . $level1 . " li.maximenuck:not(.headingck) li.maximenuck:hover > span.separator, div#" . $menuID . " li.maximenuck.maximenuflatlistck:hover:not(." . $level1 . ") li.maximenuck:not(.headingck):hover > span.separator,
div#" . $menuID . " .maxipushdownck li.maximenuck:not(.headingck) li.maximenuck:hover > span.separator { " . $css->level3itemhoverstyles['padding'] . " } ";
			}
			if ($css->level3itemhoverstyles['fontcolor'] || $css->level3itemhoverstyles['fontsize'] || $css->level3itemhoverstyles['textshadow']
			) {
				$csstoinject .= $level3active_titreck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:hover > a span.titreck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:hover > span.separator span.titreck, div#" . $menuID . " li.maximenuck.maximenuflatlistck li.maximenuck:hover:not(." . $level1 . ") span.titreck,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:hover > a span.titreck, div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:hover > span.separator span.titreck { " . $css->level3itemhoverstyles['fontcolor'] . $css->level3itemhoverstyles['fontsize'] . $css->level3itemhoverstyles['fontweight'] . $css->level3itemhoverstyles['textshadow'] . " } ";
			}
			if ($css->level3itemhoverstyles['descfontcolor'] || $css->level3itemhoverstyles['descfontsize']
			) {
				$csstoinject .= $level3active_descck . "\ndiv#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:hover > a span.descck, div#" . $menuCSSID . " li.maximenuck." . $level1 . " li.maximenuck li.maximenuck:hover > span.separator span.descck, div#" . $menuID . " li.maximenuck.maximenuflatlistck li.maximenuck:hover:not(." . $level1 . ") span.descck,
div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:hover > a span.descck, div#" . $menuID . " .maxipushdownck li.maximenuck li.maximenuck:hover > span.separator span.descck { " . $css->level3itemhoverstyles['descfontcolor'] . $css->level3itemhoverstyles['descfontsize'] . " } ";
			}
		}

		// heading items styles
		if (isset($css->headingstyles)) {
			$headingclass = '.nav-header';
			$padding = $css->headingstyles['padding'] ? trim($css->headingstyles['padding'], ";") . ";" : '';
			$margin = $css->headingstyles['margin'] ? trim($css->headingstyles['margin'], ";") . ";" : '';
			$background = $css->headingstyles['background'] ? trim($css->headingstyles['background'], ";") . ";" : '';
			$gradient = $css->headingstyles['gradient'] ? trim($css->headingstyles['gradient'], ";") . ";" : '';
			$borderradius = $css->headingstyles['borderradius'] ? trim($css->headingstyles['borderradius'], ";") . ";" : '';
			$shadow = $css->headingstyles['shadow'] ? trim($css->headingstyles['shadow'], ";") . ";" : '';
			$border = $css->headingstyles['border'] ? trim($css->headingstyles['border'], ";") . ";" : '';
			if ($padding || $margin || $background || $gradient || $borderradius || $shadow || $border || $css->headingstyles['text-align'] || $css->headingstyles['text-transform']) {
			$csstoinject .= "\ndiv#" . $menuCSSID . " ul.maximenuck2 li.maximenuck > " . $headingclass . ",
div#" . $menuID . " .maxipushdownck ul.maximenuck2 li.maximenuck > " . $headingclass . " { " . $padding . $margin . $background . $gradient . $borderradius . $shadow . $border . $css->headingstyles['text-align'] . $css->headingstyles['text-transform'] . " } ";
			}
			if ($css->headingstyles['fontcolor'] || $css->headingstyles['fontsize'] || $css->headingstyles['fontweight'] || $css->headingstyles['textshadow']) {
			$csstoinject .= "\ndiv#" . $menuCSSID . " ul.maximenuck2 li.maximenuck > " . $headingclass . " span.titreck,
div#" . $menuID . " .maxipushdownck ul.maximenuck2 li.maximenuck > " . $headingclass . " span.titreck { " . $css->headingstyles['fontcolor'] . $css->headingstyles['fontsize'] . $css->headingstyles['fontweight'] . $css->headingstyles['textshadow'] . " } ";
			}
			if ($css->headingstyles['descfontcolor'] || $css->headingstyles['descfontsize']) {
			$csstoinject .= "\ndiv#" . $menuCSSID . " ul.maximenuck2 li.maximenuck > " . $headingclass . " span.descck,
div#" . $menuID . " .maxipushdownck ul.maximenuck2 li.maximenuck > " . $headingclass . " span.descck{ " . $css->headingstyles['descfontcolor'] . $css->headingstyles['descfontsize'] . " } ";
			}
		}

		// heading items styles
		if (isset($css->fancystyles)) {
			$padding = $css->fancystyles['padding'] ? trim($css->fancystyles['padding'], ";") . ";" : '';
			$margin = $css->fancystyles['margin'] ? trim($css->fancystyles['margin'], ";") . ";" : '';
			$background = $css->fancystyles['background'] ? trim($css->fancystyles['background'], ";") . ";" : '';
			$gradient = $css->fancystyles['gradient'] ? trim($css->fancystyles['gradient'], ";") . ";" : '';
			$borderradius = $css->fancystyles['borderradius'] ? trim($css->fancystyles['borderradius'], ";") . ";" : '';
			$shadow = $css->fancystyles['shadow'] ? trim($css->fancystyles['shadow'], ";") . ";" : '';
			$border = $css->fancystyles['border'] ? trim($css->fancystyles['border'], ";") . ";" : '';
			$height = $css->fancystyles['height'] ? trim($css->fancystyles['height'], ";") . ";" : '';
			$width = $css->fancystyles['width'] ? trim($css->fancystyles['width'], ";") . ";" : '';
			if ($padding || $margin || $background || $gradient || $borderradius || $shadow || $border || $css->fancystyles['text-align'] || $height || $width) {
			$csstoinject .= "\ndiv#" . $menuCSSID . " .maxiFancybackground { " . $padding . $margin . $background . $gradient . $borderradius . $shadow . $border . $css->fancystyles['text-align']. $height . $width . " } ";
			}
		}

		if ($params->get('customcss', '') != '[]')
			$csstoinject .= str_replace('|ID|', 'div#' . $menuCSSID, $params->get('customcss', ''));

		return $csstoinject;
	}

	/**
	 * Create the css properties
	 * @param \Joomla\Registry\Registry $params
	 * @param string $prefix the xml field prefix
	 *
	 * @return Array
	 */
	static function createCss($menuID, $params, $prefix = 'menu', $important = false, $itemid = '', $use_svggradient = true) {
		$css = Array();
		$important = ($important == true ) ? ' !important' : '';
		$csspaddingtop = ($params->get($prefix . 'paddingtop') != '') ? 'padding-top: ' . Helper::testUnit($params->get($prefix . 'paddingtop', '0')) . $important . ';' : '';
		$csspaddingright = ($params->get($prefix . 'paddingright') != '') ? 'padding-right: ' . Helper::testUnit($params->get($prefix . 'paddingright', '0')) . $important . ';' : '';
		$csspaddingbottom = ($params->get($prefix . 'paddingbottom') != '') ? 'padding-bottom: ' . Helper::testUnit($params->get($prefix . 'paddingbottom', '0')) . $important . ';' : '';
		$csspaddingleft = ($params->get($prefix . 'paddingleft') != '') ? 'padding-left: ' . Helper::testUnit($params->get($prefix . 'paddingleft', '0')) . $important . ';' : '';
		$css['padding'] = $csspaddingtop . $csspaddingright . $csspaddingbottom . $csspaddingleft;
		$cssmargintop = ($params->get($prefix . 'margintop') != '') ? 'margin-top: ' . Helper::testUnit($params->get($prefix . 'margintop', '0')) . $important . ';' : '';
		$cssmarginright = ($params->get($prefix . 'marginright') != '') ? 'margin-right: ' . Helper::testUnit($params->get($prefix . 'marginright', '0')) . $important . ';' : '';
		$cssmarginbottom = ($params->get($prefix . 'marginbottom') != '') ? 'margin-bottom: ' . Helper::testUnit($params->get($prefix . 'marginbottom', '0')) . $important . ';' : '';
		$cssmarginleft = ($params->get($prefix . 'marginleft') != '') ? 'margin-left: ' . Helper::testUnit($params->get($prefix . 'marginleft', '0')) . $important . ';' : '';
		$css['margin'] = $cssmargintop . $cssmarginright . $cssmarginbottom . $cssmarginleft;
		$bgcolor1 = ($params->get($prefix . 'bgcolor1') && $params->get($prefix . 'bgopacity') !== null && $params->get($prefix . 'bgopacity') !== '') ? Helper::hex2RGB($params->get($prefix . 'bgcolor1'), $params->get($prefix . 'bgopacity')) : $params->get($prefix . 'bgcolor1');
		$css['background'] = ($params->get($prefix . 'bgcolor1')) ? 'background: ' . $bgcolor1 . $important . ';' : '';
		$css['background'] .= ($params->get($prefix . 'bgcolor1')) ? 'background-color: ' . $bgcolor1 . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-image: url("' . \Joomla\CMS\Uri\Uri::root() . $params->get($prefix . 'bgimage') . '")' . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-repeat: ' . $params->get($prefix . 'bgimagerepeat') . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-position: ' . $params->get($prefix . 'bgpositionx') . ' ' . $params->get($prefix . 'bgpositiony') . $important . ';' : '';
		$css['backgroundfilter'] = ($params->get($prefix . 'backdropfilter') && $params->get($prefix . 'backdropfilter') !== 'none') ? 'backdrop-filter: ' . $params->get($prefix . 'backdropfilter') . '(' . $params->get($prefix . 'backdropfiltervalue') . ')' . $important . ';' : '';
		$bgcolor2 = ($params->get($prefix . 'bgcolor2') && $params->get($prefix . 'bgopacity') && $params->get($prefix . 'bgopacity') !== '') ? Helper::hex2RGB($params->get($prefix . 'bgcolor2'), $params->get($prefix . 'bgopacity')) : $params->get($prefix . 'bgcolor2');
		// manage gradient svg for ie9
		$svggradient = '';
		// if ($use_svggradient) {
			// $svggradientfile = '';
			// if ($css['background'] AND $params->get($prefix . 'bgcolor2')) {
				// $svggradientfile = self::createSvgGradient($menuID, $prefix . $itemid, $params->get($prefix . 'bgcolor1', ''), $params->get($prefix . 'bgcolor2', ''));
			// }
			// $svggradient = $svggradientfile ? "background-image: url(\"" . $svggradientfile . "\")" . $important . ";" : "";
		// }
		$css['gradient'] = ($css['background'] AND $params->get($prefix . 'bgcolor2')) ?
				$svggradient
				. "background: -moz-linear-gradient(top,  " . $bgcolor1 . " 0%, " . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $bgcolor1 . "), color-stop(100%," . $bgcolor2 . "))" . $important . "; "
				. "background: -webkit-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -o-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -ms-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: linear-gradient(to bottom,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . "; " : '';
//                . "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $params->get($prefix . 'bgcolor1', '#f0f0f0') . "', endColorstr='" . $params->get($prefix . 'bgcolor2', '#e3e3e3') . "',GradientType=0 );" : '';
		$css['borderradius'] = ($params->get($prefix . 'roundedcornerstl', '') != '' || $params->get($prefix . 'roundedcornerstr', '') != '' || $params->get($prefix . 'roundedcornersbr', '') != '' || $params->get($prefix . 'roundedcornersbl', '') != '') ?
				'-moz-border-radius: ' . Helper::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';'
				. '-webkit-border-radius: ' . Helper::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';'
				. 'border-radius: ' . Helper::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';' : '';
		$shadowinset = $params->get($prefix . 'shadowinset', 0) ? 'inset ' : '';
		$css['shadow'] = ($params->get($prefix . 'shadowcolor') AND $params->get($prefix . 'shadowblur') != '') ?
				'-moz-box-shadow: ' . $shadowinset . Helper::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';'
				. '-webkit-box-shadow: ' . $shadowinset . Helper::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';'
				. 'box-shadow: ' . $shadowinset . Helper::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . Helper::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';' :
				(($params->get($prefix . 'useshadow') && $params->get($prefix . 'shadowblur') == '0') ? '-moz-box-shadow: none' . $important . ';'
						. '-webkit-box-shadow: none' . $important . ';'
						. 'box-shadow: none' . $important . ';' : '');
		$borderstyle = $params->get($prefix . 'borderstyle', 'solid') ? $params->get($prefix . 'borderstyle', 'solid') : 'solid';
		$bordertopstyle = $params->get($prefix . 'bordertopstyle', 'solid') ? $params->get($prefix . 'bordertopstyle', 'solid') : $borderstyle;
		$borderrightstyle = $params->get($prefix . 'borderrightstyle', 'solid') ? $params->get($prefix . 'borderrightstyle', 'solid') : $borderstyle;
		$borderbottomstyle = $params->get($prefix . 'borderbottomstyle', 'solid') ? $params->get($prefix . 'borderbottomstyle', 'solid') : $borderstyle;
		$borderleftstyle = $params->get($prefix . 'borderleftstyle', 'solid') ? $params->get($prefix . 'borderleftstyle', 'solid') : $borderstyle;
		$bordercolor = $params->get($prefix . 'bordercolor', '') ? $params->get($prefix . 'bordercolor', '') : '';
		$bordertopcolor = $params->get($prefix . 'bordertopcolor', '') ? $params->get($prefix . 'bordertopcolor', '') : $bordercolor;
		$borderrightcolor = $params->get($prefix . 'borderrightcolor', '') ? $params->get($prefix . 'borderrightcolor', '') : $bordercolor;
		$borderbottomcolor = $params->get($prefix . 'borderbottomcolor', '') ? $params->get($prefix . 'borderbottomcolor', '') : $bordercolor;
		$borderleftcolor = $params->get($prefix . 'borderleftcolor', '') ? $params->get($prefix . 'borderleftcolor', '') : $bordercolor;

		$css['border'] = (($params->get($prefix . 'bordertopwidth') == '0') ? 'border-top: none' . $important . ';' : (($params->get($prefix . 'bordertopwidth') != '' AND $bordertopcolor) ? 'border-top: ' . $bordertopcolor . ' ' . Helper::testUnit($params->get($prefix . 'bordertopwidth', '')) . ' ' . $bordertopstyle . ' ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderrightwidth') == '0') ? 'border-right: none' . $important . ';' : (($params->get($prefix . 'borderrightwidth') != '' AND $borderrightcolor) ? 'border-right: ' . $borderrightcolor . ' ' . Helper::testUnit($params->get($prefix . 'borderrightwidth', '')) . ' ' . $borderrightstyle . ' ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderbottomwidth') == '0') ? 'border-bottom: none' . $important . ';' : (($params->get($prefix . 'borderbottomwidth') != '' AND $borderbottomcolor) ? 'border-bottom: ' . $borderbottomcolor . ' ' . Helper::testUnit($params->get($prefix . 'borderbottomwidth', '')) . ' ' . $borderbottomstyle . ' ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderleftwidth') == '0') ? 'border-left: none' . $important . ';' : (($params->get($prefix . 'borderleftwidth') != '' AND $borderleftcolor) ? 'border-left: ' . $borderleftcolor . ' ' . Helper::testUnit($params->get($prefix . 'borderleftwidth', '')) . ' ' . $borderleftstyle . ' ' . $important . ';' : '') );
		$css['fontsize'] = ($params->get($prefix . 'fontsize') != '') ?
				'font-size: ' . Helper::testUnit($params->get($prefix . 'fontsize')) . $important . ';' : '';
		$css['fontcolor'] = ($params->get($prefix . 'fontcolor') != '') ?
				'color: ' . $params->get($prefix . 'fontcolor') . $important . ';' : '';
		$css['fontweight'] = ($params->get($prefix . 'fontweight')  == 'bold') ?
				'font-weight: ' . $params->get($prefix . 'fontweight') . $important . ';' : '';
		$css['fontunderline'] = ($params->get($prefix . 'fontunderline')  == 'underline') ?
				'text-decoration: ' . $params->get($prefix . 'fontunderline') . $important . ';' : '';
		/* $css['fontcolorhover'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'fontcolorhover')) ?
		  'color: ' . $params->get($prefix . 'fontcolorhover') . ';' : ''; */
		$css['descfontsize'] = ($params->get($prefix . 'descfontsize') != '') ?
				'font-size: ' . Helper::testUnit($params->get($prefix . 'descfontsize')) . $important . ';' : '';
		$css['descfontcolor'] = ($params->get($prefix . 'descfontcolor') != '') ?
				'color: ' . $params->get($prefix . 'descfontcolor') . $important . ';' : '';
		$textshadowoffsetx = ($params->get($prefix . 'textshadowoffsetx', '0') == '') ? '0px' : Helper::testUnit($params->get($prefix . 'textshadowoffsetx', '0'));
		$textshadowoffsety = ($params->get($prefix . 'textshadowoffsety', '0') == '') ? '0px' : Helper::testUnit($params->get($prefix . 'textshadowoffsety', '0'));
		$css['textshadow'] = ($params->get($prefix . 'textshadowcolor') AND $params->get($prefix . 'textshadowblur')) ?
				'text-shadow: ' . $textshadowoffsetx . ' ' . $textshadowoffsety . ' ' . Helper::testUnit($params->get($prefix . 'textshadowblur', '')) . ' ' . $params->get($prefix . 'textshadowcolor', '') . $important . ';' :
				(($params->get($prefix . 'textshadowblur') == '0') ? 'text-shadow: none' . $important . ';' : '');
		$css['text-align'] = $params->get($prefix . 'textalign') ? 'text-align: ' . $params->get($prefix . 'textalign') . $important . ';' : ''; '';
		$css['text-transform'] = ($params->get($prefix . 'texttransform') && $params->get($prefix . 'texttransform') != 'default') ? 'text-transform: ' . $params->get($prefix . 'texttransform') . $important . ';' : ''; '';
		$css['text-indent'] = ($params->get($prefix . 'textindent') && $params->get($prefix . 'textindent') != 'default') ? 'text-indent: ' . Helper::testUnit($params->get($prefix . 'textindent')) . $important . ';' : ''; '';
		$css['line-height'] = ($params->get($prefix . 'lineheight') && $params->get($prefix . 'lineheight') != 'default') ? 'line-height: ' . Helper::testUnit($params->get($prefix . 'lineheight')) . $important . ';' : ''; '';
		$css['height'] = ($params->get($prefix . 'height') && $params->get($prefix . 'height') != '') ? 'height: ' . Helper::testUnit($params->get($prefix . 'height')) . $important . ';' : ''; '';
		$css['width'] = ($params->get($prefix . 'width') && $params->get($prefix . 'width') != '') ? 'width: ' . Helper::testUnit($params->get($prefix . 'width')) . $important . ';' : ''; '';

		// topfixed effect
		$topfixedbgcolor1 = ($params->get($prefix . 'topfixedbgcolor1') && $params->get($prefix . 'topfixedbgopacity') !== null && $params->get($prefix . 'topfixedbgopacity') !== '') ? Helper::hex2RGB($params->get($prefix . 'topfixedbgcolor1'), $params->get($prefix . 'topfixedbgopacity')) : $params->get($prefix . 'topfixedbgcolor1');
		$topfixedbgcolor2 = ($params->get($prefix . 'topfixedbgcolor2') && $params->get($prefix . 'topfixedbgopacity') && $params->get($prefix . 'topfixedbgopacity') !== '') ? Helper::hex2RGB($params->get($prefix . 'topfixedbgcolor2'), $params->get($prefix . 'topfixedbgopacity')) : $params->get($prefix . 'topfixedbgcolor2');
		$css['topfixedbackground'] = ($params->get($prefix . 'topfixedbgcolor1')) ? 'background: ' . $topfixedbgcolor1 . $important . ';' : '';
		$css['topfixedbackground'] .= ($params->get($prefix . 'topfixedbgcolor1')) ? 'background-color: ' . $topfixedbgcolor1 . $important . ';' : '';
		$css['topfixedgradient'] = ($css['topfixedbackground'] AND $params->get($prefix . 'topfixedbgcolor2')) ?
				"background: -moz-linear-gradient(top,  " . $topfixedbgcolor1 . " 0%, " . $topfixedbgcolor2 . " 100%)" . $important . ";"
				. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $topfixedbgcolor1 . "), color-stop(100%," . $topfixedbgcolor2 . "))" . $important . "; "
				. "background: -webkit-linear-gradient(top,  " . $topfixedbgcolor1 . " 0%," . $topfixedbgcolor2 . " 100%)" . $important . ";"
				. "background: -o-linear-gradient(top,  " . $topfixedbgcolor1 . " 0%," . $topfixedbgcolor2 . " 100%)" . $important . ";"
				. "background: -ms-linear-gradient(top,  " . $topfixedbgcolor1 . " 0%," . $topfixedbgcolor2 . " 100%)" . $important . ";"
				. "background: linear-gradient(to bottom,  " . $topfixedbgcolor1 . " 0%," . $topfixedbgcolor2 . " 100%)" . $important . "; " : '';
		$css['topfixedbackgroundfilter'] = ($params->get($prefix . 'topfixedbackdropfilter') && $params->get($prefix . 'topfixedbackdropfilter') !== 'none') ? 'backdrop-filter: ' . $params->get($prefix . 'topfixedbackdropfilter') . '(' . $params->get($prefix . 'topfixedbackdropfiltervalue') . ')' . $important . ';' : '';

		self::retrocompatibility_beforev8($css, $params, $prefix);
		return $css;
	}

	static function retrocompatibility_beforev8(& $css, $params, $prefix) {
		if ( $params->exists($prefix . 'usemargin') && $params->get($prefix . 'usemargin') != '1' ) {
			$css['margin'] = '';
			$css['padding'] = '';
		}
		if ( $params->exists($prefix . 'usebackground') && $params->get($prefix . 'usebackground') != '1' ) {
			$css['background'] = '';
			$css['gradient'] = '';
		}
		if ( $params->exists($prefix . 'usegradient') && $params->get($prefix . 'usegradient') != '1' ) {
			$css['gradient'] = '';
		}
		if ( $params->exists($prefix . 'useroundedcorners') && $params->get($prefix . 'useroundedcorners') != '1' ) {
			$css['borderradius'] = '';
		}
		if ( $params->exists($prefix . 'useshadow') && $params->get($prefix . 'useshadow') != '1' ) {
			$css['shadow'] = '';
		}
		if ( $params->exists($prefix . 'useborders') && $params->get($prefix . 'useborders') != '1' ) {
			$css['border'] = '';
		}
		if ( $params->exists($prefix . 'usefont') && $params->get($prefix . 'usefont') != '1' ) {
			$css['fontsize'] = '';
			$css['fontcolor'] = '';
			$css['fontweight'] = '';
			$css['descfontsize'] = '';
			$css['descfontcolor'] = '';
		}
		if ( $params->exists($prefix . 'usetextshadow') && $params->get($prefix . 'usetextshadow') == '1' ) {
			$css['textshadow'] = '';
		}

	}

	/**
	 * Create the svg gradient for IE9
	 * @param string $prefix
	 *
	 * @return void
	 */
	static function createSvgGradient($menuID, $prefix, $color1, $color2) {
		// create the file svg for IE9 and Opera gradient compatibility
		$path = JPATH_ROOT . '/modules/mod_maximenuck/assets/svggradient/';
		$svgie9cssdest = $path . $menuID . $prefix . '-gradient.svg';

		$svgie9csstext = '<?xml version="1.0" ?>
            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.0" width="100%"
            height="100%"
            xmlns:xlink="http://www.w3.org/1999/xlink">

            <defs>
            <linearGradient id="' . $menuID . $prefix . '"
            x1="0%" y1="0%"
            x2="0%" y2="100%"
            spreadMethod="pad">
            <stop offset="0%"   stop-color="' . $color1 . '" stop-opacity="1"/>
            <stop offset="100%" stop-color="' . $color2 . '" stop-opacity="1"/>
            </linearGradient>
            </defs>

            <rect width="100%" height="100%"
            style="fill:url(#' . $menuID . $prefix . ');" />
            </svg>
            ';

		if (!\Joomla\CMS\Filesystem\File::write($svgie9cssdest, $svgie9csstext))
			return '';

		return \Joomla\CMS\Uri\Uri::root(true) . '/modules/mod_maximenuck/assets/svggradient/' . $menuID . $prefix . '-gradient.svg';
	}

	/**
	 * Extract the css family name of the google font from the url
	 * @param string $gfont the font url
	 *
	 * @return string the font family
	 */
	static function get_gfontfamily($gfont) {
		// Open+Sans+Condensed:300
		if ( preg_match( '/(.*?):/', $gfont, $matches) ) {
			if ( isset($matches[1]) ) {
				$gfont = $matches[1];
			}
		}

		return ucwords(str_replace("+", " ", $gfont));
	}

	/**
	 * Get the CSS of the style
	 * @id - the style ID
	 */
	public static function getCss($id, $returnObject = false) {
		if (! $id) return '';

		$query = 'SELECT a.params, a.layoutcss, a.customcss FROM #__maximenuck_styles as a WHERE (a.state IN (0, 1)) AND a.id = ' . (int)$id;
		$result = CKFof::dbLoadObject($query);

		if ($result === null) return '';
		$result->css = $result->layoutcss . $result->customcss;

		self::makeCssReplacement($result->css);

		if ($returnObject == true) {
			return $result;
		}
		return $result->css;
	}

	public static function makeCssReplacement(&$css) {
		$cssreplacements = self::getCssReplacement();
		foreach ($cssreplacements as $tag => $rep) {
			$css = str_replace($tag, $rep, $css);
		}
//		return $css;
	}

	/**
	 * List the replacement between the tags and the real final CSS rules
	 */
	public static function getCssReplacement() {
		if (! empty(self::$cssreplacements)) return self::$cssreplacements;

		// self::$cssreplacements = Array(
			// '[menustyles]' => ''
			// ,'[level1itemnormalstyles]' => '.cameraSlide img'
			// ,'[level1itemhoverstyles]' => '.camera_caption > div'
			// ,'[level1itemactivestyles]' => '.camera_caption_title'
			// ,'[level1itemparentstyles]' => '.camera_caption_desc'
			// ,'[level2menustyles]' => 'a.camera-button'
			// ,'[level2itemnormalstyles]' => 'a.camera-button:hover'
			// ,'[level2itemhoverstyles]' => '.camera_pag_ul li img'
			// ,'[level2itemactivestyles]' => '.camera_pag_ul li img'
			// ,'[level1itemnormalstylesicon]' => 'li.level1 > *:not(div) .maximenuiconck'
			// ,'[level1itemhoverstylesicon]' => 'li.level1:hover > *:not(div) .maximenuiconck'
			// ,'[level2itemnormalstylesicon]' => 'li.level1 li > *:not(div) .maximenuiconck'
			// ,'[level2itemhoverstylesicon]' => 'li.level1 li:hover > *:not(div) .maximenuiconck'
			// ,'[level3menustyles]' => '.camera_pag_ul li img'
			// ,'[level3itemnormalstyles]' => '.camera_pag_ul li img'
			// ,'[level3itemhoverstyles]' => '.camera_pag_ul li img'
			// ,'[headingstyles]' => '.camera_pag_ul li img'
			// ,'[fancystyles]' => '.camera_pag_ul li img'
		// );

		self::$cssreplacements = Array(
			'|qq|' => '"'
		);

		return self::$cssreplacements;
	}

	/*
	 * Convert fields between the old and new interface
	 */
	public static function updateInterface($fields, $order = 1) {
		$new = array(
			  'backgroundcolorstart'
			, 'backgroundcolorend'
			, 'backgroundopacity'
			, 'backgroundimageurl'
			, 'backgroundimagerepeat'
			, 'backgroundimageleft'
			, 'backgroundimagetop'
			, 'bordertopsize'
			, 'borderrightsize'
			, 'borderbottomsize'
			, 'borderleftsize'
			, 'borderradiustopleft'
			, 'borderradiustopright'
			, 'borderradiusbottomleft'
			, 'borderradiusbottomright'
			, 'stylescolor'
			, 'stylesdesccolor'
			, 'shadowoffseth'
			, 'shadowoffsetv'
		);

		$old = array(
			  'bgcolor1'
			, 'bgcolor2'
			, 'bgopacity'
			, 'bgimage'
			, 'bgimagerepeat'
			, 'bgpositionx'
			, 'bgpositiony'
			, 'bordertopwidth'
			, 'borderrightwidth'
			, 'borderbottomwidth'
			, 'borderleftwidth'
			, 'roundedcornerstl'
			, 'roundedcornerstr'
			, 'roundedcornersbl'
			, 'roundedcornersbr'
			, 'stylesfontcolor'
			, 'stylesdescfontcolor'
			, 'shadowoffsetx'
			, 'shadowoffsety'
		);

		// update from old to new interface
		if ($order === 1) {
			return str_replace($old, $new, $fields);
		}
		// update from new to old interface
		return str_replace($new, $old, $fields);
	}
}

// create a new class to manage objects
if (!class_exists('CkCssParams')) {

	class CkCssParams extends \stdClass {

		function get($key) {
			return isset($this->$key) ? $this->$key : null;
		}
		
		function exists($key) {
			return isset($this->$key) ? true : false;
		}

	}

}