(function( $ ){

  $.fn.embed = function( ops ) {  

    return this.each(function() {

      	var $this = $(this);
  
		switch(ops.type) {
			case 'gmap':
				var html = '<iframe width="'+ops.w+'" height="'+ops.h+'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+ops.url+'&amp;output=embed"></iframe>';
				break;
			case 'vimeo':
				var html = '<iframe src="http://player.vimeo.com/video/'+ops.id+'" width="'+ops.w+'" height="'+ops.h+'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				break;
			case 'youtube':
			default:
				var html = '<iframe width="'+ops.w+'" height="'+ops.h+'" src="http://www.youtube.com/embed/'+ops.id+'?rel=0" frameborder="0" allowfullscreen></iframe>';
				break;
		}
		$this.html(html);

    });

  };
})( jQuery );
