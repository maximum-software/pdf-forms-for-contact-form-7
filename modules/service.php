<?php
	
	abstract class WPCF7_Pdf_Forms_Service extends WPCF7_Service
	{
		public function api_get_fields( $attachment_id, $options = array() )
		{
			throw new WPCF7_Pdf_Forms_Service_Exception( 'missingFeature', __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_get_info( $attachment_id, $options = array() )
		{
			throw new WPCF7_Pdf_Forms_Service_Exception( 'missingFeature', __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_image( $destfile, $attachment_id, $page, $options = array() )
		{
			throw new WPCF7_Pdf_Forms_Service_Exception( 'missingFeature', __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_fill( $destfile, $attachment_id, $data, $options = array() )
		{
			throw new WPCF7_Pdf_Forms_Service_Exception( 'missingFeature', __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function api_fill_embed( $destfile, $attachment_id, $data, $embeds, $options = array() )
		{
			throw new WPCF7_Pdf_Forms_Service_Exception( 'missingFeature', __( "Missing feature", 'pdf-forms-for-contact-form-7' ) );
		}
		
		public function admin_notices() { }
	}
	
	class WPCF7_Pdf_Forms_Service_Exception extends Exception
	{
		private $reason;
		public function __construct($reason=null, $message="", Exception $previous = null)
		{
			$this->reason = $reason;
			return parent::__construct($message, 0, $previous);
		}
		
		final public function getReason()
		{
			return $this->reason;
		}
	}
