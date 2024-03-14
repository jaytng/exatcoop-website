<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKController;
use Maximenuck\CKFof;
use Maximenuck\CKText;

class MaximenuckControllerBrowse extends CKController {

	public static function ajaxCreateFolder() {
		// security check
		CKFof::checkAjaxToken(false);

		if (CKFof::userCan('create', 'com_media')) {
			$input = CKFof::getInput();
			$path = $input->get('path', '', 'string');
			$name = $input->get('name', '', 'string');

			require_once MAXIMENUCK_PATH . '/helpers/ckbrowse.php';
			if ($result = CKBrowse::createFolder($path, $name)) {
				$msg = CKText::_('CK_FOLDER_CREATED_SUCCESS');
			} else {
				$msg = CKText::_('CK_FOLDER_CREATED_ERROR');
			}

			echo '{"status" : "' . ($result == false ? '0' : '1') . '", "message" : "' . $msg . '"}';
		} else {
			echo '{"status" : "2", "message" : "' . CKText::_('CK_ERROR_USER_NO_AUTH') . '"}';
		}
		exit;
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		// security check
		CKFof::checkAjaxToken(false);

		require_once MAXIMENUCK_PATH . '/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}
}