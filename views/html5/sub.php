<?php
/**
* @file views/html5/sub.php
* @author Ivan Vergés
* @brief \<sub> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("sub",array('id'=>"sub1",'class'=>"my_class",'body'=>"sub html content"));\n
* //or\n
* echo m_view("sub","sub html content");
*
* @param body html content inside \<sub>...\</sub>
*/

$tag = "sub";
require("_generic_tag.php");

?>
