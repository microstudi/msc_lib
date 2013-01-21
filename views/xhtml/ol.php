<?php
/**
* @file views/xhtml/ol.php
* @author Ivan Vergés
* @brief \<ol> tag for the default XHTML view\n
* @section usage Usage
* echo m_view("ol",array('id'=>"ol1",'class'=>"my_class",'body'=>"OL html content"));\n
* //or\n
* echo m_view("ol","OL html content");\n
* //or\n
* $items = array();\n
* $items[] = "Item 1 content";\n
* $items[] = "Item 2 content";\n
* echo m_view("ol",array('id'=>"ol1",'class'=>"my_class",'items'=>$items));\n
* //or\n
* $items = array();\n
* $items[] = array('id'=>"item1",'title'=>"Item 1 title",'body'=>"Item 1 content");\n
* $items[] = array('id'=>"item2",'title'=>"Item 2 title",'body'=>"Item 2 content");\n
* echo m_view("ol",array('id'=>"ol1",'class'=>"my_class",'items'=>$items));\n
*
* @param id html id
* @param class html class
* @param title html title
* @param style html style
* @param body html content inside \<ol>...\</ol>
* @param items if body not exists and this is a array, it will be used to display the internal \<li> elements of \<ol>, a the same time \b items could be a array with text or a sub-array with \b id, \b class, \b style, \b title & \b body sub-parameters
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<ol';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//title
echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

echo '>';

if($body) echo $body;
elseif(is_array($vars['items'])) {
	foreach($vars['items'] as $item) {
		if(is_array($item)) {
			echo '<li';
			echo ($item['id'] ? ' id="'.$item['id'].'"' : '');
			echo ($item['class'] ? ' class="'.$item['class'].'"' : '');
			echo ($item['style'] ? ' style="'.$item['style'].'"' : '');
			echo ($item['title'] ? ' title="'.$item['title'].'"' : '');
			echo '>'.$item['body'].'</li>';
		}
		else {
			echo '<li>'.$item.'</li>';
		}
	}
}
?>
</ol>
