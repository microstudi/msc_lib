<?php
/**
* @file views/html5/mark.php
* @author Ivan Vergés
* @brief \<mark> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("mark",array('id'=>"div1",'class'=>"my_class",'body'=>"mark html content"));\n
* //or\n
* echo m_view("mark","mark html content");
* </code>
*
* @param body html content inside \<b>...\</b>
*/

$tag = "mark";
require("_generic_tag.php");
