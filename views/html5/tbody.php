<?php
/**
* @file views/html5/tbody.php
* @author Ivan Vergés
* @brief \<tbody> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("tbody",array('id'=>"tbody1",'class'=>"my_class",'body'=>"tbody html content"));\n
* //or\n
* echo m_view("tbody","tbody html content");
*
* @param body html content inside \<tbody>...\</tbody>
*/

$tag = "tbody";
require("_generic_tag.php");

?>
