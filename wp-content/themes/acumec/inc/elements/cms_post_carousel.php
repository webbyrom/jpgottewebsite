<?php
vc_map(array(
    "name" => 'Cms Post Carousel',
    "base" => "cms_post_carousel",
    "icon" => "cs_icon_for_vc",
    "category" =>  esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    "description" =>  '',
    "params" => array(
        array(
            "type" => "loop",
            "heading" => esc_html__("Source",'acumec'),
            "param_name" => "source",
            'settings' => array(
                'size' => array('hidden' => false, 'value' => 10),
                'order_by' => array('value' => 'date')
            ),
            "group" => esc_html__("Source Settings", 'acumec'),
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Subtitle','acumec'),
            'param_name'    => 'subtitle',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Description','acumec'),
            'param_name'    => 'blog_description',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__('Word Number Excerpt','acumec'),
            'param_name'    => 'word_number',
            'value'        => '20',
            'dependency' => array(
                'element' => 'blog_description',
                'value' => array(
                    '1'
                ),
            ),
            'group'         => esc_html__('Blog Setting', 'acumec'),
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Text Color", 'acumec'),
            "param_name" => "t_color",
            'group'         => esc_html__('Blog Setting', 'acumec')
        ),  
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Background Color", 'acumec'),
            "param_name" => "bg_color",
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 

        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show image Navigation','acumec'),
            'param_name'    => 'show_image',
            'value' => array(
                'Yes' => true
            ),
            'std' => true,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ),         
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Overlay Background Color Navigation.", 'acumec'),
            "param_name" => "bg_color_pagination",
            'group'         => esc_html__('Blog Setting', 'acumec')
        ),       
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Animation', 'acumec' ),
            'param_name' => 'animation_effect',
            'std' => '',
            'description' => esc_html__( 'Animations  for grid', 'acumec' ),
            'value' =>  array(
                esc_html__( 'None', 'acumec' ) => '',
                esc_html__( 'fadeIn', 'acumec' ) => 'wow fadeIn',
                esc_html__( 'FadeInUp', 'acumec' ) => 'wow fadeInUp',
                esc_html__( 'BounceInUp', 'acumec' ) => 'wow bounceInUp',
                esc_html__( 'BounceInDown', 'acumec' ) => 'wow bounceInDown',
                esc_html__( 'BounceInLeft', 'acumec' ) => 'wow bounceInLeft',
                esc_html__( 'BounceInRight', 'acumec' ) => 'wow bounceInRight',  
             ),
             "group" => esc_html__("Animation", 'acumec'),
             'weight' => 1
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Data duration", 'acumec'),
            "param_name" => "data_wow_duration",
            "value" =>  array(
                'None'  => '',
                '1s'    => '1s',
                '2s'    => '2s',
                '3s'    => '3s',
                '4s'    => '4s',
                '5s'    => '5s',
                '6s'    => '6s',
            ),
            "group" => esc_html__("Animation", 'acumec'),
            'weight' => 1
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Data delay", 'acumec'),
            "param_name" => "data_wow_delay",
            "value" =>  array(
                'None'  => '',
                '0.2s'    => '0.2s',
                '0.4s'    => '0.4s',
                '0.6s'    => '0.6s',
                '0.8s'    => '0.8s',
            ),
            "group" => esc_html__("Animation", 'acumec'),
            'weight' => 1
        ),
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Read More','acumec'),
            'param_name'    => 'readmore',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'group'         => esc_html__('Blog Setting', 'acumec')
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
  
