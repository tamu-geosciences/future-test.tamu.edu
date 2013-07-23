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

require_once JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php';
//require_once JPATH_SITE . '/modules/mod_zentools/fields/scripts.php';

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Mod_zentools
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldScripts extends JFormField
{
	protected $type = 'Scripts';

	protected function getInput()
	{

		$document = JFactory::getDocument();
		$root = JURI::root();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/jquery-1.8.1.min.js');
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/jquery-ui-1.8.23.custom.min.js');
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/jquery.noconflict.js');
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/scripts25.js');
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/sortables25.js');
		}
		else
		{
			$document = JFactory::getDocument();
			$document->addScript(''.$root.'/media/mod_zentools/js/admin/jquery-ui-1.8.23.custom.min.js');

			// Check if the NoNumber Advanced Modules pluign are enabled
			if (JPluginHelper::isEnabled('system', 'advancedmodules') && JRequest::getVar('option') === 'com_advancedmodules')
			{
				$document->addScript(''.$root.'/media/mod_zentools/js/admin/scripts30-advancedmodule.js');
				$document->addScript(''.$root.'/media/mod_zentools/js/admin/sortables30-advancedmodule.js');
			}
			else
			{
				$document->addScript(''.$root.'/media/mod_zentools/js/admin/scripts30.js');
				$document->addScript(''.$root.'/media/mod_zentools/js/admin/sortables30.js');
			}
		}

		$k2 = ZenToolsHelper::isK2Installed();

		ob_start();
		?>
		<script type="text/javascript">
			jQuery(function() {
				// Hide / Show relevant panels on page load
				<?php if($k2) : ?>
					jQuery("#jform_params_contentSource3").show();
					jQuery("#jform_params_contentSource3").next().show();
				<?php else : ?>
					jQuery("#jform_params_contentSource3").hide();
					jQuery("#jform_params_contentSource3").next().hide();
				<?php endif; ?>
			});
		</script>
		<?php
		return ob_get_clean();
	}
}
