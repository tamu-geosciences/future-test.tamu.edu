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
defined( '_JEXEC' ) or die( 'Restricted access' );

// Create a category selector
class JElementSectionsMultiple extends JElement
{
	protected $_name = 'sections';

	public function fetchElement($name, $value, &$node, $control_name)
	{
		$db    = JFactory::getDBO();
		$query = 'SELECT * FROM #__sections WHERE published=1';

		$db->setQuery( $query );
		$results = $db->loadObjectList();

		$sections=array();

		// Create the 'all categories' listing
		$sections[0]->id = '';
		$sections[0]->title = JText::_("Select all sections");

		// Create category listings, grouped by section
		foreach ($results as $result)
		{
			array_push($sections,$result);
		}

		// Output
		return JHTML::_('select.genericlist',  $sections, ''.$control_name.'['.$name.'][]',
			'class="inputbox" style="width:90%;"  multiple="multiple" size="10"', 'id', 'title', $value
		);
	}
}
