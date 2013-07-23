<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>

<?php if ($this->checkSpotlight('panel', 'panel1, panel2, panel3, panel4')) : ?>
		
	<div id="panel" class="modal fade" aria-hidden="true">
	  <div class="modal-header"> 
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
		<?php 
			$this->spotlight ('panel', 'panel1, panel2, panel3, panel4')
		?>
	</div>
<?php endif;?>