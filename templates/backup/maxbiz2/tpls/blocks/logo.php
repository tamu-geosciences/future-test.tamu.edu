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
<section id="logowrap">
	<div class="zen-container">
		<div class="row-fluid">
			<div class="span9">
			  <div class="logo logo-<?php echo $logotype ?> <?php echo $logoalign ?>">
				    <<?php echo $logoclass ?>>
				      <a href="<?php echo JURI::base(true) ?>" title="">
		    		    <span>
		        			<?php if($logotype == "text") { echo $logotext; } else { ?>
		        		<img src="<?php echo $logoimage ?>"/>
		        		<?php } ?>
		        		</span>
		      		</a>
		     	 </<?php echo $logoclass ?>>
		      
		     	 <?php if($tagline) {?>
		     		 <div id="tagline"><span><?php echo $tagline ?></span></div>
		     	 <?php } ?>
		   
		  	</div>
		  	
		  </div>
		  <?php if ($this->countModules('search')) { ?>
		  <div class="span3">
		  	<div id="search">
		  		<jdoc:include type="modules" name="search" style="zendefault" />
		  </div></div>
		  <?php } ?>
	 </div>
</div>
</section>
<!-- //LOGO -->
<?php } ?>