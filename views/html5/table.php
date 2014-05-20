<?php
/**
* @file views/html5/table.php
* @author Ivan Vergés
* @brief \<table> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
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
* </code>
*
* @param border border or not
* @param body html content inside \<table>...\</table>
* @param items if body not exists and this is a array, it will be used to display the internal \<option> elements of \<table>, a the same time \b items could be a array with text or a sub-array with \b id, \b class, \b style, \b title & \b body sub-parameters
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<table';

require('_common_html5_attributes.php');
require('_common_html5_event_attributes.php');

//echo (in_array($vars['border'], array("", "1")) ? ' border="' . $vars['border'] . '"' : '');
echo ($vars['border'] ? ' border="1"' : '');

echo '>';

if($body) echo $body;
elseif(is_array($vars['items'])) {
	foreach($vars['items'] as $i => $item) {
		if(is_array($item)) {

			$b = $item['body'];
			if(is_array($b)) {
				if($i == 0 && $vars['thfirst'])  $b = '<th>'.implode('</th><th>',$b).'</th>';
				else  $b = '<td>'.implode('</td><td>',$b).'</td>';
			}

			$c = '';
			foreach($item as $k => $v) {
				if(in_array($k,array('id','class','style','title')) || strpos($k,'data-')===0) $c .= " $k=\"" . htmlspecialchars($v) . '"';
			}

			if($i == 0 && $vars['thfirst']) echo "\n<thead>\n<tr$c>$b</tr></thead>\n<tbody>\n";
			else {
				if(!$vars['thfirst'] && $i == 0) echo '<tbody>';

				echo "<tr$c>$b</tr>\n";
			}
		}
		else {
			if($i == 0 && $vars['thfirst']) echo "\n<thead>\n<tr>$item</tr></thead>\n";
			else {
				if($i == 1) echo '<tbody>';
				echo "<tr>$item</tr>\n";
			}
		}
	}
	if($i > 0 ) echo '</tbody>';
}

echo '</table>';
