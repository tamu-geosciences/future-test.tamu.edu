<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$logotext = $this->params->get('logotext');
$logoclass = $this->params->get('logoclass', 'h2');
$tagline = $this->params->get('tagline');
$logotype = $this->params->get('jblogotype', 'text');
$logoalign= $this->params->get('logoalign', 'zenleft');
$logoimage = $logotype == 'image' ? $this->params->get('jblogoimage', '') : '';

?>
<?php if($logotype !=="none") {?>
<!-- LOGO -->
<script>
function usemap()
{
	var url = window.location.href function;
	if(url.indexOf('news') != -1)
	  return '#newsmap';
	 else if(url.indexOf('students') != -1)
	   return '#studentsmap';
	  else return '#logomap'; 
}
</script>

<section id="logowrap">
	<div class="zen-container">
		<div class="row-fluid">
			<div class="span12">
			  <div class="logo logo-<?php echo $logotype ?> <?php echo $logoalign ?>">
				    <<?php echo $logoclass ?>>
				      <a href="<?php echo JURI::base(true) ?>" title="">
		    		    <span>
		        			<?php if($logotype == "text") { echo $logotext; } else { ?>
		        		<img src="<?php echo $logoimage ?>" usemap="#logomap" />
                        <map name="logomap">
                        	  <area shape="rect" coords="0,0,130,105" href="http://www.tamu.edu/" alt="Texas A&M University" title="Texas A&M University">
							  <area shape="rect" coords="135,0,635,105" href="<?php echo $logoLink ?>" alt="College of Geosciences" title="College of Geosciences">
						</map>
                        <map name="newsmap">
                        	  <area shape="rect" coords="0,0,135,105" href="http://www.tamu.edu/" alt="Texas A&M University" title="Texas A&M University">
							  <area shape="rect" coords="135,0,635,105" href="<?php echo $logoLink ?>" alt="College of Geosciences" title="College of Geosciences">
                              <area shape="rect" coords="780,0,954,105" href="/news" alt="News" title="News">
						</map>
                        <map name="studentsmap">
                        	  <area shape="rect" coords="0,0,135,105" href="http://www.tamu.edu/" alt="Texas A&M University" title="Texas A&M University">
							  <area shape="rect" coords="135,0,635,105" href="<?php echo $logoLink ?>" alt="College of Geosciences" title="College of Geosciences">
                              <area shape="rect" coords="665,0,954,105" href="/students" alt="News" title="News">
						</map>
		        		<?php } ?>
		        		</span>
		      		</a>
		     	 </<?php echo $logoclass ?>>
		      
		     	 <?php if($tagline) {?>
		     		 <div id="tagline"><span><?php echo $tagline ?></span></div>
		     	 <?php } ?>
		   
		  	</div>
		  	
		  </div>
		 <!-- Search module moved into nav.php file-->
	 </div>
</div>
</section>
<!-- //LOGO -->
<?php } ?>