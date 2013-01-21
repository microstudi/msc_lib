<?php
/**
* @file views/xhtml/th.php
* @author Ivan Vergés
* @brief \<th> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("th",array('id'=>"th1",'class'=>"my_class",'body'=>"TH html content"));\n
* //or\n
* echo m_view("th","TH html content");
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<th>...\</th>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<th';
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

echo '</th>';

?>
