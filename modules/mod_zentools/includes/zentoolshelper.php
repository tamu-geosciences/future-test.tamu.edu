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

class ZenToolsHelper
{
	private static $zgfIsInstalled = null;

	private static $zgfOrT3IsInstalled = null;

	public static function _cleanIntrotext($introtext,$tags)
	{
		//$introtext = str_replace('<p>', ' ', $introtext);
		//$introtext = str_replace('</p>', ' ', $introtext);
		$introtext = strip_tags($introtext, $tags);

		$introtext = trim($introtext);

		return $introtext;
	}

	public static function string_limit_words($introText, $wordCount, $suffixChar='&hellip;')
	{
		$introText = trim($introText);
		$id = explode(' ', $introText);
		$suffix = count($id) <= $wordCount ? '' : $suffixChar;

		return implode(' ', array_slice($id, 0, $wordCount)) . $suffix;
	}

	/**
	* This is a better truncate implementation than what we
	* currently have available in the library. In particular,
	* on index.php/Banners/Banners/site-map.html JHtml's truncate
	* method would only return "Article...". This implementation
	* was taken directly from the Stack Overflow thread referenced
	* below. It was then modified to return a string rather than
	* print out the output and made to use the relevant JString
	* methods. Now, modified to work with utf-8 chars.
	*
	* @link http://stackoverflow.com/questions/1193500/php-truncate-html-ignoring-tags
	* @param mixed $html
	* @param mixed $maxLength
	*/
	public static function truncate($html, $maxLength = 0, $suffixChar='&hellip;')
	{
		$printedLength = 0;
		$position = 0;
		$tags = array();
		$suffix = '';
		$output = '';

		if((mb_strlen($html)) > ($maxLength+1)){
			$suffix = $suffixChar;
		}

		if (empty($html)) {
			return $output;
		}

		while ($printedLength < $maxLength && ZenToolsHelper::mb_preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position))
		{
			list($tag, $tagPosition) = $match[0];

			// Print text leading up to the tag.
			$str = mb_substr($html, $position, $tagPosition - $position);
			if ($printedLength + mb_strlen($str) > $maxLength) {
				$output .= mb_substr($str, 0, $maxLength - $printedLength);
				$printedLength = $maxLength;
				break;
			}

			$output .= $str;
			$lastCharacterIsOpenBracket = (mb_substr($output, -1, 1) === '<');

			if ($lastCharacterIsOpenBracket) {
				$output = mb_substr($output, 0, mb_strlen($output) - 1);
			}

			$printedLength += mb_strlen($str);

			if ($tag[0] == '&') {
				// Handle the entity.
				$output .= $tag;
				$printedLength++;
			}
			else {
				// Handle the tag.
				$tagName = $match[1][0];

				if ($tag[1] == '/') {
					// This is a closing tag.
					$openingTag = array_pop($tags);

					$output .= $tag;
				}
				else if ($tag[mb_strlen($tag) - 2] == '/') {
					// Self-closing tag.
					$output .= $tag;
				}
				else {
					// Opening tag.
					$output .= $tag;
					$tags[] = $tagName;
				}
			}

			// Continue after the tag.
			if ($lastCharacterIsOpenBracket) {
				$position = ($tagPosition - 1) + mb_strlen($tag);
			}
			else {
				$position = $tagPosition + mb_strlen($tag);
			}

		}

		// Print any remaining text.
		if ($printedLength < $maxLength && $position < mb_strlen($html)) {
			$output .= mb_substr($html, $position, $maxLength - $printedLength);
		}

		$output .= $suffix;
		// Close any open tags.
		while (!empty($tags))
		{
			$output .= sprintf('</%s>', array_pop($tags));
		}

		$length = mb_strlen($output);
		$lastChar = mb_substr($output, ($length - 1), 1);
		$characterNumber = ord($lastChar);

		if ($characterNumber === 194) {
			$output = mb_substr($output, 0, mb_strlen($output) - 1);
		}

