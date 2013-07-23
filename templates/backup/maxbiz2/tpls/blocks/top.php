<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>

<!-- Top -->
<section id="topwrap">
	<div class="zen-container<?php if ($this->countModules('panel1 or panel2 or panel3 or panel4')) : ?> panel-padding<?php endif; ?>">
	  <?php 
	  	$this->spotlight ('top', 'top1, top2, top3, top4')
	  ?>
	  
	  <?php if ($this->checkSpotlight('panel', 'panel1, panel2, panel3, panel4')) : ?>
	  		
	  		
	  	<div id="paneltrigger">
	  		<a href="#panel" role="button" data-toggle="modal">
	  			<span class="icon-plus"></span>
	  			<span><?php echo $this->params->get('openpaneltext', 'More'); ?></span>
	  		</a>
	  	</div>
	  	<?php endif;?>
  	</div>
</section>