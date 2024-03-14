<?php
Namespace Maximenuck;

// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKInput;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

require_once MAXIMENUCK_PATH . '/helpers/helper.php';

/**
 * Helper Class.
 */
class Helperfront {

	/**
	 * Create the list of all modules published as Object
	 *
	 * @return Array of Objects
	 */
	public static function CreateModulesList() {
		$query = "
			SELECT *
			FROM #__modules
			WHERE published=1
			ORDER BY id
			";
		$modulesList = CKFof::dbLoadObjectList($query, 'id');
		return $modulesList;
	}

	/**
	 * Render the module
	 *
	 * @param Int $moduleid The module ID to load
	 * @param \Joomla\Registry\Registry $params
	 * @param Array $modulesList The list of all module objects published
	 *
	 * @return string with HTML
	 */
	static function GenModuleById($moduleid, $params, $modulesList, $style, $level = '1') {
		$attribs['style'] = $style;

		$module = $modulesList[$moduleid];
		if (! $module) return '';

		// set the module param to know the calling level
		$paramstmp = new \Joomla\Registry\Registry;
		$paramstmp->loadString($module->params);
		$paramstmp->set('calledfromlevel', $level);
		$module->params = $paramstmp->toString();

		return \Joomla\CMS\Helper\ModuleHelper::renderModule($module, $attribs);
	}

	public static function convertItemFromV8ToV9($item) {
		$newitem = Helper::initItem();
		$newitem->image = null;
		$newitem->link = $item->flink;
		$newitem->title = $item->ftitle;
		$newitem->desc = $item->description;
		$newitem->settings = null;
		$newitem->type = 'menuitem';
		$newitem->level = $item->level;
		$newitem->id = $item->id;
		$newitem->settings = '';
		$newitem->params = new \Joomla\Registry\Registry();
		$newitem->params->set('thirdparty', '0');
		
		return $newitem;
	}

	/**
	 * Create the list of all modules published as Object
	 *
	 * @return Array of Objects
	 */
	public static function htmlTemplateItem($item) {
		if (is_array($item)) $item = CKFof::convertArrayToObject ($item);
		if (! isset($item->params)) {
			$item->params = Helper::decodeChars($item->settings);
			$item->params = new \Joomla\Registry\Registry($item->params);
		}

		if ($item->type == 'module') {
			$attribs['style'] = 'none';
			$module = CKFof::dbLoad('#__modules', $item->id);
			echo \Joomla\CMS\Helper\ModuleHelper::renderModule($module, $attribs);
		} else if ($item->params->get('thirdparty') == '1') {
			if ( !\Joomla\CMS\Plugin\PluginHelper::isEnabled('maximenuck', $item->type)) {
				return '';
			}

			\Joomla\CMS\Plugin\PluginHelper::importPlugin( 'maximenuck' );
			$otheritems = CKFof::triggerEvent( 'onMaximenuckRenderItem' .  ucfirst($item->type) , array($item));

			ob_start();
			if (count($otheritems) == 1) {
				// load only the first instance found, because each plugin type must be unique
				// add override feature here, look in the template
				$template = \Joomla\CMS\Factory::getApplication()->getTemplate();
				$overridefile = JPATH_ROOT . '/templates/' . $template . '/html/maximenuck/' . strtolower($item->type) . '.php';
				// var_dump($overridefile);die;
				if (file_exists($overridefile)) {
				// die('ok');
					$item = $e;
					include_once $overridefile;
				} else {
					// normal use
				$html = $otheritems[0];
				}
				echo $html;
			} else {
				echo '<p style="text-align:center;color:red;font-size:14px;">ERROR - MAXIMENU CK DEBUG : ELEMENT TYPE INSTANCE : ' . $item->type . '. Number of instances found : ' . count($otheritems) . '</p>';
			}
			$element_code = ob_get_clean();
			echo $element_code;
		} else {
				echo '<div class="ck-menu-item" data-type="' . $item->type . '" data-level="' . $item->level . '" data-id="' . $item->id . '"  data-settings="' . Helper::encodeChars($item->settings) . '">';
					echo '<div class="ck-menu-item-row">'
							. '<span class="ck-menu-item-title">' . $item->title . '</span>'
							. '<span class="ck-menu-item-desc">' . $item->desc . '</span>'
						. '</div>';
			if (! empty($item->submenu->columns)) {
						echo '<div class="ck-submenu" data-type="submenu">';
							echo '<div class="ck-columns">';

							if (! empty($item->submenu->columns)) {
								foreach ($item->submenu->columns as $column) {
									if ($column->break == 1) {
										echo '<div class="ck-column-break"></div>';
									} else {
										echo '<div class="ck-column">';
										if (! empty($column->children)) {
											foreach ($column->children as $child) {
												self::htmlTemplateItem($child);
											}
										}
									echo '</div>';
									}
								}
							} else {
								echo '<div class="ck-column"></div>';
							}
							echo '</div>';
						echo '</div>'; // close submenu
			}
				echo '</div>'; // close item
		}
	}

	public static function getHtmlItem($item) {
		if (is_array($item)) $item = CKFof::convertArrayToObject ($item);

		$html = '<li data-level="' . $item->level . '" data-type="' . $item->type . '" class="maximenuck item' . $item->id . ' level' . $item->level . '"><a class="maximenuck" href="' . $item->link . '"><span class="titreck">' . $item->title . '</span></a></li>';
		return $html;
	}

	public static function getCompiledCss($params) {
		$theme = $params->get('theme', 'default');
		$themeFile = JPATH_ROOT . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuck.php';
		$phpcss = '';
		if (file_exists($themeFile)) {
			$phpcss = file_get_contents($themeFile);
		}
		if (! $phpcss) return '';

		$menuID = $params->get('menuid', '');
		$css = str_replace('<?php echo $id; ?>', $menuID, $phpcss);
		$pattern = '/<\?php\s[^>]*[^>]*(.*)\?>/iUs';
		$replacement = '';
		$css = preg_replace($pattern, $replacement, $css);

		return $css;
	}

	public static function initItem() {
		$item = new \stdClass();
		$item->params = new \Joomla\Registry\Registry();
		$item->fparams = new \Joomla\Registry\Registry();
		$item->deeper = false;
		$item->shallower = false;
		$item->level_diff = 0;
		$item->isthirdparty = false;
		$item->is_end = false;
		$item->classe = '';
		$item->desc = '';
		$item->colwidth = '';
		$item->tagcoltitle = 'none';
		$item->tagclass = '';
		$item->leftmargin = '';
		$item->topmargin = '';
		$item->submenuwidth = '';
		$item->liclass = '';
		$item->anchor_css = '';
		$item->anchor_title = '';
		$item->colbgcolor = '';
		$item->menu_image = '';
		$item->type = '';
		$item->content = '';
		$item->rel = '';
		$item->link = '';
		$item->title = '';
		$item->parent_id = '';

		// special for the thirdparty plugins
		$item->isthirdparty = true;
		$item->type = 'thirdparty';

		return $item;
	}
}
