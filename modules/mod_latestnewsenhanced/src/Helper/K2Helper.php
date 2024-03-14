<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace SYW\Module\LatestNewsEnhanced\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\ParameterType;
use Joomla\Database\Exception\ExecutionFailureException;
use Joomla\Utilities\ArrayHelper;
use SYW\Library\Cache as SYWCache;
use SYW\Library\Text as SYWText;
use SYW\Library\Utilities as SYWUtilities;
use SYW\Library\Version as SYWVersion;

require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
require_once (JPATH_SITE.'/components/com_k2/helpers/permissions.php');
require_once (JPATH_SITE.'/components/com_k2/models/itemlist.php');

class K2Helper
{
	/**
	 *
	 * @param object $params
	 * @param array $items
	 * @throws \Exception
	 * @return array of categories (id, description, article count)
	 */
	static function getCategoryList($params, $items)
	{
		$categories = array();

		// get all categories and how many articles are in them
		foreach ($items as $item) {
			if (array_key_exists($item->catid, $categories)) {
				$categories[$item->catid]++;
			} else {
				$categories[$item->catid] = 1;
			}
		}

		if ($params->get('show_cat_description', 0)) { // need description

			$db = Factory::getDbo();

			$query = $db->getQuery(true);

			$query->select($db->quoteName('id'));
			$query->select($db->quoteName('description'));
			$query->from($db->quoteName('#__k2_categories'));
			$query->whereIn($db->quoteName('id'), array_keys($categories));

			$db->setQuery($query);

			try {
				$categories_list = $db->loadObjectList('id');
			} catch (ExecutionFailureException $e) {
			    Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
				return null;
			}

			foreach ($categories_list as $category) {
				$category->count = $categories[$category->id];
			}
		} else {
			$categories_list = array();

			foreach ($categories as $key => $value) {
				$categories_list[$key] = (Object) array('id' => $key, 'count' => $value);
			}
		}

		return $categories_list;
	}

	static function getList($params, $module)
	{
	    $app = Factory::getApplication();

		$db = Factory::getDbo();

		$user = $app->getIdentity();
		$view_levels = $user->getAuthorisedViewLevels();

		$nowDate = $db->quote(Factory::getDate()->toSql());

		$jinput = $app->input;
		$option = $jinput->get('option');
		$view = $jinput->get('view');

		if (!$params->get('show_on_item_page', 1)) {
			if ($option === 'com_k2' && $view === 'item') {
				return null;
			}
		}

		$item_on_page_id = '';
		$item_on_page_tagids = array();
		$item_on_page_keys = array();

		$related = (string) $params->get('related', '0'); // 0: no, 1: keywords, 2: tags articles only, 3: tags any content

		if ($related == '1') { // related by keyword

			if ($option === 'com_k2' && $view === 'item') {
				$temp = $jinput->getString('id');
				$temp = explode(':', $temp);
				$item_on_page_id = $temp[0];
			}

			if ($item_on_page_id) {
			    
			    $query = $db->getQuery(true);

				$query->select($db->quoteName('metakey'));
				$query->from($db->quoteName('#__k2_items'));
				$query->where($db->quoteName('id') . ' = :itemOnPageId');
				$query->bind(':itemOnPageId', $item_on_page_id, ParameterType::INTEGER);

				$db->setQuery($query);

				try {
					$result = $db->loadResult();
				} catch (ExecutionFailureException $e) {
					$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
					return null;
				}

				$result = trim($result);
				if (empty($result)) {
					return array(); // won't find a related item if no key is present
				}

				$keys = explode(',', $result);

				// assemble any non-blank word(s)
				foreach ($keys as $key) {
					$key = trim($key);
					if ($key) {
						$item_on_page_keys[] = $key;
					}
				}

				if (empty($item_on_page_keys)) {
					return array();
				}
			} else {
				return null; // no result (was not on item page)
			}

		} else if ($related == '2' || $related == '3') { // related by tag. '3' does not apply to K2

			if ($option !== 'com_k2' || $view !== 'item') {
				return null; // no result (was not on item page)
			}

			$temp = $jinput->getString('id');
			$temp = explode(':', $temp);
			$item_on_page_id = $temp[0];

			// get tags of k2 item on the page
			
			$query = $db->getQuery(true);

			$query->select($db->quoteName('tag.id'));
			$query->from($db->quoteName('#__k2_tags', 'tag'));
			$query->join('LEFT', $db->quoteName('#__k2_tags_xref', 'xref'), $db->quoteName('tag.id') . ' = ' . $db->quoteName('xref.tagID'));
			$query->where($db->quoteName('tag.published') . ' = 1');
			$query->where($db->quoteName('xref.itemID') . ' = :itemOnPageId');
			$query->bind(':itemOnPageId', $item_on_page_id, ParameterType::INTEGER);

			$db->setQuery($query);

			try {
				$item_on_page_tagids = $db->loadColumn();
			} catch (ExecutionFailureException $e) {
				$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
				return null;
			}

			if (empty($item_on_page_tagids)) {
				return array(); // no result because no tag found for the object on the page
			}
		}

		// START OF DATABASE QUERY
		
		$query = $db->getQuery(true);

		$subquery1 = ' CASE WHEN ';
		$subquery1 .= $query->charLength('a.alias');
		$subquery1 .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$subquery1 .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$subquery1 .= ' ELSE ';
		$subquery1 .= $a_id.' END AS slug';

		$subquery2 = ' CASE WHEN ';
		$subquery2 .= $query->charLength('c.alias');
		$subquery2 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$subquery2 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$subquery2 .= ' ELSE ';
		$subquery2 .= $c_id.' END AS cat_slug';

		$query->select($db->quoteName(array('a.id', 'a.catid', 'a.title', 'a.alias', 'a.introtext', 'a.fulltext', 'a.params', 'a.metadata', 'a.metakey', 'a.metadesc', 'a.access', 'a.hits', 'a.featured', 'a.language')));
		$query->select($db->quoteName('a.published', 'state'));

		$query->select('CASE WHEN ' . $db->quoteName('a.fulltext') . ' IS NULL OR ' . $db->quoteName('a.fulltext') . ' = ' . $db->quote('') . ' THEN 0 ELSE 1 END AS ' . $db->quoteName('fulltexthascontent'));

		$query->select($db->quoteName(array('a.checked_out', 'a.checked_out_time', 'a.created', 'a.created_by', 'a.created_by_alias')));

		// Use created if modified is 0
		$query->select('CASE WHEN ' . $query->isNullDatetime('a.modified') . ' THEN ' . $db->quoteName('a.created') . ' ELSE ' . $db->quoteName('a.modified') . ' END AS ' . $db->quoteName('modified'));
		$query->select($db->quoteName(array('a.modified_by', 'uam.name'), array('modified_by', 'modified_by_name')));

		// Use created if publish_up is 0
		$query->select('CASE WHEN ' . $query->isNullDatetime('a.publish_up') . ' THEN ' . $db->quoteName('a.created') . ' ELSE ' . $db->quoteName('a.publish_up') . ' END AS  ' . $db->quoteName('publish_up'));
		$query->select($db->quoteName('a.publish_down'));

		$query->select($subquery1);
		$query->select($subquery2);

		$query->from($db->quoteName('#__k2_items', 'a'));

		// join over the categories
		$query->select($db->quoteName(array('c.name', 'c.access', 'c.alias'), array('category_title', 'category_access', 'category_alias'))); // TODO check: was cat_alias originally: error ?
		$query->join('LEFT', $db->quoteName('#__k2_categories', 'c'), $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'));

		$query->where($db->quoteName('a.trash') . ' = 0');
		$query->where($db->quoteName('c.published') . ' = 1 AND ' . $db->quoteName('c.trash') . ' = 0');

		// join over the users for the author and modified_by names
		switch ($params->get('show_a', 'alias')) {
		    case 'full': $query->select($db->quoteName('ua.name', 'author')); break;
		    case 'user': $query->select($db->quoteName('ua.username', 'author')); break;
		    default: $query->select('CASE WHEN ' . $db->quoteName('a.created_by_alias') . ' > ' . $db->quote(' ') . ' THEN ' . $db->quoteName('a.created_by_alias') . ' ELSE ' . $db->quoteName('ua.name') . ' END AS ' . $db->quoteName('author'));
		}

		$query->select($db->quoteName('ua.email', 'author_email'));

		$query->join('LEFT', $db->quoteName('#__users', 'ua'), $db->quoteName('ua.id') . ' = ' . $db->quoteName('a.created_by'));
		$query->join('LEFT', $db->quoteName('#__users', 'uam'), $db->quoteName('uam.id') . ' = ' . $db->quoteName('a.modified_by'));

		// access filter

		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));

