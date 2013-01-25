<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="vendors/google-code-prettify/prettify.js"></script>

<script>
$(function(){
	prettyPrint();
	//click on images
	$('a.image-modal').click(function(e){
		e.preventDefault();
		var h = $(this).attr('href');
		$('#modal .modal-body').html('<a href="' + h + '"><img src="' + h + '"></a><p><a href="' + h + '">Click to view the image directly</a></p>');
		$('#modal').modal('show');
	});
	//add dropdown menus
	if($('section[id]').is('section')) {
		var $li = $('#m-<?php echo m_config_var('part'); ?>');
		$li.addClass('dropdown');
		$li.html('<a id="a-' + $li.attr('id') + '" class="dropdown-toggle" data-toggle="dropdown">' + $li.find('a').html()+ '<b class="caret"></b></a>');
		$li.append('<ul class="dropdown-menu"></ul>');
		$('section[id]').each(function(){
			var id = $(this).attr('id');
			var text = $(this).find('h2').text();
			$li.find('ul.dropdown-menu').append('<li><a href="#' + id + '">' + text + '</a></li>');
		});
	}
});
</script>