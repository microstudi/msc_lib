<?php

//get the vars ($run)
extract($vars);

$file = "img/rainbow.jpg";
$examples = array(
		array("m_image('$file', 200, 150, 1);", "Resize 1", "Direct resize and flush image"),
		array("m_image_set_fallback();
m_image('i-dont-exists.jpg');", "Fallback 1", "Non existing image with fallback auto-created image"),
		array("m_image_set_fallback('img/no-image.png', 'NOT IMAGE FOUND');
m_image('i-dont-exists.jpg');", "Fallback 2", "Non existing image with existing fallback image"),
		array("m_image_set_fallback('img/no-image.png', '');
m_image('i-dont-exists.jpg');", "Fallback 3", "Non existing image with fallback existing image without description over"),
		array("m_image_mix('$file', 'img/bird.png');", "Mixing images", "Direct mixing of 2 images, centered, alpha 50%"),
		array("m_image_mix('$file', array('img/bird.png', 'img/bird2.png'));", "Image mixing 1", "Direct mixing of 3 images, centered, alpha 50%"),
		array("m_image_mix('$file',
		 	array(
				'img/bird.png'  => array('position_x' => 'left',  'position_y' => 'bottom', 'alpha' => 90),
				'img/bird2.png' => array('position_x' => 'right', 'position_y' => -200, 'alpha' => 20)
				),
			array('width' => 600, 'height' => 400)
			);",
		"Image mixing 2", "Direct mixing of 3 images, with full options"),
		array("m_image_string('$file', 'Great text here');", "Text over image", "Direct text over image"),
		array("m_image_string('', 'Great text here');", "Image text", "Direct text without image"),
		array("m_image_string(m_image('$file',200,100,1,'gd'), array('text'=> 'Great text here', 'color' => 'bb0000', 'size' => 5));", "Text over image resized", "Direct text over image previously resized. The 'gd' param will ignore the cache"),
		array("m_image_cache('cache');
m_image('$file', 200, 150, 1);", "Resize using local cache", "Resize image, saves to a cache and flush. If cache file exists, then redirect to cache file. File modification times are checked on background."),
		array("m_image_cache('cache');
m_image_string(m_image('$file',200,200,1,'mimage'), 'Great text here');", "Image text using local cache", "Similar using cache"),
		array("m_image_cache('cache');
m_image_mix('$file',
		 	array(
				'img/bird.png'  => array('position_x' => 'right',  'position_y' => 'top', 'alpha' => 10),
				'img/bird2.png'
				),
			array('width' => 600, 'height' => 400)
			);",
		"Image mixing with cache", "Mixing 3 images using local cache"),
		array("m_file_set_remote('file', array('rootpath' => 'cache', 'rooturl' => '" . m_file_url_prefix() . "/cache'));
m_image_cache('', 'remote');
m_image('$file', 200, 150, 1);", "Resize using remote cache", "Resize image, saves to a cache and flush. If cache file exists, then redirect to cache file.<br><strong>Note:</strong> In this case the cache is a remote service, could be, local, ftp, ssh or Amazon S3 storage."),
		array("m_image_cache('cache');
m_image('" . m_file_url_prefix() . "/$file', 200, 150, 1);", "Remote (http) images as well", "Resize image, saves to a cache and flush. If cache file exists, then redirect to cache file.<br>File modification for the http image are regenerated every hour."),
	);

//just execute the examples if required
if(isset($run)) {
	eval($examples[$run][0]);
	return;
}

//change the global var title in this section
m_config_var('title', "MSC LIB Image processing examples");

?>
<?php

foreach($examples as $i => $a):

	$ex    = $a[0];
	$title = ($a[2] ? $a[1] : "Example #$i");
	$desc  = ($a[2] ? $a[2] : $a[1]);
	if(empty($ex)) continue;

	$ex = htmlspecialchars('<' . '?php
include_once("msc_lib/start.php");
' . $ex . '
die;
');

?>
	<section id="ex-<?php echo $i; ?>">
		<h2><?php echo $title; ?></h2>
		<p><?php echo $desc; ?></p>
		<pre class="prettyprint linenums"><?php echo $ex; ?></pre>
		<p><a class="btn btn-primary image-modal" href="?view=mimage&amp;run=<?php echo (int)$i; ?>&amp;lang=<?php echo m_lang_select(); ?>"><i class="icon-fire icon-white"></i> <?php echo m_lang_echo('try-it') ?></a></p>
	</section>
<?php
endforeach;
?>
