<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKModel;
use Maximenuck\CKFof;

class MaximenuckModelMenubuilders extends CKModel {

	protected $context = 'maximenuck.menus';

	protected $table = '#__maximenuck_menus';

	private static $prefix;

	private static $name;

	public function __construct($config = array()) {

		parent::__construct($config);
	}

	protected function populateState()
	{
		parent::populateState();
		$config = \Joomla\CMS\Factory::getConfig();
		$state = CKFof::getUserState(self::$prefix . '.' . self::$name, null);

		// first request, or custom user request
		if ($state === null || $this->input->get('state_request', 0, 'int') === 1) {
			$this->state->set('categories', $this->input->get('categories', 0));
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	array of items
	 */
	public function getItems() {
		// Create a new query object.
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__maximenuck_menus` AS a');

		// Filter by search in title
		$search = $this->getState('filter_search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.name LIKE ' . $search . ' )');
			}
		}

		// filter by state if available
		$state = $this->getState('filter_state');
		if (! empty($state)) $query->where('a.state = ' . $state);
		// Do not list the trashed items
		$query->where('a.state > -1');

		// Add the list ordering clause.
		$orderCol = $this->state->get('filter_order');
		$orderDirn = $this->state->get('filter_order_Dir');
		if ($orderCol && $orderDirn) {
			$query->order($orderCol . ' ' . $orderDirn);
		}

		$limitstart = $this->state->get('limitstart');
		$limit = $this->state->get('limit');
		$db->setQuery($query, $limitstart, $limit);

		$items = $db->loadObjectList();

		// automatically get the total number of items from the query
		$total = $this->getTotal($query);
		$this->state->set('limit_total', (empty($total) ? 0 : (int)$total));

		return $items;
	}
}
