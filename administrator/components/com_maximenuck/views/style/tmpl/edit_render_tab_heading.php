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
<div class="ckrow ckalert ckalert-success" style="padding:10px;">
	<?php echo \Joomla\CMS\Language\Text::_('CK_HEADING_TYPE_DESCRIPTION'); ?>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_TEXT_LABEL'); ?></div>
<div class="ckrow">
	<label for="headingstylesfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_FONTSTYLE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="headingstylesfontsize" name="headingstylesfontsize" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/font_add.png" />
	<input type="text" id="headingstylestextgfont" name="headingstylestextgfont" class="headingstyles cktip" onchange="ckCleanGfontName(this)" title="<?php echo \Joomla\CMS\Language\Text::_('CK_GFONT_DESC'); ?>" />
	<input class="radiobutton headingstyles" type="radio" value="left" id="headingstylestextalignleft" name="headingstylestextalign" />
	<label class="radiobutton first" for="headingstylestextalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
	</label><input class="radiobutton headingstyles" type="radio" value="center" id="headingstylestextaligncenter" name="headingstylestextalign" />
	<label class="radiobutton"  for="headingstylestextaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
	</label><input class="radiobutton headingstyles" type="radio" value="right" id="headingstylestextalignright" name="headingstylestextalign" />
	<label class="radiobutton last"  for="headingstylestextalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
</div>
<div class="ckrow">
	<label for="headingstylesfontcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<input type="text" id="headingstylesfontcolor" name="headingstylesfontcolor" class="headingstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_APPEARANCE_LABEL'); ?></div>
<div class="ckrow">
	<label for="headingstylesbgcolor1"><?php echo \Joomla\CMS\Language\Text::_('CK_BGCOLOR_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<input type="text" id="headingstylesbgcolor1" name="headingstylesbgcolor1" class="cktip headingstyles <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BGCOLOR_DESC'); ?>"/>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<input type="text" id="headingstylesbgcolor2" name="headingstylesbgcolor2" class="cktip headingstyles <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BGCOLOR2_DESC'); ?>" onchange="ckCheckGradientImageConflict(this, 'headingstylesbgimage')"/>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/layers.png" />
	<input type="text" id="headingstylesbgopacity" name="headingstylesbgopacity" class="cktip headingstyles" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BGOPACITY_DESC'); ?>"/>
</div>
<div class="ckrow">
	<label for="headingstylesbgimage"><?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/image.png" />
	<span class="btn-group">
		<input type="text" id="headingstylesbgimage" name="headingstylesbgimage" class="cktip headingstyles" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_DESC'); ?>" onchange="ckCheckGradientImageConflict(this, 'headingstylesbgcolor2')"/>
		<a class="modal btn" href="<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>/index.php?option=com_media&view=images&tmpl=component&fieldid=headingstylesbgimage" rel="{handler: 'iframe', size: {x: 700, y: 600}}" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
		<a class="btn" href="javascript:void(0)" onclick="$ck(this).parent().find('input').val('');"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAR'); ?></a>
	</span>
</div>
<div class="ckrow">
	<label></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="headingstylesbgpositionx" name="headingstylesbgpositionx" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="headingstylesbgpositiony" name="headingstylesbgpositiony" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONY_DESC'); ?>" /></span>
	<input class="radiobutton" type="radio" value="repeat" id="headingstylesbgimagerepeatrepeat" name="headingstylesbgimagerepeat" class="headingstyles" />
	<label class="radiobutton first" for="headingstylesbgimagerepeatrepeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat.png" />
	</label><input class="radiobutton headingstyles" type="radio" value="repeat-x" id="headingstylesbgimagerepeatrepeat-x" name="headingstylesbgimagerepeat" />
	<label class="radiobutton"  for="headingstylesbgimagerepeatrepeat-x"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-x.png" />
	</label><input class="radiobutton headingstyles" type="radio" value="repeat-y" id="headingstylesbgimagerepeatrepeat-y" name="headingstylesbgimagerepeat" />
	<label class="radiobutton last"  for="headingstylesbgimagerepeatrepeat-y"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-y.png" />
	</label><input class="radiobutton headingstyles" type="radio" value="no-repeat" id="headingstylesbgimagerepeatno-repeat" name="headingstylesbgimagerepeat" />
	<label class="radiobutton last"  for="headingstylesbgimagerepeatno-repeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_no-repeat.png" /></label>
