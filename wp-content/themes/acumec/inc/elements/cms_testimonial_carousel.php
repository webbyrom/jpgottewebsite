<?php
vc_map(array(
    'name' => 'CMS Testimonial Carousel',
    'base' => 'cms_testimonial_carousel',
    'icon' => 'cs_icon_for_vc',
    'category' => esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    'description' => esc_html__('Add clients Testimonial', 'acumec'),
    'params' => array(
        array(
            'type' => 'img',
            'heading' => esc_html__('Layout Mode','acumec'),
            'param_name' => 'layout_mode',
                'value' =>  array(
                    'layout1' => get_template_directory_uri().'/vc_params/layouts/testi3.png',
                ),
        ),
        array(
            'type'          => 'param_group',
            'heading'       => esc_html__( 'Add your Testimonial', 'acumec' ),
            'param_name'    => 'values',
            'value'         => '',
            'dependency' => array(
                'element' => 'layout_mode',
                'value' => array(
                    'layout1',
                ),
            ),
            'params' => array(
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__( 'Testimonial name', 'acumec' ),
                    'param_name'    => 'testi_name',
                    'admin_label'   => true,
                    'value'         => '',
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => esc_html__( 'Testimonial Position', 'acumec' ),
                    'param_name'    => 'testi_position',
                    'value'         => '',
                ),
                array(
                    'type'          => 'attach_image',
                    'heading'       => esc_html__( 'Testimonial Image', 'acumec' ),
                    'param_name'    => 'testi_avatar',
                    'value'         => ''
                ),
                array(
                    'type'          => 'textarea',
                    'heading'       => esc_html__( 'Testimonial text', 'acumec' ),
                    'description'   => esc_html__('Press double ENTER to get line-break','acumec'),
                    'param_name'    => 'text',
                    'value'         => ''
                ),
                array(
                    "type" => "vc_link",
                    "heading" => esc_html__("URL (Link)",'acumec'),
                    "param_name" => "link",
                    "value" => "",
                ), 
                
            ),
            'group' => esc_html__('Testimonial Item','acumec')
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Text Color", 'acumec'),
            "param_name" => "t_color",
            'group'         => esc_html__('Testimonial Setting', 'acumec')
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Position Color", 'acumec'),
            "param_name" => "p_color",
            'group'         => esc_html__('Testimonial Setting', 'acumec')
        ),   
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Image Size','acumec'),
            'description'   => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).','acumec'),
            'param_name'    => 'image_size',
            'value'         => "",
            'group'         => esc_html__('Testimonial Setting', 'acumec')
        ),

        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show image Navigation','acumec'),
            'param_name'    => 'show_image',
            'value' => array(
                'Yes' => true
            ),
            'std' => true,
            'group'         => esc_html__('Testimonial Setting', 'acumec')
        ),         
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Overlay Background Color pagination.", 'acumec'),
            "param_name" => "bg_color_pagination",
            'group'         => esc_html__('Testimonial Setting', 'acumec')
        ),
        /* Carousel Settings */
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('XSmall Devices','acumec'),
            'param_name'        => 'xsmall_items',
            'edit_field_class'  => 'vc_col-sm-3 vc_carousel_item',
            'value'             => array(1,2,3,4,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Small Devices','acumec'),
            'param_name'        => 'small_items',
            'edit_field_class'  => 'vc_col-sm-3 vc_carousel_item',
            'value'             => array(1,2,3,4,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Medium Devices','acumec'),
            'param_name'        => 'medium_items',
            'edit_field_class'  => 'vc_col-sm-3 vc_carousel_item',
            'value'             => array(1,2,3,4,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Large Devices','acumec'),
            'param_name'        => 'large_items',
            'edit_field_class'  => 'vc_col-sm-3 vc_carousel_item',
            'value'             => array(1,2,3,4,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Margin Items','acumec'),
            'param_name'    => 'margin',
            'value'         => '30',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Loop Items','acumec'),
            'param_name'    => 'loop',
            'std'           => 'false',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Mouse Drag','acumec'),
            'param_name'    => 'mousedrag',
            'std'           => 'true',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Pause On Hover','acumec'),
            'param_name'    => 'autoplayhoverpause',
            'std'           => 'true',
            'dependency'    => array(
                'element'   =>'autoplay',
                'value'     => 'true'
                ),
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Next/Preview','acumec'),
            'param_name'    => 'nav',
            'std'           => 'true',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Dots','acumec'),
            'param_name'    => 'dots',
            'std'           => 'false',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Auto Play','acumec'),
            'param_name'    => 'autoplay',
            'std'           => 'true',
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Auto Play TimeOut','acumec'),
            'param_name'    => 'autoplaytimeout',
            'value'         => '2000',
            'dependency'    => array(
                'element'   => 'autoplay',
                'value'     => 'true'
            ),
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Smart Speed','acumec'),
            'param_name'    => 'smartspeed',
            'value'         => '1000',
            'description'   => esc_html__('Speed scroll of each item','acumec'),
            'group'         => esc_html__('Carousel Settings', 'acumec')
        ),  
    )
));

