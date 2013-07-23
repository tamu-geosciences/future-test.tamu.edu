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

jimport('joomla.filesystem.path');
jimport('joomla.filesystem.file');

require_once JPath::clean(JPATH_SITE . '/modules/mod_zentools/includes/zentoolshelper.php');

class ModZentoolsK2Helper
{
	public static function getList($params, $id,$format = 'html')
	{
		require_once JPath::clean(JPATH_SITE . '/components/com_k2/helpers/route.php');
		require_once JPath::clean(JPATH_SITE . '/components/com_k2/helpers/utilities.php');

		// Get K2 version
		$k2Version = self::getK2Version();

		// Load K2 model
		$k2ModelPath = JPath::clean(JPATH_SITE . '/administrator/components/com_k2/models/model.php');
		$useK2Model = JFile::exists($k2ModelPath);
		if ($useK2Model && !class_exists('K2Model'))
		{
			require_once $k2ModelPath;
		}
		unset($k2ModelPath);

		jimport('joomla.filesystem.file');
		$mainframe = JFactory::getApplication();

		//K2 parameters
		$componentParams = JComponentHelper::getParams('com_k2');
		$limit = $params->get('count', 2);
		$cid = $params->get('category_id', NULL);
		$ordering = $params->get('itemsOrdering');
		$limitstart = JRequest::getInt('limitstart');
		$user = JFactory::getUser();
		$aid = $user->get('aid');
		$db = JFactory::getDBO();
		$jnow = JFactory::getDate();

		$authorisedLevels = null;

		if (version_compare(JVERSION, '1.6', '>')) {
			if (version_compare(JVERSION, '3.0', '<'))
			{
				$now = $jnow->toMySQL();
				$authorisedLevels = $user->authorisedLevels();
			}
			else
			{
				$now = $jnow->toSql();
				$authorisedLevels = $user->getAuthorisedViewLevels();
			}
		}

		else {
			// J1.5
			$now = $jnow->toMySQL();
		}

		$nullDate = $db->getNullDate();
		$itemid = $params->get('itemid','');


		// Text Params
		$wordCount	= $params->get( 'wordCount','');
		$titlewordCount	= $params->get( 'titlewordCount','');
		$strip_tags = $params->get('strip_tags',0);
		$titleSuffix = $params->get('titleSuffix','');
		$textsuffix = $params->get('textsuffix','');
		$tags = $params->get('allowed_tags','');
		$translateDate	= $params->get('translateDate', 0);
		$dateFormat	= $params->get('dateFormat', 'j M, y');
		$dateString	= $params->get('dateString', 'DATE_FORMAT_LC1');

		$link = $params->get('link');

		// Image Size and container, remove px if user entered
		$resizeImage = $params->get('resizeImage',1);
		$responsiveimages = $params->get('responsiveimages');
		$option = $params->get( 'option', 'crop');
		$img_width = str_replace('px', '', $params->get( 'image_width','170'));
		$img_height = str_replace('px', '', $params->get( 'image_height','85'));
		$thumb_width = str_replace('px', '', $params->get( 'thumb_width','20'));
		$thumb_height = str_replace('px', '', $params->get( 'thumb_height','20'));


		// Lightbox
		$modalVideo = $params->get('modalVideo');
		$modalText = $params->get('modalText');
		$modalTitle = $params->get('modalTitle');
		$modalMore = $params->get('modalMore');

		if ($k2Version && version_compare($k2Version, '2.6.0', '>='))
		{
			// public = 1
			$aid += 1;
		}

		$query = "SELECT i.*, c.name AS categoryname,c.id AS categoryid, c.alias AS categoryalias, c.alias AS category_alias, c.params AS categoryparams";

		if ($ordering == 'best')
			$query .= ", (r.rating_sum/r.rating_count) AS rating";

		$query .= " FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";

		if ($ordering == 'best')
			$query .= " LEFT JOIN #__k2_rating r ON r.itemID = i.id";

		$query .= " WHERE i.published = 1 AND i.trash = 0 AND c.published = 1 AND c.trash = 0";

		if (version_compare(JVERSION, '1.6', '<'))
		{
			$query .=" AND i.access<={$aid} ";
			$query .=" AND c.access<={$aid} ";
		}
		else
		{
			$query .= " AND i.access IN(".implode(',', $authorisedLevels).") ";
			$query .= " AND c.access IN(".implode(',', $authorisedLevels).") ";
		}

		$query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";

		$query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";


		// If content source is categories
		if($params->get('k2contentSource') == 'categories' && !is_null($cid)) {
			if (is_array($cid)) {
				if (!empty($cid[0]))
				{
					if ($params->get('getChildren')) {
						if ($useK2Model)
						{
							K2Model::addIncludePath(JPATH_SITE.'/components/com_k2/models');
							$ItemlistModel = K2Model::getInstance('Itemlist', 'K2Model');
							$categories = $ItemlistModel->getCategoryTree($cid);
						}
						else
						{
							require_once JPATH_SITE . '/components/com_k2/models/itemlist.php';
							$categories = K2ModelItemlist::getCategoryTree($cid);
						}

						$sql = @implode(',', $categories);
						$query .= " AND i.catid IN ({$sql})";

					} else {
						JArrayHelper::toInteger($cid);
						$query .= " AND i.catid IN(".implode(',', $cid).")";
					}
				}
			} elseif (!empty($cid)) {
				if ($params->get('getChildren')) {
					if ($useK2Model)
					{
						K2Model::addIncludePath(JPATH_SITE.'/components/com_k2/models');
						$ItemlistModel = K2Model::getInstance('Itemlist', 'K2Model');
						$categories = $ItemlistModel->getCategoryTree($cid);
					}
					else
					{
						require_once JPATH_SITE . '/components/com_k2/models/itemlist.php';
						$categories = K2ModelItemlist::getCategoryTree($cid);
					}

					$sql = @implode(',', $categories);
					$query .= " AND i.catid IN ({$sql})";
				} else {
					$query .= " AND i.catid=".(int)$cid;
				}

			}
		}

		// If content source is just items
		if($params->get('k2contentSource') == 'items'){
			if(is_array($itemid)) {
				if (!empty($itemid[0]))
				{
					JArrayHelper::toInteger( $itemid );
					$query .= ' AND (i.id=' . implode( ' OR i.id=', $itemid ) . ')';
				}
			}
			elseif (!empty($itemid)) {
				$query .= ' AND (i.id=' . $itemid  . ')';
			}
		}


		if ($params->get('FeaturedItems') == '0')
			$query .= " AND i.featured != 1";

		if ($params->get('FeaturedItems') == '2')
			$query .= " AND i.featured = 1";

		if ($params->get('videosOnly'))
			$query .= " AND (i.video IS NOT NULL AND i.video!='')";

		if ($ordering == 'comments')
			$query .= " AND comments.published = 1";

		if(version_compare(JVERSION, '1.6', '>='))
		{
			if($mainframe->getLanguageFilter())
			{
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND c.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") AND i.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').")";
			}
		}

