/**
 * @name		Maxi Menu CK
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014-2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

var $ck = jQuery.noConflict();

$ck(document).ready(function(){
	ckInitMenusManager();
	$ck(document.body).append('<div id="ckwaitoverlay"></div>');
	ckInitTogglers();
	ckSetParentClass();
});

function ckSetParentClass() {
	$ck('ol#sortable li').each(function(i, item) {
		item = $ck(item);
		if (item.children('ol').children('li').length) {
			item.addClass('parent');
		} else {
			item.removeClass('parent');
		}
	});
}

function ckInitTogglers() {
	$ck('.itemtoggler').not('.togglerdone').each(function() {
		$ck(this).click(function() {
			$ck($ck($ck(this).parents('li')[0]).find('.togglecontent')[0]).toggle('fast');
			$ck(this).toggleClass('opened');
		});
		$ck(this).addClass('togglerdone');
	});
}

function ckInitMenusManager() {
		$ck('ol#sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: '.ckmove',
			helper:	'clone',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tolerance: 'pointer',
			toleranceElement: '> div',
			maxLevels: 0,
			isTree: true,
			expandOnHover: 900,
			startCollapsed: true,
			rtl: false,
			update: function( event, ui ) {
				if ($ck(ui.item).attr('data-valid') == 'true' && !$ck(ui.sender).hasClass('ckmenuselectsortable'))
					ckUpdateNestedItems();
			},
			complete: function( event, ui ) {

			}
		});
		
		$ck('.disclose').on('click', function() {
			$ck(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
		});
}

function ckPublish(button) {
	if ($ck($ck(button).parents('li')[0]).attr('data-home') == '1') {
		alert(CKApi.Text._('CK_UNABLE_UNPUBLISH_HOME','CK_UNABLE_UNPUBLISH_HOME'));
		return;
	}
	button = $ck(button);
	var id = button.attr('data-id');
	var state = button.attr('data-state');
	if (button.hasClass('ckwait')) return;
	ckAddWaitIcon(button);
	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxPublish&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id,
			state: state
			}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			ckRemoveWaitIcon(button);
			var ids = result.ids.split(',');
			for (var i=0; i<ids.length; i++) {
				var resultid = ids[i];
				ckRemoveWaitIcon($ck('#publish'+resultid));
				ckUpdatePublishState(resultid, parseInt(state));
			}
		} else {
			alert(result.message);
		}
	}).fail(function() {
		ckRemoveWaitIcon($ck('#publish'+id));
	});
}

function ckUpdatePublishIcon(id, state) {
	var button = $ck('#publish'+id);
	var buttonclass = (state == 1) ? 'fas fa-check' : 'fas fa-times-circle';
	button.find('i').attr('class', buttonclass);
}

function ckUpdatePublishState(id, state) {
	button = $ck('#publish'+id);
	state = 1 - state;
	button.attr('data-state', state);
	ckUpdatePublishIcon(id, state);
	return state;
}

function ckAddWaitIcon(button) {
	var icon = button.find('i');
	if (icon.hasClass('fa-spinner')) return;
	icon.attr('data-class', icon.attr('class')).attr('class', '');
	icon.attr('class', 'fas fa-spin fa-spinner');
}

function ckRemoveWaitIcon(button, failed) {
	var icon = button.find('i');
	if (failed) {
		icon.attr('class','fas fa-exclamation-triangle');
	} else {
		icon.attr('class', icon.attr('data-class'));
	}
}

function ckCheckin(el, id) {
	ckAddWaitIcon($ck('.checkedouticon', $ck(el).parent()));
	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxCheckin&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			ckRemoveWaitIcon($ck('.checkedouticon', $ck(el).parent()));
			$ck(el).remove();
		} else {
			ckRemoveWaitIcon($ck('.checkedouticon', $ck(el).parent()), true);
			alert(CKApi.Text._('CK_CHECKIN_NOT_UPDATED', 'CK_CHECKIN_NOT_UPDATED'));
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_CHECKIN_FAILED', 'CK_CHECKIN_FAILED'));
		ckRemoveWaitIcon($ck('.checkedouticon', $ck(el).parent()), true);
	});
}

function ckEditItem(btn) {
	var item = $ck($ck(btn).parents('li')[0]);
	var boxfooterhtml = '<a class="ckboxmodal-button" href="javascript:void(0);" onclick="ckSaveIframe(this, '+item.attr('data-id')+');CKBox.close(this, \'1\')">' + CKApi.Text._('CK_SAVE_CLOSE') + '</a>';
	CKBox.open({handler: 'iframe', id: 'maximenuckJoomlaMenuItem', footerHtml:  boxfooterhtml, style: {padding: '10px'}, onCKBoxLoaded: function() {ckAddSaveBtnToIframe('maximenuckJoomlaMenuItem')}, url: MAXIMENUCK.URIROOT + '/administrator/index.php?option=com_menus&view=item&layout=edit&id='+item.attr('data-id')+'&tmpl=component'});
}

function ckAddSaveBtnToIframe(boxId) {
	var iframe = $ck('#' + boxId).find('iframe');
	iframe.load(function() {
		var iframeForm = iframe.contents().find('form');
		if (! iframeForm.find('#saveBtn').length) {
			var saveHtml = '<button id="saveBtn" style="display:none;" onclick="Joomla.submitbutton(\'item.apply\');" type="button"></button>';
			iframe.contents().find('form').prepend(saveHtml);
		}
	});
}

function ckSaveIframe(btn, id) {
	var iframe = $ck('iframe', $ck($ck(btn).parents('.ckboxmodal')[0])).contents();
	ckUdpateModuleTitle(id, iframe.find('#jform_title').val());
	iframe.find('#saveBtn').click();
}

function ckUdpateModuleTitle(id, title) {
	var el = $ck('#sortable li[data-id="'+id+'"]');
	el.find('.cktitle').text(title);
}

function ckEditTitle(editbutton) {
	el = $ck('> div .cktitle', $ck($ck(editbutton).parents('li')[0]));
	txt = $ck(el).text().trim().replace(/"/g, '&quot;');
	$ck(el).html("<input type=\"text\" value=\""+txt+"\" style=\"width:150px;\"/>");
	$ck(el).attr('data-text-origin', txt);
	$ck('input', $ck(el)).focus();
	$ck('.exittitle', $ck(el).parent()).show();
	$ck('.edittitle', $ck(el).parent()).hide();
}


function ckUpdateTitle(el) {
	txt = $ck('input', el).val();
	if (txt && txt != $ck(el).attr('data-text-origin')) {
		ckSaveTitle($ck(el).attr('data-id'), txt, el);
	}
	if (txt) {
		$ck(el).html(txt);
		$ck('.exittitle', $ck(el).parent()).hide();
		$ck('.edittitle', $ck(el).parent()).show();
	}
}

function ckExitTitle(el) {
	txt = $ck(el).attr('data-text-origin');
	if (txt) {
		$ck(el).html(txt);
		$ck('.exittitle', $ck(el).parent()).hide();
		$ck('.edittitle', $ck(el).parent()).show();
	}
}

function ckSaveTitle(id, title, el) {
	ckAddWaitIcon($ck('.edittitle', $ck(el).parent()));
	ckAddWaitIcon($ck('#ckmessage'));
	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxSaveTitle&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id,
		title: title
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			ckRemoveWaitIcon($ck('.edittitle', $ck(el).parent()));
		} else {
			ckRemoveWaitIcon($ck('.edittitle', $ck(el).parent()), true);
			ckRemoveWaitIcon($ck('#ckmessage'));
			$ck('#ckmessage').append(code);
			alert(CKApi.Text._('CK_TITLE_NOT_UPDATED', 'CK_TITLE_NOT_UPDATED'));
		}
	}).fail(function() {
		ckRemoveWaitIcon($ck('.edittitle', $ck(el).parent()), true);
	});
}

function ckSaveDescription(btn) {
	var item = $ck($ck(btn).parents('li')[0]);
	var txt = btn.value;
	if (txt != $ck(btn).attr('data-text-origin')) {
		ckSaveparam(item.attr('data-id'), 'maximenu_desc', txt, btn, true, 'ckSuccessDescription', Array(btn, txt));
	}
}

function ckSuccessDescription(params) {
	$ck(params[0]).attr('data-text-origin', params[1]);
}

function ckSaveparam(id, param, value, btn, waiticon, callback, args) {
	if (!waiticon) waiticon = false;
	if (waiticon) ckAddWaitIcon($ck(btn));
	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxSaveParam&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id,
		param: param,
		value:value
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			if (waiticon) ckRemoveWaitIcon($ck(btn), false);
			if (callback && typeof window[callback] == 'function') { if (!args) args = null;window[callback](args); }
		} else {
			if (waiticon) ckRemoveWaitIcon($ck(btn), true);
			alert(CKApi.Text._('CK_PARAM_NOT_UPDATED', 'CK_PARAM_NOT_UPDATED'));
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_PARAM_UPDATE_FAILED', 'CK_PARAM_UPDATE_FAILED'));
		if (waiticon) ckRemoveWaitIcon($ck(btn), true);
	});
}

function ckToggleColumn(btn) {
	$ck(btn).toggleClass('active');
	var item = $ck($ck(btn).parents('li')[0]);

	if (item.attr('data-level') == '1') {
		alert(CKApi.Text._('CK_NO_COLUMN_ON_ROOT_ITEM', 'WARNING : You can not create a column on a root item, this will kill your layout !'));
	}
	ckSaveparam(item.attr('data-id'), 'maximenu_createcolumn', $ck(btn).hasClass('active') ? '1' : '0', btn, true);
}


function ckChangeColWidth(btn) {
	var colwidth = prompt('Column width', $ck(btn).text());
	if (colwidth == null) return;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_colwidth', colwidth, btn, true);
	$ck(btn).text(colwidth);
}

function ckCreateNewRow(btn) {
	$ck(btn).toggleClass('active');
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_createnewrow', $ck(btn).hasClass('active') ? '1' : '0', btn, true);
}

function ckChangeSubmenuWidth(btn) {
	var submenuwidth = prompt('Submenu width', $ck(btn).find('.valuetxt').text());
	if (submenuwidth == null) return;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_submenucontainerwidth', submenuwidth, btn, true);
	$ck(btn).find('.valuetxt').text(submenuwidth);
	if (submenuwidth != '') {
		$ck(btn).addClass('ckhastext');
	} else {
		$ck(btn).removeClass('ckhastext');
	}
}

function ckChangeSubmenuHeight(btn) {
	var submenuheight = prompt('Submenu height', $ck(btn).find('.valuetxt').text());
	if (submenuheight == null) return;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_submenucontainerheight', submenuheight, btn, true);
	$ck(btn).find('.valuetxt').text(submenuheight);
	if (submenuheight != '') {
		$ck(btn).addClass('ckhastext');
	} else {
		$ck(btn).removeClass('ckhastext');
	}
}

function ckChangeSubmenuLeftMargin(btn) {
	var submenuleftmargin = prompt('Submenu left margin', $ck(btn).find('.valuetxt').text());
	if (submenuleftmargin == null) return;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_leftmargin', submenuleftmargin, btn, true);
	$ck(btn).find('.valuetxt').text(submenuleftmargin);
	if (submenuleftmargin != '') {
		$ck(btn).addClass('ckhastext');
	} else {
		$ck(btn).removeClass('ckhastext');
	}
}

function ckChangeSubmenuTopMargin(btn) {
	var submenutopmargin = prompt('Submenu top margin', $ck(btn).find('.valuetxt').text());
	if (submenutopmargin == null) return;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_topmargin', submenutopmargin, btn, true);
	$ck(btn).find('.valuetxt').text(submenutopmargin);
	if (submenutopmargin != '') {
		$ck(btn).addClass('ckhastext');
	} else {
		$ck(btn).removeClass('ckhastext');
	}
}

function ckChangeFullwidthClass(btn) {
	$ck(btn).toggleClass('active').toggleClass('ckbutton-primary');
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_liclass', $ck(btn).hasClass('active') ? '1' : '0', btn, true);
}

function ckToggleDesktopState(btn) {
	$ck(btn).toggleClass('disable');
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_disabledesktop', $ck(btn).hasClass('disable') ? '1' : '0', btn, true);
}

function ckToggleMobileState(btn) {
	$ck(btn).toggleClass('disable');
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_disablemobile', $ck(btn).hasClass('disable') ? '1' : '0', btn, true);

}

function ckSelectImage(id) {
	CKBox.open({handler: 'iframe', id: 'ckmodalbrowseimage', url: 'index.php?option=com_maximenuck&view=browse&type=image&func=ckSetImage&field='+id+'&tmpl=component'});
}

function ckSetImage(image_url, fieldid) {
	var btn = '#'+fieldid;
	var item = $ck($ck(btn).parents('li')[0]);
	var title = $ck(btn).attr('data-original-title');
	var tmp = $ck('<div />').append(title);
	var rep = tmp.find('img').attr('src');
	if (tmp.find('img').length) {
		$ck(btn).attr('data-original-title', $ck(btn).attr('data-original-title').replace(rep, MAXIMENUCK.URIROOT + '/' + image_url));
	} else {
		$ck(btn).attr('data-original-title', title + '<br /><img src="' + MAXIMENUCK.URIROOT + '/' + image_url + '" class="cktip-img-preview" />');
	}
	ckSaveparam(item.attr('data-id'), 'menu_image', image_url, btn, true);
	$ck(btn).addClass('active');
	CKBox.close('#ckmodalbrowseimage');
}

function ckRemoveImage(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;

	var item = $ck($ck(btn).parents('li')[0]);
	var title = $ck(btn).prev().attr('data-original-title');
	var pos = title.indexOf('<br />');
	
	ckSaveparam(item.attr('data-id'), 'menu_image', '', $ck(btn).prev(), true);
	$ck(btn).prev().attr('data-original-title', title.substring(0, pos)).removeClass('active');
}

function ckSelectIcon(id) {
	CKBox.open({handler: 'iframe', id: 'ckmodalicons', url: 'index.php?option=com_maximenuck&view=icons&func=ckSetIcon&field='+id+'&tmpl=component'});
}
	
function ckSetIcon(iclass, fieldid) {
	var btn = '#'+fieldid;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_icon', iclass, btn, true, 'ckSuccessIcon', Array(fieldid, iclass));

	CKBox.close('#ckmodalicons');
}

function ckSuccessIcon(params) {
	jQuery('#'+params[0]).addClass('active').find('span').attr('class', params[1]);
}

function ckRemoveIcon(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;

	var item = $ck($ck(btn).parents('li')[0]);
	
	ckSaveparam(item.attr('data-id'), 'maximenu_icon', '', $ck(btn).prev(), true);
	$ck(btn).prev().removeClass('active').find('span').removeAttr('class');
}

function ckSelectModule(id) {
	CKBox.open({handler: 'iframe', id: 'ckmodalmodules', url: 'index.php?option=com_maximenuck&view=items&type=module&func=ckSetModule&field='+id+'&tmpl=component'});
}

function ckSetModule(type, id, title, fieldid) {
	var btn = '#'+fieldid;
	var item = $ck($ck(btn).parents('li')[0]);

	ckSaveparam(item.attr('data-id'), 'maximenu_insertmodule', '1', btn, true);
	ckSaveparam(item.attr('data-id'), 'maximenu_module', id, btn, true, 'ckSuccessModule', Array(fieldid, id, title));

	CKBox.close('#ckmodalmodules');
}

function ckSuccessModule(params) {
	var module = jQuery.parseJSON(params[1]);
	var btn = jQuery('#'+params[0]);
	btn.addClass('ckhastext');
	btn.find('.modulename').html('<span class="moduleid">' + params[1] + '</span>&nbsp;' + params[2]);
	btn.addClass('active');
}

function ckRemoveModule(btn) {
	if (!confirm(CKApi.Text._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;

	var item = $ck($ck(btn).parents('li')[0]);
	
	ckSaveparam(item.attr('data-id'), 'maximenu_insertmodule', '', $ck(btn).prev(), true);
	$ck(btn).prev().removeClass('active').removeClass('ckhastext').find('.modulename').text('');
}

function ckUpdateNestedItems() {
	var saveLevelLaunched = 0;
	$ck('ol#sortable li').attr('data-leveltmp', '1').attr('data-parenttmp','1');
	$ck('ol#sortable li').each(function() {
		var item = $ck(this);
		if (item.children('ol').length) {
			$ck('li', item).attr('data-leveltmp', parseInt(item.attr('data-leveltmp'))+1).attr('data-parenttmp', item.attr('data-id'));
		}
		if (item.attr('data-leveltmp') != item.attr('data-level')
			|| item.attr('data-parenttmp') != item.attr('data-parent')) {
			ckSaveItemLevel(item.attr('data-id'), item.attr('data-leveltmp'), item.attr('data-parenttmp'));
			saveLevelLaunched = 1;
		}
	});
	ckSaveItemsOrder();
}

function ckSaveItemLevel(id, level, parentid) {
	ckAddWaitOverlay();
	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxSaveLevel&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
	type: "POST",
	url: myurl,
	async: true,
	data: {
		id: id,
		level: level,
		parentid: parentid
		}
	}).done(function(code) {
		if (code == '1') {
			$ck('#ckmessage > div').empty().append('<div class="alert alert-success">Items order saved with success</div>').fadeOut(3000);
			$ck('ol#sortable li[data-id='+id+']').attr('data-level', level).attr('data-parent', parentid);
		} else {
			alert(CKApi.Text._('CK_LEVEL_NOT_UPDATED', 'CK_LEVEL_NOT_UPDATED')+code);
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_SAVE_LEVEL_FAILED', 'CK_SAVE_LEVEL_FAILED'));
	});
}

function ckSaveItemsOrder() {
	ckAddWaitOverlay();
	var cid_array = new Array();
	var order_array = new Array();
	var lft_array = new Array();
	var rgt_array = new Array();
	var lft = 1, rgt = 2;
	$ck('ol#sortable li:first-child').attr('lft', lft).attr('rgt', rgt);
	$ck('ol#sortable li').each(function(k, item) {
		item = $ck(item);
		if (item.prev('li').length) {
			item.attr('lft', parseInt(item.prev('li').attr('rgt'))+1).attr('rgt', parseInt(item.prev('li').attr('rgt'))+2);
		}
		if (item.children('ol').length) {
			item.attr('rgt', parseInt(item.attr('lft'))+$ck('li', item).length*2+1);
			$ck('> ol > li', item).attr('lft', parseInt(item.attr('lft'))+1).attr('rgt', parseInt(item.attr('lft'))+2);
		}
		cid_array.push($ck(this).attr('data-id'));
		order_array.push(k);
		lft_array.push($ck(this).attr('lft'));
		rgt_array.push($ck(this).attr('rgt'));
	});

	var myurl = MAXIMENUCK.BASE_URL + "&task=joomlamenu.ajaxSaveOrder&" + MAXIMENUCK.TOKEN;
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		cid: cid_array,
		order: order_array,
		lft: lft_array,
		rgt: rgt_array
		}
	}).done(function(code) {
		if (code == '1') {
			ckRemoveWaitOverlay($ck('#ckmessage'));
			$ck('#ckmessage > div').empty().show().append('<div class="alert alert-success">Items order saved with success</div>').fadeOut(3000);
		} else {
			ckRemoveWaitOverlay($ck('#ckmessage'));
			alert(CKApi.Text._('CK_SAVE_ORDER_FAILED', 'CK_SAVE_ORDER_FAILED')+code);
		}
		ckSetParentClass();
	}).fail(function() {
		ckRemoveWaitOverlay($ck('#ckmessage'));
	});
}

function ckAddWaitOverlay() {
	$ck('#ckwaitoverlay').show();
}

function ckRemoveWaitOverlay() {
	$ck('#ckwaitoverlay').hide();
}

function ajaxSetStype(title, type, component, view, parentId, parentLevel, prevItemId, alias, dataId, dataLayout, dataController, category_id, dataAttribs) {
	// null here, only used in Menu Manager CK
}

function ajaxSaveItem(data64, title, type, component, view, alias, parentId, parentLevel, prevItemId, dataId) {
	// null here, only used in Menu Manager CK
}

function ajaxTrashItem(id) {
	// null here, only used in Menu Manager CK
}

function createItem(id, title, type, parentId, parentLevel, prevItemId) {
	// null here, only used in Menu Manager CK
}
