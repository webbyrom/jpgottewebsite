<?php 
add_action( 'init', 'wp_sport_support_gtb');
function wp_sport_support_gtb(){ 
    global $opt_theme_options;  
    if(!empty($opt_theme_options['gutenberg']) ){  
        add_filter( 'use_block_editor_for_post', '__return_false', 100 );
    }
}
add_filter('render_block', 'wp_sport_guten_render_block', 10, 2);
function wp_sport_guten_render_block( $block_content,  $block){
    global $opt_theme_options; 
    $wpb_js_gutenberg_disable = get_option( 'wpb_js_gutenberg_disable', '0' );
    if( !empty($opt_theme_options['gutenberg']) || class_exists('Classic_Editor') || $wpb_js_gutenberg_disable == '1' )
        return $block_content;
    $extra_css_class = ['ef4-gtb-block'];
    $change_class = [
        'core/separator',
        'core/quote',
        'core/button',
        'core/audio',
        'core/columns',
        'core/column',
        'core/pullquote',
        'core/cover',
        'core/cover-image',
        'core/image',
        'core/media-text',
    ];
    if(in_array($block['blockName'], $change_class)){
        $extra_css_class[]  = 'ef4-block-'.str_replace('core/','', $block['blockName']);
    } else {
        $extra_css_class[]  = 'wp-block-'.str_replace('core/','', $block['blockName']);
    }
    if(isset($block['attrs']['align']) && ($block['attrs']['align'] === 'wide' || $block['attrs']['align'] == 'full') ) {
        $extra_css_class[] = 'align-'.$block['attrs']['align'];
    }
    
    if(strlen($block_content) > 2 && $block['blockName'] !== 'core/column' && $block['blockName'] !== 'core/media-text' ) 
        $block_content = '<div class="'.trim(implode(' ', $extra_css_class)).'">'.$block_content.'</div>';
    return $block_content;
}
/**
 * Add theme support style
*/
function wp_sport_theme_suport_gtb_styles(){
    global $opt_theme_options;
    $wpb_js_gutenberg_disable = get_option( 'wpb_js_gutenberg_disable', '0' );
    if( !empty($opt_theme_options['gutenberg']) || class_exists('Classic_Editor') || $wpb_js_gutenberg_disable == '1' ){    
        add_filter('ef4_remove_scripts', 'wp_gutenberg_remove_script');
        return;
    }
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'wp_sport_theme_suport_gtb_styles' );
function wp_gutenberg_remove_script(){
    return array('wp-block-library');
}