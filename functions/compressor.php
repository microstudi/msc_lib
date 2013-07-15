<?php
/**
* @file functions/compressor.php
* @author Ivan Vergés
* @brief Javascript & CSS Compressor functions file\n
* This functions uses the classes JSCompressor & CSSCompressor
*
*/

/**
 * Sets the cache folder
 * @param $dir cache folder, tries to create it if not exists
 * */
function m_compressor_cache_dir($dir='') {
	global $CONFIG;

	if( ! $CONFIG->css_compressor instanceOf CSSCompressor) $CONFIG->css_compressor = new CSSCompressor(null,$dir);
	else $CONFIG->css_compressor->setCacheDir($dir);

	if( ! $CONFIG->js_compressor instanceOf JSCompressor) $CONFIG->js_compressor = new JSCompressor(null,$dir);
	else $CONFIG->js_compressor->setCacheDir($dir);
}
/**
 * compress a dir or a file of CSS, returns data
 * @param $dir if $dir is a file then returns the compressed data, if is a directory returns all files inside joined & compressed
 * @param $mode 'code' returns the raw css code compressed, 'file' returns the path to the compressed file generated
 * */
function m_css_compressor($dir,$mode='code') {
	global $CONFIG;

	if( ! $CONFIG->css_compressor instanceOf CSSCompressor) $CONFIG->css_compressor = new CSSCompressor($dir);
	else $CONFIG->css_compressor->setDirs($dir);

	$pack = $CONFIG->css_compressor->pack();

	if($mode == 'code') return $pack;
	else return $CONFIG->css_compressor->get_cache_name();
}
/**
 * compress a dir or a file of JavaScript, returns data
 * @param $dir if $dir is a file then returns the compressed data, if is a directory returns all files inside joined & compressed
 * @param $mode 'code' returns the raw css code compressed, 'file' returns the path to the compressed file generated
 * @param $encoding : None', 'Numeric', 'Normal', 'High ASCII'
 * */
function m_js_compressor($dir,$mode='code',$encoding='Normal') {
	global $CONFIG;

	if( ! $CONFIG->js_compressor instanceOf JSCompressor) $CONFIG->js_compressor = new JSCompressor($dir);
	else $CONFIG->js_compressor->setDirs($dir);

	$pack = $CONFIG->js_compressor->pack($encoding);

	if($mode == 'code') return $pack;
	else return $CONFIG->js_compressor->get_cache_name();
}
