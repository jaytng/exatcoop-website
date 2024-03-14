<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKController;
use Maximenuck\CKFof;
use Maximenuck\Helper;
use Maximenuck\Style;

require_once MAXIMENUCK_PATH . '/helpers/style.php';

class MaximenuckControllerStyle extends CKController {

	/**
	 * Ajax method to render the <style>
	 */
	public function previewModuleStyles($ajax = true) {
		CKFof::checkAjaxToken(false);

		// load the helper of the module
		if (file_exists(JPATH_ROOT.'/modules/mod_maximenuck/helper.php')) {
			require_once JPATH_ROOT.'/modules/mod_maximenuck/helper.php';
		} else {
			echo \Joomla\CMS\Language\Text::_('CK_MODULE_MAXIMENUCK_NOT_INSTALLED');
			die;
		}
		$input = new \Joomla\CMS\Input\Input();

		// $menuID = $this->input->get('menuID', '', 'string');
		$menuID = '|ID|';
		$menustyles = $this->input->get('menustyles', '', 'raw');
		$level1itemnormalstyles = $this->input->get('level1itemnormalstyles', '', 'raw');
		$level1itemdescstyles = $this->input->get('level1itemdescstyles', '', 'raw');
		$level1itemhoverstyles = $this->input->get('level1itemhoverstyles', '', 'raw');
		$level1itemactivestyles = $this->input->get('level1itemactivestyles', '', 'raw');
		$level1itemactivehoverstyles = $this->input->get('level1itemactivehoverstyles', '', 'raw');
		$level1itemparentstyles = $this->input->get('level1itemparentstyles', '', 'raw');
		$level1itemnormalstylesicon = $this->input->get('level1itemnormalstylesicon', '', 'raw');
		$level1itemhoverstylesicon = $this->input->get('level1itemhoverstylesicon', '', 'raw');
		$level2menustyles = $this->input->get('level2menustyles', '', 'raw');
		$level2itemnormalstyles = $this->input->get('level2itemnormalstyles', '', 'raw');
		$level2itemhoverstyles = $this->input->get('level2itemhoverstyles', '', 'raw');
		$level2itemactivestyles = $this->input->get('level2itemactivestyles', '', 'raw');
		$level2itemactivehoverstyles = $this->input->get('level2itemactivehoverstyles', '', 'raw');
		$level2itemnormalstylesicon = $this->input->get('level2itemnormalstylesicon', '', 'raw');
		$level2itemhoverstylesicon = $this->input->get('level2itemhoverstylesicon', '', 'raw');
		$level3menustyles = $this->input->get('level3menustyles', '', 'raw');
		$level3itemnormalstyles = $this->input->get('level3itemnormalstyles', '', 'raw');
		$level3itemhoverstyles = $this->input->get('level3itemhoverstyles', '', 'raw');
		$headingstyles = $this->input->get('headingstyles', '', 'raw');
		$fancystyles = $this->input->get('fancystyles', '', 'raw');
		$orientation = $this->input->get('orientation', 'horizontal', 'string');
		$layout = $this->input->get('layout', 'default', 'string');
		$customcss = $this->input->get('customcss', '', 'raw');

		$params= new \Joomla\Registry\Registry();
		$params->set('menustyles', Style::updateInterface($menustyles, 2));
		$params->set('level1itemnormalstyles', Style::updateInterface($level1itemnormalstyles, 2));
		$params->set('level1itemdescstyles', Style::updateInterface($level1itemdescstyles, 2));
		$params->set('level1itemhoverstyles', Style::updateInterface($level1itemhoverstyles, 2));
		$params->set('level1itemactivestyles', Style::updateInterface($level1itemactivestyles, 2));
		$params->set('level1itemactivehoverstyles', Style::updateInterface($level1itemactivehoverstyles, 2));
		$params->set('level1itemparentstyles', Style::updateInterface($level1itemparentstyles, 2));
		$params->set('level1itemnormalstylesicon', Style::updateInterface($level1itemnormalstylesicon, 2));
		$params->set('level1itemhoverstylesicon', Style::updateInterface($level1itemhoverstylesicon, 2));
		$params->set('level2menustyles', Style::updateInterface($level2menustyles, 2));
		$params->set('level2itemnormalstyles', Style::updateInterface($level2itemnormalstyles, 2));
		$params->set('level2itemhoverstyles', Style::updateInterface($level2itemhoverstyles, 2));
		$params->set('level2itemactivestyles', Style::updateInterface($level2itemactivestyles, 2));
		$params->set('level2itemactivehoverstyles', Style::updateInterface($level2itemactivehoverstyles, 2));
		$params->set('level2itemnormalstylesicon', Style::updateInterface($level2itemnormalstylesicon, 2));
		$params->set('level2itemhoverstylesicon', Style::updateInterface($level2itemhoverstylesicon, 2));
		$params->set('level3menustyles', Style::updateInterface($level3menustyles, 2));
		$params->set('level3itemnormalstyles', Style::updateInterface($level3itemnormalstyles, 2));
		$params->set('level3itemhoverstyles', Style::updateInterface($level3itemhoverstyles, 2));
		$params->set('headingstyles', Style::updateInterface($headingstyles, 2));
		$params->set('fancystyles', Style::updateInterface($fancystyles, 2));
		$params->set('orientation', $orientation);
		$params->set('layout', $layout);
		$params->set('customcss', $customcss);

		// check if the method exist in the module, else it is an old version
		// if (! method_exists('modMaximenuckHelper','createModuleCss') ) {
			// echo 'Error : ' . \Joomla\CMS\Language\Text::_('CK_METHOD_CREATEMODULECSS_NOT_FOUND');
			// die;
		// }

		// render the styles
		// $styles = modMaximenuckHelper::createModuleCss($params, $menuID);
		$styles = Style::createModuleCss($params, $menuID);
		// clean the orientation to avoid no rendering
		$search = array('"');
		$replace = array('|qq|');
		$styles = str_replace($search, $replace, $styles);

		if ($ajax == true) {
			echo '|okck|' . $styles . '';
			exit;
		} else {
			return $styles;
		}
	}