class WPBakeryShortCode_cms_post_carousel extends CmsShortCode
{
    protected function content($atts, $content = null){
        global $wp_query,$post;
        $atts_extra = shortcode_atts(array(
            'source' => '',            
            'col_xs' => 1,
            'col_sm' => 2,
            'col_md' => 3,
            'col_lg' => 4,
            'not__in'=> 'false', 
            'xsmall_items'          => 1,
            'small_items'           => 1,
            'medium_items'          => 1,
            'large_items'           => 1,
            'margin'                => 30,
            'loop'                  => 'false',
            'nav'                   => 'true',
            'dots'                  => 'false',
            'autoplay'              => 'true',
            'center'                => 'false',
        ), $atts);
           
        $atts = array_merge($atts_extra, $atts);
        global $cms_carousel;
        
        wp_enqueue_style('owl-carousel',get_template_directory_uri().'/assets/css/owl.carousel.min.css','','2.2.1','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri().'/assets/js/owl.carousel.min.js',array('jquery'),'2.2.1',true); 
        wp_enqueue_script('owl-carousel-cms',get_template_directory_uri().'/assets/js/owl.carousel.cms.js',array('jquery'),'1.0.0',true);
         
        $source = $atts['source'];
         
        if(isset($atts['not__in']) && $atts['not__in']){  
            list($args, $wp_query) = vc_build_loop_query($source, get_the_ID());
        }else{ 
            list($args, $wp_query) = vc_build_loop_query($source);
        }
        
        if( (strpos($atts['source'],'tax_query') == false) && (strpos($atts['source'],'post_type:post') == true )){
            $terms = get_terms('category');
            $tx_category = array(); 
            foreach ($terms as $txcat){
                if ($txcat->parent != 0) {
                    $tx_category[] = $txcat->term_id;
                }
            }
            
           $sources = explode('|',$atts['source']);
           $str_size = $sources[0];
           $sizes = explode(':',$sources[0]);
           if( ((int)$sizes[1]) > 0) $size = $sizes['1'];
           else $size = -1;
           
            $args = array(
                'posts_per_page' => $size,
                'post_type' => 'post',
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => $tx_category,
                        'operator' => 'NOT IN'
                    ),
                )
            );
            
            $wp_query = new WP_Query( $args );
             
        } 
          
        $args['cat_tmp'] = isset($args['cat'])?$args['cat']:'';
        // if select term on custom post type, move term item to cat.
        if(strstr($source, 'tax_query')){
            $source_a = explode('|', $source);
            foreach ($source_a as $key => $value) {
                $tmp = explode(':', $value);
                if($tmp[0] == 'tax_query'){
                    $args['cat_tmp'] = $tmp[1];
                }
            }
        }
        $atts['cat'] = isset($args['cat_tmp'])?$args['cat_tmp']:''; 
        /* get posts */
        $atts['posts'] = $wp_query;
        
        $html_id = cmsHtmlID('cms-blog-carousel');
        $overlay = !empty($atts['bg_color_pagination']) ? 'background: '.$atts['bg_color_pagination'].';' : '';
        $atts['autoplaytimeout'] = isset($atts['autoplaytimeout'])?(int)$atts['autoplaytimeout']:5000;
        $atts['smartspeed'] = isset($atts['smartspeed'])?(int)$atts['smartspeed']:1000;
        $cms_carousel[$html_id] = array(
            'margin'                => $atts['margin'],
            'loop'                  => $atts['loop'],
            'mouseDrag'             => 'true',
            'nav'                   => $atts['nav'],
            'dots'                  => $atts['dots'],
            'autoplay'              => $atts['autoplay'],
            'autoplayTimeout'       => $atts['autoplaytimeout'],
            'smartSpeed'            => $atts['smartspeed'],
            'autoplayHoverPause'    => 'true',
            'dotscontainer'         => $html_id.' .cms-dots',
            'center'                => (int)$atts['center'],
			'animateIn'             => 'flipInX',
            'animateOut'            => 'flipInY',
            'navText'               => array('<div class="bg-icon left" style="'.esc_attr($overlay).'"></div><div class="prev-post">Previous Post</div><div class="bg-overlay" style="'.esc_attr($overlay).'"></div>','<div class="bg-icon right" style="'.esc_attr($overlay).'"></div><div class="next-post">Next Post</div><div class="bg-overlay" style="'.esc_attr($overlay).'"></div>'),
            'responsive'            => array(
                0 => array(
                "items" => '1',
                ),
                768 => array(
                    "items" => '1',
                    ),
                992 => array(
                    "items" => '1',
                    ),
                1200 => array(
                    "items" => '1',
                    )
                )
        );
        wp_localize_script('owl-carousel-cms', "cmscarousel", $cms_carousel);
         
        $atts['html_id'] = $html_id;
        return parent::content($atts, $content);
    }
     
}
 


?>