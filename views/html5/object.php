<?php
/**
* @file views/html5/object.php
* @author Ivan Vergés
* @brief \<object> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("object",array('id'=>"object1",'class'=>"my_class",'body'=>"Object 1 description"));
*
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

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['data'] ? ' data="' . htmlspecialchars($vars['data']) . '"' : '');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo ($vars['type'] ? ' type="' . htmlspecialchars($vars['type']) . '"' : '');
echo ($vars['usemap'] ? ' usemap="' . htmlspecialchars($vars['usemap']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');

echo '>';


if($body) echo $body;
elseif(is_array($vars['params'])) {
	foreach($vars['params'] as $item) {
		if(is_array($item)) {
			echo '<param';
			echo ($item['id'] ? ' id="' . htmlspecialchars($item['id']) . '"' : '');
			echo ($item['class'] ? ' class="' . htmlspecialchars($item['class']) . '"' : '');
			echo ($item['style'] ? ' style="' . htmlspecialchars($item['style']) . '"' : '');
			echo ($item['title'] ? ' title="' . htmlspecialchars($item['title']) . '"' : '');
			echo ($item['name'] ? ' name="' . htmlspecialchars($item['name']) . '"' : '');
			echo ($item['value'] ? ' value="' . htmlspecialchars($item['value']) . '"' : '');
			echo '>'.$item['body'].'</param>';
		}
		else {
			echo '<param>'.$item.'</param>';
		}
	}
}
echo '</object>';
?>
