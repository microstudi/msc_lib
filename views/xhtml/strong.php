<?php
/**
* @file views/xhtml/strong.php
* @author Ivan Vergés
* @brief \<strong> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("strong",array('id'=>"strong1",'class'=>"my_class",'body'=>"STRONG html content"));\n
* //or\n
* echo m_view("strong","STRONG html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<strong>...\</strong>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<strong';
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

echo '</strong>';
?>
