<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKModel;
use Maximenuck\CKFof;

class MaximenuckModelJoomlamenu extends CKModel {

	public function __construct($config = array()) {

		parent::__construct($config);
	}

	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array  &$pks   A list of the primary keys to change.
	 * @param   integer     $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function publishItem(&$pks, $value = 1)
	{
		$pks		= (array) $pks;

		// Default menu item existence checks.
		if ($value != 1)
		{
			foreach ($pks as $i => $pk)
			{
				$table = CKFof::dbLoad('#__menu', $pk);
				if ($table->id && $table->home && $table->language == '*')
				{
					// Prune items that you can't change.
					// JError::raiseWarning(403, \Joomla\CMS\Language\Text::_('JLIB_DATABASE_ERROR_MENU_UNPUBLISH_DEFAULT_HOME'));
					unset($pks[$i]);
					break;
				}
			}
		}

		// Ensure that previous checks doesn't empty the array
		if (empty($pks))
		{
			return true;
		}

		$ids = array();
		foreach ($pks as $i => $pk)
		{
			$tree = $this->getTree($pk);
			foreach ($tree as $i => $id)
			{
				$table = CKFof::dbLoad('#__menu', $id);
				$table->published = $value;
				if (! CKFof::dbStore('#__menu', $table)) return false;
				$ids[] = $id;
			}
		}

		return $ids;
	}

	public function getTree($id) {
		// $menuModel = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Menu', '\Joomla\CMS\Table\Table', array('ignore_request' => true));
		$menuTable = \Joomla\CMS\Table\Table::getInstance('Menu', '\Joomla\CMS\Table\Table');
		$items = $menuTable->getTree($id);
		$a = array();
		foreach($items as $item) {
			$a[] = (string) $item->id;
		}

		return $a;
	}

