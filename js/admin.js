jQuery(document).ready(function($) {
	
	var wpcf7_form = jQuery('textarea#wpcf7-form');
	if(!wpcf7_form)
		return;
	
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
	
	var strtr = function(str, replacements)
	{
		for(i in replacements)
			if(replacements.hasOwnProperty(i))
				str = str.replace(i, replacements[i]);
		return str;
	}
	
	// https://github.com/uxitten/polyfill/blob/master/string.polyfill.js
	// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/padEnd
	if (!String.prototype.padEnd) {
		String.prototype.padEnd = function padEnd(targetLength,padString) {
			targetLength = targetLength>>0; //floor if number or convert non-number to 0;
			padString = String((typeof padString !== 'undefined' ? padString : ' '));
			if (this.length > targetLength) {
				return String(this);
			}
			else {
				targetLength = targetLength-this.length;
				if (targetLength > padString.length) {
					padString += padString.repeat(targetLength/padString.length); //append to original to ensure we are longer than needed
				}
				return String(this) + padString.slice(0,targetLength);
			}
		};
	}
	
	var base64urldecode = function(data)
	{
		return window.atob(strtr(data, {'.': '+', '_': '/'}).padEnd(data.length % 4, '='));
	}
	
	var getTags = function(attachments, all) {
		
		if(!all) all = false;
		
		clearMessages();
		
		var textarea = jQuery('.wpcf7-pdf-forms-admin .tags-textarea');
		
		textarea.val('');
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: { 'action': 'wpcf7_pdf_forms_query_tags', 'attachments': attachments, 'all': all, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.hasOwnProperty('tags'))
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
	
	var pdfFields = [];
	var reloadPdfFields = function() {
		
		var pdfFieldsA = [];
		var pdfFieldsB = [];
		
		var attachments = getAttachments();
		jQuery.each(attachments, function(a, attachment) {
			
			var info = getAttachmentInfo(attachment.attachment_id);
			if(!info || !info.fields)
				return;
			
			jQuery.each(info.fields, function(f, field) {
				pdfFieldsA.push({
					'id': 'all-' + field.id,
					'name': field.name,
					'caption': field.name,
					'attachment_id': 'all',
					'tag_hint': field.tag_hint,
					'tag_name': field.tag_name,
				});
				pdfFieldsB.push({
					'id': attachment.attachment_id + '-' + field.id,
					'name': field.name,
					'caption': '[' + attachment.attachment_id + '] ' + field.name,
					'attachment_id': attachment.attachment_id,
					'tag_hint': field.tag_hint,
					'tag_name': field.tag_name,
				});
			});
		});
		
		var ids = [];
		pdfFields = [];
		
		jQuery.each(pdfFieldsA.concat(pdfFieldsB), function(f, field) {
			if(ids.indexOf(field.id) == -1)
			{
				ids.push(field.id);
				pdfFields.push(field);
			}
		});
		
		refreshPdfFields();
	};
	var getPdfFieldData = function(id) {
		
		for(var i=0, l=pdfFields.length; i<l; i++)
			if(pdfFields[i].id == id)
				return pdfFields[i];
		
		return null;
	};
	
	var getUnmappedPdfFields = function() {
		
		var pdf_fields = [];
		var mappings = getMappings();
		
		jQuery.each(pdfFields, function(f, field) {
			
			var field_pdf_field = String(field.id);
			var field_attachment_id = field_pdf_field.substr(0, field_pdf_field.indexOf('-'));
			var field_pdf_field_name = field_pdf_field.substr(field_pdf_field.indexOf('-')+1);
			
			for(var i=0, l=mappings.length; i<l; i++)
			{
				var mapping_pdf_field = String(mappings[i].pdf_field);
				var mapping_attachment_id = mapping_pdf_field.substr(0, mapping_pdf_field.indexOf('-'));
				var mapping_pdf_field_name = mapping_pdf_field.substr(mapping_pdf_field.indexOf('-')+1);
				
				if( (mapping_attachment_id == 'all' || field_attachment_id == 'all' || mapping_attachment_id == field_attachment_id)
					&& mapping_pdf_field_name == field_pdf_field_name)
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
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: { 'action': 'wpcf7_pdf_forms_query_cf7_fields', 'wpcf7-form': wpcf7_form.val(), 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.hasOwnProperty('fields'))
				{
					cf7FieldsCache = data.fields;
					
					refreshCf7Fields();
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
	
	var refreshCf7Fields = function() {
		
		var cf7_fields = jQuery('.wpcf7-pdf-forms-admin .cf7-field-list');
		
		cf7_fields.empty();
		
		jQuery.each(cf7FieldsCache, function(i, field) {
			
			cf7_fields.append(jQuery('<option>', {
				value: field.id,
				text : field.caption 
			}));
		});
	};
	
	var getData = function(field) {
		var data = wpcf7_form.closest('form').find('input[name=wpcf7-pdf-forms-data]').val();
		if(data)
			data = JSON.parse(data);
		else
			data = {};
		return data[field];
	};
	
	var setData = function(field, value) {
		var form = wpcf7_form.closest('form');
		if(form)
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
	};
	
	var getAttachments = function() {
		var attachments = getData('attachments');
		if(attachments)
			return attachments;
		else
			return [];
	};
	
	var getAttachment = function(attachment_id) {
		var attachments = getAttachments();
		
		for(var i=0, l=attachments.length; i<l; i++)
			if(attachments[i].attachment_id == attachment_id)
				return attachments[i];
		
		return null;
	};
	
	var setAttachments = function(attachments) {
		setData('attachments', attachments);
		reloadPdfFields();
	};
	
	var deleteAttachment = function(attachment_id) {
		
		var mappings = getMappings();
		jQuery.each(mappings, function(index, mapping) {
			var pdf_field_data = getPdfFieldData(mapping.pdf_field);
			if(!pdf_field_data || pdf_field_data.attachment_id == attachment_id)
				deleteMapping(mapping.cf7_field, mapping.pdf_field);
		});
		
		var embeds = getEmbeds();
		jQuery.each(embeds, function(index, embed) {
			if(embed.attachment_id == attachment_id)
				deleteEmbed(embed.id);
		});
		
		var attachments = getAttachments();
		
		for(var i=0, l=attachments.length; i<l; i++)
			if(attachments[i].attachment_id == attachment_id)
			{
				attachments.splice(i, 1);
				break;
			}
		
		setAttachments(attachments);
		
		deleteAttachmentInfo(attachment_id);
		
		refreshMappings();
		refreshEmbeds();
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
	
	var attachmentInfo = {};
	var getAttachmentInfo = function(attachment_id)
	{
		return attachmentInfo[attachment_id];
	}
	var setAttachmentInfo = function(attachment_id, data)
	{
		attachmentInfo[attachment_id] = data;
	}
	var deleteAttachmentInfo = function(attachment_id)
	{
		delete attachmentInfo[attachment_id];
	}
	
	var addAttachment = function(data) {
		
		var attachment_id = data.attachment_id;
		var filename = data.filename;
		var options = data.options;
		
		var attachments = getAttachments();
		attachments.push( data );
		setAttachments(attachments);
		
		jQuery('.wpcf7-pdf-forms-admin .instructions').remove();
		
		var template = jQuery('.wpcf7-pdf-forms-admin .pdf-attachment-row-template');
		var tag = template.clone().removeClass('pdf-attachment-row-template').addClass('pdf-attachment-row');
		
		tag.find('.pdf-filename').text('['+attachment_id+'] '+filename);
		
		if(typeof options != 'undefined' && options !== null)
		{
			tag.find('.pdf-options input[type=checkbox]').each(function() {
				var option = jQuery(this).data('option');
				jQuery(this)[0].checked = options[option];
			});
			tag.find('.pdf-options input[type=text]').each(function() {
				var option = jQuery(this).data('option');
				jQuery(this).val(options[option]);
			});
		}
		tag.find('.pdf-options input').data('attachment_id', attachment_id);
		tag.find('.pdf-options input[type=checkbox]').change(function() {
			var attachment_id = jQuery(this).data('attachment_id');
			var option = jQuery(this).data('option');
			setAttachmentOption(attachment_id, option, jQuery(this)[0].checked);
		});
		tag.find('.pdf-options input[type=text]').change(function() {
			var attachment_id = jQuery(this).data('attachment_id');
			var option = jQuery(this).data('option');
			setAttachmentOption(attachment_id, option, jQuery(this).val());
		});
		tag.find('.pdf-options-button').click(function() {
			jQuery(this).closest('.pdf-attachment-row').find('.pdf-options').toggle('.pdf-options-hidden');
		});
		
		var delete_button = tag.find('.delete-button');
		delete_button.data('attachment_id', attachment_id);
		delete_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Attachment))
				return;
			
			var attachment_id = jQuery(this).data('attachment_id');
			if(!attachment_id)
				return false;
			
			deleteAttachment(attachment_id);
			
			tag.remove();
			
			jQuery('.wpcf7-pdf-forms-admin .pdf-files-list option[value='+attachment_id+']').remove();
			
			return false;
		});
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-attachments tr.pdf-buttons').before(tag);
		
		var pdf_files = jQuery('.wpcf7-pdf-forms-admin .pdf-files-list');
		pdf_files.append(jQuery('<option>', {
			value: attachment_id,
			text : '[' + attachment_id + '] ' + filename
		}));
		if(attachments.length==1)
			refreshPageList();
		
		jQuery('.wpcf7-pdf-forms-admin .help-button').each(function(){
			var button = jQuery(this);
			var helpbox = button.parent().find('.helpbox');
			hideHelp(button, helpbox);
		});
	};
	
	var preloadData = function() {
		
		if(!post_id)
			return;
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: {
				'action': 'wpcf7_pdf_forms_preload_data',
				'post_id': post_id,
				'wpcf7-form': wpcf7_form.val(),
				'nonce': wpcf7_pdf_forms.ajax_nonce
			},
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.hasOwnProperty('attachments'))
					jQuery.each(data.attachments, function(index, data) {
						if(data.hasOwnProperty('info'))
						{
							setAttachmentInfo(data.attachment_id, data.info);
							delete data.info;
						}
						addAttachment(data);
					});
				
				if(data.hasOwnProperty('cf7_fields'))
				{
					cf7FieldsCache = data.cf7_fields;
					refreshCf7Fields();
				}
				
				if(data.hasOwnProperty('mappings'))
				{
					jQuery.each(data.mappings, function(index, mapping) { addMapping(mapping.cf7_field, mapping.pdf_field); });
					refreshMappings();
				}
				
				if(data.hasOwnProperty('embeds'))
				{
					jQuery.each(data.embeds, function(index, embed) { if(embed.id && embed_id_autoinc < embed.id) embed_id_autoinc = embed.id; });
					jQuery.each(data.embeds, function(index, embed) { addEmbed(embed); });
				}
				
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
	
	var deleteAllMappings = function() {
		setMappings([]);
		refreshMappings();
	};
	
	var addMapping = function(cf7_field, pdf_field) {
		
		var mappings = getMappings();
		mappings.push( { 'cf7_field': cf7_field, 'pdf_field': pdf_field } );
		setMappings(mappings);
		
		addMappingEntry(cf7_field, pdf_field);
	};
	
	var addMappingEntry = function(cf7_field, pdf_field) {
		
		var cf7_field_data = getCf7FieldData(cf7_field);
		var cf7_field_caption = cf7_field;
		if(cf7_field_data)
			cf7_field_caption = cf7_field_data.caption;
		
		var pdf_field_data = getPdfFieldData(pdf_field);
		var pdf_field_caption;
		if(pdf_field_data)
			pdf_field_caption = pdf_field_data.caption;
		else
		{
			var field_id = pdf_field.substr(pdf_field.indexOf('-')+1);
			pdf_field_caption = base64urldecode(field_id);
		}
		
		var template = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row-template');
		var tag = template.clone().removeClass('pdf-mapping-row-template').addClass('pdf-mapping-row');
		
		tag.find('.cf7-field-name').text(cf7_field_caption);
		tag.find('.pdf-field-name').text(pdf_field_caption);
		
		var delete_button = tag.find('.delete-mapping-button');
		
		var virtual = cf7_field_data && cf7_field_data.pdf_field == pdf_field;
		
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
				
				var mappings = getMappings();
				if(mappings.length==0)
					jQuery('.wpcf7-pdf-forms-admin .delete-all-row').hide();
			});
		}
		
		tag.insertBefore(jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper .delete-all-row'));
		jQuery('.wpcf7-pdf-forms-admin .delete-all-row').show();
	};
	
	var refreshMappings = function() {
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row').remove();
		
		reloadDefaultMappings();
		
		var mappings = getMappings();
		for(var i=0, l=mappings.length; i<l; i++)
			addMappingEntry(mappings[i].cf7_field, mappings[i].pdf_field);
		
		if(mappings.length==0)
			jQuery('.wpcf7-pdf-forms-admin .delete-all-row').hide();
		else
			jQuery('.wpcf7-pdf-forms-admin .delete-all-row').show();
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
		
		refreshMappings();
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
	
	var getEmbeds = function() {
		var embeds = getData('embeds');
		if(embeds)
			return embeds;
		else
			return [];
	};
	
	var setEmbeds = function(embeds) {
		setData('embeds', embeds);
	};
	
	var embed_id_autoinc = 0;
	var addEmbed = function(embed) {
		
		var cf7_field = embed.cf7_field;
		var attachment_id = embed.attachment_id;
		var page = embed.page;
		
		if(!cf7_field || !attachment_id || !page || page < 0)
			return;
		
		var attachment = getAttachment(attachment_id);
		var cf7_field_data = getCf7FieldData(cf7_field);
		
		if(!attachment || !cf7_field_data)
			return;
		
		if(!embed.id)
			embed.id = ++embed_id_autoinc;
			
		var embeds = getEmbeds();
		embeds.push(embed);
		setEmbeds(embeds);
		
		addEmbedEntry(cf7_field_data, attachment, embed);
	};
	
	var refreshEmbeds = function() {
		
		jQuery('.wpcf7-pdf-forms-admin .image-embeds-row').remove();
		
		var embeds = getEmbeds();
		for(var i=0, l=embeds.length; i<l; i++)
		{
			var embed = embeds[i];
			var attachment = getAttachment(embed.attachment_id);
			var cf7_field_data = getCf7FieldData(embed.cf7_field);
			
			if(!attachment || !cf7_field_data)
				continue;
			
			addEmbedEntry(cf7_field_data, attachment, embed);
		}
	};
	
	var addEmbedEntry = function(cf7_field_data, attachment, embed) {
		
		var page = embed.page;
		
		var template = jQuery('.wpcf7-pdf-forms-admin .image-embeds-row-template');
		var tag = template.clone().removeClass('image-embeds-row-template').addClass('image-embeds-row');
		
		var delete_button = tag.find('.delete-cf7-field-embed-button');
		delete_button.data('embed_id', embed.id);
		delete_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Embed))
				return;
			
			deleteEmbed(jQuery(this).data('embed_id'));
			
			tag.remove();
			
			return false;
		});
		
		tag.find('.cf7-field-caption').text(cf7_field_data.caption);
		tag.find('.pdf-file-caption').text('[' + attachment.attachment_id + '] ' + attachment.filename);
		tag.find('.page-caption').text(page > 0 ? page : 'all');
		
		if(page > 0)
			loadPageSnapshot(attachment, embed, tag);
		else
			tag.find('.page-selector-row').addBack('.page-selector-row').hide();
		
		jQuery('.wpcf7-pdf-forms-admin .image-embeds tbody').append(tag);
	};
	
	var deleteEmbed = function(embed_id) {
		
		var embeds = getEmbeds();
		
		for(var i=0, l=embeds.length; i<l; i++)
			if(embeds[i].id == embed_id)
			{
				embeds.splice(i, 1);
				break;
			}
		
		setEmbeds(embeds);
	};
	
	var loadPageSnapshot = function(attachment, embed, tag) {
		
		var info = getAttachmentInfo(attachment.attachment_id);
		if(!info)
			return;
		
		var pages = info.pages;
		var pageData = null;
		for(var p=0;p<pages.length;p++)
		{
			if(pages[p].number == embed.page)
			{
				pageData = pages[p];
				break;
			}
		}
		if(!pageData || !pageData.width || !pageData.height)
			return;
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: {
				'action': 'wpcf7_pdf_forms_query_page_image',
				'attachment_id': attachment.attachment_id,
				'page': embed.page,
				'nonce': wpcf7_pdf_forms.ajax_nonce
			},
			cache: false,
			dataType: 'json',
			
			success: function(data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.hasOwnProperty('snapshot'))
				{
					var width = 500;
					var height = Math.round((pageData.height / pageData.width) * width);
					
					var container = tag.find('.jcrop-container');
					var image = tag.find('.jcrop-page');
					
					var widthStr = width.toString();
					var heightStr = height.toString();
					var widthCss = widthStr + 'px';
					var heightCss = heightStr + 'px';
					
					jQuery(image).attr('width', widthStr).css('width', widthCss);
					jQuery(image).attr('height', heightStr).css('height', heightCss);
					jQuery(container).css('width', widthCss);
					jQuery(container).css('height', heightCss);
					
					var xPixelsPerPoint = width / pageData.width;
					var yPixelsPerPoint = height / pageData.height;
					
					var leftInput = tag.find('input[name=left]');
					var topInput = tag.find('input[name=top]');
					var widthInput = tag.find('input[name=width]');
					var heightInput = tag.find('input[name=height]');
					
					leftInput.attr('max', width / xPixelsPerPoint);
					topInput.attr('max', height / yPixelsPerPoint);
					widthInput.attr('max', width / xPixelsPerPoint);
					heightInput.attr('max', height / yPixelsPerPoint);
					
					var updateEmbedCoordinates = function(x, y, w, h)
					{
						var embeds = getEmbeds();
						for(var i=0, l=embeds.length; i<l; i++)
							if(embeds[i].id == embed.id)
							{
								embeds[i].left = embed.left = x;
								embeds[i].top = embed.top = y;
								embeds[i].width = embed.width = w;
								embeds[i].height = embed.height = h;
								
								break;
							}
						setEmbeds(embeds);
					};
					
					var updateCoordinates = function(c)
					{
						leftInput.val(Math.round(c.x / xPixelsPerPoint));
						topInput.val(Math.round(c.y / yPixelsPerPoint));
						widthInput.val(Math.round(c.w / xPixelsPerPoint));
						heightInput.val(Math.round(c.h / yPixelsPerPoint));
						
						updateEmbedCoordinates(
							leftInput.val(),
							topInput.val(),
							widthInput.val(),
							heightInput.val()
						);
					};
					
					var jcropApi;
					
					var updateRegion = function() {
						
						var leftValue = parseFloat(leftInput.val());
						var topValue = parseFloat(topInput.val());
						var widthValue = parseFloat(widthInput.val());
						var heightValue = parseFloat(heightInput.val());
						
						if(typeof leftValue == 'number'
						&& typeof topValue == 'number'
						&& typeof widthValue == 'number'
						&& typeof heightValue == 'number')
						{
							jcropApi.setSelect([
								leftValue * xPixelsPerPoint,
								topValue * yPixelsPerPoint,
								(leftValue + widthValue) * xPixelsPerPoint,
								(topValue + heightValue) * yPixelsPerPoint
							]);
							
							updateEmbedCoordinates(
								leftValue,
								topValue,
								widthValue,
								heightValue
							);
						}
					}
					
					jQuery(image).one('load', function() {
						image.Jcrop({
							onChange: updateCoordinates,
							onSelect: updateCoordinates,
							onRelease: updateCoordinates,
							boxWidth: width,
							boxHeight: height,
							trueSize: [width, height],
							minSize: [1, 1]
						}, function() {
							
							jcropApi = this;
							
							if(!embed.left)
								embed.left = Math.round(pageData.width * 0.25);
							if(!embed.top)
								embed.top = Math.round(pageData.height * 0.25);
							if(!embed.width)
								embed.width = Math.round(pageData.width * 0.5);
							if(!embed.height)
								embed.height = Math.round(pageData.height * 0.5);
							
							updateCoordinates({
								x: Math.round(embed.left * xPixelsPerPoint),
								y: Math.round(embed.top * yPixelsPerPoint),
								w: Math.round(embed.width * xPixelsPerPoint),
								h: Math.round(embed.height * yPixelsPerPoint)
							});
							
							updateRegion();
						});
					});
					
					tag.find('input.coordinate').change(updateRegion);
					
					jQuery(image).attr('src', data.snapshot);
				}
				
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var refreshPageList = function()
	{
		var pages = jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .page-list');
		var files = jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .pdf-files-list');
		
		pages.empty();
		
		pages.append(jQuery('<option>', { 
			value: 0,
			text : 'all'
		}));
		
		var info = getAttachmentInfo(files.val());
		if(info)
		{
			jQuery.each(info.pages, function(p, page){
				pages.append(jQuery('<option>', { 
					value: page.number,
					text : page.number
				}));
			});
			
			if(info.pages.length > 0)
				pages.find('option:eq(1)').prop('selected', true);
		}
	};
	
	jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .pdf-files-list').change(refreshPageList);
	
	
	// set up global 'Get Tags' button handler
	jQuery('.wpcf7-pdf-forms-admin .get-tags-all-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var attachments = getAttachments();
		if(attachments.length == 0)
			return false;
		
		var attachment_id = jQuery('.wpcf7-pdf-forms-admin .tag-generator-tool .pdf-files-list').val();
		
		var all = false;
		if(!attachment_id || attachment_id == 'all')
			all = true;
		
		var attachmentList = [];
		
		if(all)
			for(var i=0, l=attachments.length; i<l; i++)
				attachmentList.push(attachments[i].attachment_id);
		else
			attachmentList.push(attachment_id);
		
		getTags(attachmentList, all);
		
		return false;
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
	jQuery('.wpcf7-pdf-forms-admin .insert-tags-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var tags = jQuery('.wpcf7-pdf-forms-admin .tags-textarea').val();
		var wpcf7obj = getWpcf7obj();
		if(wpcf7obj)
		{
			wpcf7obj.taggen.insert(tags);
			tb_remove();
		}
		
		return false;
	});
	
	// set up 'Insert And Link' button handler
	jQuery('.wpcf7-pdf-forms-admin .insert-tag-hint-btn').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
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
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var wpcf7obj = getWpcf7obj();
		var tagText = "";
		
		var pdf_fields = getUnmappedPdfFields();
		
		if(wpcf7obj)
		{
			jQuery.each(pdf_fields, function(f, field) {
				if(field.attachment_id == 'all' && field.tag_hint)
					tagText +=
						'<label>' + jQuery("<div>").text(field.name).html() + '</label>\n' +
						'    ' + field.tag_hint + '\n\n';
			});
			
			if(tagText)
			{
				wpcf7obj.taggen.insert(tagText);
				loadCf7Fields();
				
				jQuery.each(pdf_fields, function(f, field) {
					if(field.attachment_id == 'all' && field.tag_hint)
						addMapping(field.tag_name, field.id);
				});
				
				tb_remove();
			}
		}
		
		return false;
	});
	
	// set up 'Attach a PDF file' button handler
	var attachPdf = function(file_id) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var data = new FormData();
		data.append("post_id", post_id);
		data.append("file_id", file_id);
		data.append("action", 'wpcf7_pdf_forms_get_attachment_info');
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
				
				delete data.success;
				
				if(data.hasOwnProperty('attachment_id'))
				{
					if(data.hasOwnProperty('info'))
					{
						setAttachmentInfo(data.attachment_id, data.info);
						delete data.info;
					}
					addAttachment(data);
				}
				
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner(); },
			complete: function() { hideSpinner(); }
			
		});
		
		return false;
	};
	jQuery('.wpcf7-pdf-forms-admin .attach-btn').click(function(event) {
		
		event.preventDefault();
		
		// create the pdf frame
		var pdf_frame = wp.media({
			title: wpcf7_pdf_forms.__PDF_Frame_Title,
			multiple: false,
			library: {
				order: 'DESC',
				// we can use ['author','id','name','date','title','modified','uploadedTo','id','post__in','menuOrder']
				orderby: 'date',
				type: 'application/pdf',
				search: null,
				uploadedTo: null
			},
			button: {
				text: wpcf7_pdf_forms.__PDF_Frame_Button
			}
		});
		// callback on the pdf frame
		pdf_frame.on('select', function() {
			var attachment = pdf_frame.state().get('selection').first().toJSON();
			if(!getAttachmentInfo(attachment.id))
				attachPdf(attachment.id);
		});
		pdf_frame.open();
	});
	
	// set up 'Add Mapping' button handler
	jQuery('.wpcf7-pdf-forms-admin .add-mapping-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var cf7_field = jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper .cf7-field-list').val();
		var pdf_field = jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper .pdf-field-list').val();
		
		if(cf7_field && pdf_field)
			addMapping(cf7_field, pdf_field);
		
		return false;
	});
	
	// set up 'Delete All Mappings' button handler
	jQuery('.wpcf7-pdf-forms-admin .delete-all-mappings-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		deleteAllMappings();
		
		return false;
	});
	
	// set up 'Embed Image' button handler
	jQuery('.wpcf7-pdf-forms-admin .add-cf7-field-embed-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var cf7_field = jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .cf7-field-list').val();
		var attachment_id = jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .pdf-files-list').val();
		var page = jQuery('.wpcf7-pdf-forms-admin .image-embedding-tool .page-list').val();
		
		addEmbed({
			'cf7_field': cf7_field,
			'attachment_id': attachment_id,
			'page': page
		});
		
		var embedRowPosition = jQuery(".wpcf7-pdf-forms-admin .image-embeds-row").last().position();
		if(embedRowPosition)
			jQuery(this).closest('#TB_ajaxContent').animate({scrollTop: embedRowPosition.top}, 1000);
		
		return false;
	});
	
	var showHelp = function(button, helpbox)
	{
		helpbox.show();
		button.text(wpcf7_pdf_forms.__Hide_Help);
	}
	
	var hideHelp = function(button, helpbox)
	{
		helpbox.hide();
		button.text(wpcf7_pdf_forms.__Show_Help);
	}
	
	// set up help buttons
	jQuery('.help-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var button = jQuery(this);
		var helpbox = button.parent().find('.helpbox');
		
		if(helpbox.is(":visible"))
			hideHelp(button, helpbox);
		else
			showHelp(button, helpbox);
		
		return false;
	});
	
	// set up 'Return to Form' button handler
	jQuery('.wpcf7-pdf-forms-admin .return-to-form-button').click(function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		tb_remove();
		
		return false;
	});
	
	wpcf7_form.change(function() {
		loadCf7Fields(removeOldMappings);
	});
	
	preloadData();
});
