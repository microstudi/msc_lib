<?php
/**
* @file views/xhtml/script.php
* @author Ivan Vergés
* @brief \<script> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("script",array('body'=>"Script content",'src'=>"src_javascript",'async'=>true,'defer'=>true,'type'=>"MIME type"));\n
* //or\n
* echo m_view("script","Script content");
*
* @param src Javascript src path
* @param async async property (true|false)
* @param defer defer property (true|false)
* @param type Mime type (text/javascript)
* @param body html content inside \<script>...\</script>
*/
if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

echo '<script';
//Id
echo ($vars['src'] ? ' src="'.$vars['src'].'"' : '');
//async
echo (empty($vars['async']) ? '' : ' async="async"');
//defer
echo (empty($vars['defer']) ? '' : ' defer="defer"');
//type
echo ($vars['type'] ? ' type="'.$vars['type'].'"' : '');

echo '>';

echo $body;

echo "</script>\n";
?>
