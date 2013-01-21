<?php
/**
* @file views/html5/nav.php
* @author Ivan Vergés
* @brief \<nav> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("nav",array('id'=>"div1",'class'=>"my_class",'body'=>"NAV html content"));\n
* //or\n
* echo m_view("nav","NAV html content");
*
* @param body html content inside \<nav>...\</nav>
*/

$tag = "nav";
require("_generic_tag.php");

?>
