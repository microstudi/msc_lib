<?php
/**
* @file views/html5/pre.php
* @author Ivan Vergés
* @brief \<pre> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("pre",array('id'=>"pre1",'class'=>"my_class",'body'=>"PRE html content"));\n
* //or\n
* echo m_view("pre","PRE html content");
*
* @param body html content inside \<pre>...\</pre>
*/

$tag = "pre";
require("_generic_tag.php");

?>
