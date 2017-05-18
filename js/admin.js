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
	};
	
	var warningMessage = function(msg) {
		jQuery('.wpcf7-pdf-forms-admin .messages').append(
			jQuery('<div class="warning"/>').text(msg)
		);
	};
	
	var successMessage = function(msg) {
		jQuery('.wpcf7-pdf-forms-admin .messages').append(
			jQuery('<div class="updated"/>').text(msg)
		);
	};
	
	var showSpinner = function() {
		jQuery('.spinner-overlay-box')
			.addClass('spinner-overlay')
			.append('<div class="wpcf7-pdf-forms-spinner"></div>')
		;
	}
	
	var hideSpinner = function() {
		jQuery('.spinner-overlay-box')
			.empty()
			.removeClass('spinner-overlay')
		;
	}
	
	var getTags = function(attachment_id) {
		
		clearMessages();
		
		var textarea = jQuery('.wpcf7-pdf-forms-admin .tags-textarea');
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_fields', 'attachment_id': attachment_id, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function (data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.tags)
					textarea.val(data.tags);
			},
			
			error: function (jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var getAttachments = function() {
		var value = jQuery('textarea#wpcf7-form').closest('form').find('input[name=wpcf7-pdf-forms-attachments]').val();
		if(value)
			return JSON.parse(value);
		else
			return [];
	}
	
	var setAttachments = function(attachments) {
		jQuery('textarea#wpcf7-form').each(function() {
			var form = jQuery('textarea#wpcf7-form').closest('form');
			if(form[0])
			{
				var input = form.find('input[name=wpcf7-pdf-forms-attachments]');
				if(!input[0])
				{
					input = jQuery("<input type='hidden' name='wpcf7-pdf-forms-attachments'/>");
					jQuery(form).append(input);
				}
				input.val(JSON.stringify(attachments));
			}
		});
	}
	
	var deleteAttachment = function(attachment_id) {
		
		var attachments = getAttachments();
		var i = attachments.indexOf(attachment_id);
		if(i>-1)
			attachments.splice(i, 1);
		setAttachments(attachments);
	};
	
	var addAttachment = function(attachment_id, filename) {
		
		var attachments = getAttachments();
		attachments[attachments.length] = attachment_id;
		setAttachments(attachments);
		
		jQuery('.wpcf7-pdf-forms-admin .instructions').remove();
		
		var tag = jQuery('<tr><td class="filename"></td><td><a class="button button-primary get-tags-button" href="#">'+wpcf7_pdf_forms.__Get_Tags+'</a> <a class="button button-primary delete-button" href="#">'+wpcf7_pdf_forms.__Delete+'</a></td></tr>');
		tag.find('.filename').text('['+attachment_id+'] '+filename);
		var tags_button = tag.find('.get-tags-button');
		tags_button.data('attachment_id', attachment_id);
		tags_button.click(function() {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			getTags(jQuery(this).data('attachment_id'));
			
		});
		var delete_button = tag.find('.delete-button');
		delete_button.data('attachment_id', attachment_id);
		delete_button.click(function() {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			deleteAttachment(jQuery(this).data('attachment_id'));
			
			tag.remove();
		});
		jQuery('.wpcf7-pdf-forms-admin .pdf-attachments').append(tag);
	};
	
	var preloadAttachments = function() {
		
		clearMessages();
		
		if(!post_id)
			return;
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'GET',
			data: { 'action': 'wpcf7_pdf_forms_query_attachments', 'post_id': post_id, 'nonce': wpcf7_pdf_forms.ajax_nonce },
			cache: false,
			dataType: 'json',
			
			success: function (data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.attachments)
					jQuery.each(data.attachments, function(index, data) { addAttachment(data.attachment_id, data.filename); });
			},
			
			error: function (jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner() },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	// set up 'Insert Tags' button handler
	jQuery('.wpcf7-pdf-forms-admin .insert-tags-btn').click(function(event) {
		var tags = jQuery('.wpcf7-pdf-forms-admin .tags-textarea').val();
		_wpcf7.taggen.insert(tags);
		tb_remove();
		return false;
	});
	
	// set up 'Upload PDF' button handler
	jQuery('.wpcf7-pdf-forms-admin .upload-btn').click(function (event) {
		
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
			
			success: function (data, textStatus, jqXHR) {
				
				if(!data.success)
					return errorMessage(data.error_message);
				
				if(data.attachment_id)
					addAttachment(data.attachment_id, data.filename);
				
				file.val("");
				
				if(data.attachment_id)
					setTimeout( function(){ getTags(data.attachment_id); }, 100);
			},
			
			error: function (jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner(); },
			complete: function() { hideSpinner(); }
			
		});
	});
	
	preloadAttachments();
});
