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
	<label for="level1itemnormalstylesiconmargintop"><?php echo \Joomla\CMS\Language\Text::_('CK_MARGIN_LABEL'); ?></label>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesiconmargintop" name="level1itemnormalstylesiconmargintop" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINTOP_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesiconmarginright" name="level1itemnormalstylesiconmarginright" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesiconmarginbottom" name="level1itemnormalstylesiconmarginbottom" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
	<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:45px;"><input type="text" id="level1itemnormalstylesiconmarginleft" name="level1itemnormalstylesiconmarginleft" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_MARGINLEFT_DESC'); ?>" /></span>
</div>
<div class="ckrow">
	<label for="level1itemnormalstylesiconfontsize"><?php echo \Joomla\CMS\Language\Text::_('CK_ICON_FORMAT'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
	<input type="text" id="level1itemnormalstylesiconfontsize" name="level1itemnormalstylesiconfontsize" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_FONTSIZE_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_align_middle.png" />
	<input type="text" id="level1itemnormalstylesiconlineheight" name="level1itemnormalstylesiconlineheight" class="level1itemnormalstylesicon cktip" style="width:45px;" title="<?php echo \Joomla\CMS\Language\Text::_('CK_LINEHEIGHT_DESC'); ?>" />
</div>
<div class="ckrow">
	<label for="level1itemnormalstylesiconfontcolor"><?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_LABEL'); ?></label>
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_NORMAL'); ?></span>
	<input type="text" id="level1itemnormalstylesiconfontcolor" name="level1itemnormalstylesiconfontcolor" class="level1itemnormalstylesicon cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWCOLOR_DESC'); ?>" />
	<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
	<span><?php echo \Joomla\CMS\Language\Text::_('CK_HOVER'); ?></span>
	<input type="text" id="level1itemhoverstylesiconfontcolor" name="level1itemhoverstylesiconfontcolor" class="level1itemhoverstylesicon cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Joomla\CMS\Language\Text::_('CK_PARENTARROWHOVERCOLOR_DESC'); ?>" />
</div>