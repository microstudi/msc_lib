<?php
/**
* @file views/html5/h.php
* @author Ivan Vergés
* @brief \<h*> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("h",array('h'=>"1",'class'=>"my_class",'body'=>"H1 html content"));\n
* echo m_view("h",array('h'=>"2",'class'=>"my_class",'body'=>"H2 html content"));\n
* //or\n
* echo m_view("h","H1 html content");
*
* @param h 1 2 3 4 5 or 6
* @param body html content inside \<h*>...\</h*>
*/

$i = 1;

if(is_array($vars) && $vars['h']) $i = max( min( intval($vars['h']), 6 ), 1 );

$tag = "h$i";
require("_generic_tag.php");


?>
