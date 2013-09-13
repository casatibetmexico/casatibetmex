var ct = {es: {
	selector: {add: "Agregar", 
			   remove: "Quitar"},
	attachments: {edit_label: "Añadir Archivos de Media",
				  select:"Eligir"}
}};

var CTLocationAdmin = {

	ob: null,
	key: null,
	primary: 0,

	init: function(id, key, locs) {
		this.ob = jQuery(id);
		this.key = key;
		
		if (locs != undefined) {
			for(var i=0; i<locs.length; i++) {
				var li = this.generateItem(i, locs[i]);
				this.ob.find('ul').append(li);
			}
			this.addListBehaviour();
		}
		
		
		this.ob.find('.actions .button, .form .button').each(function() {
			jQuery(this).click(function() {
				CTLocationAdmin.action(jQuery(this).data('action'), jQuery(this));
			})
		});
		
		this.makePrimary(jQuery('#primary_loc').val() * 1);
		
	},
	
	generateItem: function(id, data) {
	
		console.log(data);
		var li = jQuery('<li id="loc_'+id+'"/>');
		
		var delBtn = jQuery('<div class="action"><a class="button" data-action="delete">BORRAR</a></div>');
		li.append(delBtn);
		
		if (id == this.primary) li.addClass('primary');	
	
		var html = '';
		if (data.address_1) html += data.address_1+'<br />';
		if (data.address_2) html += data.address_2+'<br />';
		if (data.tel && data.tel.length > 0) {
			if (data.tel.length > 1) {
				html += 'TELS: ';
				html += data.tel.join('&nbsp;&bull;&nbsp;');
			} else {
				html += 'TEL: '+data.tel[0];
			}
			html += '<br />';
		}
		
		if (data.email) html += 'EMAIL: '+data.email+'<br />';	
		if (data.schedule) html += 'HORARIOS: '+data.schedule+'<br />';
		if (data.map) html += 'MAPA: '+data.map;
			
		var load = jQuery('<a>'+data.name+'</a>');
		load.click(function() {
			var args = jQuery(this).parent().data();
			CTLocationAdmin.action('load', args);
		});
				
		li.append(load);
		li.append('<p>'+html+'</p>');
		
		li.hover(function() {
			jQuery(this).addClass('over');	
		}, function() {
			jQuery(this).removeClass('over');
		});
		
		data.id = id;
		li.data(data);
		
		var name = this.key+'['+id+']';
		if (data.name) 
			li.append('<input type="hidden" name="'+name+'[name]" value="'+data.name+'" />');
		if (data.address_1) 
			li.append('<input type="hidden" name="'+name+'[address_1]" value="'+data.address_1+'" />');
		if (data.address_2) 
			li.append('<input type="hidden" name="'+name+'[address_2]" value="'+data.address_2+'" />');
		if (data.tel && data.tel.length > 0) {
			for(var i=0; i<data.tel.length; i++) {
				if (data.tel[i]) 
					li.append('<input type="hidden" name="'+name+'[tel][]" value="'+data.tel[i]+'" />');
			}
		} 
		
		if (data.email) 
			li.append('<input type="hidden" name="'+name+'[email]" value="'+data.email+'" />');
		if (data.schedule) 
			li.append('<input type="hidden" name="'+name+'[schedule]" value="'+data.schedule+'" />');
		if (data.map) 
			li.append('<input type="hidden" name="'+name+'[map]" value="'+data.map+'" />');
			
		
		return li;
	},
	
	addListBehaviour: function() {
		this.ob.find('li .button').each(function() {
			jQuery(this).click(function() {
				CTLocationAdmin.action('delete', jQuery(this));
			});
		});
	},	
	
	makePrimary: function(id) {
		if (id != undefined) {
			this.ob.find('li').removeClass('primary');
			this.primary = id;
			this.ob.find('li:eq('+id+')').addClass('primary');
			jQuery('#primary_loc').val(id);
		}
	},
	
	action: function(type, args) {
		switch(type) {
			case 'add':
				this.toggleForm(true);
				break;
			case 'load':
				this.loadFormData(args);
				this.toggleForm(true);
				break;
			case 'delete':
				args = args.parent().parent().data();
				if (args.id == this.primary) this.makePrimary(0);
				this.ob.find('li:eq('+args.id+')').remove();
				this.ob.find('li').each(function(index) {
					jQuery(this).data('id', index);
					jQuery(this).attr('id', 'loc_'+index);
				});
				
				this.toggleForm(false);
				break;
			case 'cancel':
				this.toggleForm(false);
				break;
			case 'save':
				var data = this.getFormData();
				
				var is_new = (data.id) ? true : false;
				id = (is_new) ? data.id : this.ob.find('li').length;
				
				var li = this.generateItem(id, data);
							
				if (is_new) {
					this.ob.find('ul li:eq('+data.id+')').replaceWith(li);
				} else {
					this.ob.find('ul').append(li);
				}
				
				if (jQuery('#make_primary').attr('checked') == 'checked') this.makePrimary(id);
				
				this.addListBehaviour();
				this.toggleForm(false);
				break;
		}
	},
	
	toggleForm: function(state) {
		if (state) {
			this.ob.find('.actions').slideUp();
			this.ob.find('.form').slideDown();
		} else {
			this.ob.find('.actions').slideDown();
			this.ob.find('.form').slideUp();
			this.resetForm();
		}
	},
	
	resetForm: function() {
		this.ob.find('.form input').val('');
		this.ob.find('.form input[type=checkbox]').attr('checked', null);
	},
	
	loadFormData: function(data) {
		
		jQuery('#loc_id').val(data.id);
		if (data.name) jQuery('#loc_name').val(data.name);
		if (data.address_1) jQuery('#loc_address_1').val(data.address_1);
		if (data.address_2) jQuery('#loc_address_2').val(data.address_2);
		if (data.tel) {
			if (data.tel[0]) jQuery('#loc_tel_1').val(data.tel[0]);
			if (data.tel[1]) jQuery('#loc_tel_2').val(data.tel[1]);
			if (data.tel[2]) jQuery('#loc_tel_3').val(data.tel[2]);
		}
		if (data.email) jQuery('#loc_email').val(data.email);
		if (data.schedule) jQuery('#loc_schedule').val(data.schedule);
		if (data.map) jQuery('#loc_map').val(data.map);
		jQuery('#make_primary').attr('checked', (data.id == this.primary) ? 'checked' : null);
		
	},
	
	getFormData: function() {
		var ob = {};
		ob.id = jQuery('#loc_id').val();
		ob.name = jQuery('#loc_name').val();
		ob.address_1 = jQuery('#loc_address_1').val();
		ob.address_2 = jQuery('#loc_address_2').val();
		ob.tel = [];
		if (jQuery('#loc_tel_1').val()) ob.tel.push(jQuery('#loc_tel_1').val());
		if (jQuery('#loc_tel_2').val()) ob.tel.push(jQuery('#loc_tel_2').val());
		if (jQuery('#loc_tel_3').val()) ob.tel.push(jQuery('#loc_tel_3').val());
		ob.email = jQuery('#loc_email').val();
		ob.schedule = jQuery('#loc_schedule').val();
		ob.map = jQuery('#loc_map').val();
		return ob;
	}
	
};

