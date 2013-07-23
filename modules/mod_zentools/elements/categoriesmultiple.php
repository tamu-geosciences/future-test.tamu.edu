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

class JElementCategoriesmultiple extends JElement
{
	var	$_name = 'categoriesmultiple';

	function fetchElement($name, $value, &$node, $control_name)
	{
		if (ZenToolsHelper::isK2Installed())
		{
			$db = JFactory::getDBO();
			$query = 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
			$db->setQuery( $query );
			$mitems = $db->loadObjectList();
			$children = array();
			if ($mitems){
				foreach ( $mitems as $v ){
					$pt 	= $v->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push( $list, $v );
					$children[$pt] = $list;
				}
			}
			$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
			$mitems = array();
			foreach ( $list as $item ) {
				$mitems[] = JHTML::_('select.option',  $item->id, '&nbsp;&nbsp;&nbsp;'.$item->treename );
			}

			$doc = JFactory::getDocument();
			$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]',
				'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );

			return $output;
		}
	}
}
