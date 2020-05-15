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
		return __( 'Pdf.Ninja API', 'pdf-forms-for-contact-form-7' );
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
		{
			// don't try to get the key from the API on every page load!
			$fail = get_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
			if( $fail )
				throw new Exception( __( "Failed to get the Pdf.Ninja API key on last attempt.  Please retry manually.", 'pdf-forms-for-contact-form-7' ) );
			
			$this->set_key( $this->generate_key() );
		}
		
		return $this->key;
	}
	
	/*
	 * Sets the API key
	 */
	public function set_key( $value )
	{
		$this->key = $value;
		WPCF7::update_option( 'wpcf7_pdf_forms_pdfninja_key', $value );
		delete_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
		return true;
	}
	
	/*
	 * Requests a key from the API server
	 */
	public function generate_key()
	{
		try
		{
			$current_user = wp_get_current_user();
			if( ! $current_user )
				throw new Exception( __( "Failed to determine the current user.", 'pdf-forms-for-contact-form-7' ) );
			
			$email = sanitize_email( $current_user->user_email );
			if( ! $email )
				throw new Exception( __( "Failed to determine the current user's email address.", 'pdf-forms-for-contact-form-7' ) );
			
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
		catch(Exception $e)
		{
			set_transient( 'wpcf7_pdf_forms_pdfninja_key_failure', true, 12 * HOUR_IN_SECONDS );
			throw $e;
		}
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
	
	/*
	 * Generates common set of arguments to be used with remote http requests
	 */
	private function wp_remote_args()
	{
		return array(
			'headers'     => array( 'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8' ),
			'compress'    => true,
			'decompress'  => true,
			'timeout'     => 300,
			'redirection' => 5,
			'user-agent'  => 'wpcf7-pdf-forms/' . WPCF7_Pdf_Forms::VERSION,
			'sslverify'   => $this->get_verify_ssl(),
		);
	}
	
	/*
	 * Returns true if specified function is disabled with PHP security settings
	 */
	private function is_function_disabled( $function_name )
	{
		$disabled_functions = ini_get('disable_functions');
		if( $disabled_functions )
			return in_array( $function_name, array_map( 'trim', explode( ',', $disabled_functions ) ) );
		
		return false;
	}
	
	/*
	 * Returns true if the Enterprise Extension is supported on the system
	 */
	private $enterprise_extension_support_error = '';
	private function get_enterprise_extension_support()
	{
		$this->enterprise_extension_support_error = '';
		
		if( version_compare( PHP_VERSION, '5.3.0' ) < 0 )
		{
			$this->enterprise_extension_support_error = __( 'PHP version 5.3 or higher is required.', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		if( strncasecmp(PHP_OS, 'WIN', 3) == 0)
		{
			$this->enterprise_extension_support_error = __( 'Windows platform is not supported.', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		if( version_compare( PHP_VERSION, '5.4' ) < 0
			&& ini_get( 'safe_' . 'mode' ) // don't warn me about this, I know!
		)
		{
			$this->enterprise_extension_support_error = __( 'PHP safe mode is not supported.', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		if( $this->is_function_disabled( 'exec' )
		|| $this->is_function_disabled( 'proc_open' )
		|| $this->is_function_disabled( 'proc_close' ) )
		{
			$this->enterprise_extension_support_error = __( 'PHP execute functions (exec, proc_open, proc_close) are disabled.', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		exec( 'which which', $output, $retval );
		if( $retval !== 0 )
		{
			$this->enterprise_extension_support_error = __( 'PHP execute functions (exec, proc_open, proc_close) are disabled.', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		// check /proc/self/stat (required for pdftk)
		if( !file_exists( '/proc/self/stat' ) )
		{
			$this->enterprise_extension_support_error = __( 'Chroot environments are not supported', 'pdf-forms-for-contact-form-7' );
			return false;
		}
		
		$arch = php_uname( "m" );
		if( $arch == 'x86_64' )
			return true;
		
		$this->enterprise_extension_support_error = __( 'Required binaries are not available on this platform.', 'pdf-forms-for-contact-form-7' );
		return false;
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
			throw new Exception( __( "Failed to get API server response", 'pdf-forms-for-contact-form-7' ) );
		
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		
		if( strpos($content_type, 'application/json' ) !== FALSE )
		{
			$response = json_decode( $body , true );
			
			if( ! $response )
				throw new Exception( __( "Failed to decode API server response", 'pdf-forms-for-contact-form-7' ) );
			
			if( $response['success'] == true && isset( $response['fileUrl'] ) )
			{
				$args2 = $this->wp_remote_args();
				$args2['timeout'] = 100;
				$response2 = wp_remote_get( $response['fileUrl'], $args2 );
				if( is_wp_error( $response2 ) )
					throw new Exception( __( "Failed to download a file from the API server", 'pdf-forms-for-contact-form-7' ) );
				
				$response['content_type'] = wp_remote_retrieve_header( $response2, 'content-type' );
				$response['content'] = wp_remote_retrieve_body( $response2 );
			}
			
			return $response;
		}
		
		return array(
			'success' => true,
			'content_type' => $content_type,
			'content' => $body,
		);
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
		
		if( is_wp_error( $response ) )
			throw new Exception( implode( ', ', $response->get_error_messages() ) );
		
		$body = wp_remote_retrieve_body( $response );
		
		if( ! $body )
			throw new Exception( __( "Failed to get API server response", 'pdf-forms-for-contact-form-7' ) );
		
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		
		if( strpos($content_type, 'application/json' ) !== FALSE )
		{
			$response = json_decode( $body , true );
			
			if( ! $response )
				throw new Exception( __( "Failed to decode API server response", 'pdf-forms-for-contact-form-7' ) );
			
			if( $response['success'] == true && isset( $response['fileUrl'] ) )
			{
				$args2 = $this->wp_remote_args();
				$args2['timeout'] = 100;
				$response2 = wp_remote_get( $response['fileUrl'], $args2 );
				if( is_wp_error( $response2 ) )
					throw new Exception( __( "Failed to download a file from the API server", 'pdf-forms-for-contact-form-7' ) );
				
				$response['content_type'] = wp_remote_retrieve_header( $response2, 'content-type' );
				$response['content'] = wp_remote_retrieve_body( $response2 );
			}
			
			return $response;
		}
		
		return array(
			'success' => true,
			'content_type' => $content_type,
			'content' => $body,
		);
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
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
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
			$file_id = substr($attachment_id . "-" . get_site_url(), 0, 40);
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
			throw new Exception( __( "File not found", 'pdf-forms-for-contact-form-7' ) );
		
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
	public function api_get_info_helper( $endpoint, $attachment_id )
	{
		if( $this->is_new_file( $attachment_id ) )
			if( ! $this->api_upload_file( $attachment_id ) )
				return null;
		
		return $this->api_get( $endpoint, array(
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
		$result = $this->api_get_info_helper( 'fields', $attachment_id );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_get_info_helper( 'fields', $attachment_id );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( ! is_array( $result['fields'] ) )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		return $result['fields'];
	}
	
	/*
	 * Communicates with the API to obtain the PDF file information
	 */
	public function api_get_info( $attachment_id )
	{
		$result = $this->api_get_info_helper( 'info', $attachment_id );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_get_info_helper( 'info', $attachment_id );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( ! is_array( $result['fields'] ) ||  ! is_array( $result['pages'] ) )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		unset( $result['success'] );
		
		return $result;
	}
	
	/*
	 * Helper function for communicating with the API to fill fields in the PDF file
	 */
	private function api_image_helper( $attachment_id, $page )
	{
		if( $this->is_new_file( $attachment_id ) )
			if( ! $this->api_upload_file( $attachment_id ) )
				return null;
		
		$params = array(
			'fileId' => $this->get_file_id( $attachment_id ),
			'md5sum' => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'    => $this->get_key(),
			'type'   => 'jpeg',
			'page'   => intval($page),
			'dumpFile' => true,
		);
		
		return $this->api_get( 'image', $params );
	}
	
	/*
	 * Communicates with the API to get image of PDF pages
	 */
	public function api_image( $destfile, $attachment_id, $page )
	{
		$result = $this->api_image_helper( $attachment_id, $page );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_image_helper( $attachment_id, $page );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( !isset( $result['content'] ) || $result['content_type'] != 'image/jpeg' )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		$handle = @fopen( $destfile, 'w' );
		
		if( ! $handle )
			throw new Exception( __( "Failed to open file for writing", 'pdf-forms-for-contact-form-7' ) );
		
		fwrite( $handle, $result['content'] );
		fclose( $handle );
		
		if( ! file_exists( $destfile ) )
			throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		
		return true;
	}
	
	/*
	 * Helper function for communicating with the API to generate PDF file
	 */
	private function api_pdf_helper( $endpoint, $attachment_id, $data, $embeds, $options )
	{
		if( $this->is_new_file( $attachment_id ) )
			if( ! $this->api_upload_file( $attachment_id ) )
				return null;
		
		// prepare files and embed params
		$files = array();
		foreach( $embeds as $key => $embed )
		{
			$filepath = $embed['image'];
			if( !is_readable( $filepath ) )
			{
				unset( $embeds[$key] );
				continue;
			}
			$files[$filepath] = $filepath;
		}
		$files = array_values( $files );
		foreach( $embeds as &$embed )
		{
			$filepath = $embed['image'];
			$id = array_search($filepath, $files, $strict=true);
			if($id === FALSE)
				continue;
			$embed['image'] = $id;
		}
		unset($embed);
		
		$encoded_data = WPCF7_Pdf_Forms::json_encode( $data );
		if( $encoded_data === FALSE || $encoded_data === null )
			throw new Exception( __( "Failed to encode JSON data", 'pdf-forms-for-contact-form-7' ) );
		
		$encoded_embeds = WPCF7_Pdf_Forms::json_encode( $embeds );
		if( $encoded_embeds === FALSE || $encoded_embeds === null )
			throw new Exception( __( "Failed to encode JSON data", 'pdf-forms-for-contact-form-7' ) );
		
		$params = array(
			'fileId'   => $this->get_file_id( $attachment_id ),
			'md5sum'   => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'      => $this->get_key(),
			'data'     => $encoded_data,
			'embeds'   => $encoded_embeds,
			'dumpFile' => true,
		);
		
		foreach( $options as $key => $value )
		{
			if( $key == 'flatten' )
				$params[$key] = $value;
		}
		
		$boundary = wp_generate_password( 24 );
		
		$payload = "";
		
		foreach( $params as $name => $value )
			$payload .= "--{$boundary}\r\n"
			          . "Content-Disposition: form-data; name=\"{$name}\"\r\n"
			          . "\r\n"
			          . "{$value}\r\n";
		
		foreach( $files as $fileId => $filepath )
		{
			$filename = basename( $filepath );
			$filecontents = file_get_contents( $filepath );
			
			$payload .= "--{$boundary}\r\n"
					  . "Content-Disposition: form-data; name=\"images[{$fileId}]\"; filename=\"{$filename}\"\r\n"
					  . "Content-Type: application/octet-stream\r\n"
					  . "\r\n"
					  . "{$filecontents}\r\n";
		}
		
		$payload .= "--{$boundary}--";
		
		$headers  = array( 'Content-Type' => 'multipart/form-data; boundary=' . $boundary );
		$args = array( 'timeout' => 300 );
		
		return $this->api_post( $endpoint, $payload, $headers, $args );
	}
	
	/*
	 * Communicates with the API to fill fields in the PDF file
	 */
	public function api_fill( $destfile, $attachment_id, $data, $options = array() )
	{
		$result = $this->api_pdf_helper( 'fill', $attachment_id, $data, array(), $options );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_pdf_helper( 'fill', $attachment_id, $data, array(), $options );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( !isset( $result['content'] ) || $result['content_type'] != 'application/pdf' )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		$handle = @fopen( $destfile, 'w' );
		
		if( ! $handle )
			throw new Exception( __( "Failed to open file for writing", 'pdf-forms-for-contact-form-7' ) );
		
		fwrite( $handle, $result['content'] );
		fclose( $handle );
		
		if( ! file_exists( $destfile ) )
			throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		
		return true;
	}
	
	/*
	 * Communicates with the API to fill fields in the PDF file
	 */
	public function api_fill_embed( $destfile, $attachment_id, $data, $embeds, $options = array() )
	{
		$result = $this->api_pdf_helper( 'fillembed', $attachment_id, $data, $embeds, $options );
		
		if( $this->api_check_retry( $result, $attachment_id ) )
			$result = $this->api_pdf_helper( 'fillembed', $attachment_id, $data, $embeds, $options );
		
		if( $result['success'] != true )
			throw new Exception( $result['error'] );
		
		if( !isset( $result['content'] ) || $result['content_type'] != 'application/pdf' )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		$handle = @fopen( $destfile, 'w' );
		
		if( ! $handle )
			throw new Exception( __( "Failed to open file for writing", 'pdf-forms-for-contact-form-7' ) );
		
		fwrite( $handle, $result['content'] );
		fclose( $handle );
		
		if( ! file_exists( $destfile ) )
			throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		
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
						throw new Exception( __( "Permission denied", 'pdf-forms-for-contact-form-7' ) );
					
					$success = true;
					
					$api_url = isset( $_POST['api_url'] ) ? trim( wp_unslash( $_POST['api_url'] ) ) : null;
					if( $success && $this->get_api_url() != $api_url ) $success = $this->set_api_url( $api_url );
					
					$nosslverify = isset( $_POST['nosslverify'] ) ? trim( wp_unslash( $_POST['nosslverify'] ) ) : false;
					if( $success ) $success = $this->set_verify_ssl( !(bool)$nosslverify );
					
					if( isset( $_POST['new'] ) && $_POST['new'] )
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
		try { $key = $this->get_key(); } catch(Exception $e) { }
		
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_info', array(
			'top-message' => esc_html__( "This service provides functionality for working with PDF files via a web API.", 'pdf-forms-for-contact-form-7' ),
			'key-label' => esc_html__( 'API Key', 'pdf-forms-for-contact-form-7' ),
			'key' => esc_html( $key ),
			'key-copy-btn-label' => esc_html__( 'copy key', 'pdf-forms-for-contact-form-7' ),
			'key-copied-btn-label' => esc_html__( 'copied!', 'pdf-forms-for-contact-form-7' ),
			'api-url-label' => esc_html__( 'API URL', 'pdf-forms-for-contact-form-7' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'security-label' => esc_html__( 'Data Security', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'security-warning' => esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'pdf-forms-for-contact-form-7' ),
			'enterprise-extension-support-label' => esc_html__( 'Enterprise Extension', 'pdf-forms-for-contact-form-7' ),
			'enterprise-extension-support-value' => $this->get_enterprise_extension_support() ? esc_html__( 'Extension is supported.', 'pdf-forms-for-contact-form-7' ) : esc_html__( 'Extension is not supported.', 'pdf-forms-for-contact-form-7' ).' '.$this->enterprise_extension_support_error,
			'edit-label' => esc_html__( "Edit", 'pdf-forms-for-contact-form-7' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
		) );
	}
	
	/*
	 * Displays integration error web UI
	 */
	public function display_error()
	{
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_error', array(
			'top-message' => esc_html__( "Error!", 'pdf-forms-for-contact-form-7' ),
			'error-message' => esc_html( $this->error ),
			'edit-label' => esc_html__( "Edit", 'pdf-forms-for-contact-form-7' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
		) );
	}
	
	/*
	 * Displays integration edit web UI
	 */
	public function display_edit()
	{
		try { $key = $this->get_key(); } catch(Exception $e) { }
		
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_edit', array(
			'top-message' => esc_html__( "The following form allows you to edit your API key.", 'pdf-forms-for-contact-form-7' ),
			'key-label' => esc_html__( 'API Key', 'pdf-forms-for-contact-form-7' ),
			'key' => esc_html( $key ),
			'api-url-label' => esc_html__( 'API URL', 'pdf-forms-for-contact-form-7' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'security-label' => esc_html__( 'Data Security', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'security-warning' => esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'pdf-forms-for-contact-form-7' ),
			'edit-link' => esc_url( $this->menu_page_url( 'action=edit' ) ),
			'nonce' => wp_nonce_field( 'wpcf7-pdfninja-edit' ),
			'save-label' => esc_html__( "Save", 'pdf-forms-for-contact-form-7' ),
			'new-label' => esc_html__( "Get New Key", 'pdf-forms-for-contact-form-7' ),
		) );
	}
	
	/*
	 * WPCF7_Service defined function
	 */
	public function admin_notice( $message = '' )
	{
		if( 'error' == $message )
			echo WPCF7_Pdf_Forms::render( 'notice_error', array(
				'label' => esc_html__( "PDF Forms for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html__( "Can't save new key.", 'pdf-forms-for-contact-form-7' ),
			) );
		
		if( $this->error )
			echo WPCF7_Pdf_Forms::render( 'notice_error', array(
				'label' => esc_html__( "PDF Forms for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html( $this->error ),
			) );
		
		if( 'success' == $message )
			echo WPCF7_Pdf_Forms::render( 'notice_success', array(
				'message' => esc_html__( "Settings saved.", 'pdf-forms-for-contact-form-7' ),
			) );
	}
	
	/*
	 * This function gets called to display admin notices
	 */
	public function admin_notices()
	{
		try { $key = $this->get_key(); } catch(Exception $e) { };
		$fail = get_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
		if( isset( $fail ) && $fail )
			echo WPCF7_Pdf_Forms::render( 'notice_error', array(
				'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html__( "Failed to get the Pdf.Ninja API key on last attempt.  Please retry manually.", 'pdf-forms-for-contact-form-7' ),
			) );
	}
	
	/*
	 * Returns thickbox messages that need to be displayed
	 */
	public function thickbox_messages()
	{
		$messages = '';
		try
		{
			$url = $this->get_api_url();
			$verify_ssl = $this->get_verify_ssl();
			if( substr($url,0,5) == 'http:' || !$verify_ssl)
				$messages .= "<div class='notice notice-warning'><p>" . esc_html__( 'Warning: Your integration settings indicate you are using an insecure connection to the Pdf.Ninja API server.', 'pdf-forms-for-contact-form-7' ) . "</p></div>";
		}
		catch(Exception $e) { };
		
		return $messages;
	}
}
