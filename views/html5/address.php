<?php
/**
* @file views/html5/address.php
* @author Ivan Vergés
* @brief \<address> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("address",array('id'=>"address1",'class'=>"my_class",'body'=>"address html content"));\n
* //or\n
* echo m_view("address","address html content");
*
* @param body html content inside \<address>...\</address>
*/

$tag = "address";
require("_generic_tag.php");

?>
