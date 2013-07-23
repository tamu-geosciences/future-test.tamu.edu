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

class JFormFieldHeading extends JFormField
{
	protected $type = 'Heading';

	protected function getInput()
	{
		$panelname = (string) $this->element['panel'];
		$title     = (string) $this->element['title'];
		$class     = (string) $this->element['class'];
		//when our code starts the second td in a tr are open
		//we close the second td in tr

		//we close the current table and divs

		//we open the new table and divs
		//we retrieve the panel id and title attributes and add them to the toggle div
		$panel = '<div class="zenheading '.$class.'">
		<h4 class="zentools">
		<span>'.$title.'</span>
		</h4></div>
		';

		//we allow the normal element function to close the td and tr
		return $panel;
	}
}
