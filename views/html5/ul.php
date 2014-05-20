<?php
/**
* @file views/html5/ul.php
* @author Ivan Vergés
* @brief \<ul> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("ul",array('id'=>"ul1",'class'=>"my_class",'body'=>"UL html content"));\n
* //or\n
* echo m_view("ul","UL html content");\n
* //or\n
* $items = array();\n
* $items[] = "Item 1 content";\n
* $items[] = "Item 2 content";\n
* echo m_view("ul",array('id'=>"ul1",'class'=>"my_class",'items'=>$items));\n
* //or\n
* $items = array();\n
* $items[] = array('id'=>"item1",'title'=>"Item 1 title",'body'=>"Item 1 content");\n
* $items[] = array('id'=>"item2",'title'=>"Item 2 title",'body'=>"Item 2 content");\n
* echo m_view("ul",array('id'=>"ul1",'class'=>"my_class",'items'=>$items));\n
* </code>
*
* @param id html id
* @param class html class
* @param title html title
* @param style html style
* @param body html content inside \<ul>...\</ul>
* @param items if body not exists and this is a array, it will be used to display the internal \<li> elements of \<ul>, a the same time \b items could be a array with text or a sub-array with \b id, \b class, \b style, \b title & \b body sub-parameters
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<ul';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

echo '>';

if($body) echo $body;
elseif(is_array($vars['items'])) {
	foreach($vars['items'] as $item) {
		echo m_view('li', $item);
	}
}

echo '</ul>';
