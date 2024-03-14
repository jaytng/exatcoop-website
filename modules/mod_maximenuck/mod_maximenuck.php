<?php
// no direct access
defined('_JEXEC') or die;

use Maximenuck\Helperfront;
use Maximenuck\Helper;

require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/defines.php';
require_once MAXIMENUCK_PATH . '/helpers/ckfof.php';
require_once MAXIMENUCK_PATH . '/helpers/helper.php';
require_once MAXIMENUCK_FRONT_PATH . '/helpers/helperfront.php';

// check if we are using the new version 9 settings
if ($params->get('isv9', '') == '1') {

// load old helper because we still need it
require_once dirname(__FILE__) . '/helper.php';

// set the default html id for the menu
if ( $params->get('menuid', '') === '' || is_numeric($params->get('menuid', ''))) {
	$params->set('menuid', 'maximenuck' . $module->id);
}
$menuID = $params->get('menuid', '');
$loadfontawesome = false;
$theme = $params->get('theme', 'blank');

// check the compilation process
$doCompile = false;
// if one of the compile option is active (compile or yes)
if ($params->get('loadcompiledcss', '0') != '0') {
	if ( ($params->get('loadcompiledcss', '0') == '2' 
		// && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/maximenuck.php')
		)
			|| ! file_exists(dirname(__FILE__) . '/themes/custom/css/maximenuck_' . $menuID . '.css') ) {
		$doCompile = true;
	} 
	// else if($params->get('loadcompiledcss', '0') == '2') {
		// echo '<p style="color:red;font-weight:bold;">MAXIMENU ERROR : Advanced Options - Compile theme is active but file themes/' . $theme . '/css/maximenuck.php not found.</p>';
	// }
}
// set the doCompile params to use in the helper for menu items css
$params->set('doCompile', $doCompile);

// load the items
$source = $params->get('source', 'menu');
if ($source != 'menu' && $source != 'menubuilder') {
	$sourceFile = MAXIMENUCK_PLUGINS_PATH . '/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php';
	if (! file_exists($sourceFile)) {
		echo '<p syle="color:red;">Error : File plugins/maximenuck/' . strtolower($source) . '/helpers/helper_' . strtolower($source) . '.php not found !</p>';
		return;
	}
	require_once $sourceFile;
} else {
	require_once MAXIMENUCK_FRONT_PATH . '/helpers/source/' . $source . '.php';
}

$loaderClass = 'MaximenuckHelpersource' . ucfirst($source);
$items = $loaderClass::getItems($params);

if (empty($items)) return;

// Logo layout
$nLevel1 = 0;
foreach ($items as $item) {
	if ($item->level == 1) $nLevel1++;
	// B/C to avoid php errors, because of migration to J4
	if (! isset($item->fparams)) $item->fparams = $item->params;
}

$document = \Joomla\CMS\Factory::getDocument();
$app = \Joomla\CMS\Factory::getApplication();
$menu = $app->getMenu();
$active = $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path = isset($active) ? $active->tree : array();
$class_sfx = htmlspecialchars((string)$params->get('class_sfx', ''));
jimport('joomla.plugin.helper');

// get the language direction
$langdirection = $document->getDirection();

// page title management
if ($active) {
	$pagetitle = $document->getTitle();
	$title = $pagetitle;
	if (preg_match("/||/", $active->title)) {
		$title = explode("||", $active->title);
		$title = str_replace($active->title, $title[0], $pagetitle);
	}
	if (preg_match("/\[/", $active->title)) {
		if (!$title)
			$title = $active->title;
		$title = explode("[", $title);
		$title = str_replace($active->title, $title[0], $pagetitle);
	}
	$document->setTitle($title); // returns the page title without description
}

// retrieve parameters from the module
// params for the script
$fxduration = $params->get('fxduration', 500);
$fxtransition = $params->get('fxtransition', 'linear');
$orientation = $params->get('orientation', 'horizontal');
$testoverflow = $params->get('testoverflow', '0');
$opentype = $params->get('opentype', 'open');
$offcanvaswidth = $params->get('offcanvaswidth', '300');
$fxdirection = $params->get('direction', 'normal');
$directionoffset1 = $params->get('directionoffset1', '30');
$directionoffset2 = $params->get('directionoffset2', '30');
$behavior = $params->get('behavior', 'moomenu');
$usecss = $params->get('usecss', '1'); // for old version compatibility (no more used in the xml)
$usefancy = $params->get('usefancy', '1');
$fancyduree = $params->get('fancyduration', 500);
$fancytransition = $params->get('fancytransition', 'linear');
$fancyease = $params->get('fancyease', 'easeOut');
$fxtype = $params->get('fxtype', 'open');
$dureein = $params->get('dureein', 0);
$dureeout = $params->get('dureeout', 500);
$showactivesubitems = $params->get('showactivesubitems', '0');
$menubgcolor = $params->get('menubgcolor', '') ? "background:" . $params->get('menubgcolor', '') : '';
$ismobile = '0';
$logoimage = $params->get('logoimage', '');
$logolink = $params->get('logolink', '');
$logoheight = $params->get('logoheight', '');
$logowidth = $params->get('logowidth', '');
$usejavascript = $params->get('usejavascript', '1');
$effecttype = ($params->get('layout', 'default') == '_:pushdown') ? 'pushdown' : 'dropdown';
$allCss = '';

if ( ($effecttype == 'pushdown' || $effecttype == 'megatabs') && $orientation == 'vertical') {
	echo '<p style="color:red;font-weight:bold;">MAXIMENU MESSAGE : You can not use this layout for a Vertical menu</p>';
	return false;
}
// detection for mobiles
if (isset($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPad') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || strstr($_SERVER['HTTP_USER_AGENT'], 'Android'))) {
	$behavior = 'click';
	$ismobile = '1';
}

// get the css from the plugin params and inject them
// if ( file_exists(JPATH_ROOT . '/administrator/components/com_maximenuck/maximenuck.php') ) {
	// modMaximenuckHelper::injectModuleCss($params, $menuID);
// }

// if a theme has been selected, load the css from it
if ( $theme != '-1' ) {
	// do not add the stylesheet if the compile is active
	if ($params->get('loadcompiledcss', '0') == '0') {
		if ( file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/maximenuck.php') ) {
			if ($langdirection == 'rtl' && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/maximenuck_rtl.php')) {
				$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuck_rtl.php?monid=' . $menuID);
			} else {
				$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuck.php?monid=' . $menuID);
			}
		} else { // compatibility with old themes before v8
		$retrocompatibility_css = '#'.$menuID.' div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck:hover div.floatck div.floatck {
left: auto !important;
height: auto;
width: auto;
display: none;
}

#'.$menuID.' ul.maximenuck li:hover div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck li:hover div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck li:hover div.floatck li:hover div.floatck {
display: block;
left: auto !important;
height: auto;
width: auto;
}

div#'.$menuID.' ul.maximenuck li.maximenuck.nodropdown div.floatck,
div#'.$menuID.' ul.maximenuck li.maximenuck div.floatck li.maximenuck.nodropdown div.floatck,
div#'.$menuID.' .maxipushdownck div.floatck div.floatck {
display: block !important;
}';
			// $document->addStyleDeclaration($retrocompatibility_css);
			$allCss .= $retrocompatibility_css;
			// add external stylesheets
			if ($orientation == 'vertical') {
				if ($langdirection == 'rtl' && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/moo_maximenuvck_rtl.css')) {
					$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/moo_maximenuvck_rtl.css');
				} else {
					$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/moo_maximenuvck.css');
				}
				if ($usecss == 1 ) {
					if ($langdirection == 'rtl' && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/maximenuvck_rtl.php')) {
						$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuvck_rtl.php?monid=' . $menuID);
					} else {
						$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuvck.php?monid=' . $menuID);
					}
				}
			} else {
				if ($langdirection == 'rtl' && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/moo_maximenuhck_rtl.css')) {
					$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/moo_maximenuhck_rtl.css');
				} else {
					$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/moo_maximenuhck.css');
				}
				if ($usecss == 1) {
					if ($langdirection == 'rtl' && file_exists(dirname(__FILE__) . '/themes/' . $theme . '/css/maximenuhck_rtl.php')) {
						$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuhck_rtl.php?monid=' . $menuID);
					} else {
						$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuhck.php?monid=' . $menuID);
					}
				}
			}
		}
	}
} else {
	// if no theme has been selected, just load the minimal css
	$dropdown_css = '#'.$menuID.' div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck:hover div.floatck div.floatck {
display: none;
}