</div>
<?php $this->interface->createBorders('headingstyles') ?>
<div class="ckrow">
	<label for="headingstylesroundedcornerstl"><?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERS_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tl.png" /></span><span style="width:30px;"><input type="text" id="headingstylesroundedcornerstl" name="headingstylesroundedcornerstl" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSTL_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tr.png" /></span><span style="width:30px;"><input type="text" id="headingstylesroundedcornerstr" name="headingstylesroundedcornerstr" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSTR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_br.png" /></span><span style="width:30px;"><input type="text" id="headingstylesroundedcornersbr" name="headingstylesroundedcornersbr" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSBR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_bl.png" /></span><span style="width:30px;"><input type="text" id="headingstylesroundedcornersbl" name="headingstylesroundedcornersbl" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_ROUNDEDCORNERSBL_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="headingstylesshadowcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_SHADOW_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><input type="text" id="headingstylesshadowcolor" name="headingstylesshadowcolor" class="headingstyles <?php echo $this->colorpicker_class; ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span style="width:30px;"><input type="text" id="headingstylesshadowblur" name="headingstylesshadowblur" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SHADOWBLUR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_spread.png" /></span><span style="width:30px;"><input type="text" id="headingstylesshadowspread" name="headingstylesshadowspread" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SHADOWSPREAD_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="headingstylesshadowoffsetx" name="headingstylesshadowoffsetx" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="headingstylesshadowoffsety" name="headingstylesshadowoffsety" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETY_DESC'); ?>" /></span>
	<label></label><input class="radiobutton headingstyles" type="radio" value="0" id="headingstylesshadowinsetno" name="headingstylesshadowinset" />
	<label class="radiobutton last"  for="headingstylesshadowinsetno" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_OUT'); ?>
	</label><input class="radiobutton headingstyles" type="radio" value="1" id="headingstylesshadowinsetyes" name="headingstylesshadowinset" />
	<label class="radiobutton last"  for="headingstylesshadowinsetyes" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_IN'); ?></label>
</div>
<div class="ckrow">
	<label for="headingstylestextshadowcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_TEXTSHADOW_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><input type="text" id="headingstylestextshadowcolor" name="headingstylestextshadowcolor" class="headingstyles <?php echo $this->colorpicker_class; ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span style="width:30px;"><input type="text" id="headingstylestextshadowblur" name="headingstylestextshadowblur" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SHADOWBLUR_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="headingstylestextshadowoffsetx" name="headingstylestextshadowoffsetx" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="headingstylestextshadowoffsety" name="headingstylestextshadowoffsety" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_OFFSETY_DESC'); ?>" /></span>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_DIMENSIONS_LABEL'); ?></div>
<div class="ckrow">
	<label for="headingstylesmargintop"><?php echo \Joomla\CMS\Language\Text::_('CK_MARGIN_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:30px;"><input type="text" id="headingstylesmargintop" name="headingstylesmargintop" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:30px;"><input type="text" id="headingstylesmarginright" name="headingstylesmarginright" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:30px;"><input type="text" id="headingstylesmarginbottom" name="headingstylesmarginbottom" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:30px;"><input type="text" id="headingstylesmarginleft" name="headingstylesmarginleft" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINLEFT_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="headingstylespaddingtop"><?php echo \Joomla\CMS\Language\Text::_('CK_PADDING_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_top.png" /></span><span style="width:30px;"><input type="text" id="headingstylespaddingtop" name="headingstylespaddingtop" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_right.png" /></span><span style="width:30px;"><input type="text" id="headingstylespaddingright" name="headingstylespaddingright" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_bottom.png" /></span><span style="width:30px;"><input type="text" id="headingstylespaddingbottom" name="headingstylespaddingbottom" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_left.png" /></span><span style="width:30px;"><input type="text" id="headingstylespaddingleft" name="headingstylespaddingleft" class="headingstyles cktip" style="width:30px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGLEFT_DESC'); ?>" /></span>
</div>