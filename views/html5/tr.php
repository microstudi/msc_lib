<?php
/**
* @file views/html5/tr.php
* @author Ivan Vergés
* @brief \<tr> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("tr",array('id'=>"tr1",'class'=>"my_class",'body'=>"TR html content"));\n
* //or\n
* echo m_view("tr","TR html content");
*
* @param body html content inside \<tr>...\</tr>
*/

$tag = "tr";
require("_generic_tag.php");


?>