var CTResourceSelector = {

	init: function(val) {
		
		if (val != undefined) {
		
			// Generate structure for left column
			var list = jQuery('.ct_mod_resource_selector .col.left .res_listing');	
			
			this.generateListing(list, val.types);
			
			// Generate structure for left column
			list = jQuery('.ct_mod_resource_selector .col.right .res_listing');			
			for(var i=0; i<val.selected.length; i++) {
				list.append(this.generateItem(val.selected[i], false));				
			}
			
			// Add Behaviours 
			jQuery('.ct_mod_resource_selector .col.left li').each(function() {
				jQuery(this).hover(function() {
					jQuery(this).addClass('over');
				}, function() {
					jQuery(this).removeClass('over');
				});
			});
			
			jQuery('.ct_mod_resource_selector .col.left li a').each(function() {
				var item = jQuery(this).parent().get(0);
				jQuery(this).click(function() {
					CTResourceSelector.add(item);
				});
			});
			this.updateValues(true);
		
		
		}
	
				
	},
	
	generateListing: function(list, types, sub) {
		for(var i=0; i<types.length; i++) {
				
			var t = types[i];
			var tli = jQuery('<li class="res_type">'+t.label+'</li>');
			if (sub) tli.addClass('sub_type');
			list.append(tli);
			
			if (t.children) {
				this.generateListing(list, t.children.types, true);
			} else {
				for(var j=0; j<t.items.length; j++) {
					list.append(this.generateItem(t.items[j], true, j));
				}
			}
						
		}
	},
	
	generateItem: function(item, action, index) {
	
		var li = jQuery('<li />');
		li.data(item);
		if (item.status) li.addClass('selected');
		
		if (index != undefined) {}
		
		var btn_label = (action) ? ct.es.selector.add : ct.es.selector.remove;
		var a = jQuery('<a class="button">'+btn_label+'</a>');
		var p = jQuery('<p>'+item.label+'</p>');
		var status = jQuery('<div class="status"></div>');
		li.append(status);
		li.append(a);
		li.append(p);
		return li;
	},
	
	add: function(item) {
		var i = jQuery(item);
		var data = i.data();
		var list = jQuery('.ct_mod_resource_selector .col.right .res_listing');	
		list.append(this.generateItem(data, false));
		i.addClass('selected');
		
		this.updateValues(true);
	},
	
	remove: function(item) {
		var data = jQuery(item).data();
		
		var res = jQuery('.ct_mod_resource_selector .col.left li').filter(function() {
		    return jQuery(this).data("id") == data.id;
		});
		
		res.removeClass('selected');
		
		jQuery(item).remove();
		
		this.updateValues();
	},
	
	updateValues: function(behavior) {
	
		if (behavior) {
			jQuery('.ct_mod_resource_selector .col.right li').each(function() {
				jQuery(this).hover(function() {
					jQuery(this).addClass('over');
				}, function() {
					jQuery(this).removeClass('over');
				});
			});
			jQuery('.ct_mod_resource_selector .col.right li a').each(function() {
				var item = jQuery(this).parent().get(0);
				jQuery(this).click(function() {
					CTResourceSelector.remove(item);
				});
			});
		}
		
		var val = [];
		jQuery('.ct_mod_resource_selector .col.right li').each(function() {
			var data = jQuery(this).data();
			val.push(data.id);
		});
		jQuery('.ct_mod_resource_selector input').val(val.join(','));
		
	}
};

