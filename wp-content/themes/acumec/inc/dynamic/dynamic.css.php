<?php

/**
 * Auto create css from Meta Options.
 * 
 * @author Fox
 * @version 1.0.0
 */
class CMSSuperHeroes_DynamicCss
{

    function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'generate_css'));
    }

    /**
     * generate css inline.
     *
     * @since 1.0.0
     */
    public function generate_css()
    {

        wp_enqueue_style('custom-dynamic',get_template_directory_uri() . '/assets/css/custom-dynamic.css');

        $_dynamic_css = $this->css_render();
 
        wp_add_inline_style('custom-dynamic', $_dynamic_css);
    }

    /**
     * header css
     *
     * @since 1.0.0
     * @return string
     */
    public function css_render()
    {
        global $opt_theme_options,$opt_meta_options;

        ob_start();

        /* custom css. */
        if (class_exists('EF4Framework') && !is_search()){
            
            if(isset($opt_meta_options['content_padding']['padding-top']) && $opt_meta_options['content_padding']['padding-top'] != '' ){
                echo 'body .site-content{padding-top:'.$opt_meta_options['content_padding']['padding-top'].';}';
            }
            if(isset($opt_meta_options['content_padding']['padding-bottom']) && $opt_meta_options['content_padding']['padding-bottom'] != ''){
                echo 'body .site-content{padding-bottom:'.$opt_meta_options['content_padding']['padding-bottom'].';}';
            }
            if(isset($opt_meta_options['opt_general_layout']) && $opt_meta_options['opt_general_layout']){ 
                echo '@media screen and (min-width: 1200px){
                    body .cs-boxed{ width:'.esc_attr($opt_meta_options['body_width']['width']).';}
                }';
            }
            if(is_page() && isset($opt_meta_options['opt_general_layout']) && $opt_meta_options['opt_general_layout'] == '1'){ 
                echo 'body.boxed-layout{';
                echo !empty($opt_meta_options['opt_general_background']['background-color'])?'background-color:'.esc_attr($opt_meta_options['opt_general_background']['background-color']).';':'';
                echo !empty($opt_meta_options['opt_general_background']['background-repeat'])?'background-repeat:'.esc_attr($opt_meta_options['opt_general_background']['background-repeat']).';':'';
                echo !empty($opt_meta_options['opt_general_background']['background-size'])?'background-size:'.esc_attr($opt_meta_options['opt_general_background']['background-size']).';':'';
                echo !empty($opt_meta_options['opt_general_background']['background-attachment'])?'background-attachment:'.esc_attr($opt_meta_options['opt_general_background']['background-attachment']).';':'';
                echo !empty($opt_meta_options['opt_general_background']['background-position'])?'background-position:'.esc_attr($opt_meta_options['opt_general_background']['background-position']).';':'';
                echo !empty($opt_meta_options['opt_general_background']['background-image'])?'background-image: url('.esc_url($opt_meta_options['opt_general_background']['background-image']).');':'';
                echo '}';
                echo 'body.boxed-layout .site-content{';
                echo !empty($opt_meta_options['opt_content_background']['background-color'])?'background-color:'.esc_attr($opt_meta_options['opt_content_background']['background-color']).';':'';
                echo !empty($opt_meta_options['opt_content_background']['background-repeat'])?'background-repeat:'.esc_attr($opt_meta_options['opt_content_background']['background-repeat']).';':'';
                echo !empty($opt_meta_options['opt_content_background']['background-size'])?'background-size:'.esc_attr($opt_meta_options['opt_content_background']['background-size']).';':'';
                echo !empty($opt_meta_options['opt_content_background']['background-attachment'])?'background-attachment:'.esc_attr($opt_meta_options['opt_content_background']['background-attachment']).';':'';
                echo !empty($opt_meta_options['opt_content_background']['background-position'])?'background-position:'.esc_attr($opt_meta_options['opt_content_background']['background-position']).';':'';
                echo !empty($opt_meta_options['opt_content_background']['background-image'])?'background-image: url('.esc_url($opt_meta_options['opt_content_background']['background-image']).');':'';
                echo '}';
            }
             
        }
        ?>
        <?php
        
        return ob_get_clean();
    }
}

new CMSSuperHeroes_DynamicCss();