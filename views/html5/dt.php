<?php
/**
* @file views/html5/dt.php
* @author Ivan Vergés
* @brief \<dt> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("dt",array('id'=>"div1",'class'=>"my_class",'body'=>"dt html content"));\n
* //or\n
* echo m_view("dt","dt html content");
* </code>
*
* @param body html content inside \<b>...\</b>
*/

$tag = "dt";
require("_generic_tag.php");
