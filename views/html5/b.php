<?php
/**
* @file views/html5/b.php
* @author Ivan Vergés
* @brief \<b> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("b",array('id'=>"div1",'class'=>"my_class",'body'=>"B html content"));\n
* //or\n
* echo m_view("b","B html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "b";
require("_generic_tag.php");

?>
