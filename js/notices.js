jQuery(document).ready(function($) {
	
	var cookies = [];
	try { cookies = decodeURIComponent(document.cookie).split('; '); }
	catch(e) { } // ignore cookie corruption related errors
	
	jQuery('.wpcf7-pdf-forms-notice').each(function() {
		
		var notice_id = jQuery(this).data('notice-id');
		
		if(typeof notice_id === "undefined")
			return;
		
		var hidden = false;
		jQuery.each(cookies, function(key, value) {
			var kv = value.trim().split('=');
			if((kv[0] == "wpcf7-pdf-forms-notice-"+notice_id) && (kv[1] == "hidden"))
			{
				hidden = true;
				return false;
			}
		});
		
		if(hidden)
			jQuery(this).hide();
	});
	
	jQuery('.wpcf7-pdf-forms-notice').on("click", ".notice-dismiss", function(event) {
		
		var notice_id = jQuery(this).closest('.wpcf7-pdf-forms-notice').data('notice-id');
		if(typeof notice_id == 'string')
		{
			var date = new Date();
			date.setDate(date.getDate() + 10);
			document.cookie = "wpcf7-pdf-forms-notice-"+notice_id+"=hidden; expires="+date.toUTCString()+"; path=/; domain="+window.location.hostname+"; SameSite=Lax";
		}
	});
	
});