global $cms_carousel;
$cms_carousel = array();
class WPBakeryShortCode_cms_testimonial_carousel extends CmsShortCode
{
    protected function content($atts, $content = null){
        $atts_extra = shortcode_atts(array(
            'xsmall_items'          => 1,
            'small_items'           => 1,
            'medium_items'          => 1,
            'large_items'           => 1,
            'margin'                => 30,
            'loop'                  => 'false',
            'mousedrag'             => 'true',
            'nav'                   => 'true',
            'dots'                  => 'false',
            'autoplay'              => 'true',
            'autoplaytimeout'       => '2000',
            'smartspeed'            => '1000',
            'autoplayhoverpause'    => 'true',
        ), $atts);
        $atts = array_merge($atts_extra,$atts);
        global $cms_carousel;
        
        wp_enqueue_style('owl-carousel',get_template_directory_uri().'/assets/css/owl.carousel.min.css','','2.2.1','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri().'/assets/js/owl.carousel.min.js',array('jquery'),'2.2.1',true);
        wp_enqueue_script('owl-carousel-cms',get_template_directory_uri().'/assets/js/owl.carousel.cms.js',array('jquery'),'1.0.0',true);
        $html_id = cmsHtmlID('cms-testimonial-carousel');
        
        $atts['autoplaytimeout'] = isset($atts['autoplaytimeout']) ? (int)$atts['autoplaytimeout'] : 2000;
        $atts['smartspeed']      = isset($atts['smartspeed']) ? (int)$atts['smartspeed'] : 1000; 
        $overlay = !empty($atts['bg_color']) ? 'background: '.$atts['bg_color'].';' : '';
        $cms_carousel[$html_id]     = array(
            'margin'                => $atts['margin'],
            'loop'                  => $atts['loop'],
            'mouseDrag'             => $atts['mousedrag'],
            'nav'                   => $atts['nav'],
            'dots'                  => $atts['dots'],
            'autoplay'              => $atts['autoplay'],
            'autoplayTimeout'       => $atts['autoplaytimeout'],
            'smartSpeed'            => $atts['smartspeed'],
            'autoplayHoverPause'    => $atts['autoplayhoverpause'],
            'navText'               => array('<div class="bg-icon left" style="'.esc_attr($overlay).'"></div><div class="bg-overlay" style="'.esc_attr($overlay).'"></div>','<div class="bg-icon right" style="'.esc_attr($overlay).'"></div><div class="bg-overlay" style="'.esc_attr($overlay).'"></div>'),
            'responsive'    => array(
                0       => array(
                    'items' => (int)$atts['xsmall_items'],
                ),
                768     => array(
                    'items' => (int)$atts['small_items'],
                ),
                992     => array(
                    'items' => (int)$atts['medium_items'],
                ),
                1200    => array(
                    'items' => (int)$atts['large_items'],
                )
            )
        );
        wp_localize_script('owl-carousel-cms', 'cmscarousel', $cms_carousel);
        $atts['html_id'] = $html_id;
        return parent::content($atts, $content);
    }
}
?>