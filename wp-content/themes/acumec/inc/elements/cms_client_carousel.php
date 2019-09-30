<?php
vc_map(array(
    'name' => 'CMS Client Carousel',
    'base' => 'cms_client_carousel',
    'icon' => 'cs_icon_for_vc',
    'category' => esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    'description' => esc_html__('Add clients', 'acumec'),
    'params' => array(
        array(
            'type'          => 'param_group',
            'heading'       => esc_html__( 'Add your Client', 'acumec' ),
            'param_name'    => 'values',
            'params' => array(
                array(
                    'type'          => 'attach_image',
                    'heading'       => esc_html__( 'Author Image', 'acumec' ),
                    'param_name'    => 'client_image',
                    'value'         => ''
                ),
                array(
                    "type" => "vc_link",
                    "heading" => esc_html__("URL (Link)",'acumec'),
                    "param_name" => "link",
                    "value" => "",
                ),
            ),
            'group' => esc_html__('Client Item','acumec')
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
            'value'             => array(1,2,3,4,5,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec')
        ),
        array(
            'type'              => 'dropdown',
            'heading'           => esc_html__('Large Devices','acumec'),
            'param_name'        => 'large_items',
            'edit_field_class'  => 'vc_col-sm-3 vc_carousel_item',
            'value'             => array(1,2,3,4,5,6),
            'std'               => 1,
            'group'             => esc_html__('Carousel Settings', 'acumec'),
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
class WPBakeryShortCode_cms_client_carousel extends CmsShortCode
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
            'autoheight'            => 'true',
        ), $atts);
        $atts = array_merge($atts_extra,$atts);
        global $cms_carousel;
        
        wp_enqueue_style('owl-carousel',get_template_directory_uri().'/assets/css/owl.carousel.min.css','','2.2.1','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri().'/assets/js/owl.carousel.min.js',array('jquery'),'2.2.1',true);
        wp_enqueue_script('owl-carousel-cms',get_template_directory_uri().'/assets/js/owl.carousel.cms.js',array('jquery'),'1.0.0',true);
        $html_id = cmsHtmlID('cms-client-carousel');
        
        $atts['autoplaytimeout'] = isset($atts['autoplaytimeout']) ? (int)$atts['autoplaytimeout'] : 2000;
        $atts['smartspeed']      = isset($atts['smartspeed']) ? (int)$atts['smartspeed'] : 1000; 
 
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
            'navText'               => array('<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'),
            'autoHeight'            => $atts['autoheight'],
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