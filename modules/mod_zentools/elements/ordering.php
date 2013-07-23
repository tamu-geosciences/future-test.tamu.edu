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

class JElementOrdering extends JElement {
	var	$_name = 'ordering';
	function fetchElement( $name, $value, &$node, $control_name )
	{
	    $class = $node->attributes( 'class' ) ? $node->attributes( 'class' ) : "text_area";

	    $return = '<input type="text"' .
	                     'name="' . $control_name . '[' . $name . ']"' .
	                     'id="itemOrder"' .
	                     'value=""' .
	                     'class="' . $class . '" style="" />';

	    return $return;
	}

	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
