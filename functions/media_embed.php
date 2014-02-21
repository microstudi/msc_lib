<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Video auto-collector info from Youtube, Vimeo, etc
 *
 * @category MSCLIB
 * @package Utilities/MediaEmbed
 * @author Ivan Vergés
 */

/**
 * returns the url for a URL media (if exists)
 * @param  string $url [description]
 * @return string      full url with the thumbnail
 */
function m_media_url($url) {
	require_once(dirname(dirname(__FILE__)) . "/classes/media_embed.php");

	if(stripos($url, "http") !== 0) $url = "http://$url";
	$em = new MediaEmbed($url);
	//check if found anything useful
	$site = $em->get_site();
	$url = '';
	if($site != '') {
		$url = $em->get_url();
	}
	return $url;
}

/**
 * returns the url for a thumbnail of the specified URL media (if exists)
 * @param  string $url [description]
 * @param  string $size 'small' 'medium' 'large'
 * @return string      full url with the thumbnail
 */
function m_media_thumb($url, $size = 'small') {
	require_once(dirname(dirname(__FILE__)) . "/classes/media_embed.php");

	if(stripos($url, "http") !== 0) $url = "http://$url";
	$em = new MediaEmbed($url);
	//check if found anything useful
	$site = $em->get_site();
	$thumb = '';
	if($site != "")	{
		$thumb = $em->get_thumb($size);
	}
	return $thumb;
}

/**
 * returns the embed for a URL media (if exists)
 * @param  string $url [description]
 * @return string      full url with the thumbnail
 */
function m_media_embed($url, $width = 0, $height = 0) {
	require_once(dirname(dirname(__FILE__)) . "/classes/media_embed.php");

	if(stripos($url, "http") !== 0) $url = "http://$url";
	$em = new MediaEmbed($url);
	//check if found anything useful
	$site = $em->get_site();
	$iframe = '';
	if($site != "")	{
		$width  = (int) $width;
		$height = (int) $height;
		$wh = $em->get_size();
		if(empty($height) && $width) {
			//calculate height from width
			$height = round($wh['h'] * $width / $wh['w']);
		}
		elseif(empty($width) && $height) {
			//calculate height from width
			$width = round($wh['w'] * $height / $wh['h']);
		}
		if(empty($width))  $width = -1;
		if(empty($height)) $height = -1;
		$iframe = $em->get_iframe($width, $height);
		if($iframe == '') {
			$iframe = $em->get_embed($width, $height);
		}
	}
	return $iframe;
}

/**
 * returns all the parts for a URL media (if exists)
 * @param  string $url [description]
 * @param  string $size 'small' 'medium' 'large'
 * @return string      full url with the thumbnail
 */
function m_media_parts($url, $size = 'small', $width = 0, $height = 0) {
	require_once(dirname(dirname(__FILE__)) . "/classes/media_embed.php");

	if(stripos($url, "http") !== 0) $url = "http://$url";
	$em = new MediaEmbed($url);
	$site = $em->get_site();
	$parts = array('url' => '', 'title' => '', 'embed' => '', 'thumb' => '');
	//check if found anything useful
	if($site != "")	{
		$width  = (int) $width;
		$height = (int) $height;
		$wh = $em->get_size();
		if(empty($height) && $width) {
			//calculate height from width
			$height = round($wh['h'] * $width / $wh['w']);
		}
		elseif(empty($width) && $height) {
			//calculate height from width
			$width = round($wh['w'] * $height / $wh['h']);
		}
		if(empty($width))  $width = -1;
		if(empty($height)) $height = -1;
		$parts['url'] = $em->get_url();
		$parts['title'] = $em->get_title();
		$parts['thumb'] = $em->get_thumb($size);
		$parts['iframe'] = $em->get_iframe($width, $height);
		if($parts['iframe'] == '') {
			$parts['iframe'] = $em->get_embed($width, $height);
		}
	}
	return $parts;
}

/**
 * Returns any <embed> <object> <iframe> with corrected sizes
 * @param  string  $video The text containing the html tag <embed>...
 * @param  integer  $w    Width desired (could be 0 if Height is present, then will be auto-calculated)
 * @param  integer $h     Height desired (could be 0 to auto-calculate)
 * @return string         the result text with tags changed
 */
function m_parse_embed($video, $w=0, $h=0) {
	if($h && $w) {
		$video = preg_replace("#(width|height)=[\'\"](\d)+[\'\"]#ies","(strtolower('\\1')=='width') ? \"\\1='$w'\":\"\\1='$h'\"", $video);
	}
	elseif($w) {
		$video = preg_replace(array(
			"#height=[\'\"](\d+)[\'\"][ ]+width=[\'\"](\d+)[\'\"]#ies",
			"#width=[\'\"](\d+)[\'\"][ ]+height=[\'\"](\d+)[\'\"]#ies"
			),array(
			"'width=\"$w\" height=\"' . round($w*\\1/\\2) .'\"'",
			"'width=\"$w\" height=\"' . round($w*\\2/\\1) .'\"'"
			)
		, $video);
	}
	elseif($h) {
		$video = preg_replace(array(
			"#height=[\'\"](\d+)[\'\"][ ]+width=[\'\"](\d+)[\'\"]#ies",
			"#width=[\'\"](\d+)[\'\"][ ]+height=[\'\"](\d+)[\'\"]#ies"
			),array(
			"'height=\"$h\" width=\"' . round($h*\\2/\\1) .'\"'",
			"'height=\"$h\" width=\"' . round($h*\\1/\\2) .'\"'"
			)
		, $video);
	}

	if(preg_match("/<iframe/i", $video)) {
		$video = preg_replace('#src="(.*)youtube.com/embed/([a-z0-9_-]*)([\?]*)(.*)#i',"src=\"\\1youtube.com/embed/\\2?wmode=transparent&amp;\\4", $video);
	}
	if(preg_match("/<object/i", $video) && !preg_match('/<param name="wmode" value="transparent">/i', $video)) {
		$video = preg_replace("#</object>#",'<param name="wmode" value="transparent"></object>', $video);
	}
	if(preg_match("/<embed/i", $video) && !preg_match('/wmode="transparent"/i', $video)) {
		$video = preg_replace("#<embed #",'<embed wmode="transparent" ', $video);
	}
	return $video;
}
