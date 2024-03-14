/**
 * @name		Maximenu CK
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014 - 2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

var $ck = jQuery.noConflict();

function ckSetFieldsValue(fields) {
	var fields = JSON.parse(fields);
	for (field in fields) {
		ckSetValueToField(field, fields[field]);
	}
}

function ckSetValueToField(id, value) {
	var field = $ck('#' + id);

	if (!field.length) {
		if ($ck('#ckpopupstyleswizard input[name=' + id + ']').length) {
			$ck('#ckpopupstyleswizard input[name=' + id + ']').each(function(i, radio) {
				radio = $ck(radio);
				if (radio.val() == value) {
					radio.attr('checked', 'checked');
					radio.prop('checked', 'checked');
				} else {
//					radio.removeAttr('checked');
//					radio.removeProp('checked');
				}
			});
		}
	} else {
		if (field.hasClass('color')) field.css('background',value);
		$ck('#' + id).val(value);
	}
}

/**
* Encode the fields id and value in json
*/
function ckMakeJsonFields() {
	var fields = new Object();
	$ck('#styleswizard_options input, #styleswizard_options select, #styleswizard_options textarea').each(function(i, el) {
		el = $ck(el);
		if (el.attr('type') == 'radio') {
			if (el.prop('checked')) {
				fields[el.attr('name')] = el.val();
			} else {
				// fields[el.attr('id')] = '';
			}
		} else if (el.attr('type') == 'checkbox') {
			if (el.prop('checked')) {
				fields[el.attr('name')] = '1';
			} else {
				fields[el.attr('name')] = '0';
			}
		} else {
			if (el.attr('id') != 'customcss') {
			var value = el.val() ? el.val() : '';
			fields[el.attr('name')] = value
				.replace(/"/g, '|quot|')
				.replace(/{/g, '|ob|')
				.replace(/}/g, '|cb|')
				.replace(/\t/g, '|tt|')
				.replace(/\n/g, '|rr|');
			}
		}
	});
	fields = JSON.stringify(fields);

	return fields;
	// return fields.replace(/"/g, "|qq|");
}


/**
* Encode the params in json
*/
// BC method
function make_json_fields(prefix) {
	var fields = [];
	$ck('#ckpopupstyleswizard .' + prefix).each(function(i, field) {
		field = $ck(field);
		var  fieldobj = {};
		if ( field.attr('type') == 'radio' ) {
			if ( field.prop('checked') == true ) {
				fieldobj['id'] = field.attr('name');
				fieldobj['value'] = field.val();
				fields.push(fieldobj);
			}
		} else if ( field.attr('type') != 'radio' ) {
			fieldobj['id'] = field.attr('id');
			fieldobj['value'] = field.val();
			fields.push(fieldobj);
		}
	});
	fields = JSON.stringify(fields);

	return fields.replace(/"/g, "|qq|");
}


/**
* Set the stored value for each field
*/
function ckApplyStylesparams() {
	if ($ck('#params').val()) {
		var fields = JSON.parse($ck('#params').val().replace(/\|qq\|/g, "\""));
		for (var field in fields) {
			ckSetValueToField(field, fields[field])
		}
	} 
	// if V8 legacy
	else if ($ck('#frommoduleid').val()) {
		var params = get_params_list();
		for (i=0;i<params.length;i++) {
			var param = params[i];

			if (window.parent.document.getElementById('jform_params_'+param)) {
				var json_value = window.parent.document.getElementById('jform_params_'+param).value;
			} else {
//				console.log(param + ' not found in the parent module page'); 
				continue;
			}
			json_value = ckBCFieldsInterface(json_value);
			var fields = JSON.parse(json_value.replace(/\|qq\|/g, "\""));
			var j;
			for (j=0;j<fields.length;j++) {
				fieldobj = fields[j];
				fieldobj['id'] = fieldobj['id'].replace(param + '_', ''); // compatibility for old params
				ckSetValueToField(fieldobj['id'], fieldobj['value']);
			}
		}
	}
//	ckChangeMenuOrientation($ck('input[name=orientation]:checked').val());
	if (window.parent.document.getElementById('jform_params_layout')) {
		ckChangeLayout(window.parent.document.getElementById('jform_params_layout').value.replace('_:', ''), $ck('input[name=orientation]:checked').val())
	}
	// launch the preview to update the interface
	ckPreviewStyles();
}

/**
* Render the styles from the module helper
*/
function ckPreviewStyles(button) {
	if (! button) button = '#ckpopupstyleswizard_makepreview';
	ckAddWaitIcon(button);
	var myurl = MAXIMENUCK.BASE_URL + "&task=style.previewModuleStyles&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			menuID: 'maximenuck_previewmodule',
			menustyles: make_json_fields('menustyles'),
			level1itemnormalstyles: make_json_fields('level1itemnormalstyles'),
			level1itemdescstyles: make_json_fields('level1itemdescstyles'),
			level1itemhoverstyles: make_json_fields('level1itemhoverstyles'),
			level1itemactivestyles: make_json_fields('level1itemactivestyles'),
			level1itemactivehoverstyles: make_json_fields('level1itemactivehoverstyles'),
			level1itemparentstyles: make_json_fields('level1itemparentstyles'),
			level2menustyles: make_json_fields('level2menustyles'),
			level2itemnormalstyles: make_json_fields('level2itemnormalstyles'),
			level2itemhoverstyles: make_json_fields('level2itemhoverstyles'),
			level2itemactivestyles: make_json_fields('level2itemactivestyles'),
			level1itemnormalstylesicon: make_json_fields('level1itemnormalstylesicon'),
			level1itemhoverstylesicon: make_json_fields('level1itemhoverstylesicon'),
			level2itemnormalstylesicon: make_json_fields('level2itemnormalstylesicon'),
			level2itemhoverstylesicon: make_json_fields('level2itemhoverstylesicon'),
			level3menustyles: make_json_fields('level3menustyles'),
			level3itemnormalstyles: make_json_fields('level3itemnormalstyles'),
			level3itemhoverstyles: make_json_fields('level3itemhoverstyles'),
			headingstyles: make_json_fields('headingstyles'),
			fancystyles: make_json_fields('fancystyles'),
			orientation: $ck('input[name=orientation]:checked').val(),
			layout: $ck('.layoutthumb.selected').attr('data-name'),
			frommoduleid: $ck('#frommoduleid').val(),
			customcss: $ck('#customcss').val()
		}
	}).done(function(code) {
		code = code.trim();
		if ( code.substring(0,6).toLowerCase() != '|okck|' ) {
			 alert(code);
		} else {
			code = code.replace(/\|okck\|/g , '');
			code = ckMakeCharReplacements(code);
			var csscode = '<style>' + code.replace(/\|ID\|/g, 'maximenuck_previewmodule') + '</style>';
			$ck('#previewarea > .ckstyle').html(csscode);
			ckLoadGfontStylesheets();
			if (window.parent.document.getElementById('jform_params_theme')) {
				var theme = window.parent.document.getElementById('jform_params_theme').value;
				ckLoadThemeCss(theme);
			}
		}
		ckRemoveWaitIcon(button);
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

/**
* Render the styles from the module helper
*/
function ckSaveStyles(button) {
	if (! $ck('#name').val() && $ck('#name').attr('type') == 'text') {
		$ck('#name').addClass('invalid').focus();
		alert('Please give a name');
		return;
	}
	$ck('#name').removeClass('invalid');
	if (!button) button = '#ckpopupstyleswizard_save';
	ckAddSpinnerIcon(button);
	var fields = ckMakeJsonFields();

	var myurl = MAXIMENUCK.BASE_URL + "&task=style.save&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: $ck('#id').val(),
			name: $ck('#name').val(),
			layoutcss: $ck('#layoutcss').val(),
			// customstyles: customstyles,
			customcss: $ck('#customcss').val(),
			fields: fields,
			// for the css rendering
			menuID: 'maximenuck_previewmodule',
			menustyles: make_json_fields('menustyles'),
			level1itemnormalstyles: make_json_fields('level1itemnormalstyles'),
			level1itemdescstyles: make_json_fields('level1itemdescstyles'),
			level1itemhoverstyles: make_json_fields('level1itemhoverstyles'),
			level1itemactivestyles: make_json_fields('level1itemactivestyles'),
			level1itemactivehoverstyles: make_json_fields('level1itemactivehoverstyles'),
			level1itemparentstyles: make_json_fields('level1itemparentstyles'),
			level2menustyles: make_json_fields('level2menustyles'),
			level2itemnormalstyles: make_json_fields('level2itemnormalstyles'),
			level2itemhoverstyles: make_json_fields('level2itemhoverstyles'),
			level2itemactivestyles: make_json_fields('level2itemactivestyles'),
			level1itemnormalstylesicon: make_json_fields('level1itemnormalstylesicon'),
			level1itemhoverstylesicon: make_json_fields('level1itemhoverstylesicon'),
			level2itemnormalstylesicon: make_json_fields('level2itemnormalstylesicon'),
			level2itemhoverstylesicon: make_json_fields('level2itemhoverstylesicon'),
			level3menustyles: make_json_fields('level3menustyles'),
			level3itemnormalstyles: make_json_fields('level3itemnormalstyles'),
			level3itemhoverstyles: make_json_fields('level3itemhoverstyles'),
			headingstyles: make_json_fields('headingstyles'),
			fancystyles: make_json_fields('fancystyles'),
			orientation: $ck('input[name=orientation]:checked').val(),
			layout: $ck('.layoutthumb.selected').attr('data-name'),
			frommoduleid: $ck('#frommoduleid').val(),
			customcss: $ck('#customcss').val()
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			// force to return the id and name if new style
			if ($ck('#id').val() == '') $ck('#returnFunc').val('ckSelectStyle');

			if (response.result == '1') {
				$ck('#id').val(response.id);
			} else {
				alert(response.message);
			}
			if ($ck('#returnFunc').val() == 'ckSelectStyle' && typeof(window.parent.ckSelectStyle) == 'function') {
				window.parent.ckSelectStyle($ck('#id').val(), $ck('#name').val(), false)
			}

			// B/C for old module compatibility
			if (response.moduleid > 0) {
				var params = get_params_list();
				for (i=0;i<params.length;i++) {
					var param = params[i];
					var value = make_json_fields(param);

					if (window.parent.document.getElementById('jform_params_'+param)) window.parent.document.getElementById('jform_params_'+param).value = value;
				}

				// save theme and layout
				// if (window.parent.document.getElementById('jform_params_theme')) window.parent.document.getElementById('jform_params_theme').value = $ck('#theme').val();
				// if (window.parent.document.getElementById('jform_params_customcss')) window.parent.document.getElementById('jform_params_customcss').value = $ck('#customcss').val();
			}
			if (window.parent.document.getElementById('jform_params_layout')) window.parent.document.getElementById('jform_params_layout').value = '_:' + $ck('.layoutthumb.selected').attr('data-name');
		}
		catch (e) {
			alert(e);
		}
		ckRemoveSpinnerIcon(button);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}


/**
 * Float the preview on scroll to have it always visible
 */
function ckSetFloatingOnPreview() {
	var el = $ck('#previewarea');
	el.data('top', el.offset().top);
	el.data('istopfixed', false);
	$ck(window).bind('scroll load', function() { ckFloatElement(el); });
	ckFloatElement(el);
}

/**
 * Float the preview on scroll to have it always visible
 */
function ckFloatElement(el) {
	var $window = $ck(window);
	var winY = $window.scrollTop();
	if (winY > (el.data('top')-65) && !el.data('istopfixed')) {
		el.after('<div id="' + el.attr('id') + 'tmp"></div>');
		$ck('#'+el.attr('id')+'tmp').css('visibility', 'hidden').height(el.height());
		el.css({position: 'fixed', zIndex: '1000', marginTop: '0px', top: '70px'})
			.data('istopfixed', true)
			.addClass('istopfixed');
	} else if (el.data('top') >= (winY+65) && el.data('istopfixed')) {
		var modtmp = $ck('#'+el.attr('id')+'tmp');
		el.css({position: '', marginTop: ''}).data('istopfixed', false).removeClass('istopfixed');
		modtmp.remove();
	}
}

function ckLoadGfontStylesheets() {
	var gfonturl1 = $ck('#menustylestextisgfont').val() ? ckGetGfontStylesheet($ck('#menustylestextgfont').val())  : '';
	var gfonturl2 = $ck('#level2menustylestextisgfont').val() ? ckGetGfontStylesheet($ck('#level2menustylestextgfont').val()) : '';
	var gfonturl3 = $ck('#level3menustylestextisgfont').val() ? ckGetGfontStylesheet($ck('#level3menustylestextgfont').val()) : '';

	jQuery('#previewarea > .ckgfontstylesheet').html(gfonturl1 + gfonturl2 + gfonturl3);
}

function ckGetGfontStylesheet(family) {
	if (! family) return '';
	return ("<link href='https://fonts.googleapis.com/css?family="+family+"' rel='stylesheet' type='text/css'>");
}

/**
* Set the params array
* B/C function
*/
function get_params_list() {
	var styles = new Array('menustyles'
						,'level1itemnormalstyles'
						,'level1itemdescstyles'
						,'level1itemnormalstylesicon'
						,'level1itemhoverstyles'
						,'level1itemactivestyles'
						,'level1itemactivehoverstyles'
						,'level1itemparentstyles'
						,'level2menustyles'
						,'level2itemnormalstyles'
						,'level2itemnormalstylesicon'
						,'level2itemhoverstyles'
						,'level2itemactivestyles'
						,'level1itemparentstyles'
						,'level1itemhoverstylesicon'
						,'level2itemhoverstylesicon'
						,'level3menustyles'
						,'level3itemnormalstyles'
						,'level3itemhoverstyles'
						,'headingstyles'
						,'fancystyles'
//						,'customcss'
						);

	return styles;
}

/**
* Load the stylesheet from the module theme
*/
function ckLoadThemeCss(theme) {
	// ajax call to get the css code
	var myurl = MAXIMENUCK.BASE_URL + "&task=style.ajaxGetThemeCss&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			theme: theme
		}
	}).done(function(code) {
		$ck('#previewarea > .ckstyletheme').empty().append(code);
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

/**
* Load the php module layout
*/
function ckChangeLayout(name, orientation) {
	ckAddWaitIcon('#ckpopupstyleswizard_makepreview');
	if (! orientation) orientation = $ck('input[name=orientation]:checked').val();
	$ck('.layoutthumb').removeClass('selected');
	$ck('.layoutthumb[data-name=' + name + ']').addClass('selected');
	// var myurl = MAXIMENUCK.BASE_URL + '&task=style.renderModuleLayout&' + MAXIMENUCK.TOKEN;
	var myurl = MAXIMENUCK.BASE_URL + '&view=style&layout=edit_render_menu_module&modulelayout=' + name + '&' + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: {
			orientation: orientation
		}
	}).done(function(response) {
		response = response.trim();
		if ( response.substring(0,5).toLowerCase() == 'error' ) {
			ckRemoveWaitIcon('#ckpopupstyleswizard_makepreview');
			show_ckmodal(response);
			// alert(response);
		} else {
			$ck('#previewarea > .inner').empty().append(response);
			ckRemoveWaitIcon('#ckpopupstyleswizard_makepreview');
			ckPreviewStyles();
		}
		
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

/*
* Change the menu orientation
*/
function ckChangeMenuOrientation(orientation) {
	ckChangeLayout($ck('.layoutthumb.selected').attr('data-name'), orientation);
	if ( orientation === 'vertical' ) {
		$ck('#previewarea > .inner').css('width', '200px');
		$ck('.menulink[tab="tab_themesvertical"]').trigger('click');
	} else {
		$ck('#previewarea > .inner').css('width', '');
		$ck('.menulink[tab="tab_themeshorizontal"]').trigger('click');
	}
}

function ckMakeCharReplacements(data) {
	data = data.replace(/\|nl\|/g, "\n");
	data = data.replace(/\|dp\|/g, ":");
	data = data.replace(/\|ob\|/g, "\{");
	data = data.replace(/\|cb\|/g, "\}");
	data = data.replace(/\|qq\|/g, '"');

	return data;
}

function ckBCFieldsInterface(fields) {
	fields = fields.replace(/bgcolor1/g, 'backgroundcolorstart');
	fields = fields.replace(/bgcolor2/g, 'backgroundcolorend');
	fields = fields.replace(/bgopacity/g, 'backgroundopacity');
	fields = fields.replace(/bgimage/g, 'backgroundimageurl');
	fields = fields.replace(/bgimagerepeat/g, 'backgroundimagerepeat');
	fields = fields.replace(/bgpositionx/g, 'backgroundimageleft');
	fields = fields.replace(/bgpositiony/g, 'backgroundimagetop');
	fields = fields.replace(/bordertopwidth/g, 'bordertopsize');
	fields = fields.replace(/borderrightwidth/g, 'borderrightsize');
	fields = fields.replace(/borderbottomwidth/g, 'borderbottomsize');
	fields = fields.replace(/borderleftwidth/g, 'borderleftsize');
	fields = fields.replace(/roundedcornerstl/g, 'borderradiustopleft');
	fields = fields.replace(/roundedcornerstr/g, 'borderradiustopright');
	fields = fields.replace(/roundedcornersbl/g, 'borderradiusbottomleft');
	fields = fields.replace(/roundedcornersbr/g, 'borderradiusbottomright');
	fields = fields.replace(/fontcolor/g, 'color');
	fields = fields.replace(/stylesfontcolor/g, 'stylescolor');
	fields = fields.replace(/stylesdescfontcolor/g, 'stylesdesccolor');
	fields = fields.replace(/shadowoffsetx/g, 'shadowoffseth');
	fields = fields.replace(/shadowoffsety/g, 'shadowoffsetv');

	return fields;
}

/**
* Clear all fields
*/
function ckClearFields(avoidpreview) {
	if (avoidpreview) avoidpreview = false;
	var confirm_clear = confirm('This will delete all your settings and reset the styles. Do you want to continue ?');
	if (confirm_clear == false) return;
	$ck('#styleswizard_options input').each(function(i, field) {
		field = $ck(field);
		if (field.attr('type') == 'radio') {
			field.removeAttr('checked');
		} else {
			field.val('');
			if (field.hasClass('color')) field.css('background','');
		}
	});
	// launch the preview
	if (! avoidpreview) ckPreviewStyles();
}

/**
 * Loads the file from the preset and apply it to all fields
 */
function ckLoadPreset(name, orientation) {
	var confirm_clear = ckClearFields(true);
	if (confirm_clear == false) return;

	var button = '#ckpopupstyleswizard_makepreview .ckwaiticon';
	ckAddWaitIcon(button);

	// remove the values for all the fields
	

	// ajax call to get the fields
	var myurl = MAXIMENUCK.BASE_URL + "&task=style.loadPresetFields&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		dataType: 'json',
		data: {
			preset: orientation + '/' + name
		}
	}).done(function(r) {
		if (r.result == 1) {
			var fields = r.fields;
			fields = fields.replace(/\|qq\|/g, '"');
			// fields = ckBCFieldsInterface(fields);
			ckSetFieldsValue(fields);

			// get the value for the custom css
			ckLoadPresetCustomcss(name, orientation);
			// set the theme to blank automatically
			// var theme = 'blank';
			// change_theme_stylesheet(theme);
//			save_param(id, 'theme', theme);
//			if (window.parent.document.getElementById('jform_params_theme')) window.parent.document.getElementById('jform_params_theme').value = theme;
		} else {
			alert('Message : ' + r.message);
			ckRemoveWaitIcon(button);
		}
		
	}).fail(function() {
		//alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});

	
}

function ckLoadPresetCustomcss(name, orientation) {
	var button = '#ckpopupstyleswizard_makepreview .ckwaiticon';
	// ckAddWaitIcon(button); // already loaded in the previous ajax function load_preset()
	// ajax call to get the custom css
	var myurl = MAXIMENUCK.BASE_URL + "&task=style.loadPresetCustomcss&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			preset: orientation + '/' + name
		}
	}).done(function(r) {
		if (r.substr(0, 7) == '|ERROR|') {
			alert('Message : ' + r);
		} else {
			$ck('#customcss').val(r);
			ckPreviewStyles();
		}
		ckRemoveWaitIcon(button);
	}).fail(function() {
		//alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

/**
 * Export all settings in a json encoded file and send it to the user for download
 */
function ckExportParams() {
	var jsonfields = ckMakeJsonFields();
	jsonfields = jsonfields.replace(/"/g, "|qq|");
	var styleid = $ck('#id').val();
	styleid = styleid ? styleid : $ck('#frommoduleid').val();

	var myurl = MAXIMENUCK.BASE_URL + '&task=style.exportParams&' + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: {
			jsonfields: jsonfields,
			customcss: $ck('#customcss').val(),
			styleid: styleid
		}
	}).done(function(response) {
		if (response == '1') {
			if ($ck('#ckexportfile').length) $ck('#ckexportfile').remove();
			$ck('#ckexportdownload').append('<div id="ckexportfile"><a class="ckbutton" target="_blank" href="'+MAXIMENUCK.URIROOT+'/administrator/components/com_maximenuck/export/exportParams'+styleid+'.mmck" download="exportParams'+styleid+'.mmck">'+CKApi.Text._('CK_DOWNLOAD', 'Download')+'</a></div>');
			CKBox.open({handler:'inline', content: 'ckexportpopup', fullscreen: false, size: {x: '400px', y: '200px'}});
		} else {
			alert('test')
		}
	}).fail(function() {
		// alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
	return;
}

/**
 * Ask the user to select the file to import
 */
function ckImportParams() {
	CKBox.open({id:'ckimportbox', handler:'inline', content: 'ckimportpopup', fullscreen: false, size: {x: '700px', y: '200px'}});
}

/**
 * Upload the json encoded settings and apply them in the interface
 */
function ckUploadParamsFile(formData) {
	var myurl = MAXIMENUCK.BASE_URL + '&task=style.uploadParamsFile&' + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: formData,
		dataType: 'json',
		processData: false,  // indique a jQuery de ne pas traiter les donn�es
		contentType: false   // indique a jQuery de ne pas configurer le contentType
	}).done(function(response) {
		if(typeof response.error === 'undefined')
		{
			// Success
			ckImportParamsFile(response.data);
		} else {
			console.log('ERROR: ' + response.error);
		}
	}).fail(function() {
		// alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

/**
 * Apply the json settings in the interface
 * TODO : can be replaced by the existing function ckApplyStylesparams
 */
function ckImportParamsFile(data) {
	var fields = jQuery.parseJSON(data.replace(/\|qq\|/g, "\""));
	for (var field in fields) {
		if (field == 'customcss') {
			fields[field] = ckMakeCharReplacements(fields[field]);
		}
		ckSetValueToField(field, fields[field])
	}

	// launch the preview
	ckPreviewStyles();
	CKBox.close('#ckimportbox');
}