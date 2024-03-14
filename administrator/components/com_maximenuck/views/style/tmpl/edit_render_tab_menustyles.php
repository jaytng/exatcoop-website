<?php
/**
 * @name		Maximenu CK params
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$prefix = 'menustyles';
?>
<div class="ckrow">
	<label for="<?php echo $prefix; ?>textgfont"><?php echo \Joomla\CMS\Language\Text::_('CK_FONTSTYLE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/font_add.png" />
	<input type="text" id="<?php echo $prefix; ?>textgfont" name="<?php echo $prefix; ?>textgfont" class="<?php echo $prefix; ?> cktip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo \Joomla\CMS\Language\Text::_('CK_GFONT_DESC'); ?>" style="max-width:none;width:250px;" />
	<input type="hidden" id="<?php echo $prefix; ?>textisgfont" name="<?php echo $prefix; ?>textisgfont" class="isgfont <?php echo $prefix; ?>" />
</div>
<div class="ckrow">
	<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->imagespath ?>/font.png" />
	<div class="ckbutton-group">
		<input class="<?php echo $prefix; ?>" type="radio" value="left" id="<?php echo $prefix; ?>textalignleft" name="<?php echo $prefix; ?>textalign" />
		<label class="ckbutton first" for="<?php echo $prefix; ?>textalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
		</label><input class="<?php echo $prefix; ?>" type="radio" value="center" id="<?php echo $prefix; ?>textaligncenter" name="<?php echo $prefix; ?>textalign" />
		<label class="ckbutton"  for="<?php echo $prefix; ?>textaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
		</label><input class="<?php echo $prefix; ?>" type="radio" value="right" id="<?php echo $prefix; ?>textalignright" name="<?php echo $prefix; ?>textalign" />
		<label class="ckbutton last"  for="<?php echo $prefix; ?>textalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
	</div>
	<div class="ckbutton-group">
		<input class="level1itemnormalstyles" type="radio" value="lowercase" id="level1itemnormalstylestexttransformlowercase" name="level1itemnormalstylestexttransform" />
		<label class="ckbutton first cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_LOWERCASE'); ?>" for="level1itemnormalstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_lowercase.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="uppercase" id="level1itemnormalstylestexttransformuppercase" name="level1itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_UPPERCASE'); ?>" for="level1itemnormalstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_uppercase.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="capitalize" id="level1itemnormalstylestexttransformcapitalize" name="level1itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_CAPITALIZE'); ?>" for="level1itemnormalstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_capitalize.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="initial" id="level1itemnormalstylestexttransformdefault" name="level1itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_DEFAULT'); ?>" for="level1itemnormalstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_default.png" />
		</label>
	</div>
</div>
<div class="ckrow">
	<label for="level1itemnormalstylesfontweightbold"></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_bold.png" />
	<div class="ckbutton-group">
		<input class="level1itemnormalstyles" type="radio" value="bold" id="level1itemnormalstylesfontweightbold" name="level1itemnormalstylesfontweight" />
		<label class="ckbutton first cktip" title="" for="level1itemnormalstylesfontweightbold" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_BOLD'); ?>
		</label><input class="level1itemnormalstyles" type="radio" value="normal" id="level1itemnormalstylesfontweightnormal" name="level1itemnormalstylesfontweight" />
		<label class="ckbutton cktip" title="" for="level1itemnormalstylesfontweightnormal" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_underline.png" />
	<div class="ckbutton-group">
		<input class="level1itemnormalstyles" type="radio" value="underline" id="level1itemnormalstylesfontunderlineunderline" name="level1itemnormalstylesfontunderline" />
		<label class="ckbutton first cktip" title="" for="level1itemnormalstylesfontunderlineunderline" style="width:auto;"><?php echo ucfirst(\Joomla\CMS\Language\Text::_('CK_UNDERLINE')); ?>
		</label><input class="level1itemnormalstyles" type="radio" value="none" id="level1itemnormalstylesfontunderlinenone" name="level1itemnormalstylesfontunderline" />
		<label class="ckbutton cktip" title="" for="level1itemnormalstylesfontunderlinenone" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
</div>


<div class="ckrow">
	<label for="level1itemnormalstylesfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_TITLEFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level1itemnormalstylesfontsize" name="level1itemnormalstylesfontsize" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level1itemnormalstylescolor" name="level1itemnormalstylescolor" class="level1itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />

	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level1itemhoverstylescolor" name="level1itemhoverstylescolor" class="level1itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>
<div class="ckrow">
	<label for="level1itemnormalstylesdescfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_DESCFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level1itemnormalstylesdescfontsize" name="level1itemnormalstylesdescfontsize" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level1itemnormalstylesdesccolor" name="level1itemnormalstylesdesccolor" class="level1itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level1itemhoverstylesdesccolor" name="level1itemhoverstylesdesccolor" class="level1itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>