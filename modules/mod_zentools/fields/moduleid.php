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

class JElementModuleid extends JElement {
	protected $_name = 'moduleid';

	public function fetchElement( $name, $value, &$node, $control_name )
	{
		$class = $node->attributes( 'class' ) ? $node->attributes( 'class' ) : "text_area";

		/*if ( isset( $_COOKIE['listOrder'] ) ) {
			$value = $_COOKIE["listOrder"];
		}
		else
		{
			$value ="";
		}*/

		$return = '<input type="text"' .
			'name="' . $control_name . '[' . $name . ']"' .
			'id="moduleid"' .
			'value=""' .
			'class="' . $class . '"/>';

		return $return;
	}

	public function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		// Output
		return '&nbsp;';
	}
}
