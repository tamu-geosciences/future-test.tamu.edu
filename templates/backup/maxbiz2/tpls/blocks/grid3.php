<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>


<?php if ($this->checkSpotlight('grid3', 'grid9, grid10, grid11, grid12')) : ?>
<!-- Grid1 Row -->
<section id="grid3wrap">
<div class="zen-container">
	  <?php 
	  	$this->spotlight ('grid3', 'grid9, grid10, grid11, grid12')
	  ?>
  </div>
</section>
<?php endif;?>