<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;  ?>



<?php if ($this->countModules('tabs')) : ?>
<!-- Grid1 Row -->
<section id="tabwrap" class="clearfix">
	<div class="zen-container">
		<jdoc:include type="modules" name="tabs" style="zentabs"/>
  	</div>
</section>
<?php endif; ?>