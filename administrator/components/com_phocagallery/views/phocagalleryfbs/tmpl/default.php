<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

$task		= 'phocagalleryfb';

$r 			= $this->r;
$app		= Factory::getApplication();
$option 	= $app->input->get('option');
$tasks		= $task . 's';
$OPT		= strtoupper($option);
$user		= Factory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $option);
$saveOrder	= $listOrder == 'a.ordering';
$saveOrderingUrl = '';
if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = $r->saveOrder($this->t, $listDirn);
}
$sortFields = $this->getSortFields();

echo $r->startHeader();
echo $r->jsJorderTable($listOrder);

echo $r->startForm($option, $tasks, 'adminForm');
//echo $r->startFilter();
//echo $r->endFilter();

echo $r->startMainContainer();
/*
echo $r->startFilterBar();
echo $r->inputFilterSearch($OPT.'_FILTER_SEARCH_LABEL', $OPT.'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);

echo $r->startFilterBar(2);
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.published'));
echo $r->endFilterBar();

echo $r->endFilterBar();*/

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->firstColumnHeader($listDirn, $listOrder);
echo $r->secondColumnHeader($listDirn, $listOrder);

echo $r->thOrderingXML('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');

echo '<th class="ph-image">'.Text::_('COM_PHOCAGALLERY_IMAGE').'</th>'."\n";
echo '<th class="ph-name">'.HTMLHelper::_('searchtools.sort',  	$OPT.'_NAME', 'a.name', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-user">'.HTMLHelper::_('searchtools.sort',  	$OPT.'_FB_USER_ID', 'a.uid', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-user">'.HTMLHelper::_('searchtools.sort',  	$OPT.'_FB_APP_ID', 'a.appid', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.HTMLHelper::_('searchtools.sort',  $OPT.'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.HTMLHelper::_('searchtools.sort',  		$OPT.'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
echo $r->startTblBody($saveOrder, $saveOrderingUrl, $listDirn);

$originalOrders = array();
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$orderkey   	= array_search($item->id, $this->ordering[0]);
$ordering		= ($listOrder == 'a.ordering');
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $option) && $canCheckin;
$linkEdit		= Route::_( 'index.php?option=com_phocagallery&task=phocagalleryfb.edit&id='.(int) $item->id );
$canCreate		= $user->authorise('core.create',		'com_phocagallery');

echo $r->startTr($i, isset($item->catid) ? (int)$item->catid : 0);
echo $r->firstColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->secondColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $item->ordering);

if (isset($item->uid) && $item->uid!= '') {
	echo '<td><img src="https://graph.facebook.com/'. $item->uid .'/picture"></td>';
} else {
	echo '<td></td>';
}

$checkO = '';
if ($item->checked_out) {
	$checkO .= HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $tasks.'.', $canCheckin);
}

if ($item->name == ''){
	$item->name = Text::_('COM_PHOCAGALLERY_NOT_SET_YET');
}
if ($canCreate || $canEdit) {
	$name = '<a href="'. Route::_($linkEdit).'">'. $this->escape($item->name).'</a>';
} else {
	$name = $this->escape($item->name);
}
echo $r->td($checkO . $name, "small");

if ($item->uid == ''){
	$item->uid = Text::_('COM_PHOCAGALLERY_NOT_SET_YET');
}
if ($canCreate || $canEdit) {
	$uid = '<a href="'. Route::_($linkEdit).'">'. $this->escape($item->uid).'</a>';
} else {
	$uid = $this->escape($item->uid);
}
echo $r->td($uid, "small");

if ($canCreate || $canEdit) {
	$appid = '<a href="'. Route::_($linkEdit).'">'. $this->escape($item->appid).'</a>';
} else {
	$appid = $this->escape($item->appid);
}

echo $r->td($appid, "small");

echo $r->td(HTMLHelper::_('jgrid.published', $item->published, $i, $tasks.'.', $canChange), "small");
echo $r->td($item->id, "small");

echo $r->endTr();

		//}
	}
}
echo $r->endTblBody();

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();


echo $r->formInputsXML($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>
