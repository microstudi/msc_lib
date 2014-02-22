<?php
/**
* @file views/html5/dd.php
* @author Ivan Vergés
* @brief \<dd> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("dd",array('id'=>"div1",'class'=>"my_class",'body'=>"dd html content"));\n
* //or\n
* echo m_view("dd","dd html content");
* </code>
*
* @param body html content inside \<b>...\</b>
*/

$tag = "dd";
require("_generic_tag.php");
