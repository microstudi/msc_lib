<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Image Manipulation functions
 * This collection of functions manipulates images by resizing, mixing or writting
 *
 * @category MSCLIB
 * @package Images
 * @author Ivan Vergés
 */

/**
 * Sets the cache folder (or tries to create it if not exists)
 *
 * Example
 * <code>
 * // This will flush the image resized at 200x150 by cropping width or height if necessary
 * m_image('my_file.jpg', 200, 150, 1);
 * die;
 * </code>
 *
 * @uses mCache
 * @uses m_file_url_prefix()
 */
function m_image_cache($dir = '', $type = 'local') {
	global $CONFIG;

	if($type == 'remote' && $CONFIG->file_remote_fp instanceOf mFile) {
		$CONFIG->image_cache = new mCache($dir, $CONFIG->file_remote_fp, m_file_url_prefix());
	}
	else {
		if(!empty($dir) || !($CONFIG->image_cache instanceOf mCache)) {
			$CONFIG->image_cache = new mCache($dir, 'local');
		}
	}

	return $CONFIG->image_cache->type;

}

/**
 * Sets the fallback image
 *
 * @param  string $type if 'auto' and $text exists, a image will be generated with this text
 *                      if 'path/to/image.jpg', then this image will be presented as a fallback
 *                      						if $text exists, will be writed over
 * @param  [type] $text the fallback text
 */
function m_image_set_fallback($type = 'auto', $text = null) {
	global $CONFIG;
	$CONFIG->image_fallback_type = $type;
	$CONFIG->image_fallback_text = $text;
}

/**
 * Creates and resize a image if needed, returns image data or the gd resource
 *
 * Example
 * <code>
 * // This will flush the image resized at 200x150 by cropping width or height if necessary
 * m_image('my_file.jpg', 200, 150, 1);
 * die;
 * </code>
 *
 * @uses mCache
 * @uses mImage
 *
 * @param $file original file to process, could be a string location, url, gd resource or a mImage class
 * @param $width new width of the file to be returned (leaving empty or 0 means the original width)
 * @param $height new height of the file to be returned (leaving empty or 0 means the original height)
 * @param $proportional
 *     <b>0</b>: the image will be resized to the specified w/h without keeping aspect ratio<br>
 *     <b>1</b>: the image will be resized to the specified w/h keeping aspect ratio (by cropping width or height)<br>
 *     <b>2</b>: the image will be resized to the max w/h keeping aspect ratio (without cropping)<br>
 *     <b>3</b>: the image will be resized to the min w/h keeping aspect ratio (without cropping)<br>
 * @param $return
 *     <b>flush</b> returns the new image directly (as GD methods imagejpeg(), imagepng() do)<br>
 *     <b>gd</b> returns the gd resource (doesn't apply the cache)<br>
 *     <b>mimage</b> returns the mImage resource (doesn't apply the cache)<br>
 * @param quality JPEG quality (PNG or GIF does not uses this parameter)
 *
 * @return mixed Returns according the specified <b>$return</b> value
 */
