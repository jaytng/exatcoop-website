<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKController;
use Maximenuck\CKFof;

class MaximenuckControllerMaximenu extends CKController {

	public function save() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$id = $this->input->get('id', 0, 'int');

		$model = $this->getModel();
		$row = $model->getItem($id);

		// get data
		$fields = $this->input->get('fields', '', 'raw');
		$name = $this->input->get('name', '', 'string');
		if (! $name) $name = 'menu' . $id;
		$layout = $this->input->get('layout', '', 'json');

		// set data
		$row->params = $fields;
		$row->name = $name;
		$row->layouthtml = serialize($layout);

		if (! $id = $model->save($row)) {
			echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Menu !'}";
			echo($this->_db->getErrorMsg());
			exit;
		}
		echo '{"result": "1", "id": "' . $id . '", "message": "Menu saved successfully"}';
		exit;
	}

	/**
	 * Ajax method to save the json data into the .mmck file
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	public function exportParams() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}
		// create a backup file with all fields stored in it
		$fields = $this->input->get('jsonfields', '', 'string');
		$backupfile_path = MAXIMENUCK_PATH . '/export/exportParamsMaximenuckMenu'. $this->input->get('styleid',0,'int') .'.mmck';
		if (file_put_contents($backupfile_path, $fields)) {
			echo '1';
		} else {
			echo '0';
		}

		exit();
	}

	/**
	 * Ajax method to import the .mmck file into the interface
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	public function uploadParamsFile() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$file = $this->input->files->get('file', '', 'array');
		if (!is_array($file))
			exit();

		$filename = \Joomla\CMS\Filesystem\File::makeSafe($file['name']);

		// check if the file exists
		if (\Joomla\CMS\Filesystem\File::getExt($filename) != 'mmck') {
			$msg = \Joomla\CMS\Language\Text::_('CK_NOT_MMCK_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		//Set up the source and destination of the file
		$src = $file['tmp_name'];

		// check if the file exists
		if (!$src || !\Joomla\CMS\Filesystem\File::exists($src)) {
			$msg = \Joomla\CMS\Language\Text::_('CK_FILE_NOT_EXISTS', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// read the file
		if (!$filecontent = file_get_contents($src)) {
			$msg = \Joomla\CMS\Language\Text::_('CK_UNABLE_READ_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// replace vars to allow data to be moved from another server
		$filecontent = str_replace("|URIROOT|", \Joomla\CMS\Uri\Uri::root(true), $filecontent);
//		$filecontent = str_replace("|qq|", '"', $filecontent);

//		echo $filecontent;
		echo json_encode(array('data'=> $filecontent));
		exit();
	}

	/**
	 * Ajax method to read the fields values from the selected preset
	 *
	 * @return  json - 
	 *
	 */
	function loadPresetFields() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$preset = $this->input->get('preset', '', 'string');
		$folder_path = MAXIMENUCK_MEDIA_PATH . '/presets/';
		// load the fields
		$fields = '{}';
		if ( file_exists($folder_path . $preset. '.mmck') ) {
			$fields = @file_get_contents($folder_path . $preset. '.mmck');
			$fields = str_replace('\n','', $fields);
//			$fields = str_replace("{", "|ob|", $fields);
//			$fields = str_replace("}", "|cb|", $fields);
		} else {
			echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '.mmck'.'"}';
			exit();
		}

		echo '{"result" : 1, "fields" : "'.$fields.'", "customcss" : ""}';
		exit();
	}
}