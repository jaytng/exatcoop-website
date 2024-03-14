/**
 * @name		Maxi Menu CK
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014-2022. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

var $ck = jQuery.noConflict();

function ckInitItems() {
	$ck('.ck-menu-item').each(function() {
		var $item = $ck(this);
		ckAddItemControls($item);
		ckSetIconType($item);
		ckCheckIfParent($item);
	});
	$ck('.ck-column').each(function() {
		var $col = $ck(this);
		ckInitColumn($col);
	});
	$ck('.ck-column-break').each(function() {
		var $col = $ck(this);
		ckAddRowControls($col);
	});
	$ck('.ck-item-selection').click(function() {
		ckCreateItem($ck(this).attr('data-type'));
	});
}

function ckInitColumn($col) {
	ckAddColumnControls($col);
}

function ckSetIconType($item) {
	var type = $item.attr('data-type');
	var icon;
	switch (type) {
		default :
			icon = '';
			break;
		case 'menuitem': 
			icon = 'fas fa-link';
			break;
		case 'module': 
			icon = 'fas fa-cube';
			break;
		case 'image': 
//			icon = 'fas fa-image';
			icon = '';
			break;
		case 'heading': 
			icon = 'fas fa-heading';
			break;
	}
	$item.find('> .ck-menu-item-row').append('<i class="' + icon + '" aria-hidden="true"></i>');
}

function ckAddItemControls($item) {
	$item.prepend('<div class="ck-menu-item-move"><i class="fas fa-arrows-alt"></i></div>');
	$item.append('<div class="ck-menu-item-remove ck-menu-item-action" onclick="ckRemoveItem(this)"><i class="fas fa-trash"></i></div>');
	$item.append('<div class="ck-menu-item-state ck-menu-item-action cktip" title="' + Joomla.JText._('CK_STATE') + '" onclick="ckUpdateStateItem(this)"><i class="fas ' + ($item.attr('data-state') === '0' ? 'fa-times-circle' : 'fa-check-circle') + '"></i></div>');
	$item.append('<div class="ck-menu-item-style ck-menu-item-action cktip" title="' + Joomla.JText._('CK_STYLES') + '" onclick="ckEditStylesItem(this)"><i class="fas fa-paint-brush"></i></div>');
	$item.append('<div class="ck-menu-item-edit ck-menu-item-action cktip" title="' + Joomla.JText._('CK_EDITION') + '" onclick="ckEditItem(this);"><i class="fas fa-cog"></i></div>');
}

function ckCheckIfParent($item) {
	if ($item.find('> .ck-submenu .ck-menu-item').length) {
		$item.addClass('ck-menu-item-parent');
	}
}

function ckInitParentItems() {
	var $items = $ck('#ckmenucreator');
	ckInitParentItem($items)
}

function ckInitParentItem($item) {
	$item.find('.ck-menu-item-row').click(function() {
		var $link = $ck(this);
		if ($link.parent().attr('data-type').indexOf('autoload.') === 0) {
			alert(Joomla.JText._('CK_AUTOLOADER_NO_SUBMENU'));
			return;
		} // do not focus because there is no submenu on autoloaders

		if ($link.hasClass('ckfocus')) {
			$link.parent().find('> div.ck-submenu').fadeOut(100).css('z-index', '');
			$link.removeClass('ckfocus');
//			ckRemoveOverlays();
		} 
		else {
			// $link.parent().parent().find('.ck-menu-item-row').removeClass('ckfocus');
			$link.addClass('ckfocus');
			// $ck('div.ck-submenu').not($link.parents()).hide().css('z-index', '');
//			ckRemoveOverlays();
			$link.parent().find('> div.ck-submenu').fadeIn(100).css('z-index', '11')
			if ($link.parent().attr('data-level') > 1) $link.parent().find('> div.ck-submenu').append('<div class="ck-overlay"></div>');
		}
		ckUpdatePathway($link);
	});
	// create toolbars
	$item.find('div.ck-submenu').each(function() {
		var sub = $ck(this);
		var pathway = '<div class="ck-submenu-pathway"></div>';
		sub.prepend(pathway);
		var toolbar = '<div class="ck-submenu-toolbar">'
				+ '<div class="ck-submenu-toolbar-field cktip" title="' + Joomla.JText._('COM_MAXIMENUCK_SUBMENUWIDTH') + '"><input type="text" name="submenu-width" onblur="ckSetSubmenuData(this)" value="' + (sub.attr('data-width') ? sub.attr('data-width') : '') + '" /><i class="fas fa-arrows-alt-h cktip" title="Width"></i></div>'
				+ '<div class="ck-submenu-toolbar-field cktip" title="' + Joomla.JText._('COM_MAXIMENUCK_SUBMENUHEIGHT') + '"><input type="text" name="submenu-height" onblur="ckSetSubmenuData(this)" value="' + (sub.attr('data-height') ? sub.attr('data-height') : '') + '" /><i class="fas fa-arrows-alt-v cktip" title="Height"></i></div>'
				+ '<div class="ck-submenu-toolbar-field cktip" title="' + Joomla.JText._('COM_MAXIMENUCK_SUBMENULEFTMARGIN') + '"><input type="text" name="submenu-left" onblur="ckSetSubmenuData(this)" value="' + (sub.attr('data-left') ? sub.attr('data-left') : '') + '" /><i class="fas fa-caret-right cktip" title="Left"></i></div>'
				+ '<div class="ck-submenu-toolbar-field cktip" title="' + Joomla.JText._('COM_MAXIMENUCK_SUBMENUTOPMARGIN') + '"><input type="text" name="submenu-top" onblur="ckSetSubmenuData(this)" value="' + (sub.attr('data-top') ? sub.attr('data-top') : '') + '" /><i class="fas fa-caret-down cktip" title="Top"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-fullwidth cktip" onclick="ckSetSubmenuData(this)" title="' + Joomla.JText._('COM_MAXIMENUCK_FULLWIDTH') + '"><label><input type="checkbox" name="submenu-fullwidth" ' + (sub.attr('data-fullwidth') == '1' ? 'checked' : '') + ' /><i class="fas fa-expand"></i></label></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-tab cktip" onclick="ckProOny()" title="' + Joomla.JText._('COM_MAXIMENUCK_TAB') + '"><label><i class="fas fa-border-all"></i><input type="hidden" name="submenu-tabwidth" onblur="ckSetSubmenuData(this)" value="' + (sub.attr('data-tabwidth') ? sub.attr('data-tabwidth') : '') + '" /></label></div>'
//				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-edit cktip" onclick="ckEditStylesItem(this)" title="' + Joomla.JText._('CK_STYLES') + '"><i class="fas fa-paint-brush"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-columns cktip" onclick="ckManageColumns(this)" title="' + Joomla.JText._('COM_MAXIMENUCK_CREATE_COLUMN') + '"><i class="fas fa-columns"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-rows cktip" onclick="ckManageRows(this)" title="' + Joomla.JText._('COM_MAXIMENUCK_CREATE_ROW') + '"><i class="fas fa-grip-lines"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-close" onclick="ckCloseSubmenu(this)"><i class="fas fa-times"></i></div>'
			+ '</div>';
		sub.prepend(toolbar);
	});
}

function ckSetSubmenuData(field) {
	var $item = $ck($ck(field).parents('.ck-submenu')[0]);
	var fullwidth = $item.find('[name="submenu-fullwidth"]').prop('checked') ? '1' : '0';
	var tab = $item.find('[name="submenu-tab"]').prop('checked') ? '1' : '0';
	$item.attr('data-width', $item.find('[name="submenu-width"]').val());
	$item.attr('data-height', $item.find('[name="submenu-height"]').val());
	$item.attr('data-left', $item.find('[name="submenu-left"]').val());
	$item.attr('data-top', $item.find('[name="submenu-top"]').val());
	$item.attr('data-fullwidth', fullwidth);
	$item.attr('data-tab', tab);
	$item.attr('data-tabwidth', $item.find('[name="submenu-tabwidth"]').val());
	if (fullwidth === '1') {
		$item.find('> .ck-submenu-toolbar input[name="submenu-width"]').attr('disabled', 'disabled');
	} else {
		$item.find('> .ck-submenu-toolbar input[name="submenu-width"]').removeAttr('disabled');
	}
}

function ckRemoveOverlays() {
	$ck('.ck-overlay').remove();
}

function ckUpdatePathway($link) {
	var $pathway = $link.parent().find('> div.ck-submenu').find('.ck-submenu-pathway');
	$pathway.empty();
	$link.parents('.ck-menu-item').each(function() {
		$pathway.prepend('<div class="ck-submenu-pathway-parent">' + $ck(this).find('> .ck-menu-item-row .ck-menu-item-title').text() + '</div>');
	});
	$pathway.prepend('<div class="ck-submenu-pathway-home"><i class="fa fa-home"></i></div>');
}

function ckCloseSubmenu(btn) {
	$ck($ck(btn).parents('.ck-submenu')[0]).hide();
	$ck($ck(btn).parents('.ck-submenu')[0]).prev('.ck-menu-item-row').removeClass('ckfocus');
}

function ckInitSortable() {
	$ck('#ckmenucreator,.ck-column').sortable({
		items: '.ck-menu-item',
		handle: '.ck-menu-item-move',
		connectWith: '#ckmenucreator,.ck-column',
		placeholder: 'ck-placeholder',
		tolerance: "pointer",
		activate: function (event, ui) {
			$ck(this).sortable("refreshPositions");
//			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck') && !$ck(ui.item).hasClass('ckcontenttype') && !$ck(ui.item).hasClass('ckpageitem')) {
				$ck(ui.helper).css('width', '250px').css('height', '40px').css('overflow', 'hidden').addClass('ck-helper-placeholder');
				$ck(ui.placeholder).width($ck(ui.helper).css('width'));
//			}
			$ck('.ck-menu-item-row').css('pointer-events', 'none');
		}, 
		stop: function (event, ui) {
			$ck('.ck-menu-item-row').css('pointer-events', '');
			$ck(ui.item).removeClass('ck-helper-placeholder');
			ckUpdateItemsAfterSort('#ckmenucreator', '1');
		}
	});
	
	$ck('.ck-columns').sortable({
		items: '> .ck-column, > .ck-column-break',
		handle: '.ck-fields .ck-field-move',
		helper: 'clone',
		placeholder: 'ck-placeholder',
		forcePlaceholderSize: true,
//		forceHelperSize: true,
		tolerance: "pointer",
		activate: function (event, ui) {
			$ck(this).sortable("refreshPositions");
			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck') && !$ck(ui.item).hasClass('ckcontenttype') && !$ck(ui.item).hasClass('ckpageitem')) {
				$ck(ui.helper).css('width', '250px').css('height', '100px').css('overflow', 'hidden');
				$ck(ui.placeholder).width($ck(ui.helper).css('width'));
			}
			$ck('.ck-menu-item-row').css('pointer-events', 'none');
		}, 
		stop: function (event, ui) {
			$ck('.ck-menu-item-row').css('pointer-events', '');
		}
	});
}

function ckUpdateItemsAfterSort(parent, level) {
	if (parseInt(level) === 1) {
		$ck(parent).find(' > .ck-menu-item').each(function() {
			$ck(this).attr('data-level', level);
			ckUpdateItemsAfterSort(this, parseInt(level) + 1);
		});
	} else {
		$ck(parent).find(' > .ck-submenu > .ck-columns > .ck-column > .ck-menu-item').each(function() {
			$ck(this).attr('data-level', level);
			ckUpdateItemsAfterSort(this, parseInt(level) + 1);
		});
	}
}

function ckManageColumns(btn) {
	var $submenu = $ck($ck(btn).parents('.ck-submenu')[0]);
	var $newcol = $ck('<div class="ck-column"></div>');
	ckAddColumnControls($newcol);
	$submenu.find('> .ck-columns').append($newcol);
	ckInitSortable();
}

function ckManageRows(btn) {
	var $submenu = $ck($ck(btn).parents('.ck-submenu')[0]);
	var $newrow = $ck('<div class="ck-column-break"></div>');
	ckAddRowControls($newrow);
	$submenu.find('> .ck-columns').append($newrow);
	ckInitSortable();
}

function ckAddColumnControls($col) {
	$col.append('<div class="ck-column-add-item cktip" onclick="ckShowItemsSelection(this)" title="' + Joomla.JText._('CK_ADD_ITEM') + '"><i class="fa fa-plus"></i></div>');
	ckAddBlockEditionEvents($col);
}

function ckAddRowControls($col) {
	$col.append('<div class="ck-column-break-remove-item" onclick="ckRemoveItem(this, \'.ck-column-break\')"><i class="fa fa-trash"></i></div>');
}

function ckShowItemsSelection(btn) {
	$ck('.ckfocuscolumn').removeClass('ckfocuscolumn');
	$ck(btn).parent().addClass('ckfocuscolumn');
	CKBox.open({handler: 'inline', content: 'ck-items-selection', style: {padding: '10px'}, size: {x: '600px', y: '600px'}});
}

function ckCreateItem(type) {
	if (type === 'menuitem' || type === 'module') {
		CKBox.close();
		CKBox.open({url: MAXIMENUCK.BASE_URL + '&view=items&tmpl=component&type=' + type});
//	if (type === 'image' || type === 'heading' || type === 'text') {
	} else {
		$ck('#ck-item-edition').empty().append('<i class="fa fa-spinner fa-spin"></i>');
		CKBox.close();
		$ck('.ckitemfocus').removeClass('ckitemfocus');
		ckShowItemEdition(type);
		
	}
}

function ckRemoveEdition(bloc, all) {
	if (bloc.hasClass('ck-column')) {
		ckSetColumnData(bloc.find('[name="column-width"]')[0]);
	}
	if (!all)
		all = false;
	if (all == true) {
		$ck('.editorck', bloc).remove();
	} else {
		$ck('> .editorck', bloc).remove();
	}
}

function ckAddEditionControls(editor, bloc) {

//	var blocclass = bloc.attr('ckclass') ? bloc.attr('ckclass') : '';
	var controls = '<div class="ck-fields">'
			+ '<div class="ck-field-remove ck-field" onclick="ckRemoveBlock(this);" ><i class="fa fa-trash"></i></div>'
			+ '<div class="ck-field-move ck-field" ><i class="fas fa-arrows-alt"></i></div>'
//			+ '<div class="ck-field-edit ck-field" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"><i class="fa fa-edit"></i></div>'
			+ '<div class="ck-field-input cktip" title="' + Joomla.JText._('COM_MAXIMENUCK_COLUMN_WIDTH') + '" ><input type="text" name="column-width" onchange="ckSetColumnData(this)" value="' + (bloc.attr('data-width') ? bloc.attr('data-width') : '') + '"/><i class="fas fa-arrows-alt-h"></i></div>'
			+ "</div>";

	editor.append(controls);
}

function ckSetColumnData(field) {
	if (! $ck(field).length) return;
	var $item = $ck($ck(field).parents('.ck-column')[0]);
	$item.attr('data-width', field.value);
}

function ckAddEdition(bloc, i) {
	if (!i)
		i = 0;

	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) return;
	if ($ck('> .editorck', bloc).length && i == 0)
		return;
	var leftpos = bloc.position().left;
	var toppos = bloc.position().top;
	bloc.css('position','relative');
	var editorclass = '';
	var editor = '<div class="editorck' + editorclass + '" contenteditable="false"></div>';
	editor = $ck(editor);
	editor.css({
		'left': 0,
		'top': 0,
		'position': 'absolute',
		'z-index': 99 + 1,
		'width': bloc.outerWidth()
	});

	ckAddEditionControls(editor, bloc);

	bloc.append(editor);
	editor.css('display', 'none').fadeIn('fast');
	CKApi.Tooltip('.cktip');
}
function ckAddBlockEditionEvents(block) {
	block.mouseenter(function() {
		var i = block.parents('.ck-item').length;
		ckAddEdition(this, i);
	}).mouseleave(function() {
		var t = setTimeout( function() {
			block.removeClass('highlight_delete');
			ckRemoveEdition(block);
			
		}, 200);
		$ck('.cktooltip').remove();
	});
}

function ckGetLayoutHtml() {
	var layout = new Object();
	$ck('#ckmenucreator > .ck-menu-item').each(function(i) {
		var $item = $ck(this);
		layout[i] = ckGetLayoutItem($item);
//		layout[i]['submenu'] = ckGetLayoutSubmenu($item);
	});

	return layout;
}

function ckGetLayoutItem($item) {
	var data = new Object();
	data['type'] = $item.attr('data-type');
	data['title'] = data['type'] === 'image' ? $item.find('> .ck-menu-item-row .ck-menu-item-img img').attr('src') : $item.find('> .ck-menu-item-row .ck-menu-item-title').text();
	data['image'] = $item.find('> .ck-menu-item-row .ck-menu-item-img img').length ? $item.find('> .ck-menu-item-row .ck-menu-item-img img').attr('src') : '';
	data['desc'] = $item.find('> .ck-menu-item-row .ck-menu-item-desc').text();
	data['id'] = $item.attr('data-id');
	data['customid'] = $item.attr('data-customid');
	data['level'] = $item.attr('data-level');
	data['submenu'] = ckGetLayoutSubmenu($item);
	data['settings'] = $item.attr('data-settings');
	data['state'] = $item.attr('data-state') ? $item.attr('data-state') : '1';

	return data;
}

function ckGetLayoutSubmenu($item) {
	var layout = new Object();
	$item.find('> .ck-submenu').each(function() {
		var $submenu = $ck(this);
//		if (! $submenu.find('.ck-menu-item').length) return '';
		layout['params'] = ckGetParamsSubmenu($submenu);
		layout['columns'] = new Object();
		$submenu.find('> .ck-columns > [class*="ck-column"]').each(function(c) {
			var $column = $ck(this);
			if ($column.hasClass('ck-column-break')) {
				layout['columns'][c] = ckGetParamsColumn($column);
				layout['columns'][c]['break'] = 1;
			} else {
				layout['columns'][c] = ckGetParamsColumn($column);
				layout['columns'][c]['children'] = new Object();
				$column.find('> .ck-menu-item').each(function(i) {
					var $subitem = $ck(this);
					layout['columns'][c]['children'][i] = ckGetLayoutItem($subitem);
				});
				layout['columns'][c]['break'] = 0;
			}
		});
	});

	return layout;
}

function ckGetParamsSubmenu($submenu) {
	var data = new Object();
	data['width'] = $submenu.attr('data-width');
	data['height'] = $submenu.attr('data-height');
	data['left'] = $submenu.attr('data-left');
	data['top'] = $submenu.attr('data-top');
	data['fullwidth'] = $submenu.attr('data-fullwidth');
	data['tab'] = $submenu.attr('data-tab');
	data['tabwidth'] = $submenu.attr('data-tabwidth');

	return data;
}

function ckGetParamsColumn($column) {
	var data = new Object();
	data['width'] = $column.attr('data-width');
	data['settings'] = $column.attr('data-settings');

	return data;
}

//function ckGetLayoutItems($submenu) {
//	
//}

/**
* Save the interface
*/
function ckSaveEdition() {
	if (! $ck('#name').val()) {
		$ck('#name').addClass('invalid').focus();
		alert('Please give a name');
		return;
	}
	$ck('#name').removeClass('invalid');
	var button = '#cksave';
	ckAddSpinnerIcon(button);
	
	var layout = ckGetLayoutHtml();
//	console.log(layout);
//alert('script arreté avant ajax');
//return;

	var myurl = MAXIMENUCK.BASE_URL + "&task=menubuilder.save&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: $ck('#id').val(),
			name: $ck('#name').val(),
			layout: layout
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				var isNew = $ck('#id').val() == '';
				$ck('#id').val(response.id);
				if (isNew) {
					location.href = MAXIMENUCK.BASE_URL + "&view=menubuilder&layout=edit&tmpl=component&id=" + response.id;
					return;
				}
			} else {
				alert(response.message);
			}