		switch ($ordering) {

			case 'date':
				$orderby = 'i.created ASC';
				break;

			case 'rdate':
				$orderby = 'i.created DESC';
				break;

			case 'alpha':
				$orderby = 'i.title';
				break;

			case 'ralpha':
				$orderby = 'i.title DESC';
				break;

			case 'order':
				if ($params->get('FeaturedItems') == '2')
				$orderby = 'i.featured_ordering';
				else
				$orderby = 'i.ordering';
				break;

			case 'hits':
				$orderby = 'i.hits DESC';
				break;

			case 'rand':
				$orderby = 'RAND()';
				break;

			case 'best':
				$orderby = 'rating DESC';
				break;

			default:
				$orderby = 'i.id DESC';
				break;
		}

		$query .= " ORDER BY ".$orderby;
		$db->setQuery($query, 0, $limit);
		$items = $db->loadObjectList();

		require_once JPATH_SITE . '/components/com_k2/models/item.php';
		$model = new K2ModelItem;

		require_once JPATH_SITE . '/components/com_k2/helpers/route.php';

		if (count($items)) {
			$i		= 0;
			$lists	= array();

			foreach ($items as $item) {

				$item->imageMedium  = false;
				$item->imageXSmall  = false;
				$item->imageSmall   = false;
				$item->imageLarge   = false;
				$item->imageXLarge  = false;
				$item->imageDefault = false;
				$item->closelink    = false;

				//Clean title
				$titletext = JFilterOutput::ampReplace($item->title);
				$item->modaltitle = JFilterOutput::ampReplace($item->title);
				$item->title = $titlewordCount ? ZenToolsHelper::truncate($titletext, $titlewordCount, $titleSuffix) : $titletext;

				//Images
				$item->featured = $item->featured;

				$date = JFactory::getDate($item->modified);
				$timestamp = '?t='.$date->toUnix();
				$item->imageSmall ="";


				$item->imageintrotext = false;
				$item->image = "";

				if(!($params->get('itemImgSize') == "introtext")) {
					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_XS.jpg')){
						$item->imageXSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XS.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageXSmall.=$timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_S.jpg')){
						$item->imageSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageSmall.=$timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_M.jpg')){
						$item->imageMedium = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageMedium.=$timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_L.jpg')){
						$item->imageLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_L.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageLarge.=$timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_XL.jpg')){
						$item->imageXLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XL.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageXLarge.=$timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE . '/media/k2/items/cache/' . md5("Image".$item->id).'_Generic.jpg')){
						$item->imageDefault = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
						if($componentParams->get('imageTimestamp')){
							$item->imageDefault.=$timestamp;
						}
					}

					$image = 'image'.$params->get('itemImgSize','Small');

					$item->imageTiny = $item->imageXSmall;

					if (isset($item->$image))

						$item->imageOriginal = $item->imageXLarge;
						$item->modalimage = $item->$image;

						// Resize Image
						if ($resizeImage && !(empty($item->$image))) {

								$item->image =  ZenToolsHelper::getResizedImage($item->$image, $img_width, $img_height, $option);

						}
						elseif (!$resizeImage  && !(empty($item->$image))) {
							$item->image = $item->$image;
						}
				}

