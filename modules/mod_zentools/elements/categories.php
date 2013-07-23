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
// Create a category selector
class JElementCategories extends JElement {
	var	$_name = 'categories';
	function fetchElement($name, $value, &$node, $control_name){
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__sections WHERE published=1';
		$db->setQuery( $query );
		$sections = $db->loadObjectList();
		$categories=array();
		// Create the 'all categories' listing
		$categories[0] = new stdClass;
		$categories[0]->id = '';
		$categories[0]->title = JText::_("Select all categories");
		// Create category listings, grouped by section
		foreach ($sections as $section) {
			$optgroup = JHTML::_('select.optgroup',$section->title,'id','title');
			$query = 'SELECT id,title FROM #__categories WHERE published=1 AND section='.$section->id;
			$db->setQuery( $query );
			$results = $db->loadObjectList();
			array_push($categories,$optgroup);
			foreach ($results as $result) {
				array_push($categories,$result);
			}
		}
		// Create the 'Uncategorised' listing
		$optgroup = JHTML::_('select.optgroup',JText::_("Uncategorised"),'id','title');
		array_push($categories,$optgroup);
		$uncategorised=array();
		$uncategorised['id'] = '0';
		$uncategorised['title'] = JText::_("Uncategorised");
		array_push($categories,$uncategorised);
		// Output
		return JHTML::_('select.genericlist',  $categories, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;"  multiple="multiple" size="5"', 'id', 'title', $value );
	}
} // end class
