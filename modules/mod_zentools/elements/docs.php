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

class JElementdocs extends JElement
{
   var   $_name = 'docs';

   function fetchElement($name, $value, &$node, $control_name)
   {
      	ob_start();	?>





		<?php	return ob_get_clean();




      return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',  'class="inputbox" style="width:90%;" multiple="multiple" size="5"', 'id', 'title', $value, $control_name.$name);
   }
}