#'.$menuID.' ul.maximenuck li:hover div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck li:hover div.floatck, #'.$menuID.' ul.maximenuck li:hover div.floatck li:hover div.floatck li:hover div.floatck {
display: block;
}';
		// $document->addStyleDeclaration($dropdown_css);
		$allCss .= $dropdown_css;
}

$menuposition = $params->get('menuposition', '0');
if ($menuposition) {
	$fixedcssposition = ($menuposition == 'bottomfixed') ? "bottom: 0 !important;" : "top: 0 !important;";
	$fixedcss = "div#" . $menuID . ".maximenufixed {
	position: fixed !important;
	left: 0 !important;
	" . $fixedcssposition . "
	right: 0 !important;
	z-index: 1000 !important;
	margin: 0 auto;
	width: 100%;
	" . ($params->get('fixedpositionwidth') ? "max-width: " . Helper::testUnit($params->get('fixedpositionwidth')) . ";" : "" ) . "
}";
	if ($menuposition == 'topfixed') {
		$fixedcss .= "div#" . $menuID . ".maximenufixed ul.maximenuck {
            top: 0 !important;
        }";
	} else if ($menuposition == 'bottomfixed') {
		$fxdirection = 'inverse';
	}

		$allCss .= $fixedcss;
}

$isMaximenuMobilePluginActive = \Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'maximenuckmobile');
// add the css classes to show/hide the items
if ($isMaximenuMobilePluginActive) {
	$maximenuplugin = \Joomla\CMS\Plugin\PluginHelper::getPlugin('system', 'maximenuckmobile');
	$pluginParams = new \Joomla\Registry\Registry($maximenuplugin->params);
	$resolution = $pluginParams->get('maximenumobile_resolution', '640');
} else {
	$resolution = $params->get('maximenumobile_resolution', '640');
}
// update to take care of the resolution in the module options
$resolution = $params->get('mobilemenuck_resolution', $resolution);

