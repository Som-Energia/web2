<?php

class CombineJS {

	/**
	*Variables
	*/
	const nspace = 'combine-js';
	const pname = 'Combine JS';
	const version = 2.1;

	protected $_plugin_file;
	protected $_plugin_dir;
	protected $_plugin_path;
	protected $_plugin_url;

	var $cachetime = '';
	var $upload_path = '';
	var $upload_uri = '';
	var $js_domain = '';
	var $js_path = '';
	var $js_path_footer = '';
	var $js_uri = '';
	var $js_uri_footer = '';
	var $js_path_tmp = '';
	var $js_path_tmp_footer = '';
	var $js_token = '';
	var $js_settings_path = '';
	var $settings_fields = array();
	var $settings_data = array();
	var $js_files_ignore = array( 'admin-bar.js', 'admin-bar.min.js' );

	var $js_handles_found = array();
    var $js_handles_count_first = 0;
    var $js_handles_count_last = 0;
    var $js_handles_order = array();
	var $js_footer_handles_found = array();
	var $footer = false;
	var $move_to_footer = array();

	var $combined = false;
	var $paths_set = false;
    var $debug = false;

	/**
	*Constructor
	*
	*@return void
	*@since 0.1
	*/
	function __construct() {}

	/**
	*Init function
	*
	*@return void
	*@since 0.1
	*/
	function init() {

		static $init = false;

		if ( $init ) return;

		$init = true;

		// if delete js button is clicked, delete cache

		if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'deletejscache' && isset( $_GET[ '_wpnonce' ] ) ) {
			add_action( 'admin_init', array( &$this, 'admin_bar_delete_cache' ) );
		}

		// internationalize

		add_action( 'init', array( &$this, 'internationalize' ) );

		// add delete js cache button

		add_action( 'wp_before_admin_bar_render', array( &$this, 'delete_cache_button' ) );

		// settings fields

		$this->settings_fields = array(
                        'legend_1' => array(
								'label' => 'General Settings',
								'type' => 'legend'
								),
						'js_domain' => array(
								'label' => 'JavaScript Domain',
								'type' => 'text',
								'default' => get_option( 'siteurl' )
								),
						'cachetime' => array(
								'label' => 'Cache expiration',
								'instruction' => 'How often to refresh JS files in seconds.',
								'type' => 'select',
								'values' => array( '60' => '1 minute', '300' => '5 minutes', '900' => '15 minutes', '1800' => '30 minutes', '3600' => '1 hour' ),
								'default' => '300'
								),
						'htaccess_user_pw' => array(
								'label' => 'Username and password',
								'instruction' => 'Use when site is accessed behind htaccess authentication -- syntax: username:password.',
								'type' => 'text',
								'default' => 'username:password'
								),
						'ignore_files' => array(
								'label' => 'JS files to ignore',
								'instruction' => 'Enter one per line. Only use the name of the JS file (like main.js). You can be more specific about what JS file to ignore by specifying the plugin and then the JS file (like plugin:main.js).',
								'type' => 'textarea'
								),
						'compress' => array(
								'label' => 'GZIP compress JS output?',
								'type' => 'select',
								'values' => array( 'No' => 'No', 'Yes' => 'Yes' ),
								'default' => 'No'
								),
						'compress_html' => array(
                                'label' => 'GZIP Compress HTML output?',
                                'type' => 'select',
                                'values' => array( 'No' => 'No', 'Yes' => 'Yes' ),
                                'default' => 'No'
                                ),
						'footer' => array(
                                'label' => 'Move all JS to footer?',
                                'type' => 'select',
                                'values' => array( 'No' => 'No', 'Yes' => 'Yes' ),
                                'default' => 'No'
                                ),
						'debug' => array(
								'label' => 'Turn on debugging?',
								'type' => 'select',
								'values' => array( 'No' => 'No', 'Yes' => 'Yes' ),
								'default' => 'No'
								)
                            );

		// settings data

