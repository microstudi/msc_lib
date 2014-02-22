<?php

//get the vars ($run)
extract($vars);

$examples = array(
	"echo m_view('html', array('tagtitle' => 'This is a test page', 'body' => 'Hello world'));",

	"//add the path of our custom views
m_view_add_path('views/examples');
//call the view into a var
\$body = m_view('hello_world');
//output the result using the default view html
echo m_view('html', array('tagtitle' => 'This is a view\'s test page', 'body' => \$body));",

	"//add the path of our custom views
m_view_add_path('views/examples');
//calling a existing view
\$view1 = m_view('hello_world');
//calling a non-existing view
\$view2 = m_view('i_dont_exists');
//calling a non-existing view with the silent parameter
\$view3 = m_view('i_dont_exists', array(), true);

//adding a fallback view in this point
m_view_fallback('common/not_found');
//calling a non-existing view
\$view4 = m_view('i_dont_exists');
//calling a non-existing view with the silent parameter
\$view5 = m_view('i_dont_exists', array(), true);

\$body = \"\$view1
<hr>
\$view2
<hr>
\$view3
<hr>
\$view4
<hr>
\$view5\";

//output the result using the default view html
echo m_view('html', array('tagtitle' => 'This is a view\'s test page', 'body' => \$body));",

	"//add the path of our custom views
m_view_add_path('views/examples');
//call the view into a var and send some parameters inside
\$body = m_view('test_vars', array(
	'title' => 'This is my hero title',
	'text' => 'Lorem ipsum here...'
	));
//output the result using the default view html
echo m_view('html', array('tagtitle' => 'This is a view\'s test page', 'body' => \$body));",

	"//add the path of our custom views
m_view_add_path('views/examples');
//include a mobile view only if is a mobile
if(m_is_mobile()) {
	m_view_add_path('views/examples/mobile');
}
//call the view into a var
\$body = m_view('hello_world');
\$body .= m_view('sample_content');
//output the result using the default view html
echo m_view('html', array('tagtitle' => 'This is a view\'s test page', 'body' => \$body));",

	"//a title, no need to pass a array
\$body = m_view('h1', 'This is a title');

//generate a simple ul list
\$body .= m_view('h2', 'This is a ul list:');
\$body .= m_view('ul', array('items' => array('Item 1', 'Item 2', 'Item 3')));

//generate a ol list
\$body .= m_view('h2', 'This is a ol list:');
\$body .= m_view('ol', array('items' => array(
                                             array('body' => 'Item 1'),
                                             array('body' => 'Item 2', 'style' => 'color:red'),
                                             array('body' => 'Item 3', 'style' => 'color:blue;text-decoration:underline')
                                            )
                            )
                );

//generate a select list with selected value
\$body .= m_view('h2', 'This is a select list:');
\$form  = m_view('select', array(
                            'value' => 'item2',
                            'items' => array(
                                            array('body' => 'Item 1', 'value' => 'item1'),
                                            array('body' => 'Item 2', 'value' => 'item2', 'style' => 'color:red'),
                                            array('body' => 'Item 3', 'value' => 'item3', 'style' => 'color:blue')
                                            )
                                )
                );

//wrap in a form view
\$body .= m_view('form', array('enctype' => 'multipart/form-data', 'enctype' => 'post', 'action' => '#', 'body' => \$form));

//output the result using the default view html
echo m_view('html', array('tagtitle' => 'This is a view\'s test page', 'body' => \$body));",
);

//just execute the examples if required
if(isset($run)) {
	eval($examples[$run]);
	die;
}

//change the global var title in this section
m_config_var('title', m_lang_echo('default_title') .": ". m_lang_echo("title-views"));

?><div class="page-header">
	<h1><?php echo m_lang_echo("title-views"); ?></h1>
</div>
<p>Checkout the complete <a href="../doc/package-Views.html">function reference</a> for views.</p>

<?php
foreach ($examples as $i => $ex):
?>
<section id="<?php echo "ex-$i"; ?>">
	<h2><?php echo m_lang_echo("views-example$i-title"); ?></h2>
	<?php	echo m_lang_echo("views-example$i-text"); ?>
	<pre class="prettyprint numlines">&lt;?php
include('msc_lib/start.php');
<?php
echo htmlspecialchars($examples[$i]);
?>
	</pre>
<p><a class="btn btn-primary" target="_blank" href="?view=views&amp;run=<?php echo $i; ?>&amp;lang=<?php echo m_lang_select(); ?>"><i class="icon-fire icon-white"></i> <?php echo m_lang_echo('try-it') ?></a></p>

</section>
<?php
endforeach;
?>
