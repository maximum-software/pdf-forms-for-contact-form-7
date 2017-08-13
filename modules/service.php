<?php
	
	abstract class WPCF7_Pdf_Forms_Service extends WPCF7_Service
	{
		abstract public function api_get_fields( $attachment_id );
		abstract public function api_fill( $destfile, $attachment_id, $data );
		
		public function admin_notices()
		{
		}
	}
