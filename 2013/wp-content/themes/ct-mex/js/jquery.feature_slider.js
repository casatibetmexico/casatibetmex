var CTFeatureSlider = Class.create({

	ob: null,
	num: 0,
	cur: 0,
	slides: null,
	options: 0,
	is_animating: false,
	
	init: function(ob, options) {
	
		var me = this;
	
		this.ob = jQuery(ob);
		this.is_animating = false;
		
		this.options = options || {};
		
		this.slides = this.options.items;
		this.num = this.slides.length;
		this.cur = 0;
		
		if (this.num > 0) {
			var container = jQuery('<div class="slides" />');
			container.append('<div class="slide prev"></div><div class="slide prev"><div class="preview"></div></div>');
			container.append('<div class="slide cur"></div>');
			container.append('<div class="slide next"><div class="preview"></div></div><div class="slide next"></div>');
			this.ob.append(container);
			
			for(var i=0; i<3; i++) {
				var index = i % this.num;
				if (this.num == 1 && i > 0) break;
				if (i <= this.num && this.slides[index]) {
					this.setSlide(i+2, index);	
				}
			}
			for(var i=0; i<2; i++) {
				var index = (-1-i) + this.num;
				if (this.num > 1 && this.slides[index]) {
					this.setSlide(1-i, index);	
				}
			}
			
		}	
		
		if (this.options.anim_rate && this.slides.length > 1) {
			this.options.automatic = true;
		}
		
		
		jQuery(document).ready(function() {
			setTimeout(function() {
				me.animOverlayEntry(jQuery('.slide.cur'));
			}, 400);
		});
		
		
		
						
	},
	
	setSlide: function(target, index) {
		var data = this.slides[index];
		var slide = this.ob.find('.slide:eq('+target+')');
		
		var newSlide = slide.clone();		
		newSlide.addClass('ui');
		newSlide.empty();
		newSlide.data('id', index);
		
		var info = jQuery('<div class="info" />');
		
		if (!this.options.no_overlay) {
			var overlay = jQuery('<div class="overlay" />');
			overlay.append('<h1>'+data.title+'</h1>');
			overlay.append('<p class="serif-italic">'+data.caption+'</p>');
			
			if (data.url) {
			
				overlay.data('href', data.url);
				overlay.addClass('ui');
				overlay.click(function() {
					var href = jQuery(this).data('href');
					ct.goTo(href, true);
				});
			
				var btn = jQuery('<div class="btn red"><div class="label">M&Aacute;S INFORMACI&Oacute;N AQU&Iacute;</div></div>');

				btn.click(function() {
					var href = jQuery(this).parent().data('href');
					ct.goTo(href, true);
				});
				overlay.append(btn);
				
			}
		
			info.append(overlay);
		}
		
		var bg = jQuery('<div class="bg ct-image-fader" />');
		var img = jQuery('<img src="'+data.image+'" />');
		bg.append(img);
		bg.imageFader({fade:500, w:805, h:350, state:slide.hasClass('cur')});
		info.append(bg);
		
		newSlide.append(info);
		/* newSlide.append('<div class="preview"><img src="'+data.image+'" /></div>'); */
		
		var me = this;
		newSlide.click(function() {
			me.options.automatic = false;
			if (jQuery(this).hasClass('prev')) me.prev(this);
			else if (jQuery(this).hasClass('next')) me.next(this);
		});
		
		slide.replaceWith(newSlide);	
		
		
		
		
		

	},
	
	next: function(slide) {
		if (!this.is_animating) {
			this.is_animating = true;
			var me = this;
			var slides = this.ob.find('.slides');
			var pos = slides.position();
			var offset = jQuery(slide).width();
			
			jQuery(slide).find('.overlay').hide();	
			jQuery(slide).find('.ct-image-fader').data('imageFader').onOver(1000, function() {
				jQuery(slide).removeClass('next');
			});	
			
			jQuery('.slide.cur').find('.ct-image-fader').data('imageFader').onOut();
			jQuery('.slide.cur').find('.overlay').fadeOut();
			
			slides.animate({left:pos.left - offset}, {duration:1000,
				complete: function() {
					me.setCurrentSlide(jQuery(slide));
				}
			});
		}
	},
	
	prev: function(slide) {
		if (!this.is_animating) {
			this.is_animating = true;
			var me = this;
			var slides = this.ob.find('.slides');
			var pos = slides.position();
			var offset = jQuery(slide).width();
			
			jQuery(slide).find('.overlay').hide();
			jQuery(slide).find('.ct-image-fader').data('imageFader').onOver(1000, function() {
				jQuery(slide).removeClass('prev');
				
			});
			
			jQuery('.slide.cur').find('.overlay').fadeOut();
			jQuery('.slide.cur').find('.ct-image-fader').data('imageFader').onOut();

			
			slides.animate({left:pos.left + offset}, {duration:1000,
				complete: function() {
					me.setCurrentSlide(jQuery(slide));
				}
			});
		}
	},
	
	setCurrentSlide: function(slide) {
	
		var index = slide.index();
		var newState = (index > 2) ? 'prev' : 'next';
	
		var cur = jQuery('.slide.cur');
		cur.removeClass('cur');
		cur.addClass(newState);
		
		/*
var overlay = cur.find('.overlay');
		this.resetOverlay(overlay);
*/
		
		this.shiftSlides(slide);
		
		this.animOverlayEntry(slide);
		
	},
	
	animOverlayEntry: function(slide) {
	
		var me = this;
	
		if (!this.options.no_overlay) {
			var overlay = slide.find('.overlay');
		
			this.resetOverlay(overlay);
			
			
			overlay.delay(100).fadeIn(400);
			overlay.find('h1').delay(600).animate({opacity:1, left:0}, 400);
			overlay.find('p').delay(700).animate({opacity:1, left:0}, 400, function() {
				slide.addClass('cur');
				me.is_animating = false;
				if (me.options.automatic) {
					setTimeout(function() {
						me.next(jQuery('.slide.next:eq(0)'));
					}, me.options.anim_rate);
				}
				
			});
			overlay.find('.btn').delay(800).fadeIn(400);
		} else {
			slide.addClass('cur');
			this.is_animating = false;
			if (this.options.automatic) {
				setTimeout(function() {
					me.next(jQuery('.slide.next:eq(0)'));
				}, this.options.anim_rate);
			}
		}
	
		
			
	},
	
	resetOverlay: function(overlay) {
		overlay.hide();
		
		var title = overlay.find('h1');
		title.css('left', 70);
		title.css('opacity', 0);
		
		var caption = overlay.find('p');
		caption.css('left', 50);
		caption.css('opacity', 0);
		
		overlay.find('.btn').hide();

	},
	
	shiftSlides:function(cur) {
	
		var index = cur.index();
		var slides = this.ob.find('.slides');
		
		var id = cur.data('id');
		
		if (index > 2) { // front -> back
			var s = jQuery('.slide:eq(0)')
			s.detach();
			s.removeClass('prev');
			s.addClass('next');
			slides.append(s);
			
			id = (id+2) % this.num;
			this.setSlide(4, id);

		} else { // back -> front
			
			var s = jQuery('.slide:eq(4)')
			s.detach();
			s.removeClass('next');
			s.addClass('prev');
			slides.prepend(s);
			
			id -= 2;
			if (id < 0) id = this.num + id;
			this.setSlide(0, id);
		}
		slides.css('left', -1500);
		
		
		
		
		
	}
	
});

(function( $ ) {

	
	var methods = {
	 
	     init : function( options ) {   
	     	     
	       return this.each(function(){
		      $(this).data('featureSlider', new CTFeatureSlider(this, options));
	       });
	       
	     }
	     
	};

  $.fn.featureSlider = function(method) {
  
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.featureSlider' );
    }    

  };
  
})( jQuery );