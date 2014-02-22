<?php
/**
* @file views/html5/input.php
* @author Ivan Vergés
* @brief \<input> tag for the default HTML5 view\n
*
* @section usage Example:
* <code>
* echo m_view("input",array('id'=>"input1",'class'=>"my_class",'body'=>"DIV html content"));\n
* </code>
*
* @param divinput encapsulates <input> in a div with the class specified
* @param type html type (text, button, radio, checkbox, textarea, ...)
* @param name html name
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
	$label =  '<label' .
	($vars['id'] ? ' for="' . htmlspecialchars($vars['id']) . '"' : '') .
	($vars['labelclass'] ? ' class="' . htmlspecialchars($vars['labelclass']) . '"' : '') .
	($vars['labelstyle'] ? ' style="' . htmlspecialchars($vars['labelstyle']) . '"' : '') .
	($vars['labelid'] ? ' id="' . htmlspecialchars($vars['labelid']) . '"' : '') .
	($vars['labeltitle'] ? ' title="' . htmlspecialchars($vars['labeltitle']) . '"' : '') .
	'>';
	if($vars['type'] == 'checkbox' && !$vars['id']) {
		echo $label;
		if(!isset($vars['endlabel'])) echo $vars['label'];
		$label = '';
	}
	else {
		$label .= $vars['label'];
		$label .= '</label>';
	}
}

if(!isset($vars['endlabel'])) echo $label;

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

if(isset($vars['divinput'])) echo '<div' . ($vars['divinput'] ? ' class="' . $vars['divinput'] . '"' : '').'>';
echo '<input';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo ($vars['accept'] ? ' accept="' . htmlspecialchars($vars['accept']) . '"' : '');
echo ($vars['alt'] ? ' alt="' . htmlspecialchars($vars['alt']) . '"' : '');
echo (in_array($vars['autocomplete'], array("on", "off")) ? ' autocomplete="' . $vars['autocomplete'] . '"' : '');
echo (empty($vars['autofocus']) ? '' : ' autofocus="autofocus"');
echo (empty($vars['checked']) ? '' : ' checked="checked"');
echo (empty($vars['disabled']) ? '' : ' disabled="disabled"');

echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo ($vars['formaction'] ? ' formaction="' . htmlspecialchars($vars['formaction']) . '"' : '');
echo (in_array($vars['formenctype'], array("application/x-www-form-urlencoded", "multipart/form-data", "text/plain")) ? ' formenctype="' . $vars['formenctype'] . '"' : '');
echo (in_array($vars['formmethod'], array("get", "post")) ? ' formmethod="' . $vars['formmethod'] . '"' : '');
echo (empty($vars['formnovalidate']) ? '' : ' formnovalidate="formnovalidate"');
echo ($vars['formtarget'] ? ' formtarget="' . htmlspecialchars($vars['formtarget']) . '"' : '');

echo ($vars['height'] ? ' height="' . htmlspecialchars($vars['height']) . '"' : '');
echo ($vars['list'] ? ' list="' . htmlspecialchars($vars['list']) . '"' : '');
echo ($vars['max'] ? ' max="' . htmlspecialchars($vars['max']) . '"' : '');
echo ($vars['maxlength'] ? ' maxlength="' . intval($vars['maxlength']) . '"' : '');
echo ($vars['min'] ? ' min="' . htmlspecialchars($vars['min']) . '"' : '');
echo ($vars['multiple'] ? ' multiple="' . htmlspecialchars($vars['multiple']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo ($vars['pattern'] ? ' pattern="' . htmlspecialchars($vars['pattern']) . '"' : '');
echo ($vars['placeholder'] ? ' placeholder="' . htmlspecialchars($vars['placeholder']) . '"' : '');
echo ($vars['readonly'] ? ' readonly="' . htmlspecialchars($vars['readonly']) . '"' : '');
echo ($vars['required'] ? ' required="' . htmlspecialchars($vars['required']) . '"' : '');
echo ($vars['size'] ? ' size="' . intval($vars['size']) . '"' : '');
echo ($vars['src'] ? ' src="' . htmlspecialchars($vars['src']) . '"' : '');
echo ($vars['step'] ? ' step="' . intval($vars['step']) . '"' : '');
echo (in_array($vars['type'], array("button", "checkbox", "color", "date", "datetime", "datetime-local", "email", "file", "hidden", "image", "month", "number", "password", "radio", "range", "reset", "search", "submit", "tel", "text", "time", "url", "week")) ? ' type="' . $vars['type'] . '"' : '');
echo (isset($vars['value']) ? ' value="' . htmlspecialchars($vars['value']) . '"' : '');
echo ($vars['width'] ? ' width="' . htmlspecialchars($vars['width']) . '"' : '');
echo '>';

if(isset($vars['helpspan'])) echo '<span' . ($vars['helpspanclass'] ? ' class="' . $vars['helpspanclass'] . '"' : '').'>' . $vars['helpspan'] . '</span>';
if(isset($vars['helpp'])) echo '<p' . ($vars['helppclass'] ? ' class="' . $vars['helppclass'] . '"' : '').'>' . $vars['helpp'] . '</p>';

if(isset($vars['divinput'])) echo '</div>';

if($vars['label'] && $vars['type'] == 'checkbox' && !$vars['id']) {
	if(isset($vars['endlabel'])) echo $vars['label'];
	echo '</label>';
}

if(isset($vars['endlabel'])) echo $label;
