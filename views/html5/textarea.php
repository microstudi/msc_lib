<?php
/**
* @file views/xhtml/textarea.php
* @author Ivan Vergés
* @brief \<textarea> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("textarea",array('id'=>"textarea1",'class'=>"my_class",'body'=>"DIV html content"));\n
*
* @param name html name
* @param disabled html label disabled (true or false)
* @param value html content inside \<textarea>...\</textarea>
* @param label Adds the \<label>...\</label> before the \<textarea>, if param \b id is present adds \<label for="...id...">
* @param endlabel If true, the \<label> goes after the \<textarea>
* @param labelid id for the \<label id="...">
* @param labelclass class for the \<label class="...">
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

if(!isset($vars['endlabel'])) echo $label;

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

if(isset($vars['divinput'])) echo '<div' . ($vars['divinput'] ? ' class="' . $vars['divinput'] . '"' : '').'>';
echo '<textarea';

require("_common_html5_attributes.php");
require("_common_html5_event_attributes.php");

echo (in_array($vars['autocomplete'], array("on", "off")) ? ' autocomplete="' . $vars['autocomplete'] . '"' : '');

echo (empty($vars['autofocus']) ? '' : ' autofocus="autofocus"');
echo ($vars['cols'] ? ' cols="' . intval($vars['cols']) . '"' : '');
echo (empty($vars['disabled']) ? '' : ' disabled="disabled"');
echo ($vars['form'] ? ' form="' . htmlspecialchars($vars['form']) . '"' : '');
echo ($vars['maxlength'] ? ' maxlength="' . intval($vars['maxlength']) . '"' : '');
echo ($vars['name'] ? ' name="' . htmlspecialchars($vars['name']) . '"' : '');
echo ($vars['placeholder'] ? ' placeholder="' . htmlspecialchars($vars['placeholder']) . '"' : '');
echo (empty($vars['readonly']) ? '' : ' readonly="readonly"');
echo (empty($vars['required']) ? '' : ' required="required"');
echo ($vars['rows'] ? ' rows="' . intval($vars['rows']) . '"' : '');
echo (in_array($vars['wrap'], array("hard", "soft")) ? ' wrap="' . $vars['wrap'] . '"' : '');
echo '>';

echo htmlspecialchars($vars['value']);

echo '</textarea>';

if(isset($vars['helpspan'])) echo '<span' . ($vars['helpspanclass'] ? ' class="' . $vars['helpspanclass'] . '"' : '').'>' . $vars['helpspan'] . '</span>';
if(isset($vars['helpp'])) echo '<p' . ($vars['helppclass'] ? ' class="' . $vars['helppclass'] . '"' : '').'>' . $vars['helpp'] . '</p>';

if(isset($vars['divinput'])) echo '</div>';

if(isset($vars['endlabel'])) echo $label;

?>
