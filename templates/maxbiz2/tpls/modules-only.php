<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock ('head');?>  
 </head>
<body id="home" class="<?php $this->bodyClass() ?>">
<?php $this->loadBlock ('background') ?>
  	<div id="fullwrap">
		<div id="topcolour">
				<?php $this->loadBlock ('top') ?>
				<?php $this->loadBlock ('header') ?>
				<?php $this->loadBlock ('logo') ?>
				<?php $this->loadBlock ('nav') ?>
			</div>
			<?php $this->loadBlock ('banner') ?>
			<div id="gradient">
				<?php $this->loadBlock ('tabs') ?>
				<?php $this->loadBlock ('grid1') ?>
				<?php $this->loadBlock ('grid2') ?>
				<?php $this->loadBlock ('grid3') ?>
				<?php $this->loadBlock ('grid4') ?>
				<?php $this->loadBlock ('grid5') ?>
				<?php $this->loadBlock ('grid6') ?>
			</div>
			<?php $this->loadBlock ('bottom') ?>
			<?php $this->loadBlock ('footer') ?>  
		 </div>
		 <?php $this->loadBlock ('panel') ?>
		 <?php $this->loadBlock ('scripts') ?>
		<?php $this->loadBlock ('fonts') ?>
  </body>
</html>