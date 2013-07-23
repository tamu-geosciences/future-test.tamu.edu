<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if($this->params->get('bodyFont') == "-1" || $this->params->get('navFont') == "-1" || $this->params->get('headingFont') == "-1" || $this->params->get('logoFont') == "-1" ) {
	
	// Font array
	$myfonts = array();
	
// Create variables otherwise strange things happen
$bodyFont = str_replace(" ", "+", $this->params->get('bodyFont_custom'));
$headingFont = str_replace(" ", "+", $this->params->get('headingFont_custom'));
$navFont = str_replace(" ", "+", $this->params->get('navFont_custom'));
$logoFont = str_replace(" ", "+", $this->params->get('logoFont_custom'));
$customFont = str_replace(" ", "+", $this->params->get('customFont_custom'));

// Check to see if the font should be added to the array
if($this->params->get('bodyFont') == "-1") $myfonts[] = "'$bodyFont'";
if($this->params->get('headingFont') == "-1") $myfonts[] = "'$headingFont'";
if($this->params->get('navFont') == "-1") $myfonts[] = "'$navFont'";
if($this->params->get('logoFont') == "-1") $myfonts[] = "'$logoFont'";
if($this->params->get('customFont') == "-1") $myfonts[] = "'$logoFont'";
	
	// Remove Duplicates
	$myfonts = array_unique($myfonts);
	
	// Remove comma from last font
	$lastfont = end($myfonts);
	
?>
<script type="text/javascript">
      WebFontConfig = {
      
      google: {
          families: [ 
          	<?php foreach ($myfonts as $font) {echo $font; if (!($font == $lastfont)){echo ', ';}} ?>
          ]}
        
      
      };
      (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
      })();
</script>
<?php } ?>
