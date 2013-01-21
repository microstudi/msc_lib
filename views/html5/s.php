<?php
/**
* @file views/html5/s.php
* @author Ivan Vergés
* @brief \<s> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("s",array('id'=>"div1",'class'=>"my_class",'body'=>"s html content"));\n
* //or\n
* echo m_view("s","s html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "s";
require("_generic_tag.php");

?>
