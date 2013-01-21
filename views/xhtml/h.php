<?php
/**
* @file views/xhtml/h.php
* @author Ivan Vergés
* @brief \<h*> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("h",array('h'=>"1",'class'=>"my_class",'body'=>"H1 html content"));\n
* echo m_view("h",array('h'=>"2",'class'=>"my_class",'body'=>"H2 html content"));\n
* //or\n
* echo m_view("h","H1 html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<h*>...\</h*>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

$i = 1;

if($vars['h']) $i = $vars['h'];

echo "<h$i";
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

echo "</h$i>";
?>
