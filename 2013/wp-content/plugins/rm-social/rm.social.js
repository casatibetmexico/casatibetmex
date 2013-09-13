(function( $ ){

  $.fn.share = function(type, options) { 
  
  		this.addClass('social_btn');
  
  		var ops = jQuery.extend({left:0, top:0, width:300, height:200}, options);
 
  		var pos = this.offset();
  		pos.left += ops.left;
  		pos.top += ops.top;
  		
  		if (ops.value) {
  			var label = jQuery('<div class="label">'+ops.value+'</div>');
  			this.append(label);
  		}  		
		this.popupWindow({
			windowURL:options.target, 
			windowName:type+'_win',
			width:ops.width, 
			height:ops.height, 
			centerBrowser:1 });
		
  };
  
})( jQuery );