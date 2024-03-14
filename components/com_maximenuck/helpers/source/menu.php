<?php
// no direct access
defined('_JEXEC') or die;

use Maximenuck\Helper;
use Maximenuck\Helperfront;
use Maximenuck\Style;

require_once MAXIMENUCK_PATH . '/helpers/helper.php';
require_once MAXIMENUCK_PATH . '/helpers/style.php';

class MaximenuckHelpersourceMenu {

	private static $_itemcss;

	private static $_modulecss;

	/**
	 * Get a list of the menu items.
	 *
	 * @param	\Joomla\Registry\Registry	$params	The module options.
	 *
	 * @return	array
	 */
	static function getItems(&$params) {
		$app = \Joomla\CMS\Factory::getApplication();
		$menu = $app->getMenu();

		// If no active menu, use default
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		$base = self::getBase($params);

//		$user = \Joomla\CMS\Factory::getUser();
//		$levels = $user->getAuthorisedViewLevels();
//		asort($levels);
//		$key = 'menu_items' . $params . implode(',', $levels) . '.' . $active->id;
//		$cache = \Joomla\CMS\Factory::getCache('mod_maximenuck', '');
//		if (!($items = $cache->get($key)) || (int) $params->get('cache') == '0') {
			// Initialise variables.
			$list = array();
			$modules = array();
			$db = \Joomla\CMS\Factory::getDbo();
			$document = \Joomla\CMS\Factory::getDocument();

			// load the libraries
			jimport('joomla.application.module.helper');

			$path = $base->tree;
			$start = (int) $params->get('startLevel');
			$end = (int) $params->get('endLevel');
			$items = $menu->getItems('menutype', $params->get('menutype'));

			// if no items in the menu then exit
			if (!$items)
				return false;

			$hidden_parents = array();
			$lastitem = 0;
			// list all modules
			$modulesList = Helperfront::CreateModulesList();

			// check for imbrication with third party items
			$nbadditems = 0;
			foreach ($items as $i => $item) {
				if ($item->type == 'component' && 
				($item->component == 'com_maximenuckhikashop' || ($item->component == 'com_maximenuck' && $item->query['view'] == 'sources') )
				) {
					$itemparams = new \Joomla\Registry\Registry();
					if (isset($item->query) && is_array($item->query)) {
						$itemparams->loadArray($item->query);
					}
					
					if ($item->component == 'com_maximenuckhikashop') {
						require_once JPATH_ROOT . '/plugins/system/maximenuck_hikashop/helper/helper_maximenuck_hikashop.php';
						$className = 'modMaximenuckhikashopHelper';
					} else {
						$source = $itemparams->get('maximenuck_plugin_type');
						$sourceFile = MAXIMENUCK_PLUGINS_PATH . '/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php';
						if (! file_exists($sourceFile)) {
							echo '<p syle="color:red;">Error : File plugins/maximenuck/' . strtolower($source) . '/helpers/helper_' . strtolower($source) . '.php not found !</p>';
							return;
						}
						require_once $sourceFile;
						$className = 'MaximenuckHelpersource' . ucfirst($source);
					}
					
					$additems = $className::getItems($itemparams, false, $item->level, $item->parent_id);

					if (! empty($additems) && isset($additems[0]) && isset($additems[0]->params)) {
						// check the settings of the source item to implement the needed settings (colwidth ...)
						$temp_params = $item->getParams();
						$additems[0]->params->set('maximenu_createcolumn', $temp_params->get('maximenu_createcolumn', '0'));
						$additems[0]->params->set('maximenu_createnewrow', $temp_params->get('maximenu_createnewrow', '0'));
						$additems[0]->params->set('maximenu_colwidth', $temp_params->get('maximenu_colwidth', '0'));
					}

					if (is_int($i)) {
						array_splice($items, $i + $nbadditems, 1, $additems);
					} else {
						$pos   = array_search($i, array_keys($items));
						$items = array_merge(
							array_slice($items, 1, $pos),
							$additems,
							array_slice($items, $pos)
						);
					}
					$nbadditems += count($additems) - 1;
				}
				$lastitem = $i;
			}

			$lastitem = 0;

			foreach ($items as $i => $item) {
				// migration method to use with Joomla 4 where $item->params is protected
				if (method_exists($item, 'getParams')) {
					$item->fparams = $item->getParams();
					try {
						$prop = new ReflectionProperty(get_class($item), 'params');
						if (! $prop->isProtected()) {
							$item->params = $item->fparams;
						}
					} catch (Exception $e) {
						// echo 'Exception reÃ§ue : ',  $e->getMessage(), "\n";
					}
					// B/C definition, and for other items not coming from the joomla menus
					} else {
						$item->fparams = $item->params;
					}

				$isdependant = $params->get('dependantitems', false) ? ($start > 1 && isset($item->tree[$start - 2]) && !in_array($item->tree[$start - 2], $path)) : false;
				$item->isthirdparty = (isset($item->isthirdparty) && $item->isthirdparty) ? true : false;
				$item->parent = false;

				if (isset($items[$lastitem]) && isset($item->parent_id) && $items[$lastitem]->id == $item->parent_id && $item->fparams->get('menu_show', 1) == 1)
				{
					$items[$lastitem]->parent = true;
				}

				if (! $item->isthirdparty && (($start && $start > $item->level) || ($end && $item->level > $end) || $isdependant)
				) {
					unset($items[$i]);
					continue;
				}

				// Exclude item with menu item option set to exclude from menu modules
				if (! $item->isthirdparty && (($item->fparams->get('menu_show', 1) == 0) || in_array($item->parent_id, $hidden_parents))
				)
				{
					$hidden_parents[] = $item->id;
					unset($items[$i]);
					continue;
				}

				$item->deeper = false;
				$item->shallower = false;
				$item->level_diff = 0;

				if (isset($items[$lastitem])) {
					$items[$lastitem]->deeper = ($item->level > $items[$lastitem]->level);
					$items[$lastitem]->shallower = ($item->level < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
				}
				if (isset($items[$lastitem]) && $items[$lastitem]->deeper && isset($item->colwidth) && $item->colwidth) {
					$items[$lastitem]->nextcolumnwidth = $item->colwidth;
				}

				// Test if this is the last item
				$item->is_end = !isset($items[$i + 1]);

//				 if (! $item->isthirdparty) $item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);
				$item->active = false;
				$item->current = false;
				$item->flink = $item->link;
				if (! $item->isthirdparty) $item->classe = '';

				switch ($item->type) {
					case 'separator':
							break;
					case 'heading':
						$item->classe .= ' headingck';
						// No further action needed.
						break;

					case 'url':
						if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
							// If this is an internal Joomla link, ensure the Itemid is set.
							$item->flink = $item->link . '&Itemid=' . $item->id;
						}
						$item->flink = \Joomla\CMS\Filter\OutputFilter::ampReplace(htmlspecialchars($item->flink));
						break;

					case 'thirdparty':
						break;

					case 'alias':
						// If this is an alias use the item id stored in the parameters to make the link.
						$item->flink = 'index.php?Itemid=' . $item->fparams->get('aliasoptions');
						break;

					default:
							if (! $item->isthirdparty) $item->flink = 'index.php?Itemid=' . $item->id;
						break;
				}

				if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
					$item->flink = \Joomla\CMS\Router\Route::_($item->flink, true, $item->fparams->get('secure'));
				} else {
					$item->flink = \Joomla\CMS\Router\Route::_($item->flink);
				}

				$item->anchor_css = htmlspecialchars($item->fparams->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
				$item->anchor_title = htmlspecialchars($item->fparams->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
				$item->menu_image = $item->fparams->get('menu_image', '') ? htmlspecialchars($item->fparams->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : (isset($item->menu_image) && $item->menu_image ? $item->menu_image : '');



				//  ---------------- begin the maximenu work on items --------------------

				$item->ftitle = htmlspecialchars(($item->title == null ? $item->ftitle : $item->title), ENT_COMPAT, 'UTF-8', false);
				$item->ftitle = \Joomla\CMS\Filter\OutputFilter::ampReplace($item->ftitle);
				$parentItem = new stdClass();
				
				if (isset($item->parent_id) && $item->parent_id) $parentItem = self::getParentItem($item->parent_id, $items);

				// ---- add some classes ----
				// add itemid class
				$item->classe .= ' item' . $item->id;
				// add current class
				if (isset($active) && $active->id == $item->id) {
					$item->classe .= ' current';
					$item->current = true;
				}
				// add active class
				if (is_array($path) &&
						( ($item->type == 'alias' && in_array($item->fparams->get('aliasoptions'), $path)) || in_array($item->id, $path))) {
					$item->classe .= ' active';
					$item->active = true;
				}
				// add the parent class
				if ($item->deeper) {
					$item->classe .= ' deeper';
				}

				// add last and first class
				$item->classe .= $item->is_end ? ' last' : '';
				$item->classe .= !isset($items[$i - 1]) ? ' first' : '';

				if (isset($items[$lastitem])) {
					if ($items[$lastitem]->parent && ($end == 0 || (int)$items[$lastitem]->level < (int)$end) && ! $items[$lastitem]->isthirdparty) {
						if ($params->get('layout', 'default') != '_:flatlist')
							$items[$lastitem]->classe .= ' parent';
					}
				
					$items[$lastitem]->classe .= $items[$lastitem]->shallower ? ' last' : '';
					$item->classe .= $items[$lastitem]->deeper ? ' first' : '';
					if (isset($items[$i + 1]) AND $item->level - $items[$i + 1]->level > 1 AND $parentItem) {
						$parentItem->classe = isset($parentItem->classe) ? $parentItem->classe . ' last' : 'last';
					}
				}

				// manage the class to show the item on desktop and mobile
				if ($item->fparams->get('maximenu_disablemobile') == '1') {
					$item->classe .= ' nomobileck';
				}

				// compatibility with Mobile Menu CK
				if ($item->fparams->get('mobilemenuck_enablemobile', '1') == '0') {
					$item->classe .= ' mobilemenuck-hide';
				}
				
				if ($item->fparams->get('maximenu_disabledesktop') == '1' || $item->fparams->get('mobilemenuck_enabledesktop', '1') == '0') {
					$item->classe .= ' nodesktopck';
				}


				// ---- manage params ----
				// -- manage column --
				$item->colwidth = $item->fparams->get('maximenu_colwidth', '180');
				$item->createnewrow = $item->fparams->get('maximenu_createnewrow', 0) || stristr($item->ftitle, '[newrow]');
				// check if there is a width for the subcontainer
				preg_match('/\[subwidth=([0-9]+)\]/', $item->ftitle, $subwidth);
				$subwidth = isset($subwidth[1]) ? $subwidth[1] : '';
				if ($subwidth)
					$item->ftitle = preg_replace('/\[subwidth=[0-9]+\]/', '', $item->ftitle);
				$item->submenucontainerwidth = $item->fparams->get('maximenu_submenucontainerwidth', '') ? $item->fparams->get('maximenu_submenucontainerwidth', '') : $subwidth;
				if ($item->fparams->get('maximenu_createcolumn', 0)) {
					$item->colonne = true;
					// add the value to give the total parent container width
					if (isset($parentItem->submenuswidth)) {
						if (! stristr($item->colwidth, '%') 
							&& $item->colwidth != 'auto'
							&& ! stristr($parentItem->submenuswidth, '%')
							&& $parentItem->submenuswidth != 'auto'
							) 
							$parentItem->submenuswidth = (float)($parentItem->submenuswidth) + (float)($item->colwidth);
					} else if (isset($parentItem) && $parentItem) {
						if (! stristr($item->colwidth, '%') 
							&& $item->colwidth != 'auto' 
							) 
							$parentItem->submenuswidth = (float)($item->colwidth);
					}
					// if specified by user with the plugin, then give the width to the parent container
					if (isset($items[$lastitem]) && $items[$lastitem]->deeper) {
						$items[$lastitem]->nextcolumnwidth = $item->colwidth;
					}
					$item->columnwidth = $item->colwidth;
				} elseif (preg_match('/\[col=([0-9]+)\]/', $item->ftitle, $resultat)) {
					$item->ftitle = str_replace('[newrow]', '', $item->ftitle);
					$item->ftitle = preg_replace('/\[col=[0-9]+\]/', '', $item->ftitle);
					$item->colonne = true;
					if (isset($parentItem->submenuswidth)) {
						if (! stristr($item->colwidth, '%') ) $parentItem->submenuswidth = (float)($parentItem->submenuswidth) + (float)($resultat[1]);
					} else {
						if (! stristr($item->colwidth, '%') ) $parentItem->submenuswidth = (float)($resultat[1]);
					}
					if (isset($items[$lastitem]) && $items[$lastitem]->deeper) {
						$items[$lastitem]->nextcolumnwidth = $resultat[1];
					}
					$item->columnwidth = $resultat[1];
				}
				if (isset($parentItem->submenucontainerwidth) AND $parentItem->submenucontainerwidth) {
					$parentItem->submenuswidth = $parentItem->submenucontainerwidth;
				}

				// -- manage module --
				$moduleid = $item->fparams->get('maximenu_module', '');
//				$style = $item->fparams->get('maximenu_forcemoduletitle', 0) ? 'html5' : '';
				$style = 'html5';
				if ($item->fparams->get('maximenu_insertmodule', 0)) {
					if (!isset($modules[$moduleid])) {
						$modules[$moduleid] = Helperfront::GenModuleById($moduleid, $params, $modulesList, $style, $item->level);
					}
					// for maximenu imbricated, use another css class
					$special_subclass = ($modulesList[$moduleid]->module == 'mod_maximenuck') ? '2' : '';
					$item->content = '<div class="maximenuck_mod' . $special_subclass . '">' . $modules[$moduleid] . '<div class="clr"></div></div>';
				} elseif (preg_match('/\[modid=([0-9]+)\]/', $item->ftitle, $resultat)) {
					// for maximenu imbricated, use another css class
					$special_subclass = ($modulesList[$resultat[1]]->module == 'mod_maximenuck') ? '2' : '';
					$item->ftitle = preg_replace('/\[modid=[0-9]+\]/', '', $item->ftitle);
					$item->content = '<div class="maximenuck_mod' . $special_subclass . '">' . Helperfront::GenModuleById($resultat[1], $params, $modulesList, $style, $item->level) . '<div class="clr"></div></div>';
				}

				// -- manage rel attribute --
				$item->rel = '';
				if ($rel = $item->fparams->get('maximenu_relattr', $item->fparams->get('menu-anchor_rel', ''))) {
					$item->rel = ' rel="' . $rel . '"';
				} elseif (preg_match('/\[rel=([a-z]+)\]/i', $item->ftitle, $resultat)) {
					$item->ftitle = preg_replace('/\[rel=[a-z]+\]/i', '', $item->ftitle);
					$item->rel = ' rel="' . $resultat[1] . '"';
				}

				// -- manage link description --
				$item->description = $item->fparams->get('maximenu_desc', '');
				if ($item->description) {
					$item->desc = $item->description;
				} else {
					$resultat = explode("||", $item->ftitle);
					if (isset($resultat[1])) {
						$item->desc = $resultat[1];
					} else {
						$item->desc = '';
					}
					$item->ftitle = $resultat[0];
				}

				// add the anchor tag and url suffix
				$item->flink .= $item->fparams->get('maximenu_urlsuffix', '') ? $item->fparams->get('maximenu_urlsuffix', '') : '';
				$item->flink .= $item->fparams->get('maximenu_anchor', '') ? '#' . $item->fparams->get('maximenu_anchor', '') : '';

				// add styles to the page for customization
				$menuID = $params->get('menuid', 'maximenuck');

				// get plugin parameters that are used directly in the layout
				$item->leftmargin = $item->fparams->get('maximenu_leftmargin', '');
				$item->topmargin = $item->fparams->get('maximenu_topmargin', '');
				$item->liclass = $item->fparams->get('maximenu_liclass', '');
				$item->colbgcolor = $item->fparams->get('maximenu_colbgcolor', '');
				$item->tagcoltitle = $item->fparams->get('maximenu_tagcoltitle', 'none');
				$item->submenucontainerheight = $item->fparams->get('maximenu_submenucontainerheight', '');
				$item->access_key = htmlspecialchars($item->fparams->get('maximenu_accesskey', ''), ENT_COMPAT, 'UTF-8', false);

				// get mobile plugin parameters that are used directly in the layout
				$item->mobile_data = '';
				$mobileicon = $item->fparams->get('maximenumobile_icon', $item->fparams->get('mobilemenuck_icon', ''));
				$item->mobile_data .= $mobileicon ? ' data-mobileicon="' . $mobileicon . '"' : '';
				$mobiletext = $item->fparams->get('maximenumobile_textreplacement', $item->fparams->get('mobilemenuck_textreplacement', ''));
				$item->mobile_data .= $mobiletext ? ' data-mobiletext="' . $mobiletext . '"' : '';

				// set the item styles if the plugin is enabled
				if (\Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'maximenuck')) {
					if ($params->get('doCompile') || $params->get('loadcompiledcss', '0') == '0') {
						$itemcss = self::injectItemCss($item, $menuID, $params);
						if ($itemcss) {
							if ($params->get('loadcompiledcss', '0') == '0') {
								$document->addStyleDeclaration($itemcss);
							} else {
								self::$_itemcss .= $itemcss;
							}
						}
					}
				}

				$lastitem = $i;
			} // end of boucle for each items

			// give the correct deep infos for the last item
			if (isset($items[$lastitem])) {
				$items[$lastitem]->deeper = (($start ? $start : 1) > $items[$lastitem]->level);
				$items[$lastitem]->shallower = (($start ? $start : 1) < $items[$lastitem]->level);
				$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ? $start : 1));
			}
//			$cache->store($items, $key);
//		}

