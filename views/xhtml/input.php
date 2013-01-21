<?php
/**
* @file views/xhtml/input.php
* @author Ivan Vergés
* @brief \<input> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("input",array('id'=>"input1",'class'=>"my_class",'body'=>"DIV html content"));\n
*
* @param type html type (text, button, radio, checkbox, textarea, ...)
* @param id html id
* @param name html name
* @param class html label class
* @param title html label title
* @param style html label style
* @param maxlength html label maxlength
* @param checked html label checked (true or false)
* @param disabled html label disabled (true or false)
* @param value html label value
* @param label Adds the \<label>...\</label> before the \<input>, if param \b id is present adds \<label for="...id...">
* @param endlabel If true, the \<label> goes after the \<input>
* @param labelid id for the \<label id="...">
* @param labelclass class for the \<label class="...">
*/

if($vars['type'] == 'textarea') {
	require("textarea.php");
	return;
}
$label = '';
if($vars['label']) {
	$label =  '<label'.($vars['id'] ? ' for="'.$vars['id'].'"' : '') . ($vars['labelclass'] ? ' class="'.$vars['labelclass'].'"' : '') . ($vars['labelid'] ? ' id="'.$vars['labelid'].'"' : '').'>' . $vars['label'] .'</label>';
}

if(!isset($vars['endlabel'])) echo $label;

echo '<input';
//Id
echo ($vars['type'] ? ' type="'.$vars['type'].'"' : ' type="text"');

echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
echo ($vars['name'] ? ' name="'.$vars['name'].'"' : '');
//title
echo ($vars['title'] ? ' title="' . htmlspecialchars($vars['title']) . '"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

//maxlength
echo ($vars['maxlength'] ? ' maxlength="'.$vars['maxlength'].'"' : '');

//checked
echo ($vars['checked'] ? ' checked="checked"' : '');

echo ($vars['disabled'] ? ' disabled="disabled"' : '');

echo ' value="';

echo htmlspecialchars($vars['value']);

echo '" />';

if(isset($vars['endlabel'])) echo $label;
?>
