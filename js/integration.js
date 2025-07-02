jQuery(document).ready(function($) {
	jQuery("#copy-pdf-ninja-key-btn").click(function() {
		var tmp = jQuery("<input>");
		jQuery("body").append(tmp);
		tmp.val(jQuery("#pdf-ninja-key").text()).select();
		document.execCommand("copy");
		tmp.remove();
		var btn = this;
		setTimeout(function() { jQuery(btn).text(wpcf7_pdf_forms_integration.copied_label); }, 1);
		setTimeout(function() { jQuery(btn).text(wpcf7_pdf_forms_integration.copy_key_label); }, 1000);
	});
});
