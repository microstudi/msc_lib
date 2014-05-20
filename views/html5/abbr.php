<?php
/**
* @file views/html5/abbr.php
* @author Ivan Vergés
* @brief \<abbr> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("abbr",array('id'=>"abbr1",'class'=>"my_class",'body'=>"abbr html content"));\n
* //or\n
* echo m_view("abbr","abbr html content");
* </code>
*
* @param body html content inside \<abbr>...\</abbr>
*/

$tag = 'abbr';
require('_generic_tag.php');
