<?php
/**
* @file views/xhtml/body.php
* @author Ivan Vergés
* @brief \<body> tag for the default XHTML view\n
*
*/

?><body<?php ($vars['id'] ? ' id="'.$vars['id'].'"' : '')?>><?php echo $vars['body']; ?></body>
