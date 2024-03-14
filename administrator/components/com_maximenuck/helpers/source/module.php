<?php
// no direct access
defined('_JEXEC') or die;

use Maximenuck\CKModel;

class MaximenuckHelpersourceModule extends CKModel {

	public static function getItems() {
		$db = \Joomla\CMS\Factory::getDbo();
		$query = self::getListQuery();

		$items = $db->setQuery($query)->loadObjectList('id');
		self::translate($items);
		return $items;
	}

	
	/**
	 * Returns an object list
	 *
	 * @param   string The query
	 * @param   int    Offset
	 * @param   int    The number of records
	 * @return  array
	 */
	// protected function _getList($query, $limitstart = 0, $limit = 0)
	// {
		

		// $result = parent::_getList($query);
		// $this->translate($result);

		// return $result;
	// }

	/**
	 * Translate a list of objects
	 *
	 * @param   array The array of objects
	 * @return  array The array of translated objects
	 */
	protected static function translate(&$items)
	{
		$lang = \Joomla\CMS\Factory::getLanguage();
		// $client = $this->getState('filter.client_id') ? 'administrator' : 'site';
		$client = 'site';

		foreach ($items as $item)
		{
			$extension = $item->module;
			$source = constant('JPATH_' . strtoupper($client)) . "/modules/$extension";
			$lang->load("$extension.sys", constant('JPATH_' . strtoupper($client)), null, false, true)
				|| $lang->load("$extension.sys", $source, null, false, true);
			$item->name = \Joomla\CMS\Language\Text::_($item->name);
			if (is_null($item->pages))
			{
				$item->pages = \Joomla\CMS\Language\Text::_('JNONE');
			}
			elseif ($item->pages < 0)
			{
				$item->pages = \Joomla\CMS\Language\Text::_('COM_MODULES_ASSIGNED_VARIES_EXCEPT');
			}
			elseif ($item->pages > 0)
			{
				$item->pages = \Joomla\CMS\Language\Text::_('COM_MODULES_ASSIGNED_VARIES_ONLY');
			}
			else
			{
				$item->pages = \Joomla\CMS\Language\Text::_('JALL');
			}
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \Joomla\Data\DataObjectbaseQuery
	 */
	protected static function getListQuery()
	{
		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		// $query->select(
			// $this->getState(
				// 'list.select',
				// 'a.id, a.title, a.note, a.position, a.module, a.language,' .
					// 'a.checked_out, a.checked_out_time, a.published+2*(e.enabled-1) as published, a.access, a.ordering, a.publish_up, a.publish_down'
			// )
		// );
		$query->select('a.id, a.title, a.note, a.position, a.module, a.language, a.checked_out, a.checked_out_time, a.published+2*(e.enabled-1) as published, a.access, a.ordering, a.publish_up, a.publish_down');
		$query->from($db->quoteName('#__modules') . ' AS a');

		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the module menus
		$query->select('MIN(mm.menuid) AS pages')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = a.id');

		// Join over the extensions
		$query->select('e.name AS name')
			->join('LEFT', '#__extensions AS e ON e.element = a.module')
			->group(
				'a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out,' .
					'a.checked_out_time, a.published, a.access, a.ordering,l.title, uc.name, ag.title, e.name,' .
					'l.lang_code, uc.id, ag.id, mm.moduleid, e.element, a.publish_up, a.publish_down,e.enabled'
			);

		// Filter by current user access level.
		$user = \Joomla\CMS\Factory::getUser();

		// Get the current user for authorisation checks
		if ($user->authorise('core.admin') !== true)
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Filter by access level.
		// if ($access = $this->getState('filter.access'))
		// {
			// $query->where('a.access = ' . (int) $access);
		// }

		// Filter by published state
		// $state = $this->getState('filter.state');
		$state = 1;
		if (is_numeric($state))
		{
			$query->where('a.published = ' . (int) $state);
		}
		elseif ($state === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by position
		// $position = $this->getState('filter.position');
		// if ($position && $position != 'none')
		// {
			// $query->where('a.position = ' . $db->quote($position));
		// }

		// elseif ($position == 'none')
		// {
			// $query->where('a.position = ' . $db->quote(''));
		// }

		// Filter by module
		// $module = $this->getState('filter.module');
		// if ($module)
		// {
			// $query->where('a.module = ' . $db->quote($module));
		// }

		// Filter by client.
		// $clientId = $this->getState('filter.client_id');
		$clientId = 0;
		if (is_numeric($clientId))
		{
			$query->where('a.client_id = ' . (int) $clientId . ' AND e.client_id =' . (int) $clientId);
		}

		// Filter by search in title
		// $search = $this->getState('filter.search');
		// if (!empty($search))
		// {
			// if (stripos($search, 'id:') === 0)
			// {
				// $query->where('a.id = ' . (int) substr($search, 3));
			// }
			// else
			// {
				// $search = $db->quote('%' . $db->escape($search, true) . '%');
				// $query->where('(' . 'a.title LIKE ' . $search . ' OR a.note LIKE ' . $search . ')');
			// }
		// }

		// Filter on the language.
		// if ($language = $this->getState('filter.language'))
		// {
			// $query->where('a.language = ' . $db->quote($language));
		// }

		$query->order('a.title ASC');
		// $ordering = 'a.title';

		// $query->order($this->_db->quoteName($ordering) . ' ' . $this->getState('list.direction'));
		$query->order('a.ordering ASC');

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}
