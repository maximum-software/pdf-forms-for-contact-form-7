<?php

if( ! defined( 'ABSPATH' ) )
	return;

class WPCF7_Pdf_Ninja extends WPCF7_Pdf_Forms_Service
{
	private static $instance = null;
	private $key = null;
	private $api_url = null;
	private $api_version = null;
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
			$class = __CLASS__;
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
		if( $this->key )
			return $this->key;
		
		if( ! $this->key )
			$this->key = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_key' );
		
		if( ! $this->key )
		{
			// attempt to get the key from another plugin
			$key = $this->get_external_key();
			if( $key )
				$this->set_key( $key );
		}
		
		if( ! $this->key )
		{
			// don't try to get the key from the API on every page load!
			$fail = get_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
			if( $fail )
				throw new Exception( __( "Failed to get the Pdf.Ninja API key on last attempt.", 'pdf-forms-for-contact-form-7' ) );
			
			// create new key if it hasn't yet been set
			try
			{
				$key = $this->generate_key();
			}
			catch(Exception $e)
			{
				set_transient( 'wpcf7_pdf_forms_pdfninja_key_failure', true, 12 * HOUR_IN_SECONDS );
				throw $e;
			}
			
			$this->set_key( $key );
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
	
	/**
	 * Searches for key in other plugins
	 */
	public function get_external_key()
	{
		// from PDF Forms Filler for WPForms
		$option = get_option( 'wpforms_settings' );
		if( $option !== false && is_array( $option ) && isset( $option['pdf-ninja-api_key'] ) )
			return $option['pdf-ninja-api_key'];
		
		// from PDF Forms Filler for WooCommerce
		$option = get_option( 'pdf-forms-for-woocommerce-settings-pdf-ninja-api-key' );
		if( $option !== false )
			return $option;
		
		return null;
	}
	
	/*
	 * Determines administrator's email address (for use with requesting a new key from the API)
	 */
	private function get_admin_email()
	{
		$current_user = wp_get_current_user();
		if( ! $current_user )
			return null;
		
		$email = sanitize_email( $current_user->user_email );
		if( ! $email )
			return null;
		
		return $email;
	}
	
	/*
	 * Requests a key from the API server
	 */
	public function generate_key( $email = null )
	{
		if( $email === null )
			$email = $this->get_admin_email();
		
		if( $email === null )
			throw new Exception( __( "Failed to determine the administrator's email address.", 'pdf-forms-for-contact-form-7' ) );
		
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
	 * Returns (and initializes, if necessary) the api version setting
	 */
	public function get_api_version()
	{
		if( $this->api_version === null )
		{
			$value = WPCF7::get_option( 'wpcf7_pdf_forms_pdfninja_api_version' );
			if( $value == '1' ) $this->api_version = 1;
			if( $value == '2' ) $this->api_version = 2;
		}
		
		if( $this->api_version === null )
			$this->api_version = 2; // default
		
		return $this->api_version;
	}
	
	/*
	 * Sets the api version setting
	 */
	public function set_api_version( $value )
	{
		if( $value == 1 || $value == 2 )
		{
			$this->api_version = $value;
			WPCF7::update_option( 'wpcf7_pdf_forms_pdfninja_api_version', intval( $value ) );
		}
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
			'headers'     => array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
				'Referer' => home_url(),
			),
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
		if( !function_exists( $function_name ) )
			return true;
		
		$disabled_functions = ini_get('disable_functions');
		if( $disabled_functions )
			return in_array( $function_name, array_map( 'trim', explode( ',', $disabled_functions ) ) );
		
		return false;
	}
	
	/*
	 * Returns true if the Enterprise Extension is supported on the system
	 */
	private $enterprise_extension_support = null;
	private function get_enterprise_extension_support()
	{
		if( $this->enterprise_extension_support !== null )
			return $this->enterprise_extension_support;
		
		$this->enterprise_extension_support = '';
		
		$errors = array( 1 => array(), 2 => array() );
		$warnings = array( 1 => array(), 2 => array() );
		
		try
		{
			$required_php_version = '5.4.0';
			if( version_compare( PHP_VERSION, $required_php_version ) < 0 )
			{
				$error = WPCF7_Pdf_Forms::replace_tags(
					__( 'PHP {version} or higher is required.', 'pdf-forms-for-contact-form-7' ),
					array( 'version' => $required_php_version )
				);
				$errors[1][] = $error;
				$errors[2][] = $error;
			}
			
			if( strncasecmp(PHP_OS, 'WIN', 3) == 0 )
			{
				$error = __( 'Windows platform is not supported.', 'pdf-forms-for-contact-form-7' );
				$errors[1][] = $error;
				$errors[2][] = $error;
			}
			
			if( $this->is_function_disabled( 'finfo_open' ) && $this->is_function_disabled( 'mime_content_type' ) )
			{
				$error = __( 'PHP Fileinfo is disabled.', 'pdf-forms-for-contact-form-7' );
				$errors[1][] = $error;
				$errors[2][] = $error;
			}
			
			$min_kernel_version = array( 'Linux' => '3.2.0' );
			$matches = array();
			if( isset( $min_kernel_version[PHP_OS] ) && preg_match('/(\d+)(?:\.\d+)+/', php_uname( 'r' ), $matches) == 1 )
			{
				$cur_kernel_version = $matches[0];
				if( !$cur_kernel_version || version_compare( $cur_kernel_version, $min_kernel_version[PHP_OS] ) < 0 )
				{
					if(!$cur_kernel_version)
						$cur_kernel_version = __( 'unknown', 'pdf-forms-for-contact-form-7' );
					$error = WPCF7_Pdf_Forms::replace_tags(
						__( 'Minimum kernel version supported is {min-kernel-version}, current kernel version is {cur-kernel-version}.', 'pdf-forms-for-contact-form-7' ),
						array(
							'min-kernel-version' => $min_kernel_version[PHP_OS],
							'cur-kernel-version' => $cur_kernel_version,
						)
					);
					$errors[1][] = $error;
					$errors[2][] = $error;
				}
			}
			else
			{
				$warning = __( 'Warning: Failed to detect kernel version support.', 'pdf-forms-for-contact-form-7' );
				$warnings[1][] = $warning;
				$warnings[2][] = $warning;
			}
			
			if( $this->is_function_disabled( 'exec' ) )
			{
				$error = __( 'PHP execute function (exec) is disabled.', 'pdf-forms-for-contact-form-7' );
				$errors[1][] = $error;
				$errors[2][] = $error;
			}
			else
			{
				$output = null;
				$retval = -1;
				@exec( 'echo works', $output, $retval );
				if( $retval !== 0 || ! is_array( $output ) || $output[0] != 'works' )
				{
					$error = __( 'PHP execute function (exec) is not working.', 'pdf-forms-for-contact-form-7' );
					$errors[1][] = $error;
					$errors[2][] = $error;
				}
				else
				{
					// check /proc/self/stat (required by pdftk)
					if( ini_get('open_basedir') )
					{
						// if open_basedir is set then use exec to check access to the /proc filesystem
						$output = null;
						$retval = -1;
						@exec( 'cat /proc/self/stat', $output, $retval );
						$proc_accessible = $retval === 0 && is_array( $output );
					}
					else
						$proc_accessible = @file_exists( '/proc/self/stat' );
					if( ! $proc_accessible )
						$errors[1][] = __( 'Hosting environments with no access to /proc/self/stat are not supported.', 'pdf-forms-for-contact-form-7' );
					
					$getenforce = null;
					$retval = -1;
					@exec( 'getenforce', $getenforce, $retval );
					if( $retval === 0 && is_array( $getenforce ) && isset( $getenforce[0] ) && trim( $getenforce[0] ) != "Disabled" ) // TODO: fix localization
					{
						$warning = __( 'Warning: SELinux may cause problems with using required binaries. You may need to turn off SELinux or adjust its policies.', 'pdf-forms-for-contact-form-7' );
						$warnings[1][] = $warning;
						$warnings[2][] = $warning;
					}
				}
			}
			
			if( $this->is_function_disabled( 'escapeshellarg' ) )
			{
				$error = __( 'PHP function `escapeshellarg()` is disabled.', 'pdf-forms-for-contact-form-7' );
				$errors[1][] = $error;
				$errors[2][] = $error;
			}
			
			$arch = php_uname( "m" );
			if( $arch != 'x86_64' && $arch != 'amd64' )
			{
				$warnings[1][] = __( 'Warning: Bundled binaries are not available for this platform. Enterprise Extension 1 can use pdftk/qpdf/poppler/imagemagick package binaries ONLY if they are installed on the server system-wide.', 'pdf-forms-for-contact-form-7' );
				$errors[2][] = __( 'Enterprise Extension 2 is not available for this platform.', 'pdf-forms-for-contact-form-7' );
			}
		}
		catch(Exception $e)
		{
			$errors[1][] = $e->getMessage();
			$errors[2][] = $e->getMessage();
		}
		
		$common_errors = array_intersect( $errors[1], $errors[2] );
		$filtered_errors = array();
		foreach( $errors as $version => $version_errors )
			$filtered_errors[$version] = array_diff( $version_errors, $common_errors );
		$common_warnings = array_intersect( $warnings[1], $warnings[2] );
		$filtered_warnings = array();
		foreach( $warnings as $version => $version_warnings )
			$filtered_warnings[$version] = array_diff( $version_warnings, $common_warnings );
		
		if(count($errors[1]) == 0)
			$this->enterprise_extension_support .= __( 'Enterprise Extension 1 is supported.', 'pdf-forms-for-contact-form-7' ).' ';
		else
			$this->enterprise_extension_support .= __( 'Enterprise Extension 1 is not supported.', 'pdf-forms-for-contact-form-7' ).' ';
		foreach($filtered_errors[1] as $error)
			$this->enterprise_extension_support .= "$error ";
		foreach($filtered_warnings[1] as $warning)
			$this->enterprise_extension_support .= "$warning ";
		if(count($errors[2]) == 0)
			$this->enterprise_extension_support .= __( 'Enterprise Extension 2 is supported.', 'pdf-forms-for-contact-form-7' ).' ';
		else
			$this->enterprise_extension_support .= __( 'Enterprise Extension 2 is not supported.', 'pdf-forms-for-contact-form-7' ).' ';
		foreach($filtered_errors[2] as $error)
			$this->enterprise_extension_support .= "$error ";
		foreach($filtered_warnings[2] as $warning)
			$this->enterprise_extension_support .= "$warning ";
		
		foreach($common_errors as $error)
			$this->enterprise_extension_support .= "$error ";
		foreach($common_warnings as $warning)
			$this->enterprise_extension_support .= "$warning ";
		
		$this->enterprise_extension_support = WPCF7_Pdf_Forms::mb_trim($this->enterprise_extension_support);
		
		return $this->enterprise_extension_support;
	}
	
	/*
	 * Helper function for processing the API response
	 */
	private function api_process_response( $response )
	{
		if( is_wp_error( $response ) )
		{
			$errors = $response->get_error_messages();
			foreach($errors as &$error)
				if( stripos( $error, 'cURL error 7' ) !== false )
					$error = WPCF7_Pdf_Forms::replace_tags(
							__( "Failed to connect to {url}", 'pdf-forms-for-contact-form-7' ),
							array( 'url' => $this->get_api_url() )
						);
			throw new Exception( implode( ', ', $errors ) );
		}
		
		$body = wp_remote_retrieve_body( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		
		if( strpos($content_type, 'application/json' ) !== FALSE )
		{
			$result = json_decode( $body , true );
			
			if( ! $result || ! is_array( $result ) )
				throw new Exception( __( "Failed to decode API server response", 'pdf-forms-for-contact-form-7' ) );
			
			if( ! isset( $result['success'] ) || ( $result['success'] === false && ! isset( $result['error'] ) ) )
				throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
			
			if( $result['success'] === false )
				throw new WPCF7_Pdf_Ninja_Exception( $result );
			
			if( $result['success'] == true && isset( $result['fileUrl'] ) )
			{
				$args2 = $this->wp_remote_args();
				$args2['timeout'] = 100;
				$response2 = wp_remote_get( $result['fileUrl'], $args2 );
				if( is_wp_error( $response2 ) )
					throw new Exception( __( "Failed to download a file from the API server", 'pdf-forms-for-contact-form-7' ) );
				
				$result['content_type'] = wp_remote_retrieve_header( $response2, 'content-type' );
				$result['content'] = wp_remote_retrieve_body( $response2 );
			}
		}
		else
		{
			if( wp_remote_retrieve_response_code( $response ) < 400 )
				$result = array(
					'success' => true,
					'content_type' => $content_type,
					'content' => $body,
				);
			else
				$result = array(
					'success' => false,
					'error' => wp_remote_retrieve_response_message( $response ),
				);
		}
		
		return $result;
	}
	
	/*
	 * Helper function that retries GET request if the file needs to be re-uploaded or md5 sum recalculated
	 */
	private function api_get_retry_attachment( $attachment_id, $endpoint, $params )
	{
		try
		{
			return $this->api_get( $endpoint, $params );
		}
		catch( WPCF7_Pdf_Ninja_Exception $e )
		{
			$reason = $e->getReason();
			if( $reason == 'noSuchFileId' || $reason == 'md5sumMismatch' )
			{
				if( $this->is_local_attachment( $attachment_id ) )
					$this->api_upload_file( $attachment_id );
				else
					// update local md5sum
					$params['md5sum'] = WPCF7_Pdf_Forms::update_attachment_md5sum( $attachment_id );
				
				return $this->api_get( $endpoint, $params );
			}
			throw $e;
		}
	}
	
	/*
	 * Helper function that retries POST request if the file needs to be re-uploaded or md5 sum recalculated
	 */
	private function api_post_retry_attachment( $attachment_id, $endpoint, $payload, $headers = array(), $args_override = array() )
	{
		try
		{
			return $this->api_post( $endpoint, $payload, $headers, $args_override );
		}
		catch( WPCF7_Pdf_Ninja_Exception $e )
		{
			$reason = $e->getReason();
			if( $reason == 'noSuchFileId' || $reason == 'md5sumMismatch' )
			{
				if( $this->is_local_attachment( $attachment_id ) )
					$this->api_upload_file( $attachment_id );
				else
					// update local md5sum
					$params['md5sum'] = WPCF7_Pdf_Forms::update_attachment_md5sum( $attachment_id );
				
				return $this->api_post( $endpoint, $payload, $headers, $args_override );
			}
			throw $e;
		}
	}
	
	/*
	 * Helper function for communicating with the API via the GET request
	 */
	private function api_get( $endpoint, $params )
	{
		$url = add_query_arg( $params, $this->get_api_url() . "/api/v" . $this->get_api_version() . "/" . $endpoint );
		$response = wp_remote_get( $url, $this->wp_remote_args() );
		return $this->api_process_response( $response );
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
		
		$url = $this->get_api_url() . "/api/v" . $this->get_api_version() . "/" . $endpoint;
		$response = wp_remote_post( $url, $args );
		return $this->api_process_response( $response );
	}
	
	/*
	 * Communicates with the API server to get a new key
	 */
	public function api_get_key( $email )
	{
		$result = $this->api_get( 'key', array( 'email' => $email ) );
		
		if( ! isset( $result['key'] ) )
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
			$file_id = substr( $attachment_id . "-" . get_site_url(), 0, 40 );
			WPCF7_Pdf_Forms::set_meta( $attachment_id, 'file_id', $file_id );
		}
		return substr( $file_id, 0, 40 );
	}
	
	/*
	 * Returns true if file hasn't yet been uploaded to the API server
	 */
	private function is_new_file( $attachment_id )
	{
		return WPCF7_Pdf_Forms::get_meta( $attachment_id, 'file_id' ) == null;
	}
	
	/*
	 * Returns true if attachment file is on the local file system
	 */
	private function is_local_attachment( $attachment_id )
	{
		$filepath = get_attached_file( $attachment_id );
		return $filepath !== false && is_readable( $filepath ) !== false;
	}
	
	/*
	 * Returns file URL to be used with the API server
	 */
	private function get_file_url( $attachment_id )
	{
		$fileurl = wp_get_attachment_url( $attachment_id );
		
		if( $fileurl === false )
			throw new Exception( __( "Unknown attachment URL", 'pdf-forms-for-contact-form-7' ) );
		
		return $fileurl;
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
		
		$boundary = wp_generate_password( 48, $special_chars = false, $extra_special_chars = false );
		
		$payload = "";
		
		foreach( $params as $name => $value )
			$payload .= "--{$boundary}\r\n"
			          . "Content-Disposition: form-data; name=\"{$name}\"\r\n"
			          . "\r\n"
			          . "{$value}\r\n";
		
		if( ! $this->is_local_attachment( $attachment_id ) )
			throw new Exception( __( "File is not accessible in the local file system", 'pdf-forms-for-contact-form-7' ) );
		
		$filepath = get_attached_file( $attachment_id );
		$filename = wp_basename( $filepath );
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
	 * Helper function for communicating with the API to obtain the PDF file fields
	 */
	public function api_get_info_helper( $endpoint, $attachment_id )
	{
		$params = array(
			'md5sum' => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'    => $this->get_key(),
		);
		
		if( $this->is_local_attachment( $attachment_id ) )
		{
			if( $this->is_new_file( $attachment_id ) )
				$this->api_upload_file( $attachment_id );
			$params['fileId'] = $this->get_file_id( $attachment_id );
		}
		else
			$params['fileUrl'] = $this->get_file_url( $attachment_id );
		
		return $this->api_get_retry_attachment( $attachment_id, $endpoint, $params );
	}
	
	/*
	 * Communicates with the API to obtain the PDF file fields
	 */
	public function api_get_fields( $attachment_id )
	{
		$result = $this->api_get_info_helper( 'fields', $attachment_id );
		
		if( ! isset( $result['fields'] ) || ! is_array( $result['fields'] ) )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		return $result['fields'];
	}
	
	/*
	 * Communicates with the API to obtain the PDF file information
	 */
	public function api_get_info( $attachment_id )
	{
		$result = $this->api_get_info_helper( 'info', $attachment_id );
		
		if( ! isset( $result['fields'] ) || ! isset( $result['pages'] ) || ! is_array( $result['fields'] ) || ! is_array( $result['pages'] ) )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		unset( $result['success'] );
		
		return $result;
	}
	
	/*
	 * Communicates with the API to get image of PDF pages
	 */
	public function api_image( $destfile, $attachment_id, $page )
	{
		$params = array(
			'md5sum' => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'    => $this->get_key(),
			'type'   => 'jpeg',
			'page'   => intval($page),
			'dumpFile' => true,
		);
		
		if( $this->is_local_attachment( $attachment_id ) )
		{
			if( $this->is_new_file( $attachment_id ) )
				$this->api_upload_file( $attachment_id );
			$params['fileId'] = $this->get_file_id( $attachment_id );
		}
		else
			$params['fileUrl'] = $this->get_file_url( $attachment_id );
		
		$result = $this->api_get_retry_attachment( $attachment_id, 'image', $params );
		
		if( ! isset( $result['content'] ) || ! isset( $result['content_type'] ) || $result['content_type'] != 'image/jpeg' )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		if( file_put_contents( $destfile, $result['content'] ) === false || ! is_file( $destfile ) )
			throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		
		return true;
	}
	
	/*
	 * Helper function for communicating with the API to generate PDF file
	 */
	private function api_pdf_helper( $destfile, $endpoint, $attachment_id, $data, $embeds, $options )
	{
		if( !is_array ( $data ) )
			$data = array();
		
		if( !is_array ( $embeds ) )
			$embeds = array();
		
		if( !is_array ( $options ) )
			$options = array();
		
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
			'md5sum'   => WPCF7_Pdf_Forms::get_attachment_md5sum( $attachment_id ),
			'key'      => $this->get_key(),
			'data'     => $encoded_data,
			'embeds'   => $encoded_embeds,
			'dumpFile' => true,
		);
		
		if( $this->is_local_attachment( $attachment_id ) )
		{
			if( $this->is_new_file( $attachment_id ) )
				$this->api_upload_file( $attachment_id );
			$params['fileId'] = $this->get_file_id( $attachment_id );
		}
		else
			$params['fileUrl'] = $this->get_file_url( $attachment_id );
		
		foreach( $options as $key => $value )
		{
			if( $key == 'flatten' )
				$params[$key] = $value;
		}
		
		$boundary = wp_generate_password( 48, $special_chars = false, $extra_special_chars = false );
		
		$payload = "";
		
		foreach( $params as $name => $value )
			$payload .= "--{$boundary}\r\n"
			          . "Content-Disposition: form-data; name=\"{$name}\"\r\n"
			          . "\r\n"
			          . "{$value}\r\n";
		
		foreach( $files as $fileId => $filepath )
		{
			$filename = wp_basename( $filepath );
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
		
		$result = $this->api_post_retry_attachment( $attachment_id, $endpoint, $payload, $headers, $args );
		
		if( ! isset( $result['content'] ) || ! isset( $result['content_type'] ) || $result['content_type'] != 'application/pdf' )
			throw new Exception( __( "Pdf.Ninja API server did not send an expected response", 'pdf-forms-for-contact-form-7' ) );
		
		if( file_put_contents( $destfile, $result['content'] ) === false || ! is_file( $destfile ) )
			throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		
		return true;
	}
	
	/*
	 * Communicates with the API to fill fields in the PDF file
	 */
	public function api_fill( $destfile, $attachment_id, $data, $options = array() )
	{
		return $this->api_pdf_helper( $destfile, 'fill', $attachment_id, $data, array(), $options );
	}
	
	/*
	 * Communicates with the API to fill fields in the PDF file
	 */
	public function api_fill_embed( $destfile, $attachment_id, $data, $embeds, $options = array() )
	{
		return $this->api_pdf_helper( $destfile, 'fillembed', $attachment_id, $data, $embeds, $options );
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
					
					if( isset( $_POST['save'] ) && $_POST['save'] )
					{
						$api_url = isset( $_POST['api_url'] ) ? trim( wp_unslash( $_POST['api_url'] ) ) : null;
						if( $success && $this->get_api_url() != $api_url ) $success = $this->set_api_url( $api_url );
						
						$api_version = isset( $_POST['api-version'] ) ? trim( wp_unslash( $_POST['api-version'] ) ) : false;
						if( $success && $this->get_api_version() != $api_version ) $success = $this->set_api_version( $api_version );
						
						$nosslverify = isset( $_POST['nosslverify'] ) ? trim( wp_unslash( $_POST['nosslverify'] ) ) : false;
						if( $success ) $success = $this->set_verify_ssl( !(bool)$nosslverify );
						
						$key = isset( $_POST['key'] ) ? trim( wp_unslash( $_POST['key'] ) ) : null;
					}
					
					if( isset( $_POST['new'] ) && $_POST['new'] )
					{
						$email = isset( $_POST['email'] ) ? trim( wp_unslash( $_POST['email'] ) ) : null;
						$key = $this->generate_key( $email );
					}
					
					if( $success && isset( $key ) && $key ) $success = $this->set_key( $key );
					
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
		try { $key = $this->get_key(); } catch(Exception $e) { $key = ""; }
		
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_info', array(
			'top-message' =>
				WPCF7_Pdf_Forms::replace_tags(
					esc_html__( "This service provides functionality for working with PDF files via {a-href-pdf.ninja}Pdf.Ninja API{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-pdf.ninja' => "<a href='https://pdf.ninja' target='_blank'>",
						'/a' => "</a>",
					)
				),
			'key-label' => esc_html__( 'API Key', 'pdf-forms-for-contact-form-7' ),
			'key' => esc_html( $key ),
			'key-copy-btn-label' => esc_html__( 'copy key', 'pdf-forms-for-contact-form-7' ),
			'key-copied-btn-label' => esc_html__( 'copied!', 'pdf-forms-for-contact-form-7' ),
			'api-url-label' => esc_html__( 'API URL', 'pdf-forms-for-contact-form-7' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'api-version-label' => esc_html__( 'API version', 'pdf-forms-for-contact-form-7' ),
			'api-version-1-label' => esc_html__( 'Version 1', 'pdf-forms-for-contact-form-7' ),
			'api-version-2-label' => esc_html__( 'Version 2', 'pdf-forms-for-contact-form-7' ),
			'api-version-1-value' => $this->get_api_version()==1 ? 'checked' : '',
			'api-version-2-value' => $this->get_api_version()==2 ? 'checked' : '',
			'security-label' => esc_html__( 'Data Security', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'security-warning' => esc_html__( 'Warning: Using plain HTTP or disabling certificate verification can lead to data leaks.', 'pdf-forms-for-contact-form-7' ),
			'enterprise-extension-support-label' => esc_html__( 'Enterprise Extension', 'pdf-forms-for-contact-form-7' ),
			'enterprise-extension-support-value' => esc_html( $this->get_enterprise_extension_support() ),
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
		try { $key = $this->get_key(); } catch(Exception $e) { $key = ""; }
		
		echo WPCF7_Pdf_Forms::render( 'pdfninja_integration_edit', array(
			'top-message-api-settings' => esc_html__( "The following form allows you to edit your API settings.", 'pdf-forms-for-contact-form-7' ),
			'top-message-new-key' => esc_html__( "The following form allows you to request a new key from the API.", 'pdf-forms-for-contact-form-7' ),
			'key-label' => esc_html__( 'API Key', 'pdf-forms-for-contact-form-7' ),
			'key' => esc_html( $key ),
			'api-url-label' => esc_html__( 'API URL', 'pdf-forms-for-contact-form-7' ),
			'api-url' => esc_html( $this->get_api_url() ),
			'api-version-label' => esc_html__( 'API version', 'pdf-forms-for-contact-form-7' ),
			'api-version-1-label' => esc_html__( 'Version 1', 'pdf-forms-for-contact-form-7' ),
			'api-version-2-label' => esc_html__( 'Version 2', 'pdf-forms-for-contact-form-7' ),
			'api-version-1-value' => $this->get_api_version()==1 ? 'checked' : '',
			'api-version-2-value' => $this->get_api_version()==2 ? 'checked' : '',
			'security-label' => esc_html__( 'Data Security', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-label' => esc_html__( 'Ignore certificate verification errors', 'pdf-forms-for-contact-form-7' ),
			'no-ssl-verify-value' => !$this->get_verify_ssl() ? 'checked' : '',
			'email-label' => esc_html__( "Administrator's Email Address", 'pdf-forms-for-contact-form-7' ),
			'email-value' => esc_html( $this->get_admin_email() ),
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
			echo WPCF7_Pdf_Forms::render_error_notice( null, array(
				'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html__( "Can't save new key.", 'pdf-forms-for-contact-form-7' ),
			) );
		
		if( $this->error )
			echo WPCF7_Pdf_Forms::render_error_notice( null, array(
				'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html( $this->error ),
			) );
		
		if( 'success' == $message )
			echo WPCF7_Pdf_Forms::render_success_notice( null, array(
				'message' => esc_html__( "Settings saved.", 'pdf-forms-for-contact-form-7' ),
			) );
	}
	
	/*
	 * This function gets called to display admin notices
	 */
	public function admin_notices()
	{
		try { $this->get_key(); } catch(Exception $e) { };
		$fail = get_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
		if( isset( $fail ) && $fail && current_user_can( 'wpcf7_manage_integration' ) )
			echo WPCF7_Pdf_Forms::render_error_notice( 'pdf-ninja-new-key-failure', array(
				'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
				'message' =>
					WPCF7_Pdf_Forms::replace_tags(
						esc_html__( "Failed to acquire the Pdf.Ninja API key on last attempt. {a-href-edit-service-page}Please retry manually{/a}.", 'pdf-forms-for-contact-form-7' ),
						array(
							'a-href-edit-service-page' => "<a href='".esc_url( $this->menu_page_url( 'action=edit' ) )."'>",
							'/a' => "</a>",
						)
					)
			) );
	}
	
	/**
	 * Returns thickbox messages that need to be displayed
	 */
	public function thickbox_messages()
	{
		$messages = '';
		try
		{
			$url = $this->get_api_url();
			$verify_ssl = $this->get_verify_ssl();
			if( substr( $url, 0, 5 ) == 'http:' || !$verify_ssl )
				$messages .= WPCF7_Pdf_Forms::render_warning_notice( 'insecure-pdf-ninja', array(
				'label' => esc_html__( "PDF Forms Filler for CF7 plugin warning", 'pdf-forms-for-contact-form-7' ),
				'message' => esc_html__( "Your Contact Form 7 integration settings indicate that you are using an insecure connection to the Pdf.Ninja API server.", 'pdf-forms-for-contact-form-7' ),
			) );
		}
		catch(Exception $e) { };
		
		return $messages;
	}
}

class WPCF7_Pdf_Ninja_Exception extends Exception
{
	private $reason = null;
	
	public function __construct( $response )
	{
		$msg = $response;
		
		if( is_array( $response ) )
		{
			if( ! isset( $response['error'] ) || $response['error'] == "" )
				$msg = __( "Unknown error", 'pdf-forms-for-contact-form-7' );
			else
				$msg = $response['error'];
			if( isset( $response['reason'] ) )
				$this->reason = $response['reason'];
		}
		
		parent::__construct( $msg );
	}
	
	public function getReason() { return $this->reason; }
}