	/*
	 * Generate the CSS styles from the settings
	 */
	public function save() {
		// security check
		CKFof::checkAjaxToken(true);

		// get data
		$id = $this->input->get('id', 0, 'int');
		$frommoduleid = $this->input->get('frommoduleid', 0, 'int');
		$fields = $this->input->get('fields', '', 'raw');
		$customcss = $this->input->get('customcss', '', 'raw');
		$name = $this->input->get('name', '', 'string');
		if (! $name) $name = 'style' . $id;
		// $layoutcss = trim($this->input->get('layoutcss', '', 'html'));

		// get the styles
		$layoutcss = $this->previewModuleStyles(false);

		// load the item
		$model = $this->getModel();
		$row = $model->getItem($id);
		$row->id = (int)$row->id;
		$row->state = (int)$row->state;

		// set data
		$row->params = Style::updateInterface($fields);
		$row->name = $name;
		$row->layoutcss = $layoutcss;
		$row->customcss = $customcss;

		if (! $id = $model->save($row)) {
			echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Styles !'}";
			exit;
		}

		if ($frommoduleid > 0) {
			$module = CKFof::dbLoad('#__modules', $frommoduleid);
			if ($module->id) {
				$module->params = new \Joomla\Registry\Registry($module->params);
				$this->setModuleParams($module->params);
				$module->params = $module->params->toString();
				// $moduleId = $frommoduleid;
				$moduleId = CKFof::dbStore('#__modules', $module);
				if (! $moduleId) {
					echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Module Params !'}";
					exit;
				} else {
					echo '{"result": "1", "id": "' . $id . '", "moduleid": "' . $moduleId . '", "message": "Styles saved successfully"}';
					exit;
				}
			}
		}

		echo '{"result": "1", "id": "' . $id . '", "moduleid": "0", "message": "Styles saved successfully"}';
		exit;
	}


	


