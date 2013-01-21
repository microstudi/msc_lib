<?php
/**
* @file views/html5/aside.php
* @author Ivan Vergés
* @brief \<aside> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("aside",array('id'=>"aside1",'class'=>"my_class",'body'=>"aside html content"));\n
* //or\n
* echo m_view("aside","aside html content");
*
* @param body html content inside \<aside>...\</aside>
*/

$tag = "aside";
require("_generic_tag.php");

?>
