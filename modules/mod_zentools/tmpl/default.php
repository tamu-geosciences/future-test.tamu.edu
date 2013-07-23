<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.8.5
 */

defined('_JEXEC') or die('Restricted access');

// Module Class Suffix
$moduleclass_sfx = $params->get('moduleclass_sfx');

// View Parameters
$masonryColumnWidth = $params->get('masonryColumnWidth');
$masonryGutter = $params->get('masonryGutter');
$masonryWidths = $params->get('masonryWidths');
$masonryColWidths = $params->get('masonryColWidths');
$browserThreshold = $params->get('browserThreshold');
$border = $params->get('imageEffect');
$itemsperpage = $params->get('itemsperpage',3);
$slideTitleHide = str_replace('px', '', $params->get('slideTitleHide'));
$slideTitleWidth = str_replace('%', '', $params->get('slideTitleWidth'));
$slideshowPaginationWidth = $params->get('slideshowPaginationWidth','twelve');
$linktarget = $params->get('linktarget');
$fullwidthimage = $params->get('fullwidthimage');
$modalThumbTip = $params->get('modalThumbTip');

$typeclass = null;

$filterstart = 0;
$filterstartAlias = 'showall';
$db = JFactory::getDbo();
if ($contentSource == 2)
{
	$filterstart = $params->get('filterstart_joomla');
	if (is_array($filterstart))
	{
		$filterstart = $filterstart[0];
	}

	if (!empty($filterstart))
	{
		if (version_compare(JVERSION, '2.5', '<'))
		{
			$query = 'SELECT alias FROM #__categories WHERE id = "' . $filterstart . '"';
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select('alias');
			$query->from('#__categories');
			$query->where('id = "' . $filterstart . '"');
		}

		$db->setQuery($query);
		$category = $db->loadObject();

		$filterstartAlias = $category->alias;
	}
}
elseif ($contentSource == 3)
{
	$filterstart = $params->get('filterstart_k2');
	if (is_array($filterstart))
	{
		$filterstart = $filterstart[0];
	}

	if (!empty($filterstart))
	{
		if (version_compare(JVERSION, '2.5', '<'))
		{
			$query = 'SELECT alias FROM #__k2_categories WHERE id = "' . $filterstart . '"';
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select('alias');
			$query->from('#__k2_categories');
			$query->where('id = "' . $filterstart . '"');
		}

		$db->setQuery($query);
		$category = $db->loadObject();
		$filterstartAlias = $category->alias;
	}
}
if ($filterstart == 0)
{
	$filterstart = 'showall';
}

$filterwidth = str_replace("px", "", $params->get('filterwidth', 320));

if ($params->get('overlayMore'))
{
	$moreClass= 'overlaymore';
}
else
{
	$moreClass= '';
}

$renderContentPlugins = $params->get('renderPlugin');

// Lightbox
$modalVideo = $params->get('modalVideo');
$modalTitle = $params->get('modalTitle');
$modalMore = $params->get('modalMore');

// Place Holder Image
$usePlaceholder = $params->get('usePlaceholder', 0);
$placeHolderImage = $params->get('placeHolderImage');

$slideCountSep = $params->get('slideCountSep');
$slideCount = $params->get('slideCount');


// Null some variables
$joomla15 = false;
$joomla25 = false;
$altlink = false;
$imagesPath = '';

// Set the default path for the placeholder image
if (substr(JVERSION, 0, 3) >= '1.6')
{
	$imagesPath = 'images/';
}
else
{
	$joomla15 = true;
	$imagesPath = 'images/stories/';
}

if (substr(JVERSION, 0, 3) >= '2.5') {
	$joomla25 = true;
	$altlink = (bool)$params->get('altlink');
}
else {
	$joomla25 = 0;
}

// Grid Layout
$disableMargin = $params->get('disableMargin');

// Image title attribute
$imageTitle = $params->get('imageTitleAtt', 1);

// Images
$imageFade = $params->get('imageFade', 0);

// Logic for applying zenlast class to the last column.
$lastcolumn2 = false;
$lastcolumn3 = false;
$column2 = false;
$column3 = false;
$column4 = false;

$presetwarning = "<div class='notice'><strong>Warning</strong><br />It appears as though you have not applied the layout preset.<br />
After selecting the preset in the layout select list in the zentools admin, please hit the apply preset button to avoid seeing this message.</div>";

