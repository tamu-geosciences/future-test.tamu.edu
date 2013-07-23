<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$menualign = $this->params->get('menualign', 'zenleft');

?>
<!-- MAIN NAVIGATION -->
<?php if($this->params->get('stickynav')) { ?>
  <nav id="navwrap" class="affix-top" data-spy="affix" data-offset-top="200">
<?php } else { ?>
  <nav id="navwrap">
<?php } ?>
  <div class="zen-container">
  	<div class="row-fluid">
   		<div class="navwrapper navbar <?php echo $menualign ?>">
	
		<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <span class="icon-list-ul"></span>
	      </button>
	
	    <div class="nav-collapse collapse<?php echo $this->getParam('navigation_collapse_showsub', 1) ? ' always-show' : '' ?> <?php echo $menualign; ?>">
	    <?php if ($this->getParam('navigation_type') == 'megamenu') : ?>
	       <?php $this->megamenu($this->getParam('mm_type', 'mainmenu')) ?>
	    <?php else : ?>
	      <jdoc:include type="modules" name="<?php $this->_p('menu') ?>" style="raw" />
	    <?php endif ?>
	    </div>
	    
	   </div>
    </div>
  </div>
</nav>
<?php if ($this->countModules('breadcrumb')) : ?>
<section id="breadcrumbs">
  <!-- Breadcrumb -->
    <div class="zen-container">
  		<div class="row-fluid">
        <div id="breadcrumb-row">
			<div class="span9">
       			 <div id="breadcrumb" class="breadcrumb">
   		 			<jdoc:include type="modules" name="breadcrumb" style="zendefault" />
 				</div>
              </div>
	  <!-- // Breadcrumb -->
      <?php if ($this->countModules('search')) { ?>
		  <div class="span3">
		  	<div id="search">
		  		<jdoc:include type="modules" name="search" style="zendefault" />
		  </div></div>
		  <?php } ?>
          </div> <!-- closing breadcrumb-row -->
          </div>
	  </div>
</section>
  <?php endif ?>
<!-- //MAIN NAVIGATION -->