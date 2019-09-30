<?php

/**
 * Auto create .css file from Theme Options
 * @author Fox
 * @version 1.0.0
 */
class CMSSuperHeroes_StaticCss
{

    public $scss;
    
    function __construct()
    {
        if(!class_exists('scssc'))
            return;

        /* scss */
        $this->scss = new scssc();
        
        /* set paths scss */
        $this->scss->setImportPaths(get_template_directory() . '/assets/scss/');
             
        /* generate css over time */
		add_action('wp', array($this, 'generate_over_time'));
        
        /* save option generate css */
       	add_action("redux/options/opt_theme_options/saved", array($this,'generate_file'));
    }
	
    public function generate_over_time(){
    	
    	global $opt_theme_options;

    	if (!empty($opt_theme_options) && $opt_theme_options['dev_mode']){
    	    $this->generate_file();
    	}
    }
    /**
     * generate css file.
     *
     * @since 1.0.0
     */
    public function generate_file()
    {
        global $opt_theme_options, $wp_filesystem;
        
        if (empty($wp_filesystem) || !isset($opt_theme_options))
            return;
            
        $options_scss = get_template_directory() . '/assets/scss/options.scss';

        /* delete files options.scss */
        $wp_filesystem->delete($options_scss);

        /* write options to scss file */
        $wp_filesystem->put_contents($options_scss, $this->css_render(), FS_CHMOD_FILE); // Save it

        /* minimize CSS styles */
        if (!$opt_theme_options['dev_mode'])
            $this->scss->setFormatter('scss_formatter_compressed');

        /* compile scss to css */
        $css = $this->scss_render();

        $file = "static.css";

        $file = get_template_directory() . '/assets/css/' . $file;

        /* delete files static.css */
        $wp_filesystem->delete($file);

        /* write static.css file */
        $wp_filesystem->put_contents($file, $css, FS_CHMOD_FILE); // Save it
    }

    /**
     * scss compile
     * 
     * @since 1.0.0
     * @return string
     */
    public function scss_render(){
        /* compile scss to css */
        return $this->scss->compile('@import "master.scss"');
    }

