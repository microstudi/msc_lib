<?php
/**
* @file views/html5/figure.php
* @author Ivan Vergés
* @brief \<figure> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("figure",array('id'=>"div1",'class'=>"my_class",'body'=>"figure html content"));\n
* //or\n
* echo m_view("figure","figure html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "figure";
require("_generic_tag.php");

?>
