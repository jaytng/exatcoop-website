<?php
/**
 * @name		Maximenu CK params
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
defined('_JEXEC') or die;
?>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_APPEARANCE_LABEL'); ?></div>
<?php $this->interface->createBorders('level1itemparentstyles') ?>
<div class="ckrow">
	<label for="level1itemparentstylesroundedcornerstl"><?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERS_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tl.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesroundedcornerstl" name="level1itemparentstylesroundedcornerstl" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSTL_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tr.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesroundedcornerstr" name="level1itemparentstylesroundedcornerstr" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSTR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_br.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesroundedcornersbr" name="level1itemparentstylesroundedcornersbr" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSBR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_bl.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesroundedcornersbl" name="level1itemparentstylesroundedcornersbl" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSBL_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level1itemparentstylesshadowcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_SHADOW_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><input type="text" id="level1itemparentstylesshadowcolor" name="level1itemparentstylesshadowcolor" class="level1itemparentstyles <?php echo $this->colorpicker_class; ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesshadowblur" name="level1itemparentstylesshadowblur" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SHADOWBLUR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_spread.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesshadowspread" name="level1itemparentstylesshadowspread" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SHADOWSPREAD_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesshadowoffsetx" name="level1itemparentstylesshadowoffsetx" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesshadowoffsety" name="level1itemparentstylesshadowoffsety" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETY_DESC'); ?>" /></span>
	<label></label><input class="radiobutton level1itemparentstyles" type="radio" value="0" id="level1itemparentstylesshadowinsetno" name="level1itemparentstylesshadowinset" />
	<label class="radiobutton last"  for="level1itemparentstylesshadowinsetno" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_OUT'); ?>
	</label><input class="radiobutton level1itemparentstyles" type="radio" value="1" id="level1itemparentstylesshadowinsetyes" name="level1itemparentstylesshadowinset" />
	<label class="radiobutton last"  for="level1itemparentstylesshadowinsetyes" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_IN'); ?></label>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_DIMENSIONS_LABEL'); ?></div>
<div class="ckrow">
	<label for="level1itemparentstylesmargintop"><?php echo \Joomla\CMS\Language\Text::_('CK_MARGIN_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesmargintop" name="level1itemparentstylesmargintop" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesmarginright" name="level1itemparentstylesmarginright" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesmarginbottom" name="level1itemparentstylesmarginbottom" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylesmarginleft" name="level1itemparentstylesmarginleft" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINLEFT_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level1itemparentstylespaddingtop"><?php echo \Joomla\CMS\Language\Text::_('CK_PADDING_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_top.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylespaddingtop" name="level1itemparentstylespaddingtop" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_right.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylespaddingright" name="level1itemparentstylespaddingright" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_bottom.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylespaddingbottom" name="level1itemparentstylespaddingbottom" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_left.png" /></span><span style="width:30px;"><input type="text" id="level1itemparentstylespaddingleft" name="level1itemparentstylespaddingleft" class="level1itemparentstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGLEFT_DESC'); ?>" /></span>
</div>