// check for Mobile Menu CK
$isMobileMenuPluginActive = \Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'mobilemenuck');

$loadModuleMobileIcon = false;
if ($params->get('maximenumobile_enable') === '1' && !$isMaximenuMobilePluginActive && !$isMobileMenuPluginActive) {
	$loadModuleMobileIcon = true;
	$mobiletogglercss = "@media screen and (max-width: " . (int)$resolution . "px) {"
		. "#" . $menuID . " .maximenumobiletogglericonck {display: block !important;font-size: 33px !important;text-align: right !important;padding-top: 10px !important;}"
		. "#" . $menuID . " .maximenumobiletogglerck + ul.maximenuck {display: none !important;}"
		. "#" . $menuID . " .maximenumobiletogglerck:checked + ul.maximenuck {display: block !important;}"
		. "div#" . $menuID . " .maximenuck-toggler-anchor {display: block;}"
		. "}";
	// $document->addStyleDeclaration($mobiletogglercss);
	$allCss .= $mobiletogglercss;
}

$mobilecss = "
@media screen and (max-width: " . (int)$resolution . "px) {"
	. "div#" . $menuID . " ul.maximenuck li.maximenuck.nomobileck, div#" . $menuID . " .maxipushdownck ul.maximenuck2 li.maximenuck.nomobileck { display: none !important; }"
	. "
	div#" . $menuID . ".maximenuckh {
        height: auto !important;
    }
	
	div#" . $menuID . ".maximenuckh li.maxiFancybackground {
		display: none !important;
	}

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) {
        height: auto !important;
        padding-left: 0 !important;
        /*padding-right: 0 !important;*/
    }

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) li {
        float :none !important;
        width: 100% !important;
		box-sizing: border-box;
        /*padding-right: 0 !important;*/
		padding-left: 0 !important;
		padding-right: 0 !important;
        margin-right: 0 !important;
    }

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) li > div.floatck {
        width: 100% !important;
		box-sizing: border-box;
		right: 0 !important;
		left: 0 !important;
		margin-left: 0 !important;
		position: relative !important;
		/*display: none;
		height: auto !important;*/
    }
	
	div#" . $menuID . ".maximenuckh ul:not(.noresponsive) li:hover > div.floatck {
		position: relative !important;
		margin-left: 0 !important;
    }

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) div.floatck div.maximenuck2 {
        width: 100% !important;
    }

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) div.floatck div.floatck {
        width: 100% !important;
        margin: 20px 0 0 0 !important;
    }
	
	div#" . $menuID . ".maximenuckh ul:not(.noresponsive) div.floatck div.maxidrop-main {
        width: 100% !important;
    }

    div#" . $menuID . ".maximenuckh ul:not(.noresponsive) li.maximenucklogo img {
        display: block !important;
        margin-left: auto !important;
        margin-right: auto !important;
        float: none !important;
    }
	
	
	/* for vertical menu  */
	div#" . $menuID . ".maximenuckv {
        height: auto !important;
    }
	
	div#" . $menuID . ".maximenuckh li.maxiFancybackground {
		display: none !important;
	}

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) {
        height: auto !important;
        padding-left: 0 !important;
        /*padding-right: 0 !important;*/
    }

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) li {
        float :none !important;
        width: 100% !important;
        /*padding-right: 0 !important;*/
		padding-left: 0 !important;
        margin-right: 0 !important;
    }

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) li > div.floatck {
        width: 100% !important;
		right: 0 !important;
		margin-left: 0 !important;
		margin-top: 0 !important;
		position: relative !important;
		left: 0 !important;
		/*display: none;
		height: auto !important;*/
    }
	
	div#" . $menuID . ".maximenuckv ul:not(.noresponsive) li:hover > div.floatck {
		position: relative !important;
		margin-left: 0 !important;
    }

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) div.floatck div.maximenuck2 {
        width: 100% !important;
    }

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) div.floatck div.floatck {
        width: 100% !important;
        margin: 20px 0 0 0 !important;
    }
	
	div#" . $menuID . ".maximenuckv ul:not(.noresponsive) div.floatck div.maxidrop-main {
        width: 100% !important;
    }

    div#" . $menuID . ".maximenuckv ul:not(.noresponsive) li.maximenucklogo img {
        display: block !important;
        margin-left: auto !important;
        margin-right: auto !important;
        float: none !important;
    }
}
	
