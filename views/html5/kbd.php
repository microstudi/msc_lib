<?php
/**
* @file views/html5/kbd.php
* @author Ivan Vergés
* @brief \<kbd> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("kbd",array('id'=>"div1",'class'=>"my_class",'body'=>"kbd html content"));\n
* //or\n
* echo m_view("kbd","kbd html content");
*
* @param body html content inside \<kbd>...\</kbd>
*/

$tag = "kbd";
require("_generic_tag.php");

?>
