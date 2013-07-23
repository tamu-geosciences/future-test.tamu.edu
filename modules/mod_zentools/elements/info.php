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

class JElementInfo extends JElement {
	var	$_name = 'info';
	function fetchElement($name, $value, &$node, $control_name){
		// Output
		return '
		<div style="font-size:12px;line-height:18px;color:#333;padding:10px;margin:10px 0;background: #FAF2B6">
			'.JText::_($value).'
		</div>
		';
	}
	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