var CTResourceAttachments = {
	post: null,
	list: null,
	init: function(options) {
		this.post = options.post;
		var me = this;
		if (options.items != undefined) {
			this.list = jQuery('.attachment_listing');			
			for(var i=0; i<options.items.length; i++) {
				this.list.append(this.generateItem(options.items[i]));				
			}
			
			this.updateBehaviours();
			
			jQuery('.ct_res_attachments .header_bar .button').click(function(){
				me.editMedia(this);
			});
			
		}
	},
	
	editMedia: function(ob) {
	
		var id = jQuery(ob).attr('id');
		var me = this;
		
		openMediaBrowser(id, {title:ct.es.attachments.edit_label, 
										 button:ct.es.attachments.select, 
										 multiple:true},
					 	function(attachments) { me.setAttachments(attachments); });
		
	},
	
	setAttachments: function(attachments) {
				
		var me = this;
		this.list.empty();
		var ids = [];
		for(var i=0; i<attachments.length; i++) {
 			var a = attachments[i];
 			var ob = {id:a.id, label:a.name, mime:a.mime};
 			me.list.append(me.generateItem(ob));
 			ids.push(a.id);
 		} 
 		
 		jQuery.getJSON(siteurl+'/api/ct.res.setAttachments', 
						{format:'json', p:this.post, attachments:ids}, 
						function(result) {
							console.log(result);
						});
		
		this.updateBehaviours();
		
	},
	
	updateBehaviours: function() {
	
		this.list.find('li').each(function(index) {
			jQuery(this).hover(function() {
				jQuery(this).addClass('over');
			}, function() {
				jQuery(this).removeClass('over');
			});
		});
		
		this.list.find('li:even').each(function() {
			jQuery(this).addClass('alt');
		});
		
		this.list.find('li a').each(function() {
			var item = jQuery(this).parent().get(0);
			jQuery(this).click(function() {
				CTResourceAttachments.remove(item);
			});
		});
	
	},
	
	generateItem: function(item) {
		var li = jQuery('<li />');
		li.data(item);
		li.append(jQuery('<a class="button">'+ct.es.selector.remove+'</a>'));
		li.append(jQuery('<p class="label">'+item.label+'</p>'));
		li.append(jQuery('<p class="mime">'+item.mime+'</p>'));
		return li;
	},
	
	remove: function(item) {
		var data = jQuery(item).data();
		
		jQuery.getJSON(siteurl+'/api/ct.res.removeAttachment', 
						{format:'json', p:data.id});
						
		jQuery(item).remove();
		
		this.list.find('li').each(function() {
			jQuery(this).removeClass('alt');
		});
		this.list.find('li:even').each(function() {
			jQuery(this).addClass('alt');
		});
		
		
	}
};

