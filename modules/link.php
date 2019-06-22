<?php

if( ! class_exists( 'WPCF7_Pdf_Ninja_Link_Storage' ) ){

	class WPCF7_Pdf_Ninja_Link_Storage
	{

		private  static $instance = null;

		private  $download_tmp_path = null;
		private  $download_tmp_url = null;
		private  $links = array();

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

		public function set_tmp_path()
		{
			static $base_tmp_path;
			if( !$base_tmp_path )
			{
				// set the default url and dir path
				if (defined('PDF_FORMS_DOWNLOAD_TMP_DIR'))
					$defailt_path = PDF_FORMS_DOWNLOAD_TMP_DIR;
				else
					$defailt_path = 'pdf_form_tmp';

				$this->download_tmp_path = path_join($this->get_base_path('dir'), $defailt_path);
				$this->download_tmp_url = path_join($this->get_base_path('url'), $defailt_path);
				$base_tmp_path = $this->download_tmp_path;
			}
			return $base_tmp_path;
		}


		public function get_download_tmp_path()
		{
			return $this->download_tmp_path;
		}

		public function get_download_url_path()
		{
			return $this->download_tmp_url;
		}

		public function get_links()
		{
			return $this->links;
		}

		private function get_base_path( $type = false )
		{
			$uploads = wp_get_upload_dir();

			if ( 'dir' == $type )return $uploads['basedir'];
			if ( 'url' == $type )return $uploads['baseurl'];
		}

		private function generate_random_make_dir( $dir )
		{
			do {
				$random_max = mt_getrandmax();
				$ram_number = zeroise( mt_rand( 0, $random_max ), strlen( $random_max ) );
				$this->download_tmp_path = path_join( $dir, $ram_number );
				$this->download_tmp_url = path_join( $this->download_tmp_url, $ram_number );
			} while ( file_exists( $this->download_tmp_path ) );

			if ( wp_mkdir_p( $this->download_tmp_path ) )return $ram_number;
		}

		private function check_downloand_dir()
		{

			wp_mkdir_p($this->download_tmp_path);

			$sSoftware = strtolower($_SERVER["SERVER_SOFTWARE"]);
			if (strpos($sSoftware, "apache") !== false)
			{
				$htaccess_file = path_join($this->download_tmp_path, '.htaccess');

				if (file_exists($htaccess_file)) {
					return;
				}

				if ($handle = fopen($htaccess_file, 'w')) {
					fwrite($handle, "Options All -Indexes\n");
					fclose($handle);
				}
			}
			if (strpos($sSoftware, "nginx") !== false)
			{
				$htaccess_file = path_join($this->download_tmp_path, 'index.php');

				if (file_exists($htaccess_file)) {
					return;
				}

				if ($handle = fopen($htaccess_file, 'w')) {
					fwrite($handle, "<?php \n");
					fclose($handle);
				}
			}
		}

		public function init_dir()
		{
			$this->set_tmp_path();
			$this->check_downloand_dir(); // Confirm download dir
			$this->generate_random_make_dir($this->download_tmp_path);
		}

		/**
		 * Copy a temporary download file path and add path to links
		 */
		public function add_link( $srcfile, $filename )
		{
			$filename = sanitize_file_name( wpcf7_canonicalize( $filename ) );
			$filename = wp_unique_filename( $this->download_tmp_path, $filename );

			copy($srcfile, trailingslashit( $this->download_tmp_path ) . $filename);
			$url = ($this->download_tmp_url . '/' .$filename);
			array_push($this->links, array($filename => $url));
			return $this->links;
		}

	    public function get_file_size( $url )
	    {
			$response = wp_remote_get( $url );
		    if ( !$response ) return "Error, please contact the administrator ";
		    $result = $response['headers']['content-length'];
		    return round($result / 1024, 2);
		}

		public function delete_dir( $path )
		{
			// set the default files lifetime
			if (defined('PDF_FORMS_FOR_CF7_TMP_FILES_LIFETIME'))
				$seconds = PDF_FORMS_FOR_CF7_TMP_FILES_LIFETIME;
			else
				$seconds = 60*60*24; //one day 

			if ( file_exists( $path ) AND is_dir( $path ) ) {
				// open the folder
				$dir = opendir($path);
				while ( false !== ( $element = readdir( $dir ) ) ) {
					// delete only the contents of the folder
					if ( $element != '.' AND $element != '..' AND $element != '.htaccess' AND $element != 'index.php')
					{
						$tmp = $path . DIRECTORY_SEPARATOR . $element;
						@chmod( $tmp, 0777 );

						$mtime = filemtime( path_join( $path, $element ) );

						if ( $mtime and time() < $mtime + $seconds ) { // checking time
							continue;
						}
						if ( is_dir( $tmp ) ) {
							$this->delete_dir( $tmp );
						} else {
							@unlink( $tmp );
						}
					}
				}
				closedir($dir);
				if ( file_exists( $path ) &&  $path != $this->set_tmp_path()) {
					@rmdir( $path );
				}
			}
		}
	}
}
