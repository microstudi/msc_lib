<?php
/**
* @file views/html5/sup.php
* @author Ivan Vergés
* @brief \<sup> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("sup",array('id'=>"sup1",'class'=>"my_class",'body'=>"sup html content"));\n
* //or\n
* echo m_view("sup","sup html content");
* </code>
*
* @param body html content inside \<sup>...\</sup>
*/

$tag = "sup";
require("_generic_tag.php");
