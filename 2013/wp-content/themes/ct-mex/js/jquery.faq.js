(function( $ ){

  $.fn.faq = function( ops ) {  

    return this.each(function() {

      	var $this = $(this);
  
  		var question = $this.find('li.question');
  		ct.addHover(question);
  		
  		question.click(function() {
  			var answer = jQuery(this).find('.answer');
  			if (answer.is(':visible')) {
  				jQuery(this).removeClass('on');
  				answer.slideUp(400);
  			} else {
  				jQuery(this).addClass('on');
  				answer.slideDown(400);
  			}
  		});
  		
  		

    });

  };
})( jQuery );
