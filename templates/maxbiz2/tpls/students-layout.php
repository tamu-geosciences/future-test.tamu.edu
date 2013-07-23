<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

	$app = JFactory::getApplication();
    $hide_mainbody = (!$this->params->get("ZEN_MAINBODY_DISABLED",true)==false && $app->input->getString('view') == 'featured');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock ('head');?>  
 </head>
<body id="contentleft" class="<?php $this->bodyClass() ?>">
	<?php $this->loadBlock ('background') ?>
  	<div id="fullwrap">
  		<div id="topcolour">
			<?php $this->loadBlock ('top') ?>
			<?php $this->loadBlock ('header') ?>
			<?php $this->loadBlock ('nav') ?>
        	<?php $this->loadBlock ('logo') ?>
		</div>
		
		<div id="gradient">
			<div class="zen-container">
				<div class="gradient-inner">
					<div class="row-fluid">
						<div id="main-area" class="<?php if ($this->checkSpotlight('sidebar', 'sidebar-2')) { ?>span9<?php } else { ?>span12<?php } ?>">
							<?php $this->loadBlock ('banner') ?>
							<?php $this->loadBlock ('tabs') ?>
							<?php $this->loadBlock ('grid1') ?>
							<?php $this->loadBlock ('grid2') ?>
							<?php $this->loadBlock ('grid3') ?>
							<?php if (!$hide_mainbody) : ?>
									<?php $this->loadBlock ('mainbody') ?>
							<?php endif; ?>
							<?php $this->loadBlock ('grid4') ?>
							<?php $this->loadBlock ('grid5') ?>
							<?php $this->loadBlock ('grid6') ?>
						</div>
						<?php if ($this->checkSpotlight('sidebar', 'sidebar-2')) : ?>
						<div class="span3">
							<div id="main-side">
								
								
								<!-- SIDEBAR 2 -->
								<div id="sidebar-main" class="sidebar sidebar-2 affix-top">
								  <?php $this->loadBlock ('sidebar') ?>
								</div>
								<!-- //SIDEBAR 2 -->
								
							</div>
						</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<?php $this->loadBlock ('bottom') ?>
    	<?php $this->loadBlock ('footer') ?>  
	 </div>
	 <?php $this->loadBlock ('panel') ?>
	 <?php $this->loadBlock ('scripts') ?>
	<?php $this->loadBlock ('fonts') ?>
  </body>
</html>