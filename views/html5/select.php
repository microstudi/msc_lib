<?php
/**
* @file views/html5/select.php
* @author Ivan Vergés
* @brief \<select> tag for the default HTML5 view\n
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
* @param divinput encapsulates <input> in a div with the class specified
* @param name html name
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
if($vars['label']) {
	$label =  '<label' .
	($vars['id'] ? ' for="' . htmlspecialchars($vars['id']) . '"' : '') .
	($vars['labelclass'] ? ' class="' . htmlspecialchars($vars['labelclass']) . '"' : '') .
	($vars['labelstyle'] ? ' style="' . htmlspecialchars($vars['labelstyle']) . '"' : '') .
	($vars['labelid'] ? ' id="' . htmlspecialchars($vars['labelid']) . '"' : '') .
	($vars['labeltitle'] ? ' title="' . htmlspecialchars($vars['labeltitle']) . '"' : '') .
	'>';
	$label .= $vars['label'];
	$label .= '</label>';
}

if(!$vars['endlabel']) echo $label;

if(isset($vars['divinput'])) echo '<div' . ($vars['divinput'] ? ' class="' . $vars['divinput'] . '"' : '').'>';

echo '<select';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (empty($vars['autofocus']) ? '' : ' autofocus="autofocus"');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo (empty($vars['disabled']) ? '' : ' disabled="disabled"');
echo (empty($vars['multiple']) ? '' : ' multiple="multiple"');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo ($vars['size'] ? '  size="'.intval($vars['size']).'"' : '');

echo (in_array($vars['autocomplete'], array("on", "off")) ? ' autocomplete="' . $vars['autocomplete'] . '"' : '');

echo '>';

if(is_array($vars['items']) && empty($vars['options']))	$vars['options'] = $vars['items'];

if($vars['body']) echo $vars['body'];
elseif(is_array($vars['options'])) {

	$value = $vars['value']; //value pot ser un array
	if(!is_array($value)) $value = array($value);

	$associative = key($vars['options']) !== 0 || $vars['associative'];
	foreach($vars['options'] as $i => $item) {
		if(is_array($item)) {

			echo '<option';

			echo ($item['id'] ? ' id="'.htmlspecialchars($item['id']).'"' : '');

			echo (isset($item['value']) ? ' value="'.htmlspecialchars($item['value']).'"' : '');

			echo ($item['class'] ? ' class="'.htmlspecialchars($item['class']).'"' : '');
			echo ($item['style'] ? ' style="'.htmlspecialchars($item['style']).'"' : '');

			foreach($item as $_k => $_v) {
				if(strpos($_k,'data-') === 0) echo " $_k=\"" . htmlspecialchars($_v) . '"';
			}

			if(in_array($item['value'], $value)) echo ' selected="selected"';

			if($item['disabled']) $ret .= ' disabled="disabled"';

			echo '>'.htmlspecialchars($item['text'] ? $item['text'] : $item['value']).'</option>';
		}
		elseif(is_numeric($i) && !$associative) {
			echo '<option'.(in_array($item, $value) ? ' selected="selected"' : '').'>' . htmlspecialchars($item) . '</option>';
		}
		else {
			echo '<option value="'.htmlspecialchars($i).'" '.(in_array($i, $value) ? ' selected="selected"' : '').'>' . htmlspecialchars($item) . '</option>';
		}
	}
}
echo '</select>';

if(isset($vars['helpspan'])) echo '<span' . ($vars['helpspanclass'] ? ' class="' . $vars['helpspanclass'] . '"' : '').'>' . $vars['helpspan'] . '</span>';
if(isset($vars['helpp'])) echo '<p' . ($vars['helppclass'] ? ' class="' . $vars['helppclass'] . '"' : '').'>' . $vars['helpp'] . '</p>';

if(isset($vars['divinput'])) echo '</div>';

if($vars['endlabel']) echo $label;
?>
