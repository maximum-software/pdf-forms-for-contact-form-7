<?php

if( ! defined( 'ABSPATH' ) )
	return;

if( ! class_exists( 'WPCF7_Pdf_Forms_Storage' ) )
{
	class WPCF7_Pdf_Forms_Storage
	{
		private static $instance = null;
		
		private $storage_path = null;
		private $subpath = null;
		
		private function __construct() { }
		
		/*
		 * Returns a global instance of this class
		 */
		public static function get_instance()
		{
			if ( empty( self::$instance ) )
				self::$instance = new self;
			
			return self::$instance;
		}
		
		/**
		 * Returns a storage path
		 */
		private function generate_storage_path()
		{
			// override path (if defined)
			if( defined( 'WPCF7_PDF_FORMS_STORAGE_PATH' ) )
				$storage_path = WPCF7_PDF_FORMS_STORAGE_PATH;
			else
			{
				$uploads = wp_upload_dir( null, false );
				$storage_path = $uploads['basedir'];
			}
			
			return $storage_path;
		}
		
		/**
		 * Returns storage path
		 */
		public function get_storage_path()
		{
			if( ! $this->storage_path )
				$this->set_storage_path( $this->generate_storage_path() );
			
			return $this->storage_path;
		}
		
		/**
		 * Sets subpath, automatically removing preceeding invalid special characters
		 */
		private function set_storage_path( $path )
		{
			$this->storage_path = wp_normalize_path( $path );
			return $this;
		}
		
		/**
		 * Sets subpath, automatically removing preceeding invalid special characters
		 */
		public function set_subpath( $subpath )
		{
			$this->subpath = ltrim( $subpath, "/\\." );
		}
		
		/**
		 * Returns subpath
		 */
		public function get_subpath()
		{
			return $this->subpath;
		}
		
		/**
		 * Returns full path, including the subpath
		 */
		public function get_full_path()
		{
			return path_join( $this->get_storage_path(), $this->get_subpath() );
		}
		
		/**
		 * Recurively creates path directories and prevents directory listing
		 */
		private function initialize_path( $path )
		{
			$path = wp_normalize_path( $path );
			wp_mkdir_p( $path );
		}
		
		/**
		 * Copy a source file to the storage location, ensuring a unique file name
		 */
		public function save( $srcfile, $filename )
		{
			$full_path = $this->get_full_path();
			
			$this->initialize_path( $full_path );
			
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $full_path, $filename );
			
			copy($srcfile, trailingslashit( $full_path ) . $filename);
		}
	}
}
