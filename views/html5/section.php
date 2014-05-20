<?php
/**
* @file views/html5/section.php
* @author Ivan Vergés
* @brief \<section> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("section",array('id'=>"div1",'class'=>"my_class",'body'=>"section html content"));\n
* //or\n
* echo m_view("section","section html content");
* </code>
*
* @param body html content inside \<section>...\</section>
*/

$tag = 'section';
require('_generic_tag.php');