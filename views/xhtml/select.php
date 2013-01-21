<?php
/**
* @file views/xhtml/select.php
* @author Ivan Vergés
* @brief \<select> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("select",array('id'=>"select1",'class'=>"my_class",'body'=>"SELECT html content"));\n
* //or\n
* $items = array();\n
* $items[] = "Option 1 text";\n
* $items[] = "Option 2 text";\n
* echo m_view("select",array('id'=>"select1",'class'=>"my_class",'items'=>$items));\n
* //or\n
* $items = array();\n
* $items[] = array('id'=>"option1",'value'=>"option_1_value",'text'=>"Option 1 text");\n
* $items[] = array('id'=>"option2",'value'=>"option_2_value",'text'=>"Option 2 text");\n
* echo m_view("select",array('id'=>"select1",'class'=>"my_class",'items'=>$items));\n
*
*
* @param id html id
* @param name html name
* @param class html label class
* @param title html label title
* @param style html label style
* @param disabled html label disabled (true or false)
* @param label Adds the \<label>...\</label> before the \<select>, if param \b id is present adds \<label for="...id...">
* @param endlabel If true, the \<label> goes after the \<select>
* @param labelid id for the \<label id="...">
* @param labelclass class for the \<label class="...">
* @param body html content inside \<select>...\</select>
* @param options a alias for \b items
* @param items if body not exists and this is a array, it will be used to display the internal \<option> elements of \<select>, a the same time \b items could be a array with text or a sub-array with \b id, \b class, \b style, \b value & \b text sub-parameters
*/

$label = '';
if($vars['label']) $label = '<label'.($vars['id'] ? ' for="'.$vars['id'].'"' : '') . ($vars['labelclass'] ? ' class="'.$vars['labelclass'].'"' : '') . ($vars['labelid'] ? ' class="'.$vars['labelid'].'"' : '') . '>' . $vars['label'] . '</label>';

if(!$vars['endlabel']) echo $label;

echo '<select';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
echo ($vars['name'] ? ' name="'.$vars['name'].'"' : '');

//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');

//title
echo ($vars['title'] ? ' title="' . htmlspecialchars($vars['title']) . '"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

//disabled
echo ($vars['disabled'] ? ' disabled="'.$vars['disabled'].'"' : '');


echo '>';

if(is_array($vars['items'] && !is_array($vars['options']))) $vars['options'] = $vars['items'];

if($vars['body']) echo $vars['body'];
elseif(is_array($vars['options'])) {
	foreach($vars['options'] as $i => $item) {
		if(is_array($item)) {

			echo '<option';

			echo ($item['id'] ? ' id="'.$item['id'].'"' : '');

			echo (isset($item['value']) ? ' value="'.htmlspecialchars($item['value']).'"' : '');

			echo ($item['class'] ? ' class="'.$item['class'].'"' : '');
			echo ($item['style'] ? ' style="'.$item['style'].'"' : '');

			if($item['value'] == $vars['value']) echo ' selected="selected"';

			if($item['disabled']) $ret .= ' disabled="disabled"';

			echo '>'.($item['text'] ? $item['text'] : $item['value']).'</option>';
		}
		elseif(is_numeric($i)) {
			echo '<option'.($item == $vars['value'] ? ' selected="selected"' : '').'>' . $item . '</option>';
		}
		else {
			echo '<option value="'.$i.'" '.($i == $vars['value'] ? ' selected="selected"' : '').'>' . $item . '</option>';
		}
	}
}
echo '</select>';

if($vars['endlabel']) echo $label;
?>
