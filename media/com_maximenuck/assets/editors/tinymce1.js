/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

/* Call the editor using Javascript. It needs an instance of a sample editor called "ckeditor" to run
 * the ckeditor instance shall be called in PHP using \Joomla\CMS\Editor\Editor->display
 */
function ckLoadEditorOnTheFly(id) {
	try {
		tinymce.execCommand('mceAddEditor', false, id);
	} 
	catch(err) {
		alert(err);
	}
}

/* save the content of the editor into the textarea */
function ckSaveEditorOnTheFly(id) {
	try {
		var editor = tinymce.get(id);
		editor.save();
	} 
	catch(err) {
		alert('Error saving one of the editors');
	}
}

/* save the content of the editor into the textarea */
function ckRemoveEditorOnTheFly(id) {
	try {
		tinymce.execCommand('mceRemoveEditor', false, id);
	} 
	catch(err) {
		alert('Error removing one of the editors');
	}
}