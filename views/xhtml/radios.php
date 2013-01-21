<?php
/**
* @file views/xhtml/radios.php
* @author Ivan Vergés
* @brief \<input type="radio"> tag for the default XHTML view\n
*
* @section usage Usage
* $items = array();\n
* $items[] = array('label'=>'Label 1','id'=>"item1",'value'=>"value1",class="my_class");\n
* $items[] = array('label'=>'Label 2','id'=>"item2",'value'=>"value2",class="my_class");\n
* echo m_view("radios",array('options'=>$items,'addbr'=>true));\n
*
* @param addbr adds a \<br /> tag between the \<input> tags
* @param options a array of items (every \<input>) with the different options:\n
* Items parameters:
* - \b id id of the \<input id="">
* - \b name name of the \<input name="">
* - \b class class of the \<input class="">
* - \b value value of the \<input value="">
* - \b disabled if(true) adds \<input disabled="disabled">
* - \b label Adds the \<label>...\</label> before the \<input>, if param \b id is present adds \<label for="...id...">
* - \b endlabel If true, the \<label> goes after the \<input>
* - \b labelid id for the \<label id="...">
* - \b labelclass class for the \<label class="...">
*/

if(is_array($vars['options'])) {
	$items = array();
	foreach($vars['options'] as $item) {
		$label = '';
		$ret = '';
		if($item['label']) $label = '<label'.($item['id'] ? ' for="'.$item['id'].'"' : '') . ($vars['labelclass'] ? ' class="'.$vars['labelclass'].'"' : '') . ($item['labelid'] ? ' id="'.$item['labelid'].'"' : '') . '>'.$item['label'].'</label>';

		if(!isset($vars['endlabel'])) $ret .= $label;

		$ret .= '<input type="radio"';
		$ret .= ($item['name'] ? ' name="'.$item['name'].'"' : ($vars['name'] ? ' name="'.$vars['name'].'"' : ''));
		$ret .= ($item['id'] ? ' id="'.$item['id'].'"' : '');
		$ret .= (isset($item['value']) ? ' value="'.$item['value'].'"' : '');
		$ret .= ($item['class'] ? ' class="'.$item['class'].'"' : ($vars['class'] ? ' class="'.$vars['class'].'"' : ''));
		//echo $item['value']." == ".$vars['value'];
		if($item['value'] == $vars['value'] && isset($vars['value'])) $ret .= ' checked="checked"';

		if($item['disabled']) $ret .= ' disabled="disabled"';
		$ret .= ' />';

		if(isset($vars['endlabel'])) $ret .= $label;

		$items[] = $ret;

	}

	if(isset($vars['addbr'])) echo implode('<br />',$items);
	else echo implode('',$items);
}
?>