// Pagination is the same as the grid layour - well almost
// Sanistise the layotu variable in case the user selected a preset but didnt apply it.
if($layout =="pagination") {
	$layout ="grid";
}
elseif($layout =="gridtwocol") {
	$layout ="grid";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="gridfilter") {
	$layout ="grid";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="list2col") {
	$layout ="list";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="list3col") {
	$layout ="list";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="list4col") {
	$layout ="list";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="slideshowOverlay") {
	$layout ="slideshow";
	echo $presetwarning;
	$elements = false;
}
elseif($layout =="slideshowFlat") {
	$layout ="slideshow";
	echo $presetwarning;
	$elements = false;
}



	if($elements) {
		$enabled  = 1;
		$displayitems = explode(",", $elements);

		if(array_search('column2', $displayitems) !== false){	$column2 = 1;}
		if(array_search('column3', $displayitems) !== false){	$column3 = 1;}
		if(array_search('column4', $displayitems) !== false){	$column4 = 1;}

		if(!$column4 && !$column3) {$lastcolumn2 = "zenlast";}
		if(!$column4) {$lastcolumn3 = "zenlast";}


		// Remove non-image as directory options from array
		if($contentSource =="1") {
			if(array_search('extrafields', $displayitems) !== false){
				unset($displayitems[array_search('extrafields',$displayitems)]);
			}
			if(array_search('comments', $displayitems) !== false){
				unset($displayitems[array_search('comments',$displayitems)]);
			}
			if(array_search('attachments', $displayitems) !== false){
				unset($displayitems[array_search('attachments',$displayitems)]);
			}
			if(array_search('video', $displayitems) !== false){
				unset($displayitems[array_search('video',$displayitems)]);
			}
			if(array_search('date', $displayitems) !== false){
				unset($displayitems[array_search('video',$displayitems)]);
			}
			if(array_search('category', $displayitems) !== false){
				unset($displayitems[array_search('category',$displayitems)]);
			}
			// Resets the array
			$displayitems = array_values($displayitems);
		}

		// #Jinfinity - Check if com_jinfinity exists
		//$modelPath = JPATH_SITE . '/components/com_jinfinity/models';
		//$jiexists = JFolder::exists($modelPath);
		//if($jiexists) {
		//    require_once(JPATH_SITE.'/components/com_jinfinity/models/fields.php');
		    // Check fields are still available
		//    $fieldnames = JinfinityModelFields::getFieldNames();
		//    $available = array_merge(array('title','image','text','date','category','more','column2','column3','column4','tweet','isfeatured','fields'), $fieldnames);
		//    foreach($displayitems as $key=>$item) {
		//        if(!in_array($item, $available)) unset($displayitems[$key]);
		//    }
		//}


		// Remove k2 items from the array if content as a source
		if($contentSource =="2") {

			if(array_search('extrafields', $displayitems) !== false){
				unset($displayitems[array_search('extrafields',$displayitems)]);

			}

			if(array_search('comments', $displayitems)!== false){
				unset($displayitems[array_search('comments',$displayitems)]);
			}

			if(array_search('video', $displayitems) !== false){
				unset($displayitems[array_search('video',$displayitems)]);
			}
			//print_r($displayitems);

			// Resets the array
			$displayitems = array_values($displayitems);

		}

		// Test for tweet in the list of tags
		if(array_search('tweet', $displayitems) !== false){
			$tweet = true;
		}
		else {
			$tweet = false;
		}

		$countElements = count($displayitems);

	}
	else {
		echo "<div class='notice'>No content assigned to be displayed.</div>";
		$enabled = 0;
	}
	if($link !==0) {
		$closelink ="</a>";
	}
	$catlinkclose ="</a>";
	$readmoreclose ="</span></a>";


	// External Links
	if($link==3) {
		$extlinks = explode("\r\n", $params->get('extlinks'));
		$extlinks = explode("\n", $params->get('extlinks'));
	}


	// Params for column markup in the module
	if(!$column2 && !$column3 && !$column4) {
		$column1col = "twelve";
	}
	else {
		$column1col = $params->get('col1Width');
	}
	$column2col = $params->get('col2Width');
	$column3col = $params->get('col3Width');
	$column4col = $params->get('col4Width');

	$column2Markup="</div><div class='column2 grid_$column2col $lastcolumn2'>";
	$column3Markup="</div><div class='column3 grid_$column3col $lastcolumn3'>";
	$column4Markup="</div><div class='column4 grid_$column4col zenlast'>";


	// Null some of the item variables
	$titleMarkup = false;
	$textMarkup = false;
	$imageMarkup = false;

	$numMB = sizeof($list);

	if($enabled) {

		/**
		*
		* Filter triggerLoop
		*
		**/

			if($categoryFilter && ($layout == "grid" or $layout =="list")) {
			?>
			<ul id="filters" class="module<?php echo $moduleID ?>">
				<li class="showallID">
					<a href="#" data-filter="*" data-behavior="filter"><span><span><?php echo JText::_('ZEN_SHOW_ALL') ?></span></span></a>
				</li>
				<?php
				$filters = array();
				foreach($list as $key => $item) :
					if (!in_array($item->category, $filters)) :
						$filters[] = $item->category;
					endif;
				endforeach;

				// Category ordering for Joomla
				if ($contentSource == '2')
				{
					if ($params->get('ordering', 'a.ordering') === 'a.ordering') :

						// Sort the categories
						$db = JFactory::getDbo();
						$query = $db->getQuery(true);
						$query->select('id, title, alias');
						$query->from('#__categories');
						$query->group('alias');
						$query->order('lft ' . $params->get('ordering_direction', 'ASC'));

						$db->setQuery($query);
						$categories = $db->loadObjectList();
					else :
						$categories = array();
						foreach ($list as $key => $item) :
							$category = new stdClass;
							$category->alias = $item->category_alias;
							$category->title = $item->category;

							$categories[] = $category;
						endforeach;
					endif;
				}
				// K2 category sorting
				elseif ($contentSource == '3')
				{
					// Sort the categories
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->select('id, name AS title, alias');
					$query->from('#__k2_categories');
					$query->group('alias');
					$query->order('ordering');

					$db->setQuery($query);
					$categories = $db->loadObjectList();
				}

				foreach ($categories as $category) :
					if (in_array($category->title, $filters)) :
						?>
						<li class="<?php echo $category->alias ?>ID <?php echo $category->alias; ?>">
							<a href="#" data-filter=".<?php echo $category->alias ?>" data-behavior="filter"><span><span><?php echo $category->title ?></span></span></a>
						</li>
						<?php
					endif;
				endforeach;
				?>
			</ul>
			<?php } ?>



		<div id="zentools<?php echo $moduleID ?>" class="<?php if($layout=="slideshow") { ?>slideshow slideshow<?php echo $slideshowTheme; } ?> <?php if($layout=="carousel") {?>es-carousel-wrapper<?php } ?><?php if($params->get('layout') == "pagination" || $slideTrigger == "tabs") { ?> zenpagination<?php }?> <?php echo $moduleclass_sfx;?>">
			<div class="zentools <?php echo $layout ?> <?php if($layout=="slideshow") {?>flexslider<?php } ?> <?php echo $border ?> count<?php echo $countElements ?> <?php if($layout == "carousel") { ?>es-carousel<?php } ?><?php if($layout == "grid" && $disableMargin) {?> nomargin<?php }?><?php if(($layout == "grid" && $overlayGrid) || ($layout == "carousel" && $overlayCarousel)) {?> overlay<?php }?>">

				<?php if($params->get('layout') == "pagination"){ ?>
				<div class="zenpages">
						<div id="paginationinner<?php echo $moduleID ?>"></div>
				</div>
				<?php } ?>

				<ul id="zentoolslist<?php echo $moduleID ?>"  <?php if($layout=="slideshow") {?>class="slides <?php echo $params->get('slideshowPaginationPos', 'zenleft'); ?>"<?php } ?>>

					<?php
					if (is_array($list)) {

						foreach($list as $key => $item) :

							// Provides a clean version of the title without class or other markup
							$item->cleantitle = $item->title;

							// Construct the data attribute if using lightbox
							if($link==1 && $contentSource !== "1") {
									// Construct the data attribute if using lightbox
									$item->link= 'data-href="#data'.$item->id.'"';
							}

							/**
							 *
							 * #Jinfinity - Custom Fields as group
							 *
							 **/
							//if($jiexists) $item->fields = JinfinityModelFields::displayFields($item);


							$modalvideo =  JHtml::_('content.prepare', $item->video);

							/**
							*
							* Read More
							*
							**/
							$dataTitle = $modalFooterTitle ? ' data-title="'.$item->cleantitle.'"' : '';

							if($link==1){

								if($altlink && $item->newlink && $joomla25) {
									$item->more = '<a
										data-thumbTooltip="'.$item->cleantitle.'"
										data-group="gallery-'.$moduleID.'c"
										' . $dataTitle . '
										data-width="'. $modalWidth.'"
										class="jackbox '.$moreClass.' button"
										'.$item->altlink.' >
											<span class="readon">'.$params->get('readonText').$readmoreclose;
								}
								else {
									$item->more = '<a data-width="'. $modalWidth.'" data-thumbTooltip="'.$item->cleantitle.'" data-group="gallery-'.$moduleID.'c" '.$dataTitle.' class="jackbox '.$moreClass.' btn btn-primary"'.$item->link.'><span class="readon">'.$params->get('readonText').$readmoreclose;
								}
							}
							elseif($link==2) {
								if($altlink && isset($item->altlink) && $joomla25) {
									$item->more = '<a target="'.$linktarget.'" class="inline '.$moreClass.' btn btn-primary" '.$item->altlink.' data-behavior="content"><span class="readon">'.$params->get('readonText').$readmoreclose;
								}
								else {
									$item->more = '<a target="'.$linktarget.'" class="'.$moreClass.' btn btn-primary" '.$item->link.' data-behavior="content"><span class="readon">'.$params->get('readonText').$readmoreclose;
								}

							}
							elseif($link==3) {
								if(isset($extlinks[$key])) {
									$item->link = $extlinks[$key];
									$item->more = '<a target="'.$linktarget.'" class="'.$moreClass.' btn btn-primary" href="'.$item->link.'" data-behavior="external"><span class="readon">'.$params->get('readonText').$readmoreclose;
								}
								else {
									$item->more = null;
								}
							}
							else {
								$item->more = false;
							}




							/**
							*
							* Slideshow links
							*
							**/


								if($layout=="slideshow") {
									if($slideTrigger=="thumb"){
										if($item->image !=="") {
											$item->trigger = '<img src="'.$item->thumb.'" alt="'.$item->cleantitle.'" title="'.$item->title.'"/>';
										}
										else {
											$item->trigger = '<img src="'.ZenToolsHelper::getResizedImage('media/mod_zentools/images/placeholder.jpg', $thumb_width, $thumb_height, $option).'" alt="image"  title="'.$item->title.'"/>';
										}
									}
									elseif($slideTrigger=="title"){
										$item->trigger = $item->title;
									}

									elseif($slideTrigger=="numbers" || $slideTrigger=="discs"){
										$item->trigger = $key+1;
									}
									else {
										$item->trigger = false;
									}
								}


							/**
							*
							* Title and Link
							*
							**/

							if ($renderContentPlugins == 'render'){
								if (substr(JVERSION, 0, 3) >= '1.6') {
										$item->title = JHtml::_('content.prepare', $item->title);
								} else {
									$plgparams 	   = $mainframe->getParams('com_content');
									$dispatcher	   = JDispatcher::getInstance();
									JPluginHelper::importPlugin('content');
									$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $plgparams));
									$item->title = $item->title;
								}
							} else {
									$item->title = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i','', $item->title);

							}

							// Clean title for image titles etc
							$item->cleantitle = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i','', ucfirst($item->title));

							if(($layout == "accordion") && ($displayitems[0] == "title")) {
								$item->title = '<'.$titleClass.'><span>'.$item->title.'</span></'.$titleClass.'>';
							}
							else {
								if($link==1){
									$item->title = '<'.$titleClass.'>
														<a data-width="'. $modalWidth.'" data-group="gallery-'.$moduleID.'t" '.$dataTitle.' class="jackbox" '.$item->link.' >
																<span>'.$item->title.'</span>
														'.$closelink.'
													</'.$titleClass.'>';
								}
								elseif($link==2){
									if($altlink && isset($item->altlink) && $joomla25) {
										$item->title = '<'.$titleClass.'><a target="'.$linktarget.'" '.$item->altlink.' data-behavior="content"><span>'.$item->title.'</span>'.$closelink.'</'.$titleClass.'>';
									}
									else {
										$item->title = '<'.$titleClass.'><a target="'.$linktarget.'" '.$item->link.' data-behavior="content"><span>'.$item->title.'</span>'.$closelink.'</'.$titleClass.'>';
									}
								}
								elseif($link==3){
									if(isset($extlinks[$key])) {
										$item->link = $extlinks[$key];
										$item->title = '<'.$titleClass.'><a target="'.$linktarget.'" href="'.$item->link.'" data-behavior="external"><span>'.$item->title.'</span>'.$closelink.'</'.$titleClass.'>';
									}
									else {
										$item->link = null;
									}


								}
								else {
									$item->title = '<'.$titleClass.'><span>'.$item->title.'</span></'.$titleClass.'>';
								}
							}

							if($contentSource == "1") {
								$item->id = $key;
							}


							/**
							*
							* Image and Link
							*
							**/

							$img = false;
							$imgModal = false;

							if($imageTitle) {
							 $imageTitleText = 'title="'.$item->cleantitle.'"';
							}
							else {
								$imageTitleText = false;
							}

							if(!empty($item->image)) {
								if ($responsiveimages)
								{
									$img = '<img
												data-original="'.$item->{'image'.$params->get('sourceImage')}.'"
												src="'.$item->{'image'.$params->get('sourceImage')}.'"
												data-src320="'.$item->{'image'.$params->get('mobile')}.'"
												data-src481="'.$item->{'image'.$params->get('tabletPortrait')}.'"
												data-src769="'.$item->{'image'.$params->get('tabletLandscape')}.'"
												data-src1025="'.$item->{'image'.$params->get('desktopImage')}.'"
												data-src1281="'.$item->{'image'.$params->get('wideImage')}.'"
												alt="'.$item->cleantitle.'" '.$imageTitleText.'/>';
								}
								else
								{
									$img = '<img data-original="'.$item->image.'"  src="'.$item->image.'"  alt="'.$item->cleantitle.'" '.$imageTitleText.'/>';
								}

								$imgModal = '<img data-original="'.$item->imageOriginal.'"  src="'.$item->imageOriginal.'"  alt="'.$item->cleantitle.'" '.$imageTitleText.'/>';

								if($imagesreplace && isset($item->video) && !empty($item->video) && $contentSource == 3) {
									if (substr(JVERSION, 0, 3) >= '1.6') {
										$item->video = JHtml::_('content.prepare', $item->video);
									}

									$item->image = '<div class="video-container"><div class="zenvideo">'.$item->video.'</div></div>';

									$typeclass = "video";
								}
								else {
									if($link==1){
										$item->image = '<a data-width="'. $modalWidth.'" data-thumbTooltip="'.$item->cleantitle.'" data-group="gallery-'.$moduleID.'-image" '.$dataTitle.' class="jackbox" '.$item->link.' data-behavior="lightbox">'.$img.$closelink;
									}
									elseif($link==2) {
										if($altlink && isset($item->altlink) && $joomla25) {
											$item->image = '<a target="'.$linktarget.'" '.$item->altlink.' data-behavior="content">'.$img.$closelink;
										}
										else {
											$item->image = '<a target="'.$linktarget.'" '.$item->link.' data-behavior="content">'.$img.$closelink;
										}
									}
									elseif($link==3) {
										if(isset($extlinks[$key])) {
											$item->link = $extlinks[$key];
											$item->image = '<a target="'.$linktarget.'" href="'.$item->link.'" data-behavior="external">'.$img.$closelink;
										}
										else {
											$item->link = null;
											$item->image = $img;
										}
									}

									else {
										$item->image = $img;
									}
									$typeclass = "text";
								}
							}
							else {
								if($usePlaceholder) {
									if($link==1){
										$item->image = '<a data-width="'. $modalWidth.'" data-thumbTooltip="'.$item->cleantitle.'" data-group="gallery-'.$moduleID.'i" '.$dataTitle.' class="jackbox"><img src="'.ZenToolsHelper::getResizedImage(''.$imagesPath.$placeHolderImage.'', $image_width, $image_height,  $option).'" alt="'.$item->cleantitle.'" '.$imageTitleText.'/>'.$closelink;
									}
									else {
										$item->image = '<a data-width="'. $modalWidth.'" data-description="test<?php echo $item->id;?>" data-group="gallery-'.$moduleID.'i" '.$dataTitle.' class="jackbox" target="'.$linktarget.'" '.$item->link.'><img src="'.ZenToolsHelper::getResizedImage(''.$imagesPath.$placeHolderImage.'', $image_width, $image_height,  $option).'" alt="'.$item->cleantitle.'" '.$imageTitleText.'/>'.$closelink;
									}
								}
								else {
									$item->image = "";
								}
							}



							/**
							*
							* Category and Link
							*
							**/

							if(($layout == "accordion") && ($displayitems[0] == "category") or ($layout == "grid")) {
								$item->category = '<span>'.$item->category.'</span>';
							}
							else {
								$item->category = $item->catlink.'<span>'.$item->category.'</span>'.$catlinkclose;
							}

							// Adds zenlast to the last item in the row
							$lastitem = ($key == ($numMB -1)) ? "zenlast" : "";

							// Assigns the last image in the row to have 0 margin
							$imageNumber++;

							$rowFlag = ($imageNumber % $imagesPerRow) ? 0 : 1;
							
							if($contentSource == "3") {
								// K2 Extra fields
								if(is_array($item->extrafields)) {
									foreach ($item->extrafields as $key=>$extraField):
										$item->extrafields .= $extraField->value;
										$item->extrafields .= '<br />';
										endforeach;
									}
									$item->extrafields = str_replace("Array", "", $item->extrafields);
							}


							if($contentSource !=="1") {
								$meta = explode(" ", $item->metakey);
							}
							if($layout == "masonry") {
								if($masonryWidths && $contentSource !=="1") {
									$gridclass = $meta[0];
								}
								else {
									$gridclass = $masonryColWidths;
								}
							}
							elseif($layout == "list" || $layout == "accordion" || $layout =="slideshow"|| $layout =="carousel" || $layout =="single") {
								$gridclass="twelve";
							}


							/*
							* Code for adding the featured class
							*/

							if ($joomla15 && $contentSource =="2")
							{
								$meta = explode(" ", $item->metakey);

								$item->featured = $meta[0] === "featured";

								if (!$item->featured)
								{
									$db = JFactory::getDbo();
									$db->setQuery('SELECT COUNT(*) FROM #__content_frontpage WHERE content_id = "' . $item->id . '"');

									$item->featured = (int)$db->loadResult() > 0;
								}
							}



							/*
							* Process the prepare content plugins
							*/

							if ($renderContentPlugins == 'render'){
								if (substr(JVERSION, 0, 3) >= '1.6') {
									$item->text = JHtml::_('content.prepare', $item->text);
								} else {
									$plgparams 	   = $mainframe->getParams('com_content');
									$dispatcher	   = JDispatcher::getInstance();
									JPluginHelper::importPlugin('content');
									$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $plgparams));
									$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $plgparams));
								}
							} else {
								$item->text = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i','', $item->text);
							}


							/*
							* Prepare plugins for video field - used if not using the traditional methods
							*/
							if(isset($item->video)) {
								if (substr(JVERSION, 0, 3) >= '1.6') {
									$item->video = JHtml::_('content.prepare', $item->video);
								}
							}

							/*
							* Tweet This button
							*/
							$hastTags = str_replace('#', '', $params->get('tweetHashtag'));
							$item->tweet ='
								<a href="https://twitter.com/share" class="twitter-share-button" data-url="'. $item->link .'" data-text="'. $params->get('tweetText') .' '. $item->cleantitle .'" data-via="'. $params->get('twitterName','joomlabamboo') .'" data-count="'. $params->get('tweetCount') .'" data-hashtags="'. $hastTags .'" data-size="'. $params->get('tweetLargeButton') .'">' . JText::_('Tweet') . '</a>
							';
							unset($hastTags);

							// Leading then title view. Basically nulls the elements for all items after the first one
							if($layout =="leading") {
								if($key !==0){$item->image = $item ->category = $item->text = $item->more = $item->date ="";}
							}

							$countDisplayItems = count($displayitems);
							?>

					<li class="grid_<?php echo $gridclass; ?> element <?php if((bool)$categoryFilter && ($layout === "grid" || $layout === "list")) { echo $item->category_alias; }?> <?php if($rowFlag && $slideTrigger !=="tabs" && $layout != "slideshow" && $layout != 'carousel') { echo " zenlast"; } if($item->featured) { echo " featured";}?>">
							<div class="zenitem zenitem<?php echo $key + 1; ?> <?php if($item->featured) { echo "featured"; } ?> <?php echo $displayitems[0]; ?> <?php echo $fullwidthimage; ?>">
								<div class="zeninner">
									<div class="column grid_<?php echo $column1col; ?>">


										<?php if($countDisplayItems > 0)  {?>
											<div class="zen<?php echo $displayitems[0]; ?> element1 firstitem"><?php echo $item->$displayitems[0]; ?></div>
										<?php } ?>

											<?php if($layout == "accordion" || ($layout =="slideshow" && $slideshowTheme == "overlay") || ($layout =="slideshow" && $slideshowTheme == "lifestyle") || ($layout =="slideshow" && $slideshowTheme == "overlayFrame") || ($layout =="slideshow" && $slideshowTheme == "standard") || ($layout == "grid" && $overlayGrid) ||  ($layout == "carousel" && $overlayCarousel)) {?>
												<div class="allitems <?php echo $typeclass ?> container"><div>
											<?php } ?>
												<?php if($countDisplayItems > 1)  {
													if($displayitems[1] == "column2" || $displayitems[1] == "column3"  || $displayitems[1] ==  "column4") {echo ${$displayitems[1].'Markup'}; } else {	?>
													<div class="zen<?php echo $displayitems[1]; ?> element2"><?php echo $item->$displayitems[1]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 2)  {

													if($displayitems[2] == "column2" || $displayitems[2] == "column3"  || $displayitems[2] ==  "column4") {echo ${$displayitems[2].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[2]; ?> element3"><?php echo $item->$displayitems[2]; ?></div>
												<?php } }?>

												<?php if($countDisplayItems > 3)  {
													if($displayitems[3] == "column2" || $displayitems[3] == "column3"  || $displayitems[3] ==  "column4") {echo ${$displayitems[3].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[3]; ?> element4"><?php echo $item->$displayitems[3]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 4)  {
													if($displayitems[4] == "column2" || $displayitems[4] == "column3"  || $displayitems[4] ==  "column4") {echo ${$displayitems[4].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[4]; ?> element5"><?php echo $item->$displayitems[4]; ?></div>
												<?php } }?>

												<?php if($countDisplayItems > 5)  {
													if($displayitems[5] == "column2" || $displayitems[5] == "column3"  || $displayitems[5] ==  "column4") {echo ${$displayitems[5].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[5]; ?> element6"><?php echo $item->$displayitems[5]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 6)  {
													if($displayitems[6] == "column2" || $displayitems[6] == "column3"  || $displayitems[6] ==  "column4") {echo ${$displayitems[6].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[6]; ?> element7"><?php echo $item->$displayitems[6]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 7)  {
													if($displayitems[7] == "column2" || $displayitems[7] == "column3"  || $displayitems[7] ==  "column4") {echo ${$displayitems[7].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[7]; ?> element8"><?php echo $item->$displayitems[7]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 8)  {
													if($displayitems[8] == "column2" || $displayitems[8] == "column3"  || $displayitems[8] ==  "column4") {echo ${$displayitems[8].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[8]; ?> element9"><?php echo $item->$displayitems[8]; ?></div>
												<?php } } ?>

												<?php if($countDisplayItems > 9)  {
													if($displayitems[9] == "column2" || $displayitems[9] == "column3"  || $displayitems[9] ==  "column4") {echo ${$displayitems[9].'Markup'};} else {	?>
													<div class="zen<?php echo $displayitems[9]; ?> element10"><?php echo $item->$displayitems[9]; ?></div>
												<?php } }?>
											<?php if($layout == "accordion" || ($layout =="slideshow" && $slideshowTheme == "overlay") || ($layout =="slideshow" && $slideshowTheme == "lifestyle") || ($layout =="slideshow" && $slideshowTheme == "overlayFrame") || ($layout == "grid" && $overlayGrid) ||  ($layout == "carousel" && $overlayCarousel)) {?>
												</div></div>
											<?php } ?>
									</div>
									<div class="clear"></div>
								</div>
							</div>

						<?php if(($link == 1) && ($contentSource !=="1")) {?>

						<div class="jackbox-description" id="data<?php echo $item->id ?>">
							<div class="zenmodalwrap <?php echo $border; ?>">
								<?php if($modalTitle) {echo '<h2>'.$item->modaltitle.'</h2>';} ?>
							    <?php if($modalVideo) {echo $modalvideo;}?>
							    <?php if($modalImage) {echo '<div class="modalimage">'.$imgModal.'</div>';}?>
							    <?php if($modalText) {echo JHtml::_('content.prepare', $item->modaltext);} ?>
							    <?php if($modalMore) {echo '<a '.$item->lightboxmore.'><span class="readon">'.$params->get('readonText').'</span></a>';}?>
						    </div>
						</div>
						<?php } ?>
					</li>
						<?php if ($rowFlag && $layout === "leading" or ($rowFlag && $layout === "grid" && !(bool)$categoryFilter))  : ?>
								<li><div class="clearfix clear"></div></li>
						<?php endif; ?>
					<?php endforeach;
				} else {
					echo "<div class='notice'>No content assigned to be displayed.</div>";
				}?>
				</ul>

				<?php if($layout=="slideshow") {?>
					<?php
					// Avoid hide the prev/next buttons when pagination is disabled but naviagation is enabled
					$slideshowPaginationType = $params->get('slideshowPaginationType');
					$slideshowNav = $params->get('slideshowNav');
					$controllerClass = $slideshowPaginationType !== 'none' ? $slideshowPaginationType : ($slideshowNav == 1 ? '' : 'none');
					?>
					<div class="slide-controller <?php echo $controllerClass ?> <?php if($slideTrigger !=="title") { ?>zenrelative<?php } else { echo $params->get('slideshowTitleTheme', 'none'); }?> zenlast <?php if($slideTrigger !=="none" || $slideCount) {?>zenpadding<?php } ?>">
						<div class="slidenav<?php echo $slideTrigger ?> slidenav<?php echo $moduleID ?>">
						<?php $numMB = sizeof($list);
							if($numMB > 1) { ?>

								<ul class="slidenav <?php echo $params->get('slideshowPaginationPos', 'zenleft'); ?>">
									<?php foreach($list as $key => $item) :
										echo '<li>';
										echo '<span>';
										echo $item->trigger;
										echo '</span>';
										echo '</li>';
										endforeach; ?>
										<?php if($slideTrigger == "title"){ ?>
										<div class="zenpages">
												<div id="paginationinner<?php echo $moduleID ?>"></div>
										</div>
										<?php } ?>
								</ul>
						<?php } ?>
						</div>
						<?php if($slideCount) {?>
						<div class="slidecount">
							<span class="current-slide"></span>
							<span class="slide-count-sep"><?php echo $slideCountSep ?></span>
							<span class="total-slides"></span>
						</div>
						<?php } ?>
					</div>

					<div class="clear"></div>
				<?php } ?>
		</div>
	</div>
	<div class="clear"></div>
	<?php // Scripts if cache is on
	if($scripts) {
		//if(!$zgf) {
			if($cache){ ?>

				<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/zentools.css" type="text/css" />
				<?php if(!ZenToolsHelper::isZenGridFrameworkInstalled()) { ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/grid.css" type="text/css" />
				<?php } ?>

				<?php if ($params->get('loadJquery', '1') == 1 && !defined('jQueryLoaded')) : ?>
					<?php define('jQueryLoaded', true); ?>
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/jquery-1.8.3.min.js"></script>
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/jquery-noconflict.js"></script>
				<?php endif; ?>

				<?php if ($link == 1){ ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/lightbox/jackbox.min.css" type="text/css" />
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/lightbox/jackbox-packed.min.js"></script>

					<?php if(($browser == 'msie') && ($major == 8))
					{ ?>
						<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/lightbox/jackbox-ie8.css" type="text/css" />
					<?php } ?>

					<?php if(($browser == 'msie') && ($major == 9))
					{ ?>
						<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/lightbox/jackbox-ie9.css" type="text/css" />
					<?php }
				}

				if($layout == "slideshow") { ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/slideshow/slideshow-core.css" type="text/css" />
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/slideshow/slideshow-<?php echo $slideshowTheme ?>.css" type="text/css" />

					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/slideshow/jquery.flexslider.min.js"></script>
				<?php }

				if($layout == "masonry") { ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/masonry.css" type="text/css" />
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/masonry/jquery.masonry.js"></script>
				<?php }


				if($layout == "pagination" || $slideTrigger =="title") { ?>
						<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/pagination.css" type="text/css" />
						<script type="text/javascript" src="<?php echo $mediaURI; ?>js/pagination/jPages.js"></script>
				<?php }

				if($layout == "accordion") { ?>
						<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/accordion.css" type="text/css" />
				<?php }

				if($layout == "carousel") { ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/elastislide.css" type="text/css" />
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/carousel/jquery.easing.1.3-min.js"></script>
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/carousel/jquery.elastislide.min.js"></script>
				<?php }

				if($categoryFilter && ($layout == "grid" or $layout =="list")) { ?>
					<link rel="stylesheet" href="<?php echo $mediaURI; ?>css/masonry.css" type="text/css" />
					<script type="text/javascript" src="<?php echo $mediaURI; ?>js/filter/jquery.isotope.min.js"></script>
					<!--[if IE 8]>
						<script type="text/javascript" src="<?php echo $mediaURI; ?>js/filter/jquery.isotope.ie8.min.js"></script>
					<![endif]-->
				<?php }
			}
			//}
	} ?>

	<?php if($tweet) {?>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<?php }

	if($layout == "slideshow") {
		$slideshowAuto = $params->get('slideshowAuto');
		$slideshowNav = $params->get('slideshowNav');
		$slideshowLoop = $params->get('slideshowLoop');
		$slideshowPagination = $params->get('slideshowPagination');
		$slideshowPause = $params->get('slideshowPause');
		$slideshowSpeed = $params->get('slideshowSpeed');
		$slideshowDuration = $params->get('slideshowDuration');
		$transition = $params->get('transition','slide');
		$pauseText = $params->get('pauseText','');
		$playText = $params->get('playText','');
		$overlayAnimation = $params->get('overlayAnimation','');
		?>


		<script type="text/javascript" charset="utf-8">
		  jQuery(window).load(function() {
			jQuery('#zentools<?php echo $moduleID ?>').flexslider({
				animation: "<?php echo $transition ?>",
				slideDirection: "horizontal",
				smoothHeight: false,
				prevText: "<i class='icon-left-circle'></i><span></span>",
				nextText: "<i class='icon-right-circle'></i><span></span>",
				manualControls: ".slidenav<?php echo $moduleID ?> ul li",
				<?php if($slideshowAuto) { ?>
					slideshow: true,
					slideshowSpeed: <?php echo $slideshowSpeed ?>,
					animationSpeed: <?php echo $slideshowDuration ?>,
				<?php }
				else { ?>
					slideshow: false,
				<?php }
				if($slideshowNav) { ?>
					directionNav: true,
				<?php } else { ?>
					directionNav: false,
				<?php }
				if($slideshowPagination !=="none") { ?>
					controlNav: true,
				<?php } else { ?>
					controlNav: false,
				<?php } ?>
					keyboardNav: true,
					mousewheel: false,
				<?php if($slideshowPause) { ?>
					pausePlay: true,
					pauseText: "<i class='icon-pause'></i><span><?php echo $pauseText; ?></span>",
					playText: "<i class='icon-play'></i><span><?php echo $playText; ?></span>",
				<?php } ?>
				<?php if($slideshowPagination) { ?>
					randomize: false,
				<?php } ?>
					slideToStart: 0,
				<?php if($slideshowLoop) { ?>
					animationLoop: true,
				<?php } else { ?>
					animationLoop: false,
				<?php }?>
					pauseOnAction: true,
					pauseOnHover:false,
					controlsContainer: '#zentools<?php echo $moduleID ?> .slide-controller',

					start: function(slider){
						jQuery('#zentools<?php echo $moduleID ?> .current-slide').text(slider.currentSlide + 1);
						jQuery('#zentools<?php echo $moduleID ?> .total-slides').text(slider.count);
						jQuery('#zentools<?php echo $moduleID ?> .slidecount').fadeIn();

					},

					<?php if($overlayAnimation or $slideTrigger  == "tabs") { ?>
					before: function(slider){

						<?php if($overlayAnimation) { ?>
							jQuery("#zentools<?php echo $moduleID ?> .allitems.text").slideUp();
						<?php } ?>

					},
					<?php } ?>
					after: function(slider){
						<?php if($overlayAnimation) { ?>
							jQuery("#zentools<?php echo $moduleID ?> .allitems.text").slideDown();
						<?php } ?>
							jQuery('#zentools<?php echo $moduleID ?> .current-slide').text(slider.currentSlide + 1);

							<?php if($slideTrigger  == "title") { ?>
							if(slider.currentSlide == 0) {
								jQuery("#paginationinner<?php echo $moduleID ?>").jPages(1);
							}
							<?php } ?>
					}
				});

				<?php if($overlayAnimation) { ?>
					jQuery("#zentools<?php echo $moduleID ?> .flex-control-nav a,#zentools<?php echo $moduleID ?> .flex-direction-nav a,.slidenav<?php echo $moduleID ?> ul li").click(function() {
						jQuery("#zentools<?php echo $moduleID ?> .allitems.text").slideUp();
					});
				<?php } ?>


	  });
		</script>

		<?php if($slideTrigger  == "title") { ?>
		<script type="text/javascript">
		jQuery(function() {

			jQuery("#paginationinner<?php echo $moduleID ?>").jPages({
				containerID : "itemContainer",
				perPage      : <?php echo $itemsperpage ?>,
				first       : false,
				previous    : false,
				next        : false,

				last        : false

			});

		});
		</script>
		<?php } ?>
	<?php } ?>

	<?php if($layout == "masonry") { ?>
	<script type="text/javascript">

		jQuery(document).ready(function(){
			var jQuerycontainer = jQuery('#zentoolslist<?php echo $moduleID ?>');

			<?php if($masonryWidths) { ?>
			jQuerycontainer.imagesLoaded( function(){
				jQuerycontainer.masonry({
					itemSelector: '#zentoolslist<?php echo $moduleID ?> li',
					isAnimated: true,
					isResizable: true,
					columnWidth: <?php echo $masonryColumnWidth ?>,
					gutterWidth: <?php echo $masonryGutter ?>
				});
			});
			<?php }
			else { ?>
				jQuerycontainer.imagesLoaded( function(){
					jQuerycontainer.masonry({
						itemSelector: '#zentoolslist<?php echo $moduleID ?> li',
						isResizable: true,
						isAnimated: true,
						columnWidth: (jQuerycontainer.width() - 15) / <?php echo $masonryColWidths; ?>
					});
				});
				jQuery(window).resize(function(){
					var windowsize = jQuery(window).width();

					if(windowsize > <?php echo $browserThreshold; ?>) {
						jQuery("#zentoolslist<?php echo $moduleID ?> li");
							jQuerycontainer.masonry({
								isResizable: true,
								isAnimated: true,
								columnWidth: jQuerycontainer.width() / <?php echo $masonryColWidths; ?>
						});
					}
					else {
						jQuery("#zentoolslist<?php echo $moduleID ?> li");
								jQuerycontainer.masonry({
								isResizable: true,
								isAnimated: true,
								columnWidth: jQuerycontainer.width() / 1
						});
					}
				});
			<?php } ?>

			jQuery('#jbToggle').click(function(){
				// We use this as a hook for templates to trigger and retrigger the masonry layout
				setTimeout( function() {
					var jQuerycontainer = jQuery('#zentoolslist<?php echo $moduleID ?>');

					jQuerycontainer.masonry({
						itemSelector: '#zentools<?php echo $moduleID ?> li',
						isResizable: true,
						isAnimated: true,
						columnWidth: jQuerycontainer.width() / <?php echo $masonryColWidths; ?>
						});
					}, 500 );
				});
		});
	</script>
	<?php }
	if($categoryFilter && ($layout == "grid" or $layout =="list")) {?>

	<script type="text/javascript">

		// Code to change titles of filters to dots.
		Response.action( function () {
            if(Response.band(0, <?php echo $filterwidth?>)) {
            	jQuery("ul#filters.module<?php echo $moduleID ?>").addClass("sml-filter");
            	jQuery("ul#filters.module<?php echo $moduleID ?> li span").addClass("icon-circle");
            	jQuery("ul#filters.module<?php echo $moduleID ?> li.showallID span").removeClass("icon-circle").addClass("icon-th-list");
            }
            else {
            	jQuery("ul#filters.module<?php echo $moduleID ?>").removeClass("sml-filter");
            	jQuery("ul#filters.module<?php echo $moduleID ?> li span").removeClass("icon-circle");
            	jQuery("ul#filters.module<?php echo $moduleID ?> li.showallID span").removeClass("icon-th-list");
            }
		});



		var reLayouTriggered = false;
		jQuery(document).ready(function(){

			// Strips duplicates form the list
			var seen = {};
			jQuery('#filters.module<?php echo $moduleID ?> li').each(function() {
				var txt = jQuery(this).text();
				if (seen[txt])
					jQuery(this).remove();
				else
					seen[txt] = true;
			});

			// cache container
			var jQuerycontainer = jQuery('#zentoolslist<?php echo $moduleID ?>');
			// initialize isotope
			jQuerycontainer.imagesLoaded( function(){
				var options = {animationEngine: "best-available"};

				<?php if ($filterstart > 0) : ?>
					options.filter = '.<?php echo $filterstartAlias ?>';
				<?php endif; ?>

				<?php if ($layout == "list") : ?>
					options.layoutMode = 'straightDown';
				<?php elseif ($layout == "grid") : ?>
					options.masonry = { columnWidth: jQuerycontainer.width() / <?php echo $imagesPerRow ?> };
				<?php endif; ?>

				jQuerycontainer.isotope(options, function () {
					// Force a relayout after complete load
					if (!reLayouTriggered)
					{
						var t = setInterval(function ()
						{
							var instance = jQuerycontainer.data('isotope');
							if (instance && !reLayouTriggered)
							{
								defaultFilter = '*';
								<?php if ($filterstart > 0) : ?>
									defaultFilter = '.<?php echo $filterstartAlias; ?>';
								<?php endif; ?>
								// jQuerycontainer.isotope('reLayout');
								jQuerycontainer.isotope({filter: defaultFilter});
								reLayouTriggered = true;
								clearTimeout(t);
							}
						}, 100);
					}
				});

				// update columnWidth on window resize
				jQuery(window).smartresize(function(){
					jQuerycontainer.isotope({
						masonry: { columnWidth: jQuerycontainer.width() / <?php echo $imagesPerRow ?> }
					});
				});
			});

			// filter items when filter link is clicked
			jQuery('#filters.module<?php echo $moduleID ?> a').click(function(){
				jQuery('#zentoolslist<?php echo $moduleID ?> li').css({'height' : 'auto','display':'block'});
				var selector = jQuery(this).attr('data-filter');
				jQuerycontainer.isotope({
					filter: selector,
					masonry: { columnWidth: jQuerycontainer.width() / <?php echo $imagesPerRow ?> }
					//layoutMode: 'fitRows'
				});

				jQuery('#filters.module<?php echo $moduleID ?> a').removeClass('active');
				jQuery(this).toggleClass('active');

				return false;
			});

			// Filter tabs hidden by css and then shown after load
			jQuery('#filters.module<?php echo $moduleID ?> li a').show();

			jQuery('#filters.module<?php echo $moduleID ?> li.<?php echo $filterstartAlias ?>ID a').addClass("active");

			// Force a reLayout to fix unorganized content right after load
			//setTimeout(function () {jQuery('#zentoolslist<?php echo $moduleID ?>').isotope('reLayout')}, 150);
		});
	</script>
	<?php } ?>


	<?php if($layout == "accordion") { ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#zentools<?php echo $moduleID ?> .firstitem').off('click.accordion').on('click.accordion', function() {

					jQuery('#zentools<?php echo $moduleID ?> .firstitem').removeClass('open');
					jQuery('#zentools<?php echo $moduleID ?> .allitems').slideUp(300);

					if(jQuery(this).next().is(':hidden') == true) {
						jQuery(this).addClass('open');
						jQuery(this).next().animate({
							height: 'toggle'
						});
					 }
				});

				jQuery('#zentools<?php echo $moduleID ?> .allitems').hide();

				<?php if($params->get('accordionOpen')) { ?>
					jQuery('#zentools<?php echo $moduleID ?> li:first-child .firstitem').addClass('open');
					jQuery('#zentools<?php echo $moduleID ?> li:first-child .allitems').slideDown();
				<?php } ?>
			});
		</script>
	<?php } ?>


	<?php if($layout == "carousel") {

	$minItems= $params->get('minItems');
	$carouselSpeed= $params->get('carouselSpeed');
	$imageW= $params->get('imageW');

	?>
	<script type="text/javascript">
		jQuery(function() {
			jQuery('#zentools<?php echo $moduleID ?>').elastislide({
					minItems	: <?php echo $minItems; ?>,
					speed		: <?php echo $carouselSpeed; ?>,
					imageW		: <?php echo $imageW; ?>,
					margin: 0
			});

			setTimeout(function() {
				jQuery('#zentools<?php echo $moduleID ?>').elastislide('refresh');
			}, 250);
		});
	</script>
	<?php } ?>

	<?php if ($link == 1) {	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".jackbox[data-group]").jackBox("init", {
				useThumbTooltips: <?php echo ($modalThumbTip == "1") ? 'true' : 'false'; ?>,
				<?php if($contentSource !=="1"){?>useThumbs: false,<?php } ?>
				'': 0
			});

			jQuery(document).bind('keydown', 'left', function(event) {
				jQuery(".jackbox[data-group]").jackBox('previous');
			});

			jQuery(document).bind('keydown', 'right', function(event) {
				jQuery(".jackbox[data-group]").jackBox('next');
			});
		});

		</script>
	<?php } ?>

	<?php if ($link == 2 || $link == 3) { ?>
		<script type="text/javascript">
			jQuery(function() {
				jQuery('#zentoolslist<?php echo $moduleID; ?> a[data-behavior="content"], #zentoolslist<?php echo $moduleID; ?> a[data-behavior="external"]').unbind().on('click', function(e) {
					e.preventDefault();

					<?php if ($linktarget === '_blank') : ?>
						window.open(jQuery(this).attr('href'), '_blank');
					<?php else: ?>
						window.location = jQuery(this).attr('href');
					<?php endif; ?>

					return false;
				});
			});
		</script>
	<?php } ?>

	<?php if($imageFade) { 	?>
			<script type="text/javascript">
				jQuery('#zentools<?php echo $moduleID ?> img').fadeTo('fast', 1.0);
				jQuery('#zentools<?php echo $moduleID ?> img').hover(function(){
				jQuery(this).fadeTo('fast', 0.3);
					},function(){
						jQuery(this).fadeTo('fast', 1.0); // This should set the opacity back to 60% on mouseout
					});
			</script>
	<?php } ?>


	<?php if($params->get('layout') == "pagination") { ?>
	<script type="text/javascript">
	jQuery(function() {
		/* initiate plugin */
		jQuery("#paginationinner<?php echo $moduleID ?>").jPages({
			containerID  : "zentoolslist<?php echo $moduleID ?> li",
			perPage      : <?php echo $imagesPerRow ?>,
			startPage    : <?php echo $params->get('pagStartPage') ?>,
			startRange   : <?php echo $params->get('pagStartRange') ?>,
			midRange     : <?php echo $params->get('pagMidRange') ?>,
			endRange     : <?php echo $params->get('pagEndRange') ?>,
			previous     : "\u2190",
			next         : "\u2192",
		});
	});
	</script>
	<?php } ?>


	<?php // Zoom Hover
	if($params->get('imageFX' , 0)) { ?>




	<script type="text/javascript">
			jQuery(document).ready(function(){

				jQuery("#zentools<?php echo $moduleID ?> li img").hover(
					function() {
					      jQuery(this).transition({
					      	scale: 1.3

					       }).css({'z-index':'1000'});
					   },
					   function() {
					      jQuery(this).transition({
					      scale: 1,
					          delay: 1000
					      }).css({'z-index':'1'});
					   });
				});
	</script>

	<?php }
	// Animate Overlay
	$animateOverlay = $params->get('animateOverlay');
	$animateOverlayCarousel = $params->get('animateOverlayCarousel');

	if(($layout == "grid" && $animateOverlay) ||  ($layout == "carousel" && $animateOverlayCarousel)) { 	?>
		<script type="text/javascript">
		jQuery(document).ready(function(){


			var captionHeight = jQuery("#zentools<?php echo $moduleID ?> .allitems").innerHeight();

			jQuery("#zentools<?php echo $moduleID ?> .allitems").css({'bottom': '-' + captionHeight +'px','display': 'none'});

			jQuery('#zentools<?php echo $moduleID ?> li').hover(
				function(){
					jQuery(this).find('.allitems').show().animate({bottom:"0"}, 400);
				},
				function(){
					jQuery(this).find('.allitems').animate({bottom:'-' + captionHeight}, 100).fadeOut('slow');
				}
			);

		});
		</script>
		<?php }
		// Responsive Images just some standard breakpoints at this stage
		if($responsiveimages) {	?>
		<script type="text/javascript">
				Response.create({ mode: 'src',  prefix: 'src', breakpoints: [0,320,481,769,1025,1281] });
		</script>


		<?php } if($params->get('animateMoreOverlay')) {?>
		<script type="text/javascript">
		jQuery(document).ready(function(){

			var more = "#zentools<?php echo $moduleID ?> .zenmore";

			jQuery(more).hide().addClass("overlaymore");

			jQuery("#zentools<?php echo $moduleID ?> li").hover(
				function() {
				 jQuery(this).find(".zenmore").fadeIn();
			},
			function(){
				 jQuery(this).find(".zenmore").fadeOut();
			});

		});
		</script>
	<?php } ?>
<?php } ?>
