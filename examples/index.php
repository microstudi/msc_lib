<?php

//we want to see the errors
ini_set("display_errors",1);
//msc_lib include
include_once("../start.php");

//adds a path to look for views
m_view_add_path('views');

//adds a path to look for language translations
//and the languages to look for
m_lang_set("langs", array("en","es"));
//select the first lang available from GET, Browser or automatically the first available
$lang = m_input("lang", substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) );
$lang = m_lang_select($lang);

//get the current view
$view = m_input('view', 'default');

$options = array('default', 'views', 'mimage', 'mfile', 'routing', 'lang', 'sql', 'misc');

$menu = array();
foreach($options as $part) {
	$href = '?' . http_build_query(array('view' => $part) + $_GET);
	$active = ($view == $part ? 'active' : '');
	$menu[] = array('class' => $active, 'id' => "m-$part", 'body' => m_view("a", array('href' => $href, 'body' => m_lang_echo("menu-$part"))));
}

//a var from the _GET or _POST vars to know if we are running examples
$run = m_input("run");

//the the content of the menu without error message
$body = m_view("parts/$view", array( 'menu' => $menu, 'run' => $run), true);

//if empty view, redirect to default
//redirect if no content (or doesn't exists)
if(empty($body) && $view != 'default') m_forward("index.php");


//if where are running an example, die here
if(isset($run) && $body) {
	die($body);
}

//get the current title from a global var (if some view has defined it)
$title = m_config_var("title");
//get the title for the page from languages
if(!$title) $title   = m_lang_echo("default_title");

//main container for the views
$body    = m_view("common/container", array('menu' => $menu, 'body' => $body));
//usefull modal from bootstrap
$body   .= m_view("common/modal");
//custom <head></head> section
$head    = m_view('common/head');
//some javascript here
$scripts = m_view('common/scripts', array('view' => $view));

//shows the content
echo m_view("html", array('tagtitle' => $title, 'head' => $head, 'body' => $body . $scripts));