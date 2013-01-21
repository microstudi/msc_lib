<?php
/**
* @file views/xhtml/hr.php
* @author Ivan Vergés
* @brief \<hr> tag for the default XHTML view\n
*
*/

echo '<hr';
//Id
echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');
//class
echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');
//title
echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

echo ' />';

?>
