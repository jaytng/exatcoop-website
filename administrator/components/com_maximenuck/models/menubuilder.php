<?php
// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Maximenuck\CKModel;
use Maximenuck\CKFof;
use Maximenuck\Helper;
use Joomla\CMS\Factory;

class MaximenuckModelMenubuilder extends CKModel {

	protected $table = '#__maximenuck_menus';

	var $item = null;

	function __construct() {
		parent::__construct();
	}

	public function save($row) {
		$id = CKFof::dbStore($this->table, $row);

		return $id;
	}

	public function getItem() {
		if (empty($this->item)) {
			$id = $this->input->get('id', 0, 'int');
			$this->item = CKFof::dbLoad($this->table, $id);

			if ($id === 0 && $this->input->get('startwith') === 'joomla') {
				$this->item->layouthtml = $this->getItemsFromMenu($this->input->get('joomlamenu'));
			} else {
				$this->item->layouthtml = unserialize($this->item->layouthtml);
			}
		}

		return $this->item;
	}

	public function getJoomlaMenus() {
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true)
					->select($db->qn(array('menutype', 'title')))
					->from($db->qn('#__menu_types'));
//					->where($db->qn('menutype') . ' = ' . $db->q($menuType));

		$menus = $db->setQuery($query)->loadObjectList();
		return $menus;
	}

	public function getItemsFromMenu($menutype) {
		$app = Factory::getApplication();
		$menu = $app->getMenu('site');
		$items = $menu->getItems('menutype', $menutype);
//		$path           = $base->tree;
		$start          = 1;
		$end            = 0;
		$showAll        = 1;
		$hidden_parents = array();
		$lastitem       = 0;
		$fitems = array();
		if ($items)
			{
				foreach ($items as $i => $item)
				{
					
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
	
					$item->parent = false;
					if (isset($items[$lastitem]) && $items[$lastitem]->id == $item->parent_id && $item->fparams->get('menu_show', 1) == 1)
					{
						$items[$lastitem]->parent = true;
					}

					if (($start && $start > $item->level)
						|| ($end && $item->level > $end)
//						|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
//						|| ($start > 1 && !in_array($item->tree[$start - 2], $path))
						)
					{
						unset($items[$i]);
						continue;
					}

					// Exclude item with menu item option set to exclude from menu modules
					if (($item->fparams->get('menu_show', 1) == 0) || in_array($item->parent_id, $hidden_parents))
					{
						$hidden_parents[] = $item->id;
						unset($items[$i]);
						continue;
					}

					$item->deeper     = false;
					$item->shallower  = false;
					$item->level_diff = 0;

					if (isset($items[$lastitem]))
					{
						$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
						$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
						$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
					}

					$lastitem     = $i;
					$item->active = false;
					$item->flink  = $item->link;

					// We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
					// when the cause of that is found the argument should be removed
					$item->title          = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
					$item->anchor_css     = htmlspecialchars($item->fparams->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title   = htmlspecialchars($item->fparams->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_rel     = htmlspecialchars($item->fparams->get('menu-anchor_rel', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image     = $item->fparams->get('menu_image', '') ?
						htmlspecialchars($item->fparams->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
					$item->menu_image_css = htmlspecialchars($item->fparams->get('menu_image_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->settings = '|ob||qq|type|qq|:|qq|menuitem|qq|,|qq|id|qq|:|qq|' . $item->id . '|qq|,|qq|title|qq|:|qq|' . $item->title . '|qq||cb|';
//					$item->state ='1';
	
					$item->children = array();
					$fitems[$item->id] = $item;
					if ($item->parent_id) {
						if (isset($fitems[$item->parent_id]->children)) $fitems[$item->parent_id]->children[] = $item;
					}
				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start ?: 1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start ?: 1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ?: 1));
				}
			}

			$gitems = array();
			foreach ($fitems as $i => $fitem) {
				if ($fitem->level == 1) $gitems[] = $this->recurseItem($fitem);
			}

		return $gitems;
	}

	function recurseItem($fitem) {
		$gitem = array();
		// setup an ID for the maximenu item
		$gitem['customid'] = Helper::getCustomId();
		// $gitem['id'] = $fitem->id;

		$gitem['title'] = $fitem->title;
		$gitem['desc'] = '';
		$gitem['type'] = 'menuitem';
		$gitem['level'] = $fitem->level;
		$gitem['state'] = '1';
		$fitem->fparams->set('title', addslashes($fitem->title));
		$fitem->fparams->set('id', $fitem->id);
//		$gitem['settings'] = Helper::encodeChars($fitem->fparams->toString());
		$gitem['settings'] = '|ob||qq|type|qq|:|qq|menuitem|qq|,|qq|id|qq|:|qq|' . $fitem->id . '|qq|,|qq|title|qq|:|qq|' . $fitem->title . '|qq||cb|';
		$gitem['submenu'] = array('params', 'columns');
		if (! empty($fitem->children)) {
			foreach ($fitem->children as $i => $child) {
				$gitem['submenu']['columns'][0]['children'][] = $this->recurseItem($child);
			}
		}

		return $gitem;
	}

	public function saveItem($row) {
		$id = CKFof::dbStore('#__maximenuck_menubuilder_item', $row);
		return $id;
	}

	public function getMenubuilderItem($customid) {
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__maximenuck_menubuilder_item'))
					->where($db->qn('customid') . ' = ' . $db->q($customid));

		$row = $db->setQuery($query)->loadObject();
		if (! $row) {
			$row = CKFof::dbLoad('#__maximenuck_menubuilder_item', 0);
			$row->id = 0;
		}
		return $row;
	}

	public function remove($row) {
		$result = CKFof::dbDelete('#__maximenuck_menubuilder_item', $row->id);

		return $result;
	}
}