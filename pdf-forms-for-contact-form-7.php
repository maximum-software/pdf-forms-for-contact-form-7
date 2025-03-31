<?php
/**
 * Plugin Name: PDF Forms Filler for CF7
 * Plugin URI: https://pdfformsfiller.org/
 * Description: Build Contact Form 7 forms from PDF forms. Get PDFs auto-filled and attached to email messages and/or website responses on form submission.
 * Version: 2.2.2
 * Requires at least: 4.8
 * Requires PHP: 5.2
 * Requires Plugins: contact-form-7
 * Author: Maximum.Software
 * Author URI: https://maximum.software/
 * Text Domain: pdf-forms-for-contact-form-7
 * Domain Path: /languages
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if( ! defined( 'ABSPATH' ) )
	return;

require_once untrailingslashit( dirname( __FILE__ ) ) . '/inc/tgm-config.php';

if( ! class_exists( 'WPCF7_Pdf_Forms' ) )
{
	class WPCF7_Pdf_Forms
	{
		const VERSION = '2.2.2';
		const MIN_WPCF7_VERSION = '5.0';
		const MAX_WPCF7_VERSION = '6.0.99';
		private static $BLACKLISTED_WPCF7_VERSIONS = array();
		
		private static $instance = null;
		private $pdf_ninja_service = null;
		private $service = null;
		private $registered_services = false;
		private $downloads = null;
		private $storage = null;
		private $tmp_dir = null;
		private $cf7_forms_save_overrides = null;
		private $cf7_mail_attachments = array();
		
		private function __construct()
		{
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'plugins_loaded', array( $this, 'plugin_init' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 99, 2 );
			register_activation_hook( __FILE__, array( $this, 'plugin_activated' ) );
			register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivated' ) );
			add_action( 'wpcf7_pdf_forms_cron', array( $this, 'cron' ) );
		}
		
		/**
		 * Returns a global instance of this class
		 */
		public static function get_instance()
		{
			if( !self::$instance )
				self::$instance = new self;
			
			return self::$instance;
		}
		
		/**
		 * Runs after all plugins have been loaded
		 */
		public function plugin_init()
		{
			add_action( 'init', array( $this, 'load_textdomain' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			
			if( ! class_exists('WPCF7') || ! defined( 'WPCF7_VERSION' ) )
				return;
			
			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ) );
			add_filter( 'wpcf7_form_elements', array( $this, 'front_end_form_scripts' ) );
			
			add_action( 'wp_ajax_wpcf7_pdf_forms_get_attachment_info', array( $this, 'wp_ajax_get_attachment_info' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_tags', array( $this, 'wp_ajax_query_tags' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_preload_data', array( $this, 'wp_ajax_preload_data' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_cf7_fields', array( $this, 'wp_ajax_query_cf7_fields' ) );
			add_action( 'wp_ajax_wpcf7_pdf_forms_query_page_image', array( $this, 'wp_ajax_query_page_image' ) );
			
			add_action( 'admin_init', array( $this, 'extend_tag_generator' ), 80 );
			add_action( 'admin_menu', array( $this, 'register_services') );
			add_filter( 'wpcf7_editor_panels', array( $this, 'editor_panels'), 10, 1 );
			
			add_action( 'wpcf7_before_send_mail', array( $this, 'fill_pdfs' ), 1000, 3 );
			if( version_compare( WPCF7_VERSION, '5.4.1' ) < 0 )
				// WPCF7_Submission::add_extra_attachments didn't exist prior to CF7 v5.4.1 and a workaround is needed
				add_filter( 'wpcf7_mail_components', array( $this, 'attach_files' ), 10, 3 );
			add_action( 'wpcf7_mail_sent', array( $this, 'remove_tmp_dir' ), 99, 0 );
			
			add_action( 'wpcf7_after_save', array( $this, 'update_post_attachments' ) );
			
			add_filter( 'wpcf7_form_response_output', array( $this, 'change_response_nojs' ), 10, 4 );
			
			if( version_compare( WPCF7_VERSION, '5.2' ) >= 0 )
				// hook wpcf7_feedback_response (works only with CF7 version 5.2+)
				add_filter( 'wpcf7_feedback_response', array( $this, 'change_response_js' ), 10, 2 );
			else
				// hook wpcf7_ajax_json_echo (needed only for CF7 versions < 5.2)
				add_filter( 'wpcf7_ajax_json_echo', array( $this, 'change_response_js' ), 10, 2 );
			
			// hook that allows to copy media and mapping
			add_filter( 'wpcf7_copy', array( $this, 'duplicate_form_hook' ), 10, 2 );
			
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
			
			// TODO: allow users to run this manually
			add_action( 'admin_init', array( $this, 'upgrade_data' ) );
			
			// handle change_response_nojs() hidden iframe download requests
			if( isset( $_GET['wpcf7-pdf-forms-download'] ) )
				$this->handle_hidden_iframe_download();
		}
		
		/**
		 * Loads plugin textdomain
		 */
		public function load_textdomain()
		{
			load_plugin_textdomain( 'pdf-forms-for-contact-form-7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
		
		/**
		 * Runs after the plugin have been activated/deactivated
		 */
		public function plugin_activated( $network_wide = false )
		{
			if( $network_wide )
			{
				$sites = get_sites( array( 'fields' => 'ids' ) );
				foreach( $sites as $id )
				{
					switch_to_blog( $id );
					$this->plugin_activated( false );
					restore_current_blog();
				}
				return;
			}
			
			$this->enable_cron();
		}
		public function plugin_deactivated( $network_deactivating = false )
		{
			if( $network_deactivating )
			{
				$sites = get_sites( array( 'fields' => 'ids' ) );
				foreach( $sites as $id )
				{
					switch_to_blog( $id );
					$this->plugin_deactivated( false );
					restore_current_blog();
				}
				return;
			}
			
			$this->disable_cron();
			$this->get_downloads()->set_timeout(0)->delete_old_downloads();
		}
		
		/**
		 * Hook that adds a cron schedule
		 */
		public function cron_schedules( $schedules )
		{
			$interval = $this->get_downloads()->get_timeout();
			$display = self::replace_tags( __( "Every {interval} seconds", 'pdf-forms-for-contact-form-7' ), array( 'interval' => $interval ) );
			$schedules['wpcf7_pdf_forms_cron_frequency'] = array(
				'interval' => $interval,
				'display' => $display
			);
			return $schedules;
		}
		
		/**
		 * Enables cron
		 */
		private function enable_cron()
		{
			$due = wp_next_scheduled( 'wpcf7_pdf_forms_cron' );
			$current_time = time();
			
			$interval = $this->get_downloads()->get_timeout();
			
			if( $due !== false && (
				   $due < $current_time - ( $interval + 60 ) // cron is not functional
				|| $due > $current_time + $interval // interval changed to a smaller value
			) )
			{
				$this->cron(); // run manually
				wp_clear_scheduled_hook( 'wpcf7_pdf_forms_cron' );
				$due = false;
			}
			
			if( $due === false )
				wp_schedule_event( $current_time, 'wpcf7_pdf_forms_cron_frequency', 'wpcf7_pdf_forms_cron' );
		}
		
		/**
		 * Disables cron
		 */
		private function disable_cron()
		{
			wp_clear_scheduled_hook( 'wpcf7_pdf_forms_cron' );
		}
		
		/**
		 * Executes scheduled tasks
		 */
		public function cron()
		{
			$this->get_downloads()->delete_old_downloads();
		}
		
		/**
		 * Runs after plugin updates and triggers data migration
		 */
		public function upgrader_process_complete( $upgrader, $hook_extra )
		{
			$plugin_path = plugin_basename( __FILE__ );
			
			if( $hook_extra['action'] == 'update'
			&& $hook_extra['type'] == 'plugin'
			&& isset( $hook_extra['plugins'] )
			&& is_array( $hook_extra['plugins'] ) )
			{
				foreach( $hook_extra['plugins'] as $plugin )
				{
					if( $plugin == $plugin_path )
					{
						set_transient( 'wpcf7_pdf_forms_updated_old_version', self::VERSION );
						break;
					}
				}
			}
		}
		
		/**
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
		
		/**
		 * Runs data migration when triggered
		 */
		public function upgrade_data()
		{
			if( wp_doing_ajax() )
				return;
			
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
			try { include( $script ); }
			catch( Exception $e ) { }
		}
		
		/**
		 * Checks if CF7 version is supported
		 */
		public function is_wpcf7_version_supported( $version )
		{
			if( version_compare( $version, self::MIN_WPCF7_VERSION ) < 0
			|| version_compare( $version, self::MAX_WPCF7_VERSION ) > 0 )
				return false;
			
			foreach( self::$BLACKLISTED_WPCF7_VERSIONS as $blacklisted_version )
				if( version_compare( $version, $blacklisted_version ) == 0 )
					return false;
			
			return true;
		}
		
		/**
		 * Adds plugin action links
		 */
		public function action_links( $links )
		{
			$links[] = '<a target="_blank" href="https://pdfformsfiller.org/docs/cf7/">'.esc_html__( "Docs", 'pdf-forms-for-contact-form-7' ).'</a>';
			$links[] = '<a target="_blank" href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/">'.esc_html__( "Support", 'pdf-forms-for-contact-form-7' ).'</a>';
			return $links;
		}
		
		/**
		 * Prints admin notices
		 */
		public function admin_notices()
		{
			if( ( ! class_exists('WPCF7') || ! defined( 'WPCF7_VERSION' ) ) )
			{
				if( current_user_can( 'activate_plugins' ) )
					echo WPCF7_Pdf_Forms::render_error_notice( 'cf7-not-installed', array(
						'label' => esc_html__( "PDF Forms Filler for CF7 plugin error", 'pdf-forms-for-contact-form-7' ),
						'message' => esc_html__( "The required plugin 'Contact Form 7' version is not installed!", 'pdf-forms-for-contact-form-7' ),
					) );
				return;
			}
			
			if( ! $this->is_wpcf7_version_supported( WPCF7_VERSION ) )
				if( current_user_can( 'activate_plugins' ) )
					echo WPCF7_Pdf_Forms::render_warning_notice( 'unsupported-cf7-version-'.WPCF7_VERSION, array(
								'label'   => esc_html__( "PDF Forms Filler for CF7 plugin warning", 'pdf-forms-for-contact-form-7' ),
								'message' =>
									self::replace_tags(
										esc_html__( "The currently installed version of 'Contact Form 7' plugin ({current-wpcf7-version}) may not be supported by the current version of 'PDF Forms Filler for CF7' plugin ({current-plugin-version}), please switch to 'Contact Form 7' plugin version {supported-wpcf7-version} or below to ensure that 'PDF Forms Filler for CF7' plugin would work correctly.", 'pdf-forms-for-contact-form-7' ),
										array(
											'current-wpcf7-version' => esc_html( defined( 'WPCF7_VERSION' ) ? WPCF7_VERSION : __( "Unknown version", 'pdf-forms-for-contact-form-7' ) ),
											'current-plugin-version' => esc_html( self::VERSION ),
											'supported-wpcf7-version' => esc_html( self::MAX_WPCF7_VERSION ),
										)
									),
							) );
			
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
			wp_register_script( 'wpcf7_pdf_forms_notices_script', plugin_dir_url( __FILE__ ) . 'js/notices.js', array( 'jquery' ), self::VERSION );
			wp_enqueue_script( 'wpcf7_pdf_forms_notices_script' );
			
			if( ! class_exists('WPCF7') || ! defined( 'WPCF7_VERSION' ) )
				return;
			
			if( false !== strpos($hook, 'wpcf7') )
			{
				wp_register_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.13');
				wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2/select2.min.js', array( 'jquery' ), '4.0.13');
				
				wp_register_script( 'wpcf7_pdf_forms_admin_script', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery', 'jcrop', 'select2' ), self::VERSION );
				wp_register_style( 'wpcf7_pdf_forms_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin.css', array( 'jcrop', 'select2' ), self::VERSION );
				
				wp_localize_script( 'wpcf7_pdf_forms_admin_script', 'wpcf7_pdf_forms', array(
					'WPCF7_VERSION' => WPCF7_VERSION,
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'wpcf7-pdf-forms-ajax-nonce' ),
					'__Unknown_error' => __( 'Unknown error', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Attachment' => __( 'Are you sure you want to delete this file?  This will delete field mappings and image embeds associated with this file.', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Mapping' => __( 'Are you sure you want to delete this mapping?', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_All_Mappings' => __( 'Are you sure you want to delete all mappings?', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_All_Value_Mappings' => __( 'Are you sure you want to delete all value mappings?', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Attach_Empty_Pdf' => __( 'Are you sure you want to attach a PDF file without any form fields?', 'pdf-forms-for-contact-form-7' ),
					'__Confirm_Delete_Embed' => __( 'Are you sure you want to delete this embeded image?', 'pdf-forms-for-contact-form-7' ),
					'__Show_Help' => __( 'Show Help', 'pdf-forms-for-contact-form-7' ),
					'__Hide_Help' => __( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
					'__Show_Tag_Generator_Tool' => __( 'Show Tag Generator', 'pdf-forms-for-contact-form-7' ),
					'__Hide_Tag_Generator_Tool' => __( 'Hide Tag Generator', 'pdf-forms-for-contact-form-7' ),
					'__PDF_Frame_Title' => __( 'Select a PDF file', 'pdf-forms-for-contact-form-7'),
					'__PDF_Frame_Button' => __( 'Select', 'pdf-forms-for-contact-form-7'),
					'__Custom_String' => __( "Custom text string...", 'pdf-forms-for-contact-form-7' ),
					'__All_PDFs' => __( 'All PDFs', 'pdf-forms-for-contact-form-7' ),
					'__All_Pages' => __( 'All', 'pdf-forms-for-contact-form-7' ),
					'__Null_Value_Mapping' => __( '--- EMPTY ---', 'pdf-forms-for-contact-form-7' ),
				) );
				
				add_thickbox();
				wp_enqueue_media();
				
				wp_enqueue_script( 'wpcf7_pdf_forms_admin_script' );
				wp_enqueue_style( 'wpcf7_pdf_forms_admin_style' );
			}
		}
		
		/**
		 * Adds necessary global front-end scripts and styles
		*/
		function front_end_enqueue_scripts()
		{
			wp_enqueue_style( 'dashicons' ); // needed by the download link feature
		}
		
		/**
		 * Adds necessary front-end scripts and styles (only when CF7 form is displayed)
		*/
		function front_end_form_scripts( $form )
		{
			static $form_count = 0;
			$form_count++;
			if( $form_count == 1 ) // add only once
			{
				$style = '<link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . 'css/frontend.css' . '" />';
				$script = '<script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . 'js/frontend.js' . '?ver=' . self::VERSION . '"></script>';
				$form = $style . $script . $form;
			}
			
			return $form;
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
		
		/**
		 * Function for working with metadata
		 */
		public static function get_meta( $post_id, $key )
		{
			$value = get_post_meta( $post_id, "wpcf7-pdf-forms-" . $key, $single=true );
			if( $value === '' )
				return null;
			return $value;
		}
		
		/**
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
				// https://developer.wordpress.org/reference/functions/update_post_meta/#workaround
				$fixed_value = wp_slash( $value );
				
				update_post_meta( $post_id, "wpcf7-pdf-forms-" . $key, $fixed_value, $oldval );
			}
			return $value;
		}
		
		/**
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
		
		private static $pdf_options = array('skip_empty' => false, 'attach_to_mail_1' => true, 'attach_to_mail_2' => false, 'flatten' => false, 'filename' => "", 'save_directory'=>"", 'download_link' => false, 'download_link_auto' => false );
		private static $public_pdf_options = array('download_link', 'download_link_auto');
		
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
			
			$value_mappings = self::get_meta( $prev_post_id, 'value_mappings' );
			if( $value_mappings )
				$value_mappings = json_decode( $value_mappings, true );
			if( !is_array( $value_mappings ) )
				$value_mappings = array();
			
			$embeds = self::get_meta( $prev_post_id, 'embeds' );
			if( $embeds )
				$embeds = json_decode( $embeds, true );
			if( !is_array( $embeds ) )
				$embeds = array();
			
			$this->cf7_forms_save_overrides = array( 'attachments' => $attachments , 'mappings' => $mappings , 'value_mappings' => $value_mappings , 'embeds' => $embeds );
			
			return $new;
		}
		
		/**
		 * Adds editor panels
		 */
		function editor_panels( $panels )
		{
			$panels += array(
				'wpcf7-forms-panel' => array(
					'title' => __( "PDF Forms Filler", 'pdf-forms-for-contact-form-7' ),
					'callback' => array( $this, 'render_settings_panel' ),
				),
			);
			
			return $panels;
		}
		
		/**
		 * Renders the settings panel
		 */
		function render_settings_panel()
		{
			$messages = '';
			
			$service = $this->get_service();
			if( $service && is_callable( array( $service, 'thickbox_messages' ) ) )
				$messages .= $service->thickbox_messages();
			
			echo self::render( 'settings_panel', array(
				'post-id' => esc_html( WPCF7_ContactForm::get_current()->id() ),
				'pdf-forms-filler-title' => esc_html__( "PDF Forms Filler", 'pdf-forms-for-contact-form-7' ),
				'messages' => $messages,
				'instructions' => esc_html__( "You can use this panel to attach a PDF file to this contact form and link form-tags and mail-tags to fields in the PDF file. It is possible to link a combination of mail-tags to PDF fields. You can also embed images (from URLs or attached files) into the PDF file. Changes here are applied when the contact form is saved.", 'pdf-forms-for-contact-form-7' ),
				'attach-pdf' => esc_html__( "Attach a PDF File", 'pdf-forms-for-contact-form-7' ),
				'delete' => esc_html__( 'Delete', 'pdf-forms-for-contact-form-7' ),
				'map-value' => esc_html__( 'Map Value', 'pdf-forms-for-contact-form-7' ),
				'options' => esc_html__( 'Options', 'pdf-forms-for-contact-form-7' ),
				'skip-when-empty' => esc_html__( 'Skip when empty', 'pdf-forms-for-contact-form-7' ),
				'attach-to-mail-1' => esc_html__( 'Attach to primary email message', 'pdf-forms-for-contact-form-7' ),
				'attach-to-mail-2' => esc_html__( 'Attach to secondary email message', 'pdf-forms-for-contact-form-7' ),
				'flatten' => esc_html__( 'Flatten', 'pdf-forms-for-contact-form-7' ),
				'filename' => esc_html__( 'Filename (mail-tags can be used)', 'pdf-forms-for-contact-form-7' ),
				'save-directory'=> esc_html__( 'Save PDF file on the server at the given path relative to wp-content/uploads (mail-tags can be used; if empty, PDF file is not saved on disk)', 'pdf-forms-for-contact-form-7' ),
				'download-link' => esc_html__( 'Add filled PDF download link to form submission response', 'pdf-forms-for-contact-form-7' ),
				'download-link-auto' => esc_html__( 'Trigger an automatic download of the filled PDF file on a successful form submission response', 'pdf-forms-for-contact-form-7' ),
				'field-mapping' => esc_html__( 'Field Mapper Tool', 'pdf-forms-for-contact-form-7' ),
				'field-mapping-help' => esc_html__( 'This tool can be used to link form fields and mail-tags to fields in the attached PDF files. When your users submit the form, input from form fields and other mail-tags will be inserted into the corresponding fields in the PDF file. CF7 to PDF field value mappings can also be created to enable the replacement of CF7 data when PDF fields are filled.', 'pdf-forms-for-contact-form-7' ),
				'pdf-field' => esc_html__( 'PDF field', 'pdf-forms-for-contact-form-7' ),
				'cf7-field-or-mail-tags' => esc_html__( 'CF7 field/mail-tags', 'pdf-forms-for-contact-form-7' ),
				'add-mapping' => esc_html__( 'Add Mapping', 'pdf-forms-for-contact-form-7' ),
				'delete-all' => esc_html__( 'Delete All', 'pdf-forms-for-contact-form-7' ),
				'insert-tag' => esc_html__( "Insert and Link", 'pdf-forms-for-contact-form-7' ),
				'generate-and-insert-all-tags-message' => esc_html__( "This button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.", 'pdf-forms-for-contact-form-7' ),
				'insert-and-map-all-tags' => esc_html__( "Insert & Link All", 'pdf-forms-for-contact-form-7' ),
				'image-embedding' => esc_html__( 'Image Embedding Tool', 'pdf-forms-for-contact-form-7' ),
				'image-embedding-help'=> esc_html__( 'This tool allows embedding of images into PDF files. Images are taken from file upload fields or URL field values. You can select a PDF file page and draw a bounding box for image insertion. Alternatively, you can insert your image in the center of every page.', 'pdf-forms-for-contact-form-7' ),
				'add-cf7-field-embed' => esc_html__( 'Embed Image', 'pdf-forms-for-contact-form-7' ),
				'delete-cf7-field-embed' => esc_html__( 'Delete', 'pdf-forms-for-contact-form-7' ),
				'pdf-file' => esc_html__( 'PDF file', 'pdf-forms-for-contact-form-7' ),
				'page' => esc_html__( 'Page', 'pdf-forms-for-contact-form-7' ),
				'image-region-selection-hint' => esc_html__( 'Select a region where the image needs to be embeded.', 'pdf-forms-for-contact-form-7' ),
				'top' => esc_html__( 'Top', 'pdf-forms-for-contact-form-7' ),
				'left' => esc_html__( 'Left', 'pdf-forms-for-contact-form-7' ),
				'width' => esc_html__( 'Width', 'pdf-forms-for-contact-form-7' ),
				'height' => esc_html__( 'Height', 'pdf-forms-for-contact-form-7' ),
				'pts' => esc_html__( 'pts', 'pdf-forms-for-contact-form-7' ),
				'help-message' => self::replace_tags(
					esc_html__( "Have a question/comment/problem?  Feel free to use {a-href-forum}the support forum{/a} and view {a-href-tutorial}the tutorial video{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-forum' => '<a href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/" target="_blank">',
						'a-href-tutorial' => '<a href="https://youtu.be/rATOSROQAGU" target="_blank">',
						'/a' => '</a>',
					)
				),
				'show-help' => esc_html__( 'Show Help', 'pdf-forms-for-contact-form-7' ),
				'hide-help' => esc_html__( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
			) );
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
									
									// replace old attachment id in value mappings
									if( isset( $data['value_mappings'] ) && is_array( $data['value_mappings'] ) )
									{
										foreach( $data['value_mappings'] as &$value_mapping )
											if( isset( $value_mapping['pdf_field'] ) )
												$value_mapping['pdf_field'] = preg_replace( '/^' . preg_quote( $attachment_id . '-' ) . '/i', intval( $new_attachment_id ) . '-', $value_mapping['pdf_field'] );
										unset($value_mapping);
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
				{
					if( isset( $mapping['cf7_field'] ) && isset( $mapping['pdf_field'] ) )
						if( self::wpcf7_field_name_decode( $mapping['cf7_field'] ) === FALSE )
							$mappings[] = array( 'cf7_field' => $mapping['cf7_field'], 'pdf_field' => $mapping['pdf_field'] );
					if( isset( $mapping['mail_tags'] ) && isset( $mapping['pdf_field'] ) )
						$mappings[] = array( 'mail_tags' => $mapping['mail_tags'], 'pdf_field' => $mapping['pdf_field'] );
				}
				self::set_meta( $post_id, 'mappings', self::json_encode( $mappings ) );
			}
			
			if( isset( $data['value_mappings'] ) && is_array( $new_value_mappings = $data['value_mappings'] ) )
			{
				$value_mappings = array();
				foreach( $new_value_mappings as $value_mapping )
				{
					if( isset( $value_mapping['pdf_field'] ) && isset( $value_mapping['cf7_value'] ) && isset( $value_mapping['pdf_value'] ) )
						$value_mappings[] = array( 'pdf_field' => $value_mapping['pdf_field'], 'cf7_value' => $value_mapping['cf7_value'], 'pdf_value' => $value_mapping['pdf_value'] );
				}
				self::set_meta( $post_id, 'value_mappings', self::json_encode( $value_mappings ) );
			}
			
			if( isset( $data['embeds'] ) && is_array( $new_embeds = $data['embeds'] ) )
			{
				$embeds = array();
				foreach( $new_embeds as $embed )
					if( (isset( $embed['cf7_field'] ) || isset( $embed['mail_tags'] )) && isset( $embed['attachment_id'] ) )
						$embeds[] = $embed;
				self::set_meta( $post_id, 'embeds', self::json_encode( $embeds ) );
			}
		}
		
		/**
		 * Downloads a file from the given URL and saves the contents to the given filepath, returns mime type via argument
		 */
		private static function download_file( $url, $filepath, &$mimetype = null )
		{
			// if this url points to the site, copy the file directly
			$site_url = trailingslashit( get_site_url() );
			if( substr( $url, 0, strlen( $site_url ) ) == $site_url )
			{
				$path = substr( $url, strlen( $site_url ) );
				$home_path = trailingslashit( realpath( dirname(__FILE__) . '/../../../' ) );
				$sourcepath = realpath( $home_path . $path );
				if( $home_path && $sourcepath && substr( $sourcepath, 0, strlen( $home_path ) ) == $home_path )
					if( is_file( $sourcepath ) )
						if( copy( $sourcepath, $filepath ) )
						{
							$mimetype = self::get_mime_type( $sourcepath );
							return;
						}
			}
			
			$args = array(
				'compress'    => true,
				'decompress'  => true,
				'timeout'     => 100,
				'redirection' => 5,
				'user-agent'  => 'wpcf7-pdf-forms/' . self::VERSION,
			);
			
			$response = wp_remote_get( $url, $args );
			
			if( is_wp_error( $response ) )
				throw new Exception(
					self::replace_tags(
							__( "Failed to download file: {error-message}", 'pdf-forms-for-contact-form-7' ),
							array( 'error-message' => $response->get_error_message() )
						)
				);
			
			$mimetype = wp_remote_retrieve_header( $response, 'content-type' );
			
			$body = wp_remote_retrieve_body( $response );
			
			if( file_put_contents( $filepath, $body ) === false || ! is_file( $filepath ) )
				throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
		}
		
		/**
		 * Get temporary directory path
		 */
		public static function get_tmp_path()
		{
			static $uploads_dir;
			if( ! $uploads_dir )
			{
				wpcf7_init_uploads(); // Confirm upload dir
				$uploads_dir = wpcf7_upload_tmp_dir();
			}
			
			return trailingslashit( $uploads_dir );
		}
		
		/**
		 * Creates a temporary directory
		 */
		public function create_tmp_dir()
		{
			if( ! $this->tmp_dir )
				$this->tmp_dir = wpcf7_maybe_add_random_dir( self::get_tmp_path() );
			
			return trailingslashit( $this->tmp_dir );
		}
		
		/**
		 * Removes a temporary directory
		 */
		public function remove_tmp_dir()
		{
			if( ! $this->tmp_dir )
				return;
			
			// remove files in the directory
			$tmp_dir_slash = trailingslashit( $this->tmp_dir );
			$files = array_merge( glob( $tmp_dir_slash . '*' ), glob( $tmp_dir_slash . '.*' ) );
			while( $file = array_shift( $files ) )
				if( is_file( $file ) )
					@unlink( $file );
			
			@rmdir( $this->tmp_dir );
			$this->tmp_dir = null;
		}
		
		/**
		 * Creates a temporary file path (but not the file itself)
		 */
		private function create_tmp_filepath( $filename )
		{
			$uploads_dir = $this->create_tmp_dir();
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $uploads_dir, $filename );
			return trailingslashit( $uploads_dir ) . $filename;
		}
		
		/**
		 * Returns MIME type of the file
		 */
		public static function get_mime_type( $filepath )
		{
			if( ! is_file( $filepath ) )
				return null;
			
			$mimetype = null;
			
			if( function_exists( 'finfo_open' ) )
			{
				if( version_compare( phpversion(), "5.3" ) < 0 )
				{
					$finfo = finfo_open( FILEINFO_MIME );
					if( $finfo )
					{
						$mimetype = finfo_file( $finfo, $filepath );
						$mimetype = explode( ";", $mimetype );
						$mimetype = reset( $mimetype );
						finfo_close( $finfo );
					}
				}
				else
				{
					$finfo = finfo_open( FILEINFO_MIME_TYPE );
					if( $finfo )
					{
						$mimetype = finfo_file( $finfo, $filepath );
						finfo_close( $finfo );
					}
				}
			}
			
			if( ! $mimetype && function_exists( 'mime_content_type' ) )
				$mimetype = mime_content_type( $filepath );
			
			// fallback
			if( ! $mimetype )
			{
				$type = wp_check_filetype( $filepath );
				if( isset( $type['type'] ) )
					$mimetype = $type['type'];
			}
			
			if( ! $mimetype )
				return 'application/octet-stream';
			
			return $mimetype;
		}
		
		/**
		 * Checks if the image MIME type is supported for embedding
		 */
		private function is_embed_image_format_supported( $mimetype )
		{
			$supported_mime_types = array(
					"image/jpeg",
					"image/png",
					"image/gif",
					"image/tiff",
					"image/bmp",
					"image/x-ms-bmp",
					"image/svg+xml",
					"image/webp",
					"application/pdf",
				);
			
			if( $mimetype )
				foreach( $supported_mime_types as $smt )
					if( $mimetype === $smt )
						return true;
			
			return false;
		}
		
		/**
		 * Wrapper for CF7's wpcf7_mail_replace_tags()
		 */
		public static function wpcf7_mail_replace_tags( $content, $args = '' )
		{
			$content = wpcf7_mail_replace_tags( $content, $args );
			
			// this is a hack to make conditional fields plugin's groups work
			// see https://wordpress.org/support/topic/conditional-fields-cf7-support/
			// see https://github.com/rocklobster-in/contact-form-7/issues/1071
			// remove the following after the above issue is resolved
			if( class_exists( 'Wpcf7cfMailParser' )
			&& isset( $_POST['_wpcf7cf_hidden_groups'] )
			&& isset( $_POST['_wpcf7cf_visible_groups'] )
			&& isset( $_POST['_wpcf7cf_repeaters'] ) )
			{
				$hidden_groups = json_decode( wp_unslash( $_POST['_wpcf7cf_hidden_groups'] ) );
				$visible_groups = json_decode( wp_unslash( $_POST['_wpcf7cf_visible_groups'] ) );
				$repeaters = json_decode( wp_unslash( $_POST['_wpcf7cf_repeaters'] ) );
				try
				{
					$parser = new Wpcf7cfMailParser( $content, $visible_groups, $hidden_groups, $repeaters, $_POST );
					if( method_exists( $parser, 'getParsedMail' ) )
						$content = $parser->getParsedMail();
				}
				catch(Exception $e) { } // ignore
				catch(Throwable $e) { } // ignore
			}
			
			return $content;
		}
		
		/**
		 * When form data is posted, this function communicates with the API
		 * to fill the form data and get the PDF file with filled form fields
		 * 
		 * Files created in this function will be deleted automatically by
		 * CF7 after it sends the email message
		 */
		public function fill_pdfs( $contact_form, &$abort, $submission )
		{
			try
			{
				$post_id = $contact_form->id();
				$mail = $contact_form->prop( "mail" );
				$mail2 = $contact_form->prop( "mail_2" );
				
				$mappings = self::get_meta( $post_id, 'mappings' );
				if( $mappings )
					$mappings = json_decode( $mappings, true );
				if( !is_array( $mappings ) )
					$mappings = array();
				
				$value_mappings = self::get_meta( $post_id, 'value_mappings' );
				if( $value_mappings )
					$value_mappings = json_decode( $value_mappings, true );
				if( !is_array( $value_mappings ) )
					$value_mappings = array();
				
				$embeds = self::get_meta( $post_id, 'embeds' );
				if( $embeds )
					$embeds = json_decode( $embeds, true );
				if( !is_array( $embeds ) )
					$embeds = array();
				
				$uploaded_files = $submission->uploaded_files();
				
				// preprocess embedded images
				$embed_files = array();
				foreach( $embeds as $id => $embed )
				{
					$filepath = null;
					$filename = null;
					$url_mimetype = null;
					
					$url = null;
					if( isset( $embed['cf7_field'] ) )
						$url = self::wpcf7_mail_replace_tags( "[".$embed['cf7_field']."]" );
					if( isset( $embed['mail_tags'] ) )
						$url = self::wpcf7_mail_replace_tags( $embed['mail_tags'] );
					if( $url != null )
					{
						if( filter_var( $url, FILTER_VALIDATE_URL ) !== FALSE )
						if( substr( $url, 0, 5 ) === 'http:' || substr( $url, 0, 6 ) === 'https:' )
						{
							$filepath = $this->create_tmp_filepath( 'img_download_'.count($embed_files).'.tmp' );
							self::download_file( $url, $filepath, $url_mimetype ); // can throw an exception
							$filename = $url;
						}
						
						if( substr( $url, 0, 5 ) === 'data:' )
						{
							$filepath = $this->create_tmp_filepath( 'img_data_'.count($embed_files).'.tmp' );
							$filename = $url;
							
							$parsed = self::parse_data_uri( $url );
							if( $parsed !== false )
							{
								$url_mimetype = $parsed['mime'];
								if( file_put_contents( $filepath, $parsed['data'] ) === false || ! is_file( $filepath ) )
									throw new Exception( __( "Failed to create file", 'pdf-forms-for-contact-form-7' ) );
							}
						}
					}
					
					if( isset($embed['cf7_field']) && isset( $uploaded_files[$embed['cf7_field']] ) )
					{
						$filepath = $uploaded_files[$embed['cf7_field']]; // array in CF7 v5.4, string in prior versions
						if( is_array( $filepath ) )
							$filepath = reset( $filepath ); // take only the first file
						$filename = wp_basename( $filepath );
					}
					
					if( ! $filepath )
						continue;
					
					$file_mimetype = self::get_mime_type( $filepath );
					
					$mimetype_supported = false;
					$mimetype = 'unknown';
					
					if( $file_mimetype )
					{
						$mimetype = $file_mimetype;
						
						// check if MIME type is supported based on file contents
						$mimetype_supported = $this->is_embed_image_format_supported( $file_mimetype );
					}
					
					// if we were not able to determine MIME type based on file contents
					// then fall back to URL MIME type (if we are dealing with a URL)
					// (maybe fileinfo functions are not functional and WP fallback failed due to the ".tmp" extension)
					if( !$mimetype_supported && $url_mimetype )
					{
						$mimetype = $url_mimetype;
						$mimetype_supported = $this->is_embed_image_format_supported( $url_mimetype );
					}
					
					if( !$mimetype_supported )
						throw new Exception(
							self::replace_tags(
								__( "File type {mime-type} of {file} is unsupported for {purpose}", 'pdf-forms-for-contact-form-7' ),
								array( 'mime-type' => $mimetype, 'file' => $filename, 'purpose' => __( "image embedding", 'pdf-forms-for-contact-form-7' ) )
							)
						);
					
					$embed_files[$id] = $filepath;
				}
				
				$cf7_field_tags = array();
				$multiselectable_cf7_fields = array();
				foreach( $contact_form->scan_form_tags() as $cf7_tag )
					if( property_exists( $cf7_tag, 'name' ) )
					{
						$cf7_field_tags[$cf7_tag->name] = $cf7_tag;
						if(
							// cf7 checkboxes can have multiple values only if the form-tag contains more than one value and exclusive option is not enabled
							( $cf7_tag->basetype == 'checkbox' && is_array( $cf7_tag->values ) && count( $cf7_tag->values ) > 1 && !$cf7_tag->has_option( 'exclusive' ) )
							
							// cf7 drop-down menus must have 'multiple' tag to have multiple values
							|| ( $cf7_tag->basetype == 'select' && $cf7_tag->has_option( 'multiple' ) )
							
							// support for unknown field types: if posted data is an array with multiple items then it must be that this field supports multiple values
							|| ( is_array( ( $field_posted_data = $submission->get_posted_data( $cf7_tag->name ) ) ) && count( $field_posted_data ) > 1 )
						)
							$multiselectable_cf7_fields[$cf7_tag->name] = $cf7_tag->name;
					}
				
				$files = array();
				foreach( $this->post_get_all_pdfs( $post_id ) as $attachment_id => $attachment )
				{
					$fields = $this->get_fields( $attachment_id );
					
					$data = array();
					
					// process mappings
					foreach( $mappings as $mapping )
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
						
						$multiple = ( isset( $mapping["cf7_field"] ) && isset( $multiselectable_cf7_fields[ $mapping["cf7_field"] ] ) )
							|| ( isset( $fields[$field]['flags'] ) && in_array( 'MultiSelect', $fields[$field]['flags'] ) );
						
						if( isset( $mapping["cf7_field"] ) )
						{
							if( $multiple )
								$data[$field] = $submission->get_posted_data( $mapping["cf7_field"] );
							else
								$data[$field] = self::wpcf7_mail_replace_tags( "[".$mapping["cf7_field"]."]" );
						}
						
						if( isset( $mapping["mail_tags"] ) )
						{
							$data[$field] = self::wpcf7_mail_replace_tags( $mapping["mail_tags"] );
							
							if( $multiple )
							{
								$data[$field] = explode( ',' , $data[$field] );
								foreach( $data[$field] as &$value )
									$value = trim( $value );
								unset( $value );
							}
						}
					}
					
					// process old style tag generator fields
					foreach( $cf7_field_tags as $name => $cf7_field_tag )
					{
						try
						{
							$field_data = self::wpcf7_field_name_decode( $name );
							if( $field_data === FALSE )
								continue;
							if( $field_data['attachment_id'] != $attachment_id && $field_data['attachment_id'] != 'all' )
								continue;
							$field = $field_data['field'];
							if( $field === '' )
								continue;
							if( !isset( $fields[$field] ) )
								continue;
							
							$multiple = isset( $multiselectable_cf7_fields[ $cf7_field_tag->name ] )
								|| ( isset( $fields[$field]['flags'] ) && in_array( 'MultiSelect', $fields[$field]['flags'] ) );
							if( $multiple )
								$data[$field] = $submission->get_posted_data( $name );
							else
								$data[$field] = self::wpcf7_mail_replace_tags( "[".$name."]" );
						}
						catch(Exception $e) { }
					}
					
					if( count( $value_mappings ) > 0 )
					{
						// process value mappings
						$processed_value_mappings = array();
						$value_mapping_data = array();
						$existing_data_fields = array_fill_keys( array_keys( $data ), true );
						foreach( $value_mappings as $value_mapping )
						{
							$i = strpos( $value_mapping["pdf_field"], '-' );
							if( $i === FALSE )
								continue;
							
							$aid = substr( $value_mapping["pdf_field"], 0, $i );
							if( $aid != $attachment_id && $aid != 'all' )
								continue;
							
							$field = substr( $value_mapping["pdf_field"], $i+1 );
							$field = self::base64url_decode( $field );
							
							if( !isset( $existing_data_fields[$field] ) )
								continue;
							
							if( !isset( $value_mapping_data[$field] ) )
								$value_mapping_data[$field] = $data[$field];
							
							$cf7_value = strval( $value_mapping['cf7_value'] );
							if( ! isset( $processed_value_mappings[$field] ) )
								$processed_value_mappings[$field] = array();
							if( ! isset( $processed_value_mappings[$field][$cf7_value] ) )
								$processed_value_mappings[$field][$cf7_value] = array();
							$processed_value_mappings[$field][$cf7_value][] = $value_mapping;
						}
						
						// convert plain text values to arrays for processing
						foreach( $value_mapping_data as $field => &$value )
							if( ! is_array( $value ) )
								$value = array( $value );
						unset( $value );
						
						// determine old and new values
						$add_data = array();
						$remove_data = array();
						foreach($processed_value_mappings as $field => $cf7_mappings_list)
							foreach($cf7_mappings_list as $cf7_value => $list)
							{
								foreach( $value_mapping_data[$field] as $key => $value )
									if( self::mb_strtolower( $value ) === self::mb_strtolower( $cf7_value ) )
									{
										if( ! isset( $remove_data[$field] ) )
											$remove_data[$field] = array();
										$remove_data[$field][] = $value;
										
										if( ! isset( $add_data[$field] ) )
											$add_data[$field] = array();
										foreach( $list as $item )
											$add_data[$field][] = $item['pdf_value'];
									}
							}
						
						// remove old values
						foreach( $value_mapping_data as $field => &$value )
							if( isset( $remove_data[$field] ) )
								$value = array_diff( $value, $remove_data[$field] );
						unset( $value );
						
						// add new values
						foreach( $value_mapping_data as $field => &$value )
							if( isset( $add_data[$field] ) )
								$value = array_unique( array_merge( $value, $add_data[$field] ) );
						unset( $value );
						
						// convert arrays back to plain text where needed
						foreach( $value_mapping_data as $field => &$value )
							if( count( $value ) < 2 )
							{
								if( count( $value ) > 0 )
									$value = reset( $value );
								else
									$value = null;
							}
						unset( $value );
						
						// update data
						foreach( $value_mapping_data as $field => &$value )
							$data[$field] = $value;
						unset( $value );
					}
					
					// filter out anything that the pdf field can't accept
					foreach( $data as $field => &$value )
					{
						$type = $fields[$field]['type'];
						
						if( $type == 'radio' || $type == 'select' || $type == 'checkbox' )
						{
							// compile a list of field options
							$pdf_field_options = null;
							if( isset( $fields[$field]['options'] ) && is_array( $fields[$field]['options'] ) )
							{
								$pdf_field_options = $fields[$field]['options'];
								
								// if options are have more information than value, extract only the value
								foreach( $pdf_field_options as &$option )
									if( is_array( $option ) && isset( $option['value'] ) )
										$option = $option['value'];
								unset( $option );
							}
							
							// if a list of options are available then filter $value
							if( $pdf_field_options !== null )
							{
								if( is_array( $value ) )
									$value = array_intersect( $value, $pdf_field_options );
								else
									$value = in_array( $value, $pdf_field_options ) ? $value : null;
							}
						}
						
						// if pdf field is a text box but value is an array then we need to concatenate values
						if( $type == 'text' && is_array( $value ) )
						{
							$pdf_field_multiline = isset( $fields[$field]['flags'] ) && in_array( 'Multiline', $fields[$field]['flags'] );
							if( $pdf_field_multiline )
								$value = implode( "\n", $value );
							else
								$value = implode( __( ", ", 'pdf-forms-for-contact-form-7' ), $value );
						}
						
						// if pdf field is not a multiselect field but value is an array then use the first element only
						$pdf_field_multiselectable = isset( $fields[$field]['flags'] ) && in_array( 'MultiSelect', $fields[$field]['flags'] );
						if( !$pdf_field_multiselectable && is_array( $value ) )
						{
							if( count( $value ) > 0 )
								$value = reset( $value );
							else
								$value = null;
						}
					}
					unset( $value );
					
					// process image embeds
					$embeds_data = array();
					foreach( $embeds as $id => $embed )
						if( $embed['attachment_id'] == $attachment_id || $embed['attachment_id'] == 'all' )
						{
							if( isset( $embed_files[$id] ) )
							{
								$embed_data = array(
									'image' => $embed_files[$id],
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
					
					// skip file if 'skip when empty' option is enabled and form data is blank
					if($attachment['options']['skip_empty'] )
					{
						$empty_data = true;
						foreach( $data as $field => $value )
							if( !( is_null( $value ) || $value === array() || trim( $value ) === "" ) )
							{
								$empty_data = false;
								break;
							}
						
						if( $empty_data && count( $embeds_data ) == 0 )
							continue;
					}
					
					$attach_to_mail_1 = $attachment['options']['attach_to_mail_1'] || strpos( $mail["attachments"], "[pdf-form-".$attachment_id."]" ) !== FALSE;
					$attach_to_mail_2 = $attachment['options']['attach_to_mail_2'] || strpos( $mail2["attachments"], "[pdf-form-".$attachment_id."]" ) !== FALSE;
					$save_directory = strval( $attachment['options']['save_directory'] );
					$create_download_link = $attachment['options']['download_link'];
					$create_download_link_auto = $attachment['options']['download_link_auto'];
					
					// skip file if it is not used anywhere
					if( !$attach_to_mail_1
					&& !$attach_to_mail_2
					&& $save_directory === ""
					&& !$create_download_link
					&& !$create_download_link_auto )
						continue;
					
					$options = array();
					
					$options['flatten'] =
						isset($attachment['options']) &&
						isset($attachment['options']['flatten']) &&
						$attachment['options']['flatten'] == true;
					
					// determine if the attachment would be changed
					$filling_data = false;
					foreach( $data as $field => $value )
					{
						if( $value === null || $value === '' )
							$value = array();
						else if( ! is_array( $value ) )
							$value = array( $value );
						
						$pdf_value = null;
						if( isset( $fields[$field]['value'] ) )
							$pdf_value = $fields[$field]['value'];
						if( $pdf_value === null || $pdf_value === '' )
							$pdf_value = array();
						else if( ! is_array( $pdf_value ) )
							$pdf_value = array( $pdf_value );
						
						// check if values don't match
						if( ! ( array_diff( $value, $pdf_value ) == array() && array_diff( $pdf_value, $value ) == array() ) )
						{
							$filling_data = true;
							break;
						}
					}
					$attachment_affected = $filling_data || count( $embeds_data ) > 0 || $options['flatten'];
					
					$destfilename = strval( $attachment['options']['filename'] );
					if ( $destfilename != "" )
						$destfilename = strval( self::wpcf7_mail_replace_tags( $destfilename ) );
					if( $destfilename == "" )
						$destfilename = sanitize_file_name( get_the_title( $attachment_id ) );
					
					$destfile = $this->create_tmp_filepath( $destfilename . '.pdf' ); // if $destfilename is empty, create_tmp_filepath generates unnamed-file.pdf
					
					try
					{
						$service = $this->get_service();
						$filled = false;
						
						if( $service )
							// we only want to use the API when something needs to be done to the file
							if( $attachment_affected )
								$filled = $service->api_fill_embed( $destfile, $attachment_id, $data, $embeds_data, $options );
						
						if( ! $filled )
						{
							$filepath = get_attached_file( $attachment_id );
							if( empty( $filepath ) )
							{
								$fileurl = wp_get_attachment_url( $attachment_id );
								if( empty( $fileurl ) )
									throw new Exception( __( "Attachment file is not accessible", 'pdf-forms-for-contact-form-7' ) );
								self::download_file( $fileurl, $destfile );
							}
							else
								copy( $filepath, $destfile );
						}
						
						$files[] = array( 'attachment_id' => $attachment_id, 'file' => $destfile, 'filename' => $destfilename . '.pdf', 'options' => $attachment['options'] );
					}
					catch(Exception $e)
					{
						throw new Exception(
							self::replace_tags(
								__( "Error generating PDF: {error-message} at {error-file}:{error-line}", 'pdf-forms-for-contact-form-7' ),
								array( 'error-message' => $e->getMessage(), 'error-file' => wp_basename( $e->getFile() ), 'error-line' => $e->getLine() )
							)
						);
					}
				}
				
				if( version_compare( WPCF7_VERSION, '5.4.1' ) < 0 )
					// files will be attached to email messages in attach_files()
					$this->cf7_mail_attachments = $files;
				
				if( count( $files ) > 0 )
				{
					$storage = $this->get_storage();
					foreach( $files as $id => $filedata )
					{
						if( version_compare( WPCF7_VERSION, '5.4.1' ) >= 0 )
						{
							if( $filedata['options']['attach_to_mail_1'] )
								$submission->add_extra_attachments( $filedata['file'], 'mail' );
							if( $filedata['options']['attach_to_mail_2'] )
								$submission->add_extra_attachments( $filedata['file'], 'mail_2' );
						}
						
						$save_directory = strval( $filedata['options']['save_directory'] );
						if( $save_directory !== "" )
						{
							// standardize directory separator
							$save_directory = str_replace( '\\', '/', $save_directory );
							
							// remove preceding slashes and dots and space characters
							$trim_characters = "/\\. \t\n\r\0\x0B";
							$save_directory = trim( $save_directory, $trim_characters );
							
							// replace WPCF7 tags in path elements
							$path_elements = explode( "/", $save_directory );
							$tag_replaced_path_elements = self::wpcf7_mail_replace_tags( $path_elements );
							
							foreach( $tag_replaced_path_elements as $elmid => &$new_element )
							{
								// sanitize
								$new_element = trim( sanitize_file_name( wpcf7_canonicalize( $new_element ) ), $trim_characters );
								
								// if replaced and sanitized filename is blank then attempt to use the non-tag-replaced version
								if( $new_element === "" )
									$new_element = trim( sanitize_file_name( wpcf7_canonicalize( $path_elements[$elmid] ) ), $trim_characters );
							}
							unset($new_element);
							
							$save_directory = implode( "/", $tag_replaced_path_elements );
							$save_directory = preg_replace( '|/+|', '/', $save_directory ); // remove double slashes
							
							$storage->set_subpath( $save_directory );
							$storage->save( $filedata['file'], $filedata['filename'] );
						}
						
						$create_download_link = $filedata['options']['download_link'];
						$create_download_link_auto = $filedata['options']['download_link_auto'];
						if( $create_download_link || $create_download_link_auto )
						{
							$public_options = array();
							foreach( self::$public_pdf_options as $option )
								$public_options[$option] = $filedata['options'][$option];
							
							$this->get_downloads()->add_file(
								$filedata['file'],
								$filedata['filename'],
								$public_options
							);
						}
					}
				}
			}
			catch( Exception $e )
			{
				$abort = true;
				$submission->set_response(
						self::replace_tags(
							__( "An error occurred while processing a PDF: {error-message}", 'pdf-forms-for-contact-form-7' ),
							array( 'error-message' => $e->getMessage() )
						)
					);
				
				// clean up
				$this->remove_tmp_dir();
				$this->cf7_mail_attachments = array();
			}
		}
		
		/**
		 * Attaches files to CF7 email messages when needed
		 */
		public function attach_files( $components, $form = null, $mail = null )
		{
			if( $mail != null )
				foreach( $this->cf7_mail_attachments as $filedata )
				{
					$file = $filedata['file'];
					if( file_exists( $file ) )
					{
						if( ( $filedata['options']['attach_to_mail_1'] && $mail->name() == 'mail' )
						|| ( $filedata['options']['attach_to_mail_2'] && $mail->name() == 'mail_2' ) )
							$components['attachments'][] = $file;
					}
				}
			
			return $components;
		}
		
		/**
		 * Used for attaching a PDF file and retreiving PDF file information in wp-admin interface
		 */
		public function wp_ajax_get_attachment_info()
		{
			try
			{
				if ( ! check_ajax_referer( 'wpcf7-pdf-forms-ajax-nonce', 'nonce', false ) )
					throw new Exception( __( "Nonce mismatch", 'pdf-forms-for-contact-form-7' ) );
				
				$attachment_id = intval( $_POST[ 'file_id' ] );
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : "";
				
				if( ( $filepath = get_attached_file( $attachment_id ) ) !== false
				&& ( $mimetype = self::get_mime_type( $filepath ) ) != null
				&& $mimetype !== 'application/pdf' )
					throw new Exception(
						self::replace_tags(
							__( "File type {mime-type} of {file} is unsupported for {purpose}", 'pdf-forms-for-contact-form-7' ),
							array( 'mime-type' => $mimetype, 'file' => wp_basename( $filepath ), 'purpose' => __( "PDF form filling", 'pdf-forms-for-contact-form-7' ) )
						)
					);
				
				$cf7_fields = $this->query_cf7_fields( $form );
				
				$unavailableNames = array();
				foreach( $cf7_fields as $cf7_field )
					$unavailableNames[] = $cf7_field['name'];
				
				$info = $this->get_info( $attachment_id );
				$info['fields'] = $this->query_pdf_fields( $attachment_id, $unavailableNames );
				
				$options = array( );
				foreach( self::$pdf_options as $option => $default )
					$options[$option] = $default;
				
				return wp_send_json( array(
					'success' => true,
					'attachment_id' => $attachment_id,
					'filename' => wp_basename( $filepath ),
					'options' => $options,
					'info' => $info,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => wp_basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
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
		
		/**
		 * Computes, saves and returns the md5 sum of the media file
		 */
		public static function update_attachment_md5sum( $attachment_id )
		{
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
			
			$filepath = get_attached_file( $attachment_id );
			
			if( $filepath !== false && is_readable( $filepath ) !== false )
				$md5sum = @md5_file( $filepath );
			else
			{
				$fileurl = wp_get_attachment_url( $attachment_id );
				if( $fileurl === false )
					throw new Exception( __( "Attachment file is not accessible", 'pdf-forms-for-contact-form-7' ) );
				
				try
				{
					$temp_filepath = wp_tempnam();
					self::download_file( $fileurl, $temp_filepath ); // can throw an exception
					$md5sum = @md5_file( $temp_filepath );
					@unlink( $temp_filepath );
				}
				catch(Exception $e)
				{
					@unlink( $temp_filepath );
					throw $e;
				}
			}
			
			if( $md5sum === false )
				throw new Exception( __( "Could not read attached file", 'pdf-forms-for-contact-form-7' ) );
			
			return self::set_meta( $attachment_id, 'md5sum', $md5sum );
		}
		
		/**
		 * Caching wrapper for $service->api_get_info()
		 */
		public function get_info( $attachment_id )
		{
			// cache
			if( ( $info = self::get_meta( $attachment_id, 'info' ) )
			&& ( $old_md5sum = self::get_meta( $attachment_id, 'md5sum' ) ) )
			{
				// use cache only if file is locally accessible
				$filepath = get_attached_file( $attachment_id );
				if( $filepath !== false && is_readable( $filepath ) !== false )
				{
					$new_md5sum = md5_file( $filepath );
					if( $new_md5sum !== false && $new_md5sum === $old_md5sum )
						return json_decode( $info, true );
					else
						self::update_attachment_md5sum( $attachment_id );
				}
			}
			
			$service = $this->get_service();
			if( !$service )
				throw new Exception( __( "No service", 'pdf-forms-for-contact-form-7' ) );
			
			$info = $service->api_get_info( $attachment_id );
			
			// set up array keys so it is easier to search
			$fields = array();
			foreach( $info['fields'] as $field )
				if( isset( $field['name'] ) )
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
		
		/**
		 * Caches and returns fields for an attachment
		 */
		public function get_fields( $attachment_id )
		{
			$info = $this->get_info( $attachment_id );
			return $info['fields'];
		}
		
		/**
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
		
		/**
		 * Multibyte trim
		 */
		public static function mb_trim($str)
		{
			return preg_replace( '/(^\s+)|(\s+$)/us', '', $str );
		}
		
		/**
		 * Multibyte strtolower
		 */
		public static function mb_strtolower($str)
		{
			return function_exists( 'mb_strtolower' ) ? mb_strtolower( $str ) : strtolower( $str );
		}
		
		private static function escape_tag_value($value)
		{
			$value = esc_attr($value);
			$escape_characters = array("\\","]","|");
			$escape_table = array('&#92;', '&#93;','&#124;');
			$value = str_replace($escape_characters, $escape_table, $value);
			return $value;
		}
		
		/**
		 * Generates CF7 field tag based on field data
		 * $tagName must already be sanitized
		 */
		private static function generate_tag( $field, &$tagName, $unavailableNames = array() )
		{
			$type = strval($field['type']);
			
			// sanity check
			if( ! ( $type === 'text' || $type === 'radio' || $type === 'select' || $type === 'checkbox' ) )
				return null;
			
			$tagType = $type;
			$tagOptions = '';
			$tagValues = '';
			
			if( $type == 'text' )
				if( isset( $field['value'] ) && strval( $field['value'] ) != "" )
					$tagValues .= '"' . self::escape_tag_value( strval( $field['value'] ) ) . '" ';
			
			if( $type == 'radio' || $type == 'select' || $type == 'checkbox' )
			{
				if( isset( $field['options'] ) && is_array( $field['options'] ) )
				{
					$options = $field['options'];
					
					if( ( $off_key = array_search( 'Off', $options, $strict=true ) ) !== FALSE )
						unset( $options[ $off_key ] );
					
					if( $type == 'radio' && count( $options ) == 1 )
						$tagType = 'checkbox';
					
					$count = count( $options );
					foreach( $options as $option )
					{
						$name = null;
						
						// prevent user confusion with singular options when it is a non-descriptive "Yes" or "On"
						if( $count == 1 )
							$name = $field['name'];
						
						// if options list is not a primitive string array then use keys as values
						if( is_array( $option ) )
						{
							if( isset( $option['label'] ) ) $name = $option['label'];
							if( isset( $option['value'] ) ) $option = $option['value'];
						}
						
						$name = strval( $name );
						$option = strval( $option );
						$tagValues .= '"' . self::escape_tag_value( $name == null ? $option : $name ) . '" ';
					}
					
					if( $type == 'checkbox' && count( $options ) > 1 )
						$tagOptions .= 'exclusive ';
					
					if( isset( $field['defaultValue'] ) )
					{
						$default_values = $field['defaultValue'];
						if( ! is_array( $default_values ) )
							$default_values = array( $default_values );
						
						$default_string = '';
						$count = 1;
						foreach( $options as $option )
						{
							$option_value = null;
							if( is_array( $option ) && isset( $option['value'] ) )
								$option_value = $option['value'];
							if( ! is_array( $option ) )
								$option_value = $option;
							
							if( $option_value !== null )
								if( in_array( $option_value, $default_values, $strict=true ) )
									$default_string = ltrim( $default_string . '_' . $count, '_' );
							
							$count++;
						}
						
						if( $default_string !== '' )
							$tagOptions .= 'default:' . $default_string . ' ';
					}
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
			
			$reservedNames = array(
				'm','p','posts','w','cat','withcomments','withoutcomments'
				,'s','search','exact','sentence','calendar','page','paged'
				,'more','tb','pb','author','order','orderby','year','monthnum'
				,'day','hour','minute','second','name','category_name','tag','feed'
				,'author_name','static','pagename','page_id','error','attachment'
				,'attachment_id','subpost','subpost_id','preview','robots','taxonomy'
				,'term','cpage','post_type','embed'
			);
			$unavailableNames = array_merge( $unavailableNames, $reservedNames );
			if( array_search( $tagName, $unavailableNames ) !== FALSE )
			{
				if( $tagName[ strlen( $tagName ) - 1 ] != '-' )
					$tagName .= '-';
				$tagName .= '0000';
				do { $tagName++; }
				while( array_search( $tagName, $unavailableNames ) !== FALSE );
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
				
				$attachments = isset( $_POST['attachments'] ) ? wp_unslash( $_POST['attachments'] ) : null;
				$all = isset( $_POST['all'] ) ? wp_unslash( $_POST['all'] ) : null;
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : "";
				
				if( !isset($attachments) || !is_array($attachments) )
					$attachments = array();
				
				$fields = array();
				foreach( $attachments as $attachment_id )
				{
					$attachment_id = intval( $attachment_id );
					if ( ! current_user_can( 'edit_post', $attachment_id ) )
						continue;
					
					$fields[$attachment_id] = $this->get_fields( $attachment_id );
				}
				
				if( count($fields) == 1 && count(reset($fields)) == 0 )
					$tags = __( "This PDF file does not appear to contain a PDF form.  See https://acrobat.adobe.com/us/en/acrobat/how-to/create-fillable-pdf-forms-creator.html for more information.", 'pdf-forms-for-contact-form-7' );
				else
				{
					$cf7_fields = $this->query_cf7_fields( $form );
					
					$unavailableNames = array();
					foreach( $cf7_fields as $cf7_field )
						$unavailableNames[] = $cf7_field['name'];
					
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
								$generated_tag = self::generate_tag( $field, $tagName, $unavailableNames );
								
								if( $generated_tag === null)
									continue;
								
								$tag .= '    ' . $generated_tag;
								$tags .= $tag . "\n\n";
								
								$unavailableNames[] = $tagName;
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
					'error_location' => wp_basename( $e->getFile() ) . ":". $e->getLine(),
				) );
			}
		}
		
		/**
		 * Helper function to assist with WPCF7 field name sanitization
		 */
		private static function wpcf7_sanitize_field_name( $name )
		{
			$slug = sanitize_key( remove_accents( $name ) );
			if( $slug == '' )
				$slug = 'field';
			// first character must be a letter
			if( preg_match( '/^[a-zA-Z]$/', $slug[0] ) === 0 )
				$slug = 'field-'.$slug;
			return $slug;
		}
		
		/**
		 * Helper function used in wp-admin interface
		 */
		private function query_pdf_fields( $attachment_id, &$unavailableNames = array() )
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
				$slug = self::wpcf7_sanitize_field_name( $name );
				$tag_hint = self::generate_tag( $field, $slug, $unavailableNames );
				$field['id'] = self::base64url_encode( $name );
				$field['tag_hint'] = $tag_hint;
				$field['tag_name'] = $slug;
				$unavailableNames[] = $slug;
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
				
				$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : null;
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : "";
				
				if( ! $post_id )
					throw new Exception( __( "Invalid post ID", 'pdf-forms-for-contact-form-7' ) );
				
				if ( ! current_user_can( 'wpcf7_edit_contact_form', $post_id ) )
					throw new Exception( __( "Permission denied", 'pdf-forms-for-contact-form-7' ) );
				
				$cf7_fields = $this->query_cf7_fields( $form );
				
				$unavailableNames = array();
				foreach( $cf7_fields as $cf7_field )
					$unavailableNames[] = $cf7_field['name'];
				
				$attachments = array();
				foreach( $this->post_get_all_pdfs( $post_id ) as $attachment_id => $attachment )
				{
					$options = array();
					if( isset( $attachment['options']) )
						$options = $attachment['options'];
					
					$info = $this->get_info( $attachment_id );
					$info['fields'] = $this->query_pdf_fields( $attachment_id, $unavailableNames );
					
					$attachments[] = array(
						'attachment_id' => $attachment_id,
						'filename' => get_the_title( $attachment_id ),
						'options' => $options,
						'info' => $info,
					);
				}
				
				$mappings = self::get_meta( $post_id, 'mappings' );
				if( $mappings )
					$mappings = json_decode( $mappings, true );
				if( !is_array( $mappings ) )
					$mappings = array();
				
				$value_mappings = self::get_meta( $post_id, 'value_mappings' );
				if( $value_mappings )
					$value_mappings = json_decode( $value_mappings, true );
				if( !is_array( $value_mappings ) )
					$value_mappings = array();
				
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
					'value_mappings' => $value_mappings,
					'embeds' => $embeds,
				) );
			}
			catch( Exception $e )
			{
				return wp_send_json( array(
					'success'  => false,
					'error_message' => $e->getMessage(),
					'error_location' => wp_basename( $e->getFile() ) . ":". $e->getLine(),
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
			
			$tags = $contact_form->scan_form_tags();
			
			if( !is_array( $tags ) )
				throw new Exception( __( "Failed to get Contact Form fields", 'pdf-forms-for-contact-form-7' ) );
			
			$fields = array();
			foreach( $tags as $tag )
			{
				if( ! is_object($tag) || ! property_exists( $tag, 'name' ) || strval($tag->name) === "" )
					continue;
				
				$pdf_field = self::wpcf7_field_name_decode( $tag->name );
				if( $pdf_field !== FALSE )
					$pdf_field = $pdf_field['attachment_id'].'-'.$pdf_field['encoded_field'];
				
				$field = array(
					'id' => $tag->name,
					'name' => $tag->name,
					'text' => $tag->name,
					'type' => $tag->basetype,
					'pdf_field' => $pdf_field,
				);
				
				if( is_array( $tag->values ) && count( $tag->values ) > 0 )
				// don't bother with values if it is a text field
				if( ! in_array( $tag->basetype, array( 'text', 'textarea', 'tel', 'email', 'url', 'number', 'range' ) ) )
				{
					$values = $tag->values;
					$pipes = $tag->pipes;
					
					if( WPCF7_USE_PIPE
					&& $pipes instanceof WPCF7_Pipes
					&& !$pipes->zero() )
					{
						foreach( $values as &$orig_value )
						{
							if( is_array( $orig_value ) )
							{
								$value = array();
								foreach( $orig_value as $v )
									$value[] = $pipes->do_pipe( $v );
							}
							else
								$value = $pipes->do_pipe( $orig_value );
							$orig_value = $value;
						}
						unset($orig_value);
					}
					
					// remove extra item appended by a free input text field feature
					if( ( $tag->basetype == 'checkbox' || $tag->basetype == 'radio' ) && $tag->has_option( 'free_text' ) )
						array_pop( $values );
					
					// if the only option is an empty string, assume there are no options
					if( count( $values ) == 1 && reset( $values ) === "" )
						$values = array();
					
					$field['values'] = $values;
				}
				
				$fields[$tag->name] = $field;
			}
			
			return array_values( $fields );
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
				
				$form = isset( $_POST['wpcf7-form'] ) ? wp_unslash( $_POST['wpcf7-form'] ) : "";
				
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
					'error_location' => wp_basename( $e->getFile() ) . ":". $e->getLine(),
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
			
			$wp_upload_dir = wp_upload_dir();
			if( isset( $wp_upload_dir['error'] ) && false !== $wp_upload_dir['error'] )
				throw new Exception( $wp_upload_dir['error'] );
			if( ! isset( $wp_upload_dir['path'] ) || ! isset( $wp_upload_dir['url'] ) )
				throw new Exception( __( "Failed to determine upload path", 'pdf-forms-for-contact-form-7' ) );
			
			$filename = wp_unique_filename( $wp_upload_dir['path'], sanitize_file_name( get_the_title( $attachment_id ) ) . '.page' . strval( intval( $page ) ) . '.jpg' );
			$filepath = trailingslashit( $wp_upload_dir['path'] ) . $filename;
			
			$service = $this->get_service();
			if( $service )
				$service->api_image( $filepath, $attachment_id, $page );
			
			$mimetype = self::get_mime_type( $filepath );
			
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $filename,
				'post_mime_type' => $mimetype,
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
				
				$attachment_id = isset( $_POST['attachment_id'] ) ? intval( $_POST['attachment_id'] ) : null;
				$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : null;
				
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
					'success' => false,
					'error_message' => $e->getMessage(),
					'error_location' => wp_basename( $e->getFile() ) . ":". $e->getLine(),
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
				if( version_compare( WPCF7_VERSION, '6' ) < 0 )
					$tag_generator->add(
						'pdf_form',
						__( 'PDF Form', 'pdf-forms-for-contact-form-7' ),
						array( $this, 'render_tag_generator_v1' )
					);
				else
					$tag_generator->add(
						'pdf_form',
						__( 'PDF Form', 'pdf-forms-for-contact-form-7' ),
						array( $this, 'render_tag_generator' ),
						array( 'version' => '2' )
					);
			}
			// support for older CF7 versions
			else if( function_exists('wpcf7_add_tag_generator') )
			{
				wpcf7_add_tag_generator(
					'pdf_form',
					__( 'PDF Form', 'pdf-forms-for-contact-form-7' ),
					'wpcf7-tg-pane-pdfninja',
					array( $this, 'render_tag_generator_unsupported')
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
		
		/**
		 * Renders a notice with the given attributes
		 */
		public static function render_notice( $notice_id, $type, $attributes = array() )
		{
			if( ! isset( $attributes['classes'] ) )
				$attributes['classes'] = "";
			$attributes['classes'] = trim( $attributes['classes'] . " notice-$type" );
			
			if( !isset( $attributes['label'] ) )
				$attributes['label'] = __( "PDF Forms Filler for CF7", 'pdf-forms-for-contact-form-7' );
			
			if( $notice_id )
			{
				$attributes['attributes'] = 'data-notice-id="'.esc_attr( $notice_id ).'"';
				$attributes['classes'] .= ' is-dismissible';
			}
			
			return self::render( "notice", $attributes );
		}
		
		/**
		 * Renders a success notice with the given attributes
		 */
		public static function render_success_notice( $notice_id, $attributes = array() )
		{
			return self::render_notice( $notice_id, 'success', $attributes );
		}
		
		/**
		 * Renders a warning notice with the given attributes
		 */
		public static function render_warning_notice( $notice_id, $attributes = array() )
		{
			return self::render_notice( $notice_id, 'warning', $attributes );
		}
		
		/**
		 * Renders an error notice with the given attributes
		 */
		public static function render_error_notice( $notice_id, $attributes = array() )
		{
			return self::render_notice( $notice_id, 'error', $attributes );
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
				array_map( array( __CLASS__, 'add_curly_braces' ), array_keys( $tags ) ),
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
		 * Renders the contents of a thickbox that comes up when user clicks the tag generator button in the form editor
		 */
		public function render_tag_generator_v1( $contact_form, $args = '' )
		{
			echo self::render( 'tag_generator_v1', array(
				'go-to-pdf-files' => esc_html__( "Go to PDF files", 'pdf-forms-for-contact-form-7' ),
				'go-to-field-mappings' => esc_html__( "Go to Field Mappings", 'pdf-forms-for-contact-form-7' ),
				'insert-tags' => esc_html__( "Insert Tags", 'pdf-forms-for-contact-form-7' ),
				'insert-tag' => esc_html__( "Insert and Link", 'pdf-forms-for-contact-form-7' ),
				'generate-and-insert-all-tags-message' => esc_html__( "The 'Insert & Link All' button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.", 'pdf-forms-for-contact-form-7' ),
				'insert-and-map-all-tags' => esc_html__( "Insert & Link All", 'pdf-forms-for-contact-form-7' ),
				'field-mapping-generator' => esc_html__( 'Field Mapping Generator', 'pdf-forms-for-contact-form-7' ),
				'field-mapping-generator-help' => esc_html__( 'This tool can be used to generate form-tags based on PDF fields after attaching a PDF file with a form.', 'pdf-forms-for-contact-form-7' ),
				'pdf-field' => esc_html__( 'PDF field', 'pdf-forms-for-contact-form-7' ),
				'tag-generator' => esc_html__( 'Tag Generator Tool (deprecated)', 'pdf-forms-for-contact-form-7' ),
				'tag-generator-help' => esc_html__( 'This tool allows creation of CF7 fields that are linked to PDF fields by name. This feature is deprecated in favor of the field mapper tool.', 'pdf-forms-for-contact-form-7' ),
				'update-message' => self::replace_tags(
					esc_html__( "The PDF file attachment tool, the field mapper tool and the image embedding tool have moved to the {a-href-panel-link}PDF Forms Filler panel{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-panel-link' => '<a href="javascript:return false;" class="go-to-wpcf7-forms-panel-btn">',
						'/a' => '</a>',
					)
				),
				'help-message' => self::replace_tags(
					esc_html__( "Have a question/comment/problem?  Feel free to use {a-href-forum}the support forum{/a} and view {a-href-tutorial}the tutorial video{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-forum' => '<a href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/" target="_blank">',
						'a-href-tutorial' => '<a href="https://youtu.be/rATOSROQAGU" target="_blank">',
						'/a' => '</a>',
					)
				),
				'show-help' => esc_html__( 'Show Help', 'pdf-forms-for-contact-form-7' ),
				'hide-help' => esc_html__( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
				'show-tag-generator' => __( 'Show Tag Generator', 'pdf-forms-for-contact-form-7' ),
				'hide-tag-generator' => __( 'Hide Tag Generator', 'pdf-forms-for-contact-form-7' ),
				'get-tags' => esc_html__( 'Get Tags', 'pdf-forms-for-contact-form-7' ),
				'all-pdfs' => esc_html__( 'All PDFs', 'pdf-forms-for-contact-form-7' ),
			) );
			
		}
		
		/**
		 * Renders the contents of a dialog that comes up when user clicks the tag generator button in the form editor
		 */
		public function render_tag_generator( $contact_form, $options )
		{
			echo self::render( 'tag_generator', array(
				'heading' => esc_html__( "PDF form fields", 'pdf-forms-for-contact-form-7' ),
				'description' => self::replace_tags(
					esc_html__( "Once you {a-href-panel-link}attach a PDF file with fields to your form{/a}, you will be able to generate form-tags that are linked to PDF form fields.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-panel-link' => '<a href="javascript:return false;" class="go-to-wpcf7-forms-panel-btn">',
						'/a' => '</a>',
					)
				),
				'go-to-pdf-files' => esc_html__( "Go to PDF files", 'pdf-forms-for-contact-form-7' ),
				'go-to-field-mappings' => esc_html__( "Go to Field Mappings", 'pdf-forms-for-contact-form-7' ),
				'insert-tags' => esc_html__( "Insert Tags", 'pdf-forms-for-contact-form-7' ),
				'insert-tag' => esc_html__( "Insert and Link", 'pdf-forms-for-contact-form-7' ),
				'generate-and-insert-all-tags-message' => esc_html__( "The 'Insert & Link All' button allows you to generate tags for all remaining unlinked PDF fields, insert them into the form and link them to their corresponding fields.", 'pdf-forms-for-contact-form-7' ),
				'insert-and-map-all-tags' => esc_html__( "Insert & Link All", 'pdf-forms-for-contact-form-7' ),
				'field-mapping-generator' => esc_html__( 'Field Mapping Generator', 'pdf-forms-for-contact-form-7' ),
				'field-mapping-generator-help' => esc_html__( 'This tool can be used to generate form-tags based on PDF fields after attaching a PDF file with a form.', 'pdf-forms-for-contact-form-7' ),
				'pdf-field' => esc_html__( 'PDF field', 'pdf-forms-for-contact-form-7' ),
				'tag-generator' => esc_html__( 'Tag Generator Tool (deprecated)', 'pdf-forms-for-contact-form-7' ),
				'tag-generator-help' => esc_html__( 'This tool allows creation of CF7 fields that are linked to PDF fields by name. This feature is deprecated in favor of the field mapper tool.', 'pdf-forms-for-contact-form-7' ),
				'update-message' => self::replace_tags(
					esc_html__( "The PDF file attachment tool, the field mapper tool and the image embedding tool have moved to the {a-href-panel-link}PDF Forms Filler panel{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-panel-link' => '<a href="javascript:return false;" class="go-to-wpcf7-forms-panel-btn">',
						'/a' => '</a>',
					)
				),
				'help-message' => self::replace_tags(
					esc_html__( "Have a question/comment/problem?  Feel free to use {a-href-forum}the support forum{/a} and view {a-href-tutorial}the tutorial video{/a}.", 'pdf-forms-for-contact-form-7' ),
					array(
						'a-href-forum' => '<a href="https://wordpress.org/support/plugin/pdf-forms-for-contact-form-7/" target="_blank">',
						'a-href-tutorial' => '<a href="https://youtu.be/rATOSROQAGU" target="_blank">',
						'/a' => '</a>',
					)
				),
				'show-help' => esc_html__( 'Show Help', 'pdf-forms-for-contact-form-7' ),
				'hide-help' => esc_html__( 'Hide Help', 'pdf-forms-for-contact-form-7' ),
				'show-tag-generator' => __( 'Show Tag Generator', 'pdf-forms-for-contact-form-7' ),
				'hide-tag-generator' => __( 'Hide Tag Generator', 'pdf-forms-for-contact-form-7' ),
				'get-tags' => esc_html__( 'Get Tags', 'pdf-forms-for-contact-form-7' ),
				'all-pdfs' => esc_html__( 'All PDFs', 'pdf-forms-for-contact-form-7' ),
			) );
		}
		
		public function render_tag_generator_unsupported()
		{
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
			$slug = self::wpcf7_sanitize_field_name( $pdf_field_name );
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
		
		/**
		 * Parses data URI
		 */
		public static function parse_data_uri( $uri )
		{
			if( ! preg_match( '/data:([a-zA-Z-\/+.]*)((;[a-zA-Z0-9-_=.+]+)*),(.*)/', $uri, $matches ) )
				return false;
			
			$data = $matches[4];
			$mime = $matches[1] ? $matches[1] : null;
			
			$base64 = false;
			foreach( explode( ';', $matches[2] ) as $ext )
				if( $ext == "base64" )
				{
					$base64 = true; 
					if( ! ( $data = base64_decode( $data, $strict=true ) ) )
						return false;
				}
			
			if( ! $base64 )
				$data = rawurldecode( $data );
			
			return array(
				'data' => $data,
				'mime' => $mime,
			);
		}
		
		/**
		 * WPCF7 hook for adding download links to JS response
		 */
		public function change_response_js( $response, $result )
		{
			// if downloads variable is not initialized then we don't need to do anything
			if( $this->downloads )
			{
				// make sure response status is not a failure
				$valid_statuses = array( 'mail_sent', 'spam' );
				if( in_array( $response['status'], $valid_statuses ) )
					foreach( $this->downloads->get_files() as $file )
						$response['wpcf7_pdf_forms_data']['downloads'][] =
							array(
								'filename' => $file['filename'],
								'url' => $file['url'],
								'size' => size_format( filesize( $file['filepath'] ) ),
								'options' => $file['options'],
							);
				
				// make sure to enable cron if it is down so that old download files get cleaned up
				$this->enable_cron();
			}
			
			return $response;
		}
		
		/**
		 * WPCF7 hook for adding download links to response message (only for when JS is disabled)
		 */
		public function change_response_nojs( $output, $class, $content, $contact_form )
		{
			$submission = WPCF7_Submission::get_instance();
			// if downloads variable is not initialized then we don't need to do anything
			if( $this->downloads && $submission !== null )
			{
				$status = $submission->get_status();
				$valid_statuses = array( 'mail_sent', 'spam' );
				if( in_array( $status, $valid_statuses ) && $contact_form->id() == $submission->get_contact_form()->id() )
				{
					$downloads = '';
					foreach( $this->downloads->get_files() as $file )
					if( isset( $file['options'] ) && is_array( $file['options'] ) )
					{
						$options = $file['options'];
						
						if( $options['download_link'] )
							$downloads .= "<div>" .
								self::replace_tags(
									esc_html__( "{icon} {a-href-url}{filename}{/a} {i}({size}){/i}", 'pdf-forms-for-contact-form-7' ),
									array(
										'icon' => '<span class="dashicons dashicons-download"></span>',
										'a-href-url' => '<a href="' . esc_attr( $file['url'] ) . '" download>',
										'filename' => esc_html( $file['filename'] ),
										'/a' => '</a>',
										'i' => '<span class="file-size">',
										'size' => esc_html( size_format( filesize( $file['filepath'] ) ) ),
										'/i' => '</span>',
									)
								)
							. "</div>";
						
						if( $options['download_link_auto'] )
						{
							$file_path = substr( $file['filepath'], strlen( $this->downloads->get_downloads_path() ) + 1 );
							$hash = wp_hash( $file_path . wp_salt() );
							$output .= "<iframe src='?wpcf7-pdf-forms-download=" . esc_attr( $file_path ) . "&hash=" . esc_attr( $hash ) . "' style='display:none;'></iframe>";
						}
					}
					if($downloads !== '')
						$output .= "<div class='wpcf7-pdf-forms-response-output'>$downloads</div>";
					
					// make sure to enable cron if it is down so that old download files get cleaned up
					$this->enable_cron();
				}
			}
			
			return $output;
		}
		
		/**
		 * Hook for change_response_nojs() that allows hidden iframe download to work
		 */
		public function handle_hidden_iframe_download()
		{
			if( isset( $_GET['wpcf7-pdf-forms-download'] ) )
			{
				$downloads = $this->get_downloads();
				$filepath = $_GET['wpcf7-pdf-forms-download'];
				$hash = $_GET['hash'];
				
				// invasive pat down
				$path_parts = explode( '/', $filepath );
				$sanitized_parts = array_map( 'sanitize_file_name', $path_parts );
				$filepath = implode( DIRECTORY_SEPARATOR, $sanitized_parts );
				
				// additional security checks
				$downloads_path = realpath( $downloads->get_downloads_path() );
				$fullfilepath = $downloads_path . DIRECTORY_SEPARATOR . $filepath;
				$realfilepath = realpath( $fullfilepath );
				if( $downloads_path !== false && $realfilepath !== false && $fullfilepath === $realfilepath
				&& self::get_mime_type( $realfilepath ) == 'application/pdf'
				&& self::hash_equals( wp_hash( $filepath . wp_salt() ), $hash ) )
				{
					header( 'Content-Type: application/pdf' );
					header( 'Content-Disposition: attachment; filename="' . basename( $realfilepath ) . '"' );
					header( 'Content-Length: ' . filesize( $realfilepath ) );
					readfile( $realfilepath );
					exit;
				}
			}
		}
		
		/**
		 * hash_equals() for PHP < 5.6
		 */
		public static function hash_equals( $str1, $str2 )
		{
			if( function_exists( 'hash_equals' ) )
				return hash_equals( $str1, $str2 );
			
			if( strlen( $str1 ) != strlen( $str2 ) )
				return false;
			
			$res = $str1 ^ $str2;
			$ret = 0;
			for( $i = strlen( $res ) - 1; $i >= 0; $i-- )
				$ret |= ord( $res[$i] );
			return !$ret;
		}
	}
	
	WPCF7_Pdf_Forms::get_instance();
}