		$show_unauthorized_items = false; // no option to show unauthorized items in the free version

		if (!$show_unauthorized_items) { // show authorized items only
			$query->whereIn($db->quoteName('a.access'), $view_levels);
			$query->whereIn($db->quoteName('c.access'), $view_levels);
		}

		// filter by start and end dates

		$postdate = $params->get('post_d', 'published');

		if ($postdate != 'fin_pen' && $postdate != 'pending') {
		    $query->where('(' . $query->isNullDatetime('a.publish_up') . ' OR ' . $db->quoteName('a.publish_up') . ' <= ' . $nowDate . ')');
		}

		if ($postdate == 'pending') {
			$query->where($db->quoteName('a.publish_up') . ' > ' . $nowDate);
		}
		$query->where('(' . $query->isNullDatetime('a.publish_down') . ' OR ' . $db->quoteName('a.publish_down') . ' >= ' . $nowDate . ')');

		// filter by date range

		switch ($postdate)
		{
			case 'created' : $dateField = 'a.created'; break;
			case 'modified' : $dateField = 'a.modified'; break;
			case 'finished' : case 'fin_pen' : /*case 'pending' :*/ $dateField = 'a.publish_down'; break;
			default: $dateField = 'a.publish_up';
		}

		$query->select($db->quoteName($dateField, 'date'));

		switch ($params->get('use_range', 0))
		{
		    case 1: // relative

		        // parameters are reversed (backward compatibility from early on version)

		        $range_from = $params->get('range_to', 'week'); // now, day, week, month, year options
		        $spread_from = $params->get('spread_to', 1);
		        $range_to = $params->get('range_from', 'now');
		        $spread_to = $params->get('spread_from', 1);

		        // test range 'from' and 'to' to see if it will be a future or a past range

		        $from = 0;
		        switch($range_from)
		        {
		            case 'day': $from += $spread_from; break;
		            case 'week': $from += $spread_from * 7; break;
		            case 'month': $from += $spread_from * 30; break; // arbitrary
		            case 'year': $from += $spread_from * 365; break; // arbitrary
		        }

		        $to = 0;
		        switch($range_to)
		        {
		            case 'day': $to += $spread_to; break;
		            case 'week': $to += $spread_to * 7; break;
		            case 'month': $to += $spread_to * 30; break; // arbitrary
		            case 'year': $to += $spread_to * 365; break; // arbitrary
		        }

		        if ($from < 0 && $to < 0 && $from <= $to) {
		        	// dates in the past (-3 to -2 months for instance)
		            $query->where($db->quoteName($dateField) . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . abs($spread_from) . ' ' . $range_from . ')');
		            $query->where($db->quoteName($dateField) . ' <= DATE_SUB(' . $nowDate . ', INTERVAL ' . abs($spread_to) . ' ' . $range_to . ')');
		        }

		        if ($from < 0 && $to == 0) {
		        	// dates in the past (the last 2 months for instance)
		            $query->where($db->quoteName($dateField) . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . abs($spread_from) . ' ' . $range_from . ')');
		            $query->where($db->quoteName($dateField) . ' <= ' . $nowDate);
		        }

		        if ($from < 0 && $to > 0) {
		        	// dates in the past and in the future (the last month to the next 2 months for instance)
		            $query->where($db->quoteName($dateField) . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . abs($spread_from) . ' ' . $range_from . ')');
		            $query->where($db->quoteName($dateField) . ' <= DATE_ADD(' . $nowDate . ', INTERVAL ' . $spread_to . ' ' . $range_to . ')');
		        }

		        if ($from >= 0 && $to >= 0) {
		        	if ($from > $to) {
		        		// past dates
		        	    $query->where($db->quoteName($dateField) . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . $spread_from . ' ' . $range_from . ')');
		        		if ($to == 0) {
		        		    $query->where($db->quoteName($dateField) . ' <= ' . $nowDate);
		        		} else {
		        		    $query->where($db->quoteName($dateField) . ' <= DATE_SUB(' . $nowDate . ', INTERVAL ' . $spread_to . ' ' . $range_to . ')');
		        		}
		        	} elseif ($from < $to) {
		        		// future dates
		        	    $query->where($db->quoteName($dateField) . ' <= DATE_ADD(' . $nowDate . ', INTERVAL ' . $spread_to . ' ' . $range_to . ')');
		        		if ($from == 0) {
		        		    $query->where($db->quoteName($dateField) . ' >= ' . $nowDate);
		        		} else {
		        		    $query->where($db->quoteName($dateField) . ' >= DATE_ADD(' . $nowDate . ', INTERVAL ' . $spread_from . ' ' . $range_from . ')');
		        		}
		        	} else {
		        		// $from and $to are equal
		        		if ($to == 0) {
		        		    $query->where($db->quoteName($dateField) . ' = ' . $nowDate);
		        		} else {
		        		    $query->where($db->quoteName($dateField) . ' = DATE_ADD(' . $nowDate . ', INTERVAL ' . $spread_from . ' ' . $range_from . ')');
		        		}
		        	}
		        }
			break;

			case 2: // range
				$startDateRange = $db->quote($params->get('start_date_range', $db->getNullDate()));
				$endDateRange = $db->quote($params->get('end_date_range', $db->getNullDate()));
				$query->where('(' . $db->quoteName($dateField) . ' >= ' . $startDateRange . ' AND ' . $db->quoteName($dateField) . ' <= ' . $endDateRange . ')');
			break;
		}

		// category filter

		$categories_array = $params->get('k2catid', array());

