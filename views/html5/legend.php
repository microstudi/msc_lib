<?php
/**
* @file views/html5/legend.php
* @author Ivan Vergés
* @brief \<legend> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("legend",array('id'=>"div1",'class'=>"my_class",'body'=>"legend html content"));\n
* //or\n
* echo m_view("legend","legend html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "legend";
require("_generic_tag.php");

?>
