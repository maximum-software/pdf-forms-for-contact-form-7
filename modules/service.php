<?php
	
	if( ! defined( 'ABSPATH' ) )
		return;
	
	abstract class WPCF7_Pdf_Forms_Service extends WPCF7_Service
	{
		public function api_get_fields( $attachment_id )
		{
			throw new Exception( __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_get_info( $attachment_id )
		{
			throw new Exception( __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_image( $destfile, $attachment_id, $page )
		{
			throw new Exception( __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_fill( $destfile, $attachment_id, $data, $options = array() )
		{
			throw new Exception( __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_fill_embed( $destfile, $attachment_id, $data, $embeds, $options = array() )
		{
			throw new Exception( __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function admin_notices() { }
	}
