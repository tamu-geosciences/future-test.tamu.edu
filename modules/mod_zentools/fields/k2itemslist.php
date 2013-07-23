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

class JFormFieldK2itemslist extends JFormField
{
	protected $type = 'k2itemslist';

	protected function getInput()
	{
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($this->element['requirement']))
		{
			return JText::_('K2 is not installed');
		}
		else
		{
			$db = JFactory::getDBO();
			$size = ( $this->element['size'] ? $this->element['size'] : 5 );
			$query = 'SELECT id, title FROM #__k2_items WHERE published = 1
							AND trash = 0
							AND unix_timestamp(publish_up) <= '.time().' AND (unix_timestamp(publish_down) >= '.time().' OR unix_timestamp(publish_down)=0) ORDER BY title';

			$db->setQuery($query);
			$options = $db->loadObjectList();
			$k2items = array();

			// Create the 'all categories' listing
			$k2items[0] = new stdClass;
			$k2items[0]->id = '';
			$k2items[0]->title = JText::_("Select all Items");

			foreach ($options as $result)
			{
				array_push($k2items,$result);

			}

			return JHTML::_('select.genericlist',  $k2items, ''.$this->formControl.'[params]['.$this->fieldname.'][]',  'class="inputbox" style="width:90%;" multiple="multiple" size="5"', 'id', 'title', $this->value, $this->id);
		}
	}
}
