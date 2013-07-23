<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Copyright (C), Joomlabamboo. All Rights Reserved.. All rights reserved.
 * @license		license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		1.10.2
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
class JElementCheckbox extends JElement
{
   var   $_name = 'Checkbox';

   function fetchElement($name, $value, &$node, $control_name)
   {
   			$html = '<input type="checkbox" name="' . $name . '" value="' . $value . '" id="' . $name . '" />';
	      	$html .= '&nbsp;<label for="' . $name . '">' . $name . '</label>';
	$html .='' . $value . '';
			return $html;
	}
}