		return $items;
	}

	/**
	 * Get a the parent item object
	 *
	 * @param Object $id The current item
	 * @param Array $items The list of all items
	 *
	 * @return object
	 */
	static function getParentItem($id, $items) {
		foreach ($items as $item) {
			if ($item->id == $id)
				return $item;
		}
		return new stdClass();
	}

	/**
	 * Get base menu item.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return   object
	 *
	 * @since	3.0.2
	 */
	public static function getBase($params)
	{
		// Get base menu item from parameters
		if ($params->get('base'))
		{
			$base = \Joomla\CMS\Factory::getApplication()->getMenu()->getItem($params->get('base'));
		}
		else
		{
			$base = false;
		}

		// Use active menu item if no base found
		if (!$base)
		{
			$base = self::getActive();
		}

		return $base;
	}

	/**
	 * Get active menu item.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  object
	 *
	 * @since	3.0.2
	 */
	public static function getActive()
	{
		$menu = \Joomla\CMS\Factory::getApplication()->getMenu();

		return $menu->getActive() ? $menu->getActive() : $menu->getDefault();
	}

	/**
	 * Get the css from the theme php file and write them into a css file.
	 *
	 * @param   string  $filetocompile  The path to the theme php file.
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  true on success
	 *
	 */
	public static function getCompiledCss($params) {
		/*
		$theme = $params->get('theme', 'default');
		$themeFile = JPATH_ROOT . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuck.php';
		$phpcss = '';
		if (file_exists($themeFile)) {
			$phpcss = file_get_contents($themeFile);
		}
		$menuID = $params->get('menuid', '');
		$css = str_replace('<?php echo $id; ?>', $menuID, $phpcss);
		$pattern = '/<\?php\s[^>]*[^>]*(.*)\?>/iUs';
		$replacement = '';
		$css = preg_replace($pattern, $replacement, $css);
		*/
		$css = '';
		// add the menu items css
		if (self::$_modulecss) {
			$css .= '

.clr {clear:both;visibility : hidden;}

/*---------------------------------------------
---	 Module styles from Maximenu Params     ---
----------------------------------------------*/
';
			$css .= str_replace(array(";", "{"), array(";\n\t", "{\n\t"), self::$_modulecss); // add new line and tab for reading purpose
		}

		// add the menu items css
		if (self::$_itemcss) {
			$css .= '
				
/*---------------------------------------------
---	 Menu items	styles from Maximenu Params ---
----------------------------------------------*/
';
			$css .= str_replace(array(";", "{"), array(";\n\t", "{\n\t"), self::$_itemcss); // add new line and tab for reading purpose
		}
		// $cssfile = dirname(__FILE__) . '/themes/custom/css/maximenuck_' . $menuID . '.css';
		// if (! \Joomla\CMS\Filesystem\Folder::exists(dirname(__FILE__) . '/themes/custom/css/')) {
			// \Joomla\CMS\Filesystem\Folder::create(dirname(__FILE__) . '/themes/custom/css/');
		// }
		// return \Joomla\CMS\Filesystem\File::write($cssfile, $css);

		return $css;
	}

	/**
	 * Create the css properties
	 *
	 * @return Array
	 */
	static function injectItemCss($item, $menuID, $params) {
		$start = (int) $params->get('startLevel');
		$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
		$itemlevel = $params->get('calledfromlevel','') ? $itemlevel + $params->get('calledfromlevel') - 1 : $itemlevel;
		$itemcss = '';
		$cssitemnormal = Style::createCss($menuID, $item->fparams, 'itemnormalstyles', true, $item->id);
		$cssitemhover = Style::createCss($menuID, $item->fparams, 'itemhoverstyles', true, $item->id);
		$cssitemactive = Style::createCss($menuID, $item->fparams, 'itemactivestyles', true, $item->id);
		$csssubmenu = Style::createCss($menuID, $item->fparams, 'submenustyles', true, $item->id);
		//$cssheading = Style::createCss($menuID, $item->fparams, 'headingstyles');

		$separator = ($item->type == 'separator' && !$item->fparams->get('maximenu_insertmodule', 0) && $itemlevel > 1) ? '.headingck > span.separator' : '';
		$document = \Joomla\CMS\Factory::getDocument();

		// for parent arrow normal state
		$itemnormalstylesparentarrowcolor = $item->fparams->get('itemnormalstylesparentarrowcolor', '') ? $item->fparams->get('itemnormalstylesparentarrowcolor', '') : $item->fparams->get('itemnormalstylesfontcolor', '');
		if ($item->fparams->get('itemnormalstylesparentarrowtype', '') == 'image') {
			$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . " > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . " > span.separator:after { "
					// . ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemnormalstylesparentarrowcolor . ";" : "border-top-color: " . $itemnormalstylesparentarrowcolor . ";" )
					. "border: none;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemnormalstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $item->fparams->get('itemnormalstylesparentitemimage', '') . ") !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentitemimagepositionx', '') != '' && $item->fparams->get('itemnormalstylesparentitemimagepositiony', '') != '') ? "background-position: " . $item->fparams->get('itemnormalstylesparentitemimagepositionx', '') . " " . $item->fparams->get('itemnormalstylesparentitemimagepositiony', '') . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $item->fparams->get('itemnormalstylesparentitemimagerepeat', '') . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowwidth', '') != '') ? "width: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowwidth', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowheight', '') != '') ? "height: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowheight', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionleft', '')) . " !important;" : "")
					. "} ";
		} else if ($item->fparams->get('itemnormalstylesparentarrowtype', '') == 'triangle' || $itemnormalstylesparentarrowcolor) {
			if ($itemnormalstylesparentarrowcolor) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . " > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . " > span.separator:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemnormalstylesparentarrowcolor . " !important;" : ( $itemlevel == 1 ? "border-top-color: " . $itemnormalstylesparentarrowcolor . " !important;" : "border-left-color: " . $itemnormalstylesparentarrowcolor . " !important;") )
					. "color: " . $itemnormalstylesparentarrowcolor . " !important;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemnormalstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemnormalstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemnormalstylesparentarrowpositionleft', '')) . " !important;" : "")
					
					. "} ";
			}
		}
		// for parent arrow hover state
		$itemhoverstylesparentarrowcolor = $item->fparams->get('itemhoverstylesparentarrowcolor', '') ? $item->fparams->get('itemhoverstylesparentarrowcolor', '') : $item->fparams->get('itemhoverstylesfontcolor', '');
		if ($item->fparams->get('itemhoverstylesparentarrowtype', '') == 'image') {
			$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ":hover > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ":hover > span.separator:after { "
					// . ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemhoverstylesparentarrowcolor . ";" : "border-top-color: " . $itemhoverstylesparentarrowcolor . ";" )
					. "border: none;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemhoverstylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $item->fparams->get('itemhoverstylesparentitemimage', '') . ") !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentitemimagepositionx', '') != '' && $item->fparams->get('itemhoverstylesparentitemimagepositiony', '') != '') ? "background-position: " . $item->fparams->get('itemhoverstylesparentitemimagepositionx', '') . " " . $item->fparams->get('itemhoverstylesparentitemimagepositiony', '') . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $item->fparams->get('itemhoverstylesparentitemimagerepeat', '') . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowwidth', '') != '') ? "width: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowwidth', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowheight', '') != '') ? "height: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowheight', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionleft', '')) . " !important;" : "")
					. "} ";
		} else if ($item->fparams->get('itemhoverstylesparentarrowtype', '') == 'triangle' || $itemhoverstylesparentarrowcolor) {
			if ($itemhoverstylesparentarrowcolor) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ":hover > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ":hover > span.separator:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemhoverstylesparentarrowcolor . " !important;" : ( $itemlevel == 1 ? "border-top-color: " . $itemhoverstylesparentarrowcolor . " !important;" : "border-left-color: " . $itemhoverstylesparentarrowcolor . " !important;") )
					. "color: " . $itemhoverstylesparentarrowcolor . " !important;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemhoverstylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemhoverstylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemhoverstylesparentarrowpositionleft', '')) . " !important;" : "")
					
					. "} ";
			}
		}
		// for parent arrow active state
		$itemactivestylesparentarrowcolor = $item->fparams->get('itemactivestylesparentarrowcolor', '') ? $item->fparams->get('itemactivestylesparentarrowcolor', '') : $item->fparams->get('itemactivestylesfontcolor', '');
		if ($item->fparams->get('itemactivestylesparentarrowtype', '') == 'image') {
			$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ".active > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ".active > span.separator:after { "
					// . ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemactivestylesparentarrowcolor . ";" : "border-top-color: " . $itemactivestylesparentarrowcolor . ";" )
					. "border: none;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemactivestylesparentitemimage', '') != '') ? "background-image: url(" . \Joomla\CMS\Uri\Uri::root(true) . "/" . $item->fparams->get('itemactivestylesparentitemimage', '') . ") !important;" : "")
					. (($item->fparams->get('itemactivestylesparentitemimagepositionx', '') != '' && $item->fparams->get('itemactivestylesparentitemimagepositiony', '') != '') ? "background-position: " . $item->fparams->get('itemactivestylesparentitemimagepositionx', '') . " " . $item->fparams->get('itemactivestylesparentitemimagepositiony', '') . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentitemimagerepeat', '') != '') ? "background-repeat: " . $item->fparams->get('itemactivestylesparentitemimagerepeat', '') . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowwidth', '') != '') ? "width: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowwidth', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowheight', '') != '') ? "height: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowheight', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionleft', '')) . " !important;" : "")
					. "} ";
		} else if ($item->fparams->get('itemactivestylesparentarrowtype', '') == 'triangle' || $itemactivestylesparentarrowcolor) {
			if ($itemactivestylesparentarrowcolor) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ".active > a:after, div#" . $menuID . " ul.maximenuck li.maximenuck.parent.item" . $item->id . ".active > span.separator:after { " 
					. ( $params->get('orientation', 'horizontal') === 'vertical'  ? "border-left-color: " . $itemactivestylesparentarrowcolor . " !important;" : ( $itemlevel == 1 ? "border-top-color: " . $itemactivestylesparentarrowcolor . " !important;" : "border-left-color: " . $itemactivestylesparentarrowcolor . " !important;") )
					. "color: " . $itemactivestylesparentarrowcolor . " !important;"
					. "display:block;"
					. "position:absolute;"
					. (($item->fparams->get('itemactivestylesparentarrowmargintop', '') != '') ? "margin-top: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmargintop', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginright', '') != '') ? "margin-right: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginright', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginbottom', '') != '') ? "margin-bottom: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowmarginleft', '') != '') ? "margin-left: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowmarginleft', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositiontop', '') != '') ? "top: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositiontop', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionright', '') != '') ? "right: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionright', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionbottom', '') != '') ? "bottom: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionbottom', '')) . " !important;" : "")
					. (($item->fparams->get('itemactivestylesparentarrowpositionleft', '') != '') ? "left: " . Helper::testUnit($item->fparams->get('itemactivestylesparentarrowpositionleft', '')) . " !important;" : "")
					
					. "} ";
			}
		}

		// normal item styles
		if (isset($cssitemnormal)) {
			if ($cssitemnormal['margin'] || $cssitemnormal['background'] || $cssitemnormal['gradient'] || $cssitemnormal['borderradius'] || $cssitemnormal['shadow'] || $cssitemnormal['border']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . $separator . ", 
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . $separator . "{ " . $cssitemnormal['margin'] . $cssitemnormal['background'] . $cssitemnormal['gradient'] . $cssitemnormal['borderradius'] . $cssitemnormal['shadow'] . $cssitemnormal['border'] . " } ";
			}
			if ($cssitemnormal['padding']) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > a,
div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > *:not(div) { " . $cssitemnormal['padding'] . " } ";
			}
			if ($cssitemnormal['fontcolor'] || $cssitemnormal['fontsize'] || $cssitemnormal['fontweight']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > a.maximenuck span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > span.separator span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > .nav-header span.titreck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > a.maximenuck span.titreck, div#" . $menuID . " li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > span.separator span.titreck, div#" . $menuID . " li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > .nav-header span.titreck { " . $cssitemnormal['fontcolor'] . $cssitemnormal['fontsize'] . $cssitemnormal['fontweight'] . " } ";
			}
			if ($cssitemnormal['descfontcolor'] || $cssitemnormal['descfontsize']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > a.maximenuck span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $item->level . ".headingck > span.separator span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > .nav-header span.descck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . " > a.maximenuck span.descck, div#" . $menuID . " li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > span.separator span.descck, div#" . $menuID . " li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".headingck > .nav-header span.descck { " . $cssitemnormal['descfontcolor'] . $cssitemnormal['descfontsize'] . " } ";
			}
		}

		// hover item styles
		if (isset($cssitemhover)) {
			if ($cssitemhover['margin'] || $cssitemhover['background'] || $cssitemhover['gradient'] || $cssitemhover['borderradius'] || $cssitemhover['shadow'] || $cssitemhover['border']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . $separator . ":hover,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . $separator . ":hover { " . $cssitemhover['margin'] . $cssitemhover['background'] . $cssitemhover['gradient'] . $cssitemhover['borderradius'] . $cssitemhover['shadow'] . $cssitemhover['border'] . " } ";
			}
			if ($cssitemhover['padding']) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > a,
div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span { " . $cssitemhover['padding'] . " } ";
			}
			if ($cssitemhover['fontcolor'] || $cssitemhover['fontsize'] || $cssitemhover['fontweight']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > a.maximenuck span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.separator span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.nav-header span.titreck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > a.maximenuck span.titreck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.separator span.titreck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.nav-header span.titreck { " . $cssitemhover['fontcolor'] . $cssitemhover['fontsize'] . $cssitemhover['fontweight'] . " } ";
			}
			if ($cssitemhover['descfontcolor'] || $cssitemhover['descfontsize']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > a.maximenuck span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.separator span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.nav-header span.descck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > a.maximenuck span.descck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.separator span.descck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ":hover > span.nav-header span.descck { " . $cssitemhover['descfontcolor'] . $cssitemhover['descfontsize'] . " } ";
			}
		}

		// active item styles
		if (isset($cssitemactive)) {
			if ($cssitemactive['margin'] || $cssitemactive['background'] || $cssitemactive['gradient'] || $cssitemactive['borderradius'] || $cssitemactive['shadow'] || $cssitemactive['border']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active" . $separator . ",
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active" . $separator . " { " . $cssitemactive['margin'] . $cssitemactive['background'] . $cssitemactive['gradient'] . $cssitemactive['borderradius'] . $cssitemactive['shadow'] . $cssitemactive['border'] . " } ";
			}
			if ($cssitemactive['padding']) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > a,
div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span { " . $cssitemactive['padding'] . " } ";
			}
			if ($cssitemactive['fontcolor'] || $cssitemactive['fontsize'] || $cssitemactive['fontweight']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > a.maximenuck span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.separator span.titreck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.nav-header span.titreck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > a.maximenuck span.titreck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.separator span.titreck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.nav-header span.titreck { " . $cssitemactive['fontcolor'] . $cssitemactive['fontsize'] . $cssitemactive['fontweight'] . " } ";
			}
			if ($cssitemactive['descfontcolor'] || $cssitemactive['descfontsize']
			) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > a.maximenuck span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.separator span.descck, div#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.nav-header span.descck,
div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > a.maximenuck span.descck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.separator span.descck, div#" . $menuID . " ul.maximenuck2 li.maximenuck.item" . $item->id . ".level" . $itemlevel . ".active > span.nav-header span.descck { " . $cssitemactive['descfontcolor'] . $cssitemactive['descfontsize'] . " } ";
			}
		}

		// submenu item styles
		if (isset($csssubmenu)) {
			if ($csssubmenu['padding'] || $csssubmenu['margin'] || $csssubmenu['background'] || $csssubmenu['gradient'] || $csssubmenu['borderradius'] || $csssubmenu['shadow'] || $csssubmenu['border']) {
				$itemcss .= "\ndiv#" . $menuID . " ul.maximenuck li.maximenuck.item" . $item->id . ".level" . $item->level . " > div.floatck,
div#" . $menuID . " .maxipushdownck div.floatck.submenuck" . $item->id . " { " . $csssubmenu['padding'] . $csssubmenu['margin'] . $csssubmenu['background'] . $csssubmenu['gradient'] . $csssubmenu['borderradius'] . $csssubmenu['shadow'] . $csssubmenu['border'] . " } ";
			}
		}

		return $itemcss;
	}
}
