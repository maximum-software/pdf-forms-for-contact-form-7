<?php

if( ! class_exists( 'WPCF7_Pdf_Ninja_File_Storage' ) ){

	class WPCF7_Pdf_Ninja_File_Storage
	{

		private  static $instance = null;

		private  $storage_path = null;

		private function __construct() {}
		/*
		 * Returns a global instance of this class
		 */
		public static function get_instance()
		{
			if ( empty( self::$instance ) )
				self::$instance = new self;

			return self::$instance;
		}

		public function set_tmp_path($directory)
		{
			// set the default url and dir path
			if (defined('PDF_FORMS_STORAGE_TMP_DIR'))
				$this->storage_path = PDF_FORMS_STORAGE_TMP_DIR;
			else
			{
				$defailt_path = 'pdf_form_storage';
				$this->storage_path = path_join($this->get_base_path('dir'), $defailt_path);
			}
			$this->storage_path = path_join($this->storage_path, $directory);
		}

		public function get_storage_path()
		{
			return $this->storage_path;
		}

		private function get_base_path( $type = false )
		{
			$uploads = wp_get_upload_dir();
			if ( 'dir' == $type )return $uploads['basedir'];
		}

		private function check_downloand_dir() {

			$this->storage_path = wp_normalize_path($this->storage_path);

			wp_mkdir_p( $this->storage_path );

			$htaccess_file = path_join( $this->storage_path, '.htaccess' );

			if ( file_exists( $htaccess_file ) ) {
				return;
			}

			if ( $handle = fopen( $htaccess_file, 'w' ) ) {
				fwrite( $handle, "Options All -Indexes\n" );
				fclose( $handle );
				return true;
			}
		}

		public function sanitize_dir_name($dangerous_filename)
		{
			$dangerous_characters = array(" ", '"', "'", "&", "//", ".", "..", "\\", "?", "#");
			return str_replace($dangerous_characters, '', $dangerous_filename);
		}

		public function init_dir( $directory )
		{
			$this->set_tmp_path($directory);
			$this->check_downloand_dir(); // Confirm download dir
		}

		/**
		 * Copy a temporary download file path and add path to links
		 */
		public function save( $srcfile, $filename )
		{
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $this->storage_path, $filename );

			copy($srcfile, trailingslashit( $this->storage_path ) . $filename);
		}

		/**
		 * wp_upload_dir check the path if there is / then finish the absolute path
		 * so delete all characters / in front
		 */
		public function path_is( $directory )
		{
			while (substr($directory,0,1) =='/'){
				$directory = substr($directory, 1);
			}
			return $directory;
		}

		public function replace_tags( $content )
		{
			$content = explode( "/", $content );
			foreach ( $content as $num => $line ) {
				$line = new WPCF7_MailTaggedText( $line);
				$replaced = $line->replace_tags();
				$content[$num] = sanitize_file_name( wpcf7_canonicalize( $replaced ));
				unset($line);
			}
			$content = implode( "/", $content );
			return $content;
		}

	}
}