		$array_of_category_values = array_count_values($categories_array);
		if (isset($array_of_category_values['all']) && $array_of_category_values['all'] > 0) { // 'all' was selected therefore no filtering
			// take everything, so no category selection
		} else {
			if (isset($array_of_category_values['auto']) && $array_of_category_values['auto'] > 0) { // 'auto' was selected

				$categories_array = array();

				if ($option === 'com_k2') {
					switch($view)
					{
						case 'itemlist':
							if ($jinput->getInt('id')) { // id missing when on a 'categories' menu item where any categories can be selected
								$categories_array[] = $jinput->getInt('id');
							}
						break;
						case 'item':
							$item_id = $jinput->getInt('id');
							$catid = $jinput->getInt('catid');

							if (!$catid) {
								$subquery = $db->getQuery(true);

								$subquery->select($db->quoteName('catid'));
								$subquery->from($db->quoteName('#__k2_items'));
								$subquery->where($db->quoteName('id') . ' = :itemId');
								$subquery->bind(':itemId', $item_id, ParameterType::INTEGER);

								$db->setQuery($subquery);

								try {
								    $result = trim($db->loadResult());
								} catch (ExecutionFailureException $e) {
								    $app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
								    return null;
								}

								$categories_array[] = $result;
							} else {
								$categories_array[] = $catid;
							}
						break;
					}
				}

				if (empty($categories_array)) {
					return null; // no result if not in the category page
				}
			}

			if (!empty($categories_array)) {

			    $categories_ids_array = array();
			    foreach ($categories_array as $category_id) {
			        $categories_ids_array[$category_id] = array($category_id);
			    }

				// sub-category inclusion
				$get_sub_categories = $params->get('includesubcategories', 'no');
				if ($get_sub_categories != 'no') {

					$sub_categories_array = array();

					if ($get_sub_categories == 'all') {

					    foreach ($categories_array as $category_id) {
					        $sub_categories_array[$category_id] = self::getCategoryChildren($category_id, -1, !$show_unauthorized_items);
				            $categories_ids_array[$category_id] = array_merge($categories_ids_array[$category_id], $sub_categories_array[$category_id]);
					    }

					} else {

					    $levels = $params->get('levelsubcategories', 1);

					    foreach ($categories_array as $category_id) {
					        $sub_categories_array[$category_id] = self::getCategoryChildren($category_id, $levels, !$show_unauthorized_items);
				            $categories_ids_array[$category_id] = array_merge($categories_ids_array[$category_id], $sub_categories_array[$category_id]);
						}
					}

					foreach ($sub_categories_array as $subcategory) {
					    $categories_array = array_merge($categories_array, $subcategory);
					}
				}

				$test_type = $params->get('cat_inex', 1) ? 'IN' : 'NOT IN';
				$query->where($db->quoteName('a.catid') . ' ' . $test_type . ' (' . implode(',', $categories_array) . ')');
			}
		}

		// metakeys filter

		$metakeys = array();
		$keys = array_filter(explode(',', trim($params->get('keys', ''), ' ,')));

		// assemble any non-blank word(s)
		foreach ($keys as $key) {
			$metakeys[] = trim($key);
		}

		if (!empty($item_on_page_keys)) {
			if (!empty($metakeys)) { // if none of the tags we filter are in the content item on the page, return nothing

				$keys_in_common = array_intersect($item_on_page_keys, $metakeys);
				if (empty($keys_in_common)) {
					return array();
				}

				$metakeys = $keys_in_common;

			} else {
				$metakeys = $item_on_page_keys;
			}
		}

		if (!empty($metakeys)) {
			$concat_string = $query->concatenate(array('","', ' REPLACE(a.metakey, ", ", ",")', ' ","')); // remove single space after commas in keywords
			
			//$query->where('('.$concat_string.' LIKE "%'.implode('%" OR '.$concat_string.' LIKE "%', $metakeys).'%")');
			
			$query_meta_array = array();
			foreach ($metakeys as $key) {
			    $query_meta_array[] = $concat_string . ' LIKE ' . $db->quote('%' . $db->escape($key, true) . '%');
			}
			
			$query->where('(' . implode(' OR ', $query_meta_array) . ')');
		}

		// tags filter

		$tags = $params->get('k2tags', array());

		if (!empty($tags)) {

			// if all selected, get all available tags
			$array_of_tag_values = array_count_values($tags);
			if (isset($array_of_tag_values['all']) && $array_of_tag_values['all'] > 0) { // 'all' was selected

				// get all tags

				$subquery = $db->getQuery(true);

				$subquery->select($db->quoteName('id'));
				$subquery->from($db->quoteName('#__k2_tags'));
				$subquery->where($db->quoteName('published') . ' = 1');

				$db->setQuery($subquery);

				try {
					$tags = $db->loadColumn();
				} catch (ExecutionFailureException $e) {
					$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
					return null;
				}

				if (empty($tags) && $params->get('tags_inex', 1)) { // won't return any k2 item if no k2 item has been associated to any tag (when include tags only)
					return array();
				}
			}
		}

		if (!empty($item_on_page_tagids)) {
			if (!empty($tags)) { // if none of the tags we filter are in the content item on the page, return nothing

				// take the tags common to the item on the page and the module selected tags
				$tags_in_common = array_intersect($item_on_page_tagids, $tags);
				if (empty($tags_in_common)) {
					return array();
				}

				if ($params->get('tags_match', 'any') == 'all') {
					if (count($tags_in_common) != count($tags)) {
						return array();
					}
				}

				$tags = $tags_in_common;

			} else {
				$tags = $item_on_page_tagids;
			}

			// Note: does not work if 'exclude' tags, which is normal
		}

		if (!empty($tags)) {

			$tags_to_match = implode(',', $tags);

			$query->select('COUNT(' . $db->quoteName('tags.id') . ') AS tags_count');
			$query->join('LEFT', $db->quoteName('#__k2_tags_xref', 'tags_xref'), $db->quoteName('tags_xref.itemID') . ' = ' . $db->quoteName('a.id'));
			$query->join('LEFT', $db->quoteName('#__k2_tags', 'tags'), $db->quoteName('tags_xref.tagID') . ' = ' . $db->quoteName('tags.id'));
			// no access in database table
			$query->where($db->quoteName('tags.published') . ' = 1');

			// keep all items with tags to be handled outside the query (when exclude all)
			if (!$params->get('tags_inex', 1) && $params->get('tags_match', 'any') == 'all') {
				// keep all tags
			} else {
				$test_type = $params->get('tags_inex', 1) ? 'IN' : 'NOT IN';
				$query->where($db->quoteName('tags.id') . ' ' . $test_type . ' (' . $tags_to_match . ')');
			}

			if (!$params->get('tags_inex', 1) && $params->get('tags_match', 'any') == 'all') {
				// handled outside the query
			} else {
				if (!$params->get('tags_inex', 1)) { // EXCLUDE TAGS
					$query->select('tags_per_items.tag_count_per_item');
				    
					$subquery = $db->getQuery(true);

					// subquery gets all the tags for all items
					$subquery->select($db->quoteName('ttags_xref.itemID', 'content_id'));
					$subquery->select('COUNT(' . $db->quoteName('tt.id') . ') AS tag_count_per_item');
					$subquery->from($db->quoteName('#__k2_tags_xref', 'ttags_xref'));
					$subquery->join('LEFT', $db->quoteName('#__k2_tags', 'tt'), $db->quoteName('ttags_xref.tagID') . ' = ' . $db->quoteName('tt.id'));
					$subquery->where($db->quoteName('tt.published') . ' = 1');
					$subquery->group($db->quoteName('content_id'));

					$query->join('INNER', '(' . (string) $subquery . ') AS tags_per_items', $db->quoteName('tags_per_items.content_id') . ' = ' . $db->quoteName('a.id'));

					// we keep items that have the same amount of tags before and after removals
					$query->having('COUNT(' . $db->quoteName('tags.id') . ') = ' . $db->quoteName('tags_per_items.tag_count_per_item'));
				} else { // INCLUDE TAGS
					if ($params->get('tags_match', 'any') == 'all') {
						$query->having('COUNT(' . $db->quoteName('tags.id') . ') = ' . count($tags));
					}
				}
			}

			$query->group($db->quoteName('a.id'));
		}

