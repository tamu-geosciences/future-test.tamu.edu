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

// Create a category selector
class JElementK2categories extends JElement {

	var	$_name = 'K2categories';

	function fetchElement($name, $value, &$node, $control_name){
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($node->attributes('requirement')))
		{
			return JText::_('K2 is not installed');
		}

		$db = JFactory::getDBO();
		$query = 'SELECT id,name FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$results = $db->loadObjectList();
		$categories = array();

		// Create the 'all categories' listing
		$categories[0] = new stdClass;
		$categories[0]->id = '';
		$categories[0]->title = JText::_("Select all Categories");

		foreach ($results as $result)
		{
			$result->title = $result->name;
			array_push($categories,$result);
		}

		$multiple = (string)$node->attributes['multiple'] === 'true' ? 'multiple="multiple" size="5"' : '';

		// Output
		return JHTML::_('select.genericlist',  $categories, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" ' . $multiple, 'id', 'title', $value );
	}
} // end class
