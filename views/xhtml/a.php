<?php
/**
* @file views/xhtml/a.php
* @author Ivan Vergés
* @brief \<a> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("a",array('id'=>"anchor1",'class'=>"my_class",'body'=>"Link description",'href'=>"http://URL"));
*
* @param id html id
* @param class html label class
* @param name html label name
* @param title html label title
* @param style html label style
* @param onclick html label onclick
* @param rev html label rel
* @param rel html label rev
* @param body html content inside \<a>...\</a>
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
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//name
echo ($vars['name'] ? ' class="'.$vars['name'].'"' : '');
//title
echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

//href
echo ($href ? ' href="'.$href.'"' : '');

if($vars['target'] == '_blank' && empty($vars['onclick'])) {
	$vars['onclick'] = 'window.open(this.href);return false;';
}
//onclick
echo ($vars['onclick'] ? ' onclick="'.$vars['onclick'].'"' : '');
//rel
echo ($vars['rel'] ? ' rel="'.$vars['rel'].'"' : '');
//rev
echo ($vars['rev'] ? ' rev="'.$vars['rev'].'"' : '');

echo '>';

echo $body;

echo '</a>';
?>
