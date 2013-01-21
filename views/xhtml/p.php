<?php
/**
* @file views/xhtml/p.php
* @author Ivan Vergés
* @brief \<p> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("p",array('id'=>"p1",'class'=>"my_class",'body'=>"P html content"));\n
* //or\n
* echo m_view("p","P html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<p>...\</p>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<p';
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

echo '</p>';
?>
