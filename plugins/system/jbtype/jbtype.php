<?php
/**
 * @package		Joomla.Plugin
 * @subpackage	System.Jbtype
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Copyright (C), Joomlabamboo. All Rights Reserved.. All rights reserved.
 * @license		license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		${jb.latest}
 */

/**
 * Based on JW AllVideos Plugin by Joomlaworks.gr and Xtypo from www.templateplazza.com
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.plugin.plugin' );

class plgSystemJbtype extends JPlugin {

	function onAfterRoute() {
		$app = JFactory::getApplication();
		if($app->isAdmin()) {
			return;
		}

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base() . 'media/jbtype/css/font-awesome.css');
	}

	function onAfterRender() {

		// Get Plugin info
		jimport( 'joomla.html.parameter' );
		$plugin		= JPluginHelper::getPlugin('system','jbtype');
		$app        = JFactory::getApplication();
		$document   = JFactory::getDocument();
		$doctype    = $document->getType();
		$output     = JResponse::getBody();
		$urlOption  = JRequest::getVar('option','none');
		$urlTask    = JRequest::getVar('task','none');
		//$modbase    = ''.JURI::base().'plugins/system/jbtype/';


		if (substr(JVERSION, 0, 3) >= '1.6') {
			require_once (JPATH_SITE.DS.'plugins/system/jbtype/jbtype/styles.php');

			$param     = new JForm( $plugin->params );
			$iconStyle = $this->params->get('iconStyle','coquette');
			$enabled   = $this->params->get('enabled', 1);
		}
		else {
			require_once (JPATH_SITE.DS.'plugins/system/jbtype/styles.php');

			$param     = new JParameter( $plugin->params );
			$iconStyle = $param->get('iconStyle','coquette');
			$enabled   = $param->get('enabled', 1);
		}



		if($app->isAdmin()) {
			return;
		}

		if($doctype !== 'html') {
			return;
		}

		if(($urlOption == 'com_content') and ($urlTask == 'edit')) {
			return;
		}

		unset($app, $doctype, $urlTask, $urlOption, $param, $plugin);

		// Strip JB Type tags from any tag param, avoiding break tags
		$output = preg_replace('/(<[^>]*)({(jb|zen|grid)[^}]*}{\/(jb|zen|grid)[^}]*})([^>]*>)/i', '$1$5', $output);


		$jbListPattern = '#{jb_list.*}(.*?){/jb_list.*}#s';
		//$jbListRegex = '#(^(.*?)\|)|(\|(.*?)\|)|(\|(.*?)$)#s';
		$jbListReplace = '<li>***listcode***</li>';

		$regex = array(

// Bootstrap
'jb_icon-cloud-download' => array('<i class="icon-cloud-download"></i><span class="jbtype jb_icon-cloud-download">***code***</span>', '#{jb_icon-cloud-download}(.*?){/jb_icon-cloud-download}#s') ,
'jb_icon-cloud-upload' => array('<i class="icon-cloud-upload"></i><span class="jbtype jb_icon-cloud-upload">***code***</span>', '#{jb_icon-cloud-upload}(.*?){/jb_icon-cloud-upload}#s') ,
'jb_icon-lightbulb' => array('<i class="icon-lightbulb"></i><span class="jbtype jb_icon-lightbulb">***code***</span>', '#{jb_icon-lightbulb}(.*?){/jb_icon-lightbulb}#s') ,
'jb_icon-exchange' => array('<i class="icon-exchange"></i><span class="jbtype jb_icon-exchange">***code***</span>', '#{jb_icon-exchange}(.*?){/jb_icon-exchange}#s') ,
'jb_icon-bell-alt' => array('<i class="icon-bell-alt"></i><span class="jbtype jb_icon-bell-alt">***code***</span>', '#{jb_icon-bell-alt}(.*?){/jb_icon-bell-alt}#s') ,
'jb_icon-file-alt' => array('<i class="icon-file-alt"></i><span class="jbtype jb_icon-file-alt">***code***</span>', '#{jb_icon-file-alt}(.*?){/jb_icon-file-alt}#s') ,
'jb_icon-beer' => array('<i class="icon-beer"></i><span class="jbtype jb_icon-beer">***code***</span>', '#{jb_icon-beer}(.*?){/jb_icon-beer}#s') ,
'jb_icon-coffee' => array('<i class="icon-coffee"></i><span class="jbtype jb_icon-coffee">***code***</span>', '#{jb_icon-coffee}(.*?){/jb_icon-coffee}#s') ,
'jb_icon-food' => array('<i class="icon-food"></i><span class="jbtype jb_icon-food">***code***</span>', '#{jb_icon-food}(.*?){/jb_icon-food}#s') ,
'jb_icon-fighter-jet' => array('<i class="icon-fighter-jet"></i><span class="jbtype jb_icon-fighter-jet">***code***</span>', '#{jb_icon-fighter-jet}(.*?){/jb_icon-fighter-jet}#s') ,
'jb_icon-user-md' => array('<i class="icon-user-md"></i><span class="jbtype jb_icon-user-md">***code***</span>', '#{jb_icon-user-md}(.*?){/jb_icon-user-md}#s') ,
'jb_icon-stethoscope' => array('<i class="icon-stethoscope"></i><span class="jbtype jb_icon-stethoscope">***code***</span>', '#{jb_icon-stethoscope}(.*?){/jb_icon-stethoscope}#s') ,
'jb_icon-suitcase' => array('<i class="icon-suitcase"></i><span class="jbtype jb_icon-suitcase">***code***</span>', '#{jb_icon-suitcase}(.*?){/jb_icon-suitcase}#s') ,
'jb_icon-building' => array('<i class="icon-building"></i><span class="jbtype jb_icon-building">***code***</span>', '#{jb_icon-building}(.*?){/jb_icon-building}#s') ,
'jb_icon-hospital' => array('<i class="icon-hospital"></i><span class="jbtype jb_icon-hospital">***code***</span>', '#{jb_icon-hospital}(.*?){/jb_icon-hospital}#s') ,
'jb_icon-ambulance' => array('<i class="icon-ambulance"></i><span class="jbtype jb_icon-ambulance">***code***</span>', '#{jb_icon-ambulance}(.*?){/jb_icon-ambulance}#s') ,
'jb_icon-medkit' => array('<i class="icon-medkit"></i><span class="jbtype jb_icon-medkit">***code***</span>', '#{jb_icon-medkit}(.*?){/jb_icon-medkit}#s') ,
'jb_icon-h-sign' => array('<i class="icon-h-sign"></i><span class="jbtype jb_icon-h-sign">***code***</span>', '#{jb_icon-h-sign}(.*?){/jb_icon-h-sign}#s') ,
'jb_icon-plus-sign-alt' => array('<i class="icon-plus-sign-alt"></i><span class="jbtype jb_icon-plus-sign-alt">***code***</span>', '#{jb_icon-plus-sign-alt}(.*?){/jb_icon-plus-sign-alt}#s') ,
'jb_icon-spinner' => array('<i class="icon-spinner"></i><span class="jbtype jb_icon-spinner">***code***</span>', '#{jb_icon-spinner}(.*?){/jb_icon-spinner}#s') ,
'jb_icon-angle-left' => array('<i class="icon-angle-left"></i><span class="jbtype jb_icon-angle-left">***code***</span>', '#{jb_icon-angle-left}(.*?){/jb_icon-angle-left}#s') ,
'jb_icon-angle-right' => array('<i class="icon-angle-right"></i><span class="jbtype jb_icon-angle-right">***code***</span>', '#{jb_icon-angle-right}(.*?){/jb_icon-angle-right}#s') ,
'jb_icon-angle-up' => array('<i class="icon-angle-up"></i><span class="jbtype jb_icon-angle-up">***code***</span>', '#{jb_icon-angle-up}(.*?){/jb_icon-angle-up}#s') ,
'jb_icon-angle-down' => array('<i class="icon-angle-down"></i><span class="jbtype jb_icon-angle-down">***code***</span>', '#{jb_icon-angle-down}(.*?){/jb_icon-angle-down}#s') ,
'jb_icon-double-angle-left' => array('<i class="icon-double-angle-left"></i><span class="jbtype jb_icon-double-angle-left">***code***</span>', '#{jb_icon-double-angle-left}(.*?){/jb_icon-double-angle-left}#s') ,
'jb_icon-double-angle-right' => array('<i class="icon-double-angle-right"></i><span class="jbtype jb_icon-double-angle-right">***code***</span>', '#{jb_icon-double-angle-right}(.*?){/jb_icon-double-angle-right}#s') ,
'jb_icon-double-angle-up' => array('<i class="icon-double-angle-up"></i><span class="jbtype jb_icon-double-angle-up">***code***</span>', '#{jb_icon-double-angle-up}(.*?){/jb_icon-double-angle-up}#s') ,
'jb_icon-double-angle-down' => array('<i class="icon-double-angle-down"></i><span class="jbtype jb_icon-double-angle-down">***code***</span>', '#{jb_icon-double-angle-down}(.*?){/jb_icon-double-angle-down}#s') ,
'jb_icon-circle-blank' => array('<i class="icon-circle-blank"></i><span class="jbtype jb_icon-circle-blank">***code***</span>', '#{jb_icon-circle-blank}(.*?){/jb_icon-circle-blank}#s') ,
'jb_icon-circle' => array('<i class="icon-circle"></i><span class="jbtype jb_icon-circle">***code***</span>', '#{jb_icon-circle}(.*?){/jb_icon-circle}#s') ,
'jb_icon-desktop' => array('<i class="icon-desktop"></i><span class="jbtype jb_icon-desktop">***code***</span>', '#{jb_icon-desktop}(.*?){/jb_icon-desktop}#s') ,
'jb_icon-laptop' => array('<i class="icon-laptop"></i><span class="jbtype jb_icon-laptop">***code***</span>', '#{jb_icon-laptop}(.*?){/jb_icon-laptop}#s') ,
'jb_icon-tablet' => array('<i class="icon-tablet"></i><span class="jbtype jb_icon-tablet">***code***</span>', '#{jb_icon-tablet}(.*?){/jb_icon-tablet}#s') ,
'jb_icon-mobile-phone' => array('<i class="icon-mobile-phone"></i><span class="jbtype jb_icon-mobile-phone">***code***</span>', '#{jb_icon-mobile-phone}(.*?){/jb_icon-mobile-phone}#s') ,
'jb_icon-quote-left' => array('<i class="icon-quote-left"></i><span class="jbtype jb_icon-quote-left">***code***</span>', '#{jb_icon-quote-left}(.*?){/jb_icon-quote-left}#s') ,
'jb_icon-quote-right' => array('<i class="icon-quote-right"></i><span class="jbtype jb_icon-quote-right">***code***</span>', '#{jb_icon-quote-right}(.*?){/jb_icon-quote-right}#s') ,
'jb_icon-reply' => array('<i class="icon-reply"></i><span class="jbtype jb_icon-reply">***code***</span>', '#{jb_icon-reply}(.*?){/jb_icon-reply}#s') ,
'jb_icon-github-alt' => array('<i class="icon-github-alt"></i><span class="jbtype jb_icon-github-alt">***code***</span>', '#{jb_icon-github-alt}(.*?){/jb_icon-github-alt}#s') ,
'jb_icon-folder-close-alt' => array('<i class="icon-folder-close-alt"></i><span class="jbtype jb_icon-folder-close-alt">***code***</span>', '#{jb_icon-folder-close-alt}(.*?){/jb_icon-folder-close-alt}#s') ,
'jb_icon-folder-open-alt' => array('<i class="icon-folder-open-alt"></i><span class="jbtype jb_icon-folder-open-alt">***code***</span>', '#{jb_icon-folder-open-alt}(.*?){/jb_icon-folder-open-alt}#s') ,
'jb_icon-glass' => array('<i class="icon-glass"></i><span class="jbtype jb_icon-glass">***code***</span>', '#{jb_icon-glass}(.*?){/jb_icon-glass}#s') ,
'jb_icon-music' => array('<i class="icon-music"></i><span class="jbtype jb_icon-music">***code***</span>', '#{jb_icon-music}(.*?){/jb_icon-music}#s') ,
'jb_icon-search:' => array('<i class="icon-search:"></i><span class="jbtype jb_icon-search:">***code***</span>', '#{jb_icon-search:}(.*?){/jb_icon-search:}#s') ,
'jb_icon-envelope' => array('<i class="icon-envelope"></i><span class="jbtype jb_icon-envelope">***code***</span>', '#{jb_icon-envelope}(.*?){/jb_icon-envelope}#s') ,
'jb_icon-heart' => array('<i class="icon-heart"></i><span class="jbtype jb_icon-heart">***code***</span>', '#{jb_icon-heart}(.*?){/jb_icon-heart}#s') ,
'jb_icon-star' => array('<i class="icon-star"></i><span class="jbtype jb_icon-star">***code***</span>', '#{jb_icon-star}(.*?){/jb_icon-star}#s') ,
'jb_icon-star-empty' => array('<i class="icon-star-empty"></i><span class="jbtype jb_icon-star-empty">***code***</span>', '#{jb_icon-star-empty}(.*?){/jb_icon-star-empty}#s') ,
'jb_icon-user' => array('<i class="icon-user"></i><span class="jbtype jb_icon-user">***code***</span>', '#{jb_icon-user}(.*?){/jb_icon-user}#s') ,
'jb_icon-film' => array('<i class="icon-film"></i><span class="jbtype jb_icon-film">***code***</span>', '#{jb_icon-film}(.*?){/jb_icon-film}#s') ,
'jb_icon-th-large' => array('<i class="icon-th-large"></i><span class="jbtype jb_icon-th-large">***code***</span>', '#{jb_icon-th-large}(.*?){/jb_icon-th-large}#s') ,
'jb_icon-th' => array('<i class="icon-th"></i><span class="jbtype jb_icon-th">***code***</span>', '#{jb_icon-th}(.*?){/jb_icon-th}#s') ,
'jb_icon-th-list' => array('<i class="icon-th-list"></i><span class="jbtype jb_icon-th-list">***code***</span>', '#{jb_icon-th-list}(.*?){/jb_icon-th-list}#s') ,
'jb_icon-ok' => array('<i class="icon-ok"></i><span class="jbtype jb_icon-ok">***code***</span>', '#{jb_icon-ok}(.*?){/jb_icon-ok}#s') ,
'jb_icon-remove' => array('<i class="icon-remove"></i><span class="jbtype jb_icon-remove">***code***</span>', '#{jb_icon-remove}(.*?){/jb_icon-remove}#s') ,
'jb_icon-zoom-in' => array('<i class="icon-zoom-in"></i><span class="jbtype jb_icon-zoom-in">***code***</span>', '#{jb_icon-zoom-in}(.*?){/jb_icon-zoom-in}#s') ,
'jb_icon-zoom-out' => array('<i class="icon-zoom-out"></i><span class="jbtype jb_icon-zoom-out">***code***</span>', '#{jb_icon-zoom-out}(.*?){/jb_icon-zoom-out}#s') ,
'jb_icon-off' => array('<i class="icon-off"></i><span class="jbtype jb_icon-off">***code***</span>', '#{jb_icon-off}(.*?){/jb_icon-off}#s') ,
'jb_icon-signal' => array('<i class="icon-signal"></i><span class="jbtype jb_icon-signal">***code***</span>', '#{jb_icon-signal}(.*?){/jb_icon-signal}#s') ,
'jb_icon-cog' => array('<i class="icon-cog"></i><span class="jbtype jb_icon-cog">***code***</span>', '#{jb_icon-cog}(.*?){/jb_icon-cog}#s') ,
'jb_icon-trash' => array('<i class="icon-trash"></i><span class="jbtype jb_icon-trash">***code***</span>', '#{jb_icon-trash}(.*?){/jb_icon-trash}#s') ,
'jb_icon-home' => array('<i class="icon-home"></i><span class="jbtype jb_icon-home">***code***</span>', '#{jb_icon-home}(.*?){/jb_icon-home}#s') ,
'jb_icon-file' => array('<i class="icon-file"></i><span class="jbtype jb_icon-file">***code***</span>', '#{jb_icon-file}(.*?){/jb_icon-file}#s') ,
'jb_icon-time' => array('<i class="icon-time"></i><span class="jbtype jb_icon-time">***code***</span>', '#{jb_icon-time}(.*?){/jb_icon-time}#s') ,
'jb_icon-road' => array('<i class="icon-road"></i><span class="jbtype jb_icon-road">***code***</span>', '#{jb_icon-road}(.*?){/jb_icon-road}#s') ,
'jb_icon-download-alt' => array('<i class="icon-download-alt"></i><span class="jbtype jb_icon-download-alt">***code***</span>', '#{jb_icon-download-alt}(.*?){/jb_icon-download-alt}#s') ,
'jb_icon-download' => array('<i class="icon-download"></i><span class="jbtype jb_icon-download">***code***</span>', '#{jb_icon-download}(.*?){/jb_icon-download}#s') ,
'jb_icon-upload' => array('<i class="icon-upload"></i><span class="jbtype jb_icon-upload">***code***</span>', '#{jb_icon-upload}(.*?){/jb_icon-upload}#s') ,
'jb_icon-inbox' => array('<i class="icon-inbox"></i><span class="jbtype jb_icon-inbox">***code***</span>', '#{jb_icon-inbox}(.*?){/jb_icon-inbox}#s') ,
'jb_icon-play-circle' => array('<i class="icon-play-circle"></i><span class="jbtype jb_icon-play-circle">***code***</span>', '#{jb_icon-play-circle}(.*?){/jb_icon-play-circle}#s') ,
'jb_icon-repeat' => array('<i class="icon-repeat"></i><span class="jbtype jb_icon-repeat">***code***</span>', '#{jb_icon-repeat}(.*?){/jb_icon-repeat}#s') ,
'jb_icon-refresh' => array('<i class="icon-refresh"></i><span class="jbtype jb_icon-refresh">***code***</span>', '#{jb_icon-refresh}(.*?){/jb_icon-refresh}#s') ,
'jb_icon-list-alt' => array('<i class="icon-list-alt"></i><span class="jbtype jb_icon-list-alt">***code***</span>', '#{jb_icon-list-alt}(.*?){/jb_icon-list-alt}#s') ,
'jb_icon-lock' => array('<i class="icon-lock"></i><span class="jbtype jb_icon-lock">***code***</span>', '#{jb_icon-lock}(.*?){/jb_icon-lock}#s') ,
'jb_icon-flag' => array('<i class="icon-flag"></i><span class="jbtype jb_icon-flag">***code***</span>', '#{jb_icon-flag}(.*?){/jb_icon-flag}#s') ,
'jb_icon-headphones' => array('<i class="icon-headphones"></i><span class="jbtype jb_icon-headphones">***code***</span>', '#{jb_icon-headphones}(.*?){/jb_icon-headphones}#s') ,
'jb_icon-volume-off' => array('<i class="icon-volume-off"></i><span class="jbtype jb_icon-volume-off">***code***</span>', '#{jb_icon-volume-off}(.*?){/jb_icon-volume-off}#s') ,
'jb_icon-volume-down' => array('<i class="icon-volume-down"></i><span class="jbtype jb_icon-volume-down">***code***</span>', '#{jb_icon-volume-down}(.*?){/jb_icon-volume-down}#s') ,
'jb_icon-volume-up' => array('<i class="icon-volume-up"></i><span class="jbtype jb_icon-volume-up">***code***</span>', '#{jb_icon-volume-up}(.*?){/jb_icon-volume-up}#s') ,
'jb_icon-qrcode' => array('<i class="icon-qrcode"></i><span class="jbtype jb_icon-qrcode">***code***</span>', '#{jb_icon-qrcode}(.*?){/jb_icon-qrcode}#s') ,
'jb_icon-barcode' => array('<i class="icon-barcode"></i><span class="jbtype jb_icon-barcode">***code***</span>', '#{jb_icon-barcode}(.*?){/jb_icon-barcode}#s') ,
'jb_icon-tag' => array('<i class="icon-tag"></i><span class="jbtype jb_icon-tag">***code***</span>', '#{jb_icon-tag}(.*?){/jb_icon-tag}#s') ,
'jb_icon-tags' => array('<i class="icon-tags"></i><span class="jbtype jb_icon-tags">***code***</span>', '#{jb_icon-tags}(.*?){/jb_icon-tags}#s') ,
'jb_icon-book' => array('<i class="icon-book"></i><span class="jbtype jb_icon-book">***code***</span>', '#{jb_icon-book}(.*?){/jb_icon-book}#s') ,
'jb_icon-bookmark' => array('<i class="icon-bookmark"></i><span class="jbtype jb_icon-bookmark">***code***</span>', '#{jb_icon-bookmark}(.*?){/jb_icon-bookmark}#s') ,
'jb_icon-print' => array('<i class="icon-print"></i><span class="jbtype jb_icon-print">***code***</span>', '#{jb_icon-print}(.*?){/jb_icon-print}#s') ,
'jb_icon-camera' => array('<i class="icon-camera"></i><span class="jbtype jb_icon-camera">***code***</span>', '#{jb_icon-camera}(.*?){/jb_icon-camera}#s') ,
'jb_icon-font' => array('<i class="icon-font"></i><span class="jbtype jb_icon-font">***code***</span>', '#{jb_icon-font}(.*?){/jb_icon-font}#s') ,
'jb_icon-bold' => array('<i class="icon-bold"></i><span class="jbtype jb_icon-bold">***code***</span>', '#{jb_icon-bold}(.*?){/jb_icon-bold}#s') ,
'jb_icon-italic' => array('<i class="icon-italic"></i><span class="jbtype jb_icon-italic">***code***</span>', '#{jb_icon-italic}(.*?){/jb_icon-italic}#s') ,
'jb_icon-text-height' => array('<i class="icon-text-height"></i><span class="jbtype jb_icon-text-height">***code***</span>', '#{jb_icon-text-height}(.*?){/jb_icon-text-height}#s') ,
'jb_icon-text-width' => array('<i class="icon-text-width"></i><span class="jbtype jb_icon-text-width">***code***</span>', '#{jb_icon-text-width}(.*?){/jb_icon-text-width}#s') ,
'jb_icon-align-left' => array('<i class="icon-align-left"></i><span class="jbtype jb_icon-align-left">***code***</span>', '#{jb_icon-align-left}(.*?){/jb_icon-align-left}#s') ,
'jb_icon-align-center' => array('<i class="icon-align-center"></i><span class="jbtype jb_icon-align-center">***code***</span>', '#{jb_icon-align-center}(.*?){/jb_icon-align-center}#s') ,
'jb_icon-align-right' => array('<i class="icon-align-right"></i><span class="jbtype jb_icon-align-right">***code***</span>', '#{jb_icon-align-right}(.*?){/jb_icon-align-right}#s') ,
'jb_icon-align-justify' => array('<i class="icon-align-justify"></i><span class="jbtype jb_icon-align-justify">***code***</span>', '#{jb_icon-align-justify}(.*?){/jb_icon-align-justify}#s') ,
'jb_icon-list' => array('<i class="icon-list"></i><span class="jbtype jb_icon-list">***code***</span>', '#{jb_icon-list}(.*?){/jb_icon-list}#s') ,
'jb_icon-indent-left' => array('<i class="icon-indent-left"></i><span class="jbtype jb_icon-indent-left">***code***</span>', '#{jb_icon-indent-left}(.*?){/jb_icon-indent-left}#s') ,
'jb_icon-indent-right' => array('<i class="icon-indent-right"></i><span class="jbtype jb_icon-indent-right">***code***</span>', '#{jb_icon-indent-right}(.*?){/jb_icon-indent-right}#s') ,
'jb_icon-facetime-video' => array('<i class="icon-facetime-video"></i><span class="jbtype jb_icon-facetime-video">***code***</span>', '#{jb_icon-facetime-video}(.*?){/jb_icon-facetime-video}#s') ,
'jb_icon-picture' => array('<i class="icon-picture"></i><span class="jbtype jb_icon-picture">***code***</span>', '#{jb_icon-picture}(.*?){/jb_icon-picture}#s') ,
'jb_icon-pencil' => array('<i class="icon-pencil"></i><span class="jbtype jb_icon-pencil">***code***</span>', '#{jb_icon-pencil}(.*?){/jb_icon-pencil}#s') ,
'jb_icon-map-marker' => array('<i class="icon-map-marker"></i><span class="jbtype jb_icon-map-marker">***code***</span>', '#{jb_icon-map-marker}(.*?){/jb_icon-map-marker}#s') ,
'jb_icon-adjust' => array('<i class="icon-adjust"></i><span class="jbtype jb_icon-adjust">***code***</span>', '#{jb_icon-adjust}(.*?){/jb_icon-adjust}#s') ,
'jb_icon-tint' => array('<i class="icon-tint"></i><span class="jbtype jb_icon-tint">***code***</span>', '#{jb_icon-tint}(.*?){/jb_icon-tint}#s') ,
'jb_icon-edit' => array('<i class="icon-edit"></i><span class="jbtype jb_icon-edit">***code***</span>', '#{jb_icon-edit}(.*?){/jb_icon-edit}#s') ,
'jb_icon-share' => array('<i class="icon-share"></i><span class="jbtype jb_icon-share">***code***</span>', '#{jb_icon-share}(.*?){/jb_icon-share}#s') ,
'jb_icon-check' => array('<i class="icon-check"></i><span class="jbtype jb_icon-check">***code***</span>', '#{jb_icon-check}(.*?){/jb_icon-check}#s') ,
'jb_icon-move' => array('<i class="icon-move"></i><span class="jbtype jb_icon-move">***code***</span>', '#{jb_icon-move}(.*?){/jb_icon-move}#s') ,
'jb_icon-step-backward' => array('<i class="icon-step-backward"></i><span class="jbtype jb_icon-step-backward">***code***</span>', '#{jb_icon-step-backward}(.*?){/jb_icon-step-backward}#s') ,
'jb_icon-fast-backward' => array('<i class="icon-fast-backward"></i><span class="jbtype jb_icon-fast-backward">***code***</span>', '#{jb_icon-fast-backward}(.*?){/jb_icon-fast-backward}#s') ,
'jb_icon-backward' => array('<i class="icon-backward"></i><span class="jbtype jb_icon-backward">***code***</span>', '#{jb_icon-backward}(.*?){/jb_icon-backward}#s') ,
'jb_icon-play' => array('<i class="icon-play"></i><span class="jbtype jb_icon-play">***code***</span>', '#{jb_icon-play}(.*?){/jb_icon-play}#s') ,
'jb_icon-pause' => array('<i class="icon-pause"></i><span class="jbtype jb_icon-pause">***code***</span>', '#{jb_icon-pause}(.*?){/jb_icon-pause}#s') ,
'jb_icon-stop' => array('<i class="icon-stop"></i><span class="jbtype jb_icon-stop">***code***</span>', '#{jb_icon-stop}(.*?){/jb_icon-stop}#s') ,
'jb_icon-forward' => array('<i class="icon-forward"></i><span class="jbtype jb_icon-forward">***code***</span>', '#{jb_icon-forward}(.*?){/jb_icon-forward}#s') ,
'jb_icon-fast-forward' => array('<i class="icon-fast-forward"></i><span class="jbtype jb_icon-fast-forward">***code***</span>', '#{jb_icon-fast-forward}(.*?){/jb_icon-fast-forward}#s') ,
'jb_icon-step-forward' => array('<i class="icon-step-forward"></i><span class="jbtype jb_icon-step-forward">***code***</span>', '#{jb_icon-step-forward}(.*?){/jb_icon-step-forward}#s') ,
'jb_icon-eject' => array('<i class="icon-eject"></i><span class="jbtype jb_icon-eject">***code***</span>', '#{jb_icon-eject}(.*?){/jb_icon-eject}#s') ,
'jb_icon-chevron-left' => array('<i class="icon-chevron-left"></i><span class="jbtype jb_icon-chevron-left">***code***</span>', '#{jb_icon-chevron-left}(.*?){/jb_icon-chevron-left}#s') ,
'jb_icon-chevron-right' => array('<i class="icon-chevron-right"></i><span class="jbtype jb_icon-chevron-right">***code***</span>', '#{jb_icon-chevron-right}(.*?){/jb_icon-chevron-right}#s') ,
'jb_icon-plus-sign' => array('<i class="icon-plus-sign"></i><span class="jbtype jb_icon-plus-sign">***code***</span>', '#{jb_icon-plus-sign}(.*?){/jb_icon-plus-sign}#s') ,
'jb_icon-minus-sign' => array('<i class="icon-minus-sign"></i><span class="jbtype jb_icon-minus-sign">***code***</span>', '#{jb_icon-minus-sign}(.*?){/jb_icon-minus-sign}#s') ,
'jb_icon-remove-sign' => array('<i class="icon-remove-sign"></i><span class="jbtype jb_icon-remove-sign">***code***</span>', '#{jb_icon-remove-sign}(.*?){/jb_icon-remove-sign}#s') ,
'jb_icon-ok-sign' => array('<i class="icon-ok-sign"></i><span class="jbtype jb_icon-ok-sign">***code***</span>', '#{jb_icon-ok-sign}(.*?){/jb_icon-ok-sign}#s') ,
'jb_icon-question-sign' => array('<i class="icon-question-sign"></i><span class="jbtype jb_icon-question-sign">***code***</span>', '#{jb_icon-question-sign}(.*?){/jb_icon-question-sign}#s') ,
'jb_icon-info-sign' => array('<i class="icon-info-sign"></i><span class="jbtype jb_icon-info-sign">***code***</span>', '#{jb_icon-info-sign}(.*?){/jb_icon-info-sign}#s') ,
'jb_icon-screenshot' => array('<i class="icon-screenshot"></i><span class="jbtype jb_icon-screenshot">***code***</span>', '#{jb_icon-screenshot}(.*?){/jb_icon-screenshot}#s') ,
'jb_icon-remove-circle' => array('<i class="icon-remove-circle"></i><span class="jbtype jb_icon-remove-circle">***code***</span>', '#{jb_icon-remove-circle}(.*?){/jb_icon-remove-circle}#s') ,
'jb_icon-ok-circle' => array('<i class="icon-ok-circle"></i><span class="jbtype jb_icon-ok-circle">***code***</span>', '#{jb_icon-ok-circle}(.*?){/jb_icon-ok-circle}#s') ,
'jb_icon-ban-circle' => array('<i class="icon-ban-circle"></i><span class="jbtype jb_icon-ban-circle">***code***</span>', '#{jb_icon-ban-circle}(.*?){/jb_icon-ban-circle}#s') ,
'jb_icon-arrow-left' => array('<i class="icon-arrow-left"></i><span class="jbtype jb_icon-arrow-left">***code***</span>', '#{jb_icon-arrow-left}(.*?){/jb_icon-arrow-left}#s') ,
'jb_icon-arrow-right' => array('<i class="icon-arrow-right"></i><span class="jbtype jb_icon-arrow-right">***code***</span>', '#{jb_icon-arrow-right}(.*?){/jb_icon-arrow-right}#s') ,
'jb_icon-arrow-up' => array('<i class="icon-arrow-up"></i><span class="jbtype jb_icon-arrow-up">***code***</span>', '#{jb_icon-arrow-up}(.*?){/jb_icon-arrow-up}#s') ,
'jb_icon-arrow-down' => array('<i class="icon-arrow-down"></i><span class="jbtype jb_icon-arrow-down">***code***</span>', '#{jb_icon-arrow-down}(.*?){/jb_icon-arrow-down}#s') ,
'jb_icon-share-alt' => array('<i class="icon-share-alt"></i><span class="jbtype jb_icon-share-alt">***code***</span>', '#{jb_icon-share-alt}(.*?){/jb_icon-share-alt}#s') ,
'jb_icon-resize-full' => array('<i class="icon-resize-full"></i><span class="jbtype jb_icon-resize-full">***code***</span>', '#{jb_icon-resize-full}(.*?){/jb_icon-resize-full}#s') ,
'jb_icon-resize-small' => array('<i class="icon-resize-small"></i><span class="jbtype jb_icon-resize-small">***code***</span>', '#{jb_icon-resize-small}(.*?){/jb_icon-resize-small}#s') ,
'jb_icon-plus' => array('<i class="icon-plus"></i><span class="jbtype jb_icon-plus">***code***</span>', '#{jb_icon-plus}(.*?){/jb_icon-plus}#s') ,
'jb_icon-minus' => array('<i class="icon-minus"></i><span class="jbtype jb_icon-minus">***code***</span>', '#{jb_icon-minus}(.*?){/jb_icon-minus}#s') ,
'jb_icon-asterisk' => array('<i class="icon-asterisk"></i><span class="jbtype jb_icon-asterisk">***code***</span>', '#{jb_icon-asterisk}(.*?){/jb_icon-asterisk}#s') ,
'jb_icon-exclamation-sign' => array('<i class="icon-exclamation-sign"></i><span class="jbtype jb_icon-exclamation-sign">***code***</span>', '#{jb_icon-exclamation-sign}(.*?){/jb_icon-exclamation-sign}#s') ,
'jb_icon-gift' => array('<i class="icon-gift"></i><span class="jbtype jb_icon-gift">***code***</span>', '#{jb_icon-gift}(.*?){/jb_icon-gift}#s') ,
'jb_icon-leaf' => array('<i class="icon-leaf"></i><span class="jbtype jb_icon-leaf">***code***</span>', '#{jb_icon-leaf}(.*?){/jb_icon-leaf}#s') ,
'jb_icon-fire' => array('<i class="icon-fire"></i><span class="jbtype jb_icon-fire">***code***</span>', '#{jb_icon-fire}(.*?){/jb_icon-fire}#s') ,
'jb_icon-eye-open' => array('<i class="icon-eye-open"></i><span class="jbtype jb_icon-eye-open">***code***</span>', '#{jb_icon-eye-open}(.*?){/jb_icon-eye-open}#s') ,
'jb_icon-eye-close' => array('<i class="icon-eye-close"></i><span class="jbtype jb_icon-eye-close">***code***</span>', '#{jb_icon-eye-close}(.*?){/jb_icon-eye-close}#s') ,
'jb_icon-warning-sign' => array('<i class="icon-warning-sign"></i><span class="jbtype jb_icon-warning-sign">***code***</span>', '#{jb_icon-warning-sign}(.*?){/jb_icon-warning-sign}#s') ,
'jb_icon-plane' => array('<i class="icon-plane"></i><span class="jbtype jb_icon-plane">***code***</span>', '#{jb_icon-plane}(.*?){/jb_icon-plane}#s') ,
'jb_icon-calendar' => array('<i class="icon-calendar"></i><span class="jbtype jb_icon-calendar">***code***</span>', '#{jb_icon-calendar}(.*?){/jb_icon-calendar}#s') ,
'jb_icon-random' => array('<i class="icon-random"></i><span class="jbtype jb_icon-random">***code***</span>', '#{jb_icon-random}(.*?){/jb_icon-random}#s') ,
'jb_icon-comment' => array('<i class="icon-comment"></i><span class="jbtype jb_icon-comment">***code***</span>', '#{jb_icon-comment}(.*?){/jb_icon-comment}#s') ,
'jb_icon-magnet' => array('<i class="icon-magnet"></i><span class="jbtype jb_icon-magnet">***code***</span>', '#{jb_icon-magnet}(.*?){/jb_icon-magnet}#s') ,
'jb_icon-chevron-up' => array('<i class="icon-chevron-up"></i><span class="jbtype jb_icon-chevron-up">***code***</span>', '#{jb_icon-chevron-up}(.*?){/jb_icon-chevron-up}#s') ,
'jb_icon-chevron-down' => array('<i class="icon-chevron-down"></i><span class="jbtype jb_icon-chevron-down">***code***</span>', '#{jb_icon-chevron-down}(.*?){/jb_icon-chevron-down}#s') ,
'jb_icon-retweet' => array('<i class="icon-retweet"></i><span class="jbtype jb_icon-retweet">***code***</span>', '#{jb_icon-retweet}(.*?){/jb_icon-retweet}#s') ,
'jb_icon-shopping-cart' => array('<i class="icon-shopping-cart"></i><span class="jbtype jb_icon-shopping-cart">***code***</span>', '#{jb_icon-shopping-cart}(.*?){/jb_icon-shopping-cart}#s') ,
'jb_icon-folder-close' => array('<i class="icon-folder-close"></i><span class="jbtype jb_icon-folder-close">***code***</span>', '#{jb_icon-folder-close}(.*?){/jb_icon-folder-close}#s') ,
'jb_icon-folder-open' => array('<i class="icon-folder-open"></i><span class="jbtype jb_icon-folder-open">***code***</span>', '#{jb_icon-folder-open}(.*?){/jb_icon-folder-open}#s') ,
'jb_icon-resize-vertical' => array('<i class="icon-resize-vertical"></i><span class="jbtype jb_icon-resize-vertical">***code***</span>', '#{jb_icon-resize-vertical}(.*?){/jb_icon-resize-vertical}#s') ,
'jb_icon-resize-horizontal' => array('<i class="icon-resize-horizontal"></i><span class="jbtype jb_icon-resize-horizontal">***code***</span>', '#{jb_icon-resize-horizontal}(.*?){/jb_icon-resize-horizontal}#s') ,
'jb_icon-bar-chart' => array('<i class="icon-bar-chart"></i><span class="jbtype jb_icon-bar-chart">***code***</span>', '#{jb_icon-bar-chart}(.*?){/jb_icon-bar-chart}#s') ,
'jb_icon-twitter-sign' => array('<i class="icon-twitter-sign"></i><span class="jbtype jb_icon-twitter-sign">***code***</span>', '#{jb_icon-twitter-sign}(.*?){/jb_icon-twitter-sign}#s') ,
'jb_icon-facebook-sign' => array('<i class="icon-facebook-sign"></i><span class="jbtype jb_icon-facebook-sign">***code***</span>', '#{jb_icon-facebook-sign}(.*?){/jb_icon-facebook-sign}#s') ,
'jb_icon-camera-retro' => array('<i class="icon-camera-retro"></i><span class="jbtype jb_icon-camera-retro">***code***</span>', '#{jb_icon-camera-retro}(.*?){/jb_icon-camera-retro}#s') ,
'jb_icon-key' => array('<i class="icon-key"></i><span class="jbtype jb_icon-key">***code***</span>', '#{jb_icon-key}(.*?){/jb_icon-key}#s') ,
'jb_icon-cogs' => array('<i class="icon-cogs"></i><span class="jbtype jb_icon-cogs">***code***</span>', '#{jb_icon-cogs}(.*?){/jb_icon-cogs}#s') ,
'jb_icon-comments' => array('<i class="icon-comments"></i><span class="jbtype jb_icon-comments">***code***</span>', '#{jb_icon-comments}(.*?){/jb_icon-comments}#s') ,
'jb_icon-thumbs-up' => array('<i class="icon-thumbs-up"></i><span class="jbtype jb_icon-thumbs-up">***code***</span>', '#{jb_icon-thumbs-up}(.*?){/jb_icon-thumbs-up}#s') ,
'jb_icon-thumbs-down' => array('<i class="icon-thumbs-down"></i><span class="jbtype jb_icon-thumbs-down">***code***</span>', '#{jb_icon-thumbs-down}(.*?){/jb_icon-thumbs-down}#s') ,
'jb_icon-star-half' => array('<i class="icon-star-half"></i><span class="jbtype jb_icon-star-half">***code***</span>', '#{jb_icon-star-half}(.*?){/jb_icon-star-half}#s') ,
'jb_icon-heart-empty' => array('<i class="icon-heart-empty"></i><span class="jbtype jb_icon-heart-empty">***code***</span>', '#{jb_icon-heart-empty}(.*?){/jb_icon-heart-empty}#s') ,
'jb_icon-signout' => array('<i class="icon-signout"></i><span class="jbtype jb_icon-signout">***code***</span>', '#{jb_icon-signout}(.*?){/jb_icon-signout}#s') ,
'jb_icon-linkedin-sign' => array('<i class="icon-linkedin-sign"></i><span class="jbtype jb_icon-linkedin-sign">***code***</span>', '#{jb_icon-linkedin-sign}(.*?){/jb_icon-linkedin-sign}#s') ,
'jb_icon-pushpin' => array('<i class="icon-pushpin"></i><span class="jbtype jb_icon-pushpin">***code***</span>', '#{jb_icon-pushpin}(.*?){/jb_icon-pushpin}#s') ,
'jb_icon-external-link' => array('<i class="icon-external-link"></i><span class="jbtype jb_icon-external-link">***code***</span>', '#{jb_icon-external-link}(.*?){/jb_icon-external-link}#s') ,
'jb_icon-signin' => array('<i class="icon-signin"></i><span class="jbtype jb_icon-signin">***code***</span>', '#{jb_icon-signin}(.*?){/jb_icon-signin}#s') ,
'jb_icon-trophy' => array('<i class="icon-trophy"></i><span class="jbtype jb_icon-trophy">***code***</span>', '#{jb_icon-trophy}(.*?){/jb_icon-trophy}#s') ,
'jb_icon-github-sign' => array('<i class="icon-github-sign"></i><span class="jbtype jb_icon-github-sign">***code***</span>', '#{jb_icon-github-sign}(.*?){/jb_icon-github-sign}#s') ,
'jb_icon-upload-alt' => array('<i class="icon-upload-alt"></i><span class="jbtype jb_icon-upload-alt">***code***</span>', '#{jb_icon-upload-alt}(.*?){/jb_icon-upload-alt}#s') ,
'jb_icon-lemon' => array('<i class="icon-lemon"></i><span class="jbtype jb_icon-lemon">***code***</span>', '#{jb_icon-lemon}(.*?){/jb_icon-lemon}#s') ,
'jb_icon-phone' => array('<i class="icon-phone"></i><span class="jbtype jb_icon-phone">***code***</span>', '#{jb_icon-phone}(.*?){/jb_icon-phone}#s') ,
'jb_icon-check-empty' => array('<i class="icon-check-empty"></i><span class="jbtype jb_icon-check-empty">***code***</span>', '#{jb_icon-check-empty}(.*?){/jb_icon-check-empty}#s') ,
'jb_icon-bookmark-empty' => array('<i class="icon-bookmark-empty"></i><span class="jbtype jb_icon-bookmark-empty">***code***</span>', '#{jb_icon-bookmark-empty}(.*?){/jb_icon-bookmark-empty}#s') ,
'jb_icon-phone-sign' => array('<i class="icon-phone-sign"></i><span class="jbtype jb_icon-phone-sign">***code***</span>', '#{jb_icon-phone-sign}(.*?){/jb_icon-phone-sign}#s') ,
'jb_icon-twitter' => array('<i class="icon-twitter"></i><span class="jbtype jb_icon-twitter">***code***</span>', '#{jb_icon-twitter}(.*?){/jb_icon-twitter}#s') ,
'jb_icon-facebook' => array('<i class="icon-facebook"></i><span class="jbtype jb_icon-facebook">***code***</span>', '#{jb_icon-facebook}(.*?){/jb_icon-facebook}#s') ,
'jb_icon-github' => array('<i class="icon-github"></i><span class="jbtype jb_icon-github">***code***</span>', '#{jb_icon-github}(.*?){/jb_icon-github}#s') ,
'jb_icon-unlock' => array('<i class="icon-unlock"></i><span class="jbtype jb_icon-unlock">***code***</span>', '#{jb_icon-unlock}(.*?){/jb_icon-unlock}#s') ,
'jb_icon-credit-card' => array('<i class="icon-credit-card"></i><span class="jbtype jb_icon-credit-card">***code***</span>', '#{jb_icon-credit-card}(.*?){/jb_icon-credit-card}#s') ,
'jb_icon-rss' => array('<i class="icon-rss"></i><span class="jbtype jb_icon-rss">***code***</span>', '#{jb_icon-rss}(.*?){/jb_icon-rss}#s') ,
'jb_icon-hdd' => array('<i class="icon-hdd"></i><span class="jbtype jb_icon-hdd">***code***</span>', '#{jb_icon-hdd}(.*?){/jb_icon-hdd}#s') ,
'jb_icon-bullhorn' => array('<i class="icon-bullhorn"></i><span class="jbtype jb_icon-bullhorn">***code***</span>', '#{jb_icon-bullhorn}(.*?){/jb_icon-bullhorn}#s') ,
'jb_icon-bell' => array('<i class="icon-bell"></i><span class="jbtype jb_icon-bell">***code***</span>', '#{jb_icon-bell}(.*?){/jb_icon-bell}#s') ,
'jb_icon-certificate' => array('<i class="icon-certificate"></i><span class="jbtype jb_icon-certificate">***code***</span>', '#{jb_icon-certificate}(.*?){/jb_icon-certificate}#s') ,
'jb_icon-hand-right' => array('<i class="icon-hand-right"></i><span class="jbtype jb_icon-hand-right">***code***</span>', '#{jb_icon-hand-right}(.*?){/jb_icon-hand-right}#s') ,
'jb_icon-hand-left' => array('<i class="icon-hand-left"></i><span class="jbtype jb_icon-hand-left">***code***</span>', '#{jb_icon-hand-left}(.*?){/jb_icon-hand-left}#s') ,
'jb_icon-hand-down' => array('<i class="icon-hand-down"></i><span class="jbtype jb_icon-hand-down">***code***</span>', '#{jb_icon-hand-down}(.*?){/jb_icon-hand-down}#s') ,
'jb_icon-circle-arrow-left' => array('<i class="icon-circle-arrow-left"></i><span class="jbtype jb_icon-circle-arrow-left">***code***</span>', '#{jb_icon-circle-arrow-left}(.*?){/jb_icon-circle-arrow-left}#s') ,
'jb_icon-circle-arrow-right' => array('<i class="icon-circle-arrow-right"></i><span class="jbtype jb_icon-circle-arrow-right">***code***</span>', '#{jb_icon-circle-arrow-right}(.*?){/jb_icon-circle-arrow-right}#s') ,
'jb_icon-circle-arrow-up' => array('<i class="icon-circle-arrow-up"></i><span class="jbtype jb_icon-circle-arrow-up">***code***</span>', '#{jb_icon-circle-arrow-up}(.*?){/jb_icon-circle-arrow-up}#s') ,
'jb_icon-circle-arrow-down' => array('<i class="icon-circle-arrow-down"></i><span class="jbtype jb_icon-circle-arrow-down">***code***</span>', '#{jb_icon-circle-arrow-down}(.*?){/jb_icon-circle-arrow-down}#s') ,
'jb_icon-globe' => array('<i class="icon-globe"></i><span class="jbtype jb_icon-globe">***code***</span>', '#{jb_icon-globe}(.*?){/jb_icon-globe}#s') ,
'jb_icon-wrench' => array('<i class="icon-wrench"></i><span class="jbtype jb_icon-wrench">***code***</span>', '#{jb_icon-wrench}(.*?){/jb_icon-wrench}#s') ,
'jb_icon-tasks' => array('<i class="icon-tasks"></i><span class="jbtype jb_icon-tasks">***code***</span>', '#{jb_icon-tasks}(.*?){/jb_icon-tasks}#s') ,
'jb_icon-filter' => array('<i class="icon-filter"></i><span class="jbtype jb_icon-filter">***code***</span>', '#{jb_icon-filter}(.*?){/jb_icon-filter}#s') ,
'jb_icon-briefcase' => array('<i class="icon-briefcase"></i><span class="jbtype jb_icon-briefcase">***code***</span>', '#{jb_icon-briefcase}(.*?){/jb_icon-briefcase}#s') ,
'jb_icon-fullscreen' => array('<i class="icon-fullscreen"></i><span class="jbtype jb_icon-fullscreen">***code***</span>', '#{jb_icon-fullscreen}(.*?){/jb_icon-fullscreen}#s') ,
'jb_icon-group' => array('<i class="icon-group"></i><span class="jbtype jb_icon-group">***code***</span>', '#{jb_icon-group}(.*?){/jb_icon-group}#s') ,
'jb_icon-link' => array('<i class="icon-link"></i><span class="jbtype jb_icon-link">***code***</span>', '#{jb_icon-link}(.*?){/jb_icon-link}#s') ,
'jb_icon-cloud' => array('<i class="icon-cloud"></i><span class="jbtype jb_icon-cloud">***code***</span>', '#{jb_icon-cloud}(.*?){/jb_icon-cloud}#s') ,
'jb_icon-beaker' => array('<i class="icon-beaker"></i><span class="jbtype jb_icon-beaker">***code***</span>', '#{jb_icon-beaker}(.*?){/jb_icon-beaker}#s') ,
'jb_icon-cut' => array('<i class="icon-cut"></i><span class="jbtype jb_icon-cut">***code***</span>', '#{jb_icon-cut}(.*?){/jb_icon-cut}#s') ,
'jb_icon-copy' => array('<i class="icon-copy"></i><span class="jbtype jb_icon-copy">***code***</span>', '#{jb_icon-copy}(.*?){/jb_icon-copy}#s') ,
'jb_icon-paper-clip' => array('<i class="icon-paper-clip"></i><span class="jbtype jb_icon-paper-clip">***code***</span>', '#{jb_icon-paper-clip}(.*?){/jb_icon-paper-clip}#s') ,
'jb_icon-save' => array('<i class="icon-save"></i><span class="jbtype jb_icon-save">***code***</span>', '#{jb_icon-save}(.*?){/jb_icon-save}#s') ,
'jb_icon-sign-blank' => array('<i class="icon-sign-blank"></i><span class="jbtype jb_icon-sign-blank">***code***</span>', '#{jb_icon-sign-blank}(.*?){/jb_icon-sign-blank}#s') ,
'jb_icon-reorder' => array('<i class="icon-reorder"></i><span class="jbtype jb_icon-reorder">***code***</span>', '#{jb_icon-reorder}(.*?){/jb_icon-reorder}#s') ,
'jb_icon-list-ul' => array('<i class="icon-list-ul"></i><span class="jbtype jb_icon-list-ul">***code***</span>', '#{jb_icon-list-ul}(.*?){/jb_icon-list-ul}#s') ,
'jb_icon-list-ol' => array('<i class="icon-list-ol"></i><span class="jbtype jb_icon-list-ol">***code***</span>', '#{jb_icon-list-ol}(.*?){/jb_icon-list-ol}#s') ,
'jb_icon-strikethrough' => array('<i class="icon-strikethrough"></i><span class="jbtype jb_icon-strikethrough">***code***</span>', '#{jb_icon-strikethrough}(.*?){/jb_icon-strikethrough}#s') ,
'jb_icon-underline' => array('<i class="icon-underline"></i><span class="jbtype jb_icon-underline">***code***</span>', '#{jb_icon-underline}(.*?){/jb_icon-underline}#s') ,
'jb_icon-table' => array('<i class="icon-table"></i><span class="jbtype jb_icon-table">***code***</span>', '#{jb_icon-table}(.*?){/jb_icon-table}#s') ,
'jb_icon-magic' => array('<i class="icon-magic"></i><span class="jbtype jb_icon-magic">***code***</span>', '#{jb_icon-magic}(.*?){/jb_icon-magic}#s') ,
'jb_icon-truck' => array('<i class="icon-truck"></i><span class="jbtype jb_icon-truck">***code***</span>', '#{jb_icon-truck}(.*?){/jb_icon-truck}#s') ,
'jb_icon-pinterest' => array('<i class="icon-pinterest"></i><span class="jbtype jb_icon-pinterest">***code***</span>', '#{jb_icon-pinterest}(.*?){/jb_icon-pinterest}#s') ,
'jb_icon-pinterest-sign' => array('<i class="icon-pinterest-sign"></i><span class="jbtype jb_icon-pinterest-sign">***code***</span>', '#{jb_icon-pinterest-sign}(.*?){/jb_icon-pinterest-sign}#s') ,
'jb_icon-google-plus-sign' => array('<i class="icon-google-plus-sign"></i><span class="jbtype jb_icon-google-plus-sign">***code***</span>', '#{jb_icon-google-plus-sign}(.*?){/jb_icon-google-plus-sign}#s') ,
'jb_icon-google-plus' => array('<i class="icon-google-plus"></i><span class="jbtype jb_icon-google-plus">***code***</span>', '#{jb_icon-google-plus}(.*?){/jb_icon-google-plus}#s') ,
'jb_icon-money' => array('<i class="icon-money"></i><span class="jbtype jb_icon-money">***code***</span>', '#{jb_icon-money}(.*?){/jb_icon-money}#s') ,
'jb_icon-caret-down' => array('<i class="icon-caret-down"></i><span class="jbtype jb_icon-caret-down">***code***</span>', '#{jb_icon-caret-down}(.*?){/jb_icon-caret-down}#s') ,
'jb_icon-caret-up' => array('<i class="icon-caret-up"></i><span class="jbtype jb_icon-caret-up">***code***</span>', '#{jb_icon-caret-up}(.*?){/jb_icon-caret-up}#s') ,
'jb_icon-caret-left' => array('<i class="icon-caret-left"></i><span class="jbtype jb_icon-caret-left">***code***</span>', '#{jb_icon-caret-left}(.*?){/jb_icon-caret-left}#s') ,
'jb_icon-caret-right' => array('<i class="icon-caret-right"></i><span class="jbtype jb_icon-caret-right">***code***</span>', '#{jb_icon-caret-right}(.*?){/jb_icon-caret-right}#s') ,
'jb_icon-columns' => array('<i class="icon-columns"></i><span class="jbtype jb_icon-columns">***code***</span>', '#{jb_icon-columns}(.*?){/jb_icon-columns}#s') ,
'jb_icon-sort' => array('<i class="icon-sort"></i><span class="jbtype jb_icon-sort">***code***</span>', '#{jb_icon-sort}(.*?){/jb_icon-sort}#s') ,
'jb_icon-sort-down' => array('<i class="icon-sort-down"></i><span class="jbtype jb_icon-sort-down">***code***</span>', '#{jb_icon-sort-down}(.*?){/jb_icon-sort-down}#s') ,
'jb_icon-sort-up' => array('<i class="icon-sort-up"></i><span class="jbtype jb_icon-sort-up">***code***</span>', '#{jb_icon-sort-up}(.*?){/jb_icon-sort-up}#s') ,
'jb_icon-envelope-alt' => array('<i class="icon-envelope-alt"></i><span class="jbtype jb_icon-envelope-alt">***code***</span>', '#{jb_icon-envelope-alt}(.*?){/jb_icon-envelope-alt}#s') ,
'jb_icon-linkedin' => array('<i class="icon-linkedin"></i><span class="jbtype jb_icon-linkedin">***code***</span>', '#{jb_icon-linkedin}(.*?){/jb_icon-linkedin}#s') ,
'jb_icon-undo' => array('<i class="icon-undo"></i><span class="jbtype jb_icon-undo">***code***</span>', '#{jb_icon-undo}(.*?){/jb_icon-undo}#s') ,
'jb_icon-legal' => array('<i class="icon-legal"></i><span class="jbtype jb_icon-legal">***code***</span>', '#{jb_icon-legal}(.*?){/jb_icon-legal}#s') ,
'jb_icon-dashboard' => array('<i class="icon-dashboard"></i><span class="jbtype jb_icon-dashboard">***code***</span>', '#{jb_icon-dashboard}(.*?){/jb_icon-dashboard}#s') ,
'jb_icon-comment-alt' => array('<i class="icon-comment-alt"></i><span class="jbtype jb_icon-comment-alt">***code***</span>', '#{jb_icon-comment-alt}(.*?){/jb_icon-comment-alt}#s') ,
'jb_icon-comments-alt' => array('<i class="icon-comments-alt"></i><span class="jbtype jb_icon-comments-alt">***code***</span>', '#{jb_icon-comments-alt}(.*?){/jb_icon-comments-alt}#s') ,
'jb_icon-bolt' => array('<i class="icon-bolt"></i><span class="jbtype jb_icon-bolt">***code***</span>', '#{jb_icon-bolt}(.*?){/jb_icon-bolt}#s') ,
'jb_icon-sitemap' => array('<i class="icon-sitemap"></i><span class="jbtype jb_icon-sitemap">***code***</span>', '#{jb_icon-sitemap}(.*?){/jb_icon-sitemap}#s') ,
'jb_icon-umbrella' => array('<i class="icon-umbrella"></i><span class="jbtype jb_icon-umbrella">***code***</span>', '#{jb_icon-umbrella}(.*?){/jb_icon-umbrella}#s') ,
'jb_icon-paste' => array('<i class="icon-paste"></i><span class="jbtype jb_icon-paste">***code***</span>', '#{jb_icon-paste}(.*?){/jb_icon-paste}#s') ,
'jb_icon-user-md' => array('<i class="icon-user-md"></i><span class="jbtype jb_icon-user-md">***code***</span>', '#{jb_icon-user-md}(.*?){/jb_icon-user-md}#s') ,
'jb_icon-glass' => array('<i class="icon-glass"></i><span class="jbtype jb_icon-glass">***code***</span>', '#{jb_icon-glass}(.*?){/jb_icon-glass}#s') ,
'jb_icon-music' => array('<i class="icon-music"></i><span class="jbtype jb_icon-music">***code***</span>', '#{jb_icon-music}(.*?){/jb_icon-music}#s') ,
'jb_icon-search' => array('<i class="icon-search"></i><span class="jbtype jb_icon-search">***code***</span>', '#{jb_icon-search}(.*?){/jb_icon-search}#s') ,
'jb_icon-envelope' => array('<i class="icon-envelope"></i><span class="jbtype jb_icon-envelope">***code***</span>', '#{jb_icon-envelope}(.*?){/jb_icon-envelope}#s') ,
'jb_icon-heart' => array('<i class="icon-heart"></i><span class="jbtype jb_icon-heart">***code***</span>', '#{jb_icon-heart}(.*?){/jb_icon-heart}#s') ,
'jb_icon-star' => array('<i class="icon-star"></i><span class="jbtype jb_icon-star">***code***</span>', '#{jb_icon-star}(.*?){/jb_icon-star}#s') ,
'jb_icon-star-empty' => array('<i class="icon-star-empty"></i><span class="jbtype jb_icon-star-empty">***code***</span>', '#{jb_icon-star-empty}(.*?){/jb_icon-star-empty}#s') ,
'jb_icon-user' => array('<i class="icon-user"></i><span class="jbtype jb_icon-user">***code***</span>', '#{jb_icon-user}(.*?){/jb_icon-user}#s') ,
'jb_icon-film' => array('<i class="icon-film"></i><span class="jbtype jb_icon-film">***code***</span>', '#{jb_icon-film}(.*?){/jb_icon-film}#s') ,
'jb_icon-th-large' => array('<i class="icon-th-large"></i><span class="jbtype jb_icon-th-large">***code***</span>', '#{jb_icon-th-large}(.*?){/jb_icon-th-large}#s') ,
'jb_icon-th' => array('<i class="icon-th"></i><span class="jbtype jb_icon-th">***code***</span>', '#{jb_icon-th}(.*?){/jb_icon-th}#s') ,
'jb_icon-th-list' => array('<i class="icon-th-list"></i><span class="jbtype jb_icon-th-list">***code***</span>', '#{jb_icon-th-list}(.*?){/jb_icon-th-list}#s') ,
'jb_icon-ok' => array('<i class="icon-ok"></i><span class="jbtype jb_icon-ok">***code***</span>', '#{jb_icon-ok}(.*?){/jb_icon-ok}#s') ,
'jb_icon-remove' => array('<i class="icon-remove"></i><span class="jbtype jb_icon-remove">***code***</span>', '#{jb_icon-remove}(.*?){/jb_icon-remove}#s') ,
'jb_icon-zoom-in' => array('<i class="icon-zoom-in"></i><span class="jbtype jb_icon-zoom-in">***code***</span>', '#{jb_icon-zoom-in}(.*?){/jb_icon-zoom-in}#s') ,
'jb_icon-zoom-out' => array('<i class="icon-zoom-out"></i><span class="jbtype jb_icon-zoom-out">***code***</span>', '#{jb_icon-zoom-out}(.*?){/jb_icon-zoom-out}#s') ,
'jb_icon-off' => array('<i class="icon-off"></i><span class="jbtype jb_icon-off">***code***</span>', '#{jb_icon-off}(.*?){/jb_icon-off}#s') ,
'jb_icon-signal' => array('<i class="icon-signal"></i><span class="jbtype jb_icon-signal">***code***</span>', '#{jb_icon-signal}(.*?){/jb_icon-signal}#s') ,
'jb_icon-cog ' => array('<i class="icon-cog "></i><span class="jbtype jb_icon-cog ">***code***</span>', '#{jb_icon-cog }(.*?){/jb_icon-cog }#s') ,
'jb_icon-trash' => array('<i class="icon-trash"></i><span class="jbtype jb_icon-trash">***code***</span>', '#{jb_icon-trash}(.*?){/jb_icon-trash}#s') ,
'jb_icon-home' => array('<i class="icon-home"></i><span class="jbtype jb_icon-home">***code***</span>', '#{jb_icon-home}(.*?){/jb_icon-home}#s') ,
'jb_icon-file' => array('<i class="icon-file"></i><span class="jbtype jb_icon-file">***code***</span>', '#{jb_icon-file}(.*?){/jb_icon-file}#s') ,
'jb_icon-time' => array('<i class="icon-time"></i><span class="jbtype jb_icon-time">***code***</span>', '#{jb_icon-time}(.*?){/jb_icon-time}#s') ,
'jb_icon-road' => array('<i class="icon-road"></i><span class="jbtype jb_icon-road">***code***</span>', '#{jb_icon-road}(.*?){/jb_icon-road}#s') ,
'jb_icon-download-alt' => array('<i class="icon-download-alt"></i><span class="jbtype jb_icon-download-alt">***code***</span>', '#{jb_icon-download-alt}(.*?){/jb_icon-download-alt}#s') ,
'jb_icon-download' => array('<i class="icon-download"></i><span class="jbtype jb_icon-download">***code***</span>', '#{jb_icon-download}(.*?){/jb_icon-download}#s') ,
'jb_icon-upload' => array('<i class="icon-upload"></i><span class="jbtype jb_icon-upload">***code***</span>', '#{jb_icon-upload}(.*?){/jb_icon-upload}#s') ,
'jb_icon-inbox' => array('<i class="icon-inbox"></i><span class="jbtype jb_icon-inbox">***code***</span>', '#{jb_icon-inbox}(.*?){/jb_icon-inbox}#s') ,
'jb_icon-play-circle' => array('<i class="icon-play-circle"></i><span class="jbtype jb_icon-play-circle">***code***</span>', '#{jb_icon-play-circle}(.*?){/jb_icon-play-circle}#s') ,
'jb_icon-repeat ' => array('<i class="icon-repeat "></i><span class="jbtype jb_icon-repeat ">***code***</span>', '#{jb_icon-repeat }(.*?){/jb_icon-repeat }#s') ,
'jb_icon-refresh' => array('<i class="icon-refresh"></i><span class="jbtype jb_icon-refresh">***code***</span>', '#{jb_icon-refresh}(.*?){/jb_icon-refresh}#s') ,
'jb_icon-list-alt' => array('<i class="icon-list-alt"></i><span class="jbtype jb_icon-list-alt">***code***</span>', '#{jb_icon-list-alt}(.*?){/jb_icon-list-alt}#s') ,
'jb_icon-lock ' => array('<i class="icon-lock "></i><span class="jbtype jb_icon-lock ">***code***</span>', '#{jb_icon-lock }(.*?){/jb_icon-lock }#s') ,
'jb_icon-flag' => array('<i class="icon-flag"></i><span class="jbtype jb_icon-flag">***code***</span>', '#{jb_icon-flag}(.*?){/jb_icon-flag}#s') ,
'jb_icon-headphones' => array('<i class="icon-headphones"></i><span class="jbtype jb_icon-headphones">***code***</span>', '#{jb_icon-headphones}(.*?){/jb_icon-headphones}#s') ,
'jb_icon-volume-off' => array('<i class="icon-volume-off"></i><span class="jbtype jb_icon-volume-off">***code***</span>', '#{jb_icon-volume-off}(.*?){/jb_icon-volume-off}#s') ,
'jb_icon-volume-down' => array('<i class="icon-volume-down"></i><span class="jbtype jb_icon-volume-down">***code***</span>', '#{jb_icon-volume-down}(.*?){/jb_icon-volume-down}#s') ,
'jb_icon-volume-up' => array('<i class="icon-volume-up"></i><span class="jbtype jb_icon-volume-up">***code***</span>', '#{jb_icon-volume-up}(.*?){/jb_icon-volume-up}#s') ,
'jb_icon-qrcode' => array('<i class="icon-qrcode"></i><span class="jbtype jb_icon-qrcode">***code***</span>', '#{jb_icon-qrcode}(.*?){/jb_icon-qrcode}#s') ,
'jb_icon-barcode' => array('<i class="icon-barcode"></i><span class="jbtype jb_icon-barcode">***code***</span>', '#{jb_icon-barcode}(.*?){/jb_icon-barcode}#s') ,
'jb_icon-tag' => array('<i class="icon-tag"></i><span class="jbtype jb_icon-tag">***code***</span>', '#{jb_icon-tag}(.*?){/jb_icon-tag}#s') ,
'jb_icon-tags' => array('<i class="icon-tags"></i><span class="jbtype jb_icon-tags">***code***</span>', '#{jb_icon-tags}(.*?){/jb_icon-tags}#s') ,
'jb_icon-book' => array('<i class="icon-book"></i><span class="jbtype jb_icon-book">***code***</span>', '#{jb_icon-book}(.*?){/jb_icon-book}#s') ,
'jb_icon-bookmark' => array('<i class="icon-bookmark"></i><span class="jbtype jb_icon-bookmark">***code***</span>', '#{jb_icon-bookmark}(.*?){/jb_icon-bookmark}#s') ,
'jb_icon-print' => array('<i class="icon-print"></i><span class="jbtype jb_icon-print">***code***</span>', '#{jb_icon-print}(.*?){/jb_icon-print}#s') ,
'jb_icon-camera' => array('<i class="icon-camera"></i><span class="jbtype jb_icon-camera">***code***</span>', '#{jb_icon-camera}(.*?){/jb_icon-camera}#s') ,
'jb_icon-font' => array('<i class="icon-font"></i><span class="jbtype jb_icon-font">***code***</span>', '#{jb_icon-font}(.*?){/jb_icon-font}#s') ,
'jb_icon-bold' => array('<i class="icon-bold"></i><span class="jbtype jb_icon-bold">***code***</span>', '#{jb_icon-bold}(.*?){/jb_icon-bold}#s') ,
'jb_icon-italic' => array('<i class="icon-italic"></i><span class="jbtype jb_icon-italic">***code***</span>', '#{jb_icon-italic}(.*?){/jb_icon-italic}#s') ,
'jb_icon-text-height' => array('<i class="icon-text-height"></i><span class="jbtype jb_icon-text-height">***code***</span>', '#{jb_icon-text-height}(.*?){/jb_icon-text-height}#s') ,
'jb_icon-text-width' => array('<i class="icon-text-width"></i><span class="jbtype jb_icon-text-width">***code***</span>', '#{jb_icon-text-width}(.*?){/jb_icon-text-width}#s') ,
'jb_icon-align-left' => array('<i class="icon-align-left"></i><span class="jbtype jb_icon-align-left">***code***</span>', '#{jb_icon-align-left}(.*?){/jb_icon-align-left}#s') ,
'jb_icon-align-center' => array('<i class="icon-align-center"></i><span class="jbtype jb_icon-align-center">***code***</span>', '#{jb_icon-align-center}(.*?){/jb_icon-align-center}#s') ,
'jb_icon-align-right' => array('<i class="icon-align-right"></i><span class="jbtype jb_icon-align-right">***code***</span>', '#{jb_icon-align-right}(.*?){/jb_icon-align-right}#s') ,
'jb_icon-align-justify' => array('<i class="icon-align-justify"></i><span class="jbtype jb_icon-align-justify">***code***</span>', '#{jb_icon-align-justify}(.*?){/jb_icon-align-justify}#s') ,
'jb_icon-list' => array('<i class="icon-list"></i><span class="jbtype jb_icon-list">***code***</span>', '#{jb_icon-list}(.*?){/jb_icon-list}#s') ,
'jb_icon-indent-left' => array('<i class="icon-indent-left"></i><span class="jbtype jb_icon-indent-left">***code***</span>', '#{jb_icon-indent-left}(.*?){/jb_icon-indent-left}#s') ,
'jb_icon-indent-right' => array('<i class="icon-indent-right"></i><span class="jbtype jb_icon-indent-right">***code***</span>', '#{jb_icon-indent-right}(.*?){/jb_icon-indent-right}#s') ,
'jb_icon-facetime-video' => array('<i class="icon-facetime-video"></i><span class="jbtype jb_icon-facetime-video">***code***</span>', '#{jb_icon-facetime-video}(.*?){/jb_icon-facetime-video}#s') ,
'jb_icon-picture' => array('<i class="icon-picture"></i><span class="jbtype jb_icon-picture">***code***</span>', '#{jb_icon-picture}(.*?){/jb_icon-picture}#s') ,
'jb_icon-pencil' => array('<i class="icon-pencil"></i><span class="jbtype jb_icon-pencil">***code***</span>', '#{jb_icon-pencil}(.*?){/jb_icon-pencil}#s') ,
'jb_icon-map-marker ' => array('<i class="icon-map-marker "></i><span class="jbtype jb_icon-map-marker ">***code***</span>', '#{jb_icon-map-marker }(.*?){/jb_icon-map-marker }#s') ,
'jb_icon-adjust' => array('<i class="icon-adjust"></i><span class="jbtype jb_icon-adjust">***code***</span>', '#{jb_icon-adjust}(.*?){/jb_icon-adjust}#s') ,
'jb_icon-tint' => array('<i class="icon-tint"></i><span class="jbtype jb_icon-tint">***code***</span>', '#{jb_icon-tint}(.*?){/jb_icon-tint}#s') ,
'jb_icon-edit' => array('<i class="icon-edit"></i><span class="jbtype jb_icon-edit">***code***</span>', '#{jb_icon-edit}(.*?){/jb_icon-edit}#s') ,
'jb_icon-share' => array('<i class="icon-share"></i><span class="jbtype jb_icon-share">***code***</span>', '#{jb_icon-share}(.*?){/jb_icon-share}#s') ,
'jb_icon-check' => array('<i class="icon-check"></i><span class="jbtype jb_icon-check">***code***</span>', '#{jb_icon-check}(.*?){/jb_icon-check}#s') ,
'jb_icon-move' => array('<i class="icon-move"></i><span class="jbtype jb_icon-move">***code***</span>', '#{jb_icon-move}(.*?){/jb_icon-move}#s') ,
'jb_icon-step-backward' => array('<i class="icon-step-backward"></i><span class="jbtype jb_icon-step-backward">***code***</span>', '#{jb_icon-step-backward}(.*?){/jb_icon-step-backward}#s') ,
'jb_icon-fast-backward' => array('<i class="icon-fast-backward"></i><span class="jbtype jb_icon-fast-backward">***code***</span>', '#{jb_icon-fast-backward}(.*?){/jb_icon-fast-backward}#s') ,
'jb_icon-backward' => array('<i class="icon-backward"></i><span class="jbtype jb_icon-backward">***code***</span>', '#{jb_icon-backward}(.*?){/jb_icon-backward}#s') ,
'jb_icon-play' => array('<i class="icon-play"></i><span class="jbtype jb_icon-play">***code***</span>', '#{jb_icon-play}(.*?){/jb_icon-play}#s') ,
'jb_icon-pause' => array('<i class="icon-pause"></i><span class="jbtype jb_icon-pause">***code***</span>', '#{jb_icon-pause}(.*?){/jb_icon-pause}#s') ,
'jb_icon-stop' => array('<i class="icon-stop"></i><span class="jbtype jb_icon-stop">***code***</span>', '#{jb_icon-stop}(.*?){/jb_icon-stop}#s') ,
'jb_icon-forward' => array('<i class="icon-forward"></i><span class="jbtype jb_icon-forward">***code***</span>', '#{jb_icon-forward}(.*?){/jb_icon-forward}#s') ,
'jb_icon-fast-forward' => array('<i class="icon-fast-forward"></i><span class="jbtype jb_icon-fast-forward">***code***</span>', '#{jb_icon-fast-forward}(.*?){/jb_icon-fast-forward}#s') ,
'jb_icon-step-forward' => array('<i class="icon-step-forward"></i><span class="jbtype jb_icon-step-forward">***code***</span>', '#{jb_icon-step-forward}(.*?){/jb_icon-step-forward}#s') ,
'jb_icon-eject' => array('<i class="icon-eject"></i><span class="jbtype jb_icon-eject">***code***</span>', '#{jb_icon-eject}(.*?){/jb_icon-eject}#s') ,
'jb_icon-chevron-left' => array('<i class="icon-chevron-left"></i><span class="jbtype jb_icon-chevron-left">***code***</span>', '#{jb_icon-chevron-left}(.*?){/jb_icon-chevron-left}#s') ,
'jb_icon-chevron-right ' => array('<i class="icon-chevron-right "></i><span class="jbtype jb_icon-chevron-right ">***code***</span>', '#{jb_icon-chevron-right }(.*?){/jb_icon-chevron-right }#s') ,
'jb_icon-plus-sign' => array('<i class="icon-plus-sign"></i><span class="jbtype jb_icon-plus-sign">***code***</span>', '#{jb_icon-plus-sign}(.*?){/jb_icon-plus-sign}#s') ,
'jb_icon-minus-sign' => array('<i class="icon-minus-sign"></i><span class="jbtype jb_icon-minus-sign">***code***</span>', '#{jb_icon-minus-sign}(.*?){/jb_icon-minus-sign}#s') ,
'jb_icon-remove-sign' => array('<i class="icon-remove-sign"></i><span class="jbtype jb_icon-remove-sign">***code***</span>', '#{jb_icon-remove-sign}(.*?){/jb_icon-remove-sign}#s') ,
'jb_icon-ok-sign' => array('<i class="icon-ok-sign"></i><span class="jbtype jb_icon-ok-sign">***code***</span>', '#{jb_icon-ok-sign}(.*?){/jb_icon-ok-sign}#s') ,
'jb_icon-question-sign' => array('<i class="icon-question-sign"></i><span class="jbtype jb_icon-question-sign">***code***</span>', '#{jb_icon-question-sign}(.*?){/jb_icon-question-sign}#s') ,
'jb_icon-info-sign' => array('<i class="icon-info-sign"></i><span class="jbtype jb_icon-info-sign">***code***</span>', '#{jb_icon-info-sign}(.*?){/jb_icon-info-sign}#s') ,
'jb_icon-screenshot' => array('<i class="icon-screenshot"></i><span class="jbtype jb_icon-screenshot">***code***</span>', '#{jb_icon-screenshot}(.*?){/jb_icon-screenshot}#s') ,
'jb_icon-remove-circle' => array('<i class="icon-remove-circle"></i><span class="jbtype jb_icon-remove-circle">***code***</span>', '#{jb_icon-remove-circle}(.*?){/jb_icon-remove-circle}#s') ,
'jb_icon-ok-circle' => array('<i class="icon-ok-circle"></i><span class="jbtype jb_icon-ok-circle">***code***</span>', '#{jb_icon-ok-circle}(.*?){/jb_icon-ok-circle}#s') ,
'jb_icon-ban-circle' => array('<i class="icon-ban-circle"></i><span class="jbtype jb_icon-ban-circle">***code***</span>', '#{jb_icon-ban-circle}(.*?){/jb_icon-ban-circle}#s') ,
'jb_icon-arrow-left' => array('<i class="icon-arrow-left"></i><span class="jbtype jb_icon-arrow-left">***code***</span>', '#{jb_icon-arrow-left}(.*?){/jb_icon-arrow-left}#s') ,
'jb_icon-arrow-right' => array('<i class="icon-arrow-right"></i><span class="jbtype jb_icon-arrow-right">***code***</span>', '#{jb_icon-arrow-right}(.*?){/jb_icon-arrow-right}#s') ,
'jb_icon-arrow-up' => array('<i class="icon-arrow-up"></i><span class="jbtype jb_icon-arrow-up">***code***</span>', '#{jb_icon-arrow-up}(.*?){/jb_icon-arrow-up}#s') ,
'jb_icon-arrow-down' => array('<i class="icon-arrow-down"></i><span class="jbtype jb_icon-arrow-down">***code***</span>', '#{jb_icon-arrow-down}(.*?){/jb_icon-arrow-down}#s') ,
'jb_icon-share-alt' => array('<i class="icon-share-alt"></i><span class="jbtype jb_icon-share-alt">***code***</span>', '#{jb_icon-share-alt}(.*?){/jb_icon-share-alt}#s') ,
'jb_icon-resize-full' => array('<i class="icon-resize-full"></i><span class="jbtype jb_icon-resize-full">***code***</span>', '#{jb_icon-resize-full}(.*?){/jb_icon-resize-full}#s') ,
'jb_icon-resize-small' => array('<i class="icon-resize-small"></i><span class="jbtype jb_icon-resize-small">***code***</span>', '#{jb_icon-resize-small}(.*?){/jb_icon-resize-small}#s') ,
'jb_icon-plus' => array('<i class="icon-plus"></i><span class="jbtype jb_icon-plus">***code***</span>', '#{jb_icon-plus}(.*?){/jb_icon-plus}#s') ,
'jb_icon-minus' => array('<i class="icon-minus"></i><span class="jbtype jb_icon-minus">***code***</span>', '#{jb_icon-minus}(.*?){/jb_icon-minus}#s') ,
'jb_icon-asterisk' => array('<i class="icon-asterisk"></i><span class="jbtype jb_icon-asterisk">***code***</span>', '#{jb_icon-asterisk}(.*?){/jb_icon-asterisk}#s') ,
'jb_icon-exclamation-sign' => array('<i class="icon-exclamation-sign"></i><span class="jbtype jb_icon-exclamation-sign">***code***</span>', '#{jb_icon-exclamation-sign}(.*?){/jb_icon-exclamation-sign}#s') ,
'jb_icon-gift' => array('<i class="icon-gift"></i><span class="jbtype jb_icon-gift">***code***</span>', '#{jb_icon-gift}(.*?){/jb_icon-gift}#s') ,
'jb_icon-leaf' => array('<i class="icon-leaf"></i><span class="jbtype jb_icon-leaf">***code***</span>', '#{jb_icon-leaf}(.*?){/jb_icon-leaf}#s') ,
'jb_icon-fire' => array('<i class="icon-fire"></i><span class="jbtype jb_icon-fire">***code***</span>', '#{jb_icon-fire}(.*?){/jb_icon-fire}#s') ,
'jb_icon-eye-open ' => array('<i class="icon-eye-open "></i><span class="jbtype jb_icon-eye-open ">***code***</span>', '#{jb_icon-eye-open }(.*?){/jb_icon-eye-open }#s') ,
'jb_icon-eye-close' => array('<i class="icon-eye-close"></i><span class="jbtype jb_icon-eye-close">***code***</span>', '#{jb_icon-eye-close}(.*?){/jb_icon-eye-close}#s') ,
'jb_icon-warning-sign' => array('<i class="icon-warning-sign"></i><span class="jbtype jb_icon-warning-sign">***code***</span>', '#{jb_icon-warning-sign}(.*?){/jb_icon-warning-sign}#s') ,
'jb_icon-plane' => array('<i class="icon-plane"></i><span class="jbtype jb_icon-plane">***code***</span>', '#{jb_icon-plane}(.*?){/jb_icon-plane}#s') ,
'jb_icon-calendar' => array('<i class="icon-calendar"></i><span class="jbtype jb_icon-calendar">***code***</span>', '#{jb_icon-calendar}(.*?){/jb_icon-calendar}#s') ,
'jb_icon-random' => array('<i class="icon-random"></i><span class="jbtype jb_icon-random">***code***</span>', '#{jb_icon-random}(.*?){/jb_icon-random}#s') ,
'jb_icon-comment' => array('<i class="icon-comment"></i><span class="jbtype jb_icon-comment">***code***</span>', '#{jb_icon-comment}(.*?){/jb_icon-comment}#s') ,
'jb_icon-magnet' => array('<i class="icon-magnet"></i><span class="jbtype jb_icon-magnet">***code***</span>', '#{jb_icon-magnet}(.*?){/jb_icon-magnet}#s') ,
'jb_icon-chevron-up' => array('<i class="icon-chevron-up"></i><span class="jbtype jb_icon-chevron-up">***code***</span>', '#{jb_icon-chevron-up}(.*?){/jb_icon-chevron-up}#s') ,
'jb_icon-chevron-down' => array('<i class="icon-chevron-down"></i><span class="jbtype jb_icon-chevron-down">***code***</span>', '#{jb_icon-chevron-down}(.*?){/jb_icon-chevron-down}#s') ,
'jb_icon-retweet' => array('<i class="icon-retweet"></i><span class="jbtype jb_icon-retweet">***code***</span>', '#{jb_icon-retweet}(.*?){/jb_icon-retweet}#s') ,
'jb_icon-shopping-cart' => array('<i class="icon-shopping-cart"></i><span class="jbtype jb_icon-shopping-cart">***code***</span>', '#{jb_icon-shopping-cart}(.*?){/jb_icon-shopping-cart}#s') ,
'jb_icon-folder-close' => array('<i class="icon-folder-close"></i><span class="jbtype jb_icon-folder-close">***code***</span>', '#{jb_icon-folder-close}(.*?){/jb_icon-folder-close}#s') ,
'jb_icon-folder-open' => array('<i class="icon-folder-open"></i><span class="jbtype jb_icon-folder-open">***code***</span>', '#{jb_icon-folder-open}(.*?){/jb_icon-folder-open}#s') ,
'jb_icon-resize-vertical' => array('<i class="icon-resize-vertical"></i><span class="jbtype jb_icon-resize-vertical">***code***</span>', '#{jb_icon-resize-vertical}(.*?){/jb_icon-resize-vertical}#s') ,
'jb_icon-resize-horizontal' => array('<i class="icon-resize-horizontal"></i><span class="jbtype jb_icon-resize-horizontal">***code***</span>', '#{jb_icon-resize-horizontal}(.*?){/jb_icon-resize-horizontal}#s') ,
'jb_icon-hdd ' => array('<i class="icon-hdd "></i><span class="jbtype jb_icon-hdd ">***code***</span>', '#{jb_icon-hdd }(.*?){/jb_icon-hdd }#s') ,
'jb_icon-bullhorn' => array('<i class="icon-bullhorn"></i><span class="jbtype jb_icon-bullhorn">***code***</span>', '#{jb_icon-bullhorn}(.*?){/jb_icon-bullhorn}#s') ,
'jb_icon-bell' => array('<i class="icon-bell"></i><span class="jbtype jb_icon-bell">***code***</span>', '#{jb_icon-bell}(.*?){/jb_icon-bell}#s') ,
'jb_icon-certificate' => array('<i class="icon-certificate"></i><span class="jbtype jb_icon-certificate">***code***</span>', '#{jb_icon-certificate}(.*?){/jb_icon-certificate}#s') ,
'jb_icon-thumbs-up' => array('<i class="icon-thumbs-up"></i><span class="jbtype jb_icon-thumbs-up">***code***</span>', '#{jb_icon-thumbs-up}(.*?){/jb_icon-thumbs-up}#s') ,
'jb_icon-thumbs-down' => array('<i class="icon-thumbs-down"></i><span class="jbtype jb_icon-thumbs-down">***code***</span>', '#{jb_icon-thumbs-down}(.*?){/jb_icon-thumbs-down}#s') ,
'jb_icon-hand-right' => array('<i class="icon-hand-right"></i><span class="jbtype jb_icon-hand-right">***code***</span>', '#{jb_icon-hand-right}(.*?){/jb_icon-hand-right}#s') ,
'jb_icon-hand-left' => array('<i class="icon-hand-left"></i><span class="jbtype jb_icon-hand-left">***code***</span>', '#{jb_icon-hand-left}(.*?){/jb_icon-hand-left}#s') ,
'jb_icon-hand-up' => array('<i class="icon-hand-up"></i><span class="jbtype jb_icon-hand-up">***code***</span>', '#{jb_icon-hand-up}(.*?){/jb_icon-hand-up}#s') ,
'jb_icon-hand-down' => array('<i class="icon-hand-down"></i><span class="jbtype jb_icon-hand-down">***code***</span>', '#{jb_icon-hand-down}(.*?){/jb_icon-hand-down}#s') ,
'jb_icon-circle-arrow-right' => array('<i class="icon-circle-arrow-right"></i><span class="jbtype jb_icon-circle-arrow-right">***code***</span>', '#{jb_icon-circle-arrow-right}(.*?){/jb_icon-circle-arrow-right}#s') ,
'jb_icon-circle-arrow-left' => array('<i class="icon-circle-arrow-left"></i><span class="jbtype jb_icon-circle-arrow-left">***code***</span>', '#{jb_icon-circle-arrow-left}(.*?){/jb_icon-circle-arrow-left}#s') ,
'jb_icon-circle-arrow-up' => array('<i class="icon-circle-arrow-up"></i><span class="jbtype jb_icon-circle-arrow-up">***code***</span>', '#{jb_icon-circle-arrow-up}(.*?){/jb_icon-circle-arrow-up}#s') ,
'jb_icon-circle-arrow-down' => array('<i class="icon-circle-arrow-down"></i><span class="jbtype jb_icon-circle-arrow-down">***code***</span>', '#{jb_icon-circle-arrow-down}(.*?){/jb_icon-circle-arrow-down}#s') ,
'jb_icon-globe' => array('<i class="icon-globe"></i><span class="jbtype jb_icon-globe">***code***</span>', '#{jb_icon-globe}(.*?){/jb_icon-globe}#s') ,
'jb_icon-wrench' => array('<i class="icon-wrench"></i><span class="jbtype jb_icon-wrench">***code***</span>', '#{jb_icon-wrench}(.*?){/jb_icon-wrench}#s') ,
'jb_icon-tasks' => array('<i class="icon-tasks"></i><span class="jbtype jb_icon-tasks">***code***</span>', '#{jb_icon-tasks}(.*?){/jb_icon-tasks}#s') ,
'jb_icon-filter' => array('<i class="icon-filter"></i><span class="jbtype jb_icon-filter">***code***</span>', '#{jb_icon-filter}(.*?){/jb_icon-filter}#s') ,
'jb_icon-briefcase' => array('<i class="icon-briefcase"></i><span class="jbtype jb_icon-briefcase">***code***</span>', '#{jb_icon-briefcase}(.*?){/jb_icon-briefcase}#s') ,
'jb_icon-fullscreen' => array('<i class="icon-fullscreen"></i><span class="jbtype jb_icon-fullscreen">***code***</span>', '#{jb_icon-fullscreen}(.*?){/jb_icon-fullscreen}#s') ,

			// Boxes
			'jb_blackbox'  => array('<p class="jb_blackbox">***code***</p>', '#{jb_blackbox}(.*?){/jb_blackbox}#s') ,
			'jb_greenbox'  => array('<p class="jb_greenbox">***code***</p>', '#{jb_greenbox}(.*?){/jb_greenbox}#s') ,
			'jb_bluebox'   => array('<p class="jb_bluebox">***code***</p>', '#{jb_bluebox}(.*?){/jb_bluebox}#s') ,
			'jb_redbox'    => array('<p class="jb_redbox">***code***</p>', '#{jb_redbox}(.*?){/jb_redbox}#s') ,
			'jb_yellowbox' => array('<p class="jb_yellowbox">***code***</p>', '#{jb_yellowbox}(.*?){/jb_yellowbox}#s') ,
			'jb_brownbox'  => array('<p class="jb_brownbox">***code***</p>', '#{jb_brownbox}(.*?){/jb_brownbox}#s') ,
			'jb_purplebox' => array('<p class="jb_purplebox">***code***</p>', '#{jb_purplebox}(.*?){/jb_purplebox}#s') ,

			// Discs
			'jb_bluedisc'     => array('<span class="jb_bluedisc">***code***</span>', '#{jb_bluedisc}(.*?){/jb_bluedisc}#s') ,
			'jb_greendisc'    => array('<span class="jb_greendisc">***code***</span>', '#{jb_greendisc}(.*?){/jb_greendisc}#s') ,
			'jb_reddisc'      => array('<span class="jb_reddisc">***code***</span>', '#{jb_reddisc}(.*?){/jb_reddisc}#s') ,
			'jb_browndisc'    => array('<span class="jb_browndisc">***code***</span>', '#{jb_browndisc}(.*?){/jb_browndisc}#s') ,
			'jb_greydisc'     => array('<span class="jb_greydisc">***code***</span>', '#{jb_greydisc}(.*?){/jb_greydisc}#s') ,
			'jb_charcoaldisc' => array('<span class="jb_charcoaldisc">***code***</span>', '#{jb_charcoaldisc}(.*?){/jb_charcoaldisc}#s') ,
			'jb_purpledisc'   => array('<span class="jb_purpledisc">***code***</span>', '#{jb_purpledisc}(.*?){/jb_purpledisc}#s') ,
			'jb_orangedisc'   => array('<span class="jb_orangedisc">***code***</span>', '#{jb_orangedisc}(.*?){/jb_orangedisc}#s') ,
			'jb_yellowdisc'   => array('<span class="jb_yellowdisc">***code***</span>', '#{jb_yellowdisc}(.*?){/jb_yellowdisc}#s') ,
			'jb_blackdisc'    => array('<span class="jb_blackdisc">***code***</span>', '#{jb_blackdisc}(.*?){/jb_blackdisc}#s') ,


			// Typography
			'jb_dropcap'    => array('<span class="jb_dropcap">***code***</span>', '#{jb_dropcap}(.*?){/jb_dropcap}#s') ,
			'jb_quote'      => array('<blockquote><p>***code***</p></blockquote>', '#{jb_quote}(.*?){/jb_quote}#s') ,
			'jb_author'      => array('<span class="jb_author">***code***</span>', '#{jb_author}(.*?){/jb_author}#s') ,
			'jb_quoteleft'  => array('<div class="jb_quoteleft"><p>***code***</p></div>', '#{jb_quoteleft}(.*?){/jb_quoteleft}#s') ,
			'jb_quoteright' => array('<div class="jb_quoteright"><p>***code***</p></div>', '#{jb_quoteright}(.*?){/jb_quoteright}#s') ,

			// Layout
			'jb_left45'  => array('<div class="jb_left45">***code***</div>', '#{jb_left45}(.*?){/jb_left45}#s') ,
			'jb_right45' => array('<div class="jb_right45">***code***</div>', '#{jb_right45}(.*?){/jb_right45}#s') ,
			'jb_clear'   => array('<div class="jbclear">***code***</div>', '#{jb_clear}(.*?){/jb_clear}#s') ,

			'zenbutton'   => array('<div class="zenbutton"><span>***code***</span></div>', '#{zenbutton}(.*?){/zenbutton}#s') ,

			// Zengrid Style
			'grid1'  => array('<div class="grid_one">***code***</div>', '#{grid1}(.*?){/grid1}#s') ,
			'grid2'  => array('<div class="grid_two">***code***</div>', '#{grid2}(.*?){/grid2}#s') ,
			'grid3'  => array('<div class="grid_three">***code***</div>', '#{grid3}(.*?){/grid3}#s') ,
			'grid4'  => array('<div class="grid_four">***code***</div>', '#{grid4}(.*?){/grid4}#s') ,
			'grid5'  => array('<div class="grid_five">***code***</div>', '#{grid5}(.*?){/grid5}#s') ,
			'grid6'  => array('<div class="grid_six">***code***</div>', '#{grid6}(.*?){/grid6}#s') ,
			'grid7'  => array('<div class="grid_seven">***code***</div>', '#{grid7}(.*?){/grid7}#s') ,
			'grid8'  => array('<div class="grid_eight">***code***</div>', '#{grid8}(.*?){/grid8}#s') ,
			'grid9'  => array('<div class="grid_nine">***code***</div>', '#{grid9}(.*?){/grid9}#s') ,
			'grid10'  => array('<div class="grid_ten">***code***</div>', '#{grid10}(.*?){/grid10}#s') ,
			'grid11'  => array('<div class="grid_eleven">***code***</div>', '#{grid11}(.*?){/grid11}#s') ,
			'grid12'  => array('<div class="grid_twelve">***code***</div>', '#{grid12}(.*?){/grid12}#s') ,

			// ZenlastStyle
			'grid1_last'  => array('<div class="grid_one zenlast">***code***</div>', '#{grid1_last}(.*?){/grid1_last}#s') ,
			'grid2_last'  => array('<div class="grid_two zenlast">***code***</div>', '#{grid2_last}(.*?){/grid2_last}#s') ,
			'grid3_last'  => array('<div class="grid_three zenlast">***code***</div>', '#{grid3_last}(.*?){/grid3_last}#s') ,
			'grid4_last'  => array('<div class="grid_four zenlast">***code***</div>', '#{grid4_last}(.*?){/grid4_last}#s') ,
			'grid5_last'  => array('<div class="grid_five zenlast">***code***</div>', '#{grid5_last}(.*?){/grid5_last}#s') ,
			'grid6_last'  => array('<div class="grid_six zenlast">***code***</div>', '#{grid6_last}(.*?){/grid6_last}#s') ,
			'grid7_last'  => array('<div class="grid_seven zenlast">***code***</div>', '#{grid7_last}(.*?){/grid7_last}#s') ,
			'grid8_last'  => array('<div class="grid_eight zenlast">***code***</div>', '#{grid8_last}(.*?){/grid8_last}#s') ,
			'grid9_last'  => array('<div class="grid_nine zenlast">***code***</div>', '#{grid9_last}(.*?){/grid9_last}#s') ,
			'grid10_last'  => array('<div class="grid_ten zenlast">***code***</div>', '#{grid10_last}(.*?){/grid10_last}#s') ,
			'grid11_last'  => array('<div class="grid_eleven zenlast">***code***</div>', '#{grid11_last}(.*?){/grid11_last}#s') ,
			'grid12_last'  => array('<div class="grid_twelve zenlast">***code***</div>', '#{grid12_last}(.*?){/grid1_last2}#s') ,


			// Break out Style
			'jb_breakout'  => array('<div class="jb_breakout">***code***</div>', '#{jb_breakout}(.*?){/jb_breakout}#s') ,
			'jb_action'  => array('<div class="jb_action">***code***</div>', '#{jb_action}(.*?){/jb_action}#s') ,

			// Spans
			'jb_black'  => array('<span class="jb_black">***code***</span>', '#{jb_black}(.*?){/jb_black}#s') ,
			'jb_blue'   => array('<span class="jb_blue">***code***</span>', '#{jb_blue}(.*?){/jb_blue}#s') ,
			'jb_red'    => array('<span class="jb_red">***code***</span>', '#{jb_red}(.*?){/jb_red}#s') ,
			'jb_green'  => array('<span class="jb_green">***code***</span>', '#{jb_green}(.*?){/jb_green}#s') ,
			'jb_yellow' => array('<span class="jb_yellow">***code***</span>', '#{jb_yellow}(.*?){/jb_yellow}#s') ,
			'jb_white'  => array('<span class="jb_white">***code***</span>', '#{jb_white}(.*?){/jb_white}#s') ,
			'jb_brown'  => array('<span class="jb_brown">***code***</span>', '#{jb_brown}(.*?){/jb_brown}#s') ,
			'jb_purple' => array('<span class="jb_purple">***code***</span>', '#{jb_purple}(.*?){/jb_purple}#s') ,

			// Lists
			'jb_listblack'  => array('<ul class="jb_black">***code***</ul>', '#{jb_listblack}(.*?){/jb_listblack}#s') ,
			'jb_listblue'   => array('<ul class="jb_blue">***code***</ul>', '#{jb_listblue}(.*?){/jb_listblue}#s') ,
			'jb_listred'    => array('<ul class="jb_red">***code***</ul>', '#{jb_listred}(.*?){/jb_listred}#s') ,
			'jb_listgreen'  => array('<ul class="jb_green">***code***</ul>', '#{jb_listgreen}(.*?){/jb_listgreen}#s') ,
			'jb_listyellow' => array('<ul class="jb_yellow">***code***</ul>', '#{jb_listyellow}(.*?){/jb_listyellow}#s') ,
			'jb_listwhite'  => array('<ul class="jb_white">***code***</ul>', '#{jb_listwhite}(.*?){/jb_listwhite}#s') ,
			'jb_listbrown'  => array('<ul class="jb_brown">***code***</ul>', '#{jb_listbrown}(.*?){/jb_listbrown}#s') ,
			'jb_listpurple' => array('<ul class="jb_purple">***code***</ul>', '#{jb_listpurple}(.*?){/jb_listpurple}#s') ,

			// Iconic Icons
			'jb_iconic_info'      => array('<span class="jb_iconic_info"></span><span>***code***</span>', '#{jb_iconic_info}(.*?){/jb_iconic_info}#s') ,
			'jb_iconic_star'      => array('<span class="jb_iconic_star"></span><span>***code***</span>', '#{jb_iconic_star}(.*?){/jb_iconic_star}#s') ,
			'jb_iconic_heart'     => array('<span class="jb_iconic_heart"></span><span>***code***</span>', '#{jb_iconic_heart}(.*?){/jb_iconic_heart}#s') ,
			'jb_iconic_tag'       => array('<span class="jb_iconic_tag"></span><span>***code***</span>', '#{jb_iconic_tag}(.*?){/jb_iconic_tag}#s') ,
			'jb_iconic_arrival'   => array('<span class="jb_iconic_arrival"></span><span>***code***</span>', '#{jb_iconic_arrival}(.*?){/jb_iconic_arrival#s') ,
			'jb_iconic_truck'     => array('<span class="jb_iconic_truck"></span><span>***code***</span>', '#{jb_iconic_truck}(.*?){/jb_iconic_truck}#s') ,
			'jb_iconic_arrow'     => array('<span class="jb_iconic_arrow"></span><span>***code***</span>', '#{jb_iconic_arrow}(.*?){/jb_iconic_arrow}#s') ,
			'jb_iconic_article'   => array('<span class="jb_iconic_article"></span><span>***code***</span>', '#{jb_iconic_article}(.*?){/jb_iconic_article}#s') ,
			'jb_iconic_email'     => array('<span class="jb_iconic_email"></span><span>***code***</span>', '#{jb_iconic_email}(.*?){/jb_iconic_email}#s') ,
			'jb_iconic_beaker'    => array('<span class="jb_iconic_beaker"></span><span>***code***</span>', '#{jb_iconic_beaker}(.*?){/jb_iconic_beaker}#s') ,
			'jb_iconic_book'      => array('<span class="jb_iconic_book"></span><span>***code***</span>', '#{jb_iconic_book}(.*?){/jb_iconic_book}#s') ,
			'jb_iconic_bolt'      => array('<span class="jb_iconic_bolt"></span><span>***code***</span>', '#{jb_iconic_bolt}(.*?){/jb_iconic_bolt}#s') ,
			'jb_iconic_box'       => array('<span class="jb_iconic_box"></span><span>***code***</span>', '#{jb_iconic_box}(.*?){/jb_iconic_box}#s') ,
			'jb_iconic_calendar'  => array('<span class="jb_iconic_calendar"></span><span>***code***</span>', '#{jb_iconic_calendar}(.*?){/jb_iconic_calendar}#s') ,
			'jb_iconic_comment'   => array('<span class="jb_iconic_comment"></span><span>***code***</span>', '#{jb_iconic_comment}(.*?){/jb_iconic_comment}#s') ,
			'jb_iconic_tick'      => array('<span class="jb_iconic_tick"></span><span>***code***</span>', '#{jb_iconic_tick}(.*?){/jb_iconic_tick}#s') ,
			'jb_iconic_cloud'     => array('<span class="jb_iconic_cloud"></span><span>***code***</span>', '#{jb_iconic_cloud}(.*?){/jb_iconic_cloud}#s') ,
			'jb_iconic_document'  => array('<span class="jb_iconic_document"></span><span>***code***</span>', '#{jb_iconic_document}(.*?){/jb_iconic_document}#s') ,
			'jb_iconic_image'     => array('<span class="jb_iconic_image"></span><span>***code***</span>', '#{jb_iconic_image}(.*?){/jb_iconic_image}#s') ,
			'jb_iconic_quote'     => array('<span class="jb_iconic_quote"></span><span>***code***</span>', '#{jb_iconic_quote}(.*?){/jb_iconic_quote}#s') ,
			'jb_iconic_lightbulb' => array('<span class="jb_iconic_lightbulb"></span><span>***code***</span>', '#{jb_iconic_lightbulb}(.*?){/jb_iconic_lightbulb}#s') ,
			'jb_iconic_search'    => array('<span class="jb_iconic_search"></span><span>***code***</span>', '#{jb_iconic_search}(.*?){/jb_iconic_search}#s') ,
			'jb_iconic_mail'      => array('<span class="jb_iconic_mail"></span><span>***code***</span>', '#{jb_iconic_mail}(.*?){/jb_iconic_mail}#s') ,
			'jb_iconic_dash'      => array('<span class="jb_iconic_dash"></span><span>***code***</span>', '#{jb_iconic_dash}(.*?){/jb_iconic_dash}#s') ,
			'jb_iconic_movie'     => array('<span class="jb_iconic_movie"></span><span>***code***</span>', '#{jb_iconic_movie}(.*?){/jb_iconic_movie}#s') ,
			'jb_iconic_download'  => array('<span class="jb_iconic_download"></span><span>***code***</span>', '#{jb_iconic_download}(.*?){/jb_iconic_download}#s') ,

			// Icons
			'jb_new'        => array('<span class="jb_new">***code***</span>', '#{jb_new}(.*?){/jb_new}#s') ,
			'jb_code'       => array('<span class="jb_code">***code***</span>', '#{jb_code}(.*?){/jb_code}#s') ,
			'jb_attachment' => array('<span class="jb_attachment">***code***</span>', '#{jb_attachment}(.*?){/jb_attachment}#s') ,
			'jb_calculator' => array('<span class="jb_calculator">***code***</span>', '#{jb_calculator}(.*?){/jb_calculator}#s') ,
			'jb_cut'        => array('<span class="jb_cut">***code***</span>', '#{jb_cut}(.*?){/jb_cut}#s') ,
			'jb_dollar'     => array('<span class="jb_dollar">***code***</span>', '#{jb_dollar}(.*?){/jb_dollar}#s') ,
			'jb_euro'       => array('<span class="jb_euro">***code***</span>', '#{jb_euro}(.*?){/jb_euro}#s') ,
			'jb_dollar'     => array('<span class="jb_dollar">***code***</span>', '#{jb_dollar}(.*?){/jb_dollar}#s') ,
			'jb_pound'      => array('<span class="jb_pound">***code***</span>', '#{jb_pound}(.*?){/jb_pound}#s') ,
			'jb_support'    => array('<span class="jb_support">***code***</span>', '#{jb_support}(.*?){/jb_support}#s') ,
			'jb_next'       => array('<span class="jb_next">***code***</span>', '#{jb_next}(.*?){/jb_next}#s') ,
			'jb_previous'   => array('<span class="jb_previous">***code***</span>', '#{jb_previous}(.*?){/jb_previous}#s') ,
			'jb_calculator' => array('<span class="jb_calculator">***code***</span>', '#{jb_calculator}(.*?){/jb_calculator}#s') ,
			'jb_cart'       => array('<span class="jb_cart">***code***</span>', '#{jb_cart}(.*?){/jb_cart}#s') ,
			'jb_save'       => array('<span class="jb_save">***code***</span>', '#{jb_save}(.*?){/jb_save}#s') ,
			'jb_sound'      => array('<span class="jb_sound">***code***</span>', '#{jb_sound}(.*?){/jb_sound}#s') ,
			'jb_info'       => array('<span class="jb_info">***code***</span>', '#{jb_info}(.*?){/jb_info}#s') ,
			'jb_warning'    => array('<span class="jb_warning">***code***</span>', '#{jb_warning}(.*?){/jb_warning}#s') ,
			'jb_camera'     => array('<span class="jb_camera">***code***</span>', '#{jb_camera}(.*?){/jb_camera}#s') ,
			'jb_comment'    => array('<span class="jb_comment">***code***</span>', '#{jb_comment}(.*?){/jb_comment}#s') ,
			'jb_chat'       => array('<span class="jb_chat">***code***</span>', '#{jb_chat}(.*?){/jb_chat}#s') ,
			'jb_document'   => array('<span class="jb_document">***code***</span>', '#{jb_document}(.*?){/jb_document}#s') ,
			'jb_accessible' => array('<span class="jb_accessible">***code***</span>', '#{jb_accessible}(.*?){/jb_accessible}#s') ,
			'jb_star'       => array('<span class="jb_star">***code***</span>', '#{jb_star}(.*?){/jb_star}#s') ,
			'jb_heart'      => array('<span class="jb_heart">***code***</span>', '#{jb_heart}(.*?){/jb_heart}#s') ,
			'jb_mail'       => array('<span class="jb_mail">***code***</span>', '#{jb_mail}(.*?){/jb_mail}#s') ,
			'jb_film'       => array('<span class="jb_film">***code***</span>', '#{jb_film}(.*?){/jb_film}#s') ,
			'jb_pin'        => array('<span class="jb_pin">***code***</span>', '#{jb_pin}(.*?){/jb_pin}#s') ,
			'jb_recycle'    => array('<span class="jb_recycle">***code***</span>', '#{jb_recycle}(.*?){/jb_recycle}#s') ,
			'jb_lightbulb'  => array('<span class="jb_lightbulb">***code***</span>', '#{jb_lightbulb}(.*?){/jb_lightbulb}#s') ,

			//BB Code
			'jb_b'    => array('<strong>***code***</strong>', '#{jb_b}(.*?){/jb_b}#s') ,
			'jb_i'    => array('<em>***code***</em>', '#{jb_i}(.*?){/jb_i}#s') ,
			'jb_br'   => array('<br />', '#({jb_br})#s') ,

			// Other Tags
			'jb_span' => array('<span class="jbspan">***code***</span>', '#{jb_span}(.*?){/jb_span}#s') ,
			'jb_img'  => array('<img src="***code***" alt="***code***"/>', '#{jb_img}(.*?){/jb_img}#s')
		);

		// prepend and append code
		//$tagsToStrip     = array('title', 'div class="breadcrumb');
		//$tagsToExclude   = array('textarea', 'input', 'script');
		$breadcrumbRegex = '/\<(div|span).*class=".*breadcrumbs.*".*\>(.*[^0-9,\n\r]*.*)\<\/\1\>/im';
		if (preg_match_all($breadcrumbRegex, $output, $breadcrumbs, PREG_PATTERN_ORDER) > 0) {

			unset($breadcrumbRegex);

			$breadcrumbs = $breadcrumbs[2];

			$cleanbc = null;
			foreach ($breadcrumbs as $breadcrumb) {
				$cleanbc = $breadcrumb;

				foreach ($regex as $key => $value) {
					if (preg_match_all($value[1], $cleanbc, $matches, PREG_PATTERN_ORDER) > 0) {
						$matches = $matches[0];

						foreach ($matches as $match) {
							if ($key === 'jb_br')
							{
								$cleanbc = preg_replace($regex[$key][1], ' ', $cleanbc);
							}
							else
							{
								$cleanbc = preg_replace('/{'.$key.'}(.*[\n\r.]*.*){\/'.$key.'}/im', '$1', $cleanbc);
							}
						}
					}
				}
				$breadcrumb = preg_quote($breadcrumb );
				$breadcrumb = str_replace('#', '\#', $breadcrumb);
				$output = preg_replace('#('.$breadcrumb.')#Us',$cleanbc, $output);
			}

			unset($cleanbc, $breadcrumbs, $breadcrumb, $matches, $match, $key, $value);
		}

		if ( ! $enabled ) {
			foreach ($regex as $key => $value) {
				unset($value);
				$output = preg_replace( $regex[$key][1], '', $output );
			}

			return;
		}
		unset($enabled);

		// Remove jbtags from meta tags
		$metaRegex = '/(<meta.*content=")(.*)("[^>]*\/>)/im';
		if (preg_match_all($metaRegex, $output, $meta) > 0) {
			$i = 0;
			foreach($meta[0] as $metaDirty)
			{
				$metaClean = $meta[1][$i];
				$metaClean .= preg_replace('/{[\/]*jb_[^}]*}/i', '', $meta[2][$i]);
				$metaClean .= $meta[3][$i];
				$output = str_replace($metaDirty, $metaClean, $output);
				$i++;
			}
		}
		unset($metaRegex, $metaClean, $meta, $metaDirty, $i);

		// Remove jbtags from title tag
		$titleRegex = '/(<title[^>]*>)(.*)(<\/title>)/im';
		if (preg_match($titleRegex, $output, $title) > 0) {
			$titleClean = preg_replace('/{[\/]*jb_[^}]*}/im', ' ', $title[2]);
			$titleClean = preg_replace('/[\s]+/im', ' ', $titleClean);
			$titleClean = $title[1].trim($titleClean).$title[3];
			$output = str_replace($title[0], $titleClean, $output);
		}
		unset($titleRegex, $titleClean, $title);

		// Parse JB Tags
		$startcode       = '';
		$endcode         = '';
		$classes         = array();
		$uniqueClasses   = array();

		foreach ($regex as $key => $value) {
			// searching for marks
			// foreach ($tagsToExclude as $excludeTag) {
			// 				$tagStart = '<'.$excludeTag;
			// 				$element = explode(" ",$excludeTag,1);
			// 				$tagEnd = '</'.$element[0].'>';
			// 				$excludeRegex = "#".$tagStart."(.*?)".$tagEnd."#s";
			// 			}

			if (preg_match_all($value[1], $output, $matches, PREG_PATTERN_ORDER) > 0) {
				foreach ($matches[1] as $match) {

					$classes[] = $key;

					if (preg_match($jbListPattern, $value[1])) {
						$listMatches = explode("|", $match);
						$listCode = "";
						foreach ($listMatches as $listMatch) {
							$listCode .= str_replace("***listcode***", $listMatch, $jbListReplace );
						}

						$code = str_replace("***code***", $listCode, $value[0]);
					}
					else {
						$code = str_replace("***code***", $match, $value[0]);
					}

					if ($value[1] == "#({jb_br})#s")
					{
						$output = preg_replace($value[1], $startcode.$code.$endcode , $output );
					}
					else
					{
						$output = str_replace("{".$key."}".$match."{/".$key."}", $startcode.$code.$endcode , $output);
					}
				}
		 	}
		}
		unset(
			$key,
			$value,
			$regex,
			$match,
			$matches,
			$jbListPattern,
			$jbListReplace,
			$code,
			$startcode,
			$endcode/*,
				$tagsToStrip,
				$tagsToExclude,*/
			);

		if ( ! empty($classes)) {
			$uniqueClasses = array_unique($classes);
			$pageStyles = getTypeCss($iconStyle, $uniqueClasses, $jbTypeStyles);
			$document->addCustomTag($pageStyles);
			$output = str_replace('</head>', $pageStyles .'</head>', $output);
		}

		unset(
			$iconStyle,
			$uniqueClasses,
			$jbTypeStyles,
			$classes,
			$pageStyles,
			$document
			);

		// Remove tags from title in page
		$titleRegex = '/(<div[^>]*id="topHeaderRight">[\n.\s\t\w\r]*<h2[^>]*>)(.*)(<\/h2>)/im';
		if (preg_match_all($titleRegex, $output, $title) > 0) {
			$title = $title[2][0];
			$title = preg_replace('/<br[^>]*>/i', '&nbsp;', $title);
			$cleanTitle = strip_tags($title); // Remove just tags
			$output = preg_replace($titleRegex, '$1'.$cleanTitle.'$3', $output);
		}

		unset($titleRegex, $title, $cleanTitle, $attribs);

		JResponse::setBody($output);

		unset($output);

		return true;
	}
}
?>
