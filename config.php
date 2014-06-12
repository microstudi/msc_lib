<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Config file\n
 * Global vars used in the framework
 *
 * @category MSCLIB
 * @author Ivan Vergés
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * global config variable
 */
global $CONFIG;

///Global var $CONFIG
$CONFIG = new stdClass;
$CONFIG->version = 0.92;
///set to true to show errors
$CONFIG->debug = true;

///database conection class var
$CONFIG->db = null;
///default database type
$CONFIG->default_database = 'mysql';
$CONFIG->database_timezone = null;
//cache for SELECT clausules
$CONFIG->database_run_cache = null;
$CONFIG->database_run_cache_autoclear = false;
$CONFIG->database_cache = null;
$CONFIG->database_cache_enabled = false;
$CONFIG->database_cache_paused = false;
$CONFIG->database_cache_time = 0;
$CONFIG->database_log_queries = false;
$CONFIG->database_log = array('noncached' => array(), 'cached' => array(), 'runcached' => array());
$CONFIG->database_counter = array('select' => 0, 'cache'=> 0, 'nocache' =>0, 'cached' => 0, 'runcached' => 0);

///views path
$CONFIG->views_path = dirname(__FILE__) . '/views';
///default view (subdirectory in views path)
$CONFIG->default_view = 'html5';
///fallback view for non-existing views
$CONFIG->view_fallback = '';

///langs available
$CONFIG->lang_available = array();
///langs files
$CONFIG->lang_files = array();
///current lang
$CONFIG->lang = '';
///if empty codes must be searched in other messages
$CONFIG->lang_follow = true;
///used to store pairs lang_code => paths with files
$CONFIG->locale = array();
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
$CONFIG->inc_classes = array('sql', 'mysql', 'file', 'views', 'images', 'router', 'cache');

///default functions included
$CONFIG->inc_functions = array('general', 'sql', 'file', 'views', 'images', 'langs', 'sessions', 'text_utils', 'files', 'compressor', 'mail', 'router', 'media_embed');

