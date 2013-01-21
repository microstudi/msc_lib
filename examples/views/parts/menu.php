<div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
    <div class="container">
<?php
  echo m_view('a', array('class' => "brand", 'body' => 'MSCLIB ' .m_lib_version()));
  $items = array();
  if($files = scandir("views")) {
    foreach($files as $file) {
      if(substr($file, -4, 4) != '.php') continue;
      $file = substr($file, 0, -4);
      $href = "index.php?part=$file";
      $active = (m_input('part') == $file ? 'active' : '');
      $items[] = array('class' => $active, 'id' => "m-$file", 'body' => m_view("a", array('href' => $href, 'body' => m_lang_echo($file))));
    }
  }
  echo m_view("ul", array('class' => "nav", 'items' => $items));
?>
    </div>
  </div>
</div>