				// If the k2 item image isnt set lets grab the image fromt he introtext
				if(($params->get('itemImgSize') == "introtext") or (empty($item->$image))) {
						$imghtml= $item->introtext;
						$imghtml .= "alt='...' title='...' />";
						$pattern = '/<img[^>]+src[\\s=\'"]';
						$pattern .= '+([^"\'>\\s]+)/is';
							if(preg_match(
							$pattern,
							$imghtml,
							$match)) {
							$item->image = "$match[1]";

							}
							else {
									$item->image = "";
							}

						// Set the modal image
						if($item->image !=="") {
							$item->modalimage = $item->image;
							$item->imageOriginal = $item->image;
						}


						// Resize Image
						if ($resizeImage) {
							if($item->image !=="") {
								$item->image =  ZenToolsHelper::getResizedImage($item->image, $img_width, $img_height, $option);

								if($responsiveimages) {
									$item->imageTiny = ZenToolsHelper::getResizedImage($item->image, ($img_width /5), ($img_height / 5), $option);
									$item->imageXSmall = ZenToolsHelper::getResizedImage($item->image, ($img_width /3), ($img_height / 3), $option);
									$item->imageSmall = ZenToolsHelper::getResizedImage($item->image, ($img_width /2), ($img_height / 2), $option);
									$item->imageMedium = ZenToolsHelper::getResizedImage($item->image, ($img_width /1.25), ($img_height / 1.25), $option);
									$item->imageDefault = ZenToolsHelper::getResizedImage($item->image, ($img_width), ($img_height), $option);
									$item->imageLarge = ZenToolsHelper::getResizedImage($item->image, ($img_width * 1.25), ($img_height * 1.25), $option);
									if($item->imageLarge == $item->image) {
										$item->imageLarge = $item->imageDefault;
									}
									$item->imageXLarge = ZenToolsHelper::getResizedImage($item->image, ($img_width *1.75), ($img_height * 1.75), $option);

									if($item->imageXLarge == $item->image) {
										$item->imageXLarge = $item->imageDefault;
									}
								}
							}
						}
						else {
							$item->image = $item->image;

							if($responsiveimages) {
								if($item->image !=="") {
									list($width, $height) = getimagesize($item->image);

									$item->imageTiny = ZenToolsHelper::getResizedImage($item->image, ($width /5), ($height / 5), 'exact');
									$item->imageXSmall = ZenToolsHelper::getResizedImage($item->image, ($width /3), ($height / 3), 'exact');
									$item->imageSmall = ZenToolsHelper::getResizedImage($item->image, ($width /2), ($height / 2), 'exact');
									$item->imageMedium = ZenToolsHelper::getResizedImage($item->image, ($width /1.5), ($height / 1.5), 'exact');
									$item->imageDefault = ZenToolsHelper::getResizedImage($item->image, ($width), ($height), 'exact');
									$item->imageLarge = ZenToolsHelper::getResizedImage($item->image, ($width * 1.25), ($height * 1.25), $option);
									if($item->imageLarge == $item->image) {
										$item->imageLarge = $item->imageDefault;
									}
									$item->imageXLarge = ZenToolsHelper::getResizedImage($item->image, ($width *1.75), ($height * 1.75), $option);

									if($item->imageXLarge == $item->image) {
										$item->imageXLarge = $item->imageDefault;
									}
								}
							}
						}
				}



