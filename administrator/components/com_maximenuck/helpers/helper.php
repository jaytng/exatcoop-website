<?php
Namespace Maximenuck;

// No direct access
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/defines.php';
require_once JPATH_ADMINISTRATOR . '/components/com_maximenuck/helpers/style.php';

use Maximenuck\CKInput;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Style;

/**
 * Helper Class.
 */
class Helper {

	static $cssreplacements;

	public static function addSidebar($vName = '') {
		if (MAXIMENUCK_ISJ4) return;

		$input = CKFof::getInput();
		if (!$vName) $vName = $input->get('view', 'modules', 'cmd');

		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_MODULES'), MAXIMENUCK_ADMIN_URL . '&view=modules', $vName == 'modules'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_JOOMLA_MENUS'), MAXIMENUCK_ADMIN_URL . '&view=joomlamenus', $vName == 'joomlamenus'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_STYLES'), MAXIMENUCK_ADMIN_URL . '&view=styles', $vName == 'styles'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
					CKText::_('CK_MENU_BUILDER'), MAXIMENUCK_ADMIN_URL . '&view=menubuilders', $vName == 'menubuilders'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_ABOUT'), MAXIMENUCK_ADMIN_URL . '&view=about', $vName == 'about'
		);
		echo '<style>.ckadminarea {
	float: left;
	width: calc(100% - 230px);

}
.ckadminsidebar {
	float: left;
	width: 220px;
	margin-right: 10px;
}
@media only screen and (max-width:640px) {
	.ckadminsidebar, .ckadminarea {
		float: none; width: inherit;
	}
}
</style>
<div class="ckadminsidebar">' . \Joomla\CMS\HTML\Helpers\Sidebar::render() . '</div>';
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCkbox() {
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework', true);
		CKFof::addStyleSheet(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.css');
		CKFof::addScript(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.js');
	}

	public static function loadCkboxInline() {
		CKFof::addStyleSheetInline(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.css');
		CKFof::addScriptInline(MAXIMENUCK_MEDIA_URI . '/assets/ckbox.js');
	}

	public static function htmlTemplateItem($item) {
		if (is_array($item)) $item = CKFof::convertArrayToObject ($item);

		if (stristr($item->type, 'autoload.')) {
			$item->titleClass = ' ckbadge ckbadge-success';
			$item->desc = '';
		} else {
			$item->titleClass = '';
		}

		$itemImage = isset($item->image) && $item->image !== '' ? '<span class="ck-menu-item-img"><img src="' . $item->image . '"></span>' : '';
//		echo '<div class="ck-menu-item" data-type="' . $item->type . '" data-level="' . $item->level . '" data-id="' . $item->id . '"  data-settings="' . self::encodeChars($item->settings) . '" data-customid="' . $item->customid . '">';
		echo '<div class="ck-menu-item" data-type="' . $item->type . '" data-level="' . $item->level . '" data-customid="' . $item->customid . '" data-state="' . $item->state . '" data-settings="' . self::encodeChars($item->settings) . '">';
			echo '<div class="ck-menu-item-row">'
					. ($item->type === 'image' ? '<span class="ck-menu-item-img"><img src="' . $item->title . '"></span>' :
						$itemImage . '<span class="ck-menu-item-title' . $item->titleClass . '">' . $item->title . '</span>')
					. '<span class="ck-menu-item-desc">' . $item->desc . '</span>'
				. '</div>';

			if (strpos($item->type, 'autoload.') === false) {
				$submenudata = '';
				if (isset($item->submenu->params)) {
					$submenudata .= ' data-width="' . (isset($item->submenu->params->width) ? $item->submenu->params->width : '') . '"';
					$submenudata .= ' data-height="' . (isset($item->submenu->params->height) ? $item->submenu->params->height : '') . '"';
					$submenudata .= ' data-left="' . (isset($item->submenu->params->left) ? $item->submenu->params->left : '') . '"';
					$submenudata .= ' data-top="' . (isset($item->submenu->params->top) ? $item->submenu->params->top : '') . '"';
					$submenudata .= ' data-fullwidth="' . (isset($item->submenu->params->fullwidth) ? $item->submenu->params->fullwidth : '') . '"';
					$submenudata .= ' data-tab="' . (isset($item->submenu->params->tab) ? $item->submenu->params->tab : '') . '"';
					$submenudata .= ' data-tabwidth="' . (isset($item->submenu->params->tabwidth) ? $item->submenu->params->tabwidth : '') . '"';
				}
				echo '<div class="ck-submenu" data-type="submenu" ' . $submenudata . '>';
					echo '<div class="ck-columns">';

					if (! empty($item->submenu->columns)) {
						foreach ($item->submenu->columns as $column) {
							if (isset($column->break) && $column->break == 1) {
								echo '<div class="ck-column-break"></div>';
							} else {
								echo '<div class="ck-column" data-width="' . (isset($column->width) ? $column->width : '') . '">';
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

	public static function getCustomId() {
		$ID = (int) (microtime(true) * 1000000); // pour ID
		return $ID;
	}

	public static function decodeChars($a) {
		$search = array('|quot|'
			, '|qq|'
			, '|ob|'
			, '|cb|'
			, '|tt|'
			, '|rr|'
			, '|dd|'
			);
		$replace = array('"'
			, '"'
			, '{'
			, '}'
			, "\t"
			, "\n"
			, "#"
			);
		return str_replace($search, $replace, $a);
	}

	public static function encodeChars($a) {
		$search = array('"'
			, '"'
			, '{'
			, '}'
			, "\t"
			, "\n"
			);
		$replace = array('|quot|'
			, '|qq|'
			, '|ob|'
			, '|cb|'
			, '|tt|'
			, '|rr|'
			);
		return str_replace($search, $replace, $a);
	}

	public static function decodeCharsAfterJson($a) {
		$search = array('|quot|'
			, '|qq2|'
			, '|ob2|'
			, '|cb2|'
			, '|tt2|'
			, '|rr2|'
			, '|dd2|'
			);
		$replace = array('"'
			, '&quot;'
			, '{'
			, '}'
			, "\t"
			, "\n"
			, "#"
			);
		return str_replace($search, $replace, $a);
	}

	/*
	 * Make empty slide object
	 */
	public static function initItem() {
		$item = new \stdClass();
		$item->image = null;
		$item->link = null;
		$item->title = null;
		$item->text = null;
		$item->desc = null;
		$item->rel = null;
		$item->more = array();
		$item->settings = null;
		$item->type = 'menuitem';
		$item->target = 'default';
		$item->level = null;
		$item->is_end = null;
		$item->id = null;
		$item->colwidth = '';
		$item->tagcoltitle = 'none';
		$item->tagclass = '';
		$item->liclass = '';
		$item->anchor_title = '';
		$item->menu_image = '';
		$item->classe = '';
		$item->anchor_css = '';
		$item->colbgcolor = '';
		$item->leftmargin = '';
		$item->topmargin = '';
		$item->submenuwidth = '';
		$item->deeper = '';
		$item->shallower = '';
		$item->level_diff = 0;
		$item->fparams = new \Joomla\Registry\Registry();

		return $item;
	}

	/**
	 * Get the name of the style
	 */
	public static function getStyleNameById($id) {
		if (! $id) return '';

		$result = CKFof::dbLoadResult('SELECT name from #__maximenuck_styles WHERE id = "' . $id . '" AND (state IN (0, 1))');

		return $result;
	}

	/**
	 * Get the name of the menu
	 */
	public static function getJoomlaMenuNameById($menutype) {
		if (! $menutype) return '';

		$result = CKFof::dbLoadResult('SELECT title from #__menu_types WHERE menutype = "' . $menutype . '"');

		return $result;
	}

	/**
	 * Get the list of the menus
	 */
	public static function getJoomlaMenus() {
		$result = CKFof::dbLoadObjectList('SELECT id,title,menutype from #__menu_types');

		return $result;
	}

	public static function checkDbIntegrity() {
//		self::searchColumn('stylecode', 'longtext');
		self::searchTable('maximenuck_menus');
		self::searchTable('maximenuck_styles');
		self::searchTable('maximenuck_menubuilder_item');
		self::searchColumn('checked_out', 'varchar(10)', 'maximenuck_menus');
	}

	private static function searchColumn($name, $type = 'text', $table = 'maximenuck_styles') {
		$db = CKFof::getDbo();
		// test if the widget columns not exists
		$query = "SHOW COLUMNS FROM #__" . $table . " LIKE '" . $name . "'";
		$db->setQuery($query);
		if ($db->execute()) {
			if ( $db->loadResult()) {
				//echo 'existe deja!';return;
			} else {
				// add the SQL field to the main table
				$db->setQuery('ALTER TABLE `#__' . $table . '` ADD `' . $name . '` ' . $type . ' NOT NULL;');
				if (!$db->execute()) {
					echo '<p class="alert alert-danger">Error during table column ' . $name . ' update process !</p>';
				} else {
					echo '<p class="alert alert-success">Table column ' . $name . ' updated !</p>';
				}
			}
		} else {
			echo 'SQL error - Check existing ' . $name . ' column';
			return false;
		}
	}

	/**
	 * Look if the table exists, if not then create it
	 * 
	 * @param type $tableName
	 */
	private static function searchTable($tableName) {
		$db = CKFof::getDbo();
		$tablesList = $db->getTableList();
		$tableExists = in_array($db->getPrefix() . $tableName, $tablesList);
		// test if the table not exists

		if (! $tableExists) {
			self::createTable($tableName);
		}
	}

	private static function createTable($tableName) {
		switch ($tableName) {
			case 'maximenuck_styles' :
				$query = "CREATE TABLE IF NOT EXISTS `#__maximenuck_styles` (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `name` text NOT NULL,
	  `state` int(10) NOT NULL DEFAULT '1',
	  `params` longtext NOT NULL,
	  `layoutcss` text NOT NULL,
	  `customcss` text NOT NULL,
	  `checked_out` varchar(10) NOT NULL,
	  PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
				break;
			case 'maximenuck_menus' :
				$query = "CREATE TABLE IF NOT EXISTS `#__maximenuck_menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `params` longtext NOT NULL,
  `layouthtml` longtext NOT NULL,
  `layoutcss` text NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;";
				break;
			case 'maximenuck_menubuilder_item' :
				$query = "CREATE TABLE IF NOT EXISTS `#__maximenuck_menubuilder_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `styles` longtext NOT NULL,
  `params` longtext NOT NULL,
  `customid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;";
				break;
			default :
				break;
		} 

		$db = CKFof::getDbo();
		$db->setQuery($query);
		if (! $db->execute($query)) {
			echo '<p class="alert alert-danger">Error during table ' . $tableName . ' creation process !</p>';
		} else {
			echo '<p class="alert alert-success">Table ' . $tableName . ' created with success !</p>';
		}
	}

	/**
	 * Get the CSS of the style
	 * @id - the style ID
	 */
	public static function getStyleLayoutcss($id) {
		if (! $id) return '';

		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.layoutcss');
		$query->from($db->quoteName('#__maximenuck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$result = $db->loadResult();

		self::makeCssReplacement($result);

		return $result;
	}

	public static function makeCssReplacement(&$css) {
		$cssreplacements = Style::getCssReplacement();
		foreach ($cssreplacements as $tag => $rep) {
			$css = str_replace($tag, $rep, $css);
		}
//		return $css;
	}

	/** 
	* Custom function loaded from the module options to check the version compatibility
	*/
	public static function isV9($value = '0', $moduleId = 0) {
		// module already exists ?
		$moduleId = \Joomla\CMS\Factory::getApplication()->input->get('id', $moduleId, 'int');

		if ($moduleId > 0) {
			$module = CKFof::dbLoad('#__modules', $moduleId);
			$moduleParams = new \Joomla\Registry\Registry;
			$moduleParams->loadString($module->params);
			$value = $moduleParams->get('isv9', '1');
		}

		// module saved with V9
		$isV9 = $value == '1' ? '1' : ($moduleId > 0 ? '0' : '1'); 

		return $isV9;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value) {
		if (
			(stristr($value, 'px')) 
			OR (stristr($value, 'em')) 
			OR (stristr($value, 'rem')) 
			OR (stristr($value, '%')) 
			OR (stristr($value, 'vh')) 
			OR (stristr($value, 'vw')) 
			OR (stristr($value, 'vmin')) 
			OR (stristr($value, 'vmax')) 
			OR (stristr($value, 'mm')) 
			OR (stristr($value, 'in')) 
			OR (stristr($value, 'pt')) 
			OR (stristr($value, 'pc')) 
			OR $value == 'auto'
			)
			return $value;

		if ($value == '') {
			$value = 0;
		}

		return $value . 'px';
	}

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $opacity) {
		if ($opacity > 1) $opacity = $opacity/100;
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . $opacity . ")";

		return $rgbacolor;
	}

	public static function getProMessage() {
		$html = '<div class="ckinfo"><i class="fas fa-info"></i><a href="https://www.joomlack.fr/en/joomla-extensions/maximenu-ck" target="_blank">' . CKText::_('MAXIMENUCK_ONLY_PRO') . '</a></div>';
	
		return $html;
	}

	/**
	 * Get the name of the menu
	 */
	public static function getMenuBuilderNameById($id) {
		if (! $id) return '';

		$result = CKFof::dbLoadResult('SELECT name from #__maximenuck_menus WHERE id = "' . $id . '"');

		return $result;
	}

	public static function utf8_encode($string) {
		return iconv('ISO-8859-1', 'UTF-8', $string);
	}

	public static function utf8_decode($string) {
		return iconv('UTF-8', 'ISO-8859-1', $string);
	}
}
