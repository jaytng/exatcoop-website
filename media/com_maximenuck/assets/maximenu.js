/**
 * @name		Maxi Menu CK
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014-2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

var $ck = jQuery.noConflict();

/*
jQuery(document).ready(function($){
	CKBox.initialize({});
	CKBox.assign($('a.modal'), {
		parse: 'rel'
	});

	// manage the tabs
//		ckInitTabs();
	ckInitParentItems();
	ckInitItems();
	ckInitSortable();
	CKApi.Tooltip('.cktip');
});
*/

function ckInitItems() {
	$ck('.ck-menu-item').each(function() {
		var $item = $ck(this);
		ckAddItemControls($item);
		ckSetIconType($item);
	});
	$ck('.ck-column').each(function() {
		var $col = $ck(this);
		ckAddColumnControls($col);
	});
	$ck('.ck-column-break').each(function() {
		var $col = $ck(this);
		ckAddRowControls($col);
	});
	$ck('.ck-item-selection').click(function() {
		ckCreateItem($ck(this).attr('data-type'));
	});
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
	$item.append('<div class="ck-menu-item-style ck-menu-item-action" onclick="ckEditStylesItem(this)"><i class="fas fa-paint-brush"></i></div>');
	$item.append('<div class="ck-menu-item-edit ck-menu-item-action" onclick="ckEditItem(this);"><i class="fas fa-cog"></i></div>');
}

function ckInitParentItems() {
	$ck('.ck-menu-item-row').click(function() {
		var $link = $ck(this);
		if ($link.hasClass('ckfocus')) {
			$link.parent().find('> div.ck-submenu').fadeOut(500).css('z-index', '');
			$link.removeClass('ckfocus');
//			ckRemoveOverlays();
		} else {
			$link.parent().parent().find('.ck-menu-item-row').removeClass('ckfocus');
			$link.addClass('ckfocus');
			$ck('div.ck-submenu').not($link.parents()).hide().css('z-index', '');
//			ckRemoveOverlays();
			$link.parent().find('> div.ck-submenu').fadeIn(500).css('z-index', '11')
			if ($link.parent().attr('data-level') > 1) $link.parent().find('> div.ck-submenu').append('<div class="ck-overlay"></div>');
		}
		ckUpdatePathway($link);
	});

	// create toolbars
	$ck('div.ck-submenu').each(function() {
		var sub = $ck(this);
		var pathway = '<div class="ck-submenu-pathway"></div>';
		sub.prepend(pathway);
		var toolbar = '<div class="ck-submenu-toolbar">'
				+ '<div class="ck-submenu-toolbar-field"><input type="text" name="submenu-width" /><i class="fas fa-arrows-alt-h cktip" title="Width"></i></div>'
				+ '<div class="ck-submenu-toolbar-field"><input type="text" name="submenu-height" /><i class="fas fa-arrows-alt-v cktip" title="Height"></i></div>'
				+ '<div class="ck-submenu-toolbar-field"><input type="text" name="submenu-left" /><i class="fas fa-caret-right cktip" title="Left"></i></div>'
				+ '<div class="ck-submenu-toolbar-field"><input type="text" name="submenu-top" /><i class="fas fa-caret-down cktip" title="Top"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-fullwidth"><label><input type="checkbox" name="submenu-fullwidth" value="1" /><i class="fas fa-expand cktip" title="Fullwidth"></i></label></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-edit"><i class="fas fa-paint-brush"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-columns" onclick="ckManageColumns(this)"><i class="fas fa-columns"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-rows" onclick="ckManageRows(this)"><i class="fas fa-grip-lines"></i></div>'
				+ '<div class="ck-submenu-toolbar-field ck-submenu-toolbar-field-right ck-submenu-toolbar-close" onclick="ckCloseSubmenu(this)"><i class="fas fa-times"></i></div>'
			+ '</div>';
		sub.prepend(toolbar);
	});
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
	$ck('.ck-column').sortable({
		items: '.ck-menu-item',
		handle: '.ck-menu-item-move',
		connectWith: '.ck-column',
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
			$ck(ui.item).removeClass('ck-helper-placeholder')
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
	$col.append('<div class="ck-column-add-item" onclick="ckShowItemsSelection(this)"><i class="fa fa-plus"></i></div>');
	ckAddBlockEditionEvents($col);
}

function ckAddRowControls($col) {
	$col.append('<div class="ck-column-break-remove-item" onclick="ckRemoveItem(this, \'.ck-column-break\')"><i class="fa fa-trash"></i></div>');
}

function ckShowItemsSelection(btn) {
	$ck('.ckfocuscolumn').removeClass('ckfocuscolumn');
	$ck(btn).parent().addClass('ckfocuscolumn');
	CKBox.open({handler: 'inline', content: 'ck-items-selection', style: {padding: '10px'}, size: {x: '600px', y: '400px'}});
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
	if (!all)
		all = false;
	if (all == true) {
			$ck('.editorck', bloc).remove();
		} else {
			$ck('> .editorck', bloc).remove();
		}
}

function ckAddEditionControls(editor, bloc) {

	var blocclass = bloc.attr('ckclass') ? bloc.attr('ckclass') : '';
	var controls = '<div class="ck-fields">'
			+ '<div class="ck-field-remove ck-field" onclick="ckRemoveBlock(this);" ><i class="fa fa-trash"></i></div>'
			+ '<div class="ck-field-move ck-field" ><i class="fas fa-arrows-alt"></i></div>'
			+ '<div class="ck-field-edit ck-field" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"><i class="fa fa-edit"></i></div>'
			+ '<div class="ck-field-input" ><input type="text" name="column-width" /><i class="fas fa-arrows-alt-h cktip" title="Width"></i></div>'
			+ "</div>";

	editor.append(controls);
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
		
	});
}

function ckGetLayoutHtml() {
	var layout = new Object();
	$ck('#ckmenucreator .ck-menu-item[data-level="1"]').each(function(i) {
		var $item = $ck(this);
		layout[i] = ckGetLayoutItem($item);
//		layout[i]['submenu'] = ckGetLayoutSubmenu($item);
	});

	return layout;
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
				layout['columns'][c] = ckGetLayoutColumn($column);
				layout['columns'][c]['break'] = 1;
			} else {
				layout['columns'][c] = ckGetLayoutColumn($column);
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

function ckGetLayoutColumn($column) {
	var data = new Object();
	data['width'] = $column.attr('data-width');
	data['settings'] = $column.attr('data-settings');

	return data;
}

function ckGetParamsSubmenu($submenu) {
	return 'test';
}

function ckGetLayoutItems($submenu) {
	
}

function ckGetLayoutItem($item) {
	var data = new Object();
	data['type'] = $item.attr('data-type');
	data['title'] = $item.find('> .ck-menu-item-row .ck-menu-item-title').text();
	data['desc'] = $item.find('> .ck-menu-item-row .ck-menu-item-desc').text();
	data['id'] = $item.attr('data-id');
	data['level'] = $item.attr('data-level');
	data['submenu'] = ckGetLayoutSubmenu($item);
	data['settings'] = $item.attr('data-settings');

	return data;
}

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
	console.log(layout);
//alert('script arreté avant ajax');
//return;

	var myurl = MAXIMENUCK.BASE_URL + "&task=maximenu.save&" + MAXIMENUCK.TOKEN;
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
				$ck('#id').val(response.id);
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

