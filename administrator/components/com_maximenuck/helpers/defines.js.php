<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;
?>
<script>
	var MAXIMENUCK = {
		TOKEN : '<?php echo \Joomla\CMS\Factory::getSession()->getFormToken() ?>=1'
		, URIBASE : '<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>'
		, URIBASEABS : '<?php echo \Joomla\CMS\Uri\Uri::base() ?>'
		, URIROOT : '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>'
		, URIROOTABS : '<?php echo \Joomla\CMS\Uri\Uri::root() ?>'
		, HASPAGEBUILDERCK : '<?php echo (int)file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilderck') ?>'
		, ADMIN_URL : '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>/administrator/index.php?option=com_maximenuck'
		, FRONT_URL : '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>/index.php?option=com_maximenuck'
		, BASE_URL : '<?php echo \Joomla\CMS\Uri\Uri::base(true) ?>/index.php?option=com_maximenuck'
		, ISJ4 : '<?php echo version_compare(JVERSION, "4") ?>'
	};
</script>