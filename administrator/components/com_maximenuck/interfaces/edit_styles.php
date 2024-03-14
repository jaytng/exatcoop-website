<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Maximenuck\CKFof;
use Maximenuck\Helper;

include_once MAXIMENUCK_PATH . '/helpers/ckinterface.php';
$interface = new Maximenuck\CKInterface();

// load the styles
$customid = $this->input->get('customid', 0, 'int');
$type = $this->input->get('type', 'item', 'string');
$model = CKFof::getModel('Menubuilder');
$row = $model->getMenubuilderItem($customid);
?>
<input class="itemstyles" name="id" type="hidden" value="<?php echo $row->id ?>" />
<input class="itemstyles" name="fields" type="hidden" value="<?php echo $row->styles ?>" />
<input class="itemstyles" name="customid" type="hidden" value="<?php echo $row->customid ?>" />
<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-styles">
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('CK_STYLES'); ?></div>
	
	
	<div class="ckinterfacetablink current" data-level="1" data-tab="tab_itemnormalstyles" data-group="main"><?php echo \Joomla\CMS\Language\Text::_('CK_MENULINK'); ?></div>
	<div class="ckinterfacetablink" data-level="1" data-tab="tab_itemhoverstyles" data-group="main"><?php echo \Joomla\CMS\Language\Text::_('CK_MENULINK_HOVER'); ?></div>
	<div class="ckinterfacetablink" data-level="1" data-tab="tab_itemactivestyless" data-group="main"><?php echo \Joomla\CMS\Language\Text::_('CK_MENULINK_ACTIVE'); ?></div>
	<div class="ckinterfacetablink" data-level="1" data-tab="tab_submenustyles" data-group="main"><?php echo \Joomla\CMS\Language\Text::_('CK_SUBMENU'); ?></div>
	<div class="ckclr"></div>
	<div class="ckinterfacetab current" data-level="1" id="tab_itemnormalstyles" data-group="main">
		<?php echo $interface->createAll('itemnormalstyles', false); ?>
	</div>
	<div class="ckinterfacetab" data-level="1" id="tab_itemhoverstyles" data-group="main">
		<?php echo Helper::getProMessage() ?>
	</div>
	<div class="ckinterfacetab" data-level="1" id="tab_itemactivestyless" data-group="main">
		<?php echo Helper::getProMessage() ?>
	</div>
	<div class="ckinterfacetab" data-level="1" id="tab_submenustyles" data-group="main">
		<?php echo Helper::getProMessage() ?>
	</div>
</div>
<script>
jscolor.init();
ckInitTabs($ck('#ck-item-edition-item-styles'));
</script>