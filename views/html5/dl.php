<?php
/**
* @file views/html5/dl.php
* @author Ivan Vergés
* @brief \<dl> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("dl",array('id'=>"div1",'class'=>"my_class",'body'=>"dl html content"));\n
* //or\n
* echo m_view("dl","dl html content");
*
* @param body html content inside \<b>...\</b>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<dl';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo '>';

if($body) echo $body;
elseif(is_array($vars['items'])) {
	if(is_array($vars['items']['dt'])) {
		foreach($vars['items']['dt'] as $i => $dt) {
			echo m_view("dt", $dt);
			if(is_array($vars['items']['dd']) && array_key_exists($i, $vars['items']['dd'])) {
				echo m_view("dd", $vars['items']['dd'][$i]);
			}
		}
	}
	elseif(is_array($vars['items']['dd'])) {
		foreach($vars['items']['dd'] as $i => $dd) {
			echo m_view("dd", $dd);
		}
	}
	else {
		foreach($vars['items'] as $dt => $dd) {
			echo m_view("dt", $dt);
			echo m_view("dd", $dd);
		}
	}
}

echo '</dl>';

?>
