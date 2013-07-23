<?php
/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

jimport('joomla.updater.update');

$telem = T3_TEMPLATE;
$felem = T3_ADMIN;

$thasnew = false;
$ctversion = $ntversion = $xml->version;
$fhasnew = false;
$cfversion = $nfversion = $fxml->version;

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query
  ->select('*')
  ->from('#__updates')
  ->where('(element = ' . $db->q($telem) . ') OR (element = ' . $db->q($felem) . ')');
$db->setQuery($query);
$results = $db->loadObjectList('element');

if(count($results)){
  if(isset($results[$telem]) && (int)$results[$telem]->version > (int)$ctversion){
    $thasnew = true;
    $ntversion = $results[$telem]->version;
  }
  
  if(isset($results[$felem]) && (int)$results[$felem]->version > (int)$cfversion){
    $fhasnew = true;
    $nfversion = $results[$felem]->version;
  }
}

$hasperm = JFactory::getUser()->authorise('core.manage', 'com_installer');

// Try to humanize the name
$xml->name = ucwords(str_replace('_', ' ', $xml->name));
$fxml->name = ucwords(str_replace('_', ' ', $fxml->name));

?>
<div class="t3-admin-overview">

  <legend class="t3-admin-form-legend"><?php echo JText::_('T3_OVERVIEW_TPL_INFO')?></legend>
   <div id="t3-admin-template-home" class="section">
   	<div class="row-fluid">
 
   		<div class="span8">
   			<?php if (is_file (T3_TEMPLATE_PATH.'/templateInfo.php')): ?>
   			<div class="template-info row-fluid">
   				<?php include T3_TEMPLATE_PATH.'/templateInfo.php' ?>
   			</div>
   			<?php endif ?>
   		</div>
 
       <div class="span4">
         <div id="t3-admin-tpl-info" class="t3-admin-overview-block clearfix">
           <h3><?php echo JText::_('T3_OVERVIEW_TPL_INFO')?></h3>
           <dl class="info">
             <dt><?php echo JText::_('T3_OVERVIEW_NAME')?></dt>
             <dd><?php echo $xml->name ?></dd>
             <dt><?php echo JText::_('T3_OVERVIEW_VERSION')?></dt>
             <dd><?php echo $xml->version ?></dd>
             <dt><?php echo JText::_('T3_OVERVIEW_CREATE_DATE')?></dt>
             <dd><?php echo $xml->creationDate ?></dd>
             <dt><?php echo JText::_('T3_OVERVIEW_AUTHOR')?></dt>
             <dd><a href="<?php echo $xml->authorUrl ?>" title="<?php echo $xml->author ?>"><?php echo $xml->author ?></a></dd>
           </dl>
         </div>
         
       </div>
 
     </div>

   <br />
   <br />
   <div class="row">
   <legend class="t3-form-legend">This template is built using the T3 Framework</legend>
    
    <div class="span6">
          <p>The T3 framework is a feature rich, bootstrap based Joomla template framework built for Joomla 2.5 and Joomla3+.</p>
         

         <div class="span7 offset1">
         <br />
         	<ul class="nav-pills nav">
         		<li class="active"><a href="http://t3-framework.org/">Overview</a></li>
         		<li class="active"><a href="http://t3-framework.org/documentation.html">Documentation</a></li>
         		<li class="active"><a href="https://github.com/t3framework/t3">Latest</a></li>
         	</ul>
         </div>

     </div>
   	<div class="span2">
   	</div>
         <div class="span4">
           <div id="frmk-info" class="admin-block clearfix">
             <h3><?php echo JText::_('T3_OVERVIEW_FRMWRK_INFO')?></h3>
             <dl class="info">
                            <dt><?php echo JText::_('T3_OVERVIEW_NAME')?></dt>
                            <dd><?php echo $fxml->name ?></dd>
                            <dt><?php echo JText::_('T3_OVERVIEW_VERSION')?></dt>
                            <dd><?php echo $fxml->version ?><p><small>Please note that this template is not compatible with versions of T3 prior to v1.2.3.</small></p></dd>
                            <dt><?php echo JText::_('T3_OVERVIEW_CREATE_DATE')?></dt>
                            <dd><?php echo $fxml->creationDate ?></dd>
                            <dt><?php echo JText::_('T3_OVERVIEW_AUTHOR')?></dt>
                            <dd><a href="<?php echo $fxml->authorUrl ?>" title="<?php echo $fxml->author ?>"><?php echo $fxml->author ?></a></dd>
                          </dl>
           </div>
         </div>
   
       </div>
   
   	</div>

   
</div>