@media screen and (min-width: " . ((int)$resolution+1) . "px) {
	div#" . $menuID . " ul.maximenuck li.maximenuck.nodesktopck, div#" . $menuID . " .maxipushdownck ul.maximenuck2 li.maximenuck.nodesktopck { display: none !important; }
}";
// $document->addStyleDeclaration($mobilecss);
$allCss .= $mobilecss;

\Joomla\CMS\HTML\HTMLHelper::_("jquery.framework", true);
// \Joomla\CMS\HTML\HTMLHelper::_("jquery.ui");

$debug = false;
if ($usejavascript && $params->get('layout', 'default') != '_:flatlist' && $params->get('layout', 'default') != '_:nativejoomla' && $params->get('layout', 'default') != '_:dropselect') {
	if ($debug == true) {
		$document->addScript(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/assets/maximenuck.js');
	} else {
		$document->addScript(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/assets/maximenuck.min.js');
	}

	if ($fxtransition != 'linear' || $fancytransition != 'linear')
		$document->addScript(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/assets/jquery.easing.1.3.js');
	if ($opentype == 'scale' || $opentype == 'puff' || $opentype == 'drop')
		$document->addScript(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/assets/jquery.ui.1.8.js');

	$load = ($params->get('load', 'domready') == 'load') ? "jQuery(window).load(function(){" : "jQuery(document).ready(function(){";
	// $js = $load . "('#" . $menuID . "').DropdownMaxiMenu({"
	$js = $load . "new Maximenuck('#" . $menuID . "', {"
			. "fxtransition : '" . $fxtransition . "',"
			. "dureeIn : " . $dureein . ","
			. "dureeOut : " . $dureeout . ","
			. "menuID : '" . $menuID . "',"
			. "testoverflow : '" . $testoverflow . "',"
			. "orientation : '" . $orientation . "',"
			. "behavior : '" . $behavior . "',"
			. "opentype : '" . $opentype . "',"
			. "fxdirection : '" . $fxdirection . "',"
			. "directionoffset1 : '" . $directionoffset1 . "',"
			. "directionoffset2 : '" . $directionoffset2 . "',"
			. "showactivesubitems : '" . $showactivesubitems . "',"
			. "ismobile : " . $ismobile . ","
			. "menuposition : '" . $menuposition . "',"
			. "effecttype : '" . $effecttype . "',"
			. "topfixedeffect : '" . $params->get('topfixedeffect', '1') . "',"
			. "topfixedoffset : '" . $params->get('topfixedoffset', '') . "',"
			. "clickclose : '" . ($behavior == 'clickclose' ? $params->get('clickclose', '0') : '0') . "',"
			. "closeclickoutside : '" . $params->get('closeclickoutside', '0') . "',"
			. "clicktoggler : '" . $params->get('clicktoggler', '0') . "',"
			. "fxduration : " . $fxduration . "});"
			. "});";

	$document->addScriptDeclaration($js);



// add fancy effect
	if ($orientation == 'horizontal' && $usefancy == 1) {
		// $document->addScript(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/assets/fancymenuck.js');
		$js = "jQuery(document).ready(function(){"
				. "new FancyMaximenuck('#" . $menuID . "', {"
				. "fancyTransition : '" . $fancytransition . "',"
				. "fancyDuree : " . $fancyduree . "});"
				. "});";
		$document->addScriptDeclaration($js);
	}
}

// manage microdata
if ($params->get('microdata', '1') == '1') {
	$microdata_ul = ' itemscope itemtype="https://www.schema.org/SiteNavigationElement"';
	$microdata_li = ' itemprop="name"';
	$microdata_a = ' itemprop="url"';
} else {
	$microdata_ul = '';
	$microdata_li = '';
	$microdata_a = '';
}

// load all CSS in a single file if compiled, else load in the page
// styles from the theme
$themeCss = '';
if ((int) $params->get('loadcompiledcss', '0') > 0) {
	if ( $doCompile) {
		// $themeCss = modMaximenuckHelper::getCompiledCss($params);
		$themeCss .= Helperfront::getCompiledCss($params);
		// if (! $themeCss) {
			// echo '<p style="color:red;font-weight:bold;">MAXIMENU ERROR : Advanced Options - Compile theme is active, error during compilation process.</p>';
		// }
	}
	// specific for menu items settings 
	if ( $doCompile && $source == 'menu') {
		// $themeCss = modMaximenuckHelper::getCompiledCss($params);
		$themeCss .= MaximenuckHelpersourceMenu::getCompiledCss($params);
		// if (! $themeCss) {
			// echo '<p style="color:red;font-weight:bold;">MAXIMENU ERROR : Advanced Options - Compile theme is active, error during compilation process.</p>';
		// }
	}
	$document->addStyleSheet(\Joomla\CMS\Uri\Uri::base(true) . '/modules/mod_maximenuck/themes/custom/css/maximenuck_' . $menuID . '.css');
}

// styles from the styling interface
$styleCss = '';
$styleId = $params->get('styles', 0, 'int');
if ($styleId) {
	require_once MAXIMENUCK_PATH . '/helpers/style.php';
	$style = Maximenuck\Style::getCss($styleId, true);
	if (! empty($style)) {
		$styleCss = $style->css;
		$styleCss = str_replace('|ID|', $menuID, $styleCss);
		if ($orientation == 'horizontal') $styleCss = str_replace('.maximenuckv', '.maximenuckh', $styleCss);
		if ($orientation == 'vertical') $styleCss = str_replace('.maximenuckh', '.maximenuckv', $styleCss);
	}
}

require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_maximenuck', $params->get('layout', 'default'));

// load font awesome if needed
global $ckfontawesomeisloaded;
global $ckfontawesomev5isloaded;
$fontawesomeversion = $params->get('fontawesomeversion', '5');
if ($params->get('loadfontawesomescript', '1') == '1') {
	if ($loadfontawesome && $fontawesomeversion == '4' && !$ckfontawesomeisloaded) {
		$document->addStyleSheet(MAXIMENUCK_MEDIA_URI . '/assets/font-awesome.min.css');
		$ckfontawesomeisloaded = true;
	} else if ($loadfontawesome && $fontawesomeversion == '5' && !$ckfontawesomev5isloaded) {
		$document->addStyleSheet(MAXIMENUCK_MEDIA_URI . '/assets/fontawesome.all.min.css');
		$ckfontawesomev5isloaded = true;
	}
}

// manage googlefonts
$loadgooglefonts = $params->get('loadgooglefonts', '0');
if ($loadgooglefonts == 'auto') {
	
	
	if (isset($style->params)) {
		$styleParams = json_decode($style->params);
		if (! isset($styleParams->level3menustylestextgfont)) $styleParams->level3menustylestextgfont = '';
		$gfonts = array ($styleParams->menustylestextgfont
			, $styleParams->level2menustylestextgfont
			,$styleParams->level3menustylestextgfont);
		foreach($gfonts as $font) {
		$font = str_replace(' ', '+', ucwords (trim($font)));
		$font = trim(trim($font, "'"));
		if (isset($font[1])) $document->addStylesheet('https://fonts.googleapis.com/css?family=' . $font);
	}
	} else {
		preg_match_all( '/font-family: \'(.*?)\'/', $styleCss, $matches);
		if (isset($matches[1])) {
			foreach($matches[1] as $font) {
				$font = str_replace(' ', '+', ucwords (trim($font)));
				$font = trim(trim($font, "'"));
				if (isset($font[1])) $document->addStylesheet('https://fonts.googleapis.com/css?family=' . $font);
			}

		}
	}
} else if ($loadgooglefonts == 'custom') {
	$customgooglefonts = $params->get('customgooglefonts', '');
	$customgooglefonts = explode("\n", $customgooglefonts);
	foreach ($customgooglefonts as $font) {
		$document->addStylesheet(trim($font));
	}
}

// style for icons
$iconCss = '';
if ($loadfontawesome) {
	$iconCss = '#' . $menuID . ' li.maximenuck.level1 > * > span.titreck {
	display: flex;
	flex-direction: ' . ($params->get('faiconpositionlevel1', 'left') == 'left' ? 'row' : 'column') . ';
}

#' . $menuID . ' ul.maximenuck li.maximenuck.level2 span.titreck {
	display: flex;
	flex-direction: ' . ($params->get('faiconpositionlevel2', 'left') == 'left' ? 'row' : 'column') . ';
	' . ($params->get('faiconpositionlevel1', 'left') == 'left' ? 'margin-right: 5px;' : '') . '
}

#' . $menuID . ' .maximenuiconck {
	align-self: center;
	' . ($params->get('faiconpositionlevel1', 'left') == 'left' ? 'margin-right: ' : 'margin-bottom: ') . Helper::testUnit($params->get('faiconmargin', '5px')) . ';
}

#' . $menuID . ' li.maximenuck.level1 {
	vertical-align: top;
}';
}