		// user filter

		$include = $params->get('author_inex', 1);
		$authors_array = $params->get('k2_created_by', array());

		// old parameter - backward compatibility
		$old_authors = $params->get('user_id', '');
		if ($old_authors) {
			switch ($old_authors)
			{
				case 'by_me': $include = true; $authors_array[] = 'auto'; break;
				case 'not_me': $include = false; $authors_array[] = 'auto'; break;
				case 'all': default: $authors_array[] = 'all';
			}
		}

		$where_state = 'a.published = 1';
		$where_createdby = '';

		$array_of_authors_values = array_count_values($authors_array);
		if (isset($array_of_authors_values['all']) && $array_of_authors_values['all'] > 0) { // 'all' was selected
		    $test_type = $include ? '>' : '<';
		    $where_createdby = 'a.created_by ' . $test_type . ' 0'; // necessary so that the OR match returns good results
		    if ($params->get('allow_edit', 0) && (int)$user->get('id') > 0) {
				// logged user can see his own unpublished items only
		        $where_state = '(a.published = 1) OR (a.published = 0 AND a.created_by = ' . (int) $user->get('id') . ')';
			}
		} else if (isset($array_of_authors_values['realauto']) && $array_of_authors_values['realauto'] > 0) { // 'realauto' was selected: check if author on page, if so, select it

			$found = false;
			if ($option === 'com_k2' && $view === 'item') {
				$temp = $jinput->getString('id');
				$temp = explode(':', $temp);
				if ($temp[0]) {
					
					$subquery = $db->getQuery(true);

					$subquery->select($db->quoteName('created_by'));
					$subquery->from($db->quoteName('#__k2_items'));
					$subquery->where($db->quoteName('id') . ' = :itemId');
					$subquery->bind(':itemId', $temp[0], ParameterType::INTEGER);

					$db->setQuery($subquery);
					
					try {
						$result = $db->loadResult();
						if ($result) {
							$found = true;
							$test_type = $include ? '=' : '<>';
							$where_createdby = 'a.created_by' . $test_type . $result;
							
							if ($include && $params->get('allow_edit', 0) && (int)$user->get('id') === $result) {
							    $where_state = 'a.published IN (0, 1)'; // show all articles for the logged author, published or not
							}
						}
					} catch (ExecutionFailureException $e) {
						$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
						return null;
					}
				}
			}
			
			if (!$found) {
				return array();
			}

		} else if (isset($array_of_authors_values['auto']) && $array_of_authors_values['auto'] > 0) { // 'auto' was selected: equivalent to check if the author is logged in
			$test_type = $include ? '=' : '<>';
			$where_createdby = 'a.created_by ' .$test_type.' '.(int) $user->get('id');
			if ($include && $params->get('allow_edit', 0) && (int)$user->get('id') > 0) {
			    $where_state = 'a.published IN (0, 1)'; // show all items for the logged author, published or not
			}
		} else {
			$authors = implode(',', $authors_array);
			if ($authors) {
				$test_type = $include ? 'IN' : 'NOT IN';
				$where_createdby = 'a.created_by '.$test_type.' ('.$authors.')';
			}

			if ($params->get('allow_edit', 0) && (int)$user->get('id') > 0) {
			    if (($include && in_array($user->get('id'), $authors_array)) || (!$include && !in_array($user->get('id'), $authors_array))) {
					// logged user can see his own unpublished items only
			        $where_state = '(a.published = 1) OR (a.published = 0 AND a.created_by = ' . (int) $user->get('id') . ')';
				}
			}
		}

		// author alias filter
		
		$include = $params->get('author_alias_inex', 1);
		$authors_array = $params->get('k2_created_by_alias', array());
		$author_match = (int)$params->get('author_match', 0);

		$where_createdbyalias = '';
		
		if ($author_match > 0 && count($authors_array) > 0) {
		    
		    $array_of_authors_values = array_count_values($authors_array);
		    
		    if (isset($array_of_authors_values['all']) && $array_of_authors_values['all'] > 0) { // 'all' was selected
		        
		        if ($include) {
		            $where_createdbyalias = 'a.created_by_alias != ' . $db->quote('');
		        } else {
		            $where_createdbyalias = 'a.created_by_alias = ' . $db->quote('');
		        }
		        
		    } else if (isset($array_of_authors_values['auto']) && $array_of_authors_values['auto'] > 0) { // 'auto' was selected: check if author alias on page, if so, select it
		        
		        $found = false;
		        if ($option === 'com_k2' && $view === 'item') {
		            $temp = $jinput->getString('id');
		            $temp = explode(':', $temp);
		            if ($temp[0]) {
		                
		                $subquery = $db->getQuery(true);
		                
		                $subquery->select($db->quoteName('created_by_alias'));
		                $subquery->from($db->quoteName('#__k2_items'));
		                $subquery->where($db->quoteName('id') . ' = :k2Id');
		                $subquery->bind(':k2Id', $temp[0], ParameterType::INTEGER);
		                
		                $db->setQuery($subquery);
		                
		                try {
		                    $result = $db->loadResult();
		                    if ($result) {
		                        $found = true;
		                        $test_type = $include ? '=' : '!=';
		                        $where_createdbyalias = 'a.created_by_alias' . $test_type . $db->quote($result);
		                        if (!$include) {
		                            $where_createdbyalias .= ' AND a.created_by_alias != ' . $db->quote('');
		                        }
		                    }
		                } catch (ExecutionFailureException $e) {
		                    $app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		                    return null;
		                }
		            }
		        }
		        
		        if (!$found) {
		            return array();
		        }
		        
		    } else {
		        $quoted_array = array();
		        foreach ($authors_array as $author) {
		            $quoted_array[] = $db->quote($author);
		        }
		        
		        $authors = implode(',', $quoted_array);
		        if ($authors) {
		            $test_type = $include ? 'IN' : 'NOT IN';
		            $where_createdbyalias = 'a.created_by_alias '.$test_type.' ('.$authors.')';
		            if (!$include) {
		                $where_createdbyalias .= ' AND a.created_by_alias != ' . $db->quote('');
		            }
		        }
		    }
		}

		$query->where($where_state);