//			if ($ck('#returnFunc').val() == 'ckSelectStyle') {
//				window.parent.ckSelectStyle($ck('#id').val(), $ck('#name').val(), false)
//			}
		}
		catch (e) {
			alert(e);
		}
		ckRemoveSpinnerIcon(button);
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckEditStylesItem(btn) {
	if (! $ck('#id').val()) {
		alert(Joomla.JText._('CK_SAVE_BEFORE_EDIT'));
		return;
	}

	var $item = $ck($ck(btn).parents('.ck-menu-item')[0]);
	var customid = $item.attr('data-customid');
	$ck('.ckitemfocus').removeClass('ckitemfocus');
	$item.addClass('ckitemfocus');

	CKBox.open({handler: 'inline', id: 'ck-item-edition-popup', content: 'ck-item-edition'
		, style: {padding: '10px', overflow: 'auto'}, size: {x: '700px', y: '700px'},
		footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveStylesItem(\'' + customid + '\')">' + CKApi.Text._('CK_SAVE') + '</a>'
	});
	if (! $item) $item = false;

	var editionarea = $ck('#ck-item-edition');
	editionarea.empty();

	var myurl = MAXIMENUCK.BASE_URL + "&task=interface.load&layout=edit_styles&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customid : customid
		}
	}).done(function(code) {
		editionarea.append(code);
		// if (item) {
			// var settings = ckReadJsonFields(item.attr('data-settings'));
			// for (var name in settings) {
				// editionarea.find('[name="' + name + '"]').val(settings[name]);
			// }
			// editionarea.find('[name="title"]').val(item.find('> .ck-menu-item-row .ck-menu-item-title').text());
			// editionarea.find('[name="desc"]').val(item.find('> .ck-menu-item-row .ck-menu-item-desc').text());
			// if (item.attr('data-id')) editionarea.find('[name="id"]').val(item.attr('data-id'));
		// }
		ckLoadStylesItem();
//		jscolor.init();
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckSaveStylesItem(customid) {
	var jsonfields = ckMakeJsonFields('ck-item-edition-item-styles');
	// jsonfields = jsonfields.replace(/#/g, '|dd|');
	var params = $ck('.ck-menu-item[data-customid="' + customid + '"]').attr('data-settings');

	if (! $ck('#id').val()) {
		alert('Please save the menu before editing the items');
		return;
	}

	var myurl = MAXIMENUCK.BASE_URL + "&task=menubuilder.saveItemStyles&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customid : customid,
			fields: jsonfields,
			params: params
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				CKBox.close('#ck-item-edition-popup');
			} else {
				alert(response.message);
			}
		}
		catch (e) {
			alert(e);
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckLoadStylesItem() {
	var fields = $ck('.itemstyles[name="fields"]').val();
	if (! fields) return;

	fields = fields.replace(/\|qq\|/g, '"');
	fields = fields.replace(/\|ob\|/g, '{');
	fields = fields.replace(/\|cb\|/g, '}');
	ckSetFieldsValue(fields);
}

function ckRemoveBlock(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	var $item = $ck($ck(btn).parents('.ck-column')[0]);
	var p = $item.parent();
	$item.remove();
	$ck('.cktooltip').remove();
	if (! p.find('.ck-column').length) {
		ckManageColumns(p);
	}
}

function ckEditItem(btn) {
	var $item = $ck($ck(btn).parents('.ck-menu-item')[0]);
	$ck('.ckitemfocus').removeClass('ckitemfocus');
	$item.addClass('ckitemfocus');
	ckShowItemEdition($item.attr('data-type'), $item);
}

function ckShowItemEdition(type, item) {;
	CKBox.open({handler: 'inline', id: 'ck-item-edition-popup', content: 'ck-item-edition'
		, style: {padding: '10px', overflow: 'auto'}, size: {x: '600px', y: '600px'},
		footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveItem(\'' + type + '\')">' + CKApi.Text._('CK_SAVE') + '</a>'
	});
	if (! item) item = false;
	var layout = type;
	if (type.indexOf('autoload.') === 0) {
		type = type.replace('autoload.', '');
		layout = 'autoload';
	}

	var customid = item ? item.attr('data-customid') : 0;
	var myurl = MAXIMENUCK.BASE_URL + "&task=interface.editmenubuilder&layout=edit_" + layout + "&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customid: customid,
			type: type
		}
	}).done(function(code) {
		var editionarea = $ck('#ck-item-edition');
		editionarea.empty().append(code);
		if (item) {
			// var settings = ckReadJsonFields(item.attr('data-settings')); // @removed
			var settingsValue = $ck('#ck-item-edition-popup [name="params"].itemdata').val();
			if (! settingsValue) settingsValue = item.attr('data-settings');
			if (settingsValue) {
				var settings = ckReadJsonFields(settingsValue);
				for (var name in settings) {
					var field = editionarea.find('[name="' + name + '"]');
					if (settings[name].indexOf('|array|') !== -1) {
						var values = settings[name].split('|array|');
						editionarea.find('[name="' + name + '"] option').prop('selected', false);
						for (var i=0; i<values.length; i++) {
							editionarea.find('[name="' + name + '"] [value="' + values[i] + '"]').prop('selected', true);
						}
					} else if (field.attr('type') === 'radio') {
						editionarea.find('[name="' + name + '"][value="' + settings[name] + '"]').prop('checked', 'checked');
					} else {
						field.val(settings[name]);
					}
				}
			}
			// editionarea.find('[name="title"]').val(item.find('> .ck-menu-item-row .ck-menu-item-title').text());
			// editionarea.find('[name="desc"]').val(item.find('> .ck-menu-item-row .ck-menu-item-desc').text());
//			if (item.attr('data-id')) editionarea.find('[name="id"]').val(item.attr('data-id'));
		}
		ckLoadEditionItem();
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckAppendNewItem(type, id, title, desc, settings, img, customid) {
	if (! customid) customid = ckGetUniqueID();
//	if (! settings) settings = '|ob||qq|customid|qq|:|qq|' + customid + '|qq||cb|';
	if (! settings) settings = '|ob||qq|type|qq|:|qq|' + type + '|qq|,|qq|id|qq|:|qq|' + id + '|qq|,|qq|title|qq|:|qq|' + title + '|qq||cb|';
	ckSaveItemSettings(customid, settings);
	var level = $ck('.ckfocuscolumn').parents('.ck-columns').length + 1;
	var find = ["--level--", "--title--", "--desc--", "--id--", "--settings--", "--type--", "--customid--"];
	var replace = [level, title, desc, id, settings = '', type, customid];
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
	ckInitParentItem($newitem);
	ckInitColumn($newitem.find('.ck-column'));
	ckInitSortable();
	CKBox.close();

	return customid;
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
		// var id = 0;
	} else if (type.indexOf('autoload.') === 0) {
		title = type.replace('autoload.', '').toUpperCase();
		desc = '<span class="badge">' + Joomla.JText._('CK_AUTOLOADER') + '</span>';
	} else {
		var title = itemedition.find('[name="title"]').val().replace(/"/g, '&quot;');
		var desc = itemedition.find('[name="desc"]').length ? itemedition.find('[name="desc"]').val().replace(/"/g, '&quot;') : '';
		
		if ($ck('#menu_image').val()) {
			var imageurl = MAXIMENUCK.URIROOT +  '/' + $ck('#menu_image').val();
			var img = '<img src="' + imageurl + '" />';
		} else {
			var imageurl = '';
		var img = '';
		}
		// var id = itemedition.find('[name="id"]').val();
	}
	var jsonfields = ckMakeJsonFieldsWithParams('ck-item-edition-item-params');

	// if existing item
	if ($ck('.ckitemfocus').length) {
		var $item = $ck('.ckitemfocus');
		$item.find('> .ck-menu-item-row .ck-menu-item-title').text(title);
		$item.find('> .ck-submenu > .ck-submenu-pathway .ck-submenu-pathway-parent:last-child').text(title);
		$item.find('> .ck-menu-item-row .ck-menu-item-desc').html(desc);
		if (img) {
			if (! $item.find('> .ck-menu-item-row .ck-menu-item-img').length) {
				$item.find('> .ck-menu-item-row').prepend('<span class="ck-menu-item-img"></span>');
			}
			$item.find('> .ck-menu-item-row .ck-menu-item-img').html(img);
		} else {
			$item.find('> .ck-menu-item-row .ck-menu-item-img').remove();
		}

		// $item.attr('data-settings', jsonfields); // @removed
		$item.attr('data-settings', '');
		// $item.attr('data-id', id);

		var thirdparty = $ck('#ck-item-edition').find('[name="thirdparty"]').length ? itemedition.find('[name="thirdparty"]').val() : '0';
		if (thirdparty === '1') $item.find('> .ck-menu-item-row .ck-menu-item-title').addClass('ckbadge ckbadge-success');
		var customid = $item.attr('data-customid');
	// if new item to create
	} else {
		var type = itemedition.find('[name="type"]').val();
//		if (thirdparty === '1') title = '<span class="ckbadge ckbadge-success">' + title + '</span>';
//		 var customid = ckAppendNewItem(type, 0, title, desc, jsonfields, img); // @removed

		var customid = ckAppendNewItem(type, 0, title, desc, '', img);
	}

	ckSaveItemSettings(customid, jsonfields);
}

function ckSaveItemSettings(customid, params) {
	var myurl = MAXIMENUCK.BASE_URL + "&task=menubuilder.saveItemParams&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customid : customid,
			params: params
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				CKBox.close('#ck-item-edition-popup');
			} else {
				alert(response.message);
			}
		}
		catch (e) {
			alert(e);
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
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
	var customid = $item.attr('data-customid');
	var myurl = MAXIMENUCK.BASE_URL + "&task=menubuilder.removeItem&" + MAXIMENUCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customid : customid
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				$item.remove();
				$ck('.cktooltip').remove();
			} else {
				alert(response.message);
			}
		}
		catch (e) {
			alert(e);
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckRemoveBlock(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	var $item = $ck($ck(btn).parents('.ck-column')[0]);
	$item.remove();
	$ck('.cktooltip').remove();
}

/**
* Encode the fields id and value in json, and params as array
*/
function ckMakeJsonFieldsWithParams(wrapper) {
	if (! wrapper) wrapper = 'styleswizard_options';
	var fields = new Object();
	var value;
	$ck('#' + wrapper + ' input, #' + wrapper + ' select, #' + wrapper + ' textarea').each(function(i, el) {
		el = $ck(el);
		var name = el.attr('name');
		if (el.attr('type') == 'radio') {
			if (el.prop('checked')) {
				value = el.val();
			} else {
				// fields[el.attr('id')] = '';
			}
		} else if (el.attr('type') == 'checkbox') {
			if (el.prop('checked')) {
				value = '1';
			} else {
				value = '0';
			}
		} else {
			if (! Array.isArray(el.val())) {
				value = el.val()
					.replace(/"/g, '|qq2|')
					.replace(/{/g, '|ob2|')
					.replace(/}/g, '|cb2|')
					.replace(/\t/g, '|tt2|')
					.replace(/\n/g, '|rr2|');
			} else {
				value = el.val().join('|array|')
			}
		}
		// setup the value
		if (name.indexOf('params[') === 0) {
			paramname = name.replace('params[', '').replace(']', '');
			fields[paramname] = value;
			fields[name] = value;
		} else {
			fields[name] = value;
		}
	});
	fields = JSON.stringify(fields);
	fields = ckEncodeChars(fields);

	return fields;
}

function ckUpdateItemId(type, id, title, desc, settings) {
	$ck('#ck-item-edition-popup [name="id"]').val(id);
	var update = true;
	if ($ck('#ck-item-edition-popup [name="title"]').val()) {
		update = confirm(Joomla.JText._('CK_CONFIRM_UPDATE_TITLE'));
	}
	if (update) $ck('#ck-item-edition-popup [name="title"]').val(title);
	CKBox.close('#ckitemsselectpopup');
}

function ckUpdateModuleId(type, id, title, desc, settings) {
	$ck('#ck-item-edition-popup [name="id"]').val(id);
	var update = true;
	$ck('#ck-item-edition-popup [name="title"]').val(title);
	CKBox.close('#ckitemsselectpopup');
}

function ckUpdateStateItem(btn) {
	var $item = $ck($ck(btn).parents('.ck-menu-item')[0]);
	if ($item.attr('data-state') === '0') {
		$item.attr('data-state', '1');
		$ck(btn).find('> i').attr('class', 'fas fa-check-circle');
	} else {
		$item.attr('data-state', '0');
		$ck(btn).find('> i').attr('class', 'fas fa-times-circle');
	}
}