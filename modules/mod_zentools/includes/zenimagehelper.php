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

require_once JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php';

class ModZentoolsImageHelper
{
	public static function getList($params, $id)
	{
		if (substr(JVERSION, 0, 3) >= '1.6') {
			$directory = $params->get('directory', '/images');
		}
		else {
			$directory = $params->get('directory', '/images/stories');
		}

		//Remove Slashes from directory
		$directory = ltrim($directory,'/');
		$directory = rtrim($directory,'/');
		$link = $params->get('link',1);
		$prefix = $params->get('prefix',0);
		$layout = $params->get('layout', 'content');

		// Image Size and container, remove px if user entered
		$responsiveimages = $params->get( 'responsiveimages','');
		$resizeImage = $params->get('resizeImage',1);
		$option = $params->get( 'option', 'crop');
		$img_width = str_replace('px', '', $params->get( 'image_width','170'));
		$img_height = str_replace('px', '', $params->get( 'image_height','85'));
		$thumb_width = str_replace('px', '', $params->get( 'thumb_width','20'));
		$thumb_height = str_replace('px', '', $params->get( 'thumb_height','20'));
		$titlewordCount	= $params->get( 'titlewordCount','');
		$wordCount	= $params->get( 'wordCount','');
		$separator	= $params->get( 'separator','+');


		// Image Count
		$count = (int) $params->get('count');

		$titleSuffix = $params->get( 'titleSuffix','');
		// list of filetypes you want to show
		$allowed_types = '\.png$|\.gif$|\.jpg$|\.$';

		// list of filetypes you want to exclude
		$exclude = array('.svn', 'CVS','.DS_Store','__MACOSX');
		if ((strpos(JPATH_ROOT, '/'))===FALSE){//windows
			$directory = str_replace('/', '\\', $directory);
			$path = JPATH_ROOT.'\\'.$directory;
		} else {//linux
			$directory = str_replace('\\', '/', $directory);
			$path = JPATH_ROOT.'/'.$directory;
		}

		//get list of images from dir
		$images = JFolder::files($path, $allowed_types, false, true, $exclude);

		if (is_array($images))
		{
			// Randomize image array
			if ($params->get('random_image'))
			{
				list($usec, $sec) = explode(' ', microtime());
				mt_srand((float) $sec + ((float) $usec * 100000));
				shuffle($images);
			}

			//we create the array
			$items = array();
			$i     = 0;

			//create an array of items for template
			foreach ($images as $image)
			{
				// Check image count
				if ($i >= $count) break;

				//windows or linux, find local
				$local_image = str_replace('\\', '/', $image);
				$pos = strpos($local_image, '/images');
				$local_image = substr_replace($local_image, '', 0, $pos);
				// remove file path
				$file = JFile::getName($image);
				// remove file extension
				$name = JFile::stripExt($file);
				// remove root path & File name
				$names = explode('-', $name);

				// Item Title
				if(!$prefix) {
					$title = (!empty($names[0]))? $names[0] : '';

					// Item Title
					$text = (!empty($names[1]))? $names[1] : '';

					if($link == 2) {
						$articleid  = (!empty($names[2]))? $names[2] : '';
						$itemid  = (!empty($names[3]))? $names[3] : '';
					}
				}
				else {
					$title = (!empty($names[1]))? $names[1] : '';

					// Item Title
					$text = (!empty($names[2]))? $names[2] : '';

					if($link == 2) {
						$articleid  = (!empty($names[3]))? $names[3] : '';
						$itemid  = (!empty($names[4]))? $names[4] : '';
					}
				}


				$new_item = new stdClass();

				$basePath = JURI::base(true);

				// if (empty($basePath)) {
				// 	$basePath = rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '/\\');
				// }

				$new_image = $basePath.$local_image;

				if ($resizeImage) {
					if(!$responsiveimages) {
						$image =  ZenToolsHelper::getResizedImage($new_image, $img_width, $img_height, $option);
					}
					else {
						$image =  ZenToolsHelper::getResizedImage($new_image, $img_width, $img_height, $option);
						$new_item->imageTiny = ZenToolsHelper::getResizedImage($new_image, ($img_width /5), ($img_height / 5), $option);
						$new_item->imageXSmall = ZenToolsHelper::getResizedImage($new_image, ($img_width /3), ($img_height / 3), $option);
						$new_item->imageSmall = ZenToolsHelper::getResizedImage($new_image, ($img_width /2), ($img_height / 2), $option);
						$new_item->imageMedium = ZenToolsHelper::getResizedImage($new_image, ($img_width /1.25), ($img_height / 1.25), $option);
						$new_item->imageDefault = ZenToolsHelper::getResizedImage($new_image, $img_width, $img_height, $option);

						$new_item->imageLarge = ZenToolsHelper::getResizedImage($new_image, ($img_width *1.25), ($img_height * 1.25), $option);
						if($new_item->imageLarge == $new_image) {
							$new_item->imageLarge = $new_item->imageDefault;
						}
						$new_item->imageXLarge = ZenToolsHelper::getResizedImage($new_image, ($img_width *1.75), ($img_height * 1.75), $option);

						if($new_item->imageXLarge == $new_image) {
							$new_item->imageXLarge = $new_item->imageDefault;
						}
					}
				}
				else {
					$image = $new_image;

					if($responsiveimages) {

						$option ='exact';
						list($width, $height) = getimagesize(JPATH_ROOT.$local_image);

						$new_item->imageTiny = ZenToolsHelper::getResizedImage($new_image, ($width /5), ($height / 5), $option);
						$new_item->imageXSmall = ZenToolsHelper::getResizedImage($new_image, ($width /3), ($height / 3), $option);
						$new_item->imageSmall = ZenToolsHelper::getResizedImage($new_image, ($width /2), ($height / 2), $option);
						$new_item->imageMedium = ZenToolsHelper::getResizedImage($new_image, ($width /1.5), ($height / 1.5), $option);
						$new_item->imageDefault = ZenToolsHelper::getResizedImage($new_image, ($width), ($height), $option);
						$new_item->imageLarge = ZenToolsHelper::getResizedImage($new_image, ($width *1.25), ($height * 1.25), $option);
						if($new_item->imageLarge == $new_image) {
							$new_item->imageLarge = $new_item->imageDefault;
						}
						$new_item->imageXLarge = ZenToolsHelper::getResizedImage($new_image, ($width *1.75), ($height * 1.75), $option);

						if($new_item->imageXLarge == $new_image) {
							$new_item->imageXLarge = $new_item->imageDefault;
						}
					}
				}

				$new_item->modalimage = $new_image;
				$new_item->imageOriginal = $new_image;
				if($layout == "slideshow") {
					$new_item->thumb = str_replace(JPATH_SITE, '', ZenToolsResizeImageHelper::getResizedImage($new_image, $thumb_width, $thumb_height,  $option));
				}
				// Output options for the gallery
				$image = (string)$image;
				$imageBaseName = basename($image);
				$new_item->image = str_replace(JPATH_SITE, '', rtrim($image, $imageBaseName).urlencode($imageBaseName));

				// Item Title$titletext
				$titletext = str_replace(''.$separator.'', ' ', ''.$title.'');
				$new_item->title = $titlewordCount ? ZenToolsHelper::truncate($titletext, $titlewordCount, $titleSuffix) : $titletext;
				$new_item->modaltitle = $titlewordCount ? ZenToolsHelper::truncate($titletext, $titlewordCount, $titleSuffix) : $titletext;


				// Item Description
				$text = str_replace(''.$separator.'',' ', ''.$text.'');
				$new_item->text = $wordCount ? ZenToolsHelper::truncate($text, $wordCount, $titleSuffix) : $text;

				// Link Behaviour
				if(!$link) {
					$lightbox = '';
					$openlink = '';
					$closelink = '';
				}
				elseif($link == 1){
					$lightbox = '';
					$openlink ='href="'.JURI::base(true).$local_image.'" '.$lightbox.' title="'.$titletext.' '.$text.'"';
					$closelink = '</a>';
				}
				elseif($link == 2) {
					$lightbox = '';
					$openlink ='href="index.php?option=com_content&amp;view=article&id='.$articleid.'&amp;Itemid='.$itemid.'"';
					$closelink = '</a>';
				}
				elseif($link == 3) {
					$lightbox = '';
					$openlink = null;
					$closelink = '</a>';
				}


				$new_item->link = ''.$openlink.'';
				$new_item->closelink = ''.$closelink.'';
				$new_item->date = false;
				$new_item->category = false;
				$new_item->catlink = false;
				$new_item->featured = 0;
				$new_item->newlink = 0;
				$new_item->$id = false;
				$new_item->video =false;
				$items[] = $new_item;

				$i++;
			}

			return $items;
		}
		else
		{
			return array();
		}
	}
}
