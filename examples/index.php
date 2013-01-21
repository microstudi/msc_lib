<?php

include_once("../start.php");
//adds a path to look for views
m_view_add_path('views');

//adds a path to look for language translations
//and the languages to look for
$langs = array("en","es");
m_lang_set("langs",$langs);
//select the first lang
m_lang_select(current($langs));


$title = "MSC LIB Examples";
m_config_var("part", m_input('part') ? m_input('part') : 'default');
$body = m_view("parts/container", array('part' => m_config_var("part")));
$body .= m_view("parts/modal");
$head = m_view('parts/head');
$scripts = m_view('parts/scripts');

//showing the content
echo m_view("html", array('tagtitle' => $title, 'head' => $head, 'body' => $body . $scripts));