				$item->thumb ="";
				if($item->image !=="") {
					$item->thumb =  ZenToolsHelper::getResizedImage($item->image, $thumb_width, $thumb_height,  $option);
				}


				//Read more link
				if($link == 0) {
					$item->link = '';
					$item->closelink = '';
				}
				elseif($link == 1) {

					if($modalMore or $modalTitle or $modalText) {
						$item->link = 'href="#data'.$item->id.'"';
						$item->fulllink = 'href="'.urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias)))).'"';
					}
					else {
						$item->link = 'href="'.$item->modalimage.'" title="'.$item->modaltitle.'"';
					}

					$item->closelink = '</a>';
					$item->lightboxmore = 'href="'.urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias)))).'"';
				}
				else {
					$item->link = 'href="'.urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias)))).'"';
					$item->closelink = '</a>';
				}

				// Introtext
				$item->modaltext = $item->introtext;

				$modalImage = $params->get('modalImage',0);
				if($modalImage) {
					$item->modaltext = preg_replace('/<img(.*)>/i','',$item->modaltext,1);
				}


				if($strip_tags) {
					$introtext = $strip_tags ? ZenToolsHelper::_cleanIntrotext($item->introtext,$tags) : $item->introtext;
				}
				else {
					$introtext = $item->introtext;
				}
				$item->text = $wordCount ? ZenToolsHelper::truncate($introtext, $wordCount, $textsuffix) : $item->introtext;





				$item->category = $item->categoryname;
				$item->catlink = '<a href="'.urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->categoryalias)))).'">';


				// Date Format
				if (!$translateDate) {
					$item->date= date($dateFormat, (strtotime($item->created)));
				}
				else {
					$item->date=  JHtml::_('date',$item->created, JText::_(''.$dateString.''));
				}

				$item->extrafields = $model->getItemExtraFields($item->extra_fields, $item);

				if($model->countItemComments($item->id) < 2) {
					$item->comments = $model->countItemComments($item->id).' comment';
				}
				else {
					$item->comments = $model->countItemComments($item->id).' comments';
				}

				$item->attachments = $model->getItemAttachments($item->id);
				$item->newlink = 0;
				$rows[] = $item;
			}

			return $rows;

		}

	}

	public static function getK2Version()
	{
		jimport('joomla.filesystem.file');

		$version = false;

		if (version_compare(JVERSION, '1.6', '>='))
		{
			$k2Path = JPATH_SITE . '/plugins/system/k2/';

			// Check if K2 manifest file exists
			// K2 versions, older than 2.6.1 have missed manifest file
			if (JFile::exists($k2Path . 'k2.xml'))
			{
				$installer = new JInstaller;
				$installer->setPath('source', $k2Path);
				$manifest = $installer->getManifest();

				$version = (string) $manifest->version;
			}
		}
		else
		{
			$k2Path = JPATH_SITE . '/plugins/system/';

			jimport('joomla.filesystem.file');
			jimport('joomla.installer.installer');

			// Check if K2 manifest file exists
			// K2 versions, older than 2.6.1 have missed manifest file
			if (JFile::exists($k2Path . 'k2.xml'))
			{
				$manifest = new SimpleXMLElement(JFile::read($k2Path . 'k2.xml'));
				$version = (string) $manifest->version;
			}
		}

		return $version;
	}
}
