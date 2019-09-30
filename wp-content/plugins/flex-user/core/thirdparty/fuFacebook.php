<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 8:40 AM
	 */
	class fuFacebook extends fs_boot {
		private $url = 'https://graph.facebook.com';
		private $client_id;
		private $client_secret;
		
		public function __construct() {
			$options = get_option( fsUser()->setting_slug );
			if ( ! isset( $_SESSION ) ) {
				session_start();
			}
			if ( $options['enable_facebook_checkbox'] == 'yes' ) {
				$this->client_id     = $options['fb_app_id'];
				$this->client_secret = $options['fb_app_secret'];
				if ( isset( $_GET['login'] ) && $_GET['login'] == 'facebook' ) {
					$_SESSION['fs-redirect'] = $_GET['fs-redirect'];
					if ( empty( $this->client_id ) || empty( $this->client_secret ) ) {
						?>
                        <script>
                            alert('Application has not been established! Please try again later.');
                            window.reload();
                        </script>
						<?php
					}
					add_action( 'init', array( $this, 'login_facebook' ) );
				}
				if ( isset( $_GET['code'] ) ) {
					add_action( 'init', array( $this, 'authenticate' ) );
				}
			}
		}
		
		public function login_facebook() {
			wp_redirect( 'https://www.facebook.com/v2.9/dialog/oauth?client_id=' . $this->client_id . '&response_type=code&redirect_uri=' . urlencode( home_url( '/' ) ) . '&auth_type=rerequest&&scope=email' );
			exit();
		}
		
		public function authenticate() {
			if ( isset( $_GET['code'] ) ) {
				$path   = '/v2.9/oauth/access_token';
				$query  = array(
					'client_id'     => $this->client_id,
					'redirect_uri'  => urlencode( home_url( '/' ) ),
					'client_secret' => $this->client_secret,
					'code'          => $_GET['code']
				);
				$result = json_decode( fs_get_curl( 'get', $this->url . $path, $query ) );
				if ( isset( $result->error ) ) {
					return;
				}
				
				$path  = '/me';
				$query = array(
					'access_token' => $result->access_token,
					'fields'       => 'email,name'
                );
				$info  = json_decode( fs_get_curl( 'get', $this->url . $path, $query ) );
				$user  = get_user_by( 'login', 'facebook_' . $info->id );
				if ( ! $user ) {
					$user = get_user_by( 'email', $info->email );
					if ( ! $user ) {
						$user_pass = wp_generate_password();
						$user_data = [
							'user_login' => 'facebook_' . $info->id,
							'user_email' => $info->email,
							'first_name' => $info->name,
							'user_pass'  => $user_pass,
						];
						$user      = wp_insert_user( $user_data );
						$user      = get_user_by( 'login', 'facebook_' . $info->id );
					} else {
						$user = $user->data;
					}
				} else {
					$user = $user->data;
					if ( $user->user_email != $info->email ) {
						$user_pass = wp_generate_password();
						$user_data = [
							'user_login' => $info->email,
							'user_email' => $info->email,
							'first_name' => $info->name,
							'user_pass'  => $user_pass,
						];
						$user      = wp_insert_user( $user_data );
						$user      = get_user_by( 'email', $info->email );
					}
				}
				$user = wp_set_auth_cookie( $user->ID, true );
				
				wp_redirect( $_SESSION['fs-redirect'] );
				unset( $_SESSION['fs-redirect'] );
				if ( empty( $_SESSION ) ) {
					session_destroy();
				}
				exit();
			}
		}
	}