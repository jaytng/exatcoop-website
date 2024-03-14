<?php
// No direct access
defined('_JEXEC') or die;

use Maximenuck\CKView;
use Maximenuck\CKFof;
use Maximenuck\CKText;
use Maximenuck\Helper;

class MaximenuckViewJoomlamenu extends CKView
{

	protected $modules;

	public function display($tpl = null)
	{
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_maximenuck') || (count($user->getAuthorisedCategories('com_maximenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$lang 		= \Joomla\CMS\Factory::getLanguage();
		$this->items	= $this->get('Items');
		
		$className = 'MaximenuckHelpersourceModule';

		require_once(MAXIMENUCK_PATH . '/helpers/source/module.php');
		$this->modules = $className::getItems();
		// require_once(JPATH_ADMINISTRATOR . '/components/com_maximenuck/models/moduleselect.php');
		// $modules = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Moduleselect', 'MaximenuckModel');
		// $this->modules = $this->orderModules($modules->getItems());

		// Check for errors.
		// if (! empty($errors = $this->get('Errors')))
		// {
			// JError::raiseError(500, implode("\n", $errors));
			// return false;
		// }
/*
		$this->ordering = array();

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as $item)
		{
			$this->ordering[$item->parent_id][] = $item->id;

			// item type text
			switch ($item->type)
			{
				case 'url':
					$value = \Joomla\CMS\Language\Text::_('COM_MENUS_TYPE_EXTERNAL_URL');
					break;

				case 'alias':
					$value = \Joomla\CMS\Language\Text::_('COM_MENUS_TYPE_ALIAS');
					break;

				case 'separator':
					$value = \Joomla\CMS\Language\Text::_('COM_MENUS_TYPE_SEPARATOR');
					break;

				case 'heading':
					$value = \Joomla\CMS\Language\Text::_('COM_MENUS_TYPE_HEADING');
					break;

				case 'component':
				default:
					// load language
						$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR, null, false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR.'/components/'.$item->componentname, null, false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
					||	$lang->load($item->componentname.'.sys', JPATH_ADMINISTRATOR.'/components/'.$item->componentname, $lang->getDefault(), false, false);

					if (!empty($item->componentname))
					{
						$value	= \Joomla\CMS\Language\Text::_($item->componentname);
						$vars	= null;

						parse_str($item->link, $vars);
						if (isset($vars['view']))
						{
							// Attempt to load the view xml file.
							$file = JPATH_SITE.'/components/'.$item->componentname.'/views/'.$vars['view'].'/metadata.xml';
							if (is_file($file) && $xml = simplexml_load_file($file))
							{
								// Look for the first view node off of the root node.
								if ($view = $xml->xpath('view[1]'))
								{
									if (!empty($view[0]['title']))
									{
										$vars['layout'] = isset($vars['layout']) ? $vars['layout'] : 'default';

										// Attempt to load the layout xml file.
										// If Alternative Menu Item, get template folder for layout file
										if (strpos($vars['layout'], ':') > 0)
										{
											// Use template folder for layout file
											$temp = explode(':', $vars['layout']);
											$file = JPATH_SITE.'/templates/'.$temp[0].'/html/'.$item->componentname.'/'.$vars['view'].'/'.$temp[1].'.xml';
											// Load template language file
											$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE, null, false, false)
											||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE.'/templates/'.$temp[0], null, false, false)
											||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE, $lang->getDefault(), false, false)
											||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE.'/templates/'.$temp[0], $lang->getDefault(), false, false);

										}
										else
										{
											// Get XML file from component folder for standard layouts
											$file = JPATH_SITE.'/components/'.$item->componentname.'/views/'.$vars['view'].'/tmpl/'.$vars['layout'].'.xml';
										}
										if (is_file($file) && $xml = simplexml_load_file($file))
										{
											// Look for the first view node off of the root node.
											if ($layout = $xml->xpath('layout[1]'))
											{
												if (!empty($layout[0]['title']))
												{
													$value .= ' » ' . \Joomla\CMS\Language\Text::_(trim((string) $layout[0]['title']));
												}
											}
											if (!empty($layout[0]->message[0]))
											{
												$item->item_type_desc = \Joomla\CMS\Language\Text::_(trim((string) $layout[0]->message[0]));
											}
										}
									}
								}
								unset($xml);
							}
							else {
								// Special case for absent views
								$value .= ' » ' . \Joomla\CMS\Language\Text::_($item->componentname.'_'.$vars['view'].'_VIEW_DEFAULT_TITLE');
							}
						}
					}
					else {
						if (preg_match("/^index.php\?option=([a-zA-Z\-0-9_]*)/", $item->link, $result))
						{
							$value = \Joomla\CMS\Language\Text::sprintf('COM_MENUS_TYPE_UNEXISTING', $result[1]);
						}
						else {
							$value = \Joomla\CMS\Language\Text::_('COM_MENUS_TYPE_UNKNOWN');
						}
					}
					break;
			}
			$item->item_type = $value;
		}

		// Levels filter.
		$options	= array();
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '1', \Joomla\CMS\Language\Text::_('J1'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '2', \Joomla\CMS\Language\Text::_('J2'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '3', \Joomla\CMS\Language\Text::_('J3'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '4', \Joomla\CMS\Language\Text::_('J4'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '5', \Joomla\CMS\Language\Text::_('J5'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '6', \Joomla\CMS\Language\Text::_('J6'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '7', \Joomla\CMS\Language\Text::_('J7'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '8', \Joomla\CMS\Language\Text::_('J8'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '9', \Joomla\CMS\Language\Text::_('J9'));
		$options[]	= \Joomla\CMS\HTML\HTMLHelper::_('select.option', '10', \Joomla\CMS\Language\Text::_('J10'));

		$this->f_levels = $options;
*/
		parent::display($tpl);
		exit();
	}
	
	private function orderModules($modules) {
		$newmodules = Array();
		foreach ($modules as $i => $module) {
			$newmodules[$module->id] = $module;
		}
		return $newmodules;
	}
}
