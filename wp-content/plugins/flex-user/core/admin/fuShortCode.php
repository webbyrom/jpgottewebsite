<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 9:21 AM
	 */
	if ( ! class_exists( 'fuShortCode' ) ) {
		class fuShortCode extends fs_boot {
			
			/**
			 * fuShortCode constructor.
			 */
			public function __construct() {
				$this->init( fsUser()->plugin_folder_name );
				add_shortcode( 'fs-login', array( &$this, 'add_form_login' ) );
				add_shortcode( 'fs-register', array( &$this, 'add_form_register' ) );
				add_shortcode( 'fs-auth', array( &$this, 'add_form_auth' ) );
			}
			
			public function add_form_login( $atts ) {
				if ( is_user_logged_in() ) {
					return $this->get_template_file__( 'logout', array( 'atts' => $atts ), '', 'flex-login' );
				}
				$atts = shortcode_atts(
					array(
						'id' => '',
					), $atts );
				wp_enqueue_script( 'jquery.validate.js', $this->plugin_url . 'assets/vendor/jquery.validate.js', array(), '', true );
				wp_register_script( 'fs-login.js', $this->plugin_url . 'assets/js/fs-login.js', array(), '', true );
				wp_localize_script( 'fs-login.js', 'fs_login', array(
					'action' => 'fs_login',
					'url'    => admin_url( 'admin-ajax.php' ),
				) );
				wp_enqueue_script( 'fs-login.js' );
				
				return $this->get_template_file__( 'login_form', array( 'atts' => $atts ), '', 'flex-login' );
			}
			
			public function add_form_register( $atts ) {
				if ( is_user_logged_in() ) {
//
					return $this->get_template_file__( 'logout', array( 'atts' => $atts ), '', 'flex-login' );
					
				}
				$atts = shortcode_atts(
					array(
						'id' => '',
					), $atts );
				wp_enqueue_script( 'jquery.validate.js', $this->plugin_url . 'assets/vendor/jquery.validate.js', array(), '', true );
				wp_enqueue_script( 'fs-register.js', $this->plugin_url . 'assets/js/fs-register.js', array(), '', true );
				wp_localize_script( 'fs-register.js', 'fs_register', array(
					'action' => 'fs_register',
					'url'    => admin_url( 'admin-ajax.php' ),
				) );
				
				return $this->get_template_file__( 'register_form', array( 'atts' => $atts ), '', 'flex-login' );
			}
			
			public function add_form_auth( $atts ) {
				if ( is_user_logged_in() ) {
					return $this->get_template_file__( 'logout', array( 'atts' => $atts ), '', 'flex-login' );
				}
				$atts = shortcode_atts(
					array(
						'id' => '',
					), $atts );
				wp_enqueue_style( 'fs-user-form.css', $this->plugin_url . 'assets/css/fs-user-form.css', array(), '', 'all' );
				wp_enqueue_script( 'jquery.validate.js', $this->plugin_url . 'assets/vendor/jquery.validate.js', array(), '', true );
				wp_register_script( 'fs-login.js', $this->plugin_url . 'assets/js/fs-login.js', array(), '', true );
				wp_localize_script( 'fs-login.js', 'fs_login', array(
					'action' => 'fs_login',
					'url'    => admin_url( 'admin-ajax.php' ),
				) );
				wp_enqueue_script( 'fs-login.js' );
				wp_enqueue_script( 'fs-login.js', $this->plugin_url . 'assets/js/fs-login.js', array(), '', true );
				wp_localize_script( 'fs-login.js', 'fs_register', array(
					'action' => 'fs_register',
					'url'    => admin_url( 'admin-ajax.php' ),
				) );
				
				return $this->get_template_file__( 'auth_form', array( 'atts' => $atts ), '', 'flex-login' );
			}
		}
	}