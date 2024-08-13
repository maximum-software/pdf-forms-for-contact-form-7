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
			if(Array.isArray(data) && data.length > 0)
			{
				var show_download_links = false;
				var response = document.createElement('div');
				
				for(var i=0; i<data.length; i++)
				{
					var item = data[i];
					
					if(typeof item.options !== 'object')
						continue;
					
					if(item.options['download_link'])
					{
						var download = document.createElement('div');
						download.innerHTML = "<span class='dashicons dashicons-download'></span><a href='' download></a> <span class='file-size'></span>";
						
						var link = download.querySelector('a');
						link.href = item['url'];
						link.innerText = item['filename'];
						link.download = item['filename'];
						download.querySelector('.file-size').innerText = "(" + item['size'] + ")";
						
						response.appendChild(download);
						show_download_links = true;
					}
					
					if(item.options['download_link_auto'])
					{
						var downloadLink = document.createElement('a');
						downloadLink.href = item['url'];
						downloadLink.download = item['filename'];
						document.body.appendChild(downloadLink);
						downloadLink.click();
						document.body.removeChild(downloadLink);
					}
				}
				
				if(show_download_links)
				{
					var form = formDiv.querySelector('.wpcf7-form');
					if(form)
					{
						response.className = 'wpcf7-pdf-forms-response-output';
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
		}
		
	}, false);
});
