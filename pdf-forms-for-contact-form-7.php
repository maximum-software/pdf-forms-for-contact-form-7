<?php
/*
Plugin Name: PDF Forms Filler for Contact Form 7
Plugin URI: https://github.com/maximum-software/wpcf7-pdf-forms
Description: Create Contact Form 7 forms from PDF forms.  Get PDF forms filled automatically and attached to email messages and submission responses upon form submission on your website.  Embed images into PDF files.  Uses Pdf.Ninja API for working with PDF files.  See tutorial video for a demo.
Version: 1.2.4
Author: Maximum.Software
Author URI: https://maximum.software/
Text Domain: pdf-forms-for-contact-form-7
Domain Path: /languages
License: GPLv3
*/

require_once untrailingslashit( dirname( __FILE__ ) ) . '/inc/tgm-config.php';

if( ! class_exists( 'WPCF7_Pdf_Forms' ) )
{
	class WPCF7_Pdf_Forms
	{
		const VERSION = '1.2.4';
		
		private static $instance = null;
		private $pdf_ninja_service = null;
		private $service = null;
		private $registered_services = false;
		private $downloads = null;
		private $storage = null;
		private $cf7_forms_save_overrides = null;

		private function __construct()
		{
			load_plugin_textdomain( 'pdf-forms-for-contact-form-7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'plugins_loaded', array( $this, 'plugin_init' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 99, 2 );
			add_action( 'activate_'.plugin_basename( __FILE__ ), array( $this, 'plugin_activated') );
			add_action( 'deactivate_'.plugin_basename( __FILE__ ), array( $this, 'plugin_deactivated') );
			add_action( 'wpcf7_pdf_forms_cron', array( $this, 'cron') );
		}
		
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
		 * Runs after all plugins have been loaded
		 */
		public function plugin_init()
		{
			if( ! class_exists('WPCF7') )
				return;
			
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			
			add_action( 'wp_ajax_wpcf7_pdf_forms_get_attachment_info', array( $this, 'wp_ajax_get_attachment_info' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_tags', array( $this, 'wp_ajax_query_tags' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_preload_data', array( $this, 'wp_ajax_preload_data' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_cf7_fields', array( $this, 'wp_ajax_query_cf7_fields' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_page_image', array( $this, 'wp_ajax_query_page_image' ) );
			
			add_action( 'admin_init', array( $this, 'extend_tag_generator' ), 80 );
			add_action( 'admin_menu', array( $this, 'register_services') );
			
			add_action( 'wpcf7_before_send_mail', array( $this, 'fill_and_attach_pdfs' ) );
			add_action( 'wpcf7_after_save', array( $this, 'update_post_attachments' ) );
			add_action( 'wpcf7_mail_sent', array( $this, 'change_response_message' ) );
			
			//Hook that allow to copy media and mapping
			add_filter( 'wpcf7_copy', array( $this,'duplicate_form_hook' ),10,2 );

			// TODO: allow users to run this manually
			//$this->upgrade_data();
		}
		
		/*
		 * Runs after the plugin have been activated/deactivated
		 */
		public function plugin_activated()
		{
			$this->enable_cron();
		}
		public function plugin_deactivated()
		{
			$this->disable_cron();
		}
		
		/*
		 * Enables/disables cron
		 */
		private function enable_cron()
		{
			if( ! wp_next_scheduled( 'wpcf7_pdf_forms_cron' ) )
				wp_schedule_event( time(), 'daily', 'wpcf7_pdf_forms_cron' );
		}
		private function disable_cron()
		{
			if( wp_next_scheduled( 'wpcf7_pdf_forms_cron' ) )
				wp_clear_scheduled_hook( 'wpcf7_pdf_forms_cron' );
		}
		
		/*
		 * Runs after plugin updates and triggers data migration
		 */
		public function upgrader_process_complete( $upgrader_object, $options )
		{
			$plugin_path = plugin_basename( __FILE__ );
			
			if( $options['action'] == 'update'
			&& $options['type'] == 'plugin'
			&& isset( $options['plugins'] ) )
			{
				foreach( $options['plugins'] as $plugin )
				{
					if( $plugin == $plugin_path )
					{
						set_transient( 'wpcf7_pdf_forms_updated_old_version', self::VERSION );
						break;
					}
				}
			}
		}
		
		/*
		 * Returns sorted list of data migration scripts in the migrations directory
		 */
		private function get_migrations()
		{
			$migrations = array();
			
			$dir = untrailingslashit( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'migrations';
			$contents = scandir( $dir );
			
			foreach( $contents as $file )
			{
				$extension = end( explode( ".", $file ) );
				$filepath = $dir . DIRECTORY_SEPARATOR . $file;
				if( is_file( $filepath ) )
				if( preg_match("/^(\d+\.\d+\.\d+)\.php$/u", $file, $matches) )
				{
					$version = $matches[1];
					$migrations[$version] = $filepath;
				}
			}
			
			uksort( $migrations, 'version_compare' );
			
			return $migrations;
		}
		
		/*
		 * Runs data migration when triggered
		 */
		private function upgrade_data()
		{
			$old_version = get_transient( 'wpcf7_pdf_forms_updated_old_version' );
			if( !$old_version )
				return;
			
			delete_transient( 'wpcf7_pdf_forms_updated_old_version' );
			
			$new_version = self::VERSION;
			
			$migrations = $this->get_migrations();
			foreach($migrations as $version => $script)
			{
				if( version_compare( $version, $old_version ) > 0
				&& version_compare( $version, $new_version ) <= 0 )
					$this->run_data_migration( $script );
			}
		}
		
		/**
		 * Runs data migration script
		 */
		private function run_data_migration( $script )
		{
			include( $script );
		}
		
		/**
		 * Adds plugin action links
		 */
		public function action_links( $links )
		{
			$links[] = '<a target="_blank" href="https://youtu.be/jy84xqnj0Zk">'.esc_html__( "Tutorial Video", 'pdf-forms-for-contact-form-7' ).'</a>';
			$links[] = '<a target="_blank" href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/">'.esc_html__( "Support", 'pdf-forms-for-contact-form-7' ).'</a>';
			return $links;
		}
		
		/**
		 * Prints admin notices
		 */
		public function admin_notices()
		{
			if( ! class_exists('WPCF7') )
			{
				echo WPCF7_Pdf_Forms::render( 'notice_error', array(
					'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
					'message' => esc_html__( "The required plugin 'Contact Form 7' is not installed!", 'pdf-forms-for-contact-form-7' ),
				) );
				return;
			}
			
			if( ( $service = $this->get_service() ) )
				$service->admin_notices();
		}
		
		/**
		 * Loads the Pdf.Ninja service module
		 */
		private function load_pdf_ninja_service()
		{
			if( ! $this->pdf_ninja_service )
			{
				require_once untrailingslashit( dirname( __FILE__ ) ) . '/modules/pdf-ninja.php';
				$this->pdf_ninja_service = WPCF7_Pdf_Ninja::get_instance();
			}
			
			return $this->pdf_ninja_service;
		}
		
		
		/**
		 * Returns the service module instance
		 */
		public function get_service()
		{
			$this->register_services();
			
			if( ! $this->service )
				$this->set_service( $this->load_pdf_ninja_service() );
			
			return $this->service;
		}
		
		/**
		 * Sets the service module instance
		 */
		public function set_service( $service )
		{
			return $this->service = $service;
		}
		
		/**
		 * Loads and returns the storage module
		 */
		private function get_storage()
		{
			if( ! $this->storage )
			{
				require_once untrailingslashit( dirname( __FILE__ ) ) . '/modules/storage.php';
				$this->storage = WPCF7_Pdf_Forms_Storage::get_instance();
			}
			
			return $this->storage;
		}
		
		/**
		 * Loads and returns the downloads module
		 */
		private function get_downloads()
		{
			if( !$this->downloads )
			{
				require_once untrailingslashit( dirname( __FILE__ ) ) . '/modules/downloads.php';
				$this->downloads = WPCF7_Pdf_Forms_Downloads::get_instance();
			}
			return $this->downloads;
		}
		
		/**
		 * Adds necessary admin scripts and styles
		 */
		public function admin_enqueue_scripts( $hook )
		{
			if( false !== strpos($hook, 'wpcf7') )
			{
				wp_register_script( 'wpcf7_pdf_forms_admin_script', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery', 'jcrop' ), self::VERSION );
				wp_register_style( 'wpcf7_pdf_forms_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin.css', array( 'jcrop' ), self::VERSION );
				
				wp_localize_script( 'wpcf7_pdf_forms_admin_script', 'wpcf7_pdf_forms', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'wpcf7-pdf-forms-ajax-nonce' ),
					'__File_not_specified' => __( 'File not specified', 'pdf-forms-for-contact-form-7' ),
					'__Unknown_error' => __( 'Unknown error', 'pdf-forms-for-contact-form-7' ),
					'__No_WPCF7' => __( 'Please copy/paste tags manually', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Attachment' => __( 'Are you sure you want to delete this file?  This will delete field mappings and image embeds associated with this file.', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Mapping' => __( 'Are you sure you want to delete this mapping?', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Embed' => __( 'Are you sure you want to delete this embeded image?', 'pdf-forms-for-contact-form-7' ),
					'__Show_Help' => __( 'Show Help', 'pdf-forms-for-contact-form-7' ),
					'__Hide_Help' => __( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
					'__PDF_Frame_Title' => __( 'Select a PDF file', 'pdf-forms-for-contact-form-7'),
					'__PDF_Frame_Button' => __( 'Select', 'pdf-forms-for-contact-form-7')
				) );
				
				add_thickbox();
				wp_enqueue_media();
				
				wp_enqueue_script( 'wpcf7_pdf_forms_admin_script' );
				wp_enqueue_style( 'wpcf7_pdf_forms_admin_style' );
			}
		}
		
		/**
		 * Adds necessary scripts and styles
		 */
		public function enqueue_scripts( $hook )
		{
			wp_enqueue_style( 'font-awesome-free', '//use.fontawesome.com/releases/v5.2.0/css/all.css' );
		}
		
		/**
		 * Registers PDF forms category and PDF.Ninja service with the Contact Form 7 integration class
		 */
		public function register_services()
		{
			if( $this->registered_services )
				return;
			
			require_once untrailingslashit( dirname( __FILE__ ) ) . '/modules/service.php';
			
			$integration = WPCF7_Integration::get_instance();
			$integration->add_category( 'pdf_forms', __('PDF Forms', 'pdf-forms-for-contact-form-7') );
			
			$this->registered_services = true;
			
			$pdf_ninja_service = $this->load_pdf_ninja_service();
			if( $pdf_ninja_service )
				$integration->add_service( $pdf_ninja_service->get_service_name(), $pdf_ninja_service );
			
			do_action( 'wpcf7_pdf_forms_register_services' );
		}
		
		/*
		 * Function for working with metadata
		 */
		public static function get_meta( $post_id, $key )
		{
			$value = get_post_meta( $post_id, "wpcf7-pdf-forms-" . $key, $single=true );
			if( $value === '' )
				return null;
			return $value;
		}
		
		/*
		 * Function for working with metadata
		 */
		public static function set_meta( $post_id, $key, $value )
		{
			$oldval = get_post_meta( $post_id, "wpcf7-pdf-forms-" . $key, true );
			if( $oldval !== '' && $value === null)
				delete_post_meta( $post_id, "wpcf7-pdf-forms-" . $key );
			else
			{
				// wp bug workaround
				// TODO: find a better solution
				$fixed_value = wp_slash( $value );
				
				update_post_meta( $post_id, "wpcf7-pdf-forms-" . $key, $fixed_value, $oldval );
			}
			return $value;
		}
		
		/*
		 * Function for working with metadata
		 */
		public static function unset_meta( $post_id, $key )
		{
			delete_post_meta( $post_id, "wpcf7-pdf-forms-" . $key );
		}
		
		/**
		 * Attaches an attachment to a post
		 */
		public function post_add_pdf( $post_id, $attachment_id, $options )
		{
			// if this attachment is already attached, create a copy
			if( wp_get_post_parent_id( $attachment_id ) > 0 )
			{
				$filepath = get_attached_file( $attachment_id );
				
				$temp_filepath = wp_tempnam();
				if( copy( $filepath, $temp_filepath ) === FALSE )
					return;
				
				$copy_suffix = sanitize_file_name( __( "Copy", 'pdf-forms-for-contact-form-7' ) );
				$base_filename = preg_replace( '/\-' . preg_quote( $copy_suffix , "/" ) . '(\-[0-9]+)?$/iu', '', pathinfo( $filepath, PATHINFO_FILENAME ) );
				$copy_filename = $base_filename . '-' . $copy_suffix . '.' . pathinfo( $filepath, PATHINFO_EXTENSION );
				
				$copy_attachment_id = media_handle_sideload( array(
					'tmp_name' => $temp_filepath,
					'name'     => $copy_filename
				), $post_id);
				if( is_wp_error( $copy_attachment_id ) )
				{
					@unlink( $temp_filepath );
					return;
				}
				
				$attachment_id = $copy_attachment_id;
			}
			
			wp_update_post( array( 'ID' => $attachment_id, 'post_parent' => $post_id ) );
			$this->post_update_pdf( $post_id, $attachment_id, $options );
			
			return $attachment_id;
		}
		
		private static $pdf_options = array('skip_empty' => false, 'attach_to_mail_1' => true, 'attach_to_mail_2' => false, 'flatten' => false, 'filename' => "", 'save_directory'=>"", 'download_link' => false );
		
		/**
		 * Updates post attachment options
		 */
		public function post_update_pdf( $post_id, $attachment_id, $options )
		{
			$values = array();
			foreach( self::$pdf_options as $option => $default )
				if( array_key_exists( $option, $options ) )
					$values[$option] = $options[$option];
				else
					$values[$option] = $default;
			
			self::set_meta( $attachment_id, 'options-'.$post_id, self::json_encode( $values ) );
		}
		
		/**
		 * Retreives all PDF attachments of a post
		 */
		public function post_get_all_pdfs( $post_id )
		{
			$pdfs = array();
			foreach( get_attached_media( 'application/pdf', $post_id ) as $attachment )
			{
				$attachment_id = $attachment->ID;
				
				$options = array();
				
				$values = self::get_meta( $attachment_id, 'options-'.$post_id );
				if( $values )
					$values = json_decode( $values, true );
				if( !$values )
					$values = array();
				
				foreach( self::$pdf_options as $option => $default )
					if( array_key_exists( $option, $values ) )
						$options[$option] = $values[$option];
					else
						$options[$option] = $default;
				
				$pdfs[$attachment_id] = array( 'attachment_id' => $attachment_id, 'options' => $options );
			}
			return $pdfs;
		}
		
		/**
		 * Removes an attachment from a post
		 */
		public function post_del_pdf( $post_id, $attachment_id )
		{
			wp_update_post( array( 'ID' => $attachment_id, 'post_parent' => 0 ) );
			self::unset_meta( $attachment_id, 'options-'.$post_id );
		}
		
		/**
		 * Hook that runs on form copy/duplicate forms with the editor
		 */
		function duplicate_form_hook( $new, $instance )
		{
			$prev_post_id = $instance->id();
			$attachments = $this->post_get_all_pdfs( $prev_post_id );
			
			$mappings = self::get_meta( $prev_post_id, 'mappings' );
			if( $mappings )
				$mappings = json_decode( $mappings, true );
			if( !is_array( $mappings ) )
				$mappings = array();
			
			$embeds = self::get_meta( $prev_post_id, 'embeds' );
			if( $embeds )
				$embeds = json_decode( $embeds, true );
			if( !is_array( $embeds ) )
				$embeds = array();
			
			$this->cf7_forms_save_overrides = array( 'attachments' => $attachments , 'mappings' => $mappings , 'embeds' => $embeds );
			
			return $new;
		}

		/**
		 * Hook that runs on form save and attaches all PDFs that were attached to forms with the editor
		 */
		public function update_post_attachments( $contact_form )
		{
			$post_id = $contact_form->id();
			
			$data = null;
			if( is_array( $this->cf7_forms_save_overrides ) )
				$data = $this->cf7_forms_save_overrides;
			else if( isset( $_POST['wpcf7-pdf-forms-data'] ) )
				$data = json_decode( wp_unslash( $_POST['wpcf7-pdf-forms-data'] ), true );
			
			if( !is_array( $data ) )
				return;
			
			if( isset( $data['attachments'] ) && is_array( $new_attachments = $data['attachments'] ) )
			{
				$old_attachments = $this->post_get_all_pdfs( $post_id );
				
				$new_attachment_ids = array();
				foreach( $new_attachments as $attachment )
				{
					$attachment_id = intval( $attachment['attachment_id'] );
					if( $attachment_id > 0 )
						if( current_user_can( 'edit_post', $attachment_id ) )
						{
							$options = array();
							if( isset( $attachment['options'] ) )
								$options = $attachment['options'];
							
							if( ! isset( $old_attachments[$attachment_id] ) )
							{
								$new_attachment_id = $this->post_add_pdf( $post_id, $attachment_id, $options );
								if( $attachment_id != $new_attachment_id )
								{
									// replace old attachment id in mappings
									if( isset( $data['mappings'] ) && is_array( $data['mappings'] ) )
									{
										foreach( $data['mappings'] as &$mapping )
											if( isset( $mapping['pdf_field'] ) )
												$mapping['pdf_field'] = preg_replace( '/^' . preg_quote( $attachment_id . '-' ) . '/i', intval( $new_attachment_id ) . '-', $mapping['pdf_field'] );
										unset($mapping);
									}
									
									// replace old attachment id in embeds
									if( isset( $data['embeds'] ) && is_array( $data['embeds'] ) )
									{
										foreach( $data['embeds'] as &$embed )
											if( isset( $embed['attachment_id'] ) && $embed['attachment_id'] == $attachment_id )
												$embed['attachment_id'] = $new_attachment_id;
										unset($embed);
									}
									
									// TODO: replace old attachment id in tag generator tool tags in the form body and settings
									
									$attachment_id = $new_attachment_id;
								}
							}
							else
								$this->post_update_pdf( $post_id, $attachment_id, $options );
							
							$new_attachment_ids[$attachment_id] = $attachment_id;
						}
				}
				
				foreach( $old_attachments as $attachment_id => $attachment )
				{
					if( ! isset( $new_attachment_ids[$attachment_id] ) )
						$this->post_del_pdf( $post_id, $attachment_id );
				}
			}
			
			if( isset( $data['mappings'] ) && is_array( $new_mappings = $data['mappings'] ) )
			{
				$mappings = array();
				foreach( $new_mappings as $mapping )
					if( isset( $mapping['cf7_field'] ) && isset( $mapping['pdf_field'] ) )
						if( self::wpcf7_field_name_decode( $mapping['cf7_field'] ) === FALSE )
							$mappings[] = array( 'cf7_field' => $mapping['cf7_field'], 'pdf_field' => $mapping['pdf_field'] );
				self::set_meta( $post_id, 'mappings', self::json_encode( $mappings ) );
			}
			
			if( isset( $data['embeds'] ) && is_array( $new_embeds = $data['embeds'] ) )
			{
				$embeds = array();
				foreach( $new_embeds as $embed )
					if( isset( $embed['cf7_field'] ) && isset( $embed['attachment_id'] ) )
						$embeds[] = $embed;
				self::set_meta( $post_id, 'embeds', self::json_encode( $embeds ) );
			}
		}
		
		private static function download_file( $url, $filepath )
		{
			// if this url points to the site, copy the file directly
			$site_url = trailingslashit( get_site_url() );
			if( substr( $url, 0, strlen( $site_url ) ) == $site_url )
			{
				$path = substr( $url, strlen( $site_url ) );
				$home_path = trailingslashit( realpath( dirname(__FILE__) . '/../../../' ) );
				$sourcepath = realpath( $home_path . $path );
				if( $home_path && $sourcepath && substr( $sourcepath, 0, strlen( $home_path ) ) == $home_path )
					if( file_exists( $sourcepath ) )
						if( copy($sourcepath, $filepath) )
							return;
			}
			
			$args = array(
				'compress'    => true,
				'decompress'  => true,
				'timeout'     => 100,
				'redirection' => 5,
				'user-agent'  => 'wpcf7-pdf-forms/' . WPCF7_Pdf_Forms::VERSION,
			);
			
			$response = wp_remote_get( $url, $args );
			
			if( is_wp_error( $response ) )
				throw new Exception( __( "Failed to download file", 'pdf-forms-for-contact-form-7' ) );
			
			$body = wp_remote_retrieve_body( $response );
			
			$handle = @fopen( $filepath, 'w' );
			
			if( ! $handle )
				throw new Exception( __( "Failed to open file for writing", 'pdf-forms-for-contact-form-7' ) );
			
			fwrite( $handle, $body );
			fclose( $handle );
			
			if( ! file_exists( $filepath ) )
				throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		}
		
		/**
		 * Creates a temporary file path (but not the file itself)
		 */
		private static function create_wpcf7_tmp_filepath( $filename )
		{
			static $uploads_dir;
			if( ! $uploads_dir )
			{
				wpcf7_init_uploads(); // Confirm upload dir
				$uploads_dir = wpcf7_upload_tmp_dir();
				$uploads_dir = wpcf7_maybe_add_random_dir( $uploads_dir );
			}
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $uploads_dir, $filename );
			return trailingslashit( $uploads_dir ) . $filename;
		}
		
		/**
		 * When form data is posted, this function communicates with the API
		 * to fill the form data and get the PDF file with filled form fields
		 * 
		 * Files created and attached in this function will be deleted
		 * automatically by CF7 after it sends the email message
		 */
		public function fill_and_attach_pdfs( $contact_form )
		{
			$post_id = $contact_form->id();
			
			$mappings = self::get_meta( $post_id, 'mappings' );
			if( $mappings )
				$mappings = json_decode( $mappings, true );
			if( !is_array( $mappings ) )
				$mappings = array();
			
			$embeds = self::get_meta( $post_id, 'embeds' );
			if( $embeds )
				$embeds = json_decode( $embeds, true );
			if( !is_array( $embeds ) )
				$embeds = array();
			
			$submission = WPCF7_Submission::get_instance();
			$posted_data = $submission->get_posted_data();
			$uploaded_files = $submission->uploaded_files();
			
			// preprocess posted data
			$processed_data = array();
			foreach( $posted_data as $key => $value )
			{
				if( is_array( $value ) )
					$value = array_shift( $value );
				$value = strval( $value );
				if( $value === '' )
					continue;
				
				$value = wp_unslash( $value );
				
				$processed_data[$key] = $value;
			}
			
			// preprocess embedded images
			$embed_fields = array();
			foreach( $embeds as $embed )
				$embed_fields[] = $embed["cf7_field"];
			$embed_files = array();
			foreach( $embed_fields as $cf7_field )
			{
				if( isset( $processed_data[$cf7_field] ) )
				{
					$value = $processed_data[$cf7_field];
					if( filter_var( $value, FILTER_VALIDATE_URL ) !== FALSE )
					if( substr( $value, 0, 5 ) === 'http:' || substr( $value, 0, 6 ) === 'https:' )
					{
						try
						{
							$filepath = self::create_wpcf7_tmp_filepath( 'img_download_'.count($embed_files).'.tmp' );
							self::download_file( $value, $filepath );
							
							$embed_files[$cf7_field] = $filepath;
						}
						catch(Exception $e) { }
					}
				}
				if( isset( $uploaded_files[$cf7_field] ) )
					$embed_files[$cf7_field] = $uploaded_files[$cf7_field];
			}
			
			$files = array();
			foreach( $this->post_get_all_pdfs( $post_id ) as $attachment_id => $attachment )
			{
				$fields = $this->get_fields( $attachment_id );
				
				$data = array();
				foreach( $processed_data as $key => $value )
				{
					// processs mappings
					foreach( $mappings as $mapping )
						if( $mapping["cf7_field"] == $key )
						{
							$i = strpos( $mapping["pdf_field"], '-' );
							if( $i === FALSE )
								continue;
							
							$aid = substr( $mapping["pdf_field"], 0, $i );
							if( $aid != $attachment_id && $aid != 'all' )
								continue;
							
							$field = substr( $mapping["pdf_field"], $i+1 );
							$field = self::base64url_decode( $field );
							
							if( !isset( $fields[$field] ) )
								continue;
							
							$data[$field] = $value;
						}
					
					// processs old style tag generator fields
					try
					{
						$field_data = self::wpcf7_field_name_decode( $key );
						if( $field_data === FALSE )
							throw new Exception();
						if( $field_data['attachment_id'] != $attachment_id && $field_data['attachment_id'] != 'all' )
							throw new Exception();
						$field = $field_data['field'];
						if( $field === '' )
							throw new Exception();
						
						if( !isset( $fields[$field] ) )
							throw new Exception();
						
						$data[$field] = $value;
					}
					catch(Exception $e) { }
				}
				
				// process image embeds
				$embeds_data = array();
				foreach($embeds as $embed)
					if( $embed['attachment_id'] == $attachment_id )
					{
						$cf7_field = $embed['cf7_field'];
						if( isset( $embed_files[$cf7_field] ) )
						{
							$embed_data = array(
								'image' => $embed_files[$cf7_field],
								'page' => $embed['page'],
							);
							
							if($embed['page'] > 0)
							{
								$embed_data['left'] = $embed['left'];
								$embed_data['top'] = $embed['top'];
								$embed_data['width'] = $embed['width'];
								$embed_data['height'] = $embed['height'];
							};
							
							$embeds_data[] = $embed_data;
						}
					}
				
				if( count( $data ) == 0
				&& count( $embeds_data ) == 0
				&& $attachment['options']['skip_empty'] )
					continue;
				
				$mail = $attachment['options']['attach_to_mail_1'];
				$mail2 = $attachment['options']['attach_to_mail_2'];
				$save_directory = strval( $attachment['options']['save_directory'] );
				$create_download_link = $attachment['options']['download_link'];
				
				if( !$mail && !$mail2 && $save_directory === "" && !$create_download_link )
					continue;
				
				$options = array();
				
				$options['flatten'] =
					isset($attachment['options']) &&
					isset($attachment['options']['flatten']) &&
					$attachment['options']['flatten'] == true;
				
				$filepath = get_attached_file( $attachment_id );
				
				$filename = strval( $attachment['options']['filename'] );
				if ( $filename !== "" )
					$destfilename = wpcf7_mail_replace_tags( $filename );
				else
					$destfilename = basename( $filepath, '.pdf' );
				
				$destfile = self::create_wpcf7_tmp_filepath( $destfilename.'.pdf' ); // if $destfilename is empty create_wpcf7_tmp_filepath generates unnamed-file.pdf
				
				try
				{
					$service = $this->get_service();
					$filled = false;
					if( $service )
						// we only want to use the API when there is actual data to be embedded into the PDF file
						if( count( $data ) > 0 || $options['flatten'] || count( $embeds_data ) > 0 )
							$filled = $service->api_fill_embed( $destfile, $attachment_id, $data, $embeds_data, $options );
					if( ! $filled )
						copy( $filepath, $destfile );
					$files[] = array( 'file' => $destfile, 'filename' => $destfilename.'.pdf', 'options' => $attachment['options'] );
				}
				catch(Exception $e)
				{
					if( ! file_exists( $destfile ) )
						copy( $filepath, $destfile );
					$files[] = array( 'file' => $destfile, 'filename' => $destfilename.'.pdf', 'options' => $attachment['options'] );
					$destfile = self::create_wpcf7_tmp_filepath( $destfilename . ".txt" );
					$text = str_replace(
						array( '{error-message}', '{error-file}', '{error-line}' ),
						array( $e->getMessage(), basename( $e->getFile() ), $e->getLine() ),
						__( "Error generating PDF: {error-message} at {error-file}:{error-line}\n\nForm data:\n\n", 'pdf-forms-for-contact-form-7' )
					);
					foreach( $data as $field => $value )
						$text .= "$field: $value\n";
					file_put_contents( $destfile, $text );
					$files[] = array( 'file' => $destfile, 'filename' => $destfilename.'.txt', 'options' => $attachment['options'] );
				}
			}
			
			if( count( $files ) > 0 )
			{
				$mail = $contact_form->prop( "mail" );
				$mail2 = $contact_form->prop( "mail_2" );
				
				foreach( $files as $id => $filedata )
				{
					$file = $filedata['file'];
					if( file_exists( $file ) )
					{
						$submission->add_uploaded_file( "wpcf7-pdf-forms-$id", $file );
						
						if( $filedata['options']['attach_to_mail_1'] )
							$mail["attachments"] .= "\n[wpcf7-pdf-forms-$id]\n";
						
						if( $filedata['options']['attach_to_mail_2'] )
							$mail2["attachments"] .= "\n[wpcf7-pdf-forms-$id]\n";
					}
				}
				$contact_form->set_properties( array( 'mail' => $mail, 'mail_2' => $mail2 ) );
				
				$storage = $this->get_storage();
				foreach( $files as $id => $filedata )
				{
					$save_directory = strval( $filedata['options']['save_directory'] );
					if( $save_directory !== "" )
					{
						// standardize directory separator
						$save_directory = str_replace( '\\', '/', $save_directory );
						
						// remove preceeding slashes and dots and space characters
						$trim_characters = "/\\. \t\n\r\0\x0B";
						$save_directory = trim( $save_directory, $trim_characters );
						
						// replace WPCF7 tags in path elements
						$path_elements = explode( "/", $save_directory );
						foreach( $path_elements as &$element )
						{
							$text = new WPCF7_MailTaggedText( $element );
							$new_element = $text->replace_tags();
							
							// sanitize
							$new_element = trim( sanitize_file_name( wpcf7_canonicalize( $new_element ) ), $trim_characters );
							
							// if replaced and sanitized filename is blank then attempt to use the non-replaced version
							if( $new_element === "" )
								$new_element = trim( sanitize_file_name( wpcf7_canonicalize( $element ) ), $trim_characters );
							
							$element = $new_element;
						}
						unset($element);
						
						$save_directory = implode( "/", $path_elements );
						$save_directory = preg_replace( '|/+|', '/', $save_directory ); // remove double slashes
						
						// remove preceeding slashes and dots and space characters
						$save_directory = trim( $save_directory, $trim_characters );
						
						$storage->set_subpath( $save_directory );
						$storage->save( $filedata['file'], $filedata['filename'] );
					}
					
					$create_download_link = $filedata['options']['download_link'];
					if ( $create_download_link )
						$this->get_downloads()->add_file( $filedata['file'], $filedata['filename'] );
				}
			}
		}
		
		/**
		 * Used for uploading a pdf file to the server in wp-admin interface
		 */
		public function wp_ajax_get_attachment_info()
		{
			try
			{
				if ( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$attachment_id = $_POST[ 'file_id' ];
				
				$filepath = get_attached_file( $attachment_id );
				if( !$filepath )
					throw new Exception( __( "Invalid attachment", 'pdf-forms-for-contact-form-7' ) );
				if( ! file_exists( $filepath ) )
					throw new Exception( __( "File not found", 'pdf-forms-for-contact-form-7' ) );
				
				// TODO: check type of contents of the file instead of just extension
				if( ( $type = wp_check_filetype( $filepath ) ) && isset( $type['type'] ) && $type['type'] !== 'application/pdf' )
					throw new Exception( __( "Invalid file type, must be a PDF file", 'pdf-forms-for-contact-form-7' ) );
				
				$options = array( );
				foreach( self::$pdf_options as $option => $default )
					$options[$option] = $default;
				
				$info = $this->get_info( $attachment_id );
				$info['fields'] = $this->query_pdf_fields( $attachment_id );
				
				return wp_send_json( array(
					'success' => true,
					'attachment_id' => $attachment_id,
					'filename' => basename( $filepath ),
					'options' => $options,
					'info' => $info,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/*
		 * Returns (and computes, if necessary) the md5 sum of the media file
		 */
		public static function get_attachment_md5sum( $attachment_id )
		{
			$md5sum = self::get_meta( $attachment_id, 'md5sum' );
			if( ! $md5sum )
				return self::update_attachment_md5sum( $attachment_id );
			else
				return $md5sum;
		}
		
		/*
		 * Computes, saves and returns the md5 sum of the media file
		 */
		public static function update_attachment_md5sum( $attachment_id )
		{
			$filepath = get_attached_file( $attachment_id );
			
			if( ! file_exists( $filepath ) )
				throw new Exception( __( "File not found", 'pdf-forms-for-contact-form-7' ) );
			
			// clear info cache
			self::unset_meta( $attachment_id, 'info' );
			
			// delete page snapshots
			$args = array(
				'post_parent' => $attachment_id,
				'meta_key' => 'wpcf7-pdf-forms-page',
				'post_type' => 'attachment',
				'post_status' => 'any',
				'posts_per_page' => -1,
			);
			foreach( get_posts( $args ) as $post )
				wp_delete_post( $post->ID, $force_delete = true );
			
			return self::set_meta( $attachment_id, 'md5sum', md5_file( $filepath ) );
		}
		
		/*
		 * Caching wrapper for $service->api_get_info()
		 */
		public function get_info( $attachment_id )
		{
			$info = self::get_meta( $attachment_id, 'info' );
			if( $info )
			{
				$filepath = get_attached_file( $attachment_id );
				$new_md5sum = md5_file( $filepath );
				$old_md5sum = self::get_attachment_md5sum( $attachment_id );
				if($new_md5sum === $old_md5sum )
					return json_decode( $info, true );
				else
					self::update_attachment_md5sum( $attachment_id );
			}
			
			$service = $this->get_service();
			if( !$service )
				throw new Exception( __( "No service", 'pdf-forms-for-contact-form-7' ) );
			
			$info = $service->api_get_info( $attachment_id );
			
			// set up array keys so it is easier to search
			$fields = array();
			foreach( $info['fields'] as $field )
				$fields[$field['name']] = $field;
			$info['fields'] = $fields;
			
			$pages = array();
			foreach( $info['pages'] as $page )
				$pages[$page['number']] = $page;
			$info['page'] = $pages;
			
			// set fields cache
			self::set_meta( $attachment_id, 'info', self::json_encode( $info ) );
			
			return $info;
		}
		
		/*
		 * Caches and returns fields for an attachment
		 */
		public function get_fields( $attachment_id )
		{
			$info = $this->get_info( $attachment_id );
			return $info['fields'];
		}
		
		/*
		 * PHP version specific wrapper for json_encode function
		 */
		public static function json_encode( $value )
		{
			if( version_compare( phpversion(), "5.4" ) < 0 )
				return preg_replace(
						"/\\\\u([a-f0-9]{4})/" .
						"e", // don't warn me about this, I know!
						"iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))",
						json_encode( $value )
					);
			
			return json_encode( $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		}
		
		/*
		 * Multibyte trim
		 */
		public static function mb_trim($str)
		{
			return preg_replace( '/(^\s+)|(\s+$)/us', '', $str );
		}
		
		/*
		 * Generates CF7 field tag based on field data
		 */
		private static function generate_tag($field, $tagName)
		{
			$type = strval($field['type']);
			
			// sanity check
			if( ! ( $type === 'text' || $type === 'radio' || $type === 'select' || $type === 'checkbox' ) )
				return null;
			
			$tagType = $type;
			$tagOptions = '';
			$tagValues = '';
			
			if( $type == 'text' )
				if( isset( $field['value'] ) )
					$tagValues .= '"' . esc_attr( strval( $field['value'] ) ) . '" ';
			
			if( $type == 'radio' || $type == 'select' || $type == 'checkbox' )
			{
				if( isset( $field['options'] ) && is_array( $field['options'] ) )
				{
					$options = $field['options'];
					
					if( ( $off_key = array_search( 'Off', $options, $strict=true ) ) !== FALSE )
						unset( $options[ $off_key ] );
					
					if( $type == 'radio' && count( $options ) == 1 )
						$tagType = 'checkbox';
					
					if( count( $options ) == 1 )
						// add pipe to prevent user confusion with singular options
						$tagValues .= '"' . esc_attr( strval( $field['name'] ) ) . '|' . esc_attr( strval( reset( $options ) ) ) . '" ';
					else
						foreach( $options as $option )
							$tagValues .= '"' . esc_attr( strval( $option ) ) . '" ';
					
					if( $type == 'checkbox' && count( $options ) > 1 )
						$tagOptions .= 'exclusive ';
				}
				else
					return null;
			}
			
			if( isset( $field['flags'] ) && is_array( $field['flags'] ) )
			{
				$flags = $field['flags'];
				
				if( $type == 'text' )
					if( in_array( 'Multiline', $flags ) )
						$tagType = 'textarea';
				
				if( $type == 'select' )
					if( in_array( 'MultiSelect', $flags ) )
						$tagOptions .= 'multiple ';
				
				if( in_array( 'Required', $flags ) )
				{
					if( ! ( $tagType == 'radio' || $tagType == 'select' || $tagType == 'checkbox' ) )
						$tagType .= '*';
				}
				else
					if( $tagType == 'select' )
						$tagOptions .= 'include_blank ';
				
				if( in_array( 'ReadOnly', $flags ) )
					$tagOptions .= 'readonly ';
			}
			
			return '[' . self::mb_trim( $tagType . ' ' . $tagName . ' ' . $tagOptions . $tagValues ) . ']';
		}
		
		/**
		 * Used for generating tags in wp-admin interface
		 */
		public function wp_ajax_query_tags()
		{
			try
			{
				if( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$attachments = isset( $_POST['attachments'] ) ? $_POST['attachments'] : null;
				$all = isset( $_POST['all'] ) ? $_POST['all'] : null;
				
				if( !isset($attachments) || !is_array($attachments) )
					$attachments = array();
				
				$fields = array();
				foreach( $attachments as $attachment_id )
				{
					if ( ! current_user_can( 'edit_post', $attachment_id ) )
						continue;
					
					$fields[$attachment_id] = $this->get_fields( $attachment_id );
				}
				
				if( count($fields) == 1 && count(reset($fields)) == 0 )
					$tags = __( "This PDF file does not appear to contain a PDF form.  See https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html for more information.", 'pdf-forms-for-contact-form-7' );
				else
				{
					$tags = "";
					
					foreach ( $fields as $attachment_id => $fs )
						foreach ( $fs as $field )
						{
							if( isset( $field['type'] ) )
							{
								$name = strval($field['name']);
								
								$tag = '<label>' . esc_html( $name ) . '</label>' . "\n";
								
								$tag_flag = $attachment_id;
								if( $all == "true" )
									$tag_flag = "all";
								
								$tagName = self::wpcf7_field_name_encode( $tag_flag, $name );
								$generated_tag = self::generate_tag( $field, $tagName );
								
								if( $generated_tag === null)
									continue;
								
								$tag .= '    ' . $generated_tag;
								$tags .= $tag . "\n\n";
							}
						}
				}
				
				return wp_send_json( array(
					'success' => true,
					'tags' => $tags,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
		 * Helper function used in wp-admin interface
		 */
		private function query_pdf_fields( $attachment_id )
		{
			$fields = $this->get_fields( $attachment_id );
			foreach( $fields as $id => &$field )
			{
				if( !isset( $field['type'] ) || !isset( $field['name'] ) )
				{
					unset( $fields[$id] );
					continue;
				}
				
				$type = strval( $field['type'] );
				
				// sanity check
				if( ! ( $type === 'text' || $type === 'radio' || $type === 'select' || $type === 'checkbox' ) )
				{
					unset($fields[$id]);
					continue;
				}
				
				$name = strval( $field['name'] );
				
				$encoded_name = self::base64url_encode( $name );
				$slug = sanitize_title( $name );
				if( preg_match( '/^[a-zA-Z]$/', $slug[0] ) === 0 )
					$slug = 'f-'.$slug;
				$tag_hint = self::generate_tag( $field, $slug );
				$field['id'] = $encoded_name;
				$field['tag_hint'] = $tag_hint;
				$field['tag_name'] = $slug;
			}
			
			return $fields;
		}
		
		/**
		 * Used for initializing the PDF tag generator thickbox in wp-admin interface
		 */
		public function wp_ajax_preload_data()
		{
			try
			{
				if ( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : null;
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : null;
				
				if( ! $post_id )
					throw new Exception( __( "Invalid post ID", 'pdf-forms-for-contact-form-7' ) );
				
				if ( ! current_user_can( 'wpcf7_edit_contact_form', $post_id ) )
					throw new Exception( __( "Permission denied", 'pdf-forms-for-contact-form-7' ) );
				
				$attachments = array();
				$attachment_ids = array();
				foreach( $this->post_get_all_pdfs( $post_id ) as $attachment_id => $attachment )
				{
					$options = array();
					if( isset( $attachment['options']) )
						$options = $attachment['options'];
					
					$info = $this->get_info( $attachment_id );
					$info['fields'] = $this->query_pdf_fields( $attachment_id );
					
					$attachments[] = array(
						'attachment_id' => $attachment_id,
						'filename' => basename( get_attached_file( $attachment_id ) ),
						'options' => $options,
						'info' => $info,
					);
					$attachment_ids[] = $attachment_id;
				}
				
				$cf7_fields = $this->query_cf7_fields( $form );
				
				$mappings = self::get_meta( $post_id, 'mappings' );
				if( $mappings )
					$mappings = json_decode( $mappings, true );
				if( !is_array( $mappings ) )
					$mappings = array();
				
				$embeds = self::get_meta( $post_id, 'embeds' );
				if( $embeds )
					$embeds = json_decode( $embeds, true );
				if( !is_array( $embeds ) )
					$embeds = array();
				
				return wp_send_json( array(
					'success' => true,
					'attachments' => $attachments,
					'cf7_fields' => $cf7_fields,
					'mappings' => $mappings,
					'embeds' => $embeds,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
		 * Returns a list of cf7 fields
		 */
		private function query_cf7_fields( $form )
		{
			$contact_form = WPCF7_ContactForm::get_template();
			$properties = $contact_form->get_properties();
			$properties['form'] = $form;
			$contact_form->set_properties( $properties );
			
			$tags = $contact_form->collect_mail_tags();
			
			if( !is_array( $tags ) )
				throw new Exception( __( "Failed to get Contact Form fields", 'pdf-forms-for-contact-form-7' ) );
			
			$fields = array();
			foreach( $tags as $tag )
			{
				$name = $tag;
				$pdf_field = self::wpcf7_field_name_decode( $tag );
				if( $pdf_field !== FALSE )
					$pdf_field = $pdf_field['attachment_id'].'-'.$pdf_field['encoded_field'];
				
				$fields[] = array(
					'id' => $tag,
					'name' => $tag,
					'caption' => $tag,
					'pdf_field' => $pdf_field,
				);
			}
			
			return $fields;
		}
		
		/**
		 * Used for getting a list of cf7 fields in wp-admin interface
		 */
		public function wp_ajax_query_cf7_fields()
		{
			try
			{
				if ( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : null;
				
				$fields = $this->query_cf7_fields( $form );
				
				return wp_send_json( array(
					'success' => true,
					'fields' => $fields,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
		 * Downloads and caches PDF page images, returns image attachment id
		 */
		public function get_pdf_snapshot( $attachment_id, $page )
		{
			$args = array(
				'post_parent' => $attachment_id,
				'meta_key' => 'wpcf7-pdf-forms-page',
				'meta_value' => $page,
				'post_type' => 'attachment',
				'post_status' => 'any',
				'posts_per_page' => 1,
			);
			$posts = get_posts( $args );
			
			if( count( $posts ) > 0 )
			{
				$old_attachment_id = reset( $posts )->ID;
				return $old_attachment_id;
			}
			
			if( ! ( ( $wp_upload_dir = wp_upload_dir() ) && false === $wp_upload_dir['error'] ) )
				throw new Exception( $wp_upload_dir['error'] );
			
			$attachment_path = get_attached_file( $attachment_id );
			
			if( ! file_exists( $attachment_path ) )
				throw new Exception( __( "File not found", 'pdf-forms-for-contact-form-7' ) );
			
			$filename = wp_unique_filename( $wp_upload_dir['path'], basename( $attachment_path ).'.page'.intval($page).'.jpg' );
			$filepath = $wp_upload_dir['path'] . "/$filename";
			$filetype = wp_check_filetype( $filename, null );
			
			$service = $this->get_service();
			if( $service )
				$service->api_image( $filepath, $attachment_id, $page );
			
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $filename,
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			
			$new_attachment_id = wp_insert_attachment( $attachment, $filepath, $attachment_id );
			
			self::set_meta( $new_attachment_id, 'page', $page );
			
			return $new_attachment_id;
		}
		
		/**
		 * Used for getting PDF page images in wp-admin interface
		 */
		public function wp_ajax_query_page_image()
		{
			try
			{
				if ( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$attachment_id = isset( $_POST['attachment_id'] ) ? (int) $_POST['attachment_id'] : null;
				$page = isset( $_POST['page'] ) ? (int) $_POST['page'] : null;
				
				if ( $page < 1 )
					throw new Exception( __( "Invalid page number", 'pdf-forms-for-contact-form-7' ) );
				
				if( ! current_user_can( 'edit_post', $attachment_id ) )
					throw new Exception( __( "Permission denied", 'pdf-forms-for-contact-form-7' ) );
				
				$attachment_id = $this->get_pdf_snapshot( $attachment_id, $page );
				$snapshot = wp_get_attachment_image_src( $attachment_id, array( 500, 500 ) );
				
				if( !$snapshot || !is_array( $snapshot ) )
					throw new Exception( __( "Failed to retrieve page snapshot", 'pdf-forms-for-contact-form-7' ) );
				
				return wp_send_json( array(
					'success' => true,
					'snapshot' => reset( $snapshot ),
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
		 * Adds a tag to the form editor
		 */
		public function extend_tag_generator()
		{
			if( class_exists('WPCF7_TagGenerator') )
			{
				$tag_generator = WPCF7_TagGenerator::get_instance();
				$tag_generator->add(
					'pdf_form',
					__( 'PDF Form', 'pdf-forms-for-contact-form-7' ),
					array( $this, 'render_tag_generator')
				);
			}
			// support for older CF7 versions
			else if( function_exists('wpcf7_add_tag_generator') )
			{
				wpcf7_add_tag_generator(
					'pdf_form',
					__( 'PDF Form', 'pdf-forms-for-contact-form-7' ),
					'wpcf7-tg-pane-pdfninja',
					array( $this, 'render_tag_generator')
				);
			}
		}
		
		/**
		 * Takes html template from the html folder and renders it with the given attributes
		 */
		public static function render( $template, $attributes = array() )
		{
			return self::render_file( plugin_dir_path(__FILE__) . 'html/' . $template . '.html', $attributes );
		}
		
		/*
		 * Helper for replace_tags function
		 */
		private static function add_curly_braces($str)
		{
			return '{'.$str.'}';
		}
		
		/**
		 * Takes a string with tags and replaces tags in the string with the given values in $tags array
		 */
		public static function replace_tags( $string, $tags = array() )
		{
			return str_replace(
				array_map( array( get_class(), 'add_curly_braces' ), array_keys( $tags ) ),
				array_values( $tags ),
				$string
			);
		}
		
		/**
		 * Takes html template file and renders it with the given attributes
		 */
		public static function render_file( $template_filepath, $attributes = array() )
		{
			return self::replace_tags( file_get_contents( $template_filepath ) , $attributes );
		}
		
		/**
		 * Renders the contents of a thickbox that comes up when user clicks the tag in the form editor
		 */
		public function render_tag_generator( $contact_form, $args = '' )
		{
			$messages = '';
			$service = $this->get_service();
			if( $service && is_callable( array( $service, 'thickbox_messages' ) ) )
				$messages .= $service->thickbox_messages();
			
			$args = wp_parse_args( $args, array() );
			if( class_exists('WPCF7_TagGenerator') )
				echo self::render( 'add_pdf', array(
					'post-id' => esc_html( $contact_form->id() ),
					'messages' => $messages,
					'instructions' => esc_html__( "You can use this tag generator to attach a PDF file to your form, insert tags into your form and link them to fields in the PDF file.  You can also embed images (generated by other Contact Form 7 tags or fields) into the PDF file.  Changes here are applied when the contact form is saved.", 'pdf-forms-for-contact-form-7' ),
					'attach-pdf' => esc_html__( "Attach a PDF File", 'pdf-forms-for-contact-form-7' ),
					'insert-tags' => esc_html__( "Insert Tags", 'pdf-forms-for-contact-form-7' ),
					'insert-tag' => esc_html__( "Insert and Link", 'pdf-forms-for-contact-form-7' ),
					'generate-and-insert-all-tags-message' => esc_html__( "This button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.", 'pdf-forms-for-contact-form-7' ),
					'insert-and-map-all-tags' => esc_html__( "Insert & Link All", 'pdf-forms-for-contact-form-7' ),
					'delete' => esc_html__( 'Delete', 'pdf-forms-for-contact-form-7' ),
					'options' => esc_html__( 'Options', 'pdf-forms-for-contact-form-7' ),
					'skip-when-empty' => esc_html__( 'Skip when empty', 'pdf-forms-for-contact-form-7' ),
					'attach-to-mail-1' => esc_html__( 'Attach to primary email message', 'pdf-forms-for-contact-form-7' ),
					'attach-to-mail-2' => esc_html__( 'Attach to secondary email message', 'pdf-forms-for-contact-form-7' ),
					'flatten' => esc_html__( 'Flatten', 'pdf-forms-for-contact-form-7' ),
					'filename' => esc_html__( 'Filename (mail-tags can be used)', 'pdf-forms-for-contact-form-7' ),
					'save-directory'=> esc_html__( 'Save PDF file on the server at the given path relative to wp-content/uploads (mail-tags can be used; if empty, PDF file is not saved on disk)', 'pdf-forms-for-contact-form-7' ),
					'download-link' => esc_html__( 'Add filled PDF download link to form submission response', 'pdf-forms-for-contact-form-7' ),
					'field-mapping' => esc_html__( 'Field Mapper Tool', 'pdf-forms-for-contact-form-7' ),
					'field-mapping-help' => esc_html__( 'This tool can be used to link Contact Form 7 fields with fields within the PDF files.  Contact Form 7 fields can also be generated.  When the user submits the form, data from Contact Form 7 fields will be inserted into correspoinding fields in the PDF file.', 'pdf-forms-for-contact-form-7' ),
					'pdf-field' => esc_html__( 'PDF field', 'pdf-forms-for-contact-form-7' ),
					'cf7-field' => esc_html__( 'CF7 field', 'pdf-forms-for-contact-form-7' ),
					'add-mapping' => esc_html__( 'Add Mapping', 'pdf-forms-for-contact-form-7' ),
					'delete-all-mappings' => esc_html__( 'Delete All', 'pdf-forms-for-contact-form-7' ),
					'new-tag' => esc_html__( 'New Tag:', 'pdf-forms-for-contact-form-7' ),
					'tag-generator' => esc_html__( 'Tag Generator Tool (deprecated)', 'pdf-forms-for-contact-form-7' ),
					'tag-generator-help' => esc_html__( 'This tool allows one to create CF7 fields that are linked to PDF fields by name.  This feature is deprecated in favor of the field mapper tool.', 'pdf-forms-for-contact-form-7' ),
					'image-embedding' => esc_html__( 'Image Embedding Tool', 'pdf-forms-for-contact-form-7' ),
					'image-embedding-help'=> esc_html__( 'This tool allows embedding images into PDF files.  Images are taken from field attachments or field values that are URLs.  You must select a PDF file, its page and draw a bounding box for image insertion.', 'pdf-forms-for-contact-form-7' ),
					'add-cf7-field-embed' => esc_html__( 'Embed Image', 'pdf-forms-for-contact-form-7' ),
					'delete-cf7-field-embed' => esc_html__( 'Delete', 'pdf-forms-for-contact-form-7' ),
					'pdf-file' => esc_html__( 'PDF file', 'pdf-forms-for-contact-form-7' ),
					'page' => esc_html__( 'Page', 'pdf-forms-for-contact-form-7' ),
					'image-region-selection-hint' => esc_html__( 'Select a region where the image needs to be embeded.', 'pdf-forms-for-contact-form-7' ),
					'help-message' => self::replace_tags(
						esc_html__( "Have a question/comment/problem?  Feel free to use {a-href-forum}the support forum{/a} and view {a-href-tutorial}the tutorial video{/a}.", 'pdf-forms-for-contact-form-7' ),
						array(
							'a-href-forum' => '<a href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/" target="_blank">',
							'a-href-tutorial' => '<a href="https://youtu.be/jy84xqnj0Zk" target="_blank">',
							'/a' => '</a>',
						)
					),
					'show-help' => esc_html__( 'Show Help', 'pdf-forms-for-contact-form-7' ),
					'hide-help' => esc_html__( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
					'get-tags' => esc_html__( 'Get Tags', 'pdf-forms-for-contact-form-7' ),
					'all-pdfs' => esc_html__( 'All PDFs', 'pdf-forms-for-contact-form-7' ),
					'return-to-form' => esc_html__( "Return to Form", 'pdf-forms-for-contact-form-7' ),
				) );
			// support for older CF7 versions
			else
				echo self::render( 'add_pdf_unsupported', array(
					'unsupported-message' => esc_html__( 'Your CF7 plugin is too out of date, please upgrade.', 'pdf-forms-for-contact-form-7' ),
				) );
		}
		
		/**
		 * Helper functions that are used to convert between contact form field names and PDF form field names
		 */
		public static function base64url_encode( $data )
		{
			return rtrim( strtr( base64_encode( $data ), '+/', '._' ), '=' );
		}
		public static function base64url_decode( $data )
		{
			return base64_decode( str_pad( strtr( $data, '._', '+/' ), strlen( $data ) % 4, '=', STR_PAD_RIGHT ) );
		}
		public static function wpcf7_field_name_encode( $attachment_id, $pdf_field_name )
		{
			$slug = sanitize_title( $pdf_field_name );
			return "pdf-field-" . $attachment_id . "-" . $slug . "-" . self::base64url_encode( $pdf_field_name );
		}
		public static function wpcf7_field_name_decode( $wpcf7_field_name )
		{
			if( !preg_match("/^pdf-field-(\d+|all)(-.+)?-([A-Za-z0-9\._]+)$/u", $wpcf7_field_name, $matches) )
				return FALSE;
			
			$attachment_id = $matches[1];
			$field = self::base64url_decode( $matches[3] );
			if( $field === FALSE )
				return FALSE;
			
			return array( 'attachment_id' => $attachment_id, 'field' => $field, 'encoded_field' => $matches[3] );
		}
		
		/*
		 * WPCF7 hook for adding some more information to response message
		 */
		public function change_response_message( $contact_form )
		{
			// if downloads variable is not initialized then we don't need to do anything
			if( $this->downloads )
			{
				$submission = WPCF7_Submission::get_instance();
				$response = $submission->get_response();
				if( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
				{
					// ajax request
					$response .= "<br/>";
					foreach( $this->downloads->get_files() as $file )
						$response .= "<br/>" .
							self::replace_tags(
								esc_html__( "{icon} {a-href-url}{filename}{/a} {i}({size}){/i}", 'pdf-forms-for-contact-form-7' ),
								array(
									'icon' => '<span class="dashicons dashicons-download"></span>',
									'a-href-url' => '<a href="' . esc_html( $file['url'] ) . '" download>',
									'filename' => esc_html( $file['filename'] ),
									'/a' => '</a>',
									'i' => '<i>',
									'size' => esc_html( size_format( filesize( $file['filepath'] ) ) ),
									'/i' => '</i>',
								)
							);
				}
				else
				{
					// non-ajax request
					$response .= "\n";
					foreach( $this->downloads->get_files() as $file )
						// no need to escape html because output gets escaped by WPCF7 code in this case, $response is plain text
						$response .= "\n" . self::replace_tags( __( "Download {filename} at {url}", 'pdf-forms-for-contact-form-7' ), array( 'filename' => $file['filename'], 'url' => $file['url'] ) );
				}
				$submission->set_response( $response );
				
				// make sure to enable cron if it is down so that old download files get cleaned up
				$this->enable_cron();
			}
		}
		
		/**
		 * Executes scheduled tasks
		 */
		public function cron()
		{
			$this->get_downloads()->delete_old_downloads();
		}
	}
	
	WPCF7_Pdf_Forms::get_instance();
}
