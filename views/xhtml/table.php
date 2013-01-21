<?php
/**
* @file views/xhtml/table.php
* @author Ivan Vergés
* @brief \<table> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("table",array('id'=>"table1",'class'=>"my_class",'body'=>"TABLE html content"));\n
* //or\n
* $items = array();\n
* $items[] = "<td>td 1/1</td><td>td 1/2</td>";\n
* $items[] = "<td>td 2/1</td><td>td 2/2</td>";\n
* echo m_view("table",array('id'=>"table1",'class'=>"my_class",'items'=>$items));\n
* //or\n
* $items = array();\n
* $items[] = array('id'=>"item1",'body'=>"<td>td 1/1</td><td>td 1/2</td>");\n
* $items[] = array('id'=>"item2",'body'=>"<td>td 2/1</td><td>td 2/2</td>");\n
* echo m_view("table",array('id'=>"table1",'class'=>"my_class",'items'=>$items));\n
* //or\n
* $items = array();\n
* $items[] = array('id'=>"item1",'body'=>array("td 1/1","td 1/2"));\n
* $items[] = array('id'=>"item2",'body'=>array("td 2/1","td 2/2"));\n
* echo m_view("table",array('id'=>"table1",'class'=>"my_class",'items'=>$items));\n
*
*
* @param id html id
* @param class html label class
* @param title html label title
* @param style html label style
* @param body html content inside \<table>...\</table>
* @param items if body not exists and this is a array, it will be used to display the internal \<option> elements of \<table>, a the same time \b items could be a array with text or a sub-array with \b id, \b class, \b style, \b title & \b body sub-parameters
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<table';
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
			$b = $item['body'];
			if(is_array($b)) $b = "<td>".implode("</td><td>",$b)."</td>";
			echo '<tr';
			echo ($item['id'] ? ' id="'.$item['id'].'"' : '');
			echo ($item['class'] ? ' class="'.$item['class'].'"' : '');
			echo ($item['style'] ? ' style="'.$item['style'].'"' : '');
			echo ($item['title'] ? ' title="'.$item['title'].'"' : '');
			echo '>'.$b.'</tr>';
		}
		else {
			echo '<tr>'.$item.'</tr>';
		}
	}
}

echo '</table>';

?>