if (($behavior == 'clickclose' || $behavior == 'click') && $params->get('clickclose', '0')) {
	$allCss .= '.maxiclose {
	color: #fff;
	background: rgba(0,0,0,0.3);
	padding: 10px;
	border-radius: 4px;
	margin: 5px;
	display: inline-block;
	cursor: pointer;
}

.maxiclose:hover {
	background: rgba(0,0,0,0.7);
}';
}

// WCAG
$allCss .= '/*---------------------------------------------
---	 WCAG				                ---
----------------------------------------------*/';
if ($params->get('enable_accessibility_focus', '0') == '1') {
$allCss .= '#' . $menuID . ' ul.maximenuck li.maximenuck > a:focus {
    outline: 1px dashed ' . $params->get('accessibilty_border_color', '#ff0000') . ' !important;
}';
}

$allCss .= '
#' . $menuID . '.maximenuck-wcag-active .maximenuck-toggler-anchor ~ ul {
    display: block !important;
}

#' . $menuID . ' .maximenuck-toggler-anchor {
	height: 0;
	opacity: 0;
	overflow: hidden;
	display: none;
}';

// OFF CANVAS
if ($opentype == 'offcanvas') {
$allCss .= '/*---------------------------------------------
---	 OFF CANVAS				                ---
----------------------------------------------*/
#' . $menuID . ' ul.maximenuck li.maximenuck-offcanvas > div.floatck {
	position: fixed !important;
	right: 0;
	top: 0;
	bottom: 0;
	left: auto;
	width: ' . Helper::testUnit($offcanvaswidth)  .' !important;
	margin: 0 !important;
}

