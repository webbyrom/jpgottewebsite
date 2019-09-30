<?php
vc_map(array(
    "name" => 'Cms Blog Carousel',
    "base" => "cms_blog_carousel",
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
            'heading'       => esc_html__('Show Date','acumec'),
            'param_name'    => 'blog_date',
            'value' => array(
                'Yes' => true
            ),
            'std' => true,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Categories','acumec'),
            'param_name'    => 'blog_categories',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Tag','acumec'),
            'param_name'    => 'blog_tag',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Author','acumec'),
            'param_name'    => 'blog_author',
            'value' => array(
                'Yes' => true
            ),
            'std' => true,
            'group'         => esc_html__('Blog Setting', 'acumec')
        ), 
        array(
            'type'          => 'checkbox',
            'heading'       => esc_html__('Show Comment Count','acumec'),
            'param_name'    => 'blog_comment',
            'value' => array(
                'Yes' => true
            ),
            'std' => true,
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
                
    )
));

global $cms_carousel;
$cms_carousel = array();
  
class WPBakeryShortCode_cms_blog_carousel extends CmsShortCode
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
        
        $atts['autoplaytimeout'] = isset($atts['autoplaytimeout'])?(int)$atts['autoplaytimeout']:5000;
        $atts['smartspeed'] = isset($atts['smartspeed'])?(int)$atts['smartspeed']:1000;
        $cms_carousel[$html_id] = array(
            'margin' => $atts['margin'],
            'loop' => $atts['loop'],
            'mouseDrag' => 'true',
            'nav' => $atts['nav'],
            'dots' => $atts['dots'],
            'autoplay' => $atts['autoplay'],
            'autoplayTimeout' => $atts['autoplaytimeout'],
            'smartSpeed' => $atts['smartspeed'],
            'autoplayHoverPause' => 'true',
            'dotscontainer' => $html_id.' .cms-dots',
            'center'    => (int)$atts['center'],
            'responsive' => array(
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