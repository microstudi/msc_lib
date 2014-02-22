<?php
/**
* @file views/html5/code.php
* @brief \<code> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("code",array('id'=>"code1",'class'=>"my_class",'body'=>"code html content"));\n
* //or\n
* echo m_view("code","cite html content");
* </code>
*
* @author Ivan Vergés
*/

$tag = "code";
require("_generic_tag.php");
