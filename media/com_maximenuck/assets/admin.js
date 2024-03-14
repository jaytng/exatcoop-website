/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


var $ck = jQuery.noConflict();

function ckKeepAlive() {
	jQuery.ajax({type: "POST", url: "index.php"});
}

// manage the tabs
function ckInitTabs(wrap, allowClose) {
	if (! allowClose) allowClose = false;
	if (! wrap) wrap = $ck('#styleswizard_options');
	$ck('div.ckinterfacetab:not(.current)', wrap).hide();
	$ck('.ckinterfacetablink', wrap).each(function(i, tab) {
		$ck(tab).click(function() {
			if ($ck(this).hasClass('current')) {
				var taballowClose = $ck(this).attr('data-allowclose') ? $ck(this).attr('data-allowclose') : allowClose;
				if (taballowClose == true) {
					$ck('div.ckinterfacetab[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).hide();
					$ck('.ckinterfacetablink[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).removeClass('open current active');
				}
			} else {
				$ck('div.ckinterfacetab[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).hide();
				$ck('.ckinterfacetablink[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).removeClass('open current active');
				if ($ck('#' + $ck(tab).attr('data-tab'), wrap).length)
					$ck('#' + $ck(tab).attr('data-tab'), wrap).show();
				$ck(this).addClass('open current active');
			}
		});
	});
}

function ckCallImageManagerPopup(id, type) {
	if (MAXIMENUCK.ISJ4 == '1') {
		$boxfooterhtml = '<a class="ckboxmodal-button" href="javascript:void(0);" onclick="ckGetJ4Image(\'' + id + '\');CKBox.close(this);">' + Joomla.Text._('CK_SAVE_CLOSE') + '</a>';
		CKBox.open({id: 'ckmediamanager', handler: 'iframe', url: MAXIMENUCK.URIROOT + '/administrator/index.php?option=com_media&view=media&e_name='+id+'&tmpl=component'
		, footerHtml: $boxfooterhtml});
	} else {
		if (! type) type = 'image';
		CKBox.open({id: 'ckmediamanager', handler: 'iframe', url: MAXIMENUCK.ADMIN_URL + '&view=browse&type=' + type + '&func=ckSelectFile&field='+id+'&tmpl=component'});
	}
}

// automatically catch the event for the J4 media manager
window.document.addEventListener('onMediaFileSelected', e => {
	MAXIMENUCK.selectedImage = e.detail;
});

function ckGetJ4Image(field_id) {
	var data = MAXIMENUCK.selectedImage;
	if (!data || typeof data === 'object' && (!data.path || data.path === '')) {
	  Joomla.selectedFile = {};
	  // resolve({
		// resp: {
		  // success: false
		// }
	  // });
	  return;
	}

	const execTransform = (resp) => {
		if (resp.success === true) {
		  if (resp.data[0].url) {
			if (/local-/.test(resp.data[0].adapter)) {
			  // const {
				// rootFull
			  // } = Joomla.getOptions('system.paths'); // eslint-disable-next-line prefer-destructuring

			  // Joomla.selectedFile.url = resp.data[0].url.split(rootFull)[1];
			  // }
			  var imageurl = resp.data[0].url.split(MAXIMENUCK.URIROOTABS)[1];
			  ckSelectFile(imageurl, field_id);
			} else if (resp.data[0].thumb_path) {
			  Joomla.selectedFile.thumb = resp.data[0].thumb_path;
			}
		  } else {
			Joomla.selectedFile.url = false;
		  }
		}
	}

	$ck.ajax({
		url: MAXIMENUCK.URIROOT + '/administrator/index.php?option=com_media&format=json&task=api.files&url=true&path=' + data.path + '&format=json&' + MAXIMENUCK.TOKEN,
	})
	.done(function( response ) {
		const resp = JSON.parse(response);
		execTransform(resp);
		MAXIMENUCK.selectedImage = {}; // empty the image selection
	})
	.fail(function() {
		alert('FAILED');
	});
}


function ckCallVideoManagerPopup(id) {
	CKBox.open({handler: 'iframe', url: 'index.php?option=com_maximenuck&view=browse&type=video&func=ckSelectVideo&field='+id+'&tmpl=component'});
}

function ckSelectFile(file, field) {
	if (! field) {
		alert('ERROR : no field given in the function ckSelectFile');
		return;
	}
	$ck('#'+field).val(file).trigger('change');
	CKBox.close('#ckmediamanager')
}

function ckSelectFolder(path, field) {
	if (! field) {
		alert('ERROR : no field given in the function ckSelectFolder');
		return;
	}
	$ck('#'+field).val(path).trigger('change');
}

function ckSelectVideo(file, field) {
	if (! field) {
		alert('ERROR : no field given in the function ckSelectFile');
		return;
	}
	$ck('#'+field).val(file).trigger('change');
}

function ckCallMenusSelectionPopup(id) {
	CKBox.open({handler: 'iframe', url: 'index.php?option=com_maximenuck&view=menus&fieldid='+id+'&tmpl=component', id:'ckmenusmodal', size: {x: 800, y: 450}});
}

function ckCallArticleEditionPopup(id) {
//	CKBox.open({handler: 'iframe', url: 'index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id='+id});
	ckLoadIframeEdition('index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id='+id, 'maximenuckarticleedition', 'article.apply', 'article.cancel', false)
}

function ckLoadIframeEdition(url, htmlId, taskApply, taskCancel, close) {
	CKBox.open({id: htmlId, 
				url: url,
				style: {padding: '10px'},
				onCKBoxLoaded : function(){ckLoadedIframeEdition(htmlId, taskApply, taskCancel);},
				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\''+htmlId+'\', ' + close + ')">'+CKApi.Text._('MAXIMENUCK_SAVE')+'</a>'
			});
}

function ckLoadedIframeEdition(boxid, taskApply, taskCancel) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.load(function() {
		var framehtml = frame.contents();
		framehtml.find('button[onclick^="Joomla.submitbutton"]').remove();
		framehtml.find('form').prepend('<button style="display:none;" id="applyBtn" onclick="Joomla.submitbutton(\''+taskApply+'\');" ></button>')
		framehtml.find('form').prepend('<button style="display:none;" id="cancelBtn" onclick="Joomla.submitbutton(\''+taskCancel+'\');" ></button>')
	});
}

function ckSaveIframe(boxid, close) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.contents().find('#applyBtn').click();
	if (close) CKBox.close($ck('#'+boxid).find('.ckboxmodal-button'), true);
}


/*-----------------------------
 * Edition interface
 ------------------------------*/

/**
* Encode the fields id and value in json
*/
function ckMakeJsonFields(wrapper) {
	if (! wrapper) wrapper = 'styleswizard_options';
	var fields = new Object();
	$ck('#' + wrapper + ' input, #' + wrapper + ' select, #' + wrapper + ' textarea').each(function(i, el) {
		el = $ck(el);
		if (el.attr('type') == 'radio') {
			if (el.attr('checked')) {
				fields[el.attr('name')] = el.val();
			} else {
				// fields[el.attr('id')] = '';
			}
		} else if (el.attr('type') == 'checkbox') {
			if (el.attr('checked')) {
				fields[el.attr('name')] = '1';
			} else {
				fields[el.attr('name')] = '0';
			}
		} else {
			fields[el.attr('name')] = el.val()
				.replace(/"/g, '|qq|')
				.replace(/{/g, '|ob|')
				.replace(/}/g, '|cb|')
				.replace(/\t/g, '|tt|')
				.replace(/\n/g, '|rr|');
		}
	});
	fields = JSON.stringify(fields);
	fields = ckEncodeChars(fields);

	return fields;
	// return fields.replace(/"/g, "|qq|");
}

function ckEncodeChars(text) {
	return text.replace(/"/g, '|qq|')
				.replace(/{/g, '|ob|')
				.replace(/}/g, '|cb|')
				.replace(/\t/g, '|tt|')
				.replace(/\n/g, '|rr|');
}

function ckDecodeChars(text) {
	return text.replace(/\|quot\|/g, '"')
				.replace(/\|qq\|/g, '"')
				.replace(/\|ob\|/g, '{')
				.replace(/\|cb\|/g, '}')
				.replace(/\|tt\|/g, "\t")
				.replace(/\|rr\|/g, "\n");
}

// for alternative encoding not altering the json
function ckDecodeCharsAfterJson(text) {
	return text.replace(/\|qq2\|/g, '"')
				.replace(/\|ob2\|/g, '{')
				.replace(/\|cb2\|/g, '}')
				.replace(/\|tt2\|/g, "\t")
				.replace(/\|rr2\|/g, "\n");
}

function ckReadJsonFields(jsonfields) {
	jsonfields = ckDecodeChars(jsonfields);
	var fields = JSON.parse(jsonfields);
	for (var key in fields) {
		fields[key] = ckDecodeCharsAfterJson(fields[key]);
	}

	return fields;
}



/**
* Set the stored value for each field
*/
/*function ckApplyStylesparams() {
	if ($ck('#params').val()) {
		var fields = JSON.parse($ck('#params').val().replace(/\|qq\|/g, "\""));
		for (var field in fields) {
			ckSetValueToField(field, fields[field])
		}
	}
	// launch the preview to update the interface
	ckPreviewStylesparams();
}*/

/**
* Set the value in the specified field
*/
function ckSetValueToField(id, value) {
	var field = $ck('#' + id);
	if (!field.length) {
		if ($ck('#styleswizard_options input[name=' + id + ']').length) {
			$ck('#styleswizard_options input[name=' + id + ']').each(function(i, radio) {
				radio = $ck(radio);
				if (radio.val() == value) {
					radio.attr('checked', 'checked');
				} else {
					radio.removeAttr('checked');
				}
			});
		}
	} else if (field.attr('type') == 'checkbox') {
		if (value == '1') field.attr('checked', 'checked');
	} else {
		if (field.hasClass('color')) field.css('background',value);
		value = value.replace(/\|rr\|/g, "\n");
		value = value.replace(/\|tt\|/g, "\t");
		value = value.replace(/\|ob\|/g, "{");
		value = value.replace(/\|cb\|/g, "}");
		value = value.replace(/\|quot\|/g, '"');
		$ck('#' + id).val(value);
	}
}

function ckMakeCssReplacement(code) {
	for (var tag in MAXIMENUCK.CKCSSREPLACEMENT) {
		var i = 0;
		while (code.indexOf(tag) != -1 && i < 100) {
			code = code.replace(tag, MAXIMENUCK.CKCSSREPLACEMENT[tag]);
			i++;
		}
	}
	return code;
}

/**
* Clear all fields
*/
function ckClearFields() {
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
	ckPreviewStylesparams();
}




/**
 * Alerts the user about the conflict between gradient and image background
 */
function ckCheckGradientImageConflict(from, field) {
	if ($ck(from).val()) {
		if ($ck('#'+field).val()) {
			alert('Warning : you can not have a gradient and a background image at the same time. You must choose which one you want to use');
		}
	}
}

function ckSetFloatingOnPreview() {
	var el = $ck('#previewarea');
	el.data('top', el.offset().top);
	el.data('istopfixed', false);
	$ck(window).bind('scroll load', function() { ckFloatElement(el); });
	ckFloatElement(el);
}


function ckFloatElement(el) {
	var $window = $ck(window);
	var winY = $window.scrollTop();
	if (winY > (el.data('top')-70) && !el.data('istopfixed')) {
		el.after('<div id="' + el.attr('id') + 'tmp"></div>');
		$ck('#'+el.attr('id')+'tmp').css('visibility', 'hidden').height(el.height());
		el.css({position: 'fixed', zIndex: '1000', marginTop: '0px', top: '70px'})
			.data('istopfixed', true)
			.addClass('istopfixed');
	} else if ((el.data('top')-70) >= winY && el.data('istopfixed')) {
		var modtmp = $ck('#'+el.attr('id')+'tmp');
		el.css({position: '', marginTop: ''}).data('istopfixed', false).removeClass('istopfixed');
		modtmp.remove();
	}
}

/**
 * Play the animation in the Preview area 
 */
function ckPlayAnimationPreview(prefix) {
	$ck('#stylescontainer .cameraSlide,#stylescontainer .cameraContent').removeClass('cameracurrent');
	var t = setTimeout( function() {
		$ck('#stylescontainer .cameraSlide,#stylescontainer .cameraContent').addClass('cameracurrent');
	}, ( parseFloat($ck('#' + prefix + 'animdur').val()) + parseFloat($ck('#' + prefix + 'animdelay').val()) ) * 1000);
}

/**
 * Add the spinner icon
 */
function ckAddWaitIcon(button) {
	$ck(button).addClass('ckwait');
}

/**
 * Remove the spinner icon
 */
function ckRemoveWaitIcon(button) {
	$ck(button).removeClass('ckwait');
}

function ckAddSpinnerIcon(btn) {
	btn = $ck(btn);
	if (! btn.attr('data-class')) var icon = btn.find('.fa').attr('class');
	btn.attr('data-class', icon).find('.fa').attr('class', 'fa fa-spinner fa-pulse');
}

function ckRemoveSpinnerIcon(btn) {
	btn = $ck(btn);
	btn.find('.fa').attr('class', btn.attr('data-class'));
}

/**
 * Loads the file from the preset and apply it to all fields
 */
function ckLoadPreset(name) {
	var confirm_clear = ckClearFields();
	if (confirm_clear == false) return;

	var button = '#ckpopupstyleswizard_makepreview .ckwaiticon';
	ckAddWaitIcon(button);


	// ajax call to get the fields
	var myurl = MAXIMENUCK.BASE_URL + '&task=style.loadPresetFields&' + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
//		dataType: 'json',
		data: {
			preset: name
		}
	}).done(function(r) {
		r = JSON.parse(r);
		if (r.result == 1) {
			var fields = r.fields;
			fields = fields.replace(/\|qq\|/g, '"');
//			fields = fields.replace(/\|ob\|/g, '{');
//			fields = fields.replace(/\|cb\|/g, '}');
			ckSetFieldsValue(fields);
			ckPreviewStylesparams();
		} else {
			alert('Message : ' + r.message);
			ckRemoveWaitIcon(button);
		}
		
	}).fail(function() {
		//alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});

	
}

function ckSetFieldsValue(fields) {
	fields = JSON.parse(fields);
	for (field in fields) {
		ckSetValueToField(field, fields[field]);
	}
}

/** Google font management **/
function ckCleanGfontName(field) {
	var myurl = 'index.php?option=com_maximenuck&task=cleanGfontName';
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: {
			gfont: $ck(field).val().replace("<", "").replace(">", "")
		}
	}).done(function(response) {
		response = response.trim();
		if ( response.substring(0,5).toLowerCase() == 'error' ) {
			// show_ckmodal(response);
			error.log(response);
		} else {
			$ck(field).val(response);
		}
		ckCheckFontExists(field);
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckCheckFontExists(field) {
	if (!field.value) return;
	var myurl = 'https://fonts.googleapis.com/css?family=' + field.value;
	$ck.ajax({
		url: myurl,
		data: {

		},
		statusCode: {
			200: function() {
				$ck(field).next('.isgfont').val('1');
				ckLoadGfontStylesheets();
			}
		}
	}).done(function(response) {
		$ck(field).next('.isgfont').val('0');
	}).fail(function() {
		alert(CKApi.Text._('CK_IS_NOT_GOOGLE_FONT', 'This is not a google font, check that it is loaded in your website'));
		$ck(field).next('.isgfont').val('0');
	});
}

function ckLoadGfontStylesheets() {
	var gfonturls = '';
	$ck('.isgfont').each(function() {
		console.log($ck(this).val());
		if ($ck(this).val() == '1') {
			var gfonturl = ckGetFontStylesheet($ck(this).prev('.gfonturl').val());
		console.log(gfonturl);
			gfonturls += gfonturl;
		}
	});

	$ck('#ckpopupstyleswizardgfont').html(gfonturls);
}

function ckGetFontStylesheet(family) {
	if (! family) return '';
	return ("<link href='https://fonts.googleapis.com/css?family="+family+"' rel='stylesheet' type='text/css'>");
}

function ckEditItem(btn) {
	var $item = $ck($ck(btn).parents('.ck-menu-item')[0]);
	$ck('.ckitemfocus').removeClass('ckitemfocus');
	$item.addClass('ckitemfocus');
	ckShowItemEdition($item.attr('data-type'), $item);
}

function ckShowItemEdition(type, item) {
	CKBox.open({handler: 'inline', id: 'ck-item-edition-popup', content: 'ck-item-edition'
		, style: {padding: '10px', overflow: 'auto'}, size: {x: '600px', y: '600px'},
		footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveItem(\'' + type + '\')">' + CKApi.Text._('CK_SAVE') + '</a>'
	});
	if (! item) item = false;
	var myurl = MAXIMENUCK.BASE_URL + "&task=interface.load&layout=edit_" + type + "&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
//			id: $ck('#id').val(),
//			name: $ck('#name').val(),
//			layout: layout
		}
	}).done(function(code) {
		var editionarea = $ck('#ck-item-edition');
		editionarea.empty().append(code);
		if (item) {
			var settings = ckReadJsonFields(item.attr('data-settings'));
			for (var name in settings) {
				editionarea.find('[name="' + name + '"]').val(settings[name]);
			}
			editionarea.find('[name="title"]').val(item.find('> .ck-menu-item-row .ck-menu-item-title').text());
			editionarea.find('[name="desc"]').val(item.find('> .ck-menu-item-row .ck-menu-item-desc').text());
			if (item.attr('data-id')) editionarea.find('[name="id"]').val(item.attr('data-id'));
		}
		ckLoadEditionItem();
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckAppendNewItem(type, id, title, desc, settings, img) {
	if (! id) id = '0';
	if (! settings) settings = '|ob||qq|id|qq|:|qq|' + id + '|qq||cb|';
	var level = $ck('.ckfocuscolumn').parents('.ck-columns').length + 1;
	var find = ["--level--", "--title--", "--desc--", "--id--", "--settings--", "--type--"];
	var replace = [level, title, desc, id, settings, type];
	var newitem = replaceArray(cktemplateitem, find, replace);
	var $newitem = $ck(newitem);
	// for third party plugins, wrapp the title in a badge
	var thirdparty = $ck('#ck-item-edition').find('[name="thirdparty"]').length ? $ck('#ck-item-edition').find('[name="thirdparty"]').val() : '0';

	if (thirdparty === '1') $newitem.find('> .ck-menu-item-row .ck-menu-item-title').addClass('ckbadge ckbadge-success');
	if (img) {
		if (! $newitem.find('> .ck-menu-item-row .ck-menu-item-img').length) {
			$newitem.find('> .ck-menu-item-row').prepend('<span class="ck-menu-item-img"></span>');
		}
		$newitem.find('> .ck-menu-item-row .ck-menu-item-img').html(img);
	}
	ckAddItemControls($newitem);
	ckSetIconType($newitem);
	$ck('.ckfocuscolumn').append($newitem);
	CKBox.close();
}

function ckLoadEditionItem() {
	//	empty function overrided by the specific item edition popup
}

function ckBeforeSaveItem() {
	//	empty function overrided by the specific item edition popup
}

function ckSaveItem(type) {
	ckBeforeSaveItem();
	var itemedition = $ck('#ck-item-edition');
	if (type == 'image') {
//		var title = CKApi.Text._('CK_IMAGE');
		var title = '';
		var desc = '';
		if ($ck('#imageurl').val()) {
			var imageurl = MAXIMENUCK.URIROOT +  '/' + $ck('#imageurl').val();
		} else {
			var imageurl = '';
		}
		var img = '<img src="' + imageurl + '" />';
		var id = 0;
	} else {
		var title = itemedition.find('[name="title"]').val();
		var desc = itemedition.find('[name="desc"]').val();
		var img = '';
		var id = itemedition.find('[name="id"]').val();
	}
	var jsonfields = ckMakeJsonFields('ck-item-edition');

	// if existing item
	if ($ck('.ckitemfocus').length) {
		var $item = $ck('.ckitemfocus');
		$item.find('> .ck-menu-item-row .ck-menu-item-title').text(title);
		$item.find('> .ck-menu-item-row .ck-menu-item-desc').text(desc);
		if (img) {
			if (! $item.find('> .ck-menu-item-row .ck-menu-item-img').length) {
				$item.find('> .ck-menu-item-row').prepend('<span class="ck-menu-item-img"></span>');
			}
			$item.find('> .ck-menu-item-row .ck-menu-item-img').html(img);
		}
		$item.attr('data-settings', jsonfields);
		$item.attr('data-id', id);

		var thirdparty = $ck('#ck-item-edition').find('[name="thirdparty"]').length ? itemedition.find('[name="thirdparty"]').val() : '0';
		if (thirdparty === '1') $item.find('> .ck-menu-item-row .ck-menu-item-title').addClass('ckbadge ckbadge-success');
	// if new item to create
	} else {
		var type = itemedition.find('[name="type"]').val();
//		if (thirdparty === '1') title = '<span class="ckbadge ckbadge-success">' + title + '</span>';
		ckAppendNewItem(type, id, title, desc, jsonfields, img);
	}
	CKBox.close();
}

function replaceArray(replaceString, find, replace) {
	var regex; 
	for (var i = 0; i < find.length; i++) {
		regex = new RegExp(find[i], "g");
		replaceString = replaceString.replace(regex, replace[i]);
	}
	return replaceString;
}

function ckRemoveItem(btn, selector) {
	if (! selector) selector = '.ck-menu-item';
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	var $item = $ck($ck(btn).parents(selector)[0]);
	$item.remove();
	$ck('.cktooltip').remove();
}

function ckRemoveBlock(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	var $item = $ck($ck(btn).parents('.ck-column')[0]);
	$item.remove();
	$ck('.cktooltip').remove();
}

function ckSelectFaIcon(iconclass, field) {
	$ck('#' + field).val(iconclass);
}

/* remove the root path for the image to be shown in the editor */
function ckContentToEditor(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="' + MAXIMENUCK.URIROOT.replace('/', '\/')+'\/(.*?)"',"g");
	content = content.replace(search, '<img $1src="$2"');

	return content;
}

/* add the root path for the image to be shown in the pagebuilder */
function ckEditorToContent(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="(.*?)"',"g");
	var images = content.match(search);
	if (images) {
		for (var i = 0; i < images.length; i++) {
			if (images[i].indexOf('src="http') == -1) {
				var image = images[i].replace(search, '<img $1src="' + MAXIMENUCK.URIROOT + '/$2"');
				content = content.replace(images[i], image);
			}
		}
	}
//	content = content.replace(search, '<img $1src="'+MAXIMENUCK.URIROOT+'/$2"');

	return content;
}

function ckUpdateItemId(type, id, title, desc, settings) {
	$ck('#ck-item-edition-popup [name="id"]').val(id);
	$ck('#ck-item-edition-popup [name="title"]').val(title);
	CKBox.close('#ckitemsselectpopup');
}



function ckAddWaitIcon(button) {
	var icon = $ck(button).find('i');
	if (icon.hasClass('fa-spinner')) return;
	icon.attr('data-class', icon.attr('class')).attr('class', '');
	icon.attr('class', 'fas fa-spin fa-spinner');
}

function ckRemoveWaitIcon(button, failed) {
	var icon = $ck(button).find('i');
	if (failed) {
		icon.attr('class','fas fa-exclamation-triangle');
	} else {
		icon.attr('class', icon.attr('data-class'));
	}
}

function ckGetUniqueID() {
	var now = new Date().getTime();
	var id = parseInt(now, 10);

	return id;
}