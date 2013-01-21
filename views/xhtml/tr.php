<?php
/**
* @file views/xhtml/tr.php
* @author Ivan Vergés
* @brief \<tr> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("tr",array('id'=>"tr1",'class'=>"my_class",'body'=>"TR html content"));\n
* //or\n
* echo m_view("tr","TR html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<tr>...\</tr>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<tr';
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

echo '</tr>';

?>
