<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.10.5
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Mod_zentools
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldFoldsers extends JFormField
{
	protected $type = 'Folders';

	protected function getInput()
	{
		jimport('joomla.filesystem.folder');
		$filter= '.';
		$exclude = array('.svn', 'CVS','.DS_Store','__MACOSX');
		$path = JPATH_ROOT . '/images';
		//get list of image dirs
		$folders = JFolder::folders($path, $filter, true, true, $exclude);
		//if were on windows or local server we replace the DS
		$path = str_replace('\\', '/', $path);
		//find start of local url
		$pos = strpos($path, '/images');
		//remove root path
		$local_image = substr_replace($path, '', 0, $pos);
		$id = '/images';
		$title = '/images'.'/';
		$options =array();
		$options[] = JHTML::_('select.option', $id, $title, 'id', 'title');

		foreach ($folders as $folder)
		{
			//if were on windows or local server we replace the DS
			$folder = str_replace('\\', '/', $folder);
			//find start of local url
			$pos = strpos($folder, '/images');
			//remove root path
			$local_image = substr_replace($folder, '', 0, $pos);
			$id = $local_image;
			$title = $local_image.'/';
			$options[] = JHTML::_('select.option', $id, $title, 'id', 'title');
		}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox"', 'id', 'title', $value, $control_name.$name);
	}
}
