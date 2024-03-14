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
	<div class="ckcol_left">
	<div class="ckinterfacetablink current" data-level="2" data-tab="tab_themeshorizontal" data-group="themes"><?php echo \Joomla\CMS\Language\Text::_('CK_HORIZONTAL'); ?></div>
	<div class="ckinterfacetablink" data-level="2" data-tab="tab_themesvertical" data-group="themes"><?php echo \Joomla\CMS\Language\Text::_('CK_VERTICAL'); ?></div>
	<div class="clr"></div>
	</div>
	<div class="ckcol_right">
	<div class="ckinterfacetab current" id="tab_themeshorizontal" data-level="2" data-group="themes">
		<div class="clearfix" style="min-height:35px;margin: 0 5px;">
			<?php
			$url = MAXIMENUCK_MEDIA_URI . '/presets/horizontal/';
			$folder_path = MAXIMENUCK_MEDIA_PATH . '/presets/horizontal/';
			$folders = \Joomla\CMS\Filesystem\Folder::folders($folder_path);
			natsort($folders);
			$i = 1;
			foreach ($folders as $folder) {
				$theme_title = "";
				if ( file_exists($folder_path . $folder. '/styles.json') ) {
					if ( file_exists($folder_path . '/' . $folder. '/preview.png') ) {
						$theme = $url . $folder . '/preview.png';
					} else {
						$theme = MAXIMENUCK_MEDIA_URI . '/images/what.png" width="110" height="110';
					}
				} else {
					continue;
				}

				echo '<div class="themethumb" data-name="' . $folder . '" onclick="ckLoadPreset(\'' . $folder . '\', \'horizontal\')">'
					. '<div class="themethumbimg">'
					. '<img src="' . $theme . '" style="margin:0;padding:0;" title="' . $theme_title . '" class="cktip" />'
					. '</div>'
					. '<div class="themename">' . $folder . '</div>'
					. '</div>';
				$i++;
			}
			?>
		</div>
	</div>
	<div class="ckinterfacetab" id="tab_themesvertical" data-level="2" data-group="themes">
		<div class="clearfix" style="min-height:35px;margin: 0 5px;">
			<?php
			$url = MAXIMENUCK_MEDIA_URI . '/presets/vertical/';
			$folder_path = MAXIMENUCK_MEDIA_PATH . '/presets/vertical/';
			$folders = \Joomla\CMS\Filesystem\Folder::folders($folder_path);
			natsort($folders);
			$i = 1;
			foreach ($folders as $folder) {
				$theme_title = "";
				if ( file_exists($folder_path . $folder. '/styles.json') ) {
					if ( file_exists($folder_path . '/' . $folder. '/preview.png') ) {
						$theme = $url . $folder . '/preview.png';
					} else {
						$theme = MAXIMENUCK_MEDIA_URI . '/images/what.png" width="110" height="110';
					}
				} else {
					continue;
				}

				echo '<div class="themethumb" data-name="' . $folder . '" onclick="ckLoadPreset(\'' . $folder . '\', \'vertical\')">'
					. '<div class="themethumbimg">'
					. '<img src="' . $theme . '" style="margin:0;padding:0;" title="' . $theme_title . '" class="cktip" />'
					. '</div>'
					. '<div class="themename">' . $folder . '</div>'
					. '</div>';
				$i++;
			}
			?>
		</div>
	</div>
	</div>
	<div class="ckclr"></div>
