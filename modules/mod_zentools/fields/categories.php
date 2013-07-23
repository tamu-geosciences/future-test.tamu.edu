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

// Create a category selector
class JFormFieldCategories extends JFormField
{

	protected $type = 'categories';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, title FROM #__categories WHERE published=1 and extension=com_content';
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$categories=array();

		// Create the 'all categories' listing
		$categories[0] = new stdClass;
		$categories[0]->id = '';
		$categories[0]->title = JText::_("Select all categories");
		foreach ($results as $result) {
			array_push($categories,$result);
		}

		return JHTML::_('select.genericlist',  $categories, ''.$this->formControl.'[params]['.$this->fieldname.'][]',
			'class="inputbox" style="width:90%;"  multiple="multiple" size="5"', 'id', 'title', $this->value, $this->id
		);
	}

}
