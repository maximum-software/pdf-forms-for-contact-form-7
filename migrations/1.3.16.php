<?php
	
	if( ! defined( 'ABSPATH' ) )
		return;
	
	if( ! class_exists('WPCF7') )
		return;
	
	// if no Pdf.Ninja API version is set, set it to v1 to allow old forms to work in the same way as before
	$version = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_api_version' );
	if( ! $version )
		WPCF7::update_option( 'wpcf7_pdf_forms_pdfninja_api_version', 1 );
