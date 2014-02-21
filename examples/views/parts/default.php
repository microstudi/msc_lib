<?php

//get the vars ($run)
extract($vars);

$examples = array(
	'echo m_view(\'html\', array(\'tagtitle\' => "This is a test page", \'body\' => "Hello world"));'
);

//just execute the examples if required
if(isset($run)) {
	eval($examples[$run]);
	die;
}

?><div class="page-header">
	<h1><?php echo m_lang_echo("what-is-msc_lib"); ?></h1>
</div>

<?php
	echo m_lang_echo('default-text');
?>


<section id="simple">
<h2>Getting started</h2>

<p>First you need to specify the path to msc_lib library on the top of your php pages:</p>
<pre class="prettyprint numlines">&lt;?php
include('msc_lib/start.php');
</pre>
<p>Then you can make use of the html5 basic default views:</p>
<pre class="prettyprint numlines">&lt;?php
include('msc_lib/start.php');
<?php
echo $examples[0];
?>
</pre>
<p><a class="btn btn-primary" target="_blank" href="?view=default&amp;run=0&amp;lang=<?php echo m_lang_select(); ?>"><i class="icon-fire icon-white"></i> <?php echo m_lang_echo('try-it') ?></a></p>
</section>

<section id="more">
<h2>More...</h2>
<p>Check for more examples on the following sections:</p>
<?php
	//remove index
	array_shift($menu);
	echo m_view("ul", array('class' => "nav", 'items' => $menu));

?>

</section>

<section id="documentation">
<h2>Function reference</h2>
<p>Checkout the <a href="../doc/">function reference</a></p>

</section>


<section id="download">
<h2>Download</h2>
<p>View the code in github (including this page):</p>

<p><a href="https://github.com/microstudi/msc_lib"><i class="icon-magnet"></i> github.com/microstudi/msc_lib</a></p>

<p>Download zip from github:</p>
<p><a href="https://github.com/microstudi/msc_lib/archive/master.zip"><i class="icon-download"></i> Download ZIP</a></p>

<section>
<p>This library was inspired by the <a href="http://elgg.org">Elgg</a> opensource software engine</p>
</section>


<address>
	<strong>Author</strong>: <a href="http://twitter.com/ivanverges">Ivan Vergés</a> 2011 - 2014<br>
	<strong>License</strong>: <a href="http://www.gnu.org/copyleft/lgpl.html">GNU Lesser General Public License</a>
</address>