	/**
	 * Finds the default menu type.
	 *
	 * In the absence of better information, this is the first menu ordered by title.
	 *
	 * @return  string	The default menu type
	 * @since   1.6
	 */
	protected function getDefaultMenuType()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true)
			->select('menutype')
			->from('#__menu_types')
			->order('title');
		$db->setQuery($query, 0, 1);
		$menuType = $db->loadResult();

		return $menuType;
	}

	public function getItems() {
		// Create a new query object.
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);
		$user	= \Joomla\CMS\Factory::getUser();
		$app	= \Joomla\CMS\Factory::getApplication();

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__menu` AS a');

		// Filter by search in title
		$search = $this->getState('filter_search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' )');
			}
		}

		// Join over the users.
		$query->select('u.name AS editor');
		$query->join('LEFT', $db->quoteName('#__users').' AS u ON u.id = a.checked_out');

		// Exclude the root category.
		$query->where('a.id > 1');
		$query->where('a.client_id = 0');
		$query->where('(a.published IN (0, 1))');

		// Filter the items over the menu id if set.
		$menuType = $app->input->get('menutype');
		if (!empty($menuType))
		{
			$query->where('a.menutype = '.$db->quote($menuType));
		}


		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
		}

		// Add the list ordering clause.
		$query->order($db->escape('a.lft').' '.$db->escape('ASC'));

		// $limitstart = $this->state->get('limitstart');
		// $limit = $this->state->get('limit');
		$db->setQuery($query);

		$items = $db->loadObjectList();

		$items = $this->arrangeItems($items);

		return $items;
	}
	
	public function arrangeItems($items)
	{
		$app = \Joomla\CMS\Factory::getApplication();
		$menu = $app->getMenu();

		$start   = 1;
		$end     = 0;
		$showAll = 1;

			$lastitem = 0;

			if ($items)
			{
				foreach ($items as $i => $item)
				{

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

				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start?$start:1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start?$start:1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start?$start:1));
				}
			}
		return $items;
	}

	public function checkin($id) {
		JLoader::register('\Joomla\CMS\Table\Module', JPATH_PLATFORM . '/joomla/database/table/menu.php');
		$table = \Joomla\CMS\Table\Table::getInstance('Menu', '\Joomla\CMS\Table\Table');
		$status = $table->checkin($id);

		return $status;
	}

	/**
	 * Method to save the item title
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function saveTitle($id, $title)
	{
		// load the item
		$row = CKFof::dbLoad('#__menu', (int) $id);

		if ($row)
		{
			$row->title = htmlspecialchars($title);
			$result = CKFof::dbStore('#__menu', $row);
		} else {
			$result = false;
		}

		return $result;
	}

	/**
	 * Method to save a \Joomla\Registry\Registry param
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function saveParam($id, $param, $value)
	{
		// load the item
		$row = CKFof::dbLoad('#__menu', (int) $id);

		if ($row) {
			$row->params = new \Joomla\Registry\Registry($row->params);
			
			if ($param == 'maximenu_liclass') {
				$value = $this->setLiclass($row->params->get('maximenu_liclass'), 'fullwidth');
			} else if ($param == 'maximenu_tab' && $value['maximenucktab'] === '1') {
				$row->params->set('maximenu_tabwidth', $value['maximenu_tabwidth']);
				$value = $this->addLiclass($row->params->get('maximenu_liclass'), 'maximenucktab');
				$param = 'maximenu_liclass';
			} else if ($param == 'maximenu_tab' && ! $value['maximenucktab']) {
				$row->params->set('maximenu_tabwidth', '');
				$value = $this->removeLiclass($row->params->get('maximenu_liclass'), 'maximenucktab');
				$param = 'maximenu_liclass';
			}
			
			// set the new params
			$row->params->set($param, $value);
			$row->params = $row->params->toString();

			$result = CKFof::dbStore('#__menu', $row);
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Method to update the css class
	 *
	 * @access	public
	 * @return	string the new value
	 */
	private function setLiclass($liclass, $class) {
		if (stristr($liclass, $class)) {
			$value = str_replace($class, "", (string)$liclass);
		} else {
			$value = $liclass . " " . $class;
		}

		$value = trim($value);
		return $value;
	}

	/**
	 * Method to add the css class
	 *
	 * @access	public
	 * @return	string the new value
	 */
	private function addLiclass($liclass, $class) {
		if ($liclass && stristr((string)$liclass, (string)$class)) return $liclass;

		$value = $liclass . " " . $class;

		$value = trim($value);
		return $value;
	}

	/**
	 * Method to rmove the css class
	 *
	 * @access	public
	 * @return	string the new value
	 */
	private function removeLiclass($liclass, $class) {
		$value = str_replace($class, "", (string)$liclass);

		$value = trim($value);
		return $value;
	}

	public function validateItemPath($path, $id) {
		$path = implode("/", $path);

		$query = "SELECT id FROM #__menu WHERE path = '" . $path . "'";
		try
		{
			$newid = CKFof::dbLoadResult($query);
			if ($newid == $id) return true;
		}
		catch (RuntimeException $e)
		{
			return false;
		}
		if (! $newid) return true;
		return false;
	}

	/**
	 * Method to save the item level
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function saveLevel($id, $level, $parentid)
	{
		// load the item
		$row = CKFof::dbLoad('#__menu', (int) $id);

		if ($row)
		{
			$row->level = (int) $level;
			$row->parent_id = (int) $parentid;
			$result = CKFof::dbStore('#__menu', $row);
		} else {
			$result = false;
		}

		return $result;
	}

	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array  $idArray	id's of rows to be reordered
	 * @param   array  $lft_array	lft values of rows to be reordered
	 *
	 * @return  boolean false on failuer or error, true otherwise
	 * @since   1.6
	 */
	public function saveOrder($idArray = null, $order_array = null, $lft_array = null, $rgt_array = null)
	{
		// Get an instance of the table object.
		JLoader::register('\Joomla\CMS\Table\Module', JPATH_PLATFORM . '/joomla/database/table/menu.php');
		$table = \Joomla\CMS\Table\Table::getInstance('Menu', '\Joomla\CMS\Table\Table');
		$db = \Joomla\CMS\Factory::getDbo();
		
		try
		{
			$query = $db->getQuery(true);

			// Validate arguments
			if (is_array($idArray) && is_array($lft_array) && count($idArray) == count($lft_array))
			{
				for ($i = 0, $count = count($idArray); $i < $count; $i++)
				{
					// Do an update to change the lft values in the table for each id
					$query->clear()
						->update('#__menu')
						->where('id = ' . (int) $idArray[$i])
						->set('lft = ' . (int) $lft_array[$i]);

					$db->setQuery($query)->execute();
				}

				if ($table->rebuild()) 
					return true;
			}
			else
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			$this->_unlock();
			// throw $e;
			return false;
		}
	}
}
