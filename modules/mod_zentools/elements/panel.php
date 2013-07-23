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

class JElementPanel extends JElement
{
	   var   $_name = 'Panel';
	   function fetchElement($name, $value, &$node, $control_name)
	   {
		   	//when our code starts the second td in a tr are open
		   	//we close the second td in tr
			$panel = '</td></tr>';
			//we close the current table and divs
			$panel .= '</tbody></table></div></div>';
			//we open the new table and divs
			//we retrieve the panel id and title attributes and add them to the toggle div
			$panel .= '<div id="'.JText::_($node->attributes('panel')).'Panel" class="panel">
			<h3 class="zentools" id="'.JText::_($node->attributes('panel')).'h3">
			<span>'.JText::_($node->attributes('title')).'</span>
			</h3><div class="zentools '.JText::_($node->attributes('panel')).'">
			<table width="100%" class="paramlist admintable" cellspacing="1"><tbody>';
			//we open and close the first td and open the second td
			$panel .= '<tr><td></td><td>';
			//we allow the normal element function to close the td and tr
			return $panel;
	   }
}
?>
