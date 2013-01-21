<?php
/**
* @file views/xhtml/td.php
* @author Ivan Vergés
* @brief \<td> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("td",array('id'=>"td1",'class'=>"my_class",'body'=>"TD html content"));\n
* //or\n
* echo m_view("td","TD html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<td>...\</td>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<td';
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

echo '</td>';

?>
