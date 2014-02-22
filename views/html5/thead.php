<?php
/**
* @file views/html5/thead.php
* @author Ivan Vergés
* @brief \<thead> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("thead",array('id'=>"thead1",'class'=>"my_class",'body'=>"thead html content"));\n
* //or\n
* echo m_view("thead","thead html content");
* </code>
*
* @param body html content inside \<thead>...\</thead>
*/

$tag = "thead";
require("_generic_tag.php");
