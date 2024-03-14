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
		var pluginOptions = Joomla.getOptions ? Joomla.getOptions('plg_editor_tinymce', {})
						:  (Joomla.optionsStorage.plg_editor_tinymce || {}),
			editors = document.querySelectorAll('.js-editor-tinymce'),
		tinyMCEOptions = pluginOptions.tinyMCE;

		var options = tinyMCEOptions['default']; // get all options from the default editor
		var ckeditoroptions = tinyMCEOptions['ckeditor']; // get all options from the sample editor with the name 'ckeditor'

		if (ckeditoroptions.joomlaMergeDefaults) {
			options = Joomla.extend(Joomla.extend({}, ckeditoroptions), options);
		} else {
			options = Joomla.extend({}, options);
		}
		// check if we need to call the button
		if (ckeditoroptions.joomlaExtButtons && ckeditoroptions.joomlaExtButtons.names && ckeditoroptions.joomlaExtButtons.names.length) {
				options.toolbar1 += ' | ' + ckeditoroptions.joomlaExtButtons.names.join(' ');
				var callbackString = ckeditoroptions.joomlaExtButtons.script.join(';');
				options.setupCallbackString = ckeditoroptions.setupCallbackString || '';
				options.setupCallbackString = options.setupCallbackString + ';' + callbackString;
				options.joomlaExtButtons = null;
		}
		options.selector = null;
		options.target   = document.getElementById(id);
		options.setupCallbackString = options.setupCallbackString.replace(/ckeditor/g, id);
		if (options.setupCallbackString && !options.setup) {
			options.setup = new Function('editor', options.setupCallbackString);
		}
		// create and render the new editor instance with the options and ID of the textarea
		var ed = new tinyMCE.Editor(id, options, tinymce.EditorManager);
		ed.render();


		/** Register the editor's instance to Joomla Object */
		Joomla.editors.instances[id] = {
			// Required by Joomla's API for the XTD-Buttons
			'getValue': function () { return this.instance.getContent(); },
			'setValue': function (text) { return this.instance.setContent(text); },
			'replaceSelection': function (text) { return this.instance.execCommand('mceInsertContent', false, text); },
			// Some extra instance dependent
			'id': id,
			'instance': ed,
			'onSave': function() { if (this.instance.isHidden()) { this.instance.show()}; return '';},
		};
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