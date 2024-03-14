<?php
/**
 * @name		Maximenu CK params
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$prefix = 'level3menustyles';
?>
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
		<input class="level3itemnormalstyles" type="radio" value="left" id="level3itemnormalstylestextalignleft" name="level3itemnormalstylestextalign" />
		<label class="ckbutton first" for="level3itemnormalstylestextalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
		</label><input class="level3itemnormalstyles" type="radio" value="center" id="level3itemnormalstylestextaligncenter" name="level3itemnormalstylestextalign" />
		<label class="ckbutton"  for="level3itemnormalstylestextaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
		</label><input class="level3itemnormalstyles" type="radio" value="right" id="level3itemnormalstylestextalignright" name="level3itemnormalstylestextalign" />
		<label class="ckbutton last"  for="level3itemnormalstylestextalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
	</div>
	<div class="ckbutton-group">
		<input class="level3itemnormalstyles" type="radio" value="lowercase" id="level3itemnormalstylestexttransformlowercase" name="level3itemnormalstylestexttransform" />
		<label class="ckbutton first cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_LOWERCASE'); ?>" for="level3itemnormalstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_lowercase.png" />
		</label><input class="level3itemnormalstyles" type="radio" value="uppercase" id="level3itemnormalstylestexttransformuppercase" name="level3itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_UPPERCASE'); ?>" for="level3itemnormalstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_uppercase.png" />
		</label><input class="level3itemnormalstyles" type="radio" value="capitalize" id="level3itemnormalstylestexttransformcapitalize" name="level3itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_CAPITALIZE'); ?>" for="level3itemnormalstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_capitalize.png" />
		</label><input class="level3itemnormalstyles" type="radio" value="initial" id="level3itemnormalstylestexttransformdefault" name="level3itemnormalstylestexttransform" />
		<label class="ckbutton cktip" title="<?php echo \Joomla\CMS\Language\Text::_('CK_DEFAULT'); ?>" for="level3itemnormalstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_default.png" />
		</label>
	</div>
</div>
<div class="ckrow">
	<label for="level3itemnormalstylesfontweightbold"></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_bold.png" />
	<div class="ckbutton-group">
		<input class="level3itemnormalstyles" type="radio" value="bold" id="level3itemnormalstylesfontweightbold" name="level3itemnormalstylesfontweight" />
		<label class="ckbutton first cktip" title="" for="level3itemnormalstylesfontweightbold" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_BOLD'); ?>
		</label><input class="level3itemnormalstyles" type="radio" value="normal" id="level3itemnormalstylesfontweightnormal" name="level3itemnormalstylesfontweight" />
		<label class="ckbutton cktip" title="" for="level3itemnormalstylesfontweightnormal" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/text_underline.png" />
	<div class="ckbutton-group">
		<input class="level3itemnormalstyles" type="radio" value="underline" id="level3itemnormalstylesfontunderlineunderline" name="level3itemnormalstylesfontunderline" />
		<label class="ckbutton first cktip" title="" for="level3itemnormalstylesfontunderlineunderline" style="width:auto;"><?php echo ucfirst(\Joomla\CMS\Language\Text::_('CK_UNDERLINE')); ?>
		</label><input class="level3itemnormalstyles" type="radio" value="none" id="level3itemnormalstylesfontunderlinenone" name="level3itemnormalstylesfontunderline" />
		<label class="ckbutton cktip" title="" for="level3itemnormalstylesfontunderlinenone" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?>
		</label>
	</div>
</div>

<div class="ckrow">
	<label for="level3itemnormalstylesfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_TITLEFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level3itemnormalstylesfontsize" name="level3itemnormalstylesfontsize" class="level3itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level3itemnormalstylescolor" name="level3itemnormalstylescolor" class="level3itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />

	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level3itemhoverstylescolor" name="level3itemhoverstylescolor" class="level3itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>
<div class="ckrow">
	<label for="level3itemnormalstylesdescfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_DESCFONTSTYLES_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level3itemnormalstylesdescfontsize" name="level3itemnormalstylesdescfontsize" class="level3itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level3itemnormalstylesdesccolor" name="level3itemnormalstylesdesccolor" class="level3itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level3itemhoverstylesdesccolor" name="level3itemhoverstylesdesccolor" class="level3itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTHOVERCOLOR_DESC'); ?>" />
</div>