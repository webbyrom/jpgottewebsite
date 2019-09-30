<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 8:41 AM
	 */
	class fuTwitter extends fs_boot {
		private $url = "https://api.twitter.com";
		private $client_id;
		private $client_secret;
		
		public function __construct() {
			
			$options = get_option( fsUser()->setting_slug );
			if ( ! isset( $_SESSION ) ) {
				session_start();
			}
			if ( $options['enable_twitter_checkbox'] == 'yes' ) {
				$this->client_id     = $options['tw_app_id'];
				$this->client_secret = $options['tw_app_secret'];
				if ( isset( $_GET['login'] ) && $_GET['login'] == 'twitter' ) {
					$_SESSION['fs-redirect'] = $_GET['fs-redirect'];
					if ( empty( $this->client_id ) || empty( $this->client_secret ) ) {
						?>
                        <script>
                            alert('Application has not been established! Please try again later.');
                        </script>
						<?php
					}
					add_action( 'init', array( $this, 'request_token' ) );
				}
				if ( isset( $_GET['oauth_verifier'] ) ) {
					add_action( 'init', array( $this, 'access_token' ) );
				}
			}
		}
		
		public function generate_signature( $method, $url, $data, $client_secret, $token = "" ) {
			ksort( $data );
			if ( isset( $data['oauth_signature'] ) ) {
				unset( $data['oauth_signature'] );
			}
			$query = array();
			foreach ( $data as $key => $value ) {
				$query[] = rawurlencode( $key ) . "=" . rawurlencode( $value );
			}
			$param       = implode( '&', $query );
			$base_string = strtoupper( $method ) . '&' . rawurlencode( $url ) . '&' . rawurlencode( $param );
			$key         = rawurlencode( $client_secret ) . '&' . rawurlencode( $token );
			$signature   = base64_encode( hash_hmac( 'sha1', $base_string, $key, true ) );
			
			return $signature;
		}
		
		public function generate_auth( $data ) {
			$auth = 'OAuth ';
			$head = array();
			foreach ( $data as $key => $value ) {
				$head[] = rawurlencode( $key ) . "=\"" . rawurlencode( $value ) . "\"";
			}
			$auth .= implode( ',', $head );
			
			return $auth;
		}
		
		public function generate_nonce() {
			return md5( microtime() . uniqid() );
		}
		
		public function generate_timestamp() {
			return time();
		}
		
		public function generate_data() {
			$current_url = $_GET['redirect'];
			
			return array(
				'oauth_callback'         => $current_url,
				'oauth_consumer_key'     => $this->client_id,
				'oauth_nonce'            => $this->generate_nonce(),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp'        => $this->generate_timestamp(),
				'oauth_version'          => '1.0',
			);
		}
		
		public function param_to_array( $string ) {
			$result = explode( '&', $string );
			$array  = array();
			foreach ( $result as $key => $value ) {
				$arr              = explode( '=', $value );
				$array[ $arr[0] ] = $arr[1];
			}
			
			return $array;
		}
		
		public function request_token() {
			
			$path   = '/oauth/request_token';
			$method = 'POST';
			$data   = $this->generate_data();
			
			$oauth_signature         = $this->generate_signature( $method, $this->url . $path, $data, $this->client_secret );
			$data['oauth_signature'] = $oauth_signature;
			$auth                    = $this->generate_auth( $data );
			$headers                 = array(
				'Authorization' => $auth,
			);
			
			$args   = array(
				'headers' => $headers,
			);
			$result = fs_get_curl( $method, $this->url . $path, $args );
			if ( isset( json_decode( $result )->errors ) ) {
				return;
			}
			$token = $this->param_to_array( $result );
			wp_redirect( $this->url . '/oauth/authenticate?oauth_token=' . $token['oauth_token'] );
			exit();
		}
		
		public function access_token() {
			$path   = '/oauth/access_token';
			$method = 'POST';
			
			$oauth_verifier = $_GET['oauth_verifier'];
			$oauth_token    = $_GET['oauth_token'];
			
			$data                    = $this->generate_data();
			$data['oauth_token']     = $oauth_token;
			$oauth_signature         = $this->generate_signature( $method, $this->url . $path, $data, $this->client_secret );
			$data['oauth_signature'] = $oauth_signature;
			$auth                    = $this->generate_auth( $data );
			$headers                 = array(
				'Authorization' => $auth,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			);
			$body                    = array(
				'oauth_verifier' => $oauth_verifier,
			);
			$args                    = array(
				'headers' => $headers,
				'body'    => $body,
			);
			$result                  = fs_get_curl( $method, $this->url . $path, $args );
			if ( isset( json_decode( $result )->errors ) ) {
				return;
			}
			$token = $this->param_to_array( $result );
			
			$path   = '/1.1/account/verify_credentials.json';
			$method = 'GET';
			
			$data                    = $this->generate_data();
			$data['oauth_token']     = $token['oauth_token'];
			$data['include_email']   = 'true';
			$data['skip_status']     = 'true';
			$data['oauth_signature'] = $this->generate_signature( $method, $this->url . $path, $data, $this->client_secret, $token['oauth_token_secret'] );
			$auth                    = $this->generate_auth( $data );
			
			$param   = array(
				'include_email' => 'true',
				'skip_status'   => 'true',
			);
			$headers = array(
				'Authorization' => $auth,
				'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8',
			);
			$args    = array(
				'headers' => $headers,
			);
			$info    = json_decode( fs_get_curl( 'get', $this->url . $path, $param, $args ) );
			if ( isset( $info->errors ) ) {
				return;
			}
			$user      = get_user_by( 'login', 'twitter_' . $info->id );
			$user_data = array();
			if ( ! $user ) {
				$user = get_user_by( 'email', $info->email );
				if ( ! $user ) {
					$user_pass = wp_generate_password();
					$user_data = [
						'user_login' => 'twitter_' . $info->id,
						'user_email' => $info->email,
						'first_name' => $info->name,
						'user_pass'  => $user_pass,
					];
					$user      = wp_insert_user( $user_data );
					$user      = get_user_by( 'login', 'twitter_' . $info->id );
				} else {
					$user = $user->data;
				}
			} else {
				$user = $user->data;
				if ( $user->email != $info->email ) {
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
			wp_safe_redirect( $_SESSION['fs-redirect'] );
			unset( $_SESSION['fs-redirect'] );
			if ( empty( $_SESSION ) ) {
				session_destroy();
			}
			exit();
		}
	}