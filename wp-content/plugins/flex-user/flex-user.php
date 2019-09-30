<?php
	/**
	 * Plugin Name: Flex Login
	 * Plugin URI: http://fsflex.com/
	 * Description: An plugin developed by FsFlex Team.
	 * Version: 1.0.2
	 * Author: FsFlexTeam
	 * Author URI: http://fsflex.com/
	 *
	 * Text Domain: flex-user
	 */
	$plugin_path = plugin_dir_path( __FILE__ );
	if ( ! class_exists( 'fs_boot' ) ) {
		$boot_dir = rtrim( $plugin_path, '\\\/' ) . DIRECTORY_SEPARATOR . 'boot' . DIRECTORY_SEPARATOR . 'boot.php';
		include_once $boot_dir;
	}
	if ( ! class_exists( 'FlexUser' ) ) {
		class FlexUser extends fs_boot {
			
			static $instance = null;
			public $plugin_folder_name, $plugin_basename, $plugin_data;
			
			public static function instance() {
				
				if ( null === static::$instance ) {
					static::$instance = new static();
					static::$instance->includes();
				}
				
				return static::$instance;
			}
			
			function includes() {
				global $plugin_folder_name;
				$this->plugin_basename    = plugin_basename( __FILE__ );
				$plugin_folder_name       = dirname( $this->plugin_basename );
				$this->plugin_folder_name = $plugin_folder_name;
				$this->init( $plugin_folder_name );
				$this->requireFolder( 'core.config' );
				$this->requireFolder( 'core.helpers' );
				$this->requireFolder( 'core.api' );
				$this->requireFolder( 'core' );
				$this->requireFolder( 'core.admin' );
				$this->requireFolder( 'core.thirdparty' );
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$this->plugin_data = get_plugin_data( __FILE__ );
			}
		}
		
		if ( ! function_exists( 'fsUser' ) ) {
			function fsUser() {
				return FlexUser::instance();
			}
		}
		
		if ( defined( 'FlexUser_LATE_LOAD' ) ) {
			add_action( 'plugins_loaded', 'fsUser', (int) FlexUser_LATE_LOAD );
		} else {
			fsUser();
		}
		add_action( 'plugins_loaded', function () {
			do_action( 'flex-user_init' );
		} );
	}