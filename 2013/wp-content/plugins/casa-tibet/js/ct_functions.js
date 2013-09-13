var siteurl = '';
function ct_siteurl(url) {
	siteurl = url;
}

function ct_mod_verifyPasscode(id, module) {
	var val = jQuery('#'+id).val();
	jQuery.getJSON(siteurl+'/api/ct.mod.requestAccess', 
		{format:'json', m:module, p:val}, 
		function(result) {
			if (result.status) {
				var codes = jQuery.cookie('ct_mod_access');
				codes = (jQuery.type(codes)=='string') ? codes.split(',') : [];
				if (codes.indexOf(String(result.id)) == -1) codes.push(String(result.id));
				jQuery.cookie('ct_mod_access', codes.join(','), {path:'/', expires: 365});
				window.location.reload();
			} else {
				jQuery('#'+id).val('');
			}
		});			
}
	