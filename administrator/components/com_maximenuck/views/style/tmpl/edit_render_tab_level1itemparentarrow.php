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
	<img class="ckicon" src="<?php echo $this->imagespath ?>/parentitem_illustration.png" />
	<p></p>
</div>
<div class="ckrow">
	<div class="ckbutton-group">
		<label for=""><?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWTYPE_LABEL'); ?></label>
		<input class="ckbutton level1itemnormalstyles" type="radio" value="triangle" id="level1itemnormalstylesparentarrowtypetriangle" name="level1itemnormalstylesparentarrowtype" />
		<label class="ckbutton first" for="level1itemnormalstylesparentarrowtypetriangle" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_TRIANGLE'); ?>
		</label><input class="ckbutton level1itemnormalstyles" type="radio" value="image" id="level1itemnormalstylesparentarrowtypeimage" name="level1itemnormalstylesparentarrowtype" />
		<label class="ckbutton"  for="level1itemnormalstylesparentarrowtypeimage" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE'); ?>
		</label><input class="ckbutton level1itemnormalstyles" type="radio" value="none" id="level1itemnormalstylesparentarrowtypenone" name="level1itemnormalstylesparentarrowtype" />
		<label class="ckbutton"  for="level1itemnormalstylesparentarrowtypenone" style="width:auto;"><?php echo \Joomla\CMS\Language\Text::_('CK_NONE'); ?>
		</label>
	</div>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_COMMON_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level1itemnormalstylesparentarrowmargintop"><?php echo \Joomla\CMS\Language\Text::_('CK_MARGIN_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowmargintop" name="level1itemnormalstylesparentarrowmargintop" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowmarginright" name="level1itemnormalstylesparentarrowmarginright" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowmarginbottom" name="level1itemnormalstylesparentarrowmarginbottom" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowmarginleft" name="level1itemnormalstylesparentarrowmarginleft" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINLEFT_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level1itemnormalstylesparentarrowpositiontop"><?php echo \Joomla\CMS\Language\Text::_('CK_POSITION_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_top.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowpositiontop" name="level1itemnormalstylesparentarrowpositiontop" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_right.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowpositionright" name="level1itemnormalstylesparentarrowpositionright" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_bottom.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowpositionbottom" name="level1itemnormalstylesparentarrowpositionbottom" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/position_left.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowpositionleft" name="level1itemnormalstylesparentarrowpositionleft" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_POSITIONLEFT_DESC'); ?>" /></span>
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_TRIANGLE_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level1itemnormalstylesparentarrowcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level1itemnormalstylesparentarrowcolor" name="level1itemnormalstylesparentarrowcolor" class="level1itemnormalstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level1itemhoverstylesparentarrowcolor" name="level1itemhoverstylesparentarrowcolor" class="level1itemhoverstyles cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWHOVERCOLOR_DESC'); ?>" />
</div>
<div class="ckheading"><?php echo \Joomla\CMS\Language\Text::_('CK_IMAGE_OPTIONS'); ?></div>
<div class="ckrow">
	<label for="level1itemnormalstylesparentarrowwidth"><?php echo \Joomla\CMS\Language\Text::_('CK_DIMENSIONS_REQUIRED_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/width.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowwidth" name="level1itemnormalstylesparentarrowwidth" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_WIDTH_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/height.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentarrowheight" name="level1itemnormalstylesparentarrowheight" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_HEIGHT_DESC'); ?>" /></span>
</div>


<div class="ckrow">
	<label for="level1itemnormalstylesparentitemimage"><?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/image.png" />
	<div class="ckbutton-group">
		<input type="text" id="level1itemnormalstylesparentitemimage" name="level1itemnormalstylesparentitemimage" class="cktip level1itemnormalstyles" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDIMAGE_DESC'); ?>" style="max-width: none; width: 150px;"/>
		<a class="modal ckbutton" href="<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>/index.php?option=com_maximenuck&view=browse&tmpl=component&field=level1itemnormalstylesparentitemimage" rel="{handler: 'iframe'}" ><?php echo \Joomla\CMS\Language\Text::_('CK_SELECT'); ?></a>
		<a class="ckbutton" href="javascript:void(0)" onclick="$ck(this).parent().find('input').val('');"><?php echo \Joomla\CMS\Language\Text::_('CK_CLEAR'); ?></a>
	</div>
</div>
<div class="ckrow">
	<label></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentitemimageleft" name="level1itemnormalstylesparentitemimageleft" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONX_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesparentitemimagetop" name="level1itemnormalstylesparentitemimagetop" class="level1itemnormalstyles cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_BACKGROUNDPOSITIONY_DESC'); ?>" /></span>
	<div class="ckbutton-group">
		<input class="" type="radio" value="repeat" id="level1itemnormalstylesparentitemimagerepeatrepeat" name="level1itemnormalstylesparentitemimagerepeatrepeat" class="level1itemnormalstyles" />
		<label class="ckbutton" for="level1itemnormalstylesparentitemimagerepeatrepeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="repeat-x" id="level1itemnormalstylesparentitemimagerepeatrepeat-x" name="level1itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level1itemnormalstylesparentitemimagerepeatrepeat-x"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-x.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="repeat-y" id="level1itemnormalstylesparentitemimagerepeatrepeat-y" name="level1itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level1itemnormalstylesparentitemimagerepeatrepeat-y"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-y.png" />
		</label><input class="level1itemnormalstyles" type="radio" value="no-repeat" id="level1itemnormalstylesparentitemimagerepeatno-repeat" name="level1itemnormalstylesparentitemimagerepeatrepeat" />
		<label class="ckbutton"  for="level1itemnormalstylesparentitemimagerepeatno-repeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_no-repeat.png" /></label>
	</div>
</div>
