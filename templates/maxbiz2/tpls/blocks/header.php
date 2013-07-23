<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('header', 'header1, header2, header3, header4')) : ?>
<!-- Grid4 Row -->
<section id="headerwrap">
	<div class="zen-container">
		<?php 
			$this->spotlight ('header', 'header1, header2, header3, header4')
		?>
  </div>
</section>
<?php endif;?>
