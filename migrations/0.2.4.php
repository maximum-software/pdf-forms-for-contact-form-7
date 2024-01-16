<?php
	
	if( ! defined( 'ABSPATH' ) )
		return;
	
	if( ! class_exists('WPCF7') )
		return;
	
	function wpcf7_pdf_forms_migrate_option_value( $post_id, $attachment_id, $options )
	{
		$old_options = array( 'skip_empty' );
		
		foreach( $old_options as $option )
		{
			$value = get_post_meta( $attachment_id, 'wpcf7-pdf-forms-'.$post_id.'-'.$option, true );
			if($value !== '')
			{
				$value = json_decode( $value );
				$options[$option] = $value;
			}
		}
		
		WPCF7_Pdf_Forms::get_instance()->post_update_pdf( $post_id, $attachment_id, $options );
		
		foreach( $old_options as $option )
			delete_post_meta( $attachment_id, 'wpcf7-pdf-forms-'.$post_id.'-'.$option );
	}
	
	
	foreach( WPCF7_ContactForm::find() as $form )
	{
		$post_id = $form->id();
		
		foreach( WPCF7_Pdf_Forms::get_instance()->post_get_all_pdfs( $post_id ) as $attachment_id => $attachment )
			wpcf7_pdf_forms_migrate_option_value($post_id, $attachment_id, $attachment['options']);
	}
