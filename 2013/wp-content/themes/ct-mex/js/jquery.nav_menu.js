var CTNavMenu = Class.create({

	ob: null,
	sub: null,
	cur: null,
	timeout: null,
	
	init: function(ob, options, config) {
	
		var me = this;
	
		this.ob = jQuery(ob);
		
		if (!config.no_caps) this.ob.append('<li class="cap" />');
		
		if (options) {
	
		
			for(var i=0; i<options.length; i++) {
				var item = options[i];
				if (i>0 && !config.no_separator) this.ob.append('<li class="separator" />');
				var li = jQuery('<li>'+item.label+'</li>');
							
				li.addClass('ui');
				li.hover(function() {
					me.onOver(jQuery(this));
				}, function() {
					me.onOut(jQuery(this));
				});
				li.click(function() {
					var url = jQuery(this).data('url');
					var target = jQuery(this).data('target');
					ct.goTo(url, true, target);
				});
				li.mouseleave(function() {
					me.timeout = setTimeout(function() {
						me.hideSubMenu();
					}, 1000);
				});
				
				li.data(item);
				this.ob.append(li);
			}
			
			if (!config.no_caps) this.ob.append('<li class="cap" />');
				
			this.center();
			
			this.sub = jQuery('<div class="sub_menu"><ul></ul></div>');
			this.sub.mouseover(function() {
				clearTimeout(me.timeout);
			});
			this.sub.mouseleave(function() {
				me.timeout = setTimeout(function() {
					me.hideSubMenu();
				}, 1000);
			});
			jQuery('body').append(this.sub);
		
		}	
				
	},
	
	center: function() {
		
		var w = 0;
		this.ob.find('li').each(function() {
			w += jQuery(this).outerWidth();
		});
		this.ob.width(w+10);
	},
	
	onOver: function(item) {
	
		clearTimeout(this.timeout);
	
		if (!item.hasClass('on')) {
			this.hideSubMenu();	
			item.addClass('over');
		
			var children = item.data('children');
			if (children) this.showSubMenu(item, children);
		}
		
	},
	
	onOut: function(item) {
		item.removeClass('over');
	},
	
	showSubMenu: function(item, children) {
		
		var list = this.sub.find('ul');
		list.empty();
		for(var i=0; i<children.length; i++) {
			var li = jQuery('<li class="ui">'+children[i].label+'</li>');
			li.hover(function() {
				jQuery(this).addClass('over');
			}, function() {
				jQuery(this).removeClass('over');
			});
			li.click(function() {
				ct.goTo(jQuery(this).data('url'), true, jQuery(this).data('target'));
			});
			li.data(children[i]);
			list.append(li);
		}
		
		var pos = item.offset();
		this.sub.css('left', pos.left);
		this.sub.css('top', pos.top);
		this.sub.show();
		
		var h = list.height();
		this.sub.height(0);
		this.sub.animate({height:h}, 300);
		item.addClass('on');
		
		this.cur = item;
		
	},
	
	hideSubMenu: function(animate) {
		if (animate) {
			var me = this;
			this.sub.fadeOut(200, function() {
				if (me.cur) me.cur.removeClass('on');
			});
		} else {
			this.sub.height(0);
			this.sub.hide();
			if (this.cur) this.cur.removeClass('on');
		} 
		
	}
	
});

(function( $ ) {

	
	var methods = {
	 
	     init : function( items, options ) {
	     
	     	if (!options) options = {};
	     	     
	       return this.each(function(){
	       
	       		var data = $(this).data('navMenu');
	             if ( ! data ) {
		           $(this).data('navMenu', {
		           	   ob:new CTNavMenu(this, items, options)
		           });
		           data = $(this).data('navMenu');
		         }				
	
	       });
	       
	     }
	     
	};

  $.fn.navMenu = function(method) {
  
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 2 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.navMenu' );
    }    

  };
  
})( jQuery );