<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Widgets;

defined('_JEXEC') or die;

class MapEditor extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		/**
		 * The list of markers added to the map.
		 * 
		 * Example:
		 * 
		 * [
		 * 	  'lat' => 37.9838,
		 * 	  'lng' => 23.7275,
		 * 	  'title' => 'Athens',
		 * 	  'description' => 'The capital of Greece',
		 * ]
		 */
		'value' => [],

		// The default map latitude. Where it points when no markers are added.
		'lat' => null,
		
		// The default map longitude. Where it points when no markers are added.
		'lng' => null,
		
		// Max markers allowed
		'maxMarkers' => 1,

		// Set whether to show the map editor sidebar
		'showSidebar' => true,

		// Set the marker image, relative path to an image file
		'markerImage' => ''
	];

	public function __construct($options = [])
	{
		parent::__construct($options);

		$this->prepare();

		$this->loadMedia();
	}

	private function prepare()
	{
		if (!$this->options['pro'] && is_array($this->options['value']) && count($this->options['value']) >= 1)
		{
			$this->options['css_class'] .= ' markers-limit-reached';
		}

		if ($this->options['markerImage'])
		{
			$markerImage = explode('#', ltrim($this->options['markerImage'], DIRECTORY_SEPARATOR));
			$this->options['markerImage'] = \JURI::root() . reset($markerImage);
		}

		\JText::script('NR_ENTER_AN_ADDRESS_OR_COORDINATES');
		\JText::script('NR_ARE_YOU_SURE_YOU_WANT_TO_DELETE_ALL_SELECTED_MARKERS');
		\JText::script('NR_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MARKER');
		\JText::script('NR_ADD_MARKER');
		\JText::script('NR_EDIT_MARKER');
		\JText::script('NR_DELETE_MARKER');
		\JText::script('NR_UNKNOWN_LOCATION');
		\JText::script('NR_UNLIMITED_MARKERS');
		\JText::script('NR_ADD_MORE_MARKERS_UPGRADE_TO_PRO');
		\JText::script('NR_MARKERS');
		\JText::script('NR_YOU_HAVENT_ADDED_ANY_MARKERS_YET');
		\JText::script('NR_ADD_YOUR_FIRST_MARKER');
		\JText::script('NR_NO_MARKERS_FOUND');
		\JText::script('NR_LOCATION_ADDRESS');
		\JText::script('NR_ADD_TO_MAP');
		\JText::script('NR_COORDINATES');
		\JText::script('NR_ADDRESS_ADDRESS_HINT');
		\JText::script('NR_LATITUDE');
		\JText::script('NR_LONGITUDE');
		\JText::script('NR_MARKER_INFO');
		\JText::script('NR_LABEL');
		\JText::script('NR_DESCRIPTION');
		\JText::script('NR_MARKER_LABEL');
		\JText::script('NR_MARKER_DESCRIPTION');
		\JText::script('NR_SAVE');
		\JText::script('NR_PLEASE_SELECT_A_LOCATION');
		\JText::script('NR_IMPORT');
		\JText::script('NR_IMPORT_MARKERS');
		\JText::script('NR_IMPORT_LOCATIONS_DESC');
		\JText::script('NR_IMPORT_LOCATIONS_DESC2');
		\JText::script('NR_PLEASE_ENTER_LOCATIONS_TO_IMPORT');
		\JText::script('NR_COULDNT_IMPORT_LOCATIONS');
		\JText::script('NR_ADDING_MARKERS');
		\JText::script('NR_SAVE_YOUR_FIRST_MARKER');
		\JText::script('NR_OUT_OF');
		\JText::script('NR_MARKERS_ADDED');
		\JText::script('NR_MARKERS_LIMIT_REACHED_DELETE_MARKER_TO_ADD');
		\JText::script('NR_EXPORT_MARKERS');
		\JText::script('NR_EXPORT_MARKERS_DESC');
		\JText::script('NR_THERE_ARE_NO_LOCATIONS_TO_EXPORT');
		\JText::script('NR_LOCATIONS_IMPORTED');
	}

	/**
	 * Loads media files
	 * 
	 * @return  void
	 */
	public function loadMedia()
	{
		\JHtml::script('https://unpkg.com/react@18.2.0/umd/react.production.min.js');
		\JHtml::script('https://unpkg.com/react-dom@18.2.0/umd/react-dom.production.min.js');

		\JHtml::stylesheet('plg_system_nrframework/vendor/leaflet.css', ['relative' => true, 'version' => 'auto']);
		\JHtml::script('plg_system_nrframework/vendor/leaflet.js', ['relative' => true, 'version' => 'auto']);
		
		\JHtml::stylesheet('plg_system_nrframework/widgets/mapeditor.css', ['relative' => true, 'version' => 'auto']);
		\JHtml::script('plg_system_nrframework/mapeditor.js', ['relative' => true, 'version' => 'auto']);
	}
}