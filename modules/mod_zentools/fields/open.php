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

class JFormFieldOpen extends JFormField
{
	protected $type = 'Open';

	protected function getInput()
	{
		$panelname		= (string) $this->element['panel'];
		$title			= (string) $this->element['title'];

		//when our code starts the second td in a tr are open
		//we close the second td in tr
		$panel = '<div class="'.$title.'">';

		return $panel;
	}
}
