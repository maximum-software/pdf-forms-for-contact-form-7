<?php

class WPCF7_Pdf_Ninja extends WPCF7_Pdf_Forms_Service
{
	private static $instance = null;
	private $key = null;
	private $api_url = null;
	private $verify_ssl = null;
	private $error = null;
	
	const API_URL = 'https://pdf.ninja';
	
	private function __construct() { }
	
	/*
	 * Returns a global instance of this class
	 */
	public static function get_instance()
	{
		if( !self::$instance )
			self::$instance = new self;
		
		return self::$instance;
	}
	
	/*
	 * Returns service name that this service provides
	 */
	public function get_service_name()
	{
		return 'pdf_ninja';
	}
	
	
	/*
	 * WPCF7_Service defined function
	 */
	public function get_title()
	{
		return __( 'Pdf.Ninja API', 'wpcf7-pdf-forms' );
	}
	
	/*
	 * WPCF7_Service defined function
	 */
	public function is_active()
	{
		try
		{
			$class = get_class();
			return ($this->get_key() != null) && (WPCF7_Pdf_Forms::get_instance()->get_service() instanceof $class);
		}
		catch(Exception $e)
		{
			$this->error = $e->getMessage();
			return false;
		}
	}
	
	/*
	 * WPCF7_Service defined function
	 */
	public function get_categories()
	{
		return array( 'pdf_forms' );
	}
	
	/*
	 * WPCF7_Service defined function
	 */
	public function icon() { }
	
	/*
	 * WPCF7_Service defined function
	 */
	public function link()
	{
		echo '<a href="https://pdf.ninja/">Pdf.Ninja</a>';
	}
	
	/*
	 * Returns (and initializes, if necessary) the current API key
	 */
	public function get_key()
	{
		if( ! $this->key )
			$this->key = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_key' );
		
		if( ! $this->key )
			$this->set_key( $this->generate_key() );
		
		return $this->key;
	}
	
	/*
	 * Sets the API key
	 */
	public function set_key( $value )
	{
		$this->key = $value;
		WPCF7::update_option( 'wpcf7_pdf_forms_pdfninja_key', $value );
		return true;
	}
	
	/*
	 * Requests a key from the API server
	 */
	public function generate_key()
	{
		$current_user = wp_get_current_user();
		
		if( ! $current_user )
			return null;
		
		$email = sanitize_email($current_user->user_email);
		
		if( ! $email )
			return null;
		
		$key = null;
		
		// try to get the key the normal way
		try { $key = $this->api_get_key( $email ); }
		catch(Exception $e)
		{
			// if we are not running for the first time, throw on error
			$old_key = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_key' );
			if( $old_key )
				throw $e;
			
			// there might be an issue with certificate verification on this system, disable it and try again
			$this->set_verify_ssl( false );
			try { $key = $this->api_get_key( $email ); }
			catch(Exception $e)
			{
				// if it still fails, revert and throw
				$this->set_verify_ssl( true );
				throw $e;
			}
		}
		
		return $key;
	}
	
	/*
	 * Returns (and initializes, if necessary) the current API URL
	 */
	public function get_api_url()
	{
		if( ! $this->api_url )
			$this->api_url = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_api_url' );
		
		if( ! $this->api_url )
			$this->set_api_url( self::API_URL );
		
		return $this->api_url;
	}
	
	/*
	 * Sets the current API URL
	 */
	public function set_api_url( $value )
	{
		$this->api_url = $value;
		WPCF7::update_option( 'wpcf7_pdf_forms_pdfninja_api_url', $value );
		return true;
	}
	
	/*
	 * Returns (and initializes, if necessary) the verify ssl setting
	 */
	public function get_verify_ssl()
	{
		if( $this->verify_ssl === null )
		{
			$value = WPCF7::get_option( 'wpcf7_pdf_forms_verify_ssl' );
			if( $value == 'true' ) $this->verify_ssl = true;
			if( $value == 'false' ) $this->verify_ssl = false;
		}
		
		if( $this->verify_ssl === null )
			$this->set_verify_ssl( true );
		
		return $this->verify_ssl;
	}
	
	/*
	 * Sets the verify ssl setting
	 */
	public function set_verify_ssl( $value )
	{
		$this->verify_ssl = $value;
		WPCF7::update_option( 'wpcf7_pdf_forms_verify_ssl', $value ? 'true' : 'false' );
		return true;
	}
	
	private function wp_remote_args()
	{
		return array(
			'headers'     => array( 'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8' ),
			'compress'    => true,
			'decompress'  => true,
			'timeout'     => 10,
			'redirection' => 5,
			'user-agent'  => 'wpcf-pdf-forms/' . WPCF7_Pdf_Forms::VERSION,
			'sslverify'   => $this->get_verify_ssl(),
		);
	}
	
