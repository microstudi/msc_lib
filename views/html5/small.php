<?php
/**
* @file views/html5/small.php
* @author Ivan Vergés
* @brief \<small> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("small",array('id'=>"small1",'class'=>"my_class",'body'=>"small html content"));\n
* //or\n
* echo m_view("small","small html content");
*
* @param body html content inside \<small>...\</small>
*/

$tag = "small";
require("_generic_tag.php");

?>