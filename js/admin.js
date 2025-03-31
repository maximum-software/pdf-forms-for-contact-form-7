jQuery(document).ready(function($) {
	
	var wpcf7_form = jQuery('textarea#wpcf7-form');
	if(!wpcf7_form)
		return;
	
	var pluginData = {
		attachments: [],
		mappings: [],
		value_mappings: [],
		embeds: []
	};
	
	var post_id = jQuery('.wpcf7-pdf-forms-settings-panel input[name=post_id]').val();
	
	var tagGeneratorVersion = parseInt(wpcf7_pdf_forms.WPCF7_VERSION.split('.')[0], 10) < 6 ? 1 : 2;
	
	var goToFormPanel = function() {
		jQuery('#form-panel-tab a')[0].click();
	};
	
	var goToPdfFormFillerPanel = function() {
		jQuery('#wpcf7-forms-panel-tab a')[0].click();
	};
	
	var openTagGenerator = function() {
		goToFormPanel();
		jQuery('button[data-target="tag-generator-panel-pdf_form"]')[0].click();
	};
	
	var closeTagGenerator = function() {
		if(tagGeneratorVersion == 1)
			tb_remove();
		else
			jQuery('.wpcf7-pdf-forms-tag-generator-panel').closest('.tag-generator-dialog').find('.close-button')[0].click();
	};
	
	var isInTagGenerator = function() {
		return jQuery('.wpcf7-pdf-forms-tag-generator-panel').parent().is(':visible');
	};
	
	var insertFormTags = function(tagText) {
		if(tagGeneratorVersion == 1)
		{
			if(isInTagGenerator())
			{
				jQuery('.wpcf7-pdf-forms-admin-insert-box .tag').val(tagText);
				jQuery('.wpcf7-pdf-forms-admin-insert-box .insert-tag')[0].click();
			}
			else
			{
				wpcf7.taggen.insert(tagText);
				wpcf7_form.trigger('change');
				goToFormPanel();
				wpcf7_form.trigger('focus');
			}
		}
		else
		{
			if(!isInTagGenerator())
				openTagGenerator();
			
			jQuery('.wpcf7-pdf-forms-admin-insert-box .tag').val(tagText);
			jQuery('.wpcf7-pdf-forms-admin-insert-box .insert-tag')[0].click();
		}
	};
	
	var shallowCopy = function(obj)
	{
		if(obj === null || typeof obj !== 'object')
			return obj;
		if(Array.isArray(obj))
			return obj.slice();
		try
		{
			return Object.assign({}, obj);
		}
		catch (e)
		{
			console.error('shallowCopy: failed to copy value of type ' + typeof obj);
			return obj;
		}
	};
	
	var deepCopy = function(obj)
	{
		// return primitive values as-is
		if(obj === null || typeof obj !== 'object')
			return obj;
		
		// use structuredClone if available (modern browsers)
		if(typeof structuredClone === 'function')
			try { return structuredClone(obj); } catch (e) { } // ignore failure
		
		// fallback implementation for older browsers
		
		if(Array.isArray(obj))
			return obj.map(function(item) { return deepCopy(item); });
		
		try
		{
			var copy = {};
			Object.keys(obj).forEach(function(key) { copy[key] = deepCopy(obj[key]); });
			return copy;
		}
		catch (e)
		{
			console.error('deepCopy: failed to copy value of type ' + typeof obj);
			return obj;
		}
	};
	
	var clearMessages = function() {
		jQuery('.wpcf7-pdf-forms-settings-panel .messages').empty();
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
	
	var utf8atob = function(str)
	{
		// see https://developer.mozilla.org/en-US/docs/Glossary/Base64#the_unicode_problem
		return (new TextDecoder()).decode(Uint8Array.from(atob(str), c => c.charCodeAt(0)));
	};
	
	var base64urldecode = function(data)
	{
		return utf8atob(strtr(data, {'.': '+', '_': '/'}).padEnd(data.length % 4, '='));
	}
	
	var getTags = function(attachments, all) {
		
		if(!all) all = false;
		
		clearMessages();
		
		var textarea = jQuery('.wpcf7-pdf-forms-admin .tags-textarea');
		
		textarea.val('');
		
		jQuery.ajax({
			url: wpcf7_pdf_forms.ajax_url,
			type: 'POST',
			data: { 'action': 'wpcf7_pdf_forms_query_tags', 'attachments': attachments, 'all': all, 'wpcf7-form': wpcf7_form.val(), 'nonce': wpcf7_pdf_forms.ajax_nonce },
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
			
			beforeSend: function() { showSpinner(); },
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
				var data = {
						'name': field.name,
						'tag_hint': field.tag_hint,
						'tag_name': field.tag_name,
					};
				
				if(field.hasOwnProperty('options'))
				{
					var options = [];
					
					jQuery.each(field.options, function(o, option) {
						if(typeof option === 'object')
						{
							if(option.hasOwnProperty('value'))
								options.push(String(option.value));
						}
						else
							options.push(String(option));
					});
					
					data['options'] = options;
				}
				
				var all_attachment_data = shallowCopy(data);
				var current_attachment_data = shallowCopy(data);
				
				all_attachment_data['id'] = 'all-' + field.id;
				all_attachment_data['text'] = field.name;
				all_attachment_data['attachment_id'] = 'all';
				
				current_attachment_data['id'] = attachment.attachment_id + '-' + field.id;
				current_attachment_data['text'] = '[' + attachment.attachment_id + '] ' + field.name;
				current_attachment_data['attachment_id'] = attachment.attachment_id;
				
				pdfFieldsA.push(all_attachment_data);
				pdfFieldsB.push(current_attachment_data);
			});
		});
		
		var ids = [];
		pdfFields = [];
		
		jQuery.each(pdfFieldsA.concat(pdfFieldsB), function(f, field) {
			if(ids.indexOf(field.id) == -1)
			{
				ids.push(field.id);
				field.lowerText = String(field.text).toLowerCase();
				pdfFields.push(field);
			}
		});
		
		runWhenDone(refreshPdfFields);
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
		select2SharedData.unmappedPdfFields = getUnmappedPdfFields(); // TODO: optimize this
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-field-list').resetSelect2Field();
	};
	
	var cf7FieldsCache = [];
	
	// Object assign polyfill, courtesy of https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/assign
	if (typeof Object.assign !== 'function') {
		// Must be writable: true, enumerable: false, configurable: true
		Object.defineProperty(Object, "assign", {
			value: function assign(target, varArgs) { // .length of function is 2
				'use strict';
				if (target === null || target === undefined) {
					throw new TypeError('Cannot convert undefined or null to object');
				}
				
				var to = Object(target);
				
				for (var index = 1; index < arguments.length; index++) {
					var nextSource = arguments[index];
					
					if (nextSource !== null && nextSource !== undefined) {
						for (var nextKey in nextSource) {
							// Avoid bugs when hasOwnProperty is shadowed
							if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
							  to[nextKey] = nextSource[nextKey];
							}
						}
					}
				}
				return to;
			},
			writable: true,
			configurable: true
		});
	}
	
	var precomputeCf7Select2Cache = function() {
		
		var cf7Select2Cache = [];
		
		jQuery.each(cf7FieldsCache, function(i, field) {
			field = shallowCopy(field);
			field.lowerText = String(field.text).toLowerCase();
			field.mailtag = false;
			cf7Select2Cache.push(field);
		});
		
		var mailtags = [
			 '[_date]'
			,'[_date] [_time]'
			,'[_serial_number]'
			,'[_format_your-date "D, d M y"]'
			,wpcf7_pdf_forms.__Custom_String
			,'https://embed.image.url/[_url]'
			,'[_raw_your-field]'
			,'[_remote_ip]'
			,'[_url]'
			,'[_user_agent]'
			,'[_post_id]'
			,'[_post_name]'
			,'[_post_title]'
			,'[_contact_form_title]'
			,'[_post_url]'
			,'[_post_author]'
			,'[_post_author_email]'
			,'[_site_title]'
			,'[_site_description]'
			,'[_site_url]'
			,'[_site_domain]'
			,'[_site_admin_email]'
			,'[_user_login]'
			,'[_user_email]'
			,'[_user_url]'
			,'[_user_first_name] [_user_last_name]'
			,'[_user_nickname]'
			,'[_user_display_name]'
			,'[_invalid_fields]'
		];
		
		jQuery.each(mailtags, function(i, mailtag) {
			cf7Select2Cache.push({
				id: mailtag,
				text: mailtag,
				lowerText: String(mailtag).toLowerCase(),
				mailtag: true
			});
		});
		
		// TODO: use the same list for both uses (cf7FieldsCache)
		select2SharedData.cf7FieldsCache = cf7Select2Cache;
	};
	
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
					
					runLoadCf7FieldsCallbacks();
					
					if(callback)
						callback();
				}
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner(); },
			complete: function() { hideSpinner(); }
			
		});
	};
	var getCf7FieldData = function(id) {
		
		for(var i=0, l=cf7FieldsCache.length; i<l; i++)
			if(cf7FieldsCache[i].id == id)
				return cf7FieldsCache[i];
		
		return null;
	};
	
	var refreshCf7Fields = function() {
		precomputeCf7Select2Cache();
		
		jQuery('.wpcf7-pdf-forms-settings-panel .cf7-field-list').resetSelect2Field();
	};
	
	var getData = function(field) {
		return pluginData[field];
	};
	
	var setData = function(field, value) {
		pluginData[field] = value;
		runWhenDone(updatePluginDataField);
	};
	
	var updatePluginDataField = function() {
		var form = wpcf7_form.closest('form');
		if(form)
		{
			var input = form.find('input[name=wpcf7-pdf-forms-data]');
			if(!input[0])
			{
				input = jQuery("<input type='hidden' name='wpcf7-pdf-forms-data'/>");
				jQuery(form).append(input);
			}
			input.val(JSON.stringify(pluginData));
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
		
		var remove_ids = [];
		var mappings = getMappings();
		jQuery.each(mappings, function(index, mapping) {
			var field_attachment_id = mapping.pdf_field.substr(0, mapping.pdf_field.indexOf('-'));
			if(field_attachment_id == attachment_id)
				remove_ids.push(mapping.mapping_id);
		});
		jQuery.each(remove_ids, function(index, id) { deleteMapping(id); });
		
		remove_ids = [];
		var embeds = getEmbeds();
		jQuery.each(embeds, function(index, embed) {
			if(embed.attachment_id == attachment_id)
				remove_ids.push(embed.id);
		});
		jQuery.each(remove_ids, function(index, id) { deleteEmbed(id); });
		
		var attachments = getAttachments();
		
		for(var i=0, l=attachments.length; i<l; i++)
			if(attachments[i].attachment_id == attachment_id)
			{
				attachments.splice(i, 1);
				break;
			}
		
		setAttachments(attachments);
		
		for (var i=0, l=select2SharedData.pdfSelect2Files.length; i<l; i++)
			if (select2SharedData.pdfSelect2Files[i].id == attachment_id)
			{
				select2SharedData.pdfSelect2Files.splice(i, 1);
				break;
			}
		
		deleteAttachmentInfo(attachment_id);
		
		refreshMappings();
		refreshEmbeds();
		refreshPdfFilesList();
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
		
		data = deepCopy(data);
		
		var attachment_id = data.attachment_id;
		var filename = data.filename;
		var options = data.options;
		
		var attachments = getAttachments();
		attachments.push(deepCopy(data));
		setAttachments(attachments);
		
		jQuery('.wpcf7-pdf-forms-settings-panel .instructions').remove();
		
		var template = jQuery('.wpcf7-pdf-forms-settings-panel  .pdf-attachment-row-template');
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
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
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
			
			jQuery('.wpcf7-pdf-forms-settings-panel .pdf-files-list option[value='+attachment_id+']').remove();
			
			return false;
		});
		
		jQuery('.wpcf7-pdf-forms-settings-panel .pdf-attachments tr.pdf-buttons').before(tag);
		// TODO: remove item when attachment is deleted
		// better TODO: use shared list (attachmentInfo)
		select2SharedData.pdfSelect2Files.push({
			id: attachment_id,
			text: '[' + attachment_id + '] ' + filename,
			lowerText: String('[' + attachment_id + '] ' + filename).toLowerCase()
		});
		
		refreshPdfFilesList();
		
		jQuery('.wpcf7-pdf-forms-admin .help-button').each(function(){
			var button = jQuery(this);
			var helpbox = button.parent().find('.helpbox');
			hideHelp(button, helpbox);
		});
	};
	
	jQuery.fn.select2.amd.define("pdf-forms-for-cf7-shared-data-adapter", 
	['select2/data/array','select2/utils'],
		function (ArrayData, Utils) {
			function CustomData($element, options) {
				CustomData.__super__.constructor.call(this, $element, options);
			}
			
			Utils.Extend(CustomData, ArrayData);
			
			CustomData.prototype.query = function (params, callback) {
				
				var items = select2SharedData[this.options.options.sharedDataElement];
				
				var pageSize = 20;
				if(!("page" in params))
					params.page = 1;
				
				var totalNeeded = params.page * pageSize;
				
				if(params.term && params.term !== '')
				{
					var upperTerm = params.term.toLowerCase();
					var count = 0;
					
					items = items.filter(function(item) {
						
						// don't filter any more items if we have collected enough
						if(count > totalNeeded)
							return false;
						
						var lowerText = item.hasOwnProperty("lowerText") ? item.lowerText : item.text.toLowerCase();
						var counts = lowerText.indexOf(upperTerm) >= 0;
						if(counts)
							count++;
						
						return counts;
					});
				}
				
				var more = items.length > totalNeeded;
				
				items = items.slice((params.page - 1) * pageSize, totalNeeded); // paginate
				
				callback({
					results: deepCopy(items),
					pagination: { more: more }
				});
			};
			
			return CustomData;
		}
	);
	
	jQuery.fn.resetSelect2Field = function(id) {
		
		if(typeof id == 'undefined')
			id = null;
		
		if(!jQuery(this).data('select2'))
			return;
		
		jQuery(this).empty();
		
		var select2Data = select2SharedData[this.data().select2.options.options.sharedDataElement];
		if(select2Data.length > 0)
		{
			var optionInfo = select2Data[id !== null ? id : 0];
			var option = new Option(optionInfo.text, optionInfo.id, true, true);
			jQuery(this).append(option).val(optionInfo.id);
		}
		
		// TODO fix
		jQuery(this).trigger('change');
		jQuery(this).trigger({
			type: 'select2:select',
			params: {
				data: optionInfo
			}
		});
		
		return this;
	}
	
	var select2SharedData = {
		unmappedPdfFields: [],
		cf7FieldsCache: [],
		pdfSelect2Files: [{id: 0, text: wpcf7_pdf_forms.__All_PDFs, lowerText: String(wpcf7_pdf_forms.__All_PDFs).toLowerCase()}],
		pageList: []
	};
	
	jQuery('.wpcf7-pdf-forms-tag-generator-panel .pdf-field-list').select2({
		ajax: {},
		width: '100%',
		sharedDataElement: "unmappedPdfFields",
		dropdownParent: jQuery('.wpcf7-pdf-forms-tag-generator-panel').closest('.tag-generator-panel'),
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	});
	jQuery('.wpcf7-pdf-forms-settings-panel .pdf-field-list').select2({
		ajax: {},
		width: '100%',
		sharedDataElement: "unmappedPdfFields",
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	});
	jQuery('.wpcf7-pdf-forms-settings-panel .cf7-field-list').select2({
		ajax: {},
		width: '100%',
		dropdownAutoWidth: true,
		sharedDataElement: "cf7FieldsCache",
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	}).on('select2:select', function (e) {
		var data = e.params.data;
		jQuery(this).find('option:selected').attr('data-mailtags', data['mailtag']);
	});
	jQuery('.wpcf7-pdf-forms-tag-generator-panel .pdf-files-list').select2({
		ajax: {},
		dropdownAutoWidth: true,
		dropdownParent: jQuery('.wpcf7-pdf-forms-tag-generator-panel').closest('.tag-generator-panel'),
		sharedDataElement: "pdfSelect2Files",
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	});
	jQuery('.wpcf7-pdf-forms-settings-panel .pdf-files-list').select2({
		ajax: {},
		width: '100%',
		dropdownAutoWidth: true,
		sharedDataElement: "pdfSelect2Files",
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	});
	jQuery('.wpcf7-pdf-forms-settings-panel .page-list').select2({
		ajax: {},
		width: '100%',
		dropdownAutoWidth: true,
		sharedDataElement: "pageList",
		dataAdapter: jQuery.fn.select2.amd.require("pdf-forms-for-cf7-shared-data-adapter")
	});
	
	var preloadData = function() {
		
		if(!post_id)
		{
			loadCf7Fields(); // even with no post id we can still populate CF7 field dropdowns
			return;
		}
		
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
					refreshMappings(); // needed to reloadDefaultMappings()
				}
				
				if(data.hasOwnProperty('mappings'))
					jQuery.each(data.mappings, function(index, mapping) {
						addMapping(mapping);
					});
				
				if(data.hasOwnProperty('value_mappings'))
				{
					var mappings = getMappings();
					jQuery.each(data.value_mappings, function(index, value_mapping) {
						
						value_mapping = shallowCopy(value_mapping);
						
						// find mapping id
						for(var i=0, l=mappings.length; i<l; i++)
						{
							if(mappings[i].pdf_field == value_mapping.pdf_field)
							{
								value_mapping.mapping_id = mappings[i].mapping_id;
								break;
							}
						}
						
						if(!value_mapping.hasOwnProperty('mapping_id'))
							return;
						
						addValueMapping(value_mapping);
					});
				}
				
				if(data.hasOwnProperty('embeds'))
				{
					jQuery.each(data.embeds, function(index, embed) { if(embed.id && embed_id_autoinc < embed.id) embed_id_autoinc = embed.id; });
					jQuery.each(data.embeds, function(index, embed) { addEmbed(embed); });
				}
			},
			
			error: function(jqXHR, textStatus, errorThrown) { return errorMessage(textStatus); },
			
			beforeSend: function() { showSpinner(); },
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
	
	var getMapping = function(id) {
		var mappings = getMappings();
		for(var i=0; i<mappings.length; i++)
			if(mappings[i].mapping_id == id)
				return mappings[i];
		return undefined;
	};
	
	var getValueMappings = function() {
		var valueMappings = getData('value_mappings');
		if(valueMappings)
			return valueMappings;
		else
			return [];
	};
	
	var runWhenDoneTimers = {};
	var runWhenDone = function(func) {
		if(runWhenDoneTimers[func])
			return;
		runWhenDoneTimers[func] = setTimeout(function(func){ delete runWhenDoneTimers[func]; func(); }, 0, func);
	};
	
	var runAfterLoadCf7FieldsCallbacks = {};
	var runAfterLoadCf7Fields = function(func) {
		if(runAfterLoadCf7FieldsCallbacks[func])
			return;
		runAfterLoadCf7FieldsCallbacks[func] = func;
	};
	var runLoadCf7FieldsCallbacks = function() {
		for(let key in runAfterLoadCf7FieldsCallbacks)
			runAfterLoadCf7FieldsCallbacks[key]();
		runAfterLoadCf7FieldsCallbacks = {};
	};
	
	var setMappings = function(mappings) {
		setData('mappings', mappings);
		runWhenDone(refreshPdfFields);
	};
	
	var deleteMapping = function(mapping_id) {
		
		var mappings = getMappings();
		
		for(var i=0; i<mappings.length; i++)
			if(mappings[i].mapping_id == mapping_id)
			{
				mappings.splice(i, 1);
				break;
			}
		
		deleteValueMappings(mapping_id);
		
		setMappings(mappings);
	};
	
	var deleteAllMappings = function() {
		setMappings([]);
		setValueMappings([]);
		refreshMappings();
	};
	
	var setValueMappings = function(value_mappings) {
		setData('value_mappings', value_mappings);
	};
	
	var deleteValueMapping = function(value_mapping_id) {
		
		var value_mappings = getValueMappings();
		
		for(var i=0; i<value_mappings.length; i++)
			if(value_mappings[i].value_mapping_id == value_mapping_id)
			{
				value_mappings.splice(i, 1);
				break;
			}
		
		setValueMappings(value_mappings);
	};
	
	var deleteValueMappings = function(mapping_id) {
		
		var value_mappings = getValueMappings();
		
		value_mappings = value_mappings.filter(function(value_mapping) {
			return value_mapping.mapping_id != mapping_id;
		});
		
		setValueMappings(value_mappings);
		runWhenDone(refreshMappings);
	};
	
	var generateId = function() {
		return Math.random().toString(36).substring(2) + Date.now().toString();
	}
	
	var addValueMapping = function(data) {
		
		if(typeof data.mapping_id == 'undefined'
		|| typeof data.pdf_field == 'undefined'
		|| typeof data.cf7_value == 'undefined'
		|| typeof data.pdf_value == 'undefined')
			return;
		
		data = deepCopy(data);
		
		data.value_mapping_id = generateId();
		pluginData["value_mappings"].push(data);
		
		runWhenDone(updatePluginDataField);
		
		addValueMappingEntry(data);
	};
	
	var addValueMappingEntry = function(data) {
		
		var mapping = getMapping(data.mapping_id);
		
		var cf7Field = null;
		if(mapping && mapping.hasOwnProperty('cf7_field'))
			cf7Field = getCf7FieldData(mapping.cf7_field);
		
		var pdfField = getPdfFieldData(data.pdf_field);
		
		var template = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row-valuemapping-template');
		var tag = template.clone().removeClass('pdf-mapping-row-valuemapping-template').addClass('pdf-valuemapping-row');
		tag.data('mapping_id', data.mapping_id);
		
		tag.find('input').data('value_mapping_id', data.value_mapping_id);
		
		if(typeof cf7Field == 'object' && cf7Field !== null && cf7Field.hasOwnProperty('values') && Array.isArray(cf7Field.values) && cf7Field.values.length > 0)
		{
			var input = tag.find('input.cf7-value');
			var select = jQuery('<select>');
			select.insertAfter(input);
			input.hide();
			
			var options = [];
			var add_custom = true;
			jQuery.each(cf7Field.values, function(i, option) {
				options.push({ id: option, text: option });
				if(option == data.cf7_value)
					add_custom = false;
			});
			if(add_custom && data.cf7_value != '')
				options.push({ id: data.cf7_value, text: data.cf7_value });
			options.unshift({ id: '', text: wpcf7_pdf_forms.__Null_Value_Mapping });
			
			select.select2({
				data: options,
				tags: true,
				width: '100%',
			});
			
			select.val(data.cf7_value).trigger('change');
			
			select.change(function() {
				jQuery(this).prev().val(jQuery(this).val()).trigger('change');
			});
		}
		
		if(typeof pdfField == 'object' && pdfField !== null && pdfField.hasOwnProperty('options') && Array.isArray(pdfField.options) && pdfField.options.length > 0)
		{
			var input = tag.find('input.pdf-value');
			var select = jQuery('<select>');
			select.insertAfter(input);
			input.hide();
			
			var options = [];
			var add_custom = true;
			jQuery.each(pdfField.options, function(i, option) {
				options.push({ id: option, text: option });
				if(option == data.pdf_value)
					add_custom = false;
			});
			if(add_custom && data.pdf_value != '')
				options.push({ id: data.pdf_value, text: data.pdf_value });
			options.unshift({ id: '', text: wpcf7_pdf_forms.__Null_Value_Mapping });
			
			select.select2({
				data: options,
				tags: true,
				width: '100%',
			});
			
			select.val(data.pdf_value).trigger('change');
			
			select.change(function() {
				jQuery(this).prev().val(jQuery(this).val()).trigger('change');
			});
		}
		
		tag.find('input.cf7-value').val(data.cf7_value);
		tag.find('input.pdf-value').val(data.pdf_value);
		
		// make sure the "Delete All" button is shown in the list of value mappings for current field mapping only once
		var manageDeleteAllValueMappingsButton = function(mapping_id) {
			
			var value_mapping_rows = template.parent().find('.pdf-valuemapping-row').filter(function() {
				return jQuery(this).data('mapping_id') === mapping_id;
			});
			
			value_mapping_rows.find('.delete-all-valuemappings-button').hide();
			
			if(value_mapping_rows.length > 1) // no need to show the "Delete All" button if there is only one value mapping
				value_mapping_rows.last().find('.delete-all-valuemappings-button').show();
		};
		
		var delete_button = tag.find('.delete-valuemapping-button');
		delete_button.data('value_mapping_id', data.value_mapping_id);
		delete_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Mapping))
				return;
			
			deleteValueMapping(jQuery(this).data('value_mapping_id'));
			
			var value_mapping_row = jQuery(this).closest('.pdf-valuemapping-row');
			var mapping_id = value_mapping_row.data('mapping_id');
			value_mapping_row.remove();
			manageDeleteAllValueMappingsButton(mapping_id);
		});
		
		var delete_all_button = tag.find('.delete-all-valuemappings-button');
		delete_all_button.data('mapping_id', data.mapping_id);
		delete_all_button.click(function(event) {
			
			// prevent running default button click handlers
			event.stopPropagation();
			event.preventDefault();
			
			if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_All_Value_Mappings))
				return;
			
			var mapping_id = jQuery(this).data('mapping_id');
			deleteValueMappings(mapping_id);
		});
		
		var mappingTag = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row[data-mapping_id="'+data.mapping_id+'"]');
		tag.insertAfter(mappingTag);
		
		manageDeleteAllValueMappingsButton(data.mapping_id);
	};
	
	var addMapping = function(data) {
		
		data = deepCopy(data);
		
		data.mapping_id = generateId();
		pluginData["mappings"].push(data);
		
		runWhenDone(updatePluginDataField);
		runWhenDone(refreshPdfFields);
		
		addMappingEntry(data);
		
		return data.mapping_id;
	};
	
	var addMappingEntry = function(data) {
		var pdf_field_data = getPdfFieldData(data.pdf_field);
		var pdf_field_caption;
		if(pdf_field_data)
			pdf_field_caption = pdf_field_data.text;
		else
		{
			var field_id = data.pdf_field.substr(data.pdf_field.indexOf('-')+1);
			pdf_field_caption = base64urldecode(field_id);
		}
		
		if(data.hasOwnProperty('cf7_field'))
		{
			var cf7_field_data = getCf7FieldData(data.cf7_field);
			var cf7_field_caption = data.cf7_field;
			if(cf7_field_data)
				cf7_field_caption = cf7_field_data.text;
			
			var template = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row-template');
			var tag = template.clone().removeClass('pdf-mapping-row-template').addClass('pdf-mapping-row');
			
			tag.find('.cf7-field-name').text(cf7_field_caption);
			tag.find('.pdf-field-name').text(pdf_field_caption);
			
			tag.find('.convert-to-mailtags-button').data('mapping_id', data.mapping_id);
			
			var virtual = cf7_field_data && cf7_field_data.pdf_field == data.pdf_field;
		}
		else if(data.hasOwnProperty('mail_tags'))
		{
			var template = jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row-mailtag-template');
			var tag = template.clone().removeClass('pdf-mapping-row-mailtag-template').addClass('pdf-mapping-row');
			
			tag.find('textarea.mail-tags').val(data.mail_tags).data('mapping_id', data.mapping_id);
			tag.find('.pdf-field-name').text(pdf_field_caption);
			
			var virtual = false;
		}
		
		tag.attr('data-mapping_id', data.mapping_id);
		
		var delete_button = tag.find('.delete-mapping-button');
		if(virtual)
			delete_button.remove();
		else
		{
			delete_button.data('mapping_id', data.mapping_id);
			delete_button.click(function(event) {
				
				// prevent running default button click handlers
				event.stopPropagation();
				event.preventDefault();
				
				if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_Mapping))
					return;
				
				deleteMapping(jQuery(this).data('mapping_id'));
				
				jQuery(this).closest('.pdf-mapping-row').remove();
				
				var mappings = getMappings();
				if(mappings.length==0)
					jQuery('.wpcf7-pdf-forms-admin .delete-all-row').hide();
			});
		}
		
		var map_value_button = tag.find('.map-value-button');
		if(virtual)
			map_value_button.remove();
		else
		{
			map_value_button.data('mapping_id', data.mapping_id);
			map_value_button.click(function(event) {
				
				// prevent running default button click handlers
				event.stopPropagation();
				event.preventDefault();
				
				addValueMapping({'mapping_id': data.mapping_id, 'pdf_field': data.pdf_field, 'cf7_value': "", 'pdf_value': ""});
			});
		}
		
		tag.insertBefore(jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper .delete-all-row'));
		jQuery('.wpcf7-pdf-forms-admin .delete-all-row').show();
	};
	
	var refreshMappings = function() {
		
		jQuery('.wpcf7-pdf-forms-admin .pdf-mapping-row').remove();
		jQuery('.wpcf7-pdf-forms-admin .pdf-valuemapping-row').remove();
		
		reloadDefaultMappings();
		
		var mappings = getMappings();
		for(var i=0, l=mappings.length; i<l; i++)
			addMappingEntry(mappings[i]);
		
		var value_mappings = getValueMappings();
		for(var i=0, l=value_mappings.length; i<l; i++)
			addValueMappingEntry(value_mappings[i]);
		
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
	
	var removeOldMappingsAndEmbeds = function() {
		
		var mappings = getMappings();
		for(var i=0; i<mappings.length; i++)
			if(mappings[i].hasOwnProperty('cf7_field'))
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
		
		var embeds = getEmbeds();
		for(var i=0; i<embeds.length; i++)
			if(embeds[i].hasOwnProperty('cf7_field'))
			{
				var cf7_field_data = getCf7FieldData(embeds[i].cf7_field);
				if(!cf7_field_data)
				{
					embeds.splice(i, 1);
					i--;
				}
			}
		setEmbeds(embeds);
		refreshEmbeds();
	};
	
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
		
		var attachment_id = embed.attachment_id;
		var page = embed.page;
		
		if(!attachment_id || !page || (page != 'all' && page < 0))
			return;
		
		var attachment = null;
		if(attachment_id != 'all')
		{
			attachment = getAttachment(attachment_id);
			if(!attachment)
				return;
		}
		
		if(!embed.hasOwnProperty('mail_tags') && !embed.hasOwnProperty('cf7_field'))
			return;
		
		var cf7_field_data = null;
		if(embed.hasOwnProperty('cf7_field'))
		{
			cf7_field_data = getCf7FieldData(embed.cf7_field);
			if(!cf7_field_data)
				return;
		}
		
		embed = deepCopy(embed);
		
		if(!embed.id)
			embed.id = ++embed_id_autoinc;
		
		var embeds = getEmbeds();
		embeds.push(embed);
		setEmbeds(embeds);
		
		if(embed.hasOwnProperty('mail_tags'))
			addEmbedEntry({mail_tags: embed.mail_tags, attachment: attachment, embed: embed});
		
		if(embed.hasOwnProperty('cf7_field'))
			addEmbedEntry({cf7_field_data: cf7_field_data, attachment: attachment, embed: embed});
	};
	
	var refreshEmbeds = function() {
		
		jQuery('.wpcf7-pdf-forms-settings-panel .image-embeds-row').remove();
		
		var embeds = getEmbeds();
		for(var i=0, l=embeds.length; i<l; i++)
		{
			var embed = embeds[i];
			
			var attachment = null;
			if(embed.attachment_id != 'all')
			{
				attachment = getAttachment(embed.attachment_id);
				if(!attachment)
					continue;
			}
			
			if(embed.hasOwnProperty('mail_tags'))
				addEmbedEntry({mail_tags: embed.mail_tags, attachment: attachment, embed: embed});
			
			if(embed.hasOwnProperty('cf7_field'))
			{
				var cf7_field_data = getCf7FieldData(embed.cf7_field);
				if(!cf7_field_data)
					continue;
				
				addEmbedEntry({cf7_field_data: cf7_field_data, attachment: attachment, embed: embed});
			}
		}
	};
	
	var addEmbedEntry = function(data) {
		
		var page = data.embed.page;
		
		if(data.hasOwnProperty('mail_tags'))
		{
			var template = jQuery('.wpcf7-pdf-forms-settings-panel .image-embeds-row-mailtag-template');
			var tag = template.clone().removeClass('image-embeds-row-mailtag-template').addClass('image-embeds-row');
			tag.find('textarea.mail-tags').text(data.mail_tags);
			tag.find('textarea.mail-tags').data('embed_id', data.embed.id);
		}
		else
		{
			var template = jQuery('.wpcf7-pdf-forms-settings-panel .image-embeds-row-template');
			var tag = template.clone().removeClass('image-embeds-row-template').addClass('image-embeds-row');
			tag.find('.convert-to-mailtags-button').data('embed_id', data.embed.id);
			tag.find('span.cf7-field-name').text(data.cf7_field_data.text);
		}
		
		var delete_button = tag.find('.delete-cf7-field-embed-button');
		delete_button.data('embed_id', data.embed.id);
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
		
		var pdf_name = wpcf7_pdf_forms.__All_PDFs;
		if(data.hasOwnProperty('attachment') && data.attachment)
			pdf_name = '[' + data.attachment.attachment_id + '] ' + data.attachment.filename;
		
		tag.find('.pdf-file-caption').text(pdf_name);
		tag.find('.page-caption').text(page > 0 ? page : wpcf7_pdf_forms.__All_Pages);
		
		if(data.hasOwnProperty('attachment') && data.attachment && page > 0)
			loadPageSnapshot(data.attachment, data.embed, tag);
		else
			tag.find('.page-selector-row').addBack('.page-selector-row').hide();
		
		jQuery('.wpcf7-pdf-forms-settings-panel .image-embeds tbody').append(tag);
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
			
			beforeSend: function() { showSpinner(); },
			complete: function() { hideSpinner(); }
			
		});
	};
	
	var refreshPageList = function()
	{
		var pageList = [];
		
		pageList.push({
			id: 0,
			text: wpcf7_pdf_forms.__All_Pages,
			lowerText: String(wpcf7_pdf_forms.__All_Pages).toLowerCase()
		});
		
		var files = jQuery('.wpcf7-pdf-forms-settings-panel .image-embedding-tool .pdf-files-list');
		var info = getAttachmentInfo(files.val());
		
		if(typeof info != 'undefined' && info !== null)
		{
			jQuery.each(info.pages, function(p, page){
				pageList.push({
					id: page.number,
					text: page.number,
					lowerText: String(page.number).toLowerCase()
				});
			});
		}
		
		// TODO: use a new dynamically generated data adapter for better memory efficiency
		select2SharedData.pageList = pageList;
		
		var id = typeof info != 'undefined' && info !== null && info.pages.length > 0 ? 1 : 0;
		jQuery('.wpcf7-pdf-forms-settings-panel .page-list').resetSelect2Field(id);
	};
	
	var refreshPdfFilesList = function()
	{
		var id = select2SharedData.pdfSelect2Files.length > 1 ? 1 : null;
		jQuery('.wpcf7-pdf-forms-settings-panel .pdf-files-list').resetSelect2Field(id);
	}
	
	jQuery('.wpcf7-pdf-forms-settings-panel .image-embedding-tool').on("change", '.pdf-files-list', refreshPageList);
	
	// set up global 'Get Tags' button handler
	jQuery('.wpcf7-pdf-forms-admin').on("click", '.get-tags-all-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var attachments = getAttachments();
		if(attachments.length == 0)
			return false;
		
		var attachment_id = jQuery('.wpcf7-pdf-forms-admin .tag-generator-tool .pdf-files-list').val();
		if(attachment_id == 0)
			attachment_id = 'all';
		
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
	
	// set up 'Insert Tags' button handler
	jQuery('.wpcf7-pdf-forms-tag-generator-panel').on("click", '.insert-tags-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var tags = jQuery('.wpcf7-pdf-forms-admin .tags-textarea').val();
		insertFormTags(tags);
		jQuery('.wpcf7-pdf-forms-admin .tags-textarea').val("");
		
		return false;
	});
	
	// set up 'Pdf Field List' change handler
	jQuery('.wpcf7-pdf-forms-admin').on("change", '.pdf-field-list', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var tag;
		var tagGenerator = jQuery(this).closest('.tag-generator-panel');
		var inTagGenerator = tagGenerator.length > 0;
		if(inTagGenerator)
			tag = tagGenerator.find('input.tag-hint');
		else
			tag = jQuery('.wpcf7-pdf-forms-settings-panel input.tag-hint');
		
		tag.val('');
		tag.data('pdf_field', '');
		tag.data('cf7_field', '');
		
		var pdf_field = jQuery(this).val();
		if(!pdf_field)
			return;
		
		var pdf_field_data = getPdfFieldData(pdf_field);
		if(!pdf_field_data)
			return;
		
		tag.val(pdf_field_data.tag_hint);
		tag.data('cf7_field', pdf_field_data.tag_name);
		tag.data('pdf_field', pdf_field_data.id);
	});
	
	// set up 'Insert And Link' button handler
	jQuery('.wpcf7-pdf-forms-tag-generator-panel, .wpcf7-pdf-forms-settings-panel').on("click", '.insert-tag-hint-btn', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var tag = isInTagGenerator() ? jQuery('.wpcf7-pdf-forms-tag-generator-panel').find('input.tag-hint') : jQuery('.wpcf7-pdf-forms-settings-panel input.tag-hint');
		var tagText = tag.val();
		var cf7_field = tag.data('cf7_field');
		var pdf_field = tag.data('pdf_field');
		if(tagText !="" && (typeof cf7_field != 'undefined') && (typeof pdf_field != 'undefined'))
		{
			insertFormTags(tagText);
			
			runAfterLoadCf7Fields(function() {
				var mapping_id = addMapping({
					cf7_field: cf7_field,
					pdf_field: pdf_field,
				});
				generateValueMappings(mapping_id, cf7_field, pdf_field);
			});
		}
		
		return false;
	});
	
	// set up 'Insert & Link All' button handler
	jQuery('.wpcf7-pdf-forms-tag-generator-panel, .wpcf7-pdf-forms-settings-panel').on("click", '.insert-and-map-all-tags-btn', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var tagText = "";
		var pdf_fields = getUnmappedPdfFields();
		
		jQuery.each(pdf_fields, function(f, field) {
			if(field.attachment_id == 'all' && field.tag_hint)
				tagText +=
					'<label>' + jQuery("<div>").text(field.name).html() + '</label>\n' +
					'    ' + field.tag_hint + '\n\n';
		});
		
		if(tagText)
		{
			insertFormTags(tagText);
			
			runAfterLoadCf7Fields(function() {
				jQuery.each(pdf_fields, function(f, field) {
					if(field.attachment_id == 'all' && field.tag_hint)
					{
						var mapping_id = addMapping({
							cf7_field: field.tag_name,
							pdf_field: field.id,
						});
						generateValueMappings(mapping_id, field.tag_name, field.id);
					}
				});
			});
		}
		
		return false;
	});
	
	// set up 'Go to PDF Forms Filler panel' buttons handlers
	jQuery('.wpcf7-pdf-forms-tag-generator-panel').on("click", '.go-to-wpcf7-forms-panel-btn', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		closeTagGenerator();
		goToPdfFormFillerPanel();
		
		return false;
	});
	
	// set up 'Attach a PDF file' button handler
	var attachPdf = function(file_id) {
		
		var data = new FormData();
		data.append("action", 'wpcf7_pdf_forms_get_attachment_info');
		data.append("post_id", post_id);
		data.append("file_id", file_id);
		data.append("wpcf7-form", wpcf7_form.val());
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
						if(!data.info.hasOwnProperty('fields')
						|| typeof data.info.fields !== 'object'
						|| Object.keys(data.info.fields).length == 0)
							if(!confirm(wpcf7_pdf_forms.__Confirm_Attach_Empty_Pdf))
								return;
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
	
	// set up 'Attach a PDF File' button handler
	jQuery('.wpcf7-pdf-forms-settings-panel').on("click", '.attach-btn', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
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
	
	var generateValueMappings = function(mapping_id, cf7_field, pdf_field) {
		
		var mapping = getMapping(mapping_id);
		
		if(!mapping || !mapping.hasOwnProperty('cf7_field'))
			return;
		
		var cf7Field = getCf7FieldData(cf7_field);
		var pdfField = getPdfFieldData(pdf_field);
		
		if(!cf7Field || !pdfField)
			return;
		
		if(!cf7Field.hasOwnProperty('values') || !Array.isArray(cf7Field.values))
			return;
		
		if(!pdfField.hasOwnProperty('options') || !Array.isArray(pdfField.options))
			return;
		
		if(cf7Field.values.length == 0 || pdfField.options.length == 0)
			return;
		
		var cf7Values = cf7Field.values.filter(function(item){
			return pdfField.options.indexOf(item) < 0;
		});
		
		var pdfOptions = pdfField.options.filter(function(item){
			return item != 'Off' && cf7Field.values.indexOf(item) < 0;
		});
		
		for(var i=0; i<cf7Values.length; i++)
		{
			var bestScore = 0;
			var bestValueIndex = 0;
			
			for(var j=0; j<pdfOptions.length; j++)
			{
				var score = similarity(cf7Values[i], pdfOptions[j]);
				if(score > bestScore)
				{
					bestScore = score;
					bestValueIndex = j;
				}
			}
			addValueMapping({'mapping_id': mapping_id, 'pdf_field': pdfField.id, 'cf7_value': cf7Values[i], 'pdf_value': pdfOptions[bestValueIndex]});
		}
	};
	
	// implementation of levenshtein algorithm taken from https://stackoverflow.com/a/36566052/8915264
	function similarity(s1, s2) {
		var longer = s1;
		var shorter = s2;
		if (s1.length < s2.length) {
			longer = s2;
			shorter = s1;
		}
		var longerLength = longer.length;
		if (longerLength == 0) {
			return 1.0;
		}
		return (longerLength - editDistance(longer, shorter)) / parseFloat(longerLength);
	}
	function editDistance(s1, s2) {
		s1 = s1.toLowerCase();
		s2 = s2.toLowerCase();
		
		var costs = new Array();
		for (var i = 0; i <= s1.length; i++) {
			var lastValue = i;
			for (var j = 0; j <= s2.length; j++) {
				if (i == 0)
					costs[j] = j;
				else {
					if (j > 0) {
						var newValue = costs[j - 1];
						if (s1.charAt(i - 1) != s2.charAt(j - 1))
							newValue = Math.min(Math.min(newValue, lastValue), costs[j]) + 1;
						costs[j - 1] = lastValue;
						lastValue = newValue;
					}
				}
			}
			if (i > 0)
				costs[s2.length] = lastValue;
		}
		return costs[s2.length];
	}
	
	// set up 'Add Mapping' button handler
	jQuery('.wpcf7-pdf-forms-admin').on("click", '.add-mapping-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var tag = jQuery('.wpcf7-pdf-forms-admin .pdf-fields-mapper');
		
		var subject = tag.find('.cf7-field-list').val();
		var mailtags = tag.find('.cf7-field-list').find('option:selected').data('mailtags');
		var pdf_field = tag.find('.pdf-field-list').val();
		
		if(pdf_field && subject)
		{
			if(mailtags)
				addMapping({
					mail_tags: subject,
					pdf_field: pdf_field,
				});
			else
			{
				var mapping_id = addMapping({
					cf7_field: subject,
					pdf_field: pdf_field,
				});
				generateValueMappings(mapping_id, subject, pdf_field);
			}
		}
		
		return false;
	});
	
	// set up 'Delete All Mappings' button handler
	jQuery('.wpcf7-pdf-forms-admin').on("click", '.delete-all-mappings-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		if(!confirm(wpcf7_pdf_forms.__Confirm_Delete_All_Mappings))
			return;
		
		deleteAllMappings();
		
		return false;
	});
	
	// set up 'Embed Image' button handler
	jQuery('.wpcf7-pdf-forms-settings-panel').on("click", '.add-cf7-field-embed-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		clearMessages();
		
		var tag = jQuery('.wpcf7-pdf-forms-settings-panel .image-embedding-tool');
		
		var subject = tag.find('.cf7-field-list').val();
		var mailtags = tag.find('.cf7-field-list').find('option:selected').data('mailtags');
		var attachment_id = tag.find('.pdf-files-list').val();
		if(attachment_id == 0)
			attachment_id = 'all';
		var page = tag.find('.page-list').val();
		if(page == 0)
			page = 'all';
		
		if(subject && attachment_id && page)
		{
			if(mailtags)
				addEmbed({
					'mail_tags': subject,
					'attachment_id': attachment_id,
					'page': page
				});
			else
				addEmbed({
					'cf7_field': subject,
					'attachment_id': attachment_id,
					'page': page
				});
		}
		
		var embedRowElement = jQuery(".wpcf7-pdf-forms-settings-panel .image-embeds-row:visible").last();
		jQuery('html, body').animate({scrollTop: embedRowElement.offset().top}, 1000);
		
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
	jQuery('.wpcf7-pdf-forms-admin').on("click", '.help-button', function(event) {
		
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
	
	// set up "Show/Hide Tag Generator" button
	jQuery('.wpcf7-pdf-forms-admin').on("click", '.tag-generator-toggle-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var button = jQuery(this);
		var elements = button.closest(".tag-generator-tool").find('.tag-generator-body-element');
		
		if(elements.is(":visible"))
		{
			elements.hide();
			button.text(wpcf7_pdf_forms.__Show_Tag_Generator_Tool);
		}
		else
		{
			elements.show();
			button.text(wpcf7_pdf_forms.__Hide_Tag_Generator_Tool);
		}
		
		return false;
	});
	
	jQuery('.wpcf7-pdf-forms-admin .field-mapping-tool').on("click", '.convert-to-mailtags-button', function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var mapping_id = jQuery(this).data('mapping_id');
		
		var mappings = getMappings();
		for(var i=0, l=mappings.length; i<l; i++)
		{
			if(mappings[i].mapping_id == mapping_id)
			{
				mappings[i].mail_tags = '['+mappings[i].cf7_field+']';
				delete mappings[i].cf7_field;
				break;
			}
		}
		setMappings(mappings);
		refreshMappings();
	});
	
	jQuery('.wpcf7-pdf-forms-admin .field-mapping-tool').on("input change", 'textarea.mail-tags', function(event) {
		
		var mail_tags = jQuery(this).val();
		var mapping_id = jQuery(this).data('mapping_id');
		
		var mappings = getMappings();
		jQuery.each(mappings, function(index, mapping) {
			if(mapping.mapping_id == mapping_id)
			{
				mappings[index].mail_tags = mail_tags;
				return false; // break
			}
		});
		
		setMappings(mappings);
	});
	
	jQuery('.wpcf7-pdf-forms-admin .field-mapping-tool').on("input change", 'input.cf7-value', function(event) {
		
		var cf7_value = jQuery(this).val();
		var value_mapping_id = jQuery(this).data('value_mapping_id');
		
		var value_mappings = getValueMappings();
		for(var i=0, l=value_mappings.length; i<l; i++)
			if(value_mappings[i].value_mapping_id == value_mapping_id)
			{
				value_mappings[i].cf7_value = cf7_value;
				break;
			}
		
		setValueMappings(value_mappings);
	});
	
	jQuery('.wpcf7-pdf-forms-admin .field-mapping-tool').on("input change", 'input.pdf-value', function(event) {
		
		var pdf_value = jQuery(this).val();
		var value_mapping_id = jQuery(this).data('value_mapping_id');
		
		var value_mappings = getValueMappings();
		for(var i=0, l=value_mappings.length; i<l; i++)
			if(value_mappings[i].value_mapping_id == value_mapping_id)
			{
				value_mappings[i].pdf_value = pdf_value;
				break;
			}
		
		setValueMappings(value_mappings);
	});
	
	jQuery('.wpcf7-pdf-forms-settings-panel .image-embedding-tool').on("click", ".convert-to-mailtags-button", function(event) {
		
		// prevent running default button click handlers
		event.stopPropagation();
		event.preventDefault();
		
		var embed_id = jQuery(this).data('embed_id');
		
		var embeds = getEmbeds();
		for(var i=0, l=embeds.length; i<l; i++){
			if(embeds[i].id == embed_id)
			{
				embeds[i].mail_tags = '['+embeds[i].cf7_field+']';
				delete embeds[i].cf7_field;
				break;
			}
		}
		setEmbeds(embeds);
		refreshEmbeds();
	});
	
	jQuery('.wpcf7-pdf-forms-settings-panel .image-embedding-tool').on("input change", "textarea.mail-tags", function(event) {
		
		var mail_tags = jQuery(this).val();
		var embed_id = jQuery(this).data('embed_id');
		
		var embeds = getEmbeds();
		jQuery.each(embeds, function(index, embed) {
			if(embed.id == embed_id)
				embeds[index].mail_tags = mail_tags;
		});
		
		setEmbeds(embeds);
	});
	
	// set up a trigger for the form change event
	var changeHandler = function() { loadCf7Fields(removeOldMappingsAndEmbeds); };
	wpcf7_form.on('change', changeHandler);
	
	// set up a trigger for the tag generator insertion because change event isn't fired when value is changed with js
	var oldFormContent = "";
	var changeDetectTTL = 0; // don't let the loop run forever
	var changeDetectLoop = function() {
		// polling for change is needed because tag insertion may happen at a later time
		if(oldFormContent == wpcf7_form.val() && changeDetectTTL > 0)
			runWhenDone(changeDetectLoop);
		else
			changeHandler();
		changeDetectTTL--;
	};
	var changeDetect = function() {
		oldFormContent = wpcf7_form.val();
		changeDetectTTL = 10;
		changeDetectLoop();
	};
	jQuery('form.tag-generator-panel .insert-tag').on('click', changeDetect);
	
	// TODO: remove this workaround, determine what is causing the tag-hint not to be filled when tag generator dialog is opened
	if(tagGeneratorVersion == 2)
		jQuery('button[data-target="tag-generator-panel-pdf_form"]').on("click", function(event) {
			runWhenDone(function() { jQuery('.wpcf7-pdf-forms-tag-generator-panel .pdf-field-list').resetSelect2Field(); });
		});
	
	// auto-resizing textareas
	jQuery('.wpcf7-pdf-forms-admin').on("input change focus", "textarea.mail-tags", function() {
		if(this.scrollHeight > this.clientHeight)
		{
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		}
	});
	
	preloadData();
});
