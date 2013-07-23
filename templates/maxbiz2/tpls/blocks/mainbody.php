<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Mainbody 3 columns, content in center: sidebar1 - content - sidebar2
 */
defined('_JEXEC') or die;
?>
<?php

  // Layout configuration
  $layout_config = json_decode ('{
    "two_sidebars": {
      "default" : [ "span6 offset3" , "span3 offset-9"    , "span3"             ],
      "wide"    : [],
      "xtablet" : [],
      "tablet"  : [ "span12"        , "span6 spanfirst"   , "span6"             ]
    },
    "one_sidebar1": {
      "default" : [ "span9 pull-right"         , "span3"             ],
      "wide"    : [],
      "xtablet" : [ "span9 pull-right"         , "span3"             ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  ]
    },
    "one_sidebar2": {
      "default" : [ "span8"         , "span4"             ],
      "wide"    : [],
      "xtablet" : [ "span8"         , "span4"             ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  ]
    },
    "no_sidebar": {
      "default" : [ "span12" ]
    }
  }');

  // positions configuration
  $sidebar1 = 'sidebar-1';
 
  // Detect layout
 if ($this->countModules($sidebar1)) {
    $layout = 'one_sidebar1';
   } else {
    $layout = 'no_sidebar';
  }
  $layout = $layout_config->$layout;

  //
  $col = 0;
?>

<section id="mainWrap">
	<div class="zen-container">
		<div class="row-fluid">
		
		
                  <?php if ($this->countModules('above')) : ?>
                  <!-- Above -->
                  <div id="above">
                    <jdoc:include type="modules" name="above" style="zendefault" />
                  </div>
                  <!-- //Above -->
                  <?php endif ?>

                
		
		    <!-- MAIN CONTENT -->
		    <div id="midCol" class="zen-content <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
		   

                      <?php if ($this->countModules('abovecontent')) : ?>
                      <!-- Above Content -->
                      <div id="abovecontent">
                        <jdoc:include type="modules" name="abovecontent" style="zendefault" />
                      </div>
                      <div class="clearfix"></div>
                      <!-- //Above Content -->
                      <?php endif ?>

      		      <?php if($this->hasMessage()):?>
      		           <jdoc:include type="message" />
      		           <?php endif; ?>
      		      <jdoc:include type="component" />

                <?php if ($this->countModules('belowcontent')) : ?>
                      <!-- Below Content -->
                      <div id="belowcontent">
                        <jdoc:include type="modules" name="belowcontent" style="zendefault" />
                      </div>
                      <!-- //Below Content -->
                      <?php endif ?>
      		    </div>
      		    <!-- //MAIN CONTENT -->

                      
		
		    <?php if ($this->countModules($sidebar1)) : ?>
		    <!-- SIDEBAR 1 -->
		    <div class="sidebar sidebar-1 <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
		      <jdoc:include type="modules" name="<?php $this->_p($sidebar1) ?>" style="zendefault" />
		    </div>
		    <!-- //SIDEBAR 1 -->
		    <?php endif ?>
		    
		    

              <?php if ($this->countModules('below')) : ?>
                  <!-- Below -->
                  <div class="clearfix"></div>
                  <div id="below">
                    <jdoc:include type="modules" name="below" style="zendefault" />
                  </div>
                  <!-- //Below -->
                  <?php endif ?>
		</div>
  </div>
</section> 