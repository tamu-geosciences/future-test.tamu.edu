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


/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Mod_zentools
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldOrdering extends JFormField
{
	protected   $type = 'Ordering';

	protected function getInput()
	{
		//$class = $node->attributes( 'class' ) ? $node->attributes( 'class' ) : "text_area";

		$name         = (string) $this->element['name'];
		$control_name = (string) $this->element['name'];
		$class        =  (string) $this->element['name'];

		$return = '<input type="text"' .
						 'name="' . $control_name . '[' . $name . ']"' .
						 'id="jform_params_itemOrder"' .
						 'value="'.$this->value.'"' .
						 'class="' . $class . '" style="" />';

		return $return;
	}

	public function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		// Output
		return '&nbsp;';
	}
}
