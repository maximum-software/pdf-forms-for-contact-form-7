window.addEventListener('load', function(event)
{
	document.addEventListener('wpcf7submit', function(event)
	{
		if(typeof event.detail !== 'object'
		|| typeof event.detail.unitTag === 'undefined'
		|| typeof event.detail.apiResponse !== 'object'
		|| typeof event.detail.apiResponse.wpcf7_pdf_forms_data !== 'object'
		|| event.detail.apiResponse.wpcf7_pdf_forms_data === null)
			return;
		
		var unitTag = event.detail.unitTag;
		var formDiv = document.getElementById(unitTag);
		
		if(!formDiv)
			return;
		
		// delete previous response
		var prevResponse = formDiv.querySelector('.wpcf7-pdf-forms-response-output');
		if(prevResponse)
			prevResponse.parentNode.removeChild(prevResponse);
		
		if(typeof event.detail.apiResponse.wpcf7_pdf_forms_data.downloads === 'object')
		{
			var data = event.detail.apiResponse.wpcf7_pdf_forms_data.downloads;
			if(data.length > 0)
			{
				var response = document.createElement('div');
				response.className = 'wpcf7-pdf-forms-response-output';
				
				for(var i=0; i<data.length; i++)
				{
					var download = document.createElement('div');
					download.innerHTML = "<span class='dashicons dashicons-download'></span><a href='' download></a> <span class='file-size'></span>";
					
					var link = download.querySelector('a');
					link.href = data[i]['url'];
					link.innerText = data[i]['filename'];
					download.querySelector('.file-size').innerText = "(" + data[i]['size'] + ")";
					
					response.appendChild(download);
				}
				
				var form = formDiv.querySelector('.wpcf7-form');
				if(form)
				{
					form.appendChild(response);
					
					// add response removal event listener to all submit buttons if not already added
					var submitButtons = form.querySelectorAll('input[type="submit"], button[type="submit"]');
					for(var i=0; i<submitButtons.length; i++)
					{
						if(submitButtons[i].getAttribute('data-wpcf7-pdf-forms-event-listener') !== 'true')
						{
							submitButtons[i].addEventListener("click", function()
							{
								// delete previous response
								var prevResponse = this.form.querySelector('.wpcf7-pdf-forms-response-output');
								if(prevResponse)
									prevResponse.parentNode.removeChild(prevResponse);
							});
							
							submitButtons[i].setAttribute('data-wpcf7-pdf-forms-event-listener', 'true');
						}
					}
				}
			}
		}
		
	}, false);
});
