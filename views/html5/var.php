<?php
/**
* @file views/html5/var.php
* @author Ivan Vergés
* @brief \<var> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("var",array('id'=>"div1",'class'=>"my_class",'body'=>"var html content"));\n
* //or\n
* echo m_view("var","var html content");
* </code>
*
* @param body html content inside \<var>...\</var>
*/

$tag = "var";
require("_generic_tag.php");
