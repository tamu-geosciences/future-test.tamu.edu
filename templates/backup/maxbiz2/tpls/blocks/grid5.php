<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>


<?php if ($this->checkSpotlight('grid5', 'grid17, grid18, grid19, grid20')) : ?>
<!-- Grid5 Row -->
<section id="grid5wrap">
<div class="zen-container">
	  <?php 
	  	$this->spotlight ('grid5', 'grid17, grid18, grid19, grid20')
	  ?>
  </div>
</section>
<?php endif;?>