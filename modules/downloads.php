<?php

if( ! defined( 'ABSPATH' ) )
	return;

if( ! class_exists( 'WPCF7_Pdf_Forms_Downloads' ) )
{
	class WPCF7_Pdf_Forms_Downloads
	{
		private static $instance = null;
		
		private $downloads_path = null;
		private $downloads_url = null;
		private $downloads_timeout = 3600;
		private $subdir = null;
		private $files = array();
		
		private function __construct()
		{
			$uploads = wp_upload_dir( null, false );
			$subdir = 'wpcf7_pdf_forms_downloads';
			$this->downloads_path = path_join( $uploads['basedir'], $subdir );
			$this->downloads_url = $uploads['baseurl'] . '/' . $subdir;
			if( defined( 'WPCF7_PDF_FORMS_DOWNLOADS_TIMEOUT_SECONDS' ) )
				$this->downloads_timeout = WPCF7_PDF_FORMS_DOWNLOADS_TIMEOUT_SECONDS;
		}
		
		/*
		 * Returns a global instance of this class
		 */
		public static function get_instance()
		{
			if( empty( self::$instance ) )
				self::$instance = new self;
			
			return self::$instance;
		}
		
		/**
		 * Returns downloads path
		 */
		public function get_downloads_path()
		{
			return $this->downloads_path;
		}
		
		/**
		 * Returns downloads url
		 */
		public function get_downloads_url()
		{
			return $this->downloads_url;
		}
		
		/**
		 * Generates and returns a temporary
		 */
		private function get_subdir()
		{
			if($this->subdir != null)
				return $this->subdir;
			
			$random_max = mt_getrandmax();
			$random_num = zeroise( mt_rand( 0, $random_max ), strlen( $random_max ) );
			$this->subdir = wp_unique_filename( $this->get_downloads_path(), $random_num );
			
			return $this->subdir;
		}
		
		/**
		 * Returns full path, including the subdir
		 */
		public function get_full_path()
		{
			return path_join( $this->get_downloads_path(), $this->get_subdir() );
		}
		
		/**
		 * Returns full path, including the subdir
		 */
		public function get_full_url()
		{
			return $this->get_downloads_url() . '/' . $this->get_subdir();
		}
		
		/**
		 * Recurively creates path directories and prevents directory listing
		 */
		private function initialize_path( $path )
		{
			$path = wp_normalize_path( $path );
			
			wp_mkdir_p( $path );
			
			$index_file = path_join( $path, 'index.php' );
			
			if( file_exists( $index_file ) )
				return;
			
			if( $handle = @fopen( $index_file, 'w' ) )
			{
				fwrite( $handle, "<?php // Silence is golden." );
				fclose( $handle );
			}
		}
		
		/**
		 * Copies a source file into a temporary location available for download
		 */
		public function add_file( $srcfile, $filename, $options = array() )
		{
			$full_path = $this->get_full_path();
			
			$this->initialize_path( $this->get_downloads_path() );
			$this->initialize_path( $full_path );
			
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $full_path, $filename );
			
			$filepath = trailingslashit( $full_path ) . $filename;
			copy( $srcfile, $filepath );
			$url = $this->get_full_url() . '/' .$filename;
			
			array_push($this->files, array(
				'filename' => $filename,
				'url' => $url,
				'filepath' => $filepath,
				'options' => $options,
			));
			
			return $this;
		}
		
		/**
		 * Returns added files
		 */
		public function get_files()
		{
			return $this->files;
		}
		
		/**
		 * Sets download files timeout (in number of seconds)
		 */
		public function set_timeout( $timeout )
		{
			$this->downloads_timeout = $timeout;
			return $this;
		}
		
		/**
		 * Returns download files timeout (in number of seconds)
		 */
		public function get_timeout()
		{
			return $this->downloads_timeout;
		}
		
		/**
		 * Removes old downloads
		 */
		public function delete_old_downloads()
		{
			$timeout = $this->get_timeout();
			
			$path = $this->get_downloads_path();
			
			if( ( $downloads_dir = @opendir( $path ) ) !== FALSE )
			{
				while( FALSE !== ( $temp_item = @readdir( $downloads_dir ) ) )
				{
					if( $temp_item != '.' && $temp_item != '..' )
					{
						$temp_item_path = trailingslashit( $path ) . $temp_item;
						if( is_dir( $temp_item_path ) )
						{
							$mtime = filemtime( $temp_item_path );
							
							if( $mtime && time() < $mtime + $timeout )
								continue;
							
							if( ( $temp_item_dir = @opendir( $temp_item_path ) ) !== FALSE )
							{
								while( FALSE !== ( $file = @readdir( $temp_item_dir ) ) )
								{
									if( $file != '.' && $file != '..' )
									{
										$filepath = trailingslashit( $temp_item_path ) . $file;
										@unlink( $filepath );
									}
								}
								
								closedir( $temp_item_dir );
							}
							
							rmdir( $temp_item_path );
						}
					}
				}
				
				closedir( $downloads_dir );
			}
		}
	}
}
