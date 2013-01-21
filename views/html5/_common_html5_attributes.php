<?php

//title
echo ($vars['accesskey'] ? ' accesskey="' . htmlspecialchars($vars['acceskey']) . '"' : '');
//class
echo ($vars['class'] ? ' class="' . htmlspecialchars($vars['class']) . '"' : '');
//contenteditable
echo (in_array($vars['contenteditable'], array("true", "false", "inherit")) ? ' contenteditable="' . $vars['contenteditable'] . '"' : '');
//contextmenu
echo ($vars['contextmenu'] ? ' contextmenu="' . htmlspecialchars($vars['contextmenu']) . '"' : '');
//dir
echo (in_array($vars['dir'], array("ltr", "rtl", "auto")) ? ' dir="' . $vars['dir'] . '"' : '');
//draggable
echo (in_array($vars['draggable'], array("true", "false", "auto")) ? ' draggable="' . $vars['draggable'] . '"' : '');
//dropzone
echo (in_array($vars['dropzone'], array("copy", "move", "link")) ? ' dropzone="' . $vars['dropzone'] . '"' : '');
//hidden
echo (empty($vars['hidden']) ? '' : ' hidden="hidden"');
//Id
echo ($vars['id'] ? ' id="' . htmlspecialchars($vars['id']) . '"' : '');
//lang
echo ($vars['lang'] ? ' lang="' . htmlspecialchars($vars['lang']) . '"' : '');
//spellcheck
echo (in_array($vars['spellcheck'], array("true", "false")) ? ' spellcheck="' . $vars['spellcheck'] . '"' : '');
//style
echo ($vars['style'] ? ' style="' . htmlspecialchars($vars['style']) . '"' : '');
//tabindex
echo ($vars['tabindex'] ? ' tabindex="' . htmlspecialchars($vars['tabindex']) . '"' : '');
echo ($vars['role'] ? ' role="' . htmlspecialchars($vars['role']) . '"' : '');
//title
echo ($vars['title'] ? ' title="' . htmlspecialchars($vars['title']) . '"' : '');

//data-* for jquery/bootstrap

foreach($vars as $k => $v) {
	if(strpos($k,'data-') === 0) echo ($v ? " $k=\"" . htmlspecialchars($v) . '"' : '');
}

?>