		$this->settings_data = unserialize( get_option( self::nspace . '-settings' ) );
		if ( ! $this->settings_data ) $this->settings_data = array();
		foreach ( $this->settings_fields as $key => $val ) {
			if ( ! isset( $this->settings_data[$key] ) ) $this->settings_data[$key] = '';
		}
		$this->cachetime = $this->get_settings_value( 'cachetime' );
		if ( ! @strlen( $this->cachetime ) ) $this->cachetime = 300;
		$this->js_domain = $this->get_settings_value( 'js_domain' );
		if ( ! @strlen( $this->js_domain ) ) $this->js_domain = get_option( 'siteurl' );
		if ( $this->settings_data['debug'] == 'Yes' ) $this->debug = true;
		if ( $this->settings_data['footer'] == 'Yes' ) $this->footer = true;
		if ( ! $this->settings_data['compress'] ) $this->settings_data['compress'] = 'Yes';
		if ( ! $this->settings_data['compress_html'] ) $this->settings_data['compress_html'] = 'No';

		// turn on output compression

        if ( $this->settings_data['compress_html'] == 'Yes' ) ob_start( 'ob_gzhandler' );

		// check upload dirs

		$this->check_upload_dirs();

		if ( is_admin() ) {

			// add settings page

			add_action( 'admin_menu', array( &$this, 'add_settings_page' ), 30 );
		}
		elseif ( strstr( $_SERVER['REQUEST_URI'], 'wp-login' ) || strstr( $_SERVER['REQUEST_URI'], 'gf_page=' ) || strstr( $_SERVER['REQUEST_URI'], 'preview=' ) ) {}
		elseif ( ! file_exists( $this->js_settings_path ) ) {}
		else {

			// add ignore files

			$ignore_list = preg_split( "/\r\n|\n|\r/", $this->settings_data['ignore_files'] );
			foreach ( $ignore_list as $item ) $this->js_files_ignore[] = $item;
			$this->debug( 'ignore list: ' . implode( ', ', $this->js_files_ignore ) );

			// gather and install functions

			add_filter( 'print_scripts_array', array( $this, 'gather_js' ) );
			add_action( 'wp_head', array( $this, 'install_combined_js' ), 501 );
			add_action( 'wp_footer', array( $this, 'install_combined_js_footer' ), 502 );

			// get rid of browser prefetching of next page from link rel="next" tag

			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link' );
		}
	}

	/**
	*Check upload dirs
	*
	*@return void
	*@since 0.3
	*/
	function check_upload_dirs() {

		// make sure upload dirs exist and set file path and uri

		$upload_dir = wp_upload_dir();
		if ( ! file_exists( $upload_dir['basedir'] ) ) wp_mkdir_p( $upload_dir['basedir'] );
		$this->upload_path = $upload_dir['basedir'] . '/' . self::nspace . '/';
		$this->upload_uri = $upload_dir['baseurl'] . '/' . self::nspace . '/';
		if ( ! file_exists( $this->upload_path ) ) wp_mkdir_p( $this->upload_path );

		// create tmp directory

		$this->create_tmp_dir();

		// write settings to temp file so that js script has access to WP settings without having to load WP infrastructure

		$domain = $_SERVER['HTTP_HOST'];
		if ( function_exists( 'get_current_site' ) ) {
			$site = get_current_site();
			$domain = $site->domain;
		}
		$this->js_settings_path = $this->tmp_dir . $domain . '-settings.dat';
		if ( $this->cache_expired( $this->js_settings_path, false ) ) {
			$args = array( 'upload_path' => $this->upload_path, 'compress' => $this->settings_data['compress'] );
			$this->write_file( $this->js_settings_path, serialize( $args ) );
		}

	}

    /**
    *Create tmp dir
    *
    *@return void
    *@since 0.3
    */
    function create_tmp_dir() {
        $this->tmp_dir = $this->get_plugin_path() . '/tmp/';
        if ( ! is_writable( dirname( $this->tmp_dir ) ) ) {
            $this->tmp_dir = sys_get_temp_dir() . '/';
            if ( ! file_exists( $this->tmp_dir ) ) wp_mkdir_p( $this->tmp_dir );
            $this->tmp_dir .= self::nspace . '/';
        }
        if ( ! file_exists( $this->tmp_dir ) ) wp_mkdir_p( $this->tmp_dir );
    }

	/**
	*Translation
	*
	*@return void
	*@since 0.1
	*/
	function internationalize() {
		load_plugin_textdomain( self::nspace, false, $this->get_plugin_dir() . '/lang' );
	}

	/**
	*Cache expired?
	*
	*@return boolean
	*@since 0.1
	*/
	function cache_expired ( $path, $debug = true ) {
		$mtime = 0;
		if( file_exists( $path ) && filesize( $path ) ) $mtime = @filemtime( $path );
		if ( ( time() - $mtime ) > $this->cachetime ) {
			if ( $debug ) $this->debug( 'Cache expired (' . $path . ')' );
			if ( $debug ) $this->debug( 'Time since (' . ( time() - $mtime ) . ') and cache time (' . $this->cachetime . ')' );
			return true;
		}
		if ( $debug ) $this->debug( 'Using cache (' . $path . ')' );
		return false;
	}

	/**
	*File exists
	*
	*@return boolean
	*@since 0.1
	*/
	function file_exists ( $src ) {
		if ( @strlen( $src ) && file_exists( ABSPATH . $src ) ) return true;
		return false;
	}

	/**
	*Get file from source
	*
	*@return string
	*@since 0.1
	*/
	function get_file_from_src ( $src ) {
		$frags = explode( '/', $src );
		return $frags[count( $frags ) -1];
	}

	/**
	*Gather javascript
	*
	*@return void
	*@since 0.1
	*/
	function gather_js ( $to_do ) {

        if ( empty( $to_do ) ) return $to_do;

		global $wp_scripts;
        $handle_count = 0;
		foreach ( $to_do as $key => $handle ) {

            // keep order of all handles

            $this->js_handles_order[$handle] = $handle_count++;

			// keep track of footer files

			if ( isset( $wp_scripts->registered[$handle]->extra['group'] ) ) {
				$this->js_footer_handles_found[$handle] = $wp_scripts->registered[$handle]->src;
			}
			$js_src = $this->strip_domain( $wp_scripts->registered[$handle]->src );
			$js_file = $this->get_file_from_src( $js_src );
			if ( isset( $wp_scripts->registered[$handle]->extra['data'] ) ) {
				echo "<script type='text/javascript'>/* <![CDATA[ */ ";
				echo $this->compress( $wp_scripts->registered[$handle]->extra['data'] ) . "\n";
				echo " /* ]]> */ </script>";
			}
			elseif ( isset( $wp_scripts->registered[$handle]->extra['l10n'] ) ) {
				$vars = array();
				foreach ( $wp_scripts->registered[$handle]->extra['l10n'][1] as $key => $val ) {
					$vars[] = "\t\t\t" . $key . ': "' . $val . '"';
				}
				$extra = "<script type='text/javascript'>/* <![CDATA[ */ ";
				$extra .= "var " . $wp_scripts->registered[$handle]->extra['l10n'][0] . " = { " . implode( ",\n", $vars ) . " }; ";
				$extra .= " /* ]]> */ </script>";
				echo $this->compress( $extra ) . "\n";
			}

			// get context (plugin or theme)

			$context = $this->get_context( $js_src );

			// don't include js that we are to ignore

			if( ! in_array( $context . ':' . $js_file, $this->js_files_ignore ) && ! in_array( $js_file, $this->js_files_ignore ) 
				&& @strlen( $js_src ) && $this->file_exists( $js_src ) ) {
				$msg = 'JS context & file found (' . $context . ':' . $js_file;
				if ( ! $context ) $msg = 'JS file found (' . $js_file;
				if ( isset( $this->js_footer_handles_found[$handle] ) ) $msg .= ' [footer]';
				else $msg .= ' [header]';
				$msg .= ')';
				$this->debug( $msg );
				$this->js_handles_found[$handle] = $js_src;
				unset( $to_do[$key] );
                if ( ! $this->js_handles_count_first ) $this->js_handles_count_first = $handle_count;
                $this->js_handles_count_last = $handle_count;
			}
			elseif ( $this->footer && @strlen( $wp_scripts->registered[$handle]->src ) ) {

				// keep track of external and/or ignored js files to move to footer

				$this->move_to_footer[$handle] = $wp_scripts->registered[$handle]->src;
			}
		}

		if ( array_keys( $this->js_handles_found ) ) {

			// loop through and unset scripts

			foreach ( $to_do as $key => $handle ) {
				$js_src = $this->strip_domain( $wp_scripts->registered[$handle]->src );
				$js_file = $this->get_file_from_src( $js_src );
				$context = $this->get_context( $js_src );
				if( ! in_array( $context . ':' . $js_file, $this->js_files_ignore ) && 
					! in_array( $js_file, $this->js_files_ignore )  && $this->file_exists( $js_src ) ) {
					wp_deregister_script( $handle );
				}
			}
			foreach ( $wp_scripts->queue as $key => $handle ) {
				if ( isset( $this->js_handles_found[$handle] ) ) {
					unset( $wp_scripts->queue[$key] );
				}
			}
		}
		if ( ! $this->footer ) return $to_do;
	}

	/**
	*Get context function
	*
	*@return string
	*@since 0.1
	*/
	function get_context( $js_src = '' ) {
		preg_match( "/(plugins|themes)\/(.*)\/.*/", $js_src, $jmatches );
		$context = '';
		if ( $jmatches ) {
			$context = $jmatches[2];
			$context_list = explode( '/', $context );
			if ( $context_list ) $context = $context_list[0];
		}
		return $context;
	}

	/**
	*Debug function
	*
	*@return void
	*@since 0.1
	*/
	function debug ( $msg ) {
		if ( $this->debug ) error_log( 'DEBUG: ' . $msg );
	}

	/**
	*Combine javascript
	*
	*@return void
	*@since 0.1
	*/
	function combine_js ( $force_to_footer = false ) {

		$this->debug( 'function combine_js' );

		$this->combined = true;

		if ( ! @count( @array_keys( $this->js_handles_found ) ) ) {
			$this->debug( 'No handles found' );
			return;
		}

		// loop through found scripts and cache them to file system

		$header_content = $footer_content = '';
		foreach ( $this->js_handles_found as $handle => $js_src ) {
			$js_file = $this->get_file_from_src( $js_src );
			$context = $this->get_context( $js_src );
			if( $this->file_exists( $js_src ) && ! in_array( $context . ':' . $js_file, $this->js_files_ignore ) 
				&& ! in_array( $js_file, $this->js_files_ignore ) ) {

				// if file is a PHP script, pull content via curl

				$js_content = '';
				if ( preg_match( "/\.php/", $js_src ) ) $js_content = $this->curl_file_get_contents ( $js_src );
				else $js_content = file_get_contents( ABSPATH . $js_src );
				$this->debug( 'combine - ' . ABSPATH . $js_src );
				if ( isset( $this->js_footer_handles_found[$handle] ) || $force_to_footer ) {
					$footer_content .= $this->compress( $js_content, $handle, $js_src );
				}
				else $header_content .= $this->compress( $js_content, $handle, $js_src );
				$this->unset_handle( $handle );
			}
			else $this->debug( 'SRC NOT FOUND: ' . ABSPATH . $js_src );
		}

		// cache content to file system

		$this->cache_content( $header_content, $footer_content );
	}

	/**
    *Unset handle
    *
    *@return void
    *@since 1.4
    */
	function unset_handle ( $handle = '' ) {
		unset( $this->js_footer_handles_found[$handle] );
		unset( $this->js_handles_found[$handle] );
	} 

	/**
    *Set paths
    *
    *@return void
    *@since 1.4
    */
	function set_paths () {

		// get name of file (token) based on md5 hash of js handles

		$this->js_token = md5( implode( '', array_keys( $this->js_handles_found ) ) );

		// set paths (only do once)

		$this->js_path = $this->upload_path . $this->js_token . '.js';
		$this->js_path_footer = $this->upload_path . $this->js_token . '-footer.js';
		$this->js_uri = $this->upload_uri . $this->js_token . '.js';
		$this->js_uri_footer = $this->upload_uri . $this->js_token . '-footer.js';
		$this->js_path_tmp = $this->js_path . '.tmp';
		$this->js_path_tmp_footer = $this->js_path_footer . '.tmp';
		$this->paths_set = true;
	}

	/**
	*Cache content
	*
	*@return void
	*@since 0.1
	*/
	function cache_content ( $header_content, $footer_content ) {
		if ( @strlen( $header_content ) || @strlen( $footer_content ) ) {
			if ( @strlen( $header_content ) ) $this->cache( 'js_path_tmp', $header_content );
			if ( @strlen( $footer_content ) ) $this->cache( 'js_path_tmp_footer', $footer_content );
		}
	}

	/**
	*Write data to file system
	*
	*@return void
	*@since 0.1
	*/
	function cache( $tmp_file, $content ) {
		if ( ! file_exists( $this->$tmp_file ) ) $this->write_file( $this->$tmp_file, $content );
	}

	/**
	*Write file
	*
	*@return void
	*@since 0.4
	*/
	function write_file ( $file, $content ) {
		if ( is_writable ( dirname( $file ) ) ) {
			$this->debug( 'Write: ' . $file );
			$fp = fopen( $file, "w" );
			if ( flock( $fp, LOCK_EX, $wouldblock ) ) { // do an exclusive lock
				fwrite( $fp, $content );
				flock( $fp, LOCK_UN, $wouldblock ); // release the lock
			}
			fclose( $fp );
		}
	}

	/**
	*Get file content via curl
	*
	*@return string
	*@since 0.1
	*/
	function curl_file_get_contents ( $src ) {
		$url = trim( $src );
		$url = preg_replace( "/http(|s):\/\//", "http://" . $this->get_settings_value( 'htaccess_user_pw' ) . "@", $url );
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, $url );
		curl_setopt( $c, CURLOPT_FAILONERROR, false );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_VERBOSE, false );
		curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_SSL_VERIFYHOST, false );
		if( count( $header ) ) {
			curl_setopt ( $c, CURLOPT_HTTPHEADER, $header );
		}
		$contents = curl_exec( $c );
		curl_close( $c );
		return $contents;
	}

	/**
	*Strip domain from path
	*
	*@return string
	*@since 0.1
	*/
	function strip_domain( $src ) {
		if ( strpos( $src, 'http://' ) === false && strpos( $src, 'https://' ) === false ) return $src;
		$src = str_replace( array( 'http://', 'https://' ), array( '', '' ), $src );
		$frags = explode( '/', $src );
		array_shift( $frags );
		return implode( '/', $frags );
	}

	/**
	*Minify content
	*
	*@return string
	*@since 0.1
	*/
	function compress( $content='', $handle='', $src='' ) {
		$minify = true;
		if ( preg_match( "/(\-|\.)min/", $src ) ) $minify = false;
		if ( $minify ) {
			require_once $this->get_plugin_path() . '/classes/jsmin.php';
			return JSMin::minify( $content );
		}
		else return $content . "\n";
	}

	/**
	*Move temp file cache to actual file cache and add script tag to header
	*
	*@return void
	*@since 0.1
	*/
	function install_combined_js () {

		// if no header handles found, return

		if ( ! @count( @array_keys( $this->js_handles_found ) ) ) return;

		// set paths

		$this->set_paths();

		// move temp file to real path

		if ( $this->cache_expired( $this->js_path ) )  {

			// combine javascript

			$this->combine_js();

			// move temp file to actual path

			$this->debug( 'Create combined header file (' . $this->js_path . ')' );
			@rename( $this->js_path_tmp, $this->js_path );
		}

		// add script tag

		if ( file_exists( $this->js_path ) && ! $this->footer ) {
			echo "\t\t" . '<script type="text/javascript" src="' . str_replace( get_option( 'siteurl' ), $this->js_domain, $this->get_plugin_url() . 'js.php?token=' . $this->js_token . '&#038;ver=' . self::version ) . '" charset="UTF-8"></script>' . "\n";
		}
	}

	/**
	*Move temp file cache to actual file cache and add script tag to footer
	*
	*@return void
	*@since 0.1
	*/
	function install_combined_js_footer () {

		// if no header handles found, return

		if ( ! @count( @array_keys( $this->js_footer_handles_found ) ) && ! $this->footer ) return;

		// set paths

		if ( ! $this->paths_set ) $this->set_paths();

		// move temp file to real path

		if ( $this->cache_expired( $this->js_path_footer ) )  {

			// combine javascript, if necessary
        
			if ( ! $this->combined ) $this->combine_js( true );

			// move temp file to actual path

			$this->debug( 'Create combined footer file (' . $this->js_path_footer . ')' );
			@rename( $this->js_path_tmp_footer, $this->js_path_footer );
		}

        // if move to footer set to yes,
        // figure out what js should be above or below our combined file

        $top = $bottom = array();
        if ( $this->footer && array_keys( $this->move_to_footer ) ) {
            foreach ( $this->move_to_footer as $handle => $src ) {
                if ( $this->js_handles_order[$handle] < $this->js_handles_count_first ) $top[$handle] = $src;
                else $bottom[$handle] = $src;
            }
        }

        // add js above combined file

        foreach ( $top as $handle => $src ) {
			echo "\t\t" . '<script type="text/javascript" src="' . $src . '"></script>' . "\n";
		}

		// add script tag

		if ( file_exists( $this->js_path_footer ) || $this->footer ) {
			$query_string = 'token=' . $this->js_token . '&#038;footer=1&#038;ver=' . self::version;
			if ( $this->footer ) $query_string .= '&#038;both=1';
			echo "\t\t" . '<script type="text/javascript" src="' . str_replace( get_option( 'siteurl' ), $this->js_domain, $this->get_plugin_url() . 'js.php?' . $query_string ) . '"></script>' . "\n";
		}

        // add js below combined file

        foreach ( $bottom as $handle => $src ) {
            echo "\t\t" . '<script type="text/javascript" src="' . $src . '"></script>' . "\n";
        }

	}

	/**
	*Add settings page
	*
	*@return void
	*@since 0.1
	*/
	function add_settings_page () {
		if ( current_user_can( 'manage_options' ) ) {
			add_options_page( self::pname, self::pname, 'manage_options', self::nspace . '-settings', array( &$this, 'settings_page' ) );
		}
	}

	/**
	*Settings page
	*
	*@return void
	*@since 0.1
	*/
	function settings_page () {
		if( isset( $_POST['combine-js_update_settings'] ) ) $this->update_settings();
		$this->show_settings_form();
	}

	/**
	*Show settings form
	*
	*@return void
	*@since 0.1
	*/
	function show_settings_form () {
		include( $this->get_plugin_path() . '/views/admin_settings_form.php' );
	}

	/**
	*Get single value from unserialized data
	*
	*@return string
	*@since 0.1
	*/
	function get_settings_value( $key = '' ) {
		return $this->settings_data[$key];
	}

	/**
	*Remove option when plugin is deactivated
	*
	*@return void
	*@since 0.1
	*/
	function delete_settings () {
		delete_option( $this->option_key );
	}

	/**
	*Is associative array function
	*
	*@return string
	*@since 0.1
	*/
	function is_assoc ( $arr ) {
		if ( isset ( $arr[0] ) ) return false;
		return true;
	}

	/**
	*Display a select form element
	*
	*@return string
	*@since 0.1
	*/
	function select_field( $name, $values, $value, $use_label = false, $default_value = '', $custom_label = '' ) {
		ob_start();
		$label = '-- please make a selection --';
		if (@strlen($custom_label)) {
			$label = $custom_label;
		}

		// convert indexed array into associative

		if ( ! $this->is_assoc( $values ) ) {
				$tmp_values = $values;
				$values = array();
				foreach ( $tmp_values as $tmp_value ) {
						$values[$tmp_value] = $tmp_value;
				}
		}
?>
		<select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
				<?php if ( $use_label ): ?>
				<option value=""><?php echo $label; ?></option>

				<?php endif; ?>
				<?php foreach ( $values as $val => $label ) : ?>
						<option value="<?php echo $val; ?>"<?php if ($value == $val || ( $default_value == $val && @strlen( $default_value ) && ! @strlen( $value ) ) ) : ?> selected="selected"<?php endif; ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
		</select>
<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	*Update settings form
	*
	*@return void
	*@since 0.1
	*/
	function update_settings () {
		$data = array();
		foreach( $this->settings_fields as $key => $val ) {
			if( $val['type'] != 'legend' ) $data[$key] = $_POST[$key];
		}
		$this->set_settings( $data );
		$this->delete_cache();
	}

	/**
	*Update serialized array option
	*
	*@return void
	*@since 0.1
	*/
	function set_settings ( $data ) {
		update_option( self::nspace . '-settings', serialize( $data ) );
		$this->settings_data = $data;
	}

	/**
	*Delete cache
	*
	*@return void
	*@since 0.1
	*/
	function delete_cache () {
		$files = glob( $this->upload_path . '*.js' );
		if ( is_array( $files ) ) array_map( "unlink", $files );
		if ( function_exists( 'wp_cache_clear_cache' ) ) wp_cache_clear_cache();
	}

	/**
	*Set plugin file
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_file( $plugin_file ) {
		$this->_plugin_file = $plugin_file;
	}

	/**
	*Get plugin file
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_file() {
		return $this->_plugin_file;
	}

	/**
	*Set plugin directory
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_dir( $plugin_dir ) {
		$this->_plugin_dir = $plugin_dir;
	}

	/**
	*Get plugin directory
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_dir() {
		return $this->_plugin_dir;
	}

	/**
	*Set plugin file path
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_path( $plugin_path ) {
		$this->_plugin_path = $plugin_path;
	}

	/**
	*Get plugin file path
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_path() {
		return $this->_plugin_path;
	}

	/**
	*Set plugin URL
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_url( $plugin_url ) {
		$this->_plugin_url = $plugin_url;
	}

	/**
	*Get plugin URL
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_url() {
		return $this->_plugin_url;
	}

	/**
	*Delete cache button
	*
	*@return void
	*@since 0.6
	*/
	function delete_cache_button() {
		global $wp_admin_bar;
		if ( ! is_user_logged_in() ) return false;
		if ( function_exists( 'current_user_can' ) && false == current_user_can( 'delete_others_posts' ) ) return false;
		$wp_admin_bar->add_menu(
								array(
										'parent' => '',
										'id' => 'delete-js-cache',
										'title' => __( 'Delete JS Cache', self::nspace ),
										'meta' => array( 'title' => __( 'Delete JS Cache', self::nspace ) ),
										'href' => wp_nonce_url( admin_url( 'index.php?action=deletejscache&path=' . urlencode( $_SERVER[ 'REQUEST_URI' ] ) ), 'delete-js-cache' )
										)
								);
	}

	/**
	*Admin bar delete cache
	*
	*@return void
	*@since 0.6
	*/
	function admin_bar_delete_cache() {
		if ( function_exists( 'current_user_can' ) && false == current_user_can( 'delete_others_posts' ) ) return false;
		if ( wp_verify_nonce( $_REQUEST[ '_wpnonce' ], 'delete-js-cache' ) ) {
			$this->delete_cache();
			wp_redirect( preg_replace( '/[ <>\'\"\r\n\t\(\)]/', '', $_GET[ 'path' ] ) );
			die();
		}
	}
}

?>
