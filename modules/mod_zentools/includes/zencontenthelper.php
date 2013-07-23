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
require_once JPATH_SITE . '/components/com_content/models/article.php';
require_once JPATH_SITE . '/components/com_content/helpers/route.php';

// Joomla content
class ModZentoolsHelper
{
	function getList($params, $id, $handleImages = false)
	{
		global $mainframe;

		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		$count		= (int) $params->get('count', 5);
		$catid		= $params->get('catid');
		$artids		= $params->get('artids');
		$joomlaContentSource = $params->get('joomlaContentSource');
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);
		$link = $params->get('link');
		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('show_noauth');
		$nullDate	= $db->getNullDate();
		$date = JFactory::getDate();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$now = $date->toMySQL();
		}
		else
		{
			$now = $date->toSql();
		}

		// Word Count
		$wordCount	= $params->get( 'wordCount','');
		$titlewordCount	= $params->get( 'titlewordCount','');
		$strip_tags = $params->get('strip_tags',0);
		$titleSuffix = $params->get('titleSuffix','');
		$tags	= $params->get( 'allowed_tags','');

		// Image Size and container, remove px if user entered
		$resizeImage = $params->get('resizeImage',1);
		$responsiveimages = $params->get('responsiveimages');
		$option = $params->get( 'option', 'crop');
		$img_width = str_replace('px', '', $params->get( 'image_width','170'));
		$img_height = str_replace('px', '', $params->get( 'image_height','85'));

		$thumb_width = str_replace('px', '', $params->get( 'thumb_width','20'));
		$thumb_height = str_replace('px', '', $params->get( 'thumb_height','20'));

		// Other Params
		$dateFormat		= $params->get('dateFormat', 'j M, y');
		$translateDate		= $params->get('translateDate', '0');
		$dateString		= $params->get('dateString', 'DATE_FORMAT_LC3');
		$showCategory = $params->get('showCategory',1);
		$textsuffix	= $params->get( 'textsuffix','');

		// Lightbox
		$modalVideo = $params->get('modalVideo');
		$modalText = $params->get('modalText');
		$modalTitle = $params->get('modalTitle');
		$modalMore = $params->get('modalMore');

				$where = 'a.state = 1'
					. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
					. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
					;
				// User Filter
				switch ($params->get( 'user_id' ))
				{
					case 'by_me':
						$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
						break;
					case 'not_me':
						$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
						break;
				}
				// Ordering
				$orderby = $params->get( 'ordering');

				switch ($orderby)
				{
					case 'random':
						$ordering = 'rand()';
						break;
					case 'm_dsc':
						$ordering		= 'a.modified DESC, a.created DESC';
						break;
					case 'date':
						$ordering = 'a.created';
						break;
					case 'rdate':
						$ordering = 'a.created DESC';
						break;
					case 'alpha':
						$ordering = 'a.title';
						break;
					case 'ralpha':
						$ordering = 'a.title DESC';
						break;
					case 'hits':
						$ordering = 'a.hits DESC';
						break;
					case 'rhits':
						$ordering = 'a.hits ASC';
						break;
					case 'order':
					default:
						$ordering = 'a.ordering';
						break;
				}

				$frontCondition1 = ($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '');

				$frontCondition2 = ($show_front == '0' ? ' AND f.content_id IS NULL ' : '');


					//we get articles in the selected categories
					$catCondition = '';

					if (($catid)&&($joomlaContentSource=='categories'))

					{

						if( is_array( $catid ) ) {

							$catCondition = ' AND (cc.id IN ( ' . implode( ',', $catid ) . ') )';

						} else {

							$catCondition = ' AND (cc.id = '.$catid.')';

						}
					}


					//we get the selected articles
					$artCondition = '';

					if (($artids)&&($joomlaContentSource=='items'))

					{

						if( is_array( $artids ) ) {

							$artCondition = ' AND (a.id IN ( ' . implode( ',', $artids ) . ') )';

						} else {

							$artCondition = ' AND (a.id = '.$artids.')';

						}

					}

				//Articles query
				$query = 'SELECT a.*, cc.title AS catname, cc.alias AS category_alias, s.title AS sectitle, ' .
					' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
					' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
					' FROM #__content AS a' .
					$frontCondition1.

					' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
					' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
					' WHERE '. $where .' AND s.id > 0' .
					($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
					$artCondition.
					$catCondition.
					$frontCondition2.
					' AND s.published = 1' .
					' AND cc.published = 1' .
					' ORDER BY '. $ordering;
				$db->setQuery($query, 0, $count);
				$rows = $db->loadObjectList();

				$i		= 0;
				$lists	= array();
				foreach ( $rows as $row )
				{


			/**
			*
			* Joomla 1.5 Image Logic
			*
			**/

			if ($handleImages) {

				$imghtml= $row->introtext;
				$imghtml .= "alt='...' title='...' />";
				$pattern = '/<img[^>]+src[\\s=\'"]';
				$pattern .= '+([^"\'>\\s]+)/is';
				if(preg_match(
				$pattern,
				$imghtml,
				$match)) {
				$lists[$i]->image = "$match[1]";}
				else {$lists[$i]->image = false;}


				/**
				*
				* Joomla 1.5 Resize Images
				*
				**/
				$lists[$i]->thumb ="";
				if($lists[$i]->image) {

					$lists[$i]->image = ZenToolsHelper::handleRemoteImage($lists[$i]->image);

					$lists[$i]->modalimage = $lists[$i]->image;
					$lists[$i]->imageOriginal = $lists[$i]->image;

					if ($resizeImage) {
						$lists[$i]->image = ZenToolsHelper::handleRemoteImage($lists[$i]->image);
						$lists[$i]->image =  ZenToolsHelper::getResizedImage($lists[$i]->image, $img_width, $img_height, $option);

							if($responsiveimages) {
								$lists[$i]->imageTiny = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width /5), ($img_height / 5), $option);
								$lists[$i]->imageXSmall = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width /3), ($img_height / 3), $option);
								$lists[$i]->imageSmall = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width /2), ($img_height / 2), $option);
								$lists[$i]->imageMedium = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width /1.5), ($img_height / 1.5), $option);
								$lists[$i]->imageDefault = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width), ($img_height), $option);

								$lists[$i]->imageLarge = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width *1.25), ($img_height * 1.25), $option);
								if($lists[$i]->imageLarge == $lists[$i]->image) {
									$lists[$i]->imageLarge = $lists[$i]->imageDefault;
								}

								$lists[$i]->imageXLarge = ZenToolsHelper::getResizedImage($lists[$i]->image, ($img_width *1.75), ($img_height * 1.75), $option);
								if($lists[$i]->imageXLarge == $lists[$i]->image) {
									$lists[$i]->imageXLarge = $lists[$i]->imageDefault;
								}
							}
					}
					else {
						$lists[$i]->image = $lists[$i]->image;

						if($responsiveimages) {
							$lists[$i]->image = ZenToolsHelper::handleRemoteImage($lists[$i]->image);

							// Get the width and height of the original image
							if($lists[$i]->image !=="") {
								list($width, $height) = getimagesize($lists[$i]->image);
							}

							$lists[$i]->imageTiny = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width /5), ($height / 5), 'exact');
							$lists[$i]->imageXSmall = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width /3), ($height / 3), 'exact');
							$lists[$i]->imageSmall = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width /2), ($height / 2), 'exact');
							$lists[$i]->imageMedium = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width /1.5), ($height / 1.5), 'exact');
							$lists[$i]->imageDefault = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width), ($height), 'exact');
							$lists[$i]->imageLarge = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width *1.25), ($height * 1.25), $option);
							if($lists[$i]->imageLarge == $lists[$i]->image) {
								$lists[$i]->imageLarge = $lists[$i]->imageDefault;
							}

							$lists[$i]->imageXLarge = ZenToolsHelper::getResizedImage($lists[$i]->image, ($width *1.75), ($height * 1.75), $option);
							if($lists[$i]->imageXLarge == $lists[$i]->image) {
								$lists[$i]->imageXLarge = $lists[$i]->imageDefault;
							}
						}
					}

					$lists[$i]->thumb = ZenToolsHelper::getResizedImage($lists[$i]->image, $thumb_width, $thumb_height, $option);
				}
			}
			else
			{
				$lists[$i]->image = '';
			}



			/**
			*
			* Joomla 1.5 Title
			*
			**/
			$titletext = htmlspecialchars( $row->title );
			$lists[$i]->modaltitle = htmlspecialchars( $row->title );
			$lists[$i]->title = $titlewordCount ? ZenToolsHelper::truncate($titletext, $titlewordCount, $titleSuffix) : $titletext;


			/**
			*
			* Joomla 1.5 Link Behaviour
			*
			**/


			if($row->access <= $aid)
			{
				if($link == 0) {
					$lists[$i]->link = '';
					$lists[$i]->closelink = '';
				}
				elseif($link == 1) {

					if($modalMore or $modalTitle or $modalText) {
						$lists[$i]->link = 'href="#data'.$row->id.'"';
					}
					else {
						$lists[$i]->link  = 'href="'.$lists[$i]->modalimage.'" title="'.$lists[$i]->modaltitle.'"';
					}

					$lists[$i]->closelink = '</a>';
					$lists[$i]->lightboxmore = ''.JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid)).'';
				}
				else {
					$lists[$i]->link = 'href="'.JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid)).'"';
					$lists[$i]->closelink = '</a>';
				}



			/**
			*
			* Joomla 1.5 Category Name and Link
			*
			**/
			$lists[$i]->catlink = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($row->catslug,$row->sectionid).'&layout=blog').'">';

			} else {
				$lists[$i]->link = '<a href="'.JRoute::_('index.php?option=com_user&view=login').'">';

			}

			if($showCategory)$lists[$i]->category = $row->catname;

			/**
			*
			* Joomla 1.5 Metakeywords
			*
			**/

			$lists[$i]->metakey = $row->metakey;


			/**
			*
			* Joomla 1.5 Intro Text
			*
			**/

			if($strip_tags) {
				$introtext = $strip_tags ? ZenToolsHelper::_cleanIntrotext($row->introtext,$tags) : $item->introtext;
			}
			else {
				$introtext = $row->introtext;
			}
			$lists[$i]->text = $wordCount ? ZenToolsHelper::truncate($introtext, $wordCount, $textsuffix) : $tempintro;

			/**
			*
			* Joomla 1.5 Full Text
			*
			**/

			$lists[$i]->fulltext = $row->fulltext;
			$lists[$i]->modaltext = $row->introtext;

			$modalImage = $params->get('modalImage',0);
			if($modalImage) {
				$lists[$i]->modaltext = preg_replace('/<img(.*)>/i','',$lists[$i]->modaltext,1);
			}


			/**
			*
			* Joomla 1.5 Date
			*
			**/

			if (!$translateDate) {
				$lists[$i]->date = date($dateFormat,  (strtotime($row->created)));
			}
			else {
				$lists[$i]->date = JHTML::_('date', $row->created, JText::_(''.$dateString.''));
			}


			/**
			*
			* Joomla 1.5 IDs
			*
			**/

			$lists[$i]->id = $row->id;

			/**
			*
			* null
			*
			**/

			$lists[$i]->video = false;
			$lists[$i]->more = false;
			$lists[$i]->featured = 0;
			$lists[$i]->newlink = 0;
			$lists[$i]->category_alias = $row->category_alias;
			$i++;
		}

		return $lists;
	}


}