		$output = JString::rtrim($output);

		return $output;
	}

	public static function mb_preg_match(
		$ps_pattern,
		$ps_subject,
		&$pa_matches,
		$pn_flags = 0,
		$pn_offset = 0,
		$ps_encoding = NULL
		)
	{
		// WARNING! - All this function does is to correct offsets, nothing else:
		//(code is independent of PREG_PATTER_ORDER / PREG_SET_ORDER)

		if (is_null($ps_encoding)) $ps_encoding = mb_internal_encoding();

		$pn_offset = strlen(mb_substr($ps_subject, 0, $pn_offset, $ps_encoding));
		$ret = preg_match($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);

		if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
		{
			foreach($pa_matches as &$ha_match) {
				$ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
			}
		}

		return $ret;
	}

	public static function checkK2Requirement($requirement)
	{
		return !((string) $requirement === 'k2' && !self::isK2Installed());
	}

	public static function isK2Installed()
	{
		jimport( 'joomla.filesystem.file' );

		if (defined('K2_JVERSION'))
		{
			return true;
		}

		if (version_compare(JVERSION, '3.0', '>='))
		{
			jimport('joomla.filesystem.files');
		}

		return JFile::exists(JPATH_ADMINISTRATOR . '/components/com_k2/admin.k2.php')
			|| JFile::exists(JPATH_ADMINISTRATOR . '/components/com_k2/k2.php');
	}

	public static function removeBasePath($path)
	{
		$basePath = JPATH_SITE . '/';

		if (empty($basePath))
		{
			$basePath = dirname($_SERVER['SCRIPT_FILENAME']);
		}

		return str_replace($basePath, '', $path);
	}

	public static function getResizedImage($image, $width, $height, $option)
	{
		return self::removeBasePath(ZenToolsResizeImageHelper::getResizedImage($image, $width, $height, $option));
	}

	public static function handleRemoteImage($image)
	{
		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');

		// Check if it is a remote image
		if ((bool)preg_match('/^http/i', $image))
		{
			// Import libraries
			jimport('joomla.filesystem.folder');

			$ext = pathinfo($image, PATHINFO_EXTENSION);

			$cacheFolder = JPath::clean(JPATH_ROOT . '/media/mod_zentools/cache/images/');
			$dest = $cacheFolder . 'remote-image-' . md5($image) . '.' . $ext;

			if (!JFolder::exists($cacheFolder))
			{
				JFolder::create($cacheFolder);
			}

			if (!JFile::exists($dest) || filesize($dest) === 0)
			{
				// Check if the allow_url_open is enabled
				if ((bool)ini_get('allow_url_fopen'))
				{
					file_put_contents($dest, file_get_contents($image));
				}
				// Check if cUrl is enabled
				else if (in_array('curl', get_loaded_extensions()))
				{
					$fp = fopen($dest, 'wb');

					$curl = curl_init($image);
					curl_setopt($curl, CURLOPT_HEADER, false);
    				curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
					curl_setopt($curl, CURLOPT_FILE, $fp);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_exec($curl);
					curl_close($curl);

					fclose($fp);
				}
			}

			// Check if the cached file exists
			if (JFile::exists($dest))
			{
				$image = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $dest);
			}
		}

		return $image;
	}

	public static function isZenGridFrameworkInstalled()
	{
		if (self::$zgfIsInstalled === null)
		{
			$plugin = JPluginHelper::getPlugin('system', 'zengridframework');
			self::$zgfIsInstalled = ! empty($plugin);
		}

		return self::$zgfIsInstalled;
	}

	public static function isFrameworkInstalled()
	{
		if (self::$zgfOrT3IsInstalled === null)
		{
			$zgf = self::isZenGridFrameworkInstalled();

			$plugin = JPluginHelper::getPlugin('system', 't3');
			$t3 = ! empty($plugin);

			self::$zgfOrT3IsInstalled = ($zgf || $jblibrary || $t3);
		}

		return self::$zgfOrT3IsInstalled;
	}
}
