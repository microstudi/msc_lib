<?php
/**
* @file views/xhtml/html.php
* @author Ivan Vergés
* @brief \<html> tag for the default XHTML view\n
*
* @section usage Usage
* echo m_view("html",array('title'=>"Html page title",'head'=>"head html part, css definitions, javascript...",'body'=>"Body html content"));\n
*
* @param title html page title (inside \<title>...\</title>)
* @param head html head content after title, (inside \<head>...\</head>)
* @param body html content inside \<body>...\</body>
*/

if(is_array($vars)) $body = $vars['body'];
else {
	$body = $vars;
	$vars = array();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $vars['lang']; ?>" lang="<?php echo $vars['lang']; ?>">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title><?php echo $vars['title']; ?></title>
<?php

	echo $vars['head'];

?>
</head>
<body><?php

	echo $body;

?>
</body>
</html>
