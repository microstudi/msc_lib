<?php
    echo m_view("parts/menu");
?>
<div class="container">
    <!--Body content-->
    <?php

    $part = $vars['part'];

    $body = m_view($part, array(), true);

    echo $body;
    ?>
</div>

<footer class="footer">
  <div class="container">

        <p>Author <a href="http://twitter.com/ivanverges" target="_blank">@ivanverges</a>.</p>
        <p>Code licensed under <a href="http://www.gnu.org/copyleft/lgpl.html" target="_blank">GNU Lesser General Public License</a>
</address></a>, documentation under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
        <p><a href="http://twitter.github.com/bootstrap/base-css.htm">Bootstrap</a> used on this examples licensed under <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>.</p>
        <p><a href="http://glyphicons.com">Glyphicons Free</a> licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
    </div>
</footer>