document.cookie = 'wpcf7_pdf_forms_js=on';

// Polyfill For IE 10+
(function(){
	if ( typeof window.CustomEvent === "function" ) return false;
	function CustomEvent ( event, params ) {
		params = params || { bubbles: false, cancelable: false, detail: null };
		var evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}
	window.CustomEvent = CustomEvent;
})();

document.addEventListener( 'wpcf7mailsent', function( event )
{
	// https://stackoverflow.com/questions/6832596/how-to-compare-software-version-number-using-js-only-number
	var compareVersions = function(min, current)
	{
		var reg = "/(\.0+)+$/";
		var partMin = min.replace(reg, '').split('.');
		var partCur = current.replace(reg, '').split('.');
		
		for(var i = 0; i < Math.min(partMin.length, partCur.length); i++) {
			var diff = parseInt(partMin[i], 10) - parseInt(partCur[i], 10);
			if(diff)
				return diff;
		}
		
		return partMin.length - partCur.length;
	};
	
	if(typeof event.detail !== 'object'
	|| typeof event.detail.apiResponse !== 'object'
	|| typeof event.detail.apiResponse.wpcf7_pdf_forms_data !== 'object'
	|| typeof event.detail.pluginVersion !== 'string'
	|| event.detail.apiResponse.wpcf7_pdf_forms_data === null
	|| compareVersions( '5.2', event.detail.pluginVersion ) > 0 )
		return;
	
	var data = event.detail.apiResponse.wpcf7_pdf_forms_data;
	var downloads = document.createElement('div');
	for(var i=0; i<data.length; i++)
	{
		var download = document.createElement('div');
		download.innerHTML = "<span class='dashicons dashicons-download'></span><a href='' download></a> (<span class='file-size'></span>)";
		
		var link = download.querySelector('a');
		link.href = data[i]['url'];
		link.innerText = data[i]['filename'];
		download.querySelector('.file-size').innerText = data[i]['size'];
		
		downloads.appendChild(download);
	}
	
	var cf7form = document.querySelector('.wpcf7-form');
	downloads.className = 'wpcf7-pdf-response-output';
	cf7form.appendChild(downloads);
	
}, false );
