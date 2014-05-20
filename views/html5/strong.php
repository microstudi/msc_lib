<?php
/**
* @file views/html5/strong.php
* @author Ivan Vergés
* @brief \<strong> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("strong",array('id'=>"strong1",'class'=>"my_class",'body'=>"STRONG html content"));\n
* //or\n
* echo m_view("strong","STRONG html content");
* </code>
* @param body html content inside \<strong>...\</strong>
*/

$tag = 'strong';
require('_generic_tag.php');
