<?php
/**
* @file views/xhtml/textarea.php
* @author Ivan Vergés
* @brief \<textarea> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("textarea",array('id'=>"textarea1",'class'=>"my_class",'body'=>"DIV html content"));\n
*
* @param type html type (text, button, radio, checkbox, textarea, ...)
* @param id html id
* @param name html name
* @param class html label class
* @param title html label title
* @param style html label style
* @param disabled html label disabled (true or false)
* @param value html content inside \<textarea>...\</textarea>
* @param label Adds the \<label>...\</label> before the \<textarea>, if param \b id is present adds \<label for="...id...">
* @param endlabel If true, the \<label> goes after the \<textarea>
* @param labelid id for the \<label id="...">
* @param labelclass class for the \<label class="...">
*/
$label = '';
if($vars['label']) {
	$label =  '<label'.($vars['id'] ? ' for="'.$vars['id'].'"' : '') . ($vars['labelclass'] ? ' class="'.$vars['labelclass'].'"' : '') . ($vars['labelid'] ? ' id="'.$vars['labelid'].'"' : '') . '>' . $vars['label'] .'</label>';
}

if(!isset($vars['endlabel'])) echo $label;

echo '<textarea';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');

echo ($vars['name'] ? ' name="'.$vars['name'].'"' : '');

echo ($vars['title'] ? ' name="'.htmlspecialchars($vars['title']).'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

echo ($vars['disabled'] ? ' disabled="'.$vars['disabled'].'"' : '');

echo '>';

echo htmlspecialchars($vars['value']);

echo '</textarea>';

if(isset($vars['endlabel'])) echo $label;

?>
