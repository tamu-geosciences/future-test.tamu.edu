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

class JFormFieldButton extends JFormField
{
	protected $type = 'Button';

	protected function getInput()
	{
		$buttonclass = (string) $this->element['buttonclass'];
		$value       = (string) $this->element['value'];
		$id          = (string) $this->element['id'];

		// Output
		return '
		<div id="'.$id.'Wrap">
			<div id="'.$id.'">
				<a href="#" class="btn ' . $buttonclass . '"><span>' . $value . '</span></a>
			</div>
		</div>
		';
	}

	protected function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		// Output
		return '&nbsp;';
	}
}
