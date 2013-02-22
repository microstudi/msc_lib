<?php
/**
* @file functions/images.php
* @author Ivan Vergés
* @brief Image Manipulation class\n
* This functions uses the class mImage defined in the file classes/images.php
*
* @section usage Usage
* m_image('my_file.jpg',300,200);
*/

/**
 * Sets the cache folder (or tries to create it if not exists)
 * */
function m_image_cache($dir='', $type='local') {
	global $CONFIG;

	if($type == 'remote' && $CONFIG->file_remote_fp instanceOf mFile) {
		$CONFIG->image_cache = new mCache($dir, $CONFIG->file_remote_fp, m_file_url_prefix());
	}
	else {
		$CONFIG->image_cache = new mCache($dir, 'local');
	}

	return $CONFIG->image_cache;

}

function m_image_set_fallback($type='auto', $text=null) {
	global $CONFIG;
	$CONFIG->image_fallback_type = $type;
	$CONFIG->image_fallback_text = $text;
}
/**
 * creates and resize a image if needed, returns image data or the gd resource
 * @param $file original file to process, could be a gd resource or a mImage class
 * @param $width new width of te file to be returned (leave empty/0 to bypass)
 * @param $height new height of te file to be returned (leave empty/0 to bypass)
 * @param $proportional
 * - \b 0 => the image will be resized to the specified w/h without keeping aspect ratio
 * - \b 1 => the image will be resized to the specified w/h keeping aspect ratio (by cropping width or height)
 * - \b 2 => the image will be resized to the max w/h keeping aspect ratio (without cropping).
 * - \b 3 => the image will be resized to the min w/h keeping aspect ratio (without cropping).
 * @param $return if \b 'flush' returns the new image directly (as methods imagejpeg, imagepng does),
 *                if \b 'gd' returns the gd resource (doesn't apply the cache)
 *                if \b 'mimage' returns the mImage resource (doesn't apply the cache)
 * @param quality JPEG quality (PNG or GIF does not uses this parameter)
 * */
function m_image($file, $width=0, $height=0, $proportional=1, $return='flush', $quality=90) {
	global $CONFIG;

	$cache_file = false;
	if($file && in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$_file = $file;
		if($file instanceOf mImage) $_file = $file->file();
		$name = md5(dirname($_file))."-".((int)$width) . "x" . ((int)$height) . "-$proportional-$quality-" . basename($_file);
		$cache_file = $name;
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			mImage::stream($f);
		}
	}

	$im = new mImage($file);
	$im->fallback($CONFIG->image_fallback_type, $CONFIG->image_fallback_text);
	$im->proportional($proportional);
	$im->quality($quality);

	$im->resize($width, $height);

	//save the cache
	if($cache_file && !$im->has_errors()) {
		$tmp = tempnam(sys_get_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	if($return=='flush') return $im->flush();
	elseif($return=='gd') return $im->gd();
	else return $im;
}

/**
 * Resizes and mix images
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
 * @return [type]               [description]
 */
function m_image_mix($file, $mix_images = null, $options = null, $return='flush', $quality=90) {
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

	$cache_file = false;
	if($file && in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$_file = $file;
		if($file instanceOf mImage) $_file = $file->file();
		$name = md5(dirname($_file)."-".serialize($mix) . serialize($options)) . basename($_file);

		$cache_file = $name;
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			mImage::stream($f);
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

	if($cache_file) {
		$tmp = tempnam(sys_get_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	if($return=='flush') return $im->flush();
	elseif($return=='gd') return $im->gd();
	else return $im;
}

/**
 * creates and add a text to a image, returns image data or the gd resource
 * @param string $file original file to process, could be a gd or mimage resource
 * @param string $text text to write at the image
 * @param array $options
 * @param string $return_data if \b true returns the new image directly (as methods imagejpeg, imagepng does), if \b false returns the gd resource
 * */
function m_image_string($file, $options = array(), $return='flush') {
	global $CONFIG;
	$cache_file = false;
	if(in_array($return, array('flush', 'data')) && !mImage::is_gd($file) && $CONFIG->image_cache instanceOf mCache) {
		$_file = $file;
		if($file instanceOf mImage) $_file = $file->file();
		if(!$_file) $_file = $options['text'];
		$name = md5(dirname($_file)."-".serialize($options)).basename($_file);

		$cache_file = $name;
		//returns the file or url if exists
		if($f = $CONFIG->image_cache->get($cache_file)) {
			mImage::stream($f);
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

	if($cache_file) {
		$tmp = tempnam(sys_get_temp_dir(), 'mimage');
		if($im->save($tmp)) {
			$CONFIG->image_cache->put($tmp, $cache_file);
			@unlink($tmp);
		}
		else die("error saving cache: $cache_file");
	}

	if($return=='flush') return $im->flush();
	elseif($return=='gd') return $im->gd();
	else return $im;

}

?>
