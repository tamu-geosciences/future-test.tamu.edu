<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- FOOTER -->
<footer id="footerwrap" class="wrap zen-footer">
  <section class="zen-copyright">
    <div class="zen-container">
      <div class="row-fluid">
        <div class="span12 copyright">
          <jdoc:include type="modules" name="<?php $this->_p('footer') ?>" style="jbChrome" />
        </div>
     <!--   <div class="span4">
        	<div id="zen-copyright">
	        	<?php if (!$this->params->get('copyright')) { ?> 
                      <a class="jblink" target="_blank" href="http://www.joomlabamboo.com"><span>Joomla Template by Joomlabamboo</span></a>
                    <?php } else {
                    echo $this->params->get('customcopyright');
                  }?>
        	</div>
        </div> -->
      </div>
    </div>
  </section>
</footer>
<jdoc:include type="modules" name="<?php $this->_p('debug') ?>" style="jbChrome" />
<!-- //FOOTER -->