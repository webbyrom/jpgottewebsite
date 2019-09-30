<?php
    /* VC single image */
    add_action( 'vc_after_init', 'acumec_add_vc_single_image_new_style' );
    function acumec_add_vc_single_image_new_style() {
      $param = WPBMap::getParam( 'vc_single_image', 'style' );
      $param['value'][esc_html__( 'Top overlap', 'acumec' )] = 'top-overlap';
      $param['value'][esc_html__( 'Full overlap', 'acumec' )] = 'full-overlap';
      vc_update_shortcode_param( 'vc_single_image', $param );
    }
    /* VC Accordion */
    add_action( 'vc_after_init', 'acumec_update_vc_tta_accordion' );
    function acumec_update_vc_tta_accordion() {  
        $style = WPBMap::getParam( 'vc_tta_accordion', 'style' );
        $style['value'][esc_html__( 'Theme', 'acumec' )] = 'theme';
        $style['value'][esc_html__( 'Primary', 'acumec' )] = 'primary';
        $style['value'][esc_html__( 'Second', 'acumec' )] = 'second';
        vc_update_shortcode_param( 'vc_tta_accordion', $style );
    }
     add_action( 'vc_after_init', 'acumec_update_vc_tta_tabs' );
    function acumec_update_vc_tta_tabs() {  
        $style = WPBMap::getParam( 'vc_tta_tabs', 'style' );
        $style['value'][esc_html__( 'Theme', 'acumec' )] = 'theme';
        vc_update_shortcode_param( 'vc_tta_tabs', $style );
    }
    add_action( 'vc_after_init', 'acumec_update_vc_btn_color' );
    function acumec_update_vc_btn_color() {  
        $style = WPBMap::getParam( 'vc_btn', 'color' );
        $style['value'][esc_html__( 'Primary', 'acumec' )] = 'primary';
        $style['value'][esc_html__( 'Default', 'acumec' )] = 'default';
        vc_update_shortcode_param( 'vc_btn', $style );
    }

