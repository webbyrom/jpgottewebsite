<?php
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 28/6/2017
	 * Time: 8:43 AM
	 */
	
	if ( ! function_exists( 'fs_get_option' ) ) {
		function fs_get_option( $fields, $default = "" ) {
			$options = get_option( fsUser()->setting_slug, array() );
			if ( is_array( $fields ) || is_object( $fields ) ) {
				$result = array();
				foreach ( $fields as $key => $field ) {
					$def              = ( isset( $default[ $key ] ) ) ? $default[ $key ] : '';
					$result[ $field ] = ( isset( $options[ $field ] ) ) ? $options[ $field ] : $def;
				}
				
				return $result;
			} else {
				return ( isset( $options[ $fields ] ) ) ? $options[ $fields ] : $default;
			}
		}
	}