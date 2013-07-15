<?php
/**
* @file views/html5/header.php
* @author Ivan Vergés
* @brief \<header> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("header",array('id'=>"div1",'class'=>"my_class",'body'=>"HEADER html content"));\n
* //or\n
* echo m_view("header","HEADER html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "header";
require("_generic_tag.php");