		if ($author_match === 2 && !empty($where_createdby) && !empty($where_createdbyalias)) {
		    $query->where('(' . $where_createdby . ' OR ' . '(' . $where_createdbyalias . ')' . ')');
		} else {
		    if ($where_createdby) {
		        $query->where($where_createdby);
		    }
		    if ($where_createdbyalias) {
		        $query->where('(' . $where_createdbyalias . ')');
		    }
		}

		// language filter

		if ($params->get('filter_lang', 1) && Multilanguage::isEnabled()) {
		    $query->whereIn($db->quoteName('a.language'), [Factory::getLanguage()->getTag(), '*'], ParameterType::STRING);
		}

		$ordering = array();

		// author order

		switch ($params->get('author_order', ''))
		{
		    case 'selec_asc': $ordering[] = $db->quoteName('author') . ' ASC'; break;
		    case 'selec_dsc': $ordering[] = $db->quoteName('author') . ' DESC'; break;
		}

		// featured switch

		$featured = false;
		$featured_only = false;
		switch ($params->get('show_f', 3))
		{
			case '1': // only
				$featured = true;
				$featured_only = true;
				$query->where($db->quoteName('a.featured') . ' = 1');
				break;
			case '0': // hide
			    $query->where($db->quoteName('a.featured') . ' = 0');
				break;
			case '2': // first the featured ones
				$featured = true;
				$ordering[] = $db->quoteName('a.featured') . ' DESC';
				break;
			default: // no discrimination between featured/unfeatured items
				$featured = true;
		}

		// category order

		if (!$featured_only) {
			switch ($params->get('cat_order', ''))
			{
			    case 'o_asc': $ordering[] = $db->quoteName('c.parent') . ' ASC'; $ordering[] = $db->quoteName('c.ordering') . ' ASC'; break;
			    case 'o_dsc': $ordering[] = $db->quoteName('c.parent') . ' DESC'; $ordering[] = $db->quoteName('c.ordering') . ' DESC'; break;
			    case 't_asc': $ordering[] = $db->quoteName('c.name') . ' ASC'; break;
			    case 't_dsc': $ordering[] = $db->quoteName('c.name') . ' DESC'; break;
			}
		}

		// general ordering

		switch ($params->get('order'))
		{
			case 'o_asc': 
				if ($featured) { 
					$ordering[] = 'CASE WHEN ' . $db->quoteName('a.featured') . ' = 1 THEN ' . $db->quoteName('a.featured_ordering') . ' ELSE ' . $db->quoteName('a.ordering') . ' END ASC'; 
				} else { 
					$ordering[] = $db->quoteName('a.ordering') . ' ASC'; 
				} 
				break;
			case 'o_dsc': 
			    if ($featured) { 
			        $ordering[] = 'CASE WHEN ' . $db->quoteName('a.featured') . ' = 1 THEN ' . $db->quoteName('a.featured_ordering') . ' ELSE ' . $db->quoteName('a.ordering') . ' END DESC'; 
			    } else { 
			        $ordering[] = $db->quoteName('a.ordering') . ' DESC'; 
			    } 
			    break;
			case 'p_asc': $ordering[] = $db->quoteName('a.publish_up') . ' ASC'; break;
			case 'p_dsc': $ordering[] = $db->quoteName('a.publish_up') . ' DESC'; break;
			case 'f_asc': $ordering[] = 'CASE WHEN ' . $db->quoteName('a.publish_down') . ' IS NULL THEN ' . $db->quoteName('a.publish_up') . ' ELSE ' . $db->quoteName('a.publish_down') . ' END ASC'; break;
			case 'f_dsc': $ordering[] = 'CASE WHEN ' . $db->quoteName('a.publish_down') . ' IS NULL THEN ' . $db->quoteName('a.publish_up') . ' ELSE ' . $db->quoteName('a.publish_down') . ' END DESC'; break;
			case 'm_asc': $ordering[] = $db->quoteName('a.modified') . ' ASC'; $ordering[] = $db->quoteName('a.created') . ' ASC'; break;
			case 'm_dsc': $ordering[] = $db->quoteName('a.modified') . ' DESC'; $ordering[] = $db->quoteName('a.created') . ' DESC'; break;
			case 'c_asc': $ordering[] = $db->quoteName('a.created') . ' ASC'; break;
			case 'c_dsc': $ordering[] = $db->quoteName('a.created') . ' DESC'; break;
			case 'mc_asc': $ordering[] = 'CASE WHEN ' . $db->quoteName('a.modified') . ' IS NULL THEN ' . $db->quoteName('a.created') . ' ELSE ' . $db->quoteName('a.modified') . ' END ASC'; break;
			case 'mc_dsc': $ordering[] = 'CASE WHEN ' . $db->quoteName('a.modified') . ' IS NULL THEN ' . $db->quoteName('a.created') . ' ELSE ' . $db->quoteName('a.modified') . ' END DESC'; break;
			case 'random': $ordering[] = $query->rand(); break;
			case 'hit': $ordering[] = $db->quoteName('a.hits') . ' DESC'; break;
			case 'title_asc': $ordering[] = $db->quoteName('a.title') . ' ASC'; break;
			case 'title_dsc': $ordering[] = $db->quoteName('a.title') . ' DESC'; break;
			case 'manual':
				$articles_to_include = array_filter(explode(',', trim($params->get('in', ''), ' ,')));
				if (!empty($articles_to_include)) {
					$manual_ordering = 'CASE a.id';
					foreach ($articles_to_include as $key => $id) {
					    $manual_ordering .= ' WHEN ' . $id . ' THEN ' . $key;
					}
					$ordering[] = $manual_ordering . ' ELSE 999 END, a.id'; // 'FIELD(a.id, ' . $articles_to_include . ')' is MySQL specific
				}
		}

		if (count($ordering) > 0) {
			$query->order($ordering);
		}

		// include only

		$articles_to_include = array_filter(explode(',', trim($params->get('in', ''), ' ,')));
		if (!empty($articles_to_include)) {
		    $articles_to_include = ArrayHelper::toInteger($articles_to_include);
		    $query->whereIn($db->quoteName('a.id'), $articles_to_include);
		}

		// exclude

		$articles_to_exclude = array_filter(explode(',', trim($params->get('ex', ''), ' ,')));

		$item_on_page_id = '';
		if ($params->get('ex_current_item', 0) && $option === 'com_k2' && $view === 'item') {
			$temp = $jinput->getString('id');
			$temp = explode(':', $temp);
			$item_on_page_id = $temp[0];
		}

		if ($item_on_page_id) { // do not show the current item in the list
			$articles_to_exclude[] = $item_on_page_id;
		}

		if (!empty($articles_to_exclude)) {
		    $articles_to_exclude = ArrayHelper::toInteger($articles_to_exclude);
		    $query->whereNotIn($db->quoteName('a.id'), $articles_to_exclude);
		}

		// launch query

		$count = trim($params->get('count', ''));
		$startat = $params->get('startat', 1);
		if ($startat < 1) {
			$startat = 1;
		}

		if (!empty($tags) && !$params->get('tags_inex', 1) && $params->get('tags_match', 'any') == 'all') {
			$db->setQuery($query);
		} else if (!empty($count) && $params->get('count_for', 'articles') == 'articles') {
			$db->setQuery($query, $startat - 1, intval($count));
		} else {
			$db->setQuery($query);
		}

