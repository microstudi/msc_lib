<?php

extract($vars);

?>
<div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
    <div class="container">
     <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="pull-right">
<?php
  echo m_view('a', array('class' => "brand", 'body' => 'MSCLIB ' .m_lib_version()));

  $items = array();
  $curr_lang = m_lang_select();
  foreach(m_lang_set() as $lang) {
    $items[] = m_view('a', array('class' => "btn" . ($lang == m_lang_select() ? ' disabled' : ''), 'href' => '?' . http_build_query(array('lang' => $lang) + $_GET), 'body' => strtoupper($lang)));
  }
  echo m_view("div", array('class' => "btn-group", 'body' => implode($items)));
?>

      </div>
    <div class="nav-collapse collapse">
<?php
  echo m_view("ul", array('id' => "menu", 'class' => "nav", 'items' => $menu));
?>
    	</div>
    </div>
  </div>
</div>