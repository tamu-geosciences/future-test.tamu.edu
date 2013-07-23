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

require_once JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php';

// Create a category selector
class JFormFieldK2categories extends JFormField
{

	var	$type = 'k2categories';

	function getInput()
	{
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($this->element['requirement']))
		{
			return JText::_('K2 is not installed');
		}
		else
		{
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

			$multiple = (string)$this->element['multiple'] === 'true' ? 'multiple="multiple" size="5"' : '';

			return JHTML::_('select.genericlist',  $categories, ''.$this->formControl.'[params]['.$this->fieldname.'][]',
				'class="inputbox" style="width:90%;" ' . $multiple, 'id', 'title', $this->value, $this->id );
		}
	}
}