#' . $menuID . ' ul.maximenuck li.maximenuck-offcanvas > div.floatck > div.maxidrop-main {
	margin-top: 50px;
	width: auto;
}

#' . $menuID . ' .maximenuck-offcanvas-bar {
	box-sizing: border-box;
	font-family: verdana;
	background: #666;
	padding: 5px 10px;
	padding-top: 5px;
	height: 50px;
	position: absolute;
	top: 0px;
	right: 0;
	left: 0;
	color: #333;
}

#' . $menuID . ' .maximenuck-offcanvas-close {
	display: block;
	text-align: center;
	height: 50px;
	background: #777;
	position: absolute;
	right: 0px;
	top: 0px;
	box-sizing: border-box;
	width: 50px;
	text-align: center;
	line-height: 45px;
	cursor: pointer;
	color: #444;

}

#' . $menuID . ' .maximenuck-offcanvas-close:after {
	content: "x";
}

#' . $menuID . ' .maximenuck-offcanvas-close:hover {
	color: #aaa;
}

#' . $menuID . ' .maximenuck-offcanvas-back {
	position: absolute;
	left: 10px;
	background: #777;
	padding: 5px 10px;
	box-sizing: border-box;
	height: 30px;
	top: 10px;
	border-radius: 15px;
	color: #444;
	line-height: 20px;
	cursor: pointer;
	text-transform: uppercase;
}

