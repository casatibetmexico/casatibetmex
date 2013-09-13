var CTImageFader = Class.create({

	ob: null,
	canvas: null,
	img: null,
	option: null,
	w: 0,
	h: 0,
	active:false,
	
	init: function(ob, options) {
	
		var me = this;
	
		this.ob = jQuery(ob);
		this.img = this.ob.find('img');
		this.img.hide();
		this.active = false;
		
		this.options = options || {fade:400};
		
		if (this.isCanvasSupported() && this.img.length > 0) {
			me.genImage();
		} else {
			this.img.show();	
		}
				
	},

	genImage: function() {	
	
		var me = this;	
		
		this.w = this.options.w || this.img.width();
		this.h = this.options.h || this.img.height();
		
		if (!this.img.width() && !this.img.height()) {
			this.img.hide();
			this.img.load(function() {
				me.genImage();
			});
			return false;
		}
		
		this.ob.width(this.w);
		this.ob.height(this.h);		
			
		this.canvas = jQuery('<canvas />').get(0);
		this.canvas.width = this.w;
		this.canvas.height = this.h;
	
		var ctx = this.canvas.getContext('2d');
		var img = this.img.get(0);
		
		ctx.drawImage(img, 0, 0);
	
		try {
		
			var imageData = ctx.getImageData(0, 0, this.w, this.h);
    		var data = imageData.data;
    		
			for(var i = 0; i < data.length; i += 4) {
		        var brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
		       	data[i] = brightness;
		        data[i + 1] = brightness;
		    	data[i + 2] = brightness;
		    }
		    
		    ctx.putImageData(imageData, 0, 0);
		
		} catch (e) {
			console.log(e);
		}

	    
	    var dataURL = this.canvas.toDataURL("image/png");
	    
	    this.ob.css('background-image', 'url('+dataURL+')');
	    
	    this.img = jQuery(img);
	    
	    if (!this.options.state) this.img.hide();
	    else this.img.show();
	    
	    this.active = true;
		
	},
	
	isCanvasSupported: function(callback) {
		var elem = document.createElement('canvas');
		return !!(elem.getContext && elem.getContext('2d'));
	},
	
	onOver: function(rate, callback) {
		if (this.active) {
			rate = rate || this.options.fade;
			this.ob.find('img').fadeIn(rate, callback);
		}
	},
	
	onOut: function(rate, callback) {
		if (this.active) {
			rate = rate || this.options.fade;
			this.ob.find('img').fadeOut(rate, callback);
		}
	}
	
});

(function( $ ) {

	
	var methods = {
	 
	     init : function( options ) {   
	     	     
	       return this.each(function(){
		      $(this).data('imageFader', new CTImageFader(this, options));
	       });
	       
	     }
	     
	};

  $.fn.imageFader = function(method) {
  
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.imageFader' );
    }    

  };
  
})( jQuery );