<?php
/**
 * @name		Maximenu CK params
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$prefix = 'level2menustyles';
?>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_DIMENSIONS_LABEL'); ?></div>
<div class="ckrow">
	<div id="level2menustyles_iilustration">
		<img src="<?php echo $this->imagespath ?>/menu_illustration.png" />
	</div>
</div>
<div class="ckrow">
	<label for="menustylessubmenuheight"><?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENU_SETTINGS'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/height.png" /></span><span><input type="text" id="menustylessubmenuheight" name="menustylessubmenuheight" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENUHEIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/width.png" /></span><span><input type="text" id="menustylessubmenuwidth" name="menustylessubmenuwidth" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENUWIDTH_DESC'); ?>" /></span>
	<br />
	<label></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span><input type="text" id="menustylessubmenu1marginleft" name="menustylessubmenu1marginleft" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENUMARGINLEFT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span><input type="text" id="menustylessubmenu1margintop" name="menustylessubmenu1margintop" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENUMARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span><input type="text" id="menustylessubmenu2marginleft" name="menustylessubmenu2marginleft" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBSUBMENUMARGINLEFT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span><input type="text" id="menustylessubmenu2margintop" name="menustylessubmenu2margintop" class="menustyles cktip" style="width:60px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_SUBSUBMENUMARGINTOP_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level2menustylespaddingtop"><?php echo \Joomla\CMS\Language\Text::_('CK_PADDING_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_top.png" /></span><span style="width:45px;"><input type="text" id="level2menustylespaddingtop" name="level2menustylespaddingtop" class="level2menustyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_right.png" /></span><span style="width:45px;"><input type="text" id="level2menustylespaddingright" name="level2menustylespaddingright" class="level2menustyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_bottom.png" /></span><span style="width:45px;"><input type="text" id="level2menustylespaddingbottom" name="level2menustylespaddingbottom" class="level2menustyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_left.png" /></span><span style="width:45px;"><input type="text" id="level2menustylespaddingleft" name="level2menustylespaddingleft" class="level2menustyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PADDINGLEFT_DESC'); ?>" /></span>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_TEXT_LABEL'); ?></div>
<div class="ckrow">
	<label for="<?php echo $prefix; ?>textgfont"><?php echo \Joomla\CMS\Language\Text::_('CK_FONTSTYLE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/font_add.png" />
	<input type="text" id="<?php echo $prefix; ?>textgfont" name="<?php echo $prefix; ?>textgfont" class="<?php echo $prefix; ?> cktip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo \Joomla\CMS\Language\Text::_('CK_GFONT_DESC'); ?>" style="max-width:none;width:250px;" />
	<input type="hidden" id="<?php echo $prefix; ?>textisgfont" name="<?php echo $prefix; ?>textisgfont" class="isgfont <?php echo $prefix; ?>" />
</div>
<div class="ckrow">
	<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->imagespath ?>/font.png" />
	<div class="ckbutton-group">
		<input class="level2itemnormalstyles" type="radio" value="left" id="level2itemnormalstylestextalignleft" name="level2itemnormalstylestextalign" />
		<label class="ckbutton first" for="level2itemnormalstylestextalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="center" id="level2itemnormalstylestextaligncenter" name="level2itemnormalstylestextalign" />
		<label class="ckbutton"  for="level2itemnormalstylestextaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="right" id="level2itemnormalstylestextalignright" name="level2itemnormalstylestextalign" />
		<label class="ckbutton last"  for="level2itemnormalstylestextalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
	</div>
	<div class="ckbutton-group">
		<input class="level2itemnormalstyles" type="radio" value="lowercase" id="level2itemnormalstylestexttransformlowercase" name="level2itemnormalstylestexttransform" />
		<label class="ckbutton first cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_LOWERCASE'); ?>" for="level2itemnormalstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_lowercase.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="uppercase" id="level2itemnormalstylestexttransformuppercase" name="level2itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_UPPERCASE'); ?>" for="level2itemnormalstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_uppercase.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="capitalize" id="level2itemnormalstylestexttransformcapitalize" name="level2itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_CAPITALIZE'); ?>" for="level2itemnormalstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_capitalize.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="initial" id="level2itemnormalstylestexttransformdefault" name="level2itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_DEFAULT'); ?>" for="level2itemnormalstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_default.png" />
		</label>
	</div>
</div>
<div class="ckrow">
	<label for="level2itemnormalstylesfontweightbold"></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_bold.png" />
	<div class="ckbutton-group">
		<input class="level2itemnormalstyles" type="radio" value="bold" id="level2itemnormalstylesfontweightbold" name="level2itemnormalstylesfontweight" />
		<label class="ckbutton first cktip" title="" for="level2itemnormalstylesfontweightbold" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_BOLD'); ?>
		</label><input class="level2itemnormalstyles" type="radio" value="normal" id="level2itemnormalstylesfontweightnormal" name="level2itemnormalstylesfontweight" />
		<label class="ckbutton cktip" title="" for="level2itemnormalstylesfontweightnormal" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_underline.png" />
	<div class="ckbutton-group">
		<input class="level2itemnormalstyles" type="radio" value="underline" id="level2itemnormalstylesfontunderlineunderline" name="level2itemnormalstylesfontunderline" />
		<label class="ckbutton first cktip" title="" for="level2itemnormalstylesfontunderlineunderline" style="width:auto;"><?php echo ucfirst(\Joomla\CMS\Language\Text::_('CK_UNDERLINE')); ?>
		</label><input class="level2itemnormalstyles" type="radio" value="none" id="level2itemnormalstylesfontunderlinenone" name="level2itemnormalstylesfontunderline" />
		<label class="ckbutton cktip" title="" for="level2itemnormalstylesfontunderlinenone" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
</div>

<div class="ckrow">
	<label for="level2itemnormalstylesfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_TITLEFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level2itemnormalstylesfontsize" name="level2itemnormalstylesfontsize" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level2itemnormalstylescolor" name="level2itemnormalstylescolor" class="level2itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />

	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level2itemhoverstylescolor" name="level2itemhoverstylescolor" class="level2itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>
<div class="ckrow">
	<label for="level2itemnormalstylesdescfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_DESCFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level2itemnormalstylesdescfontsize" name="level2itemnormalstylesdescfontsize" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level2itemnormalstylesdesccolor" name="level2itemnormalstylesdesccolor" class="level2itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level2itemhoverstylesdesccolor" name="level2itemhoverstylesdesccolor" class="level2itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>