<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.10.5
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once

// Import the file / foldersystem
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

//Check for browser class
if(!class_exists('JBrowser')){
	jimport( 'joomla.environment.browser' );
}

// Include the image helper just for J3.0
require_once dirname(__FILE__) . '/includes/imageresize.php';

if (substr(JVERSION, 0, 3) >= '1.6') {
	if ($app->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}
else {
	// Test to see if cache is enabled
	if ($mainframe->getCfg('caching')) {
		$cache = 1;
	}
	else {
		$cache = 0;
	}
}

// Globals
$document = JFactory::getDocument();

$mediaURI = JURI::base(true).'/media/mod_zentools/';

$moduleID = $module->id;

// Parameters
$responsiveimages = $params->get('responsiveimages');
$resizeImage = $params->get('resizeImage',1);
$layout = $params->get('layout', 'content');
$contentSource = $params->get('contentSource', '3');
$overlayGrid = $params->get('overlayGrid', 0);
$overlayCarousel = $params->get('overlayCarousel', 0);

// Helper Files
require_once dirname(__FILE__) . '/includes/zentoolshelper.php';

//get items from helper
if($contentSource=="1") {
	require_once dirname(__FILE__) . '/includes/zenimagehelper.php';
}
else if ($contentSource=="2") {
	if (substr(JVERSION, 0, 3) >= '1.6') {
		require_once dirname(__FILE__) . '/includes/zenj17contenthelper.php';
	}
	else {
		require_once dirname(__FILE__) . '/includes/zencontenthelper.php';
	}
}
else if ($contentSource=="3") {
	require_once dirname(__FILE__) . '/includes/zenk2helper.php';
}


// Parameters
$scripts = $params->get('scripts',1);
$itemImage = $params->get('itemImage',1);
$itemText = $params->get('itemText',1);
$itemTitle = $params->get('itemTitle',1);

$videosOnly = $params->get('videosOnly',1);
$imagesreplace = $params->get('imagesreplace',0);
$option= $params->get( 'option');


// Grid Variables
if($layout=="accordion") {
	$imagesPerRow = "1";
}
else {
	$imagesPerRow = $params->get( 'imagesPerRow','3');
}

// Category Filter
$categoryFilter = $params->get('categoryFilter');

if($contentSource == 1) {
	$categoryFilter = 0;
}

$zoomClass = 'flickrZoom';
$imageNumber = 0;
$startDiv = 0;

$layout = $params->get('layout',0);
$link = $params->get('link');
$modalTitle = $params->get('modalTitle',0);
$modalText = $params->get('modalText',0);
$modalImage = $params->get('modalImage',0);
$modalWidth = $params->get('modalWidth',0);
$modalHeight = $params->get('modalHeight',0);
$modalFooterTitle = (bool)$params->get('modalFooterTitle', 1);

if($imagesPerRow == "1") $gridclass = "twelve";
if($imagesPerRow == "2") $gridclass = "six";
if($imagesPerRow == "3") $gridclass = "four";
if($imagesPerRow == "4") $gridclass = "three";
if($imagesPerRow == "5") $gridclass = "5";
if($imagesPerRow == "6") $gridclass = "two";
if($imagesPerRow == "7") $gridclass = "7";
if($imagesPerRow == "8") $gridclass = "8";
if($imagesPerRow == "9") $gridclass = "9";
if($imagesPerRow == "10") $gridclass = "10";
if($imagesPerRow == "11") $gridclass = "11";
if($imagesPerRow == "12") $gridclass = "one";

$firstrow = $params->get('firstrow','date');
$secondrow = $params->get('secondrow','title');
$thirdrow = $params->get('thirdrow','image');
$fourthrow = $params->get('fourthrow','text');
$titleClass = $params->get('titleClass','h2');
$elements = $params->get('useditems');
$border = $params->get('imageBorder');
$image_height = str_replace('px', '', $params->get( 'image_height','20'));
$image_width = str_replace('px', '', $params->get( 'image_width','20'));
$thumb_width = str_replace('px', '', $params->get( 'thumb_width','20'));
$thumb_height = str_replace('px', '', $params->get( 'thumb_height','20'));
$slideTrigger = $params->get('slideshowPaginationType','thumb');
$slideshowTheme = $params->get('slideshowTheme');
$navigator = JBrowser::getInstance();
$browser = $navigator->getBrowser();
$major = $navigator->getMajor();

// Test to see if the slidehowtheme is old
$legacytheme = false;

if($slideshowTheme == "overlay" || $slideshowTheme == "overlayFrame" || $slideshowTheme == "flat") {
	$legacytheme = 1;
}


//include css styles in head
if($scripts) {
	//if(!$zgf) {
		if(!$cache){

			$document->addStyleSheet($mediaURI .'css/zentools.css');

			if(!ZenToolsHelper::isZenGridFrameworkInstalled())
			{
				$document->addStyleSheet($mediaURI .'css/grid.css');
			}

			if ($params->get('loadJquery', '0') == 1 && !defined('jQueryLoaded'))
			{
				define('jQueryLoaded', true);

				// Load J3.0 jquery if available, avoiding duplicated jQuery
				if (version_compare(JVERSION, '3.0', '>='))
				{
					JHtml::_('jquery.framework');
				}
				else
				{
					$document->addScript($mediaURI .'js/jquery/jquery-1.8.3.min.js');
					$document->addScript($mediaURI .'js/jquery/jquery-noconflict.js');
				}
			}

			if ($link == 1){
				if (version_compare(JVERSION, '3.0', '>='))
				{
					JHtml::_('bootstrap.framework');
				}

				$document->addStyleSheet($mediaURI .'css/lightbox/jackbox.min.css');
				$document->addScript($mediaURI . "js/lightbox/jackbox-packed.min.js");

				if(($browser == 'msie') && ($major == 8))
				{
					$document->addStyleSheet($mediaURI .'css/lightbox/jackbox-ie8.css');
				}

				if(($browser == 'msie') && ($major == 9))
				{
					$document->addStyleSheet($mediaURI .'css/lightbox/jackbox-ie9.css');
				}
			}

			if($layout == "pagination" || $slideTrigger =="title") {
				$document->addStyleSheet($mediaURI .'css/pagination.css');
				$document->addScript($mediaURI . "js/pagination/jPages.js");
			}

			if($layout == "slideshow") {
				$document->addScript($mediaURI .'js/slideshow/jquery.flexslider.min.js');
				$document->addStyleSheet($mediaURI .'css/slideshow/slideshow-core.css');
				$document->addStyleSheet($mediaURI .'css/slideshow/slideshow-'.$slideshowTheme.'.css');

				// Fix for IE 8 when image's column doesn't have 12 columns
				if(($browser == 'msie') && ($major == 8))
				{
					$usedItems = $params->get('useditems');

					// Has image
					if (substr_count($usedItems, 'image') > 0)
					{
						$imageColumn = 1;

						// Has more than 1 column
						if (substr_count($usedItems, 'column') > 0)
						{
							// Use more than 1 column, so get image column
							preg_match_all('/image|column([\d])/', $usedItems, $match);

							foreach ($match[1] as $value)
							{
								if (empty($value))
								{
									// image
									break;
								}
								else
								{
									$imageColumn = (int) $value;
								}
							}
						}

						// Apply fix just for column less than 12
						if ($params->get("col{$imageColumn}Width") !== 'twelve')
						{
							$document->addStyleDeclaration('.flexslider .slides img {max-width: none !important;}');
						}

						unset($imageColumn);
					}
					unset($usedItems);
				}
				unset($browser, $major, $navigator);
				}
			}

			if($layout == "masonry") {
				$document->addScript($mediaURI .'js/masonry/jquery.masonry.js');
				$document->addStyleSheet($mediaURI .'css/masonry.css');
			}


			if($layout == "accordion") {
				$document->addStyleSheet($mediaURI .'css/accordion.css');
			}

			if($layout == "carousel") {
				$document->addStyleSheet($mediaURI .'css/elastislide.css');
				$document->addScript($mediaURI .'js/carousel/jquery.elastislide.min.js');
			}

			if($categoryFilter && ($layout == "grid" or $layout =="list")) {
				$document->addScript($mediaURI .'js/filter/jquery.isotope.min.js');

				if(($browser == 'msie') && ($major == 8))
				{
					$document->addScript($mediaURI .'js/filter/jquery.isotope.ie8.min.js');
				}

				$document->addStyleSheet($mediaURI .'css/masonry.css');
			}

			if($responsiveimages or ($categoryFilter && ($layout == "grid" or $layout =="list"))) {
				$document->addScript($mediaURI .'js/responsive/response.min.js');
			}

			if(!$legacytheme || $layout == "carousel" || $layout == "accordion" || ($categoryFilter && ($layout == "grid" or $layout =="list"))) {
				$document->addStyleSheet($mediaURI .'css/fonticons.css');
			}

			if($params->get('imageFX', 0))  {
				$document->addScript($mediaURI . "js/effects/jquery.transit.js");
			}

			if ($params->get('imageGrayscaleFx', 0)) {
				$document->addScript($mediaURI . "js/effects/jquery.grayscale.js");

				$document->addScriptDeclaration('
					jQuery(window).load(function() {
						setTimeout(function() {
							jQuery(\'#zentools' . $moduleID . ' .zenimage img\').grayscale();
						}, 300);
					});
				');
			}

}

// Fix cropped images in Lightbox - moved from css because IE issues
?>
<!--[if !IE]>
<style type="text/css">
	.jackbox-modal img.jackbox-content {

		image-rendering: optimizeSpeed;
		width: inherit !important;
		height: inherit !important;
		max-width: inherit !important;
	}
</style>
<![endif]-->
<?php

// Check if the should resize/handle images
$handleImages = (bool)substr_count($params->get('useditems'), 'image');


//get items from helper
if($contentSource=="1") {
	$list = ModZentoolsImageHelper::getList($params, $moduleID);
}
else if ($contentSource=="2") {
	$list = ModZentoolsHelper::getList($params, $moduleID, $handleImages);
}
else if ($contentSource=="3") {
	$list = ModZentoolsK2Helper::getList($params, $moduleID);
}


if($layout =="twitter") {
	$count=0;
	require JModuleHelper::getLayoutPath('mod_zentools','twitter');
}

else {
	require JModuleHelper::getLayoutPath('mod_zentools','default');
}
