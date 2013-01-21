<?php
/**
* @file views/html5/p.php
* @author Ivan Vergés
* @brief \<p> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("p",array('id'=>"p1",'class'=>"my_class",'body'=>"P html content"));\n
* //or\n
* echo m_view("p","P html content");
*
* @param body html content inside \<p>...\</p>
*/

$tag = "p";
require("_generic_tag.php");

?>
