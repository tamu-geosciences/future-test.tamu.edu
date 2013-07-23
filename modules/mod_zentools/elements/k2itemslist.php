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

require_once JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php';

class JElementK2itemslist extends JElement
{

	var	$_name = 'K2itemslist';

	function fetchElement($name, $value, &$node, $control_name)
	{
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($node->attributes('requirement')))
		{
			return '';
		}

		$db = JFactory::getDBO();
		$size = ( $node->attributes('size') ? $node->attributes('size') : 5 );
		$query = 'SELECT id, title FROM #__k2_items WHERE published = 1
						AND trash = 0
						AND unix_timestamp(publish_up) <= '.time().' AND (unix_timestamp(publish_down) >= '.time().' OR unix_timestamp(publish_down)=0) ORDER BY title';

		$db->setQuery($query);
		$options = $db->loadObjectList();
		$k2items = array();

		// Create the 'all items' listing
		$k2items[] = JHTML::_('select.option', '', JText::_("Select all Items"));

		foreach ($options as $result)
		{
			$k2items[] = JHTML::_('select.option', $result->id, JText::_($result->title));
		}

		return JHTML::_('select.genericlist',  $k2items, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;"  multiple="multiple" size="5"', 'value', 'text', $value, $control_name.$name);
	}
}
