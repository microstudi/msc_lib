<?php
/**
* @file views/html5/em.php
* @author Ivan Vergés
* @brief \<em> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("code",array('id'=>"code1",'class'=>"my_class",'body'=>"code html content"));\n
* //or\n
* echo m_view("code","cite html content");
* </code>
*/

$tag = 'em';
require('_generic_tag.php');
