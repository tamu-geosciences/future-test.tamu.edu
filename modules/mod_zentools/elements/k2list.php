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
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php';

class JElementK2list extends JElementList {

	var	$_name = 'K2list';

	function fetchElement($name, $value, &$node, $control_name){
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($node->attributes('requirement')))
		{
			return JText::_('K2 is not installed');
		}

		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$options = array ();

		foreach ($node->children() as $option)
		{
			// Check k2
			if (!ZenToolsHelper::isK2Installed())
			{
				if (substr_count(strtolower($option->attributes('value')), 'k2') > 0
					|| substr_count(strtolower($option->attributes('text')), 'k2') > 0)
				{
					continue;
				}
			}

			$val	= $option->attributes('value');
			$text	= $option->data();
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
	}


} // end class
