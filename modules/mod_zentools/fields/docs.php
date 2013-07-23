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

class JFormFieldDocs extends JFormField
{
	protected	$type = 'docs';

	protected function getInput()
	{
		ob_start();
		?>

		<?php	return ob_get_clean();

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',
			'class="inputbox" style="width:90%;" multiple="multiple" size="5"', 'id', 'title', $value, $control_name.$name
		);
	}
}
