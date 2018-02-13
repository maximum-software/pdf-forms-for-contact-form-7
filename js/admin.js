jQuery(document).ready(function($) {
	
	var post_id = jQuery('.wpcf7-pdf-forms-admin input[name=post_id]').val();
	
	var clearMessages = function() {
		jQuery('.wpcf7-pdf-forms-admin .messages').empty();
	};
	
	var errorMessage = function(msg) {
		if(!msg)
			msg = wpcf7_pdf_forms.__Unknown_error;
		jQuery('.wpcf7-pdf-forms-admin .messages').append(
			jQuery('<div class="error"/>').text(msg)
		);
		location.href = '#wpcf7-pdf-form-messages';
	};
	
	var warningMessage = function(msg) {
		jQuery('.wpcf7-pdf-forms-admin .messages').append(
			jQuery('<div class="warning"/>').text(msg)
		);
		location.href = '#wpcf7-pdf-form-messages';
	};
	
	var successMessage = function(msg) {
		jQuery('.wpcf7-pdf-forms-admin .messages').append(
			jQuery('<div class="updated"/>').text(msg)
		);
		location.href = '#wpcf7-pdf-form-messages';
	};
	
	var spinners = 0;
	var showSpinner = function() {
		spinners++;
		if(spinners==1)
			jQuery('.wpcf7-pdf-forms-spinner-overlay-box')
				.addClass('wpcf7-pdf-forms-spinner-overlay')
				.append('<div class="wpcf7-pdf-forms-spinner-box"><div class="wpcf7-pdf-forms-spinner"></div></div>')
			;
	}
	
	var hideSpinner = function() {
		if(spinners > 0)
			spinners--;
		if(spinners==0)
			jQuery('.wpcf7-pdf-forms-spinner-overlay-box')
				.empty()
				.removeClass('wpcf7-pdf-forms-spinner-overlay')
			;
	}
	
	var getTags = function(attachments, all) {
		
		if(!all) all = false;
		
		clearMessages();
		
		var textarea = jQuery('.wpcf7-pdf-forms-admin .tags-textarea');
		
		textarea.val('');
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_tags', 'attachments': attachments, 'all': all, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.tags)
				{
					textarea.val(data.tags);
					location.href = '#wpcf7-pdf-form-tags-textarea';
				}
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var pdfFieldsCache = [];
	var reloadPdfFieldsCounter = 0;
	var reloadPdfFieldsXhr;
	var reloadPdfFields = function() {
		
		var attachments = getAttachments();
		var attachmentList = [];
		
		for(var i=0, l=attachments.length; i<l; i++)
			attachmentList.push(attachments[i].attachment_id);
		
		var reloadIndex = ++reloadPdfFieldsCounter;
		
		if(reloadPdfFieldsXhr)
			reloadPdfFieldsXhr.abort();
		
		reloadPdfFieldsXhr = jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_pdf_fields', 'attachments': attachmentList, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				reloadPdfFieldsXhr = null;
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.fields)
				{
					if(reloadIndex!=reloadPdfFieldsCounter)
						return;
					
					pdfFieldsCache = data.fields;
					
					preloadMappings();
					refreshPdfFields();
				}
			},
			
			error: function(jqXHR, textStatus, errorThrown) { if(!jqXHR.getAllResponseHeaders()) return; return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	var getPdfFieldData = function(id) {
		
		for(var i=0, l=pdfFieldsCache.length; i<l; i++)
			if(pdfFieldsCache[i].id == id)
				return pdfFieldsCache[i];
		
		return null;
	};
	
	var getUnmappedPdfFields = function() {
		
		var pdf_fields = [];
		var mappings = getMappings();
		
		jQuery.each(pdfFieldsCache, function(f, field) {
			
			for(var i=0, l=mappings.length; i<l; i++)
			{
				mapped_id = String(mappings[i].pdf_field);
				field_id = String(field.id);
				mapped_id = mapped_id.substr(mapped_id.indexOf('-')+1);
				field_id = field_id.substr(field_id.indexOf('-')+1);
				if(mapped_id == field_id)
					return;
			}
			
			pdf_fields.push(field);
		});
		
		return pdf_fields;
	};
	
	var refreshPdfFields = function() {
		
		var pdf_fields = jQuery('.wpcf7-pdf-forms-admin .pdf-field-list');
		pdf_fields.empty();
		
		jQuery.each(getUnmappedPdfFields(), function(f, field) {
			
			pdf_fields.append(jQuery('<option>', { 
				value: field.id,
				text : field.caption
			}));
		});
		
		updateTagHint();
	};
	
	var cf7FieldsCache = [];
	var loadCf7Fields = function(callback) {
		
		if(!callback) callback = null;
		
		var cf7_fields = jQuery('.wpcf7-pdf-forms-admin .cf7-field-list');
		
		var form = jQuery('textarea#wpcf7-form');
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: { 'action': 'wpcf7_pdf_forms_query_cf7_fields', 'wpcf7-form': form.val(), 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.fields)
				{
					cf7FieldsCache = data.fields;
					
					cf7_fields.empty();
					
					jQuery.each(data.fields, function(i, field) {
						
						cf7_fields.append(jQuery('<option>', {
							value: field.id,
							text : field.caption 
						}));
					});
					
					refreshMappings();
					
					if(callback)
						callback();
				}
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	var getCf7FieldData = function(id) {
		
		for(var i=0, l=cf7FieldsCache.length; i<l; i++)
			if(cf7FieldsCache[i].id == id)
				return cf7FieldsCache[i]
		
		return null;
	};
	
	var getData = function(field) {
		var data = jQuery('textarea#wpcf7-form').closest('form').find('input[name=wpcf7-pdf-forms-data]').val();
		if(data)
			data = JSON.parse(data);
		else
			data = {};
		return data[field];
	};
	
	var setData = function(field, value) {
		jQuery('textarea#wpcf7-form').each(function() {
			var form = jQuery('textarea#wpcf7-form').closest('form');
			if(form[0])
			{
				var input = form.find('input[name=wpcf7-pdf-forms-data]');
				if(!input[0])
				{
					input = jQuery("<input type='hidden' name='wpcf7-pdf-forms-data'/>");
					jQuery(form).append(input);
				}
				var data = input.val();
				if(data)
					data = JSON.parse(data);
				else
					data = {};
				data[field] = value;
				input.val(JSON.stringify(data));
			}
		});
	};
	
	var getAttachments = function() {
		var attachments = getData('attachments');
		if(attachments)
			return attachments;
		else
			return [];
	};
	
	var setAttachments = function(attachments) {
		setData('attachments', attachments);
		reloadPdfFields();
	};
	
	var deleteAttachment = function(attachment_id) {
		
		var attachments = getAttachments();
		
		for(var i=0, l=attachments.length; i<l; i++)
			if(attachments[i].attachment_id == attachment_id)
			{
				attachments.splice(i, 1);
				break;
			}
		
		setAttachments(attachments);
	};
	
	var setAttachmentOption = function(attachment_id, option, value) {
		
		var attachments = getAttachments();
		
		for(var i=0, l=attachments.length; i<l; i++)
			if(attachments[i].attachment_id == attachment_id)
			{
				if(typeof attachments[i].options == 'undefined'
				|| attachments[i].options == null)
					attachments[i].options = {};
				attachments[i].options[option] = value;
				break;
			}
		
		setAttachments(attachments);
	};
	
	var addAttachment = function(attachment_id, filename, options) {
		
		var attachments = getAttachments();
		attachments.push( { 'attachment_id': attachment_id, 'filename': filename, 'options': options } );
		setAttachments(attachments);
		
		jQuery('.wpcf7-pdf-forms-admin .instructions').remove();
		
		var template = jQuery('.wpcf7-pdf-forms-admin .pdf-attachment-row-template');
		var tag = template.clone().removeClass('pdf-attachment-row-template').addClass('pdf-attachment-row');
		
		tag.find('.filename').text('['+attachment_id+'] '+filename);
		
		if(typeof options != 'undefined' && options !== null)
		{
			tag.find('.pdf-options input[type=checkbox]').each(function(){
					var option = jQuery(this).data('option');
					jQuery(this)[0].checked = options[option];
				});
		}
		tag.find('.pdf-options input').data('attachment_id', attachment_id);
		tag.find('.pdf-options input[type=checkbox]').change(function() {
				var attachment_id = jQuery(this).data('attachment_id');
				var option = jQuery(this).data('option');
				setAttachmentOption(attachment_id, option, jQuery(this)[0].checked);
			});
		tag.find('.pdf-options-button').click(function() {
				jQuery(this).closest('.pdf-attachment-row').find('.pdf-options').toggle('.pdf-options-hidden');
			});
		
		var tags_button = tag.find('.get-tags-button');
		tags_button.data('attachment_id', attachment_id);
		tags_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			getTags([jQuery(this).data('attachment_id')]);
			
		});
		
		var delete_button = tag.find('.delete-button');
		delete_button.data('attachment_id', attachment_id);
		delete_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Attachment))
				return;
			
			deleteAttachment(jQuery(this).data('attachment_id'));
			
			tag.remove();
		});
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-attachments').append(tag);
	};
	
	var preloadAttachments = function() {
		
		if(!post_id)
			return;
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_attachments', 'post_id': post_id, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.attachments)
					jQuery.each(data.attachments, function(index, data) { addAttachment(data.attachment_id, data.filename, data.options); });
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var getMappings = function() {
		var mappings = getData('mappings');
		if(mappings)
			return mappings;
		else
			return [];
	};
	
	var setMappings = function(mappings) {
		setData('mappings', mappings);
		refreshPdfFields();
	};
	
	var deleteMapping = function(cf7_field, pdf_field) {
		
		var mappings = getMappings();
		
		for(var i=0, l=mappings.length; i<l; i++)
			if(mappings[i].cf7_field == cf7_field
			&& mappings[i].pdf_field == pdf_field)
			{
				mappings.splice(i, 1);
				break;
			}
		
		setMappings(mappings);
	};
	
	var addMapping = function(cf7_field, pdf_field) {
		
		var mappings = getMappings();
		mappings.push( { 'cf7_field': cf7_field, 'pdf_field': pdf_field } );
		setMappings(mappings);
		
		addMappingEntry(cf7_field, pdf_field);
	};
	
	var addMappingEntry = function(cf7_field, pdf_field) {
		
		var cf7_field_data = getCf7FieldData(cf7_field);
		if(!cf7_field_data)
			return;
		
		var pdf_field_data = getPdfFieldData(pdf_field);
		if(!pdf_field_data)
			return;
		
		var template = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row-template');
		var tag = template.clone().removeClass('pdf-mapping-row-template').addClass('pdf-mapping-row');
		
		tag.find('.cf7-field-name').text(cf7_field_data.caption);
		tag.find('.pdf-field-name').text(pdf_field_data.caption);
		
		var delete_button = tag.find('.delete-mapping-button');
		
		var virtual = cf7_field_data.pdf_field == pdf_field;
		
		if(virtual)
			delete_button.remove();
		else
		{
			delete_button.data('cf7_field', cf7_field);
			delete_button.data('pdf_field', pdf_field);
			delete_button.click(function(event) {
				
				// prevent running default button click handlers
				event.stopPropagation();
				event.preventDefault();
				
				if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Mapping))
					return;
				
				deleteMapping(jQuery(this).data('cf7_field'), jQuery(this).data('pdf_field'));
				
				tag.remove();
			});
		}
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper').append(tag);
	};
	
	var loadedMappings = false;
	var preloadMappings = function() {
		
		if(!post_id)
			return;
		
		if(loadedMappings)
			return;
		
		loadedMappings = true;
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_mappings', 'post_id': post_id, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.mappings)
					jQuery.each(data.mappings, function(index, data) { addMapping(data.cf7_field, data.pdf_field); });
				
				refreshMappings();
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var refreshMappings = function() {
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row').remove();
		
		reloadDefaultMappings();
		
		var mappings = getMappings();
		for(var i=0, l=mappings.length; i<l; i++)
			addMappingEntry(mappings[i].cf7_field, mappings[i].pdf_field);
	};
	
	var reloadDefaultMappings = function() {
		
		jQuery.each(cf7FieldsCache, function(i, field) {
			if(field.pdf_field)
			{
				var mappings = getMappings();
				
				for(var i=0; i<mappings.length; i++)
					if(mappings[i].pdf_field == field.pdf_field)
					{
						mappings.splice(i, 1);
						i--;
					}
				
				mappings.push( { 'cf7_field': field.id, 'pdf_field': field.pdf_field } );
				
				setMappings(mappings);
			}
		});
	};
	
	var removeOldMappings = function() {
		
		var mappings = getMappings();
		
		for(var i=0; i<mappings.length; i++)
		{
			var cf7_field_data = getCf7FieldData(mappings[i].cf7_field);
			if(!cf7_field_data)
			{
				mappings.splice(i, 1);
				i--;
			}
		}
		
		setMappings(mappings);
	};
	
	var updateTagHint = function() {
		
		var tag = jQuery('.wpcf7-pdf-forms-admin .tag-hint');
		tag.text('');
		tag.data('pdf_field', '');
		tag.data('cf7_field', '');
		
		var pdf_field = jQuery('.wpcf7-pdf-forms-admin .pdf-field-list').val();
		if(!pdf_field)
			return;
		
		var pdf_field_data = getPdfFieldData(pdf_field);
		if(!pdf_field_data)
			return;
		
		tag.text(pdf_field_data.tag_hint);
		tag.data('cf7_field', pdf_field_data.tag_name);
		tag.data('pdf_field', pdf_field_data.id);
	};
	
	jQuery('.wpcf7-pdf-forms-admin .pdf-field-list').change(updateTagHint);
	
	// set up global 'Get Tags' button handler
	jQuery('.wpcf7-pdf-forms-admin .get-tags-all-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var attachments = getAttachments();
		if(attachments.length == 0)
			return;
		
		var attachmentList = [];
		
		for(var i=0, l=attachments.length; i<l; i++)
			attachmentList.push(attachments[i].attachment_id);
		
		getTags(attachmentList, true);
	});
	
	var getWpcf7obj = function() {
		var wpcf7obj;
		if(typeof _wpcf7 != 'undefined' && _wpcf7 !== null)
			wpcf7obj = _wpcf7;
		if(typeof wpcf7 != 'undefined' && wpcf7 !== null)
			wpcf7obj = wpcf7;
		if(!wpcf7obj)
			alert(wpcf7_pdf_forms.__No_WPCF7);
		return wpcf7obj;
	};
	
	// set up 'Insert Tags' button handler
	jQuery('.wpcf7-pdf-forms-admin .insert-tags-btn').click(function(event) {
		var tags = jQuery('.wpcf7-pdf-forms-admin .tags-textarea').val();
		var wpcf7obj = getWpcf7obj();
		if(wpcf7obj)
		{
			wpcf7obj.taggen.insert(tags);
			tb_remove();
		}
		return false;
	});
	
	// set up 'Insert Tag' button handler
	jQuery('.wpcf7-pdf-forms-admin .insert-tag-hint-btn').click(function(event) {
		var tag = jQuery('.wpcf7-pdf-forms-admin .tag-hint');
		var wpcf7obj = getWpcf7obj();
		var tagText = tag.text();
		if(wpcf7obj && tagText)
		{
			wpcf7obj.taggen.insert(tagText);
			loadCf7Fields();
			addMapping(tag.data('cf7_field'), tag.data('pdf_field'));
			tb_remove();
		}
		return false;
	});
	
	// set up 'Insert & Link All' button handler
	jQuery('.wpcf7-pdf-forms-admin .insert-and-map-all-tags-btn').click(function(event) {
		var wpcf7obj = getWpcf7obj();
		var tagText = "";
		
		var pdf_fields = getUnmappedPdfFields();
		
		if(wpcf7obj)
		{
			jQuery.each(pdf_fields, function(f, field) {
				if(field.attachment_id == 'all')
					tagText +=
						'<label>' + $("<div>").text(field.name).html() + '</label>\n' +
						'    ' + field.tag_hint + '\n\n';
			});
			
			if(tagText)
			{
				wpcf7obj.taggen.insert(tagText);
				loadCf7Fields();
				
				jQuery.each(pdf_fields, function(f, field) {
					if(field.attachment_id == 'all')
						addMapping(field.tag_name, field.id);
				});
				
				tb_remove();
			}
		}
		
		return false;
	});
	
	// set up 'Upload PDF' button handler
	jQuery('.wpcf7-pdf-forms-admin .upload-btn').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		// prepare request
		
		var file = jQuery(document).find('.wpcf7-pdf-forms-admin input.file');
		
		if( ! file[0].files[0])
			return errorMessage(wpcf7_pdf_forms.__File_not_specified);
		
		var data = new FormData();
		data.append("post_id", post_id);
		data.append("file", file[0].files[0]);
		data.append("action", 'wpcf7_pdf_forms_upload');
		data.append("nonce", wpcf7_pdf_forms.ajax_nonce);
		
		// submit request
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // this is needed for file upload to work properly
			contentType: false, // this is needed for file upload to work properly
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.attachment_id)
					addAttachment(data.attachment_id, data.filename, data.options);
				
				file.val("");
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner(); },
			complete: function() { hideSpinner(); }
			
		});
	});
	
	
	// set up 'Add Mapping' button handler
	jQuery('.wpcf7-pdf-forms-admin .add-mapping-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var cf7_field = jQuery('.wpcf7-pdf-forms-admin .cf7-field-list').val();
		var pdf_field = jQuery('.wpcf7-pdf-forms-admin .pdf-field-list').val();
		
		if(cf7_field && pdf_field)
			addMapping(cf7_field, pdf_field);
	});
	
	
	var form = jQuery('textarea#wpcf7-form');
	form.change(function() {
		loadCf7Fields(removeOldMappings);
	});
	
	loadCf7Fields(preloadAttachments);
});
