<?php
/**
* @file views/xhtml/span.php
* @author Ivan Vergés
* @brief \<span> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("span",array('id'=>"span1",'class'=>"my_class",'body'=>"SPAN html content"));\n
* //or\n
* echo m_view("span","SPAN html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<span>...\</span>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<span';
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

echo '</span>';
?>
