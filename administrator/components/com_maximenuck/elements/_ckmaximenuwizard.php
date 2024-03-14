<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.form');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class JFormFieldCkmaximenuwizard extends \Joomla\CMS\Form\FormField {

	protected $type = 'ckmaximenuwizard';

	protected function createFields($form) {

		$identifier = 'ckmaximenuwizard';
		?>
		<div id="ckpopupwizard" style="display:none;" class="ckpopupwizard">
			<div id="ckheader" style="position: absolute;">
				<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
				<div class="ckheadermenu">
					<div class="ckheadertitle"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD') ?></div>
					<a href="javascript:void(0)" onclick="CKBox.close()" class="ckheadermenuitem ckcancel">
						<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_CLOSE') ?>"></span>
						<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_CLOSE') ?></span>
					</a>
					<a href="javascript:void(0);" id="ckpopupstyleswizard_save" class="ckheadermenuitem cksave" onclick="javascript:saveWizardCK('#ckpopupwizard', '<?php echo $this->name ?>', 'ckmaximenuwizard');">
						<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?>"></span>
						<span class="ckheadermenuitemtext"><?php echo \Joomla\CMS\Language\Text::_('CK_SAVE') ?></span>
					</a>
				</div>
			</div>
<div class="ckpopupwizard_index active">1</div>
<div class="ckpopupwizard_index">2</div>
<div class="ckpopupwizard_index">3</div>
<div class="ckpopupwizard_index">4</div>
<div class="ckpopupwizard_index">5</div>
<div class="ckpopupwizard_indexline"></div>
<div class="ckpopupwizard_indexline active"></div>
<div id="ckpopupwizard_slider" data-index="0">
	<div class="inner">
		<div class="ckpopupwizard_slider">
			<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MENU_TO_RENDER') ?></div>
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_select active" data-type="none" data-target="ckpopupwizard_menutorenderoptions_joomla" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','none');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_JOOMLA_MENU') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_JOOMLA_MENU_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="k2" data-target="ckpopupwizard_menutorenderoptions_k2" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','k2');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_K2_MENU') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_K2_MENU_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="hikashop" data-target="ckpopupwizard_menutorenderoptions_hikashop" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','hikashop');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_HIKASHOP_MENU') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_HIKASHOP_MENU_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="joomshopping" data-target="ckpopupwizard_menutorenderoptions_joomshopping" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','joomshopping');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_JOOMSHOPPING_MENU') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_JOOMSHOPPING_MENU_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="virtuemart" data-target="ckpopupwizard_menutorenderoptions_virtuemart" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','virtuemart');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::sprintf('MAXIMENUCK_WIZARD_MENU_LABEL', 'Virtuemart') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::sprintf('MAXIMENUCK_WIZARD_MENU_DESC', 'Virtuemart') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="adsmanager" data-target="ckpopupwizard_menutorenderoptions_adsmanager" onclick="changeFieldValue('ckmaximenuwizard_thirdparty','adsmanager');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::sprintf('MAXIMENUCK_WIZARD_MENU_LABEL', 'Adsmanager') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::sprintf('MAXIMENUCK_WIZARD_MENU_DESC', 'Adsmanager') ?></div>
				</div>
				<input type="hidden" id="ckmaximenuwizard_thirdparty" class="ckmaximenuwizard_inputbox" />
				<br />
				<div id="ckpopupwizard_select_desc_area"></div>
			</div>
			<div class="ckpopupwizard_options_area">
				<div id="ckpopupwizard_menutorenderoptions_joomla">
				<?php foreach ($form->getFieldset('ckmaximenuwizard_menujoomlaoptions') as $key => $field) : ?>
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; ?>
				</div>
				<div id="ckpopupwizard_menutorenderoptions_k2">
				<?php
				if (\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_k2')
					AND \Joomla\CMS\Filesystem\File::exists(JPATH_ROOT . '/modules/mod_maximenuck/helper_k2.php')) {
				foreach ($form->getFieldset('ckmaximenuwizard_menuk2options') as $key => $field)  : ?> 
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; 
				} else {
					if (!\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_k2'))
					echo '<div class="ckinfo"><i class="fas fa-info"></i>' . \Joomla\CMS\Language\Text::_('MOD_MAXIMENUCK_K2_NOTFOUND') . '</div>';
				}?>
				</div>
				<div id="ckpopupwizard_menutorenderoptions_hikashop">
				<?php
				if (\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_hikashop')
					AND \Joomla\CMS\Plugin\PluginHelper::isEnabled('maximenuck', 'hikashop')) {
				foreach ($form->getFieldset('ckmaximenuwizard_menuhikashopoptions') as $key => $field)  : ?> 
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; 
				} else {
					if (!\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_hikashop'))
						echo '<div class="ckinfo"><i class="fas fa-info"></i>' . \Joomla\CMS\Language\Text::sprintf( 'MOD_MAXIMENUCK_NOTFOUND', 'Hikashop' ) . '</div>';
				}?>
				</div>
				<div id="ckpopupwizard_menutorenderoptions_joomshopping">
				<?php
				if (\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_jshopping')
					AND \Joomla\CMS\Filesystem\File::exists(JPATH_ROOT . '/modules/mod_maximenuck/helper_joomshopping.php')) {
				foreach ($form->getFieldset('ckmaximenuwizard_menujoomshoppingoptions') as $key => $field)  : ?> 
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; 
				} else {
					if (!\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_jshopping'))
						echo '<div class="ckinfo"><i class="fas fa-info"></i>' . \Joomla\CMS\Language\Text::sprintf( 'MOD_MAXIMENUCK_NOTFOUND', 'Joomshopping' ) . '</div>';
				}?>
				</div>
				<div id="ckpopupwizard_menutorenderoptions_virtuemart">
				<?php
				if (\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_virtuemart')
					AND \Joomla\CMS\Filesystem\File::exists(JPATH_ROOT . '/modules/mod_maximenuck/helper_virtuemart.php')) {
				foreach ($form->getFieldset('ckmaximenuwizard_menuvirtuemartoptions') as $key => $field)  : ?> 
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; 
				} else {
					if (!\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_virtuemart'))
						echo '<div class="ckinfo"><i class="fas fa-info"></i>' . \Joomla\CMS\Language\Text::sprintf( 'MOD_MAXIMENUCK_NOTFOUND', 'Virtuemart' ) . '</div>';
				}?>
				</div>
				<div id="ckpopupwizard_menutorenderoptions_adsmanager">
				<?php
				if (\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_adsmanager')
					AND \Joomla\CMS\Filesystem\File::exists(JPATH_ROOT . '/modules/mod_maximenuck/helper_adsmanager.php')) {
				foreach ($form->getFieldset('ckmaximenuwizard_menuadsmanageroptions') as $key => $field)  : ?> 
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; 
				} else {
					if (!\Joomla\CMS\Filesystem\Folder::exists(JPATH_ROOT . '/administrator/components/com_adsmanager'))
						echo '<div class="ckinfo"><i class="fas fa-info"></i>' . \Joomla\CMS\Language\Text::sprintf( 'MOD_MAXIMENUCK_NOTFOUND', 'Adsmanager' ) . '</div>';
				}?>
				</div>
			</div>
		</div>
		<div class="ckpopupwizard_slider">
			<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_TYPE_OF_LAYOUT') ?></div>
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_select active" data-type="_:default" data-target="ckpopupwizard_typeoflayout_default" onclick="changeFieldValue('ckmaximenuwizard_layout','_:default');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_DEFAULT') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_DEFAULT_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="_:pushdown" data-target="ckpopupwizard_typeoflayout_pushdown" onclick="changeFieldValue('ckmaximenuwizard_layout','_:pushdown');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_PUSHDOWN') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_PUSHDOWN_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="_:nativejoomla" data-target="ckpopupwizard_typeoflayout_nativejoomla" onclick="changeFieldValue('ckmaximenuwizard_layout','_:nativejoomla');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_NATIVEJOOMLA') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_NATIVEJOOMLA_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="_:dropselect" data-target="ckpopupwizard_typeoflayout_dropselect" onclick="changeFieldValue('ckmaximenuwizard_layout','_:dropselect');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_DROPSELECT') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_DROPSELECT_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="_:flatlist" data-target="ckpopupwizard_typeoflayout_flatlist" onclick="changeFieldValue('ckmaximenuwizard_layout','_:flatlist');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_FLATLIST') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_FLATLIST_DESC') ?></div>
				</div>
				<div class="ckpopupwizard_select" data-type="_:fullwidth" data-target="ckpopupwizard_typeoflayout_fullwidth" onclick="changeFieldValue('ckmaximenuwizard_layout','_:fullwidth');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_FULLWIDTH') ?>
					<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_LAYOUT_FULLWIDTH_DESC') ?></div>
				</div>
				<input type="hidden" id="ckmaximenuwizard_layout" class="ckmaximenuwizard_inputbox" />
				<br />
				<div id="ckpopupwizard_select_desc_area"></div>
			</div>
			<div class="ckpopupwizard_options_area">
				<div id="ckpopupwizard_typeoflayout_default">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>default_layout.jpg" width="450" height="142" />
				</div>
				<div id="ckpopupwizard_typeoflayout_pushdown">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>pushdown_layout.jpg" width="450" height="142" />
				</div>
				<div id="ckpopupwizard_typeoflayout_nativejoomla">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>nativejoomla_layout.jpg" width="450" height="198" />
				</div>
				<div id="ckpopupwizard_typeoflayout_dropselect">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>dropselect_layout.jpg" width="400" height="142" />
				</div>
				<div id="ckpopupwizard_typeoflayout_flatlist">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>flatlist_layout.jpg" width="450" height="142" />
				</div>
				<div id="ckpopupwizard_typeoflayout_fullwidth">
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>fullwidth_layout.jpg" width="450" height="142" />
				</div>
			</div>
		</div>
		<div class="ckpopupwizard_slider">
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MENU_POSITION') ?></div>
				<div class="ckpopupwizard_page">
					<div class="ckpopupwizard_select" data-type="topfixed" data-target="ckpopupwizard_menuposition_topfixed" onclick="changeFieldValue('ckmaximenuwizard_menuposition','topfixed');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_TOPFIXED') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_TOPFIXED_DESC') ?></div>
					</div>
					<div style="clear:both;"></div>
					<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>logo_fake.png" height="24.5%" width="40%" style="float:left;margin-top: 20px;"/> 
					<div class="ckpopupwizard_block ckpopupwizard_logomodule"></div>
					<div style="clear:both;"></div>
					<div class="ckpopupwizard_select active" data-type="0" data-target="ckpopupwizard_menuposition_normal" onclick="changeFieldValue('ckmaximenuwizard_menuposition','0');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_NORMAL') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_NORMAL_DESC') ?></div>
					</div>
					<div style="clear:both;"></div>
					<div class="ckpopupwizard_block ckpopupwizard_leftcol"></div>
					<div class="ckpopupwizard_block ckpopupwizard_centercol"></div>
					<div class="ckpopupwizard_block ckpopupwizard_right"></div>
					<div style="clear:both;"></div>
					<div class="ckpopupwizard_select" data-type="bottomfixed" data-target="ckpopupwizard_menuposition_bottomfixed" onclick="changeFieldValue('ckmaximenuwizard_menuposition','bottomfixed');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_BOTTOMFIXED') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_POSITION_BOTTOMFIXED_DESC') ?></div>
					</div>
				</div>
				<input type="hidden" id="ckmaximenuwizard_menuposition" class="ckmaximenuwizard_inputbox" />
				<br />
				<div id="ckpopupwizard_select_desc_area"></div>
			</div>
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MENU_EFFECT') ?></div>
				<?php foreach ($form->getFieldset('ckmaximenuwizard_effectoptions') as $key => $field) : ?>
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="ckpopupwizard_slider">
			<div class="ckpopupwizard_optionsselect_area" style="width:auto;">
				<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MENU_STYLES') ?></div>
				<?php foreach ($form->getFieldset('ckmaximenuwizard_stylesoptions') as $key => $field) : ?>
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
			<br style="clear:both;"/>
			<div id="ckpopupwizard_select_desc_area"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_STYLES_DESC') ?></div>
		</div>
		<div class="ckpopupwizard_slider">
			<?php 
			if (file_exists(JPATH_ROOT . '/plugins/system/mobilemenuck/mobilemenuck.php')
				&& \Joomla\CMS\Plugin\PluginHelper::isEnabled('system','mobilemenuck'))  : 
			?>
			
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE') ?></div>
				<?php foreach ($form->getFieldset('ckmaximenuwizard_mobileoptions') as $key => $field) : ?>
					<div class="ckpopupwizard_row">
						<div class="ckpopupwizard_label"><?php echo $form->getLabel(str_replace($identifier . "_", "", $key), $identifier); ?></div>
						<div class="ckpopupwizard_input"><?php echo $form->getInput(str_replace($identifier . "_", "", $key), $identifier); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="ckpopupwizard_optionsselect_area">
				<div class="ckpopupwizard_heading"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_EFFECT') ?></div>
				<div style="width:150px;float:left;">
					<div class="ckpopupwizard_select active" data-type="normal" data-target="ckpopupwizard_mobileoptions_normal" onclick="changeFieldValue('ckmaximenuwizard_mobilemenuck_displayeffect','normal');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_NORMAL') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_NORMAL_DESC') ?></div>
					</div>
					<div class="ckpopupwizard_select" data-type="slideleft" data-target="ckpopupwizard_mobileoptions_slideleft" onclick="changeFieldValue('ckmaximenuwizard_mobilemenuck_displayeffect','slideleft');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_SLIDELEFT') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_SLIDELEFT_DESC') ?></div>
					</div>
					<div class="ckpopupwizard_select" data-type="slideright" data-target="ckpopupwizard_mobileoptions_slideright" onclick="changeFieldValue('ckmaximenuwizard_mobilemenuck_displayeffect','slideright');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_SLIDERIGHT') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_SLIDERIGHT_DESC') ?></div>
					</div>
					<div class="ckpopupwizard_select" data-type="topfixed" data-target="ckpopupwizard_mobileoptions_topfixed" onclick="changeFieldValue('ckmaximenuwizard_mobilemenuck_displayeffect','topfixed');showActiveOptions(this)"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_TOPFIXED') ?>
						<div class="ckpopupwizard_select_desc"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_WIZARD_MOBILE_TOPFIXED_DESC') ?></div>
					</div>
					<input type="hidden" id="ckmaximenuwizard_mobilemenuck_displayeffect" class="ckmaximenuwizard_inputbox" />
					<br />
					<div id="ckpopupwizard_select_desc_area"></div>
				</div>
				<div  class="ckpopupwizard_options_area" style="width:200px;float: left;margin-left:20px;">
					<div id="ckpopupwizard_mobileoptions_normal">
						<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>mobilemenu_normal.jpg" width="200" height="auto" />
					</div>
					<div id="ckpopupwizard_mobileoptions_slideleft">
						<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>mobilemenu_slideleft.jpg" width="200" height="auto" />
					</div>
					<div id="ckpopupwizard_mobileoptions_slideright">
						<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>mobilemenu_slideright.jpg" width="200" height="auto" />
					</div>
					<div id="ckpopupwizard_mobileoptions_topfixed">
						<img src="<?php echo MAXIMENUCK_MEDIA_URI . '/images/' ?>mobilemenu_topfixed.jpg" width="200" height="auto" />
					</div>
				</div>
			</div>
			<?php else: ?>
				<?php echo '<p>' . \Joomla\CMS\Language\Text::_('MAXIMENUCK_NEED_PLUGIN_MOBILE') . ' <a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank">Mobile Menu CK</a></p>'; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<div style="clear:both;"></div>
<div id="ckpopupwizard_next" class="ckboxmodal-button" onclick="ckpopupwizard_next()">>></div>
<div id="ckpopupwizard_prev" class="ckboxmodal-button" onclick="ckpopupwizard_prev()"><<</div>

<?php
		echo '</div>';
	}

	protected function getInput() {

		$this->imagesPath = \Joomla\CMS\Uri\Uri::root() . 'media/com_maximenuck/elements/ckmaximenuwizard/';
		
		$identifier = 'ckmaximenuwizard';

		$form = new \Joomla\CMS\Form\Form('ckmaximenuwizard');
		\Joomla\CMS\Form\Form::addFormPath(JPATH_SITE . '/modules/mod_maximenuck/elements/ckmaximenuwizard');
		\Joomla\CMS\Form\Form::addFormPath(JPATH_SITE . '/administrator/components/com_maximenuck/elements');
		\Joomla\CMS\Form\Form::addFormPath(JPATH_SITE . '/media/com_maximenuck/elements/ckmaximenuwizard');
		\Joomla\CMS\Form\Form::addFieldPath(JPATH_SITE . '/media/com_maximenuck/elements');

		if (!$formexists = $form->loadFile($identifier, false)) {
			echo '<p style="color:red">' . \Joomla\CMS\Language\Text::_('Problem loading the file : ' . $identifier . '.xml') . '</p>';
			return '';
		}
// var_dump($form);die;
		$this->createFields($form);

		$doc = \Joomla\CMS\Factory::getDocument();
		$doc->addScriptDeclaration("\Joomla\CMS\Uri\Uri='" . \Joomla\CMS\Uri\Uri::root() . "';");
		$path = 'compo/mod_maximenuck/elements/ckmaximenuwizard/';
		\Joomla\CMS\HTML\HTMLHelper::_('script', MAXIMENUCK_MEDIA_URI . '/images/'  . 'ckmaximenuwizard.js');
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', MAXIMENUCK_MEDIA_URI . '/images/'  . 'ckmaximenuwizard.css');

		$html = '<input name="' . $this->name . '" id="' . $this->name . '" type="hidden" value="' . $this->value . '" />';
		$button = '<div class="ckbutton" style="padding:20px;" onclick="CKBox.open({handler:\'inline\', size: {x: \'820px\', y: \'550px\'}, fullscreen: false, content: \'ckpopupwizard\', footerHtml: document.getElementById(\'ckpopupwizard_next\').outerHTML + document.getElementById(\'ckpopupwizard_prev\').outerHTML});showWizardPopupCK(jQuery(\'ckpopupwizard\'))">' 
		. '<span class="fas fa-magic" style="font-size: 18px;color:orchid;margin-right: 10px;"></span> ' 
		. \Joomla\CMS\Language\Text::_((string) $this->element['label']) 
		. '</div>';
		$html .= $button;
		return $html;
	}

	protected function getLabel() {

		return '';
	}
}

