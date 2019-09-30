<?php
if (!function_exists('vc_iconpicker_type_foodfont2')) :
    /**
     * awesome class.
     * 
     * @return string[]
     * @author FOX.
     */
    add_filter( 'vc_iconpicker-type-foodfont2', 'vc_iconpicker_type_foodfont2' );

    function vc_iconpicker_type_foodfont2( $icons ) { 
        $foodfont2_icons = array(
            array( 'icon icon-food2-food'                               => esc_html( 'food' ) ),
            array( 'icon icon-food2-music'                              => esc_html( 'music' ) ),
            array( 'icon icon-food2-crown-king-streamline'              => esc_html( 'crown-king-streamline' ) ),
            array( 'icon icon-food2-cocktail-mojito-streamline'         => esc_html( 'cocktail-mojito-streamline' ) ),
            array( 'icon icon-food2-eat-food-fork-knife-streamline'     => esc_html( 'eat-food-fork-knife-streamline' ) ),
            array( 'icon icon-food2-chef-food-restaurant-streamline'    => esc_html( 'chef-food-restaurant-streamline' ) ),
            array( 'icon icon-food2-fire'                               => esc_html( 'fire' ) ),
            array( 'icon icon-food2-bag-1'                              => esc_html( 'bag-1' ) ),
            array( 'icon icon-food2-japan-streamline-tea'               => esc_html( 'japan-streamline-tea' ) ),
           
        );
        return array_merge( $icons, $foodfont2_icons );
    }
endif;