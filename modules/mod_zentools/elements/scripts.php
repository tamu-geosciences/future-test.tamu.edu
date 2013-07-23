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

class JElementScripts extends JElement {

	var	$_name = 'scripts';

	function fetchElement( $name, $value, &$node, $control_name )
	{

		ob_start();
		JHTML::_('behavior.modal');

		$document = JFactory::getDocument();
		$document->addScript(''.JURI::root(true).'/media/mod_zentools/js/admin/jquery-1.8.1.min.js');
		$document->addScript(''.JURI::root(true).'/media/mod_zentools/js/admin/jquery.noconflict.js');
		$document->addScript(''.JURI::root(true).'/media/mod_zentools/js/admin/jquery-ui-1.8.23.custom.min.js');
		$document->addScript(''.JURI::root(true).'/media/mod_zentools/js/admin/scripts15.js');
		$document->addScript(''.JURI::root(true).'/media/mod_zentools/js/admin/sortables15.js');

		// Check to see if k2 is installed.
		$k2 = ZenToolsHelper::isK2Installed();
		?>
		<script type="text/javascript">

			// Hide / Show relevant panels on page load
			jQuery(document).ready(function() {
				<?php if($k2) { ?>
					jQuery("#paramscontentSource3").show();
					jQuery("#paramscontentSource3").next().show();
				<?php }
				else { ?>
					jQuery("#paramscontentSource3").hide();
					jQuery("#paramscontentSource3").next().hide();
				<?php } ?>
			});


		</script>
		<div id="zentools">
		<?php

		return ob_get_clean();
	}

	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}
}
