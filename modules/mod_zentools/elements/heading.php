<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Copyright (C), Joomlabamboo. All Rights Reserved.. All rights reserved.
 * @license		license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		1.10.2
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementHeading extends JElement
{
	   var   $_name = 'Heading';
	   function fetchElement($name, $value, &$node, $control_name)
	   {

			   	//when our code starts the second td in a tr are open
			   	//we close the second td in tr

				//we close the current table and divs

				//we open the new table and divs
				//we retrieve the panel id and title attributes and add them to the toggle div
				$panel = '<div class="zenheading">
				<h4 class="zentools">
				<span>'.JText::_($node->attributes('title')).'</span>
				</h4></div>
				';
				//we allow the normal element function to close the td and tr
				return $panel;
		   }
	}
	?>
