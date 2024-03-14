/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */

//function addfromfolderck() {
//
//}
var $ck = jQuery.noConflict();

function showWizardPopupCK(popup) {
	popup = $ck(popup);
	if (popup.parent('.accordion-body'))
		popup.parent('.accordion-body').css('overflow', 'visible');
	popup.css('display', 'block');
	$ck('.ckoverlay').fadeIn();
	// load the menu description
	$ck('#ckpopupwizard_select_desc_area').empty().text($ck('.ckpopupwizard_select.active > .ckpopupwizard_select_desc').text());
	$ck('.ckpopupwizard_select.active').each(function(i, button) {
		showActiveOptions($ck(button));
	});

	initFieldsValues();

	// fix for bootsrap issue
	$ck('select', $ck('#ckpopupwizard')).show();
	$ck('.chzn-container', $ck('#ckpopupwizard')).hide();
}

function initFieldsValues() {
	//jform_params_ / ckmaximenuwizard_
	var targetEl;
	$ck('.ckmaximenuwizard_inputbox').each(function(i, el) {
		el = $ck(el);
		targetEl = el.attr('id').replace('ckmaximenuwizard_', 'jform_params_');

		var $targetEl = $ck('#'+targetEl);
		if ($targetEl.length && $targetEl[0].tagName.toLowerCase() != 'fieldset') {
			el.val($targetEl.val());
		} else {
			// for radio button
			if ($ck('input[id^="'+targetEl+'"]').length) {
				$targetEl = $ck('input[id^="'+targetEl+'"]:checked');
				el.val($targetEl.val());
			}
		}
		
		// champs specifiques
		if (el.attr('id') == 'ckmaximenuwizard_theme') {
			if ($ck('#jform_params_usecss').val() == 0) {
				el.val(-1);
			} else {
				el.val($targetEl.val())
			}
			// updateRadiobuttonState($ck('#jform_params_usecss'))
		}
		
		if (el.attr('id') == 'ckmaximenuwizard_thirdparty'
		|| el.attr('id') == 'ckmaximenuwizard_layout'
		|| el.attr('id') == 'ckmaximenuwizard_menuposition'
		|| el.attr('id') == 'ckmaximenuwizard_maximenumobile_displayeffect') {
			el.parent().find('.ckpopupwizard_select').removeClass('active');
			el.parent().find('.ckpopupwizard_select[data-type="'+el.val()+'"]').addClass('active');
			showActiveOptions(el.parent().find('.ckpopupwizard_select[data-type="'+el.val()+'"]'));
		}
	});
}

function saveWizardCK(popup, fieldName, identifier) {
	popup = $ck(popup);

	CKBox.close();
	if (popup.parent('.accordion-body'))
		popup.parent('.accordion-body').css('overflow', '');

	var targetEl;
	$ck('.ckmaximenuwizard_inputbox').each(function(i, el) {
		el = $ck(el);
		targetEl = el.attr('id').replace('ckmaximenuwizard_', 'jform_params_');

		$targetEl = $ck('#'+targetEl);
		if ($targetEl.length) $targetEl.val(el.val());
		if ($targetEl[0] && $targetEl[0].tagName.toUpperCase() == 'SELECT') {
			if ($targetEl.next('.chzn-container').length) {
				$targetEl.next('.chzn-container').find('.chzn-single').text($targetEl.find('option[value="'+el.val()+'"]').text());
			}
		}

		// pour type radio
		if ($targetEl.length && $targetEl[0].tagName.toLowerCase() == 'fieldset') {
			updateRadiobuttonState($targetEl);
		}

		// champs specifiques
		if (el.attr('id') == 'ckmaximenuwizard_theme') {
			if (el.val() == -1) {
				$ck('#jform_params_usecss').val(0);
			} else {
				$ck('#jform_params_usecss').val(1);
			}
			updateRadiobuttonState($ck('#jform_params_usecss'))
		}
	});
}