	/*
	 * Helper function for communicating with the API via the GET request
	 */
	private function api_get( $endpoint, $params )
	{
		$url = add_query_arg( $params, $this->get_api_url() . '/api/v1/' . $endpoint );
		$response = wp_remote_get( $url, $this->wp_remote_args() );
		
		if( is_wp_error( $response ) )
			throw new Exception( implode( ', ', $response->get_error_messages() ) );
		
		$body = wp_remote_retrieve_body( $response );
		
		if( ! $body )
			throw new Exception( __( "Failed to get API server response", 'wpcf7-pdf-forms' ) );
		
		$response = json_decode( $body , true );
		
		if( ! $response )
			throw new Exception( __( "Failed to decode API server response", 'wpcf7-pdf-forms' ) );
		
		return $response;
	}
	
	/*
	 * Helper function for communicating with the API via the POST request
	 */
	private function api_post( $endpoint, $payload, $headers = array(), $args_override = array() )
	{
		$args = $this->wp_remote_args();
		
		$args['body'] = $payload;
		
		if( is_array( $headers ) )
			foreach( $headers as $key => $value )
				$args['headers'][$key] = $value;
		
		if( is_array( $args_override ) )
			foreach( $args_override as $key => $value )
				$args[$key] = $value;
		
		$response = wp_remote_post( $this->get_api_url() . '/api/v1/' . $endpoint, $args );
		
		$body = wp_remote_retrieve_body( $response );
		
		if( ! $body )
			throw new Exception( __( "Failed to get API server response", 'wpcf7-pdf-forms' ) );
		
		$response = json_decode( $body , true );
		
		if( ! $response )
			throw new Exception( __( "Failed to decode API server response", 'wpcf7-pdf-forms' ) );
		
		return $response;
	}
	
