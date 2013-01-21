<?php
/**
* @file views/xhtml/img.php
* @author Ivan Vergés
* @brief \<img> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("img",array('id'=>"img1",'class'=>"my_class",'src'=>"URL Image",'alt'=>"Alt text"));\n
* //or\n
* echo m_view("img","URL image");
*
* @param id html id
* @param class html label class
* @param src html label src (URL image)
* @param title html label title
* @param style html label style
*/

?><img<?php

if(is_array($vars)) $src = $vars['src'];
else {
	$src = $vars;
	$vars = array();
}

echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');

echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');

echo ($src ? ' src="'.$src.'"' : '');

echo ($vars['alt'] ? ' alt="' . htmlspecialchars($vars['alt']) . '"' : '');

echo ($vars['title'] ? ' title="' . htmlspecialchars($vars['title']) . '"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

?> />