function updateRadiobuttonState(targetEl) {
	targetEl.find('input[type=radio]').each(function(j, el2) {
		el2 = $ck(el2);
		elparent = el2.parent();
		labelbtn = elparent.find('label[for=' + el2.attr('id') + ']');

		if (el2.val() == targetEl.attr('value')) {
			el2.attr('checked', 'checked');
			if (el2.parent().hasClass('boutonRadio'))
				el2.parent().addClass('coche');
			if (elparent.hasClass('btn-group')) {
				labelbtn.addClass('active');
				if (el2.val() == 1)
					labelbtn.removeClass('btn-danger').addClass('btn-success');
				if (el2.val() == 0)
					labelbtn.removeClass('btn-success').addClass('btn-danger');
			}
		}
		else {
			el2.removeAttr('checked');
			if (el2.parent().hasClass('boutonRadio'))
				el2.parent().removeClass('coche');
			if (elparent.hasClass('btn-group')) {
				labelbtn.removeClass('active');
				labelbtn.removeClass('btn-danger').removeClass('btn-success');
			}
		}
	});
}

function closeWizardCK(popup) {
	popup.css('display', 'none');
	$ck('.ckoverlay').fadeOut();
	if (popup.getParent('.accordion-body'))
		popup.getParent('.accordion-body').css('overflow', '');
}

function ckpopupwizard_prev() {
	if (parseInt($ck('#ckpopupwizard_slider > .inner').css('marginLeft')) <= -780) {
		$ck('#ckpopupwizard_slider > .inner').css({'margin-left': '+=780px'});
		var index = parseInt($ck('#ckpopupwizard_slider').attr('data-index'));
		$ck('#ckpopupwizard_slider').attr('data-index', index-1);
		$ck('.ckpopupwizard_index').removeClass('active').eq(index-1).addClass('active');
	}
}

function ckpopupwizard_next() {
	var maxwidth = ( $ck('.ckpopupwizard_slider', $ck('#ckpopupwizard_slider')).length - 1 ) * 780;
	if (parseInt($ck('#ckpopupwizard_slider > .inner').css('marginLeft')) > -maxwidth) {
		$ck('#ckpopupwizard_slider > .inner').css({'margin-left': '-=780px'});
		var index = parseInt($ck('#ckpopupwizard_slider').attr('data-index'));
		$ck('#ckpopupwizard_slider').attr('data-index', index+1);
		$ck('.ckpopupwizard_index').removeClass('active').eq(index+1).addClass('active');
	}
}

function showActiveOptions(button) {
	var parentbutton = $ck(button).parents('.ckpopupwizard_slider')[0];
	$ck('#ckpopupwizard_select_desc_area', $ck(parentbutton)).empty().text($ck('.ckpopupwizard_select_desc', $ck(button)).text());
	$ck('.ckpopupwizard_select', $ck(parentbutton)).removeClass('active');
	$ck(button).addClass('active');
	$ck(parentbutton).find('.ckpopupwizard_options_area > div').hide();
	$ck('#'+$ck(button).attr('data-target')).show();
	
	// $ck('.btn-group', $ck('#ckpopupwizard')).button();
}

function getOptionForWizard(optionId) {
	return $ck('#'+optionId).parents('.control-group')[0].clone();
}

function changeFieldValue(fieldId, fieldValue) {
	$ck('#'+fieldId).val(fieldValue);
}

jQuery(document).ready(function (){
	/*var script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "Joomla.submitbutton = function(task){"
			+ "$$('.ckpopup').destroy();"
			+ "if (task == 'module.cancel' || document.formvalidator.isValid(document.id('module-form'))) {	Joomla.submitform(task, document.getElementById('module-form'));"
			+ "if (self != top) {"
			+ "window.top.setTimeout('window.parent.SqueezeBox.close()', 1000);"
			+ "}"
			+ "} else {"
			+ "alert('Invalid Form');"
			+ "}"
			+ "}";
	document.body.appendChild(script);*/
	$ck(document.body).append($ck('#ckpopupwizard'));
	// if ($ck('#jform[params][maximenuwizard]').val() != 1) showWizardPopupCK($('ckpopupwizard'));
	$ck('.hasTip').tooltip({"html": true,"container": "body"});
});




    