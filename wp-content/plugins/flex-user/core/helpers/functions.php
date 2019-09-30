<?php
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 9:30 AM
	 */
	if ( ! function_exists( 'fs_get_curl' ) ) {
		function fs_get_curl( $method = 'get', $url, $query = array(), $args = array() ) {
			if ( $url == "" ) {
				return "";
			}
			
			if ( $method == 'get' || $method == 'GET' ) {
				
				if ( strpos( $url, '?' ) === false ) {
					$flag = false;
				}
				
				foreach ( $query as $key => $q ) {
					if ( $flag === false ) {
						$url  .= "?";
						$flag = true;
					} else {
						$url .= "&";
						
					}
					$url .= $key . '=' . $q;
				}
				// var_dump(wp_remote_get($url, $args));
				$result = wp_remote_get( $url, $args );
				if(is_wp_error($result)){
					return $result;
				}
				return isset( $result['body'] ) ? $result['body'] : array();
			} elseif ( $method == 'post' || $method == 'POST' ) {
				// var_dump(wp_remote_post($url, $args));
				$result = wp_remote_post( $url, $query );
				
				return isset( $result['body'] ) ? $result['body'] : array();
				
			}
			
			return "";
		}
	}