<?php
/**
* @file views/html5/caption.php
* @author Ivan Vergés
* @brief \<caption> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("caption",array('id'=>"div1",'class'=>"my_class",'body'=>"caption html content"));\n
* //or\n
* echo m_view("caption","caption html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "caption";
require("_generic_tag.php");

?>
