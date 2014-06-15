var ct = {
	theme:'',
	siteurl:'',
	logout:'',
	init: function() {
		
		this.initNavBar();
		this.initFooter();
		this.initTags();
		this.initLists();
		
		this.initBtns();
		
		jQuery('p:empty').remove();
		
		jQuery(window).resize();
	},
	pad: function (n, width, z) {
	  z = z || '0';
	  n = n + '';
	  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	},
	onResize: function(e) {
		
	},
	goTo: function(url, full, target) {
		if (url != undefined) {
			if(target == '_blank') {
				window.open(url);
			} else {
				document.location.href = (full) ? url : this.siteurl+url;
			}
		}
	},
	
	initNavBar: function() {
	
		var me = this;
	
		// Nav Bar
		jQuery('.nav_bar .menu li').each(function() {
		
			var item = jQuery(this);
			var data = item.data();
		
			item.hover(function() {
				jQuery(this).addClass('over');
			}, function() {
				jQuery(this).removeClass('over');
			});
			
			item.click(function() {
				if (!jQuery(this).hasClass('on')) {
					var href = jQuery(this).data('href');
					if (href) document.location.href = href;
				}
			});
			
			if (data.site == me.theme) {
				item.addClass('on');
			}
			
		});
		
		// Login 
		jQuery('.nav_bar .login li').each(function() {
		
			var item = jQuery(this);
			var action = item.data('action');
			
			if (action != undefined) {
				item.addClass('ui');
				item.hover(function() {
					jQuery(this).addClass('over');
				}, function() {
					jQuery(this).removeClass('over');
				})
				switch(action) {
					case 'login':
						item.click(function() {
							var href = jQuery(this).data('href');
							if (href) {
								ct.goTo(href, true);
							} else {
								var domain = jQuery(this).data('domain');
								if (domain) {
									ct.goTo(domain+'/login', true);
								} else {
									ct.goTo('/login');
								}
							}
							
							
						});
						break;
					case 'register':
						item.click(function() {
							ct.goTo(jQuery(this).data('href'), true);
						});
						break;
					case 'groups':
						item.click(function() {
							ct.goTo('/groups');
						});
						break;
					case 'profile':
						item.click(function() {
							var href = jQuery(this).data('href');
							ct.goTo(href, true);
						});
						break;
					case 'logout':
						var href = item.data('href');
						item.click(function() {
							ct.goTo(href, true);
						});
						break;
					default:
						var href = item.data('href');
						item.click(function() {
							ct.goTo(href, true);
						});
						break;
				}
			}
			
		
		});
		
	},
	
	initFooter: function() {
		jQuery('#rm-footer .menu li').each(function() {
		
			var item = jQuery(this);
			var href = item.data('href');
			if (href) {
				item.addClass('ui');
				item.hover(function() {
					jQuery(this).addClass('over');
				}, function() {
					jQuery(this).removeClass('over');
				});
				item.click(function() {
					ct.goTo(href, true);
				});
			}
		
		});
	},
	
	initTags: function() {
		jQuery('.tag').each(function() {
			var $this = jQuery(this);
			$this.addClass('ui');
			$this.hover(function() {
				jQuery(this).addClass('over');
			}, function() {
				jQuery(this).removeClass('over');
			});
			$this.click(function(e) {
				e.stopPropagation();
				//console.log(jQuery(this).data('href'));
				ct.goTo(jQuery(this).data('href'), true);
			});	
		});
	},
	
	initLists: function() {
		
		
		var me = this;
		
		jQuery('.listing li').each(function() {
			
			var $this = jQuery(this);
			if ($this.data('href') != undefined) {
			
				$this.addClass('ui');
				$this.hover(function() {
					jQuery(this).addClass('over');
					jQuery(this).find('.ct-image-fader').trigger('ctListItemOver');
				}, function() {
					jQuery(this).removeClass('over');
					jQuery(this).find('.ct-image-fader').trigger('ctListItemOut');
				});
				
				$this.click(function() {
					ct.goTo(jQuery(this).data('href'), true);
				});
			
			}
			
		});
		
		jQuery('.ct-image-fader').bind('ctListItemOver', function() {
			jQuery(this).data('imageFader').onOver();
	    });
	    jQuery('.ct-image-fader').bind('ctListItemOut', function() {
	    	jQuery(this).data('imageFader').onOut();
	    });
		
		jQuery('.section .title').each(function() {
			
			var $this = jQuery(this);
			if ($this.data('href') != undefined) {
				console.log($this.data('href'));
				me.addHover(this);
				$this.click(function() {
					ct.goTo(jQuery(this).data('href'), true);
				});
			}			
			
		});

	},
	
	initBtns: function() {
		jQuery('.btn').each(function() {
			ct.addHover(this);
			jQuery(this).click(function() {
				var href = jQuery(this).data('href');
				if (href) ct.goTo(href, true);
			});
		});
	},
	
	addHover: function(item) {
		jQuery(item).addClass('ui');
		jQuery(item).hover(function(){
			jQuery(this).addClass('over');
		}, function() {
			jQuery(this).removeClass('over');
		});
	}
};
jQuery(window).resize(function(e) {
	ct.onResize(e);
});
jQuery(document).ready(function() {
	ct.init();
});