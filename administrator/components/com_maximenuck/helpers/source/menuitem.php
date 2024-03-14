<?php
// no direct access
defined('_JEXEC') or die;

class MaximenuckHelpersourceMenuitem {

	public static function getItems() {
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true)
					->select($db->qn(array('menutype', 'title')))
					->from($db->qn('#__menu_types'));
//					->where($db->qn('menutype') . ' = ' . $db->q($menuType));

		$menus = $db->setQuery($query)->loadObjectList();
		return $menus;
	}

	public static function getChildrenItems($menutype, $parentId) {
		\Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_SITE . '/administrator/components/com_menus/models', 'MenusModel');
		// Get an instance of the generic menus model
		$items = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Items', 'MenusModel', array('ignore_request' => true));
		if (! $parentId) $items->setState('filter.level', '1');
		$items->setState('filter.menutype', $menutype);
		$items->setState('filter.parent_id', $parentId);

		return $items->getItems();
	}

	public static function ajaxShowMenuItems() {

		$parentId = \Joomla\CMS\Factory::getApplication()->input->get('parentid', 0, 'int');
		$menutype = \Joomla\CMS\Factory::getApplication()->input->get('menutype', '', 'string');
		$returnFunc = \Joomla\CMS\Factory::getApplication()->input->get('returnFunc', 'ckAppendNewItem', 'string');

		$items = self::getChildrenItems($menutype, $parentId);

		$imagespath = MAXIMENUCK_MEDIA_URI .'/images/';
		?>
		<div class="cksubfolder">
		<?php
		foreach ($items as $item) {
			$aliasId = $item->id;
			if ($item->type == 'alias') {
				$itemParams = new \Joomla\Registry\Registry($item->params);
				$aliasId = $itemParams->get('aliasoptions', 0);
			}
			$Itemid = substr($item->link,-7,7) == 'Itemid=' ? $aliasId : '&Itemid=' . $aliasId;
		?>
			<div class="ckfoldertree parent">
				<div class="ckfoldertreetoggler <?php if ($item->rgt - $item->lft <= 1) { echo 'empty'; } ?>" onclick="ckToggleTreeSub(this, <?php echo $item->id ?>)" data-menutype="<?php echo $item->menutype; ?>"></div>
				<div class="ckfoldertreename hasTip" title="<?php echo $item->link . $Itemid ?>" onclick="window.parent.<?php echo $returnFunc ?>('menuitem', '<?php echo $item->id ?>', '<?php echo $item->title ?>', '')"><i class="fas fa-link"></i><?php echo $item->title; ?></div>
			</div>
		<?php
		}
		?>
		</div>
		<?php
		exit;
	}
}
