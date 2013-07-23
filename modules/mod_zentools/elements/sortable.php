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

/**
 * @package RocketTheme
 * @subpackage roktabs.elements
 */
class JElementSortable extends JElement {

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $mainframe;
		$display		= $node->attributes( 'display' );

		// Global Document
		$document 	= JFactory::getDocument();

		// Params
		$document->addStyleSheet( ''.JURI::root(true).'/media/mod_zentools/css/admin/admin.css' );

		ob_start();	?>
		<div id="help"></div>


			<div id="zenmessage"><p></p></div>
			<div id="items">

				<ul id="sortable" class="ui-sortable">
					<li class="disabled">Drag items here to use</li>

				</ul>
				<ul id="sortable2" class="ui-sortable">
					<li class="disabled">Available Items</li>
				</ul>
			</div>



	<?php	return ob_get_clean();

	}
}