	/*
	 * Get the styles settings and store them in a Registry variable
	 * B/C function to store the module settings
	 */
	public function setModuleParams(&$params) {

		$menustyles = $this->input->get('menustyles', '', 'raw');
		$level1itemnormalstyles = $this->input->get('level1itemnormalstyles', '', 'raw');
		$level1itemhoverstyles = $this->input->get('level1itemhoverstyles', '', 'raw');
		$level1itemactivestyles = $this->input->get('level1itemactivestyles', '', 'raw');
		$level1itemparentstyles = $this->input->get('level1itemparentstyles', '', 'raw');
		$level1itemnormalstylesicon = $this->input->get('level1itemnormalstylesicon', '', 'raw');
		$level1itemhoverstylesicon = $this->input->get('level1itemhoverstylesicon', '', 'raw');
		$level2menustyles = $this->input->get('level2menustyles', '', 'raw');
		$level2itemnormalstyles = $this->input->get('level2itemnormalstyles', '', 'raw');
		$level2itemhoverstyles = $this->input->get('level2itemhoverstyles', '', 'raw');
		$level2itemactivestyles = $this->input->get('level2itemactivestyles', '', 'raw');
		$level2itemnormalstylesicon = $this->input->get('level2itemnormalstylesicon', '', 'raw');
		$level2itemhoverstylesicon = $this->input->get('level2itemhoverstylesicon', '', 'raw');
		$level3menustyles = $this->input->get('level3menustyles', '', 'raw');
		$level3itemnormalstyles = $this->input->get('level3itemnormalstyles', '', 'raw');
		$level3itemhoverstyles = $this->input->get('level3itemhoverstyles', '', 'raw');
		$headingstyles = $this->input->get('headingstyles', '', 'raw');
		$fancystyles = $this->input->get('fancystyles', '', 'raw');
		$orientation = $this->input->get('orientation', 'horizontal', 'string');
		$layout = $this->input->get('layout', 'default', 'string');
		// $theme = $this->input->get('theme', 'blank', 'string');
		$customcss = $this->input->get('customcss', '', 'raw');

		$params->set('menustyles', $menustyles);
		$params->set('level1itemnormalstyles', $level1itemnormalstyles);
		$params->set('level1itemhoverstyles', $level1itemhoverstyles);
		$params->set('level1itemactivestyles', $level1itemactivestyles);
		$params->set('level1itemparentstyles', $level1itemparentstyles);
		$params->set('level1itemnormalstylesicon', $level1itemnormalstylesicon);
		$params->set('level1itemhoverstylesicon', $level1itemhoverstylesicon);
		$params->set('level2menustyles', $level2menustyles);
		$params->set('level2itemnormalstyles', $level2itemnormalstyles);
		$params->set('level2itemhoverstyles', $level2itemhoverstyles);
		$params->set('level2itemactivestyles', $level2itemactivestyles);
		$params->set('level2itemnormalstylesicon', $level2itemnormalstylesicon);
		$params->set('level2itemhoverstylesicon', $level2itemhoverstylesicon);
		$params->set('level3menustyles', $level3menustyles);
		$params->set('level3itemnormalstyles', $level3itemnormalstyles);
		$params->set('level3itemhoverstyles', $level3itemhoverstyles);
		$params->set('headingstyles', $headingstyles);
		$params->set('fancystyles', $fancystyles);
		$params->set('orientation', $orientation);
		$params->set('layout', $layout);
		// $params->set('theme', $theme);
		$params->set('customcss', $customcss);
	}

	public function ajaxGetThemeCss() {
		// security check
		CKFof::checkAjaxToken(true);

		$theme = $this->input->get('theme', '', 'string');
		
		$phpcss = file_get_contents(JPATH_ROOT . '/modules/mod_maximenuck/themes/' . $theme . '/css/maximenuck.php');
		$css = str_replace('<?php echo $id; ?>', 'maximenuck_previewmodule', $phpcss);
		$pattern = '/<\?php\s[^>]*[^>]*(.*)\?>/iUs';
		$replacement = '';
		$css = preg_replace($pattern, $replacement, $css);

		echo $css;
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
		CKFof::checkAjaxToken(false);

		// create a backup file with all fields stored in it
		$fields = $this->input->get('jsonfields', '', 'string');
		$fields = Style::updateInterface($fields);
		$customcss = $this->input->get('customcss', '', 'string');
		$customcss = str_replace("\n", "|nl|", $customcss);
		$customcss = str_replace("\t", "    ", $customcss);
		$customcss = str_replace(":", "|dp|", $customcss);
		$customcss = str_replace("{", "|ob|", $customcss);
		$customcss = str_replace("}", "|cb|", $customcss);
		$content = trim($fields, '}') . ', |qq|customcss|qq| : |qq|' . addcslashes($customcss, '"') . '|qq|}';
		$styleid = $this->input->get('styleid', $this->input->get('frommoduleid',0,'int'),'int');
		$backupfile_path = MAXIMENUCK_PATH . '/export/exportParams'. $styleid .'.mmck';
		if (file_put_contents($backupfile_path, $content)) {
			echo '1';
		} else {
			echo '0';
		}

		exit();
	}
	
/*----------------------------- OK ------------------------*/





	
	
	/**
	 * Ajax method to clean the name of the google font
	 */
	public function clean_gfont_name() {
		// load the helper of the module
		if (file_exists(JPATH_ROOT.'/modules/mod_maximenuck/helper.php')) {
			require_once JPATH_ROOT.'/modules/mod_maximenuck/helper.php';
		} else {
			echo \Joomla\CMS\Language\Text::_('CK_MODULE_MAXIMENUCK_NOT_INSTALLED');
			die;
		}
		
		$input = new \Joomla\CMS\Input\Input();
		$gfont = $this->input->get('gfont', '', 'string');

		$cleaned_gfont = modMaximenuckHelper::clean_gfont_name($gfont);

		echo $cleaned_gfont;

		die;
	}
	
