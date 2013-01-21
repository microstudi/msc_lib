<?php
/**
* @file views/html5/article.php
* @author Ivan Vergés
* @brief \<article> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("article",array('id'=>"div1",'class'=>"my_class",'body'=>"article html content"));\n
* //or\n
* echo m_view("article","article html content");
*
* @param body html content inside \<b>...\</b>
*/

$tag = "article";
require("_generic_tag.php");

?>