#' . $menuID . ' .maximenuck-offcanvas-back:hover {
	background: #444;
	color: #aaa;
}
';
}


if ($params->get('logoposition', 'left') == 'center') {
	$nLogo = (int)($nLevel1 / 2) + 1;
	if (($nLevel1 % 2) === 1 && $params->get('logopositionpartition', 'even') == 'even') $nLogo += 1;
	$allCss .= 'div#' . $menuID . ' > ul.maximenuck {
	display: flex !important;
	align-items : center; /* center, start, end, normal, */
	justify-content: center; /* center, left, right, space-between, space-around */
}

div#' . $menuID . ' > ul.maximenuck > .maximenucklogo {
	order :1;
}

div#' . $menuID . ' ul.maximenuck li.maximenuck.level1:nth-of-type(n+' . ($nLogo + 1) . ') {
	order: 2;
}
';
}

if ($params->get('clicktoggler', '0') == '1') {
$allCss .= '/* toggler icon */
#' . $menuID . ' li.level1.parent .maximenuck-toggler:after {
	content: "";
	display: block;
	position: absolute;
	width: 0; 
	height: 0; 
	border-style: solid;
	border-width: 7px 6px 0 6px;
	border-color: #000 transparent transparent transparent;
	top: 50%;
	right: 3px;
	margin-top: -3px;
}

