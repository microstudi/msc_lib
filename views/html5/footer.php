<?php
/**
* @file views/html5/footer.php
* @author Ivan Vergés
* @brief \<footer> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("footer",array('id'=>"div1",'class'=>"my_class",'body'=>"footer html content"));\n
* //or\n
* echo m_view("footer","footer html content");
* </code>
*
* @param body html content inside \<b>...\</b>
*/

$tag = "footer";
require("_generic_tag.php");
