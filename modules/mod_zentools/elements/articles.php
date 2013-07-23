<?php
/**
 * @package    Zen Tools
 * @subpackage Zen Tools
 * @author     Joomla Bamboo - design@joomlabamboo.com
 * @copyright  Copyright (c) 2013 Copyright (C), Joomlabamboo. All Rights Reserved.. All rights reserved.
 * @license    license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    1.10.2
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
class JElementArticles extends JElement
{
	var   $_name = 'Articles';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = JFactory::getDBO();
		$size = ( $node->attributes('size') ? $node->attributes('size') : 5 );
	  $query = 'SELECT id, title FROM #__content WHERE state=1 ORDER BY title';
		$db->setQuery($query);
		$options = $db->loadObjectList();

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',  'class="inputbox" style="width:90%;" multiple="multiple" size="5"', 'id', 'title', $value, $control_name.$name);
	}
}
