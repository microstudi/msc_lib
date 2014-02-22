<?php
/**
* @file views/html5/cite.php
* @author Ivan Vergés
* @brief \<cite> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("cite",array('id'=>"div1",'class'=>"my_class",'body'=>"cite html content"));\n
* //or\n
* echo m_view("cite","cite html content");
* </code>
*
* @param body html content inside \<b>...\</b>
*/

$tag = "cite";
require("_generic_tag.php");
