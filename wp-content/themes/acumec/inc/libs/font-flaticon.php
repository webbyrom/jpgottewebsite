<?php
if (!function_exists('vc_iconpicker_type_flaticon')) :
    /**
     * awesome class.
     * 
     * @return string[]
     * @author FOX.
     */
    add_filter( 'vc_iconpicker-type-flaticon', 'vc_iconpicker_type_flaticon' );

    function vc_iconpicker_type_flaticon( $icons ) { 
        $flaticon_icons = array(
            array( 'fi flaticon-pie-chart'                  => esc_html( 'pie chart' ) ),
            array( 'fi flaticon-support-1'                  => esc_html( 'support 1' ) ),
            array( 'fi flaticon-support-2'                  => esc_html( 'support 2' ) ),
            array( 'fi flaticon-support'                    => esc_html( 'support' ) ),
            array( 'fi flaticon-strategy'                   => esc_html( 'strategy' ) ),  
            array( 'fi flaticon-idea'                       => esc_html( 'idea' ) ),
            array( 'fi flaticon-idea-1'                     => esc_html( 'idea' ) ),
            array( 'fi flaticon-handshake'                  => esc_html( 'handshake' ) ), 
            array( 'fi flaticon-arrow-down-and-right'       => esc_html( 'arrow down and right' ) ),  
            array( 'fi flaticon-file'                       => esc_html( 'file' ) ), 
            array( 'fi flaticon-medal'                      => esc_html( 'medal' ) ), 
            array( 'fi flaticon-ribbon'                     => esc_html( 'ribbon' ) ), 
            array( 'fi flaticon-broken-link'                => esc_html( 'broken link' ) ), 
            array( 'fi flaticon-man'                        => esc_html( 'man' ) ),            
        );
        return array_merge( $icons, $flaticon_icons );
    }
endif;