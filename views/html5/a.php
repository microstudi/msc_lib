<?php
/**
 * @file views/html5/a.php
 * @author Ivan Vergés
 * @brief \<a> tag for the default HTML5 view
 *
 * @section usage Example:
 * <code>
 * echo m_view("a",array('id'=>"anchor1",'class'=>"my_class",'body'=>"Link description",'href'=>"http://URL"));
 * </code>
 *
 * @param rel html label rev
 * @param body html content inside \<a>...\</a>
 *
 */

if(is_array($vars)) {
	$body = $vars['body'];
	$href = $vars['href'];
}
else {
	$body = $vars;
	$href = $vars;
	$vars = array();
}

echo '<a';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

//href
echo ($href ? ' href="' . htmlspecialchars($href) . '"' : '');
//hreflang
echo ($vars['hreflang'] ? ' hreflang="' . htmlspecialchars($vars['hreflang']) . '"' : '');

//media
echo ($vars['media'] ? ' media="' . htmlspecialchars($vars['media']) . '"' : '');

//rel
//echo (in_array($vars['rel'],array('alternate', 'author', 'bookmark', 'help', 'license', 'next', 'nofollow', 'noreferrer', 'prefetch', 'prev', 'search', 'tag')) ? ' rel="' . $vars['rel'] . '"' : '');
echo ($vars['rel'] ? ' rel="' . htmlspecialchars($vars['rel']) . '"' : '');

//target
echo ($vars['target'] ? ' target="' . htmlspecialchars($vars['target']) . '"' : '');

//type
echo ($vars['type'] ? ' type="' . htmlspecialchars($vars['type']) . '"' : '');

echo '>';

echo $body;

echo '</a>';