#' . $menuID . ' .maximenuckv li.parent .maximenuck-toggler:after,
#' . $menuID . ' li.parent li.parent .maximenuck-toggler:after {
	border-width: 6px 0 6px 7px;
	border-color: transparent transparent transparent #000;
	right: 6px;
	margin-top: -6px;
}

#' . $menuID . ' li.has-maximenuck-toggler.parent > a:after,
#' . $menuID . ' li.has-maximenuck-toggler.parent > span:after {
	display: none !important;
}

#' . $menuID . ' li.has-maximenuck-toggler.parent > a,
#' . $menuID . ' li.has-maximenuck-toggler.parent > span {
	padding-right: 20px;
	position: relative;
}

#' . $menuID . ' .maximenuck-toggler {
	display: inline-block;
	position: absolute;
	width: 20px;
	height: 100%;
	margin-left: -20px;
	top: 0;
	right: 0;
}

#' . $menuID . ' .maximenuck-toggler:hover {
	background: rgba(0,0,0, 0.1);
}';
}
// GENERAL USE
$generalCss = 'div#' . $menuID . ' .titreck-text {
	flex: 1;
}

div#' . $menuID . ' .maximenuck.rolloveritem  img {
	display: none !important;
}';

// for images position
$imagesCSS = '/* for images position */
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > a,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > span.separator {
	display: flex;
	align-items: center;
}

div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > a,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > span.separator {
	display: flex;
}

div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > a[data-align="bottom"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > span.separator[data-align="bottom"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > a[data-align="bottom"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > span.separator[data-align="bottom"],
	div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > a[data-align="top"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > span.separator[data-align="top"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > a[data-align="top"],
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > span.separator[data-align="top"]{
	flex-direction: column;
	align-items: inherit;
}

div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > a[data-align=*"bottom"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > span.separator[data-align=*"bottom"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > a[data-align=*"bottom"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > span.separator[data-align=*"bottom"] img {
	align-self: end;
}

div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > a[data-align=*"top"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 > span.separator[data-align=*"top"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > a[data-align=*"top"] img,
div#' . $menuID . ' ul.maximenuck li.maximenuck.level1 li.maximenuck > span.separator[data-align=*"top"] img {
	align-self: start;
}


';

// combine all styles
$allCss = $generalCss . $themeCss . $allCss . $styleCss . $iconCss . $imagesCSS;
if ( $doCompile ) {
	$cssfile = dirname(__FILE__) . '/themes/custom/css/maximenuck_' . $menuID . '.css';
	if (! file_exists(dirname(__FILE__) . '/themes/custom/css/')) {
		\Joomla\CMS\Filesystem\Folder::create(dirname(__FILE__) . '/themes/custom/css/');
	}

	// store the css in the file, if error then load the css directly in the page
	if (! file_put_contents($cssfile, $allCss)) {
		$document->addStyleDeclaration($allCss); // fallback if compile fails
	}
} else {
	$document->addStyleDeclaration($allCss);
}

// use the V8 settings if the module has not yet been saved in the new version 9
} else {
	echo '<div class="alert alert-danger">Maximenu CK message : Your module ID ' . $module->id . ' is still working in V8 Legacy mode. Please change it in the Advanced options to remove this message.</div>';
	// load the old V8 file
	require dirname(__FILE__) . '/legacy.php';
}