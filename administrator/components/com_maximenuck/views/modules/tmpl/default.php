<?php
// no direct access
defined('_JEXEC') or die;

use Maximenuck\CKFof;
use Maximenuck\Helper;
use Maximenuck\CKFramework;

CKFramework::load();
CKFramework::loadFaIconsInline();
Helper::loadCkbox();

// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
CKFof::addStyleSheet(MAXIMENUCK_MEDIA_URI . '/assets/adminlist.css');
?>
<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_maximenuck'); ?>" method="post" name="adminForm" id="adminForm">
	<?php Helper::addSidebar(); ?>
	<div class="ckadminarea">
		<div id="filter-bar" class="btn-toolbar input-group">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->state->get('filter_search'); ?>" class="form-control" />
			</div>
			<div class="input-group-append btn-group pull-left hidden-phone">
				<button type="submit" class="btn btn btn-primary hasTooltip" title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn btn-secondary hasTooltip" title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="this.form.filter_search.value = '';
						this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone ordering-select">
				<label for="limit" class="element-invisible"><?php echo \Joomla\CMS\Language\Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>
	
		<table class="table table-striped" id="itemsList">
			<thead>
				<tr>
					<th class='left'>
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo 'style' ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo 'source' ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CK_POSITION', 'position', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$link = 'index.php?option=com_modules&&task=module.edit&id=' . $item->id;
					$params = new \Joomla\Registry\Registry($item->params);
					$source = $params->get('source', '');
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<a href="<?php echo \Joomla\CMS\Uri\Uri::root(true) . '/administrator/' . $link ?>"><?php echo $item->title; ?></a>
							<?php echo (Helper::isV9($params->get('isv9', 0, 'int'), $item->id) == '1' ? '' : '<span class="cklabel cklabel-warning">' . \Joomla\CMS\Language\Text::_('CK_V8_VERSION_ALERT') . '</span>'); ?>
						</td>
						<td width="1%" class="nowrap center">
							<?php echo $params->get('style') ?>
						</td>
						<td width="1%" class="nowrap center">
							<?php echo $source ?>
						</td>
						
						<td class="center">
							<?php
							$buttonstate = $item->published == '1' ? '' : 'un';
							?>
							<span class="icon-<?php echo $buttonstate ?>publish"></span>
						</td>
						<td class="small">
								<?php if ($item->position) : ?>
								<span class="label label-info">
								<?php echo $item->position; ?>
								</span>
								<?php else : ?>
								<span class="label">
								<?php echo \Joomla\CMS\Language\Text::_('JNONE'); ?>
								</span>
								<?php endif; ?>
						</td>
						<td class="center">
						<?php echo (int) $item->id; ?>
						</td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->getListFooter() ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<input type="hidden" name="state_request" value="1" />
			<?php CKFof::renderToken() ?>
		</div>
	</div>
</form>