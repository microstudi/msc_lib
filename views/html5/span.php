<?php
/**
* @file views/html5/span.php
* @author Ivan Vergés
* @brief \<span> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("span",array('id'=>"span1",'class'=>"my_class",'body'=>"SPAN html content"));\n
* //or\n
* echo m_view("span","SPAN html content");
* </code>
*
* @param body html content inside \<span>...\</span>
*/

$tag = 'span';
require('_generic_tag.php');
