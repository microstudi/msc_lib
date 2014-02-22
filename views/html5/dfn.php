<?php
/**
* @file views/html5/dfn.php
* @author Ivan Vergés
* @brief \<dfn> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("dfn",array('id'=>"div1",'class'=>"my_class",'body'=>"dfn html content"));\n
* //or\n
* echo m_view("dfn","dfn html content");
* </code>
*
* @param body html content inside \<dfn>...\</dfn>
*/

$tag = "dfn";
require("_generic_tag.php");