function m_image($file, $width = 0, $height = 0, $proportional = 1, $return = 'flush', $quality = 90) {
	global $CONFIG;

    ignore_user_abort(true);
    $file_name = $file;
    //get the string filename
	if($file instanceOf mImage) $file_name = $file->file();

	$cache_file = false;
	if($file && in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$cache_file = md5(dirname($file_name)) . "-".((int)$width) . "x" . ((int)$height) . "-$proportional-$quality-" . basename($file_name);
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			if(!($file instanceOf mImage)) {
				ob_end_clean();
	    		header("Connection: close", true);
				mImage::stream($f, false);
				//close connection with browser
	    		ob_end_flush();
    			flush();
				//check if file is newer
				if(!$CONFIG->image_cache->expired($cache_file, @filemtime($file_name))) {
					exit;
				}
				//continue to force rebuild cache
			}
			else {
				//exits
				mImage::stream($f);
			}
		}
	}
	$im = new mImage($file);
	$im->fallback($CONFIG->image_fallback_type, $CONFIG->image_fallback_text);
	$im->proportional($proportional);
	$im->quality($quality);

	$im->resize($width, $height);

	//save the cache
	if($cache_file && !$im->has_errors()) {
		$tmp = tempnam(m_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	ignore_user_abort(false);
	if($return == 'flush')  return $im->flush();
	elseif($return == 'gd') return $im->gd();
	else return $im;
}

/**
 * Resizes and mix images
 *
 * Example
 * <code>
 * // This will flush the image resized at 200x150 by cropping width or height if necessary
 * m_image('my_file.jpg', 200, 150, 1);
 * die;
 * </code>
 *
 * @uses mCache
 * @uses mImage
 *
 * @param  string  $file        original image, could be a gd resource
 * @param  array   $options     array with width, height, proportional for the original images
 * @param  mixed   $mix_images  if string, the file to mix
 *                              if array, several images with options:
 *                              array('image_file.jpg' => array(
 *                              	'width' => w,			//0 for original size, integer to resize
 *                              	'height' => h,			//0 for original size, integer to resize
 *                              	'proportional' => 1,	//aspect ration maintainer
 *                              	'position_x' => posx,	//left, right, center, integer
 *                              	'position_y' => posy,	// top, bottom, center, integer
 *                              	'alpha' => 70 			// from 0 to 100
 *                              	))
 * @param  boolean $return_data [description]
 * @param  integer $quality     [description]
 * @return mixed Returns according the specified <b>$return</b> value
 */
function m_image_mix($file, $mix_images = null, $options = null, $return = 'flush', $quality = 90) {
	global $CONFIG;

	$default_ops = array('width' => 0, 'height' => 0, 'proportional' => 1);
	if(!is_array($options)) $options = $default_ops;
	foreach($default_ops as $k => $v) if(!array_key_exists($k, $options)) $options[$k] = $v;

	//opcions per defecte per a la mescla d'imatges
	$default_mix_ops = array('width' => 0, 'height' => 0, 'proportional' => 1, 'position_x' => "center", 'position_y' => "center", 'alpha' => 50);

	$mix = array();
	if(is_string($mix_images))  $mix = array($mix_images => $default_mix_ops);
	elseif(is_array($mix_images)) {
		foreach($mix_images as $key => $val) {
			if(is_array($val)) {
				foreach($default_mix_ops as $k => $v) if(!array_key_exists($k, $val)) $val[$k] = $v;
				$mix[$key] = $val;
			}
			else $mix[$val] = $default_mix_ops;
		}
	}

    ignore_user_abort(true);
    $file_name = $file;
    //get the string filename
	if($file instanceOf mImage) $file_name = $file->file();

	$cache_file = false;
	if($file && in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$cache_file = md5(dirname($file_name) . "-" . serialize($mix) . serialize($options)) . basename($file_name);
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			if(!($file instanceOf mImage)) {
				ob_end_clean();
	    		header("Connection: close", true);
				mImage::stream($f, false);
				//close connection with browser
	    		ob_end_flush();
    			flush();
				//check if file is newer
				if(!$CONFIG->image_cache->expired($cache_file, @filemtime($file_name))) {
					exit;
				}
				//continue to force rebuild cache
			}
			else {
				//exits
				mImage::stream($f);
			}
		}
	}

	$im = new mImage($file);

	$im->proportional($options['proportional']);
	$im->quality($quality);

	$im->resize($options['width'], $options['height']);
	//falta executar options

	foreach($mix as $f => $op) {
		//resample
		$im2 = new mImage($f);
		$im2->proportional($op['proportional']);
		$im2->quality($quality);
		$im2->resize($op['width'], $op['height']);
		$im->mix_gd($im2->gd(), $op['position_x'], $op['position_y'], $op['alpha']);
		$im2->destroy();
	}

	//save the cache
	if($cache_file && !$im->has_errors()) {
		$tmp = tempnam(m_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	ignore_user_abort(false);
	if($return == 'flush')  return $im->flush();
	elseif($return == 'gd') return $im->gd();
	else return $im;
}

/**
 * Creates and add a text to a image, returns image data or the gd resource
 *
 * @uses mCache
 * @uses mImage
 *
 * @param string $file original file to process, could be a gd or mimage resource
 * @param string $text text to write at the image
 * @param array $options
 * @param string $return_data if <b>true</b> returns the new image directly (as methods imagejpeg, imagepng does), if <b>false</b> returns the gd resource
 * @return mixed Returns according the specified <b>$return</b> value
 * */
function m_image_string($file, $options = array(), $return = 'flush') {
	global $CONFIG;

    ignore_user_abort(true);
 	$file_name = $file;
    //get the string filename
	if($file instanceOf mImage) $file_name = $file->file();

	$cache_file = false;
	if($file && in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$cache_file = md5(dirname($file_name) . "-" . serialize($options)) . basename($file_name);
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			if(!($file instanceOf mImage)) {
				ob_end_clean();
	    		header("Connection: close", true);
				mImage::stream($f, false);
				//close connection with browser
	    		ob_end_flush();
    			flush();
				//check if file is newer
				if(!$CONFIG->image_cache->expired($cache_file, @filemtime($file_name))) {
					exit;
				}
				//continue to force rebuild cache
			}
			else {
				//exits
				mImage::stream($f);
			}
		}
	}

	$default_ops = array('text' => '', 'color' => '000000', 'size' => 2, 'bgcolor' => 'transparent', 'margins' => array(1, 1, 1, 1));
	if(is_string($options)) $options = array('text' => $options) + $default_ops;
	elseif(is_array($options)) {
		foreach($default_ops as $k => $v) if(!array_key_exists($k, $options)) $options[$k] = $v;
	}

	$im = new mImage($file);
	if(!$file) $im->image_from_text($options['text'], $options['size'], $options['bgcolor'], $options['margins']);
	$im->add_string($options['text'], $options['color'], $options['size']);

	//save the cache
	if($cache_file && !$im->has_errors()) {
		$tmp = tempnam(m_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	ignore_user_abort(false);
	if($return == 'flush')  return $im->flush();
	elseif($return == 'gd') return $im->gd();
	else return $im;

}