    public function primary_HexToRGB($hex,$opacity = 1) {
        $hex = str_replace("#",null, $hex);
        $color = array();
        if(strlen($hex) == 3) {
            $color['r'] = hexdec(substr($hex,0,1).substr($hex,0,1));
            $color['g'] = hexdec(substr($hex,1,1).substr($hex,1,1));
            $color['b'] = hexdec(substr($hex,2,1).substr($hex,2,1));
            $color['a'] = $opacity;
        }
        else if(strlen($hex) == 6) {
            $color['r'] = hexdec(substr($hex, 0, 2));
            $color['g'] = hexdec(substr($hex, 2, 2));
            $color['b'] = hexdec(substr($hex, 4, 2));
            $color['a'] = $opacity;
        }
        $color = "rgba(".implode(', ', $color).")";
        return $color;
    }
    /** 
     * main css 
     *
     * @since 1.0.0
     * @return string
     */
    public function css_render()
    {
        global $opt_theme_options,$opt_meta_options;
        
        ob_start();
        
        

        /* boxed layout. */
        if(isset($opt_theme_options['general_layout']) && $opt_theme_options['general_layout'] == '1')
            echo '$boxed_width:'.esc_attr($opt_theme_options['body_width']['width']).';';
        else 
            echo '$boxed_width: 1238px;';
        if(isset($opt_meta_options['opt_general_layout']) && $opt_meta_options['opt_general_layout'] == '1'){
            echo '$boxed_width:'.esc_attr($opt_meta_options['body_width']['width']).';';
        }

        /* default_color */
        if(!empty($opt_theme_options['default_color']) ) {
            echo '$default_color:'.esc_attr($opt_theme_options['default_color']).';';
            echo '$default_color_overlay:'.'rgba('.esc_attr($opt_theme_options['default_color']).', 0.8)'.';';
        }  
            
        /* primary_color */
        if(!empty($opt_theme_options['primary_color'])) {
            echo '$primary_color:'.esc_attr($opt_theme_options['primary_color']).';';
            echo '$primary_color_overlay:'.'rgba('.esc_attr($opt_theme_options['primary_color']).', 0.95)'.';';
            echo '$primary_color_overlay1:'.'rgba('.esc_attr($opt_theme_options['primary_color']).', 0.9)'.';';
            echo '$primary_color_overlay2:'.'rgba('.esc_attr($opt_theme_options['primary_color']).', 0.8)'.';';
        }

        /* primary_color 1 */
        if(!empty($opt_theme_options['primary_color1'])) {
            echo '$primary_color1:'.esc_attr($opt_theme_options['primary_color1']).';';
            echo '$primary_color1_overlay:'.'rgba('.esc_attr($opt_theme_options['primary_color1']).', 0.95)'.';';
            echo '$primary_color1_overlay1:'.'rgba('.esc_attr($opt_theme_options['primary_color1']).', 0.9)'.';';
            echo '$primary_color1_overlay2:'.'rgba('.esc_attr($opt_theme_options['primary_color1']).', 0.8)'.';';
        }

        /* primary_color 2 */
        if(!empty($opt_theme_options['primary_color2'])) {
            echo '$primary_color2:'.esc_attr($opt_theme_options['primary_color2']).';';
            echo '$primary_color2_overlay:'.'rgba('.esc_attr($opt_theme_options['primary_color2']).', 0.95)'.';';
            echo '$primary_color2_overlay1:'.'rgba('.esc_attr($opt_theme_options['primary_color2']).', 0.9)'.';';
            echo '$primary_color2_overlay2:'.'rgba('.esc_attr($opt_theme_options['primary_color2']).', 0.8)'.';';
        }

        /* second_color */
        if(!empty($opt_theme_options['second_color'])){
            echo '$second_color:'.esc_attr($opt_theme_options['second_color']).';';
            echo '$second_color_overlay:'.'rgba('.esc_attr($opt_theme_options['second_color']).', 0.8)'.';';         
        }

        /* border_color */
        if(!empty($opt_theme_options['border_color']))
            echo '$border_color:'.esc_attr($opt_theme_options['border_color']).';';

        /* submenu hover color */
        if(!empty($opt_theme_options['menu_hover_color']))
            echo '$menu_hover_color:'.esc_attr($opt_theme_options['menu_hover_color']).';';
        else
            echo '$menu_hover_color: #050508;';

        /* link color */        
        if(!empty($opt_theme_options['link_color']['regular'])) {
            echo '$link_color_regular:'.esc_attr($opt_theme_options['link_color']['regular']).';';
        }
            
        if(!empty($opt_theme_options['link_color']['hover'])) {
            echo '$link_color_hover:'.esc_attr($opt_theme_options['link_color']['hover']).';';
           
        }

        //button_primary
        if(!empty($opt_theme_options['button_primary_color']['regular']))
            echo '$button_primary_color_regular:'.esc_attr($opt_theme_options['button_primary_color']['regular']).';';
        else 
            echo '$button_primary_color_regular: #ffffff;';

        if(!empty($opt_theme_options['button_primary_color']['hover']))
            echo '$button_primary_color_hover:'.esc_attr($opt_theme_options['button_primary_color']['hover']).';';
        else 
            echo '$button_primary_color_hover: #ffffff;';

        if(!empty($opt_theme_options['button_primary_bg']['regular']))
            echo '$button_primary_bg_regular:'.esc_attr($opt_theme_options['button_primary_bg']['regular']).';';
        else 
            echo '$button_primary_bg_regular: '.$opt_theme_options['primary_color'].';';  

        if(!empty($opt_theme_options['button_primary_bg']['hover']))
            echo '$button_primary_bg_hover:'.esc_attr($opt_theme_options['button_primary_bg']['hover']).';';
        else 
            echo '$button_primary_bg_hover:#0c0d12; ';

        if(!empty($opt_theme_options['button_primary_border']['regular']))
            echo '$button_primary_border_regular:'.esc_attr($opt_theme_options['button_primary_border']['regular']).';';
        else 
            echo '$button_primary_border_regular:transparent;';
        if(!empty($opt_theme_options['button_primary_border']['hover']))
            echo '$button_primary_border_hover:'.esc_attr($opt_theme_options['button_primary_border']['hover']).';';
        else 
            echo '$button_primary_border_hover: transparent;';

        //button_default
        if(!empty($opt_theme_options['button_default_color']['regular']))
            echo '$button_default_color_regular:'.esc_attr($opt_theme_options['button_default_color']['regular']).';';
        else 
            echo '$button_default_color_regular: #ffffff;';
        if(!empty($opt_theme_options['button_default_color']['hover']))
            echo '$button_default_color_hover:'.esc_attr($opt_theme_options['button_default_color']['hover']).';';
        else 
            echo '$button_default_color_hover: #ffffff;';

        if(!empty($opt_theme_options['button_default_bg']['regular']))
            echo '$button_default_bg_regular:'.esc_attr($opt_theme_options['button_default_bg']['regular']).';';
        else 
            echo '$button_default_bg_regular:#0c0d12;';
        if(!empty($opt_theme_options['button_default_bg']['hover']))
            echo '$button_default_bg_hover:'.esc_attr($opt_theme_options['button_default_bg']['hover']).';';
        else 
            echo '$button_default_bg_hover: #82d10c;';

        if(!empty($opt_theme_options['button_default_border']['regular']))
            echo '$button_default_border_regular:'.esc_attr($opt_theme_options['button_default_border']['regular']).';';
        else 
            echo '$button_default_border_regular:#0c0d12;';
        if(!empty($opt_theme_options['button_default_border']['hover']))
            echo '$button_default_border_hover:'.esc_attr($opt_theme_options['button_default_border']['hover']).';';
        else 
            echo '$button_default_border_hover: #82d10c;';

        //button_second
        if(!empty($opt_theme_options['button_second_color']['regular']))
            echo '$button_second_color_regular:'.esc_attr($opt_theme_options['button_second_color']['regular']).';';
        else 
            echo '$button_second_color_regular: #ffffff;';
        if(!empty($opt_theme_options['button_second_color']['hover']))
            echo '$button_second_color_hover:'.esc_attr($opt_theme_options['button_second_color']['hover']).';';
        else 
            echo '$button_second_color_hover: #ffffff;';

        if(!empty($opt_theme_options['button_second_bg']['regular']))
            echo '$button_second_bg_regular:'.esc_attr($opt_theme_options['button_second_bg']['regular']).';';
        else 
            echo '$button_second_bg_regular:#019dd8;';
        if(!empty($opt_theme_options['button_second_bg']['hover']))
            echo '$button_second_bg_hover:'.esc_attr($opt_theme_options['button_second_bg']['hover']).';';
        else 
            echo '$button_second_bg_hover: #323232;';

        if(!empty($opt_theme_options['button_second_border']['regular']))
            echo '$button_second_border_regular:'.esc_attr($opt_theme_options['button_second_border']['regular']).';';
        else 
            echo '$button_second_border_regular:#019dd8;';
        if(!empty($opt_theme_options['button_second_border']['hover']))
            echo '$button_second_border_hover:'.esc_attr($opt_theme_options['button_second_border']['hover']).';';
        else 
            echo '$button_second_border_hover: #323232;';

        /* logo_max_height */
        if(!empty($opt_theme_options['logo_max_height']['height']))
            echo '#cshero-header-logo img{max-height:'.esc_attr($opt_theme_options['logo_max_height']['height']).';}';
        
        /* menu_toggle_height */
        if(!empty($opt_theme_options['menu_height']['height']))
            echo '#cshero-header-navigation .menu-main-menu > li .cs-menu-toggle i{line-height:'.esc_attr($opt_theme_options['menu_height']['height']).';}';
        
        /* sticky menu. */
        if(isset($opt_theme_options['menu_sticky']) && $opt_theme_options['menu_sticky']){
            echo '.sticky-desktop.header-fixed{position:fixed !important;top: 0;z-index: 999;width: 100%;}';
            echo '.admin-bar .sticky-desktop.header-fixed{top: 32px;}';
        }
        return ob_get_clean();
    }
   
}

new CMSSuperHeroes_StaticCss();