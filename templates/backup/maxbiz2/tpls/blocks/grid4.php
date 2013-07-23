<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>


<?php if ($this->checkSpotlight('grid4', 'grid13, grid14, grid15, grid16')) : ?>
<!-- Grid4 Row -->
<section id="grid4wrap">
<div class="zen-container">
	  <?php 
	  	$this->spotlight ('grid4', 'grid13, grid14, grid15, grid16')
	  ?>
  </div>
</section>
<?php endif;?>