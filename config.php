<?php
/**
* @file config/config.php
* @author Ivan Vergés
* @brief Config file\n
* Global vars used in the framework
*
*/
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

//sets global
global $CONFIG;

///Global var $CONFIG
$CONFIG = new stdClass;
$CONFIG->version = 0.9;
///set to true to show errors
$CONFIG->debug = true;

///database conection class var
$CONFIG->db = null;
///default database type
$CONFIG->default_database = 'mysql';

///views path
$CONFIG->views_path = dirname(__FILE__) . "/views";
///default view (subdirectory in views path)
$CONFIG->default_view = "html5";
///fallback view for non-existing views
$CONFIG->view_fallback = "";

///langs available
$CONFIG->lang_available = array();
///langs files
$CONFIG->lang_files = array();
///current lang
$CONFIG->lang = '';
///used to store pairs lang_code => paths with files
$CONFIG->locale = array();
$CONFIG->locale_lang = array();
///prefix to be added to the string code in case of lang error (by default \b {)
$CONFIG->lang_error_prefix = '';
///sufix to be added to the string code in case of lang error (by default \b })
$CONFIG->lang_error_sufix = '';

///cache image
$CONFIG->image_cache = null;
$CONFIG->image_cache_dir = null;
$CONFIG->image_cache_type = 'local';
$CONFIG->image_fallback_type = null;
$CONFIG->image_fallback_text = null;

///Router
$CONFIG->router = null;

///File
$CONFIG->file_url_prefix = null;
$CONFIG->file_remote_fp = null;

///php mailer
$CONFIG->mailer = null;

///javascript compressor
$CONFIG->js_compressor = null;
///css compressor
$CONFIG->css_compressor = null;

///custom vars
$CONFIG->custom_vars = array();

///default classes included
$CONFIG->inc_classes = array("sql", "mysql", "file", "views", "images", "JSCompressor", "CSSCompressor", 'router', 'cache', 'media_embed');

///default functions included
$CONFIG->inc_functions = array("general", "sql", "file", "views", "images", "langs", "sessions", "text_utils", "files", "compressor", "mail", 'router', 'media_embed');

