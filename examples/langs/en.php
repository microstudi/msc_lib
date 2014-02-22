<?php

$lang = array(

	'default_title' => "MSC LIB Examples",

	//menus
	'menu-default' => '<i class="icon-home"></i> Index',
	'menu-views' => '<i class="icon-eye-open"></i> Views',
	'menu-mimage' => '<i class="icon-picture"></i> Image processing',
	'menu-mfile' => '<i class="icon-hdd"></i> File handling',
	'menu-routing' => '<i class="icon-road"></i> Routing',
	'menu-lang' => '<i class="icon-flag"></i> Langs',
	'menu-misc' => '<i class="icon-wrench"></i> Misc. functions',
	'menu-sql' => '<i class="icon-cog"></i> SQL',
	'menu-compression' => '<i class="icon-briefcase"></i> Compressors',

	//pages
	'what-is-msc_lib' => "MSC LIB a simple Framework Library",
	'default-text' => "<p>MSC LIB is a super-lightweight procedural framework providing some useful methods to build php websites.</p>
<p>Is a function-orientated library, allows you to work with views, FTP, S3, SSH automatic file handling, resizing/mixing images with disc cache, SQL abstraction functions, CSS &amp; Javascript compression, language files management &amp; URL routing with regexp expressions.</p>",

	'title-images' => "Image processing",

	'title-views' => "Handling views",

	'views-example0-title' => "Basics",
	'views-example0-text' => '<p>Views are all managed with the function <code>m_view($my_view, $params)</code>.</p>
	<p>A view is just a php file expected to be found in a some directory.</p>
	<p>If the file with the name <code>\$my_view</code> is found, <code>$params</code> are passed to the variable <code>$vars</code> as an array in the php script.</p>
	<p>Views are accumulative, like a stack specified by the function <code>m_view_add_path($my_path_for_views);</code>. When a view is requested by <code>m_view($my_view, $params)</code> it will be search from the last specified path to the first one (which is msc_lib/views/html5/)</p>
	<p>A collection of all html5 elements is build-in in the default path for views (msc_lib/views/html5). So, if you specify <code>m_view("a", array("body" => "I\'m a link"))</code> the html produced will be <code>&lt;a&gt;I\'m a link&lt;/a&gt;</code></p>',

	'views-example1-title' => 'Example 1: a custom view',
	'views-example1-text' => '<p>Let\'s suppose we have this folders/files structure:</p>
<pre>
index.php                       &larr; the index file
views/examples                  &larr; the views dir
views/examples/hello_world.php  &larr; the view called
msc_lib/...                     &larr; msc_lib of course!
</pre>
<p>The content of <b>hello_world.php</b> is:</p>
<pre>
&lt;h1&gt;Hello World!&lt;/h1&gt;
</pre>
<p>We want to call the view <b>hello_world</b> inside the folder <b>views/example</b>, this will be the <b>index.php</b> file:</p>
',
	'views-example2-title' => 'Example 2: error fallback',
	'views-example2-text' => '<p>By default, if a view is not found, the <code>m_view()</code> function returns a error message. When called with the 3th parameter \$silent set to true, a empty string will be returned if a view is not found.</p>
<p>Also, we can specify a default view as a fallback when a view is not found</p>
<p>Just add a <b>not_found</b> view into the folders/files structure:</p>
<pre>
index.php                             &larr; the index file
views/examples                        &larr; the views dir
views/examples/hello_world.php        &larr; a existing view
views/examples/common/not_found.php   &larr; the fallback view
msc_lib/...                           &larr; msc_lib of course!
</pre>
<p>The content of <b>not_found.php</b> is:</p>
<pre>
&lt;h1&gt;The requested page is not here!&lt;/h1&gt;
</pre>
<p>We want to call the view <b>hello_world</b> inside the folder <b>views/example</b>, this will be the <b>index.php</b> file:</p>
',
	'views-example3-title' => 'Example 3: Sending parameters',
	'views-example3-text' => '
<p>Variables can be send to the views, just put in a array as a second parameter of <code>m_view($view, $vars)</code></p>
<p>Variables will be available inside the view with the $vars array.</p>
<p>We are calling the view <b>test_vars.php</b> here, with this content:</p>
<pre>
&lt;?php
//convert all vars in $vars to standalone Variables
extract($vars);
?&gt;
&lt;h1&gt;&lt;?php echo $title; ?&gt;&lt;/h1&gt;
&lt;p&gt;&lt;?php echo $text; ?&gt;&lt;/p&gt;
</pre>
<p>Then, we just need to call the view with the vars <b>$title</b> and <b>$text</b>:</p>
',

	'views-example4-title' => 'Example 4: overriding views',
	'views-example4-text' => '<p>Multiple folders can be specified in order to overwrite views under certain conditions (like the visitor is a mobile device)</p>
<p>With this folders/files structure:</p>
<pre>
index.php                                  &larr; the index file
views/examples                             &larr; the views dir
views/examples/hello_world.php             &larr; a general view
views/examples/sample_content.php          &larr; a general view
views/examples/mobile                      &larr; the mobiles override dir
views/examples/mobile/sample_content.php   &larr; some view that overrides a general one
msc_lib/...                                &larr; msc_lib of course!
</pre>
<p>This will show different views (only if exists) if the browser is a mobile device:</p>
',

	'views-example5-title' => 'Using the HTML5 set of built-in views',
	'views-example5-text' => '<p>The default folder to search views is <b>msc_lib/views/html5/</b></p>
<p>All the common HTML5 tags are defined as views, attributes can be passed trough the second parameter of the <code>m_view($view, $vars)</code> function.</p>
<p>Besides, <b>$vars</b> can be just a string, then the it will be converted automatically to the <b>$body</b> var</p>
<p>This vars are commonly used in the HTML5 views:</p>
<table class="table">
<tr><td><b>body</b></td><td>Specifies the content between tags: &lt;tag&gt;<b>body</b>&lt;/tag&gt;</td></tr>
<tr><td><b>class</b></td><td>Specifies the <b>class</b> attribute of any tag</td></tr>
<tr><td><b>style</b></td><td>Specifies the <b>style</b> attribute of any tag</td></tr>
<tr><td><b>id</b></td><td>Specifies the <b>id</b> attribute of any tag</td></tr>
<tr><td><b>onload</b></td><td>Specifies the <b>onload</b> event attribute of any tag</td></tr>
<tr><td><b>onmouseover</b></td><td>Specifies the <b>onmouseover</b> event attribute of any tag</td></tr>
<tr><td><b>...</b></td><td>Same for any other valid HTML5 attribute</td></tr>

<tr><td><b>data-<i>something</i></b></td><td>Specifies the <b>data-<i>something</i></b> attribute of any tag, usefull if you are using jQuery</td></tr>
<tr><td><b>href</b></td><td>Specifies the <b>href</b> attribute in &lt;a&gt;, &lt;area&gt;, &lt;base&gt;, &lt;link&gt; tags</td></tr>
<tr><td><b>rel</b></td><td>Specifies the <b>rel</b> attribute in &lt;a&gt;, &lt;area&gt;, &lt;link&gt; tags</td></tr>
<tr><td><b>...</b></td><td>Etcetera</td></tr>
<tr><td><b>items</b></td><td>Specifies the <b>option</b> list in a &lt;select&gt; tag or the <b>&lt;li&gt;</b> tags list inside a &lt;ul&gt;.<br>
Could be a simple array like: <code>$options = array("item 1", "item 2", "item 3");</code><br>
Or an array of arrays with attributes inside: <pre>$options = array(
	array("body" => "item 1", "value" => "item1"),
	array("body" => "item 2", "value" => "item2", "disabled" => true),
	array("body" => "item 3", "value" => "item3"),
	);</pre>
<p>A <b>value</b> attribute can be passed to the parent &lt;select&gt; tag, then the attribute <b>selected</b> in the <b>options</b> list will be automatically detected.
</td></tr>
<tr><td><b>label</b></td><td>In <b>input</b> or <b>textarea</b> tags will create a &lt;label&gt;&lt;/label&gt; tags automatically.</td></tr>
<tr><td><b>tagtitle</b></td><td>The content of the &lt;title&gt;tagtitle&lt;/title&gt; inside the <b>html</b> view</td></tr>
<tr><td><b>header</b></td><td>Additional content after the &lt;title&gt; tag inside of the &lt;head&gt;&lt;/head&gt; in the <b>html</b> view</td></tr>
</table>
<p><a href="../doc/html5/files.html">Checkout the documentation for the HTML5 files</a></p>
<p>Some examples:</p>
',

	//misc
	'try-it' => "Try it",
);