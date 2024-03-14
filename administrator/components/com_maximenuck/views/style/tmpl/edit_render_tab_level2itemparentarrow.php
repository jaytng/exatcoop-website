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
<div class="ckrow">
	<img class="ckicon" src="<?php echo $this->imagespath ?>/parentitem_illustration_level2.png" />
	<p></p>
</div>
<div class="ckrow">
	<div class="ckbutton-group">
		<label for=""><?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWTYPE_LABEL'); ?></label>
		<input class="ckbutton level2itemnormalstyles" type="radio" value="triangle" id="level2itemnormalstylesparentarrowtypetriangle" name="level2itemnormalstylesparentarrowtype" />
		<label class="ckbutton first" for="level2itemnormalstylesparentarrowtypetriangle" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_TRIANGLE'); ?>
		</label><input class="ckbutton level2itemnormalstyles" type="radio" value="image" id="level2itemnormalstylesparentarrowtypeimage" name="level2itemnormalstylesparentarrowtype" />
		<label class="ckbutton"  for="level2itemnormalstylesparentarrowtypeimage" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?>
		</label><input class="ckbutton level2itemnormalstyles" type="radio" value="none" id="level2itemnormalstylesparentarrowtypenone" name="level2itemnormalstylesparentarrowtype" />
		<label class="ckbutton"  for="level2itemnormalstylesparentarrowtypenone" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NONE'); ?>
		</label>
	</div>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_COMMON_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level2itemnormalstylesparentarrowmargintop"><?php echo \Joomla\CMS\Language\Text::_('CK_MARGIN_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowmargintop" name="level2itemnormalstylesparentarrowmargintop" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowmarginright" name="level2itemnormalstylesparentarrowmarginright" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowmarginbottom" name="level2itemnormalstylesparentarrowmarginbottom" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowmarginleft" name="level2itemnormalstylesparentarrowmarginleft" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINLEFT_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level2itemnormalstylesparentarrowpositiontop"><?php echo \Joomla\CMS\Language\Text::_('CK_POSITION_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_top.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowpositiontop" name="level2itemnormalstylesparentarrowpositiontop" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_right.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowpositionright" name="level2itemnormalstylesparentarrowpositionright" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_bottom.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowpositionbottom" name="level2itemnormalstylesparentarrowpositionbottom" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_left.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowpositionleft" name="level2itemnormalstylesparentarrowpositionleft" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONLEFT_DESC'); ?>" /></span>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_TRIANGLE_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level2itemnormalstylesparentarrowcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level2itemnormalstylesparentarrowcolor" name="level2itemnormalstylesparentarrowcolor" class="level2itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level2itemhoverstylesparentarrowcolor" name="level2itemhoverstylesparentarrowcolor" class="level2itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWHOVERCOLOR_DESC'); ?>" />
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level2itemnormalstylesparentarrowwidth"><?php echo \Joomla\CMS\Language\Text::_('CK_DIMENSIONS_REQUIRED_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/width.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowwidth" name="level2itemnormalstylesparentarrowwidth" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_WIDTH_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/height.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentarrowheight" name="level2itemnormalstylesparentarrowheight" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_HEIGHT_DESC'); ?>" /></span>
</div>


<div class="ckrow">
	<label for="level2itemnormalstylesparentitemimage"><?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/image.png" />
	<div class="ckbutton-group">
		<input type="text" id="level2itemnormalstylesparentitemimage" name="level2itemnormalstylesparentitemimage" class="cktip level2itemnormalstyles" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_DESC'); ?>" style="max-width: none; width: 150px;"/>
		<a class="modal ckbutton" href="<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>/index.php?option=com_maximenuck&view=browse&tmpl=component&field=level2itemnormalstylesparentitemimage" rel="{handler: 'iframe'}" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
		<a class="ckbutton" href="javascript:void(0)" onclick="$ck(this).parent().find('input').val('');"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAR'); ?></a>
	</div>
</div>
<div class="ckrow">
	<label></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentitemimageleft" name="level2itemnormalstylesparentitemimageleft" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:45px;"><input type="text" id="level2itemnormalstylesparentitemimagetop" name="level2itemnormalstylesparentitemimagetop" class="level2itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONY_DESC'); ?>" /></span>
	<div class="ckbutton-group">
		<input class="" type="radio" value="repeat" id="level2itemnormalstylesparentitemimagerepeatrepeat" name="level2itemnormalstylesparentitemimagerepeatrepeat" class="level2itemnormalstyles" />
		<label class="ckbutton" for="level2itemnormalstylesparentitemimagerepeatrepeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="repeat-x" id="level2itemnormalstylesparentitemimagerepeatrepeat-x" name="level2itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level2itemnormalstylesparentitemimagerepeatrepeat-x"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-x.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="repeat-y" id="level2itemnormalstylesparentitemimagerepeatrepeat-y" name="level2itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level2itemnormalstylesparentitemimagerepeatrepeat-y"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-y.png" />
		</label><input class="level2itemnormalstyles" type="radio" value="no-repeat" id="level2itemnormalstylesparentitemimagerepeatno-repeat" name="level2itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level2itemnormalstylesparentitemimagerepeatno-repeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_no-repeat.png" /></label>
	</div>
</div>
