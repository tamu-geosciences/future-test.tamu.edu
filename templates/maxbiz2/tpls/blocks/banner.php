<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>

<?php if ($this->checkSpotlight('banner', 'banner')) : ?>
<section id="bannerwrap">
	<div class="zen-container">
  		<?php $this->spotlight ('banner', 'banner')?>
  	</div>
</section>
<?php endif;?>