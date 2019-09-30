<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 8:43 AM
	 */
	class fuHandle extends fs_boot {
		
		/**
		 * fuHandle constructor.
		 */
		public function __construct() {
			$this->init( fsUser()->plugin_folder_name );
			add_action( 'wp_ajax_nopriv_fs_login', array( $this, 'fs_login' ) );
			add_action( 'wp_ajax_nopriv_fs_register', array( $this, 'fs_register' ) );
		}
		
		public function fs_login() {
			$data     = $_POST['data'];
			$username = $data['username'];
			$password = $data['password'];
			$remember = $data['remember'];
			$creds    = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => ( $remember ) ? true : false
			);
			$user     = wp_signon( $creds, false );
			if ( is_wp_error( $user ) ) {
				die( json_encode( array(
					'type'    => 'error',
					'message' => $user->get_error_message()
				) ) );
			}
			do_action( 'fs-after-login', $data );
			die( json_encode( array(
				'type'    => 'success',
				'message' => 'Login Successfully!'
			) ) );
		}
		
		public function fs_register() {
			$data  = $_POST['data'];
			$creds = array(
				'user_email' => $data['fs_email'],
				'first_name' => $data['fs_first_name'],
				'last_name'  => $data['fs_last_name'],
				'user_login' => $data['fs_username'],
				'user_pass'  => $data['fs_password'],
			);
			$user  = wp_insert_user( $creds, false );
			if ( is_wp_error( $user ) ) {
				die( json_encode( array(
					'type'    => 'error',
					'message' => $user->get_error_message(),
				) ) );
			}
			
			do_action( 'fs-after-register', $data );
			wp_signon(array(
				'user_login'    => $data['fs_username'],
				'user_password' => $data['fs_password'],
				'remember'      => false
			));
			die( json_encode( array(
				'type'    => 'success',
				'message' => 'Register Successfully!'
			) ) );
		}
	}