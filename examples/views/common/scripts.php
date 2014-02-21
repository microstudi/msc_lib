<?php

extract($vars);

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="vendors/google-code-prettify/prettify.js"></script>

<script>
$(function(){
	prettyPrint();

	//click on images
	$('a.image-modal').click(function(e){
		e.preventDefault();
		var id = parseInt($(this).closest('section').attr('id').substr(3));

		var h = $(this).attr('href');
		var t = '<a target="_blank" href="' + h + '"><img src="' + h + '"></a><p><a target="_blank" href="' + h + '">Click to view the image directly</a></p>';
		if(id > 9) {
			t += '<p><a target="_blank" href="cache/">View the cache dir</a></p>';
		}
		$('#modal .modal-body').html(t);
		$('#modal').modal('show');
	});

	//add dropdown menus
	if($('section[id]').is('section')) {
		var $li = $('#m-<?php echo $view; ?>');
		$li.addClass('dropdown');
		$li.html('<a id="a-' + $li.attr('id') + '" class="dropdown-toggle" data-toggle="dropdown">' + $li.find('a').html()+ '<b class="caret"></b></a>');
		$li.append('<ul class="dropdown-menu"></ul>');
		$('section[id]').each(function(){
			var id = $(this).attr('id');
			var text = $(this).find('h2').text();
			$li.find('ul.dropdown-menu').append('<li><a href="#' + id + '">' + text + '</a></li>');
		});
		$li.hover(function() {
			if($(window).width() < 980) return;
			try{clearTimeout(T_HIDE)}catch(e){};
			$('.dropdown-menu').show();
		},function() {
			if($(window).width() < 980) return;
			 T_HIDE = setTimeout(function(){$('.dropdown-menu').fadeOut()}, 200);
		});
		$li.find('ul.dropdown-menu a').click(function(){
			if($(window).width() < 980) return;
			try{clearTimeout(T_HIDE)}catch(e){};
			$('.dropdown-menu').fadeOut();
		});
	}
});
</script>