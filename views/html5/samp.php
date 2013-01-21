<?php
/**
* @file views/html5/samp.php
* @author Ivan Vergés
* @brief \<samp> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("samp",array('id'=>"div1",'class'=>"my_class",'body'=>"samp html content"));\n
* //or\n
* echo m_view("samp","samp html content");
*
* @param body html content inside \<samp>...\</samp>
*/

$tag = "samp";
require("_generic_tag.php");

?>
