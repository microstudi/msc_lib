<?php
/**
* @file views/html5/body.php
* @author Ivan Vergés
* @brief \<body> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("body",array('id'=>"body1",'class'=>"my_class",'body'=>"BODY html content"));\n
* //or\n
* echo m_view("body","BODY html content");
* </code>
*
* @param body html content inside \<body>...\</body>
*/

$tag = 'body';
require('_generic_tag.php');
