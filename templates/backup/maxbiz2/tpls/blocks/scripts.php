<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if($this->params->get('lazyload')) {?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("<?php echo $this->params->get('llselector') ?>").not("<?php echo $this->params->get('notllselector') ?>").lazyload({
			effect : "fadeIn"
		});
});
</script>
<?php } 

if($this->params->get('backtotop')) {?>

<div id="toTop" class="hidden-phone"><a id="toTopLink"><span class="icon-arrow-up"></span><span id="toTopText"> Back to top</span></a></div>
<script type="text/javascript">
	jQuery(document).ready(function(){
			
			jQuery(window).scroll(function () {
			
				if (jQuery(this).scrollTop() >200) {
				 	jQuery("#toTop").fadeIn();
				}
				else {
				 	jQuery("#toTop").fadeOut();
				}
			});
		
			jQuery("#toTop").click(function() {
				jQuery("html, body").animate({ scrollTop: 0 }, "slow");
				 return false;
			});
	});
</script>
<?php } ?>