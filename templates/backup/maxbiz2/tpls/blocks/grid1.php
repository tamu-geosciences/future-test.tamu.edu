<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>



<?php if ($this->checkSpotlight('grid1', 'grid1, grid2, grid3, grid4')) : ?>
<!-- Grid1 Row -->
<section id="grid1wrap">
	<div class="zen-container">
	  <?php 
	  	$this->spotlight ('grid1', 'grid1, grid2, grid3, grid4')
	  ?>
  </div>
</section>
<?php endif ?>