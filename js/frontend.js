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
	if(typeof event.detail !== 'object'
	|| typeof event.detail.apiResponse !== 'object'
	|| typeof event.detail.apiResponse.wpcf7_pdf_forms_data !== 'object'
	|| typeof event.detail.pluginVersion !== 'string'
	|| event.detail.apiResponse.wpcf7_pdf_forms_data === null
	|| compareVersions( '5.2', event.detail.pluginVersion ) > 0 )
		return;
	
	var data = event.detail.apiResponse.wpcf7_pdf_forms_data;
	var download = '';
	
	for(var i=0; i<data.length; i++)
		download += "<span class='dashicons dashicons-download'></span><a href='"+data[i]['url']+"' download>"+data[i]['filename']+"</a> ("+data[i]['size']+")<br>";
	
	var cf7form = document.querySelector('.wpcf7-form');
	var div = document.createElement('div');
	div.innerHTML = download;
	div.className = 'wpcf7-pdf-response-output';
	cf7form.appendChild(div);
	
}, false );

// https://stackoverflow.com/questions/6832596/how-to-compare-software-version-number-using-js-only-number
function compareVersions(min, current)
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
}
