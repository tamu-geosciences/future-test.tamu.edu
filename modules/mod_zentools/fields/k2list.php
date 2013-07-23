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

class JFormFieldK2list extends JFormFieldList
{
	protected $type = 'k2list';

	protected function getInput()
	{
		// Is K2 required but not installed?
		if (!ZenToolsHelper::checkK2Requirement($this->element['requirement']))
		{
			return '';
		}

		return parent::getInput();
	}

	protected function getOptions()
	{
		$options = parent::getOptions();

		// Check k2
		if (!ZenToolsHelper::isK2Installed())
		{
			$filtered = array();

			// Remove k2 option
			foreach ($options as $option)
			{
				if (substr_count(strtolower($option->value), 'k2') === 0
					&& substr_count(strtolower($option->text), 'k2') === 0)
				{
					$filtered[] = $option;
}
			}

			$options = $filtered;
		}

		return $options;
	}
}