	/*
	 * Communicates with the API server to get a new key
	 */
	public function api_get_key( $email )
	{
		$result = $this->api_get('key', array( 'email' => $email ) );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( ! $result['key'])
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'wpcf7-pdf-forms' ) );
		
		return $result['key'];
	}
	
	/*
	 * Generates and returns file id to be used with the API server
	 */
	private function get_file_id( $attachment_id )
	{
		$file_id = WPCF7_Pdf_Forms::get_meta( $attachment_id, 'file_id' );
		if( ! $file_id )
		{
			$file_id = $attachment_id . "-" . get_site_url();
			return WPCF7_Pdf_Forms::set_meta( $attachment_id, 'file_id', $file_id );
		}
		else
			return $file_id;
	}
	
	/*
	 * Returns true if file hasn't yet been uploaded to the API server
	 */
	private function is_new_file( $attachment_id )
	{
		return WPCF7_Pdf_Forms::get_meta( $attachment_id, 'file_id' ) == null;
	}
	
	/*
	 * Communicates with the API to upload the media file
	 */
	public function api_upload_file( $attachment_id )
	{
		$md5sum = WPCF7_Pdf_Forms::update_attachment_md5sum( $attachment_id );
		
		$params = array(
			'fileId' => $this->get_file_id( $attachment_id ),
			'md5sum' => $md5sum,
			'key'    => $this->get_key(),
		);
		
		$boundary = wp_generate_password( 24 );
		
		$payload = "";
		
		foreach( $params as $name => $value )
			$payload .= "--{$boundary}\r\n"
			          . "Content-Disposition: form-data; name=\"{$name}\"\r\n"
			          . "\r\n"
			          . "{$value}\r\n";
		
		$filepath = get_attached_file( $attachment_id );
		
		if( ! file_exists( $filepath ) )
			throw new Exception( __( "File not found", 'wpcf7-pdf-forms' ) );
		
		$filename = basename( $filepath );
		$filecontents = file_get_contents( $filepath );
		
		$payload .= "--{$boundary}\r\n"
		          . "Content-Disposition: form-data; name=\"file\"; filename=\"{$filename}\"\r\n"
		          . "Content-Type: application/octet-stream\r\n"
		          . "\r\n"
		          . "{$filecontents}\r\n";
		
		$payload .= "--{$boundary}--";
		
		$headers  = array( 'Content-Type' => 'multipart/form-data; boundary=' . $boundary );
		$args = array( 'timeout' => 300 );
		
		$result = $this->api_post( 'file', $payload, $headers, $args );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		return true;
	}
	
	/*
	 * Returns true if we need to retry the action that works on the file
	 */
	private function api_check_retry( $previous_result, $attachment_id )
	{
		if( ! is_array( $previous_result ) )
			return false;
		
		// retry uploading the file
		// if file is gone from the API server
		// or if there is a md5 mismatch
		if( isset( $previous_result['reason'] ) )
			if( $previous_result['reason'] == 'noSuchFileId'
			||  $previous_result['reason'] == 'md5sumMismatch' )
				if( $this->api_upload_file( $attachment_id ) )
					return true;
		
		return false;
	}
	
	/*
	 * Helper function for communicating with the API to obtain the PDF file fields
	 */
	public function api_get_fields_helper( $attachment_id )
	{
		if( $this->is_new_file( $attachment_id ) )
			if( ! $this->api_upload_file( $attachment_id ) )
				return null;
		
		return $this->api_get( 'fields', array(
			'fileId' => $this->get_file_id( $attachment_id ),
			'md5sum' => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'    => $this->get_key(),
		) );
	}
	
	/*
	 * Communicates with the API to obtain the PDF file fields
	 */
	public function api_get_fields( $attachment_id )
	{
		$result = $this->api_get_fields_helper( $attachment_id );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_get_fields_helper( $attachment_id );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( ! is_array( $result['fields'] ) )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'wpcf7-pdf-forms' ) );
		
		return $result['fields'];
	}
	
	/*
	 * Helper function for communicating with the API to fill fields in the PDF file
	 */
	private function api_fill_helper( $attachment_id, $data )
	{
		if( $this->is_new_file( $attachment_id ) )
			if( ! $this->api_upload_file( $attachment_id ) )
				return null;
		
		$encoded_data = WPCF7_Pdf_Forms::json_encode( $data );
		if( $encoded_data === FALSE || $encoded_data === null )
			throw new Exception( __( "Failed to encode JSON data", 'wpcf7-pdf-forms' ) );
		
		$params = array(
			'fileId' => $this->get_file_id( $attachment_id ),
			'md5sum' => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'    => $this->get_key(),
			'data'   => $encoded_data
		);
		
		return $this->api_post( 'fill', $params );
	}
	
	/*
	 * Communicates with the API to fill fields in the PDF file
	 */
	public function api_fill( $destfile, $attachment_id, $data )
	{
		$result = $this->api_fill_helper( $attachment_id, $data );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_fill_helper( $attachment_id, $data );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( ! $result['fileUrl'] )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'wpcf7-pdf-forms' ) );
		
		$args = $this->wp_remote_args();
		$args['timeout'] = 100;
		$response = wp_remote_get( $result['fileUrl'], $args );
		if( is_wp_error( $response ) )
			throw new Exception( __( "Cannot download PDF file from the API server", 'wpcf7-pdf-forms' ) );
		
		$handle = @fopen( $destfile, 'w' );
		
		if( ! $handle )
			throw new Exception( __( "Cannot open temporary PDF file for writing", 'wpcf7-pdf-forms' ) );
		
		fwrite( $handle, $response['body'] );
		fclose( $handle );
		
		if( ! file_exists( $destfile ) )
			throw new Exception( __( "Cannot create temporary PDF file", 'wpcf7-pdf-forms' ) );
		
		return true;
	}
	
	/*
	 * Helper function for getting menu page URL
	 */
	private function menu_page_url( $args = '' )
	{
		$args = wp_parse_args( $args, array() );
		
		$url = menu_page_url( 'wpcf7-integration', false );
		$url = add_query_arg( array( 'service' => $this->get_service_name() ), $url );
		
		if ( ! empty( $args ) )
			$url = add_query_arg( $args, $url );
		
		return $url;
	}
	
	/*
	 * WPCF7_Service defined function used to process integration POST requests
	 */
	public function load( $action = '' )
	{
		if( 'edit' == $action )
		{
			if( 'POST' == $_SERVER['REQUEST_METHOD'] )
			{
				try
				{
					check_admin_referer( 'wpcf7-pdfninja-edit' );
					
					if ( ! current_user_can( 'wpcf7_manage_integration' ) )
						throw new Exception( __( "Permission denied", 'wpcf7-pdf-forms' ) );
					
					$success = true;
					
					$api_url = isset( $_POST['api_url'] ) ? trim( wp_unslash( $_POST['api_url'] ) ) : null;
					if( $success && $this->get_api_url() != $api_url ) $success = $this->set_api_url( $api_url );
					
					$nosslverify = isset( $_POST['nosslverify'] ) ? trim( wp_unslash( $_POST['nosslverify'] ) ) : false;
					if( $success ) $success = $this->set_verify_ssl( !(bool)$nosslverify );
					
					if( $_POST['new'] )
						$key = $this->generate_key();
					else
						$key = isset( $_POST['key'] ) ? trim( wp_unslash( $_POST['key'] ) ) : null;
					if( $success && $key ) $success = $this->set_key( $key );
					
					if( $success )
						wp_safe_redirect( $this->menu_page_url( array( 'message' => 'success' ) ) );
				}
				catch(Exception $e)
				{
					$this->error = $e->getMessage();
				}
			}
		}
	}
	
	/*
	 * WPCF7_Service defined function used to display integration web UI
	 */
	public function display( $action = '' )
	{
		try
		{
			if( 'edit' == $action )
				return $this->display_edit();
			
			if( ! $this->is_active() && $this->error )
				return $this->display_error();
			
			return $this->display_info();
		}
		catch(Exception $e)
		{
			$this->error = $e->getMessage();
			return $this->display_error();
		}
	}
	
	/*
	 * Displays integration info web UI
	 */
	public function display_info()
	{
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_info', array(
			'top-message' => esc_html__( "This service provides functionality for working with PDF forms via a web API.", 'wpcf7-pdf-forms' ),
			'key-label' => esc_html__( 'API Key', 'wpcf7-pdf-forms' ),
			'key' => esc_html( $this->get_key() ),
			'api-url-label' => esc_html__( 'API URL', 'wpcf7-pdf-forms' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'security-label' => esc_html__( 'Data Security', 'wpcf7-pdf-forms' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'wpcf7-pdf-forms' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'security-warning' => esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'wpcf7-pdf-forms' ),
			'edit-label' => esc_html__( "Edit", 'wpcf7-pdf-forms' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
		) );
	}
	
	/*
	 * Displays integration error web UI
	 */
	public function display_error()
	{
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_error', array(
			'top-message' => esc_html__( "Error!", 'wpcf7-pdf-forms' ),
			'error-message' => esc_html( $this->error ),
			'edit-label' => esc_html__( "Edit", 'wpcf7-pdf-forms' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
		) );
	}
	
	/*
	 * Displays integration edit web UI
	 */
	public function display_edit()
	{
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_edit', array(
			'top-message' => esc_html__( "The following form allows you to edit your API key.", 'wpcf7-pdf-forms' ),
			'key-label' => esc_html__( 'API Key', 'wpcf7-pdf-forms' ),
			'key' => esc_html( $this->get_key() ),
			'api-url-label' => esc_html__( 'API URL', 'wpcf7-pdf-forms' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'security-label' => esc_html__( 'Data Security', 'wpcf7-pdf-forms' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'wpcf7-pdf-forms' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'security-warning' => esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'wpcf7-pdf-forms' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
			'nonce' => wp_nonce_field( 'wpcf7-pdfninja-edit' ),
			'save-label' => esc_html__( "Save", 'wpcf7-pdf-forms' ),
			'new-label' => esc_html__( "Get New Key", 'wpcf7-pdf-forms' ),
		) );
	}
	
	/*
	 * WPCF7_Service defined function
	 */
	public function admin_notice( $message = '' )
	{
		if( 'error' == $message )
			echo WPCF7_Pdf_Forms::render( 'notice_error', array(
				'label' => esc_html__( "PDF Forms for CF7 plugin error", 'wpcf7-pdf-forms' ),
				'message' => esc_html__( "Can't save new key.", 'wpcf7-pdf-forms' ),
			) );
		
		if( $this->error )
			echo WPCF7_Pdf_Forms::render( 'notice_error', array(
				'label' => esc_html__( "PDF Forms for CF7 plugin error", 'wpcf7-pdf-forms' ),
				'message' => esc_html( $this->error ),
			) );
		
		if( 'success' == $message )
			echo WPCF7_Pdf_Forms::render( 'notice_success', array(
				'message' => esc_html__( "Key saved.", 'wpcf7-pdf-forms' ),
			) );
	}
	
	public function admin_notices()
	{
		try { $key = $this->get_key(); } catch(Exception $e) { };
		if( ! $key )
		echo WPCF7_Pdf_Forms::render( 'notice_error', array(
			'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'wpcf7-pdf-forms' ),
			'message' => esc_html__( "Could not get a Pdf.Ninja API key.", 'wpcf7-pdf-forms' ),
		) );
	}
	
	public function thickbox_messages()
	{
		$messages = '';
		try
		{
			$url = $this->get_api_url();
			$verify_ssl = $this->get_verify_ssl();
			if( substr($url,0,5) == 'http:' || !$verify_ssl)
				$messages .= "<p>" . esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'wpcf7-pdf-forms' ) . "</p>";
		}
		catch(Exception $e) { };
		
		return $messages;
	}
}