	/**
	* Save the param in the module options table
	*
	* @param 	integer 	$id  	the module ID
	* @param 	string 		$param	the param name
	* @param 	string 		$value	the param value
	*/
	public function save_param($id = 0, $param = '', $value = '') {
		$input = new \Joomla\CMS\Input\Input();
		$id = $this->input->post->get('id', $id, 'int');
		$param = $this->input->post->get('param', $param, 'string');
		$value = $this->input->post->get('value', $value, 'raw');

		$row = \Joomla\CMS\Table\Table::getInstance('Module');

		// load the module
		$row->load( (int) $id ); 
		if ($row->id === null) {
			echo 'Error : Can not load the module ID : ' . $id;
			die;
		}
		$row->params = new \Joomla\Registry\Registry($row->params);
		// set the new params
		$row->params->set($param, $value);
		$row->params = $row->params->toString();

		if ($id)
		{
			if (!$row->store()) {
				echo 'Error : Can not save the module ID : ' . $id;
				echo($this->_db->getErrorMsg());
				die;
			}
		}
		echo "1";
		die;
	}
	
	/**
	* Load the param from the module options table
	*
	* @param 	integer 	$id  	the module ID
	* @param 	string 		$param	the param name
	*/
	public function load_param($id = 0, $param = '', $ajax = true, $all = false, $json = false) {
		$input = new \Joomla\CMS\Input\Input();
		$id = $this->input->post->get('id', $id, 'int');
		$param = $this->input->post->get('param', $param, 'string');
		$all = $this->input->post->get('all', $all, 'bool');

		$row = \Joomla\CMS\Table\Table::getInstance('Module');

		// load the module
		$row->load( (int) $id ); 
		if ($row->id === null && $ajax === true) {
			echo 'Error LOAD PARAM : Can not load the module ID : ' . $id;
			die;
		}
		$params = new \Joomla\Registry\Registry($row->params);
		if ( $ajax === true && $all === false ) {
			// get the needed params
			echo $params->get($param);
			die;
		} else if( $ajax === true && $all === true && $json === false ) {
			// get all the params
			echo $params;
			die;
		} else if( $ajax === false && $all === true && $json === true ) {
			// get all the params
			return $row->params;
			die;
		} else {
			return $params;
		}
	}

	/**
	* Load the param from the module options table
	*
	* @param 	integer 	$id  	the module ID
	* @param 	string 		$param	the param name
	*/
	public function load_params($id = 0) {
		$input = new \Joomla\CMS\Input\Input();
		$id = $this->input->post->get('id', $id, 'int');
//		$param = $this->input->post->get('param', $param, 'string');
//		$all = $this->input->post->get('all', $all, 'bool');

		$row = \Joomla\CMS\Table\Table::getInstance('Module');

		// load the module
		$row->load( (int) $id ); 
		if ($row->id === null && $ajax === true) {
			echo 'Error LOAD PARAM : Can not load the module ID : ' . $id;
			die;
		}
		$params = new \Joomla\Registry\Registry($row->params);

		// get all the params
		echo $params;
		die;
	}

	/**
	 * Ajax method to read the fields values from the selected preset
	 *
	 * @return  json - 
	 *
	 */
	function loadPresetFields() {
		// security check
		CKFof::checkAjaxToken(true);

		$preset = $this->input->get('preset', '', 'string');
		$folder_path = MAXIMENUCK_MEDIA_PATH . '/presets/';
		// load the fields
		$fields = '{}';
		if ( file_exists($folder_path . $preset. '/styles.json') ) {
			$fields = @file_get_contents($folder_path . $preset. '/styles.json');
			$fields = str_replace("\n", "", $fields);
		} else if ( file_exists($folder_path . $preset. '.mmck') ) {
			$fields = @file_get_contents($folder_path . $preset. '.mmck');
			$fields = str_replace("\n", "", $fields);
		} else {
			echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '/styles.json'.'"}';
			exit();
		}

		$fields = Style::updateInterface($fields);
		echo '{"result" : 1, "fields" : "'.$fields.'", "customcss" : ""}';
		exit();
	}

	/**
	 * Ajax method to read the custom css from the selected preset
	 *
	 * @return  string - the custom CSS on success, error message on failure
	 *
	 */
	function loadPresetCustomcss() {
		$input = \Joomla\CMS\Factory::getApplication()->input;
		$preset = $this->input->get('preset', '', 'string');
		$folder_path = MAXIMENUCK_MEDIA_PATH . '/presets/';

		// load the custom css
		$customcss = '';
		if ( file_exists($folder_path . $preset. '/custom.css') ) {
			$customcss = @file_get_contents($folder_path . $preset. '/custom.css');
		} else {
			echo '|ERROR| File Not found : '.$folder_path . $preset. '/custom.css';
			exit();
		}

		echo $customcss;
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
		CKFof::checkAjaxToken(false);

		$file = $this->input->files->get('file', '', 'array');
		if (!is_array($file)) {
			$msg = \Joomla\CMS\Language\Text::_('CK_NO_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

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

		$filecontent = Style::updateInterface($filecontent);
		echo json_encode(array('data'=> $filecontent));
		exit();
	}
}