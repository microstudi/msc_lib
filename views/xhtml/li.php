<?php
/**
* @file views/xhtml/li.php
* @author Ivan Vergés
* @brief \<li> tag for the default XHTML view\n
* @section usage Usage
* echo m_view("li",array('id'=>"item1",'class'=>"my_class",'body'=>"Item 1 description"));\n
* //or\n
* echo m_view("li","Item 1 description");
*
* @param id html id
* @param class html class
* @param title html title
* @param style html style
* @param body html content inside \<li>...\</li>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<li';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//title
echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

echo '>';

echo $body;

echo '</li>';

?>
