<?php
/**
* @file views/xhtml/button.php
* @author Ivan Vergés
* @brief \<button> tag for the default XHTML view\n
*
*/

?><input type="button"<?php

echo ($vars['id'] ? ' id="'.$vars['id'].'"' : '');

echo ($vars['name'] ? ' name="'.$vars['name'].'"' : '');

echo ($vars['class'] ? ' class="'.$vars['class'].'"' : '');

echo ($vars['src'] ? ' src="'.$vars['src'].'"' : '');

echo ($vars['alt'] ? ' src="'.$vars['alt'].'"' : '');

echo ($vars['title'] ? ' title="'.htmlspecialchars($vars['title']).'"' : '');
//style
echo ($vars['style'] ? ' style="'.$vars['style'].'"' : '');

?> />
