<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>


<?php if ($this->checkSpotlight('grid6', 'grid21, grid22, grid23, grid24')) : ?>
<!-- Grid5 Row -->
<section id="grid6wrap">
<div class="zen-container">
	  <?php 
	  	$this->spotlight ('grid6', 'grid21, grid22, grid23, grid24')
	  ?>
  </div>
</section>
<?php endif;?>