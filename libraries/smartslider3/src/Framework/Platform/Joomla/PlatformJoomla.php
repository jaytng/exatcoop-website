<?php


namespace Nextend\Framework\Platform\Joomla;


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Platform\AbstractPlatform;
use Nextend\Framework\Plugin;

class PlatformJoomla extends AbstractPlatform {

    protected $hasPosts = true;

    public function __construct() {

        if (Factory::getApplication()
                   ->isClient('administrator')) {

            $this->isAdmin = true;
        }

        // Load required UTF-8 config from Joomla
        jimport('joomla.utilities.string');

        if (!defined('JPATH_NEXTEND_IMAGES')) {
            define('JPATH_NEXTEND_IMAGES', '/' . trim(ComponentHelper::getParams('com_media')
                                                                     ->get('image_path', 'images'), "/"));
        }

        Plugin::addAction('exit', array(
            $this,
            'addKeepAlive'
        ));
    }

    public function getName() {

        return 'joomla';
    }

    public function getLabel() {

        return 'Joomla';
    }

    public function getVersion() {

        return JVERSION;
    }

    public function getSiteUrl() {

        return Uri::root();
    }

    public function getCharset() {

        return Document::getInstance()
                       ->getCharset();
    }

    public function getMysqlDate() {

        $config = Factory::getConfig();

        return Factory::getDate('now', $config->get('offset'))
                      ->toSql(true);
    }

    public function getTimestamp() {

        return strtotime($this->getMysqlDate());
    }

    public function localizeDate($date) {

        return HtmlHelper::date($date, Text::_('DATE_FORMAT_LC3'));
    }

    public function getPublicDirectory() {

        if (defined('JPATH_MEDIA')) {
            return rtrim(JPATH_SITE, '\\/') . JPATH_MEDIA;
        }

        return rtrim(JPATH_SITE, '\\/') . '/media';
    }

    public function getUserEmail() {

        return Factory::getUser()->email;
    }

    public function getDebug() {
        $debug = array();

        $db    = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array(
            'template',
            'title'
        )))
              ->from($db->quoteName('#__template_styles'))
              ->where('client_id = 0 AND home = 1');

        $db->setQuery($query);
        $result = $db->loadObject();
        if (isset($result->template)) {
            $debug[] = '';
            $debug[] = 'Template: ' . $result->template . ' - ' . $result->title;
        }

        $query = $db->getQuery(true);
        $query->select($db->quoteName(array(
            'name',
            'manifest_cache'
        )))
              ->from($db->quoteName('#__extensions'));

        $db->setQuery($query);
        $result = $db->loadObjectList();

        $debug[] = '';
        $debug[] = 'Extensions:';
        foreach ($result as $extension) {
            $decode = json_decode($extension->manifest_cache);
            if (isset($extension->name) && isset($decode->version)) {
                $debug[] = $extension->name . ' : ' . $decode->version;
            } else if (isset($extension->name)) {
                $debug[] = $extension->name;
            }
        }

        return $debug;
    }

    public function filterAssetsPath($assetsPath) {
        /**
         * Fix the error when Joomla installed in the system root / and Joomla sets JPATH_* to //
         */
        $jpath_libraries = JPATH_LIBRARIES;
        if (strpos(JPATH_LIBRARIES, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) === 0) {
            $jpath_libraries = substr(JPATH_LIBRARIES, 1);
        }
        if (strpos($assetsPath, $jpath_libraries) === 0) {

            $jpath_root = JPATH_ROOT;
            if (JPATH_ROOT === DIRECTORY_SEPARATOR) {
                $jpath_root = '';
            }

            return str_replace('/', DIRECTORY_SEPARATOR, $jpath_root . '/media/' . ltrim(substr($assetsPath, strlen($jpath_libraries)), '/\\'));
        }

        return $assetsPath;
    }

    public function addKeepAlive() {
        if ($this->isAdmin) {
            $lifetime = Factory::getConfig()
                               ->get('lifetime');
            if (empty($lifetime)) {
                $lifetime = 60;
            }
            $lifetime = min(max(intval($lifetime) - 1, 9), 60 * 24);
            Js::addInline('setInterval(function(){$.ajax({url: "' . Uri::current() . '", cache: false});}, ' . ($lifetime * 60 * 1000) . ');');
        }
    }
}