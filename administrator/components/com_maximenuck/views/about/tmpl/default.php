<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

use Maximenuck\Helper;

// get the version installed
$installed_version = 'UNKOWN';
if ($xml_installed = simplexml_load_file(JPATH_SITE .'/administrator/components/com_maximenuck/maximenuck.xml')) {
	$installed_version = (string)$xml_installed->version;
}

// loads the language files from the frontend
$lang	= \Joomla\CMS\Factory::getLanguage();
$lang->load('mod_maximenuck', JPATH_SITE . '/modules/mod_maximenuck', $lang->getTag(), false);
$lang->load('mod_maximenuck', JPATH_SITE, $lang->getTag(), false);
?>
<style>
	.ckaboutversion {
		margin: 10px;
		padding: 10px;
		font-size: 20px;
		font-color: #000;
		text-align: center;
	}
	.ckcenter {
		margin: 10px 0;
		text-align: center;
	}
</style>
<div style="display:flex;flex-wrap:wrap;">
	<div style="flex: 0 0 250px">
	<?php Helper::addSidebar(); ?>
	</div>
	<div style="flex: 1 1 auto">
	<div class="ckaboutversion">MAXIMENU CK <?php echo $installed_version; ?> LIGHT</div>
	<div class="ckcenter"><a href="https://www.joomlack.fr/en/joomla-extensions/maximenu-ck" target="_blank" class="btn btn-small btn-inverse"><?php echo \Joomla\CMS\Language\Text::_('Get the Pro version'); ?></a></div>
	<p class="ckcenter"><a href="https://www.joomlack.fr" target="_blank">https://www.joomlack.fr</a></p>
	<div class="alert ckcenter"><a href="https://extensions.joomla.org/extension/maxi-menu-ck/" target="_blank" class="btn btn-small btn-warning"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_VOTE_JED'); ?></a></div>
	<div class="ckcenter"><a href="https://www.joomlack.fr/en/documentation/maximenu-ck" target="_blank"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_DOCUMENTATION')  ?></a></div>
	<hr />
	</div>
</div>