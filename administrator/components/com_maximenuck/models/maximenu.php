<?php
// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Maximenuck\CKModel;
use Maximenuck\CKFof;
use Maximenuck\Helper;
use Joomla\CMS\Factory;

class MaximenuckModelMAximenu extends CKModel {

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
					
					
					$item->parent = false;
					if (isset($items[$lastitem]) && $items[$lastitem]->id == $item->parent_id && $item->params->get('menu_show', 1) == 1)
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
					if (($item->params->get('menu_show', 1) == 0) || in_array($item->parent_id, $hidden_parents))
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
					$item->anchor_css     = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title   = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_rel     = htmlspecialchars($item->params->get('menu-anchor_rel', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image     = $item->params->get('menu_image', '') ?
						htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
					$item->menu_image_css = htmlspecialchars($item->params->get('menu_image_css', ''), ENT_COMPAT, 'UTF-8', false);
				
					$item->children = array();
					$fitems[$item->id] = $item;
					if ($item->parent_id) {
						$fitems[$item->parent_id]->children[] = $item;
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
				// var_dump($fitem);
				if ($fitem->level == 1) $gitems[] = $this->recurseItem($fitem);
//				if ($item->parent_id) {
//					$fitems[$item->parent_id] = $fitem;
//				} else{
//					$fitems[$item->id] = $fitem;
//				}
//				echo '<div class="ck-menu-item" data-type="' . $item->datatype . '" data-level="' . $item->level . '" data-id="' . $item->id . '"  data-settings="' . $item->settings . '">';
//				echo '<div class="ck-menu-item-row"><span class="ck-menu-item-title">' . $item->title . '</span><span class="ck-menu-item-desc">' . $item->desc . '</span></div>';
//
//				// The next item is deeper.
//				if ($item->deeper) {
//					echo '<div class="ck-submenu" data-type="submenu"><div class="ck-columns"><div class="ck-column">';
//				}
//				// The next item is shallower.
//				else
//				if ($item->shallower) {
//					echo '<div class="ck-submenu" data-type="submenu"><div class="ck-columns"><div class="ck-column"></div></div></div>';
//					echo '</div>';
//					echo str_repeat('</div></div></div></div>', $item->level_diff);
//				}
//				// The next item is on the same level.
//				else {
//					echo '<div class="ck-submenu" data-type="submenu"><div class="ck-columns"><div class="ck-column"></div></div></div>';
//					echo '</div>';
//				}
			}
		return $gitems;
	}

	function recurseItem($fitem) {
		$gitem = array();
		$gitem['title'] = $fitem->title;
		$gitem['desc'] = '';
		$gitem['type'] = 'menuitem';
		$gitem['id'] = $fitem->id;
		$gitem['level'] = $fitem->level;
		$fitem->params->set('title', addslashes($fitem->title));
		$fitem->params->set('id', $fitem->id);
		$gitem['settings'] = Helper::encodeChars($fitem->params->toString());
		$gitem['submenu'] = array('params', 'columns');
		if (! empty($fitem->children)) {
			foreach ($fitem->children as $i => $child) {
				$gitem['submenu']['columns'][0]['children'][] = $this->recurseItem($child);
			}
		}

		return $gitem;
	}
}