/** MEDIA BROWSER *****************************************************/

var file_frame;
		 
function openMediaBrowser(id, options, callback) {

	jQuery('#'+id).live('click', function( event ){
		
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: options.title || jQuery( this ).data( 'uploader_title' ),
		  button: {
		    text: options.button || jQuery( this ).data( 'uploader_button_text' ),
		  },
		  multiple: options.multiple || false  // Set to true to allow multiple files to be selected
		});
		
		
	
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		
		  if (options.multiple) {
		 
		 	var selection = file_frame.state().get('selection');
		 	
		 	attachment = [];
 
			selection.map( function( a ) {
			      attachment.push(a.toJSON());
			});
		 
		 
		  } else {
		  	// We set multiple to false so only get one image from the uploader
		  	attachment = file_frame.state().get('selection').first().toJSON();
		  }
		
		  callback(attachment);

		});
		
		// Finally, open the modal
		file_frame.open();
		
	});

}

/** COURSE LISTING *************************************************/
		
var CTCourseListing = {
	post: null,
	list: null,
	select: null,
	input: null,
	init: function(options) {
		this.post = options.post;
		this.input = jQuery('.course_form input');
		this.select = jQuery('.course_form select');
		this.list = jQuery('.course_listing');	
		var me = this;
		if (options.items != undefined) {
			for(var i=0; i<options.items.length; i++) {
				this.list.append(this.generateItem(options.items[i]));				
			}
			this.updateBehaviours();
		}
		
		jQuery('.course_form .button').click(function(){
			me.addCourse();
		});
	},
	
	addCourse: function() {
		
		var op = this.select.find('option:selected');
		var id = op.val();
		if (id > 0) {
		
			var data = this.input.val().split(',');
			if (data.indexOf(id) == -1) {
				var item = {id:op.val(), label:op.html()};
				this.list.append(this.generateItem(item));
				this.updateBehaviours();
			}
			
		}

	},
	
	updateBehaviours: function() {
	
		var data = [];
		this.list.find('li').each(function(index) {
			jQuery(this).removeClass('alt');
			jQuery(this).hover(function() {
				jQuery(this).addClass('over');
			}, function() {
				jQuery(this).removeClass('over');
			});
			data.push(jQuery(this).data('id'));
		});
		this.input.val(data.join(','));
		
		this.list.find('li:even').each(function() {
			jQuery(this).addClass('alt');
		});
		
		this.list.find('li a').each(function() {
			var item = jQuery(this).parent().get(0);
			jQuery(this).click(function() {
				CTCourseListing.remove(item);
			});
		});
	
	},
	
	generateItem: function(item) {
		var li = jQuery('<li />');
		li.data(item);
		li.append(jQuery('<a class="button">'+ct.es.selector.remove+'</a>'));
		li.append(jQuery('<p class="label">'+item.label+'</p>'));
		return li;
	},
	
	remove: function(item) {
		jQuery(item).remove();
		this.updateBehaviours();		
	}
};

/** DATA SELECTOR ****************************************************/

(function(){

    var matcher = /\s*(?:((?:(?:\\\.|[^.,])+\.?)+)\s*([!~><=]=|[><])\s*("|')?((?:\\\3|.)*?)\3|(.+?))\s*(?:,|$)/g;

    function resolve(element, data) {

        data = data.match(/(?:\\\.|[^.])+(?=\.|$)/g);

        var cur = jQuery.data(element)[data.shift()];

        while (cur && data[0]) {
            cur = cur[data.shift()];
        }

        return cur || undefined;

    }

    jQuery.expr[':'].data = function(el, i, match) {
    

        matcher.lastIndex = 0;

        var expr = match[3],
            m,
            check, val,
            allMatch = null,
            foundMatch = false;

        while (m = matcher.exec(expr)) {

            check = m[4];
            val = resolve(el, m[1] || m[5]);

            switch (m[2]) {
                case '==': foundMatch = val == check; break;
                case '!=': foundMatch = val != check; break;
                case '<=': foundMatch = val <= check; break;
                case '>=': foundMatch = val >= check; break;
                case '~=': foundMatch = RegExp(check).test(val); break;
                case '>': foundMatch = val > check; break;
                case '<': foundMatch = val < check; break;
                default: if (m[5]) foundMatch = !!val;
            }

            allMatch = allMatch === null ? foundMatch : allMatch && foundMatch;

        }

        return allMatch;

    };

}());