		try {
			$items = $db->loadObjectList();
		} catch (ExecutionFailureException $e) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			return null;
		}

		// END OF DATABASE QUERY

		if (empty($items)) {
			return array();
		}

		// ITEM DATA MODIFICATIONS AND ADDITIONS

		$still_need_to_slice_count = false;

		// exclude all

		if (!empty($tags) && !$params->get('tags_inex', 1) && $params->get('tags_match', 'any') == 'all') {

			$total_tags = count($tags);

			foreach ($items as $key => &$item) {

				if (!isset($item->tags)) {
					$item->tags = self::getItemTags($item->id);
				}

				if (self::getItemTagsCountIn($item->tags, $tags) == $total_tags) {
					unset($items[$key]);
				}
			}

			$still_need_to_slice_count = true;
		}

		// restrict articles per author or category
		// drawback : forces grouping per author or category

		$count_for = $params->get('count_for', 'articles');

		if ($count_for == 'catid' || $count_for == 'author') {

			$grouped = array();
			$pass = array();
			$ordered_items = array();

			if ($count_for == 'catid' && isset($categories_ids_array) && count($categories_ids_array) > 1) {

			    $array_keys = array_keys($categories_ids_array);
			    $last_key = array_pop($array_keys);

			    foreach ($categories_ids_array as $category_id => $categories_id_array) {

			        $grouped[$category_id] = array();
			        $pass[$category_id] = array();

			        foreach ($items as $key => $item) {

			            if (in_array($item->catid, $categories_id_array)) {

			                if (count($pass[$category_id]) < ($startat - 1)) {
			                    $pass[$category_id][] = $item->id;
			                } else {
			                    if ($count) {
			                        if (count($grouped[$category_id]) < intval($count)) {
			                            $grouped[$category_id][] = $item->catid;
			                            $ordered_items[$key] = $item;
			                        } else {
			                            if ($category_id == $last_key) {
			                                break; // only break on the last category
			                            }
			                        }
			                    } else {
			                        $ordered_items[$key] = $item;
			                    }
			                }
			                unset($items[$key]);
			            }
			        }
			    }

			    ksort($ordered_items); // the ordre may have been lost

			} else {

				foreach ($items as $key => $item) {

					if (!isset($grouped[$item->$count_for])) {
						$grouped[$item->$count_for] = array();
						$pass[$item->$count_for] = array();
					}

					if (count($pass[$item->$count_for]) < ($startat - 1)) {
						$pass[$item->$count_for][] = $item->id;
					} else {
						if ($count) {
							if (count($grouped[$item->$count_for]) < intval($count)) {
								$grouped[$item->$count_for][] = $item->id;
    							$ordered_items[$key] = $item;
							}
						} else {
    						$ordered_items[$key] = $item;
						}
					}
				}
			}

			$items = $ordered_items;
		}

		// limit to count

		if ($still_need_to_slice_count) {
			if (!empty($count)) {
				$items = array_slice($items, $startat - 1, intval($count));
			} else {
				$items = array_slice($items, $startat - 1);
			}
		}

		// parameters for all

		$head_type = $params->get('head_type', 'none');

		$image_types = array('image', 'imageintro', 'imagefull', 'allimagesasc', 'allimagesdesc');

		$show_image = false;

		if (in_array($head_type, $image_types)) {

			$show_image = true;

			$crop_picture = ($params->get('crop_pic', 0) && $params->get('create_thumb', 1));

			$create_highres_images = false;
			$lazyload = $params->get('lazyload', false);

			$allow_remote = $params->get('allow_remote', true);

			$thumbnail_mime_type = $params->get('thumb_mime_type', '');

			$maintain_height = $params->get('maintain_height', 0);
			$head_width = $params->get('head_w', 64);
			$head_height = $params->get('head_h', 64);
			$border_width = $params->get('border_w', 0);

			$head_width = $head_width - $border_width * 2;
			$head_height = $head_height - $border_width * 2;

			$filter = $params->get('filter', 'none');

			$quality_jpg = $params->get('quality_jpg', 75);
			$quality_png = $params->get('quality_png', 3);
			$quality_webp = $params->get('quality_webp', 80);
			$quality_avif = $params->get('quality_avif', 80);

			if ($quality_jpg > 100) {
				$quality_jpg = 100;
			}
			if ($quality_jpg < 0) {
				$quality_jpg = 0;
			}

			if ($quality_png > 9) {
				$quality_png = 9;
			}
			if ($quality_png < 0) {
				$quality_png = 0;
			}

			if ($quality_webp > 100) {
				$quality_webp = 100;
			}
			if ($quality_webp < 0) {
				$quality_webp = 0;
			}

			if ($quality_avif > 100) {
			    $quality_avif = 100;
			}
			if ($quality_avif < 0) {
			    $quality_avif = 0;
			}

			$image_qualities = array('jpg' => $quality_jpg, 'png' => $quality_png, 'webp' => $quality_webp, 'avif' => $quality_avif);

			$clear_cache = Helper::IsClearPictureCache($params);

			$subdirectory = 'thumbnails/lne';
			if ($params->get('thumb_path', 'cache') == 'cache') {
				$subdirectory = 'mod_latestnewsenhanced';
			}
			$tmp_path = SYWCache::getTmpPath($params->get('thumb_path', 'cache'), $subdirectory);

			$default_picture = trim($params->get('default_pic', ''));

			if ($clear_cache) {
				Helper::clearThumbnails($module->id, $tmp_path);

				SYWVersion::refreshMediaVersion('mod_latestnewsenhanced_' . $module->id);
			}
		}

		$text_type = $params->get('text', 'intro');
		$letter_count = trim($params->get('l_count', ''));
		$truncate_last_word = $params->get('trunc_l_w', 0);
		$keep_tags = trim($params->get('keep_tags', ''));
		$strip_tags = $params->get('strip_tags', 1);
		$always_show_readmore = $params->get('readmore_always_show', true);
		$trigger_OnContentPrepare = $params->get('trigger_events', false);
		$force_one_line = $params->get('force_one_line', false);
		$title_letter_count = trim($params->get('letter_count_title', ''));
		$title_truncate_last_word = $params->get('trunc_l_w_title', 0);
		//$show_date = $params->get('show_d', 'date');

		$link_to = $params->get('link_to', 'item');
		switch ($params->get('link_target', 'default')) {
			case 'same': $link_target = ''; break;
			case 'inline': $link_target = 4; break;
			case 'new': $link_target = 1; break;
			case 'modal': $link_target = 3; break;
			case 'popup': $link_target = 2; break;
			default: $link_target = 'default';
		}

		$when_no_date = $params->get('when_no_date', 0);
		$items_with_no_date = array();

		$requireVoteData = Helper::isInfoTypeRequired('rating', $params);

		foreach ($items as $key => &$item) {

			// date

			if ($item->date == $db->getNullDate() || $item->date == null) {

				if ($when_no_date == 0) {
					unset($items[$key]);
					continue;
				}

				$item->date = '';
			}

			// category link

			if (!$show_unauthorized_items || in_array($item->category_access, $authorised)) {
				$item->catlink = urldecode(Route::_(K2HelperRoute::getCategoryRoute($item->cat_slug)));
				$item->category_authorized = true;
			} else {
				$catlink = new Uri(Route::_('index.php?option=com_users&view=login', false));
				$catlink->setVar('return', base64_encode(K2HelperRoute::getCategoryRoute($item->cat_slug)));

				$item->catlink = $catlink;
				$item->category_authorized = false;
			}

			// item edit link

			if ($params->get('allow_edit', 0)) {
				if ($option !== 'com_k2') {
					K2HelperPermissions::setPermissions();
				}

				if (K2HelperPermissions::canEditItem($item->created_by, $item->catid)) {
					$item->link_edit = Route::_('index.php?option=com_k2&view=item&task=edit&cid=' . $item->id . '&tmpl=component&template=system');
				}
			}

			// item link

			$item->link = '';

			if ($item->state == 1) {

				//$item->linktarget = '';
				$item->isinternal = true;

				$item->linktitle = $item->title;

				$link_string = K2HelperRoute::getItemRoute($item->slug, $item->cat_slug);

				$forced_itemid = intval($params->get('force_itemid', ''));
				if ($forced_itemid > 0) {

					if (Multilanguage::isEnabled()) {
						$currentLanguage = Factory::getLanguage()->getTag();
						$langAssociations = Associations::getAssociations('com_menus', '#__menu', 'com_menus.item', $forced_itemid, 'id', '', '');
						foreach ($langAssociations as $langAssociation) {
							if ($langAssociation->language == $currentLanguage) {
								$forced_itemid = $langAssociation->id;
								break;
							}
						}
					}

					if (strpos($link_string, 'Itemid') === false) {
						$link_string .= '&Itemid=' . $forced_itemid;
					} else {
						$link_string = preg_replace('#Itemid=([0-9]*)#', 'Itemid=' . $forced_itemid, $link_string);
					}
				}

				if ($item->category_authorized && (!$show_unauthorized_items || in_array($item->access, $authorised))) {

					if ($link_target !== 'default') {
						$item->linktarget = $link_target;
					} else {
						$item->linktarget = '';
					}

					$item->link = urldecode(Route::_($link_string));
					$item->authorized = true; // we know that user has the privilege to view the article

				} else {

					$link = new Uri(Route::_('index.php?option=com_users&view=login', false));
					$link->setVar('return', base64_encode($link_string));

					$item->link = $link;
					$item->linktarget = ''; // cannot open in modal window in this case - too many cases where it might fail bacause the login form	opens first
					$item->authorized = false;
				}
			}

			// rating (to avoid call to rating plugin, use $item->vote)

			$item->vote = '';
			$item->vote_count = 0;

			if ($requireVoteData) {

			    $query = $db->getQuery(true);

			    $query->select('ROUND(' . $db->quoteName('v.rating_sum') . ' / ' . $db->quoteName('v.rating_count') . ', 1) AS rating');
				$query->select($db->quoteName('v.rating_count', 'rating_count'));
				$query->from($db->quoteName('#__k2_rating', 'v'));
    			$query->where($db->quoteName('v.itemID') . ' = :itemId');
    			$query->bind(':itemId', $item->id, ParameterType::INTEGER);

				$db->setQuery($query);

				try {
					$ratings = $db->loadObjectList();
					foreach ($ratings as $rating) {
						$item->vote = $rating->rating;
						$item->vote_count = $rating->rating_count;
					}
				} catch (ExecutionFailureException $e) {
					//$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
				}
			}

			// tags

			if (!isset($item->tags)) {
				$item->tags = self::getItemTags($item->id);
			}

			// thumbnail image creation

			$item->imagetag = '';
			$item->error = array();

			if ($show_image) {

				$filename = '';
				$image_width = 0;
				$image_height = 0;

				// note: original images are not cached, therefore looking thru article content will be inefficient

				if (!$clear_cache && $params->get('create_thumb', 1)) {
				    $thumbnail_src = Helper::thumbnailExists($module->id, $item->id, $tmp_path, $create_highres_images);
				    if ($thumbnail_src !== false) {
				        $filename = $thumbnail_src; // found a corresponding thumbnail
					}
				}

				if (empty($filename)) {

					$imagesrc = '';

					if ($head_type == 'imageintro' || $head_type == 'imagefull') { // K2 image

						$k2imagesrc = 'media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
						if (is_file(JPATH_ROOT.'/'.$k2imagesrc)) { // makes sure the K2 image exists
							$imagesrc = $k2imagesrc;
						}

					} else if ($head_type == 'image') {
						if (isset($item->fulltext))	{
							$imagesrc = Helper::getImageSrcFromContent($item->introtext, $item->fulltext);
						} else {
							$imagesrc = Helper::getImageSrcFromContent($item->introtext);
						}

					} else if ($head_type == 'allimagesasc') {
						if (isset($item->fulltext))	{
							$imagesrc = Helper::getImageSrcFromContent($item->introtext, $item->fulltext);
						} else {
							$imagesrc = Helper::getImageSrcFromContent($item->introtext);
						}

						// if images not found, look into K2 image
						if (empty($imagesrc)) {
							$k2imagesrc = 'media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
							if (is_file(JPATH_ROOT.'/'.$k2imagesrc)) { // makes sure the k2 image exists
								$imagesrc = $k2imagesrc;
							}
						}

					} else if ($head_type == 'allimagesdesc') {

						$k2imagesrc = 'media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
						if (is_file(JPATH_ROOT.'/'.$k2imagesrc)) { // makes sure the k2 image exists
							$imagesrc = $k2imagesrc;
						}

						// if K2 image not found, look into the K2 items
						if (empty($imagesrc)) {

							if (isset($item->fulltext))	{
								$imagesrc = Helper::getImageSrcFromContent($item->introtext, $item->fulltext);
							} else {
								$imagesrc = Helper::getImageSrcFromContent($item->introtext);
							}
						}
					}

					// last resort, use default image if it exists
					$used_default_image = false;
					if (empty($imagesrc)) {
						if ($default_picture) {
							$imagesrc = $default_picture;
							$used_default_image = true;
						} else {
						    $imagesrc = '';
						}
					}

					if ($imagesrc) { // found an image
					    if (!$params->get('create_thumb', 1) || $head_width <= 0 || $head_height <= 0) { // no thumbnails are created, use the original image
					        // Use the original
					        $filename = $imagesrc;
					    } else {
					        // Create the thumbnail
					        $result_array = Helper::getImageFromSrc($module->id, $item->id, $imagesrc, $tmp_path, $head_width, $head_height, $crop_picture, $image_qualities, $filter, $create_highres_images, $allow_remote, $thumbnail_mime_type);

					        if (isset($result_array['url']) && $result_array['url']) {
    							$filename = $result_array['url'];
    						}

    						if (isset($result_array['error']) && $result_array['error']) {

    						    $item->error[] = $result_array['error'];

    							// if error for the file found, try and use the default image instead
    							if (!$used_default_image && $default_picture) { // if the default image was the one chosen, no use to retry

    							    $default_image_object = HTMLHelper::cleanImageURL($default_picture);

    							    $result_array = Helper::getImageFromSrc($module->id, $item->id, $default_image_object->url, $tmp_path, $head_width, $head_height, $crop_picture, $image_qualities, $filter, $create_highres_images, $allow_remote, $thumbnail_mime_type);

    							    if (isset($result_array['url']) && $result_array['url']) {
    							        $filename = $result_array['url'];
    							    }

    							    if (isset($result_array['error']) && $result_array['error']) {
    							        $item->error[] = $result_array['error'];
    							    }
    							}
    						}
					    }
					}
				}

				if ($filename) {

					$img_attributes = array();

				    if ($crop_picture) {
				        if ($head_width > 0 && $head_height > 0) {
   					        $image_width = $head_width;
   					        $image_height = $head_height;
				        }
				    } else {
				        try {
				            $image_properties = Image::getImageFileProperties($filename);
				            $image_width = $image_properties->width;
				            $image_height = $image_properties->height;
				        } catch (\Exception $e) {
				            $image_width = 0;
				            $image_height = 0;
				        }
				    }

					if ($image_width > 0 && $image_height > 0) {
					    $img_attributes = array('width' => $image_width, 'height' => $image_height);
					}

					$extra_attributes = trim($params->get('image_attributes', ''));
					if ($extra_attributes) {
						$xml = new \SimpleXMLElement('<element ' . $extra_attributes . ' />');
						foreach ($xml->attributes() as $attribute_name => $attribute_value) {
							$img_attributes[$attribute_name] = $attribute_value;
						}
					}

					$item->imagetag = SYWUtilities::getImageElement($filename, $item->title, $img_attributes, $lazyload, $create_highres_images, null, true, SYWVersion::getMediaVersion('mod_latestnewsenhanced_' . $module->id));
				}
			}

			// ago

			if ($item->date) {
				$details = Helper::date_to_counter($item->date, ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') ? true : false);

				$item->nbr_seconds  = intval($details['secs']);
				$item->nbr_minutes  = intval($details['mins']);
				$item->nbr_hours = intval($details['hours']);
				$item->nbr_days = intval($details['days']);
				$item->nbr_months = intval($details['months']);
				$item->nbr_years = intval($details['years']);
			}

			// calendar shows an extra field of type 'date'

			if ($head_type == 'calendar') {
				$item->calendar_date = $item->date;
			}

			// title

			if (!$force_one_line) {
				if (strlen($title_letter_count) > 0) {
					$item->title = SYWText::getText($item->title, 'txt', (int)$title_letter_count, true, '', true, $title_truncate_last_word);
				}
			}

			// text

			$item->text = '';

			$number_of_letters = -1;
			if ($letter_count != '') {
				$number_of_letters = (int)($letter_count);
			}

			$beacon = '';
			if (!$always_show_readmore) {
				$beacon = '^';
			}

			switch ($text_type)
			{
				case 'intrometa': $use_intro = (trim($item->introtext) != '') ? true : false; break;
				case 'metaintro': $use_intro = (trim($item->metadesc) != '') ? false : true; break;
				case 'meta': $use_intro = false; break;
				default: case 'intro': $use_intro = true;
			}

			if ($use_intro) { // use intro text
				$item->text = $item->introtext;
				if ($item->text) {
					if ($trigger_OnContentPrepare) { // will trigger events from plugins
						$item->text = HTMLHelper::_('content.prepare', $item->text);
					}
					$item->text = SYWText::getText($item->text.$beacon, 'html', $number_of_letters, $strip_tags, $keep_tags, true, $truncate_last_word);
				}
			} else { // use meta text
				$item->text = SYWText::getText($item->metadesc.$beacon, 'txt', $number_of_letters, false, '', true, $truncate_last_word);
			}

			// the text won't be cropped if the ^ character is still present after processing (hopefully no ^ at the end of the text)
			$item->cropped = true;
			if (!$always_show_readmore) {
				$text_length = strlen($item->text);
				$item->text = rtrim($item->text, "^");
				if (strlen($item->text) < $text_length && !$item->fulltexthascontent) {
					$item->cropped = false;
				}
			}

			// re-order items with no dates
			if (empty($item->date) && ($when_no_date == 1 || $when_no_date == 2)) {
				$items_with_no_date[] = $item;
				unset($items[$key]);
			}
		}

		if ($when_no_date == 1) {
			return array_merge($items_with_no_date, $items);
		} else if ($when_no_date == 2) {
			return array_merge($items, $items_with_no_date);
		}

		return $items;
	}

	protected static function getItemTags($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('tag.*');
		$query->from($db->quoteName('#__k2_tags', 'tag'));
		$query->join('LEFT', $db->quoteName('#__k2_tags_xref', 'xref'), $db->quoteName('tag.id') . ' = ' . $db->quoteName('xref.tagID'));
		$query->where($db->quoteName('tag.published') . ' = 1');
		$query->where($db->quoteName('xref.itemID') . ' = :itemId');
		$query->bind(':itemId', $id, ParameterType::INTEGER);
		$query->order($db->quoteName('tag.name') . ' ASC');

		$db->setQuery($query);

		try {
			$tags_array = $db->loadObjectList();
			if (count($tags_array) > 0) {
				return $tags_array;
			}
		} catch (ExecutionFailureException $e) {
			return array(); // should return null so we know there is an error
		}

		return array();
	}

	protected static function getItemTagsCountIn($item_tags, $tags)
	{
		if (empty($item_tags)) {
			return 0;
		}

		$count_tags_in_item = 0;

		foreach ($item_tags as $tag) {
			if (in_array($tag->id, $tags)) {
				$count_tags_in_item++;
			}
		}

		return $count_tags_in_item;
	}

	protected static function getCategoryChildren($category_id, $level = -1, $limited_access = true, $reset = true)
	{
	    static $array = array();
	    if ($reset) {
	        $array = array();
	    }

	    $db = Factory::getDbo();

	    $query = $db->getQuery(true);

	    $query->select($db->quoteName('id'));
	    $query->from($db->quoteName('#__k2_categories'));
	    $query->where($db->quoteName('parent') . ' = :parentId');
	    $query->bind(':parentId', $category_id, ParameterType::INTEGER);
	    $query->where($db->quoteName('published') . ' = 1');
	    $query->where($db->quoteName('trash') . ' = 0');

	    if ($limited_access) {
	        $query->whereIn($db->quoteName('access'), Factory::getUser()->getAuthorisedViewLevels());
	    }

	    if (Factory::getApplication()->getLanguageFilter()) {
	        $query->whereIn($db->quoteName('language'), [Factory::getLanguage()->getTag(), '*'], ParameterType::STRING);
	    }

	    $db->setQuery($query);

	    try {
            $rows = $db->loadColumn();
            foreach ($rows as $row) {
                array_push($array, $row);
                if ($level < 0) {
                    self::getCategoryChildren($row, -1, $limited_access, false);
                } else if ($level > 1) {
                    self::getCategoryChildren($row, $level - 1, $limited_access, false);
                }
            }
	    } catch (ExecutionFailureException $e) {
	        Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
	        return null;
	    }

        return $array;
	}

}
?>
