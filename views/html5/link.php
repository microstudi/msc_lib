<?php
/**
* @file views/html5/link.php
* @author Ivan Vergés
* @brief \<link> tag for the default HTML5 view\n
*
* @section usage Usage
* echo m_view("link",array('id'=>"img1",'class'=>"my_class",'src'=>"href URL"));\n
* //or\n
* echo m_view("link","href URL");
*
* @param href html label href (href URL)
*/

if(is_array($vars)) {
	$href = $vars['href'];
}
else {
	$href = $vars;
	$vars = array();
}

echo '<link';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

//href
echo ($href ? ' href="' . htmlspecialchars($href) . '"' : '');
//hreflang
echo ($vars['hreflang'] ? ' hreflang="' . htmlspecialchars($vars['hreflang']) . '"' : '');
//media
echo ($vars['media'] ? ' media="' . htmlspecialchars($vars['media']) . '"' : '');
//rel
echo (in_array($vars['rel'],array("alternate", "archives", "author", "bookmark", "external", "first", "help", "icon", "last", "licence", "next", "nofollow", "noreferrer", "pingback", "prefetch", "prev", "search", "sidebar", "stylesheet", "stylesheet/less", "tag", "up")) ? ' rel="' . $vars['rel'] . '"' : '');

//target
echo ($vars['sizes'] ? ' sizes="' . htmlspecialchars($vars['sizes']) . '"' : '');
//type
echo ($vars['type'] ? ' type="' . htmlspecialchars($vars['type']) . '"' : '');

echo ">\n";

?>
