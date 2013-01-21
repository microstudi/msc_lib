<?php
/**
* @file views/xhtml/object.php
* @author Ivan Vergés
* @brief \<object> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("object",array('id'=>"object1",'class'=>"my_class",'body'=>"Object 1 description"));
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param onclick html label onclick
* @param data html label data
* @param type html label type
* @param body html content inside \<object>...\</object>
*/
if(is_array($vars)) {
	$body = $vars['body'];
}
else {
	$body = $vars;
	$vars = array();
}

echo '<object';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');

//title
echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');

//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

//onclick
echo ($vars['onclick'] ? ' onclick="'.$vars['onclick'].'"' : '');
//src
echo ($vars['data'] ? ' data="'.$vars['data'].'"' : '');
//type
echo ($vars['type'] ? ' type="'.$vars['type'].'"' : '');

echo '>';

echo $body;

echo '</object>';
?>
