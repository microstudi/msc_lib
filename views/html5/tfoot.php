<?php
/**
* @file views/html5/tfoot.php
* @author Ivan Vergés
* @brief \<tfoot> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("tfoot",array('id'=>"tfoot1",'class'=>"my_class",'body'=>"tfoot html content"));\n
* //or\n
* echo m_view("tfoot","tfoot html content");
* </code>
*
* @param body html content inside \<tfoot>...\</tfoot>
*/

$tag = 'tfoot';
require('_generic_tag.php');
