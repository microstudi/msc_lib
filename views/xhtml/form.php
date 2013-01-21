<?php
/**
* @file views/xhtml/form.php
* @author Ivan Vergés
* @brief \<form> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("form",array('id'=>"form1",'class'=>"my_class",'body'=>"Form html content"));\n
* //or\n
* echo m_view("form","Form html content");
*
* @param id html id
* @param class html label class
* @param action html label action
* @param method html label method (post, get)
* @param style html label style
* @param enctype html label enctype (application/x-www-form-urlencoded, multipart/form-data)
* @param body html content inside \<form>...\</form>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<form';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');

echo ($vars['action'] ? ' action="'.$vars['action'].'"' : '');

echo ($vars['method'] ? ' method="'.$vars['method'].'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

//$enc = 'application/x-www-form-urlencoded';
//$enc = 'multipart/form-data';
echo ($vars['enctype'] ? ' enctype="'.$vars['enctype'].'"' : '');

echo '>';

echo $body;

echo '</form>';
?>
