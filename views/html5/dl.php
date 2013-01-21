<?php
/**
* @file views/html5/dl.php
* @author Ivan Vergés
* @brief \<dl> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("dl",array('id'=>"div1",'class'=>"my_class",'body'=>"dl html content"));\n
* //or\n
* echo m_view("dl","dl html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "dl";
require("_generic_tag.php");

?>
