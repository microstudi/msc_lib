<?php
/**
* @file views/html5/div.php
* @author Ivan Vergés
* @brief \<div> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("div",array('id'=>"div1",'class'=>"my_class",'body'=>"DIV html content"));\n
* //or\n
* echo m_view("div","DIV html content");
* </code>
*
* @param body html content inside \<div>...\</div>
*/

$tag = 'div';
require('_generic_tag.php');
