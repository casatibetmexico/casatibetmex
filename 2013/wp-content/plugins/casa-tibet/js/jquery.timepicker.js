(function( $ ){

  $.fn.timepicker = function( ops ) {  

    return this.each(function() {

      	var $this = $(this);
  
  		var hours = 24;
  		var mins = ['00', '15', '30', '45'];
  
  		var overlay = jQuery('<div class="overlay" />');
  		var options = jQuery('<select size="'+((hours*mins.length)+1)+'" class="custom"><option value="-1">-- Elige un horario --</option></select>');
  		for(var i=0; i<hours; i++) {
  			var hour = (i < 10) ? '0'+i : i;
  			for(var j=0; j<mins.length; j++) {
  				var val = hour+':'+mins[j];
  				options.append('<option value="'+val+'">'+val+'</option>');
  			}
  		}
  		options.blur(function() {
  			$this.find('.overlay').fadeOut(200);
  		});
  		options.change(function() {
  			var val = jQuery(this).val();
  			if (val != '-1') $this.find('input').val(val);
  			$this.find('.overlay').fadeOut(200);
  			$this.find('input').focus();
  		});
  		
  		overlay.append(options);
  		
  		$this.append(overlay);
  
		$this.find('input').click(function() {
			var pos = jQuery(this).position();
			var o = $this.find('.overlay');
			o.css('left', pos.left-7);
			o.css('top', pos.top+jQuery(this).height()+10);
			o.fadeIn(200);
		});

    });

  };
})( jQuery );
