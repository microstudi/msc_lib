<?php
/**
* @file views/html5/video.php
* @author Ivan Vergés
* @brief \<video> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("video",array('id'=>"th1",'class'=>"my_class",'body'=>"VIDEO alternative html content"));\n
* //or\n
* echo m_view("video","VIDEO alternative html content");
* </code>
*
* @param body html content inside \<th>...\</th>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<video';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (empty($vars['autoplay']) ? '' : ' autoplay="autoplay"');
echo (empty($vars['controls']) ? '' : ' controls="controls"');
echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo (empty($vars['loop']) ? '' : ' loop="loop"');
echo (empty($vars['muted']) ? '' : ' muted="muted"');
echo ($vars['poster'] ? ' poster="' . htmlspecialchars($vars['poster']) . '"' : '');
echo (in_array($vars['preload'], array("auto", "metadata", "none")) ? ' preload="' . $vars['preload'] . '"' : '');
echo ($vars['url'] ? ' url="' . htmlspecialchars($vars['url']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');

echo '>';

if($body) echo $body;
else {
	$source = array();
	if(is_array($vars['source'])) $source = $vars['source'];
	elseif(is_array($vars['items'])) $source = $vars['items'];
	elseif($vars['source']) $source = array($vars['source']);

	foreach($source as $ob) {
		if(is_array($ob)) {
			$src = $ob['src'];
			$type = $ob['type'];
		}
		else $src = $ob;
		echo '<source src="'.htmlspecialchars($src).'"'.($type ? ' type="'.htmlspecialchars($type).'"' : '').' />';
	}
}

echo '</video>';
