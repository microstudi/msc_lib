<?php

m_config_var('title', "mFile examples");

?>
<section id="ex-1">
	<h2>Configuring the remote service storage</h2>
	<p>Services can be:
<ol>
	<li><strong>FTP:</strong>
	</p>
	<pre class="prettyprint linenums">&lt;?php
include_once("msc_lib/start.php");
//configuring service
m_file_set_remote('ftp', array(
	'host'     => 'ftp.myhost.com',
	'user'     => 'username',
	'password' => 'password',
	'rootpath' => '/path/to/start',                       //path considered root to work on
	'urlpath'  => 'http://myhost.com/public/start/folder' //equivalent to /path/to/start for the public links
	                                                      //eg: this document /path/to/start/image.png will be available
	                                                      //    on http://myhost.com/public/start/folder/image.png
	));
//uploading a file
$ok = m_file_put('myfile.png', 'myfile.png');
if($ok === true) {
	//accessing to the public link
	$link = m_file_url('myfile.png');
	//echo html code
	echo '&lt;a href="' . $link . '"&gt;My File link&lt;/a&gt;';
}
else {
	echo "Error uploading: $ok";
}
?&gt;</pre>
<p>If it goes fine, this will produce the html code:  <code class="prettyprint">&lt;a href="http://myhost.com/public/start/folder/myfile.png"&gt;My File link&lt;/a&gt;</code></p>
</li>
</ol>
</section>
