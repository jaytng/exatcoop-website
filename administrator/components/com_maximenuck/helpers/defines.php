<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

// set variables
define('MAXIMENUCK_PLATFORM', 'joomla');
define('MAXIMENUCK_PATH', JPATH_SITE . '/administrator/components/com_maximenuck');
define('MAXIMENUCK_ADMIN_PATH', MAXIMENUCK_PATH);
define('MAXIMENUCK_BASE_PATH', JPATH_BASE . '/components/com_maximenuck');
define('MAXIMENUCK_FRONT_PATH', JPATH_SITE . '/components/com_maximenuck');
define('MAXIMENUCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_maximenuck/projects');
define('MAXIMENUCK_ADMIN_URL', \Joomla\CMS\Uri\Uri::root(true) . '/administrator/index.php?option=com_maximenuck');
define('MAXIMENUCK_URL', \Joomla\CMS\Uri\Uri::base(true) . '/index.php?option=com_maximenuck');
define('MAXIMENUCK_ADMIN_GENERAL_URL', \Joomla\CMS\Uri\Uri::root(true) . '/administrator/index.php?option=com_maximenuck&view=templates');
define('MAXIMENUCK_MEDIA_URI', \Joomla\CMS\Uri\Uri::root(true) . '/media/com_maximenuck');
define('MAXIMENUCK_MEDIA_URL', MAXIMENUCK_MEDIA_URI);
define('MAXIMENUCK_MEDIA_PATH', JPATH_ROOT . '/media/com_maximenuck');
define('MAXIMENUCK_PLUGIN_URL', MAXIMENUCK_MEDIA_URI);
define('MAXIMENUCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('MAXIMENUCK_SITE_ROOT', JPATH_ROOT);
define('MAXIMENUCK_URI', \Joomla\CMS\Uri\Uri::root(true) . '/administrator/components/com_maximenuck');
define('MAXIMENUCK_URI_ROOT', \Joomla\CMS\Uri\Uri::root(true));
define('MAXIMENUCK_URI_BASE', \Joomla\CMS\Uri\Uri::base(true));
define('MAXIMENUCK_PLUGINS_PATH', JPATH_SITE . '/plugins/maximenuck');
define('MAXIMENUCK_ISJ4', version_compare(JVERSION, "4") >= 0);

// include the classes
require_once MAXIMENUCK_PATH . '/helpers/ckinput.php';
require_once MAXIMENUCK_PATH . '/helpers/cktext.php';
require_once MAXIMENUCK_PATH . '/helpers/ckfile.php';
require_once MAXIMENUCK_PATH . '/helpers/ckfolder.php';
require_once MAXIMENUCK_PATH . '/helpers/ckfof.php';
//require_once MAXIMENUCK_PATH . '/helpers/helper.php';
//require_once MAXIMENUCK_PATH . '/helpers/ckcontroller.php';
//require_once MAXIMENUCK_PATH . '/helpers/ckmodel.php';
//require_once MAXIMENUCK_PATH . '/helpers/ckview.php';