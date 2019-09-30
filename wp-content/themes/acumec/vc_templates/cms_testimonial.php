<?php 
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    $classes = array('cms-testimonial',vc_shortcode_custom_css_class( $css ));

    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
    $class_bg ='';
    $color = !empty($atts['tcolor']) ? 'color: '.$atts['tcolor'].';' : '';
    $align = !empty($atts['alignment']) ? $atts['alignment'] : '';
    $bg = '';
    if (!empty($atts['bg_testimonial']) ) {
        if($atts['bg_testimonial'] == 'image' && !empty($atts['bgimage'])) {
            $attachment_image = wp_get_attachment_image_src($atts['bgimage'], 'full');
            $image_url = $attachment_image[0];
            $bg = !empty($atts['bgimage']) ? 'background-image: url(' . $image_url . ');' : '';
            $class_bg = 'has-bg';
        }
        if($atts['bg_testimonial'] == 'color' && $atts['bgcolor']) {

            $bg = !empty($atts['bgcolor']) ? 'background-color: ' . $atts['bgcolor'] . ';' : '';
            $class_bg = 'has-bg';
        }
        
    }
    $radius = !empty($atts['testi_borderradius']) ? 'border-radius: '.$atts['testi_borderradius'] . ';' : ''; 
?>

<div class="<?php echo esc_attr($css_class);?> <?php echo esc_attr($class_bg); ?> <?php echo esc_attr($align); ?>" style= "<?php echo esc_attr($bg); ?> <?php echo esc_attr($radius); ?>">
    <?php if (!empty($atts['testi_des'])): ?>
       <div class="testi-description">
            <blockquote>
                <p style="<?php echo esc_attr($color); ?>"><?php echo esc_attr($atts['testi_des']); ?></p>
            </blockquote>
        </div> 
    <?php endif; ?>       
    <div class="testimonial" >
        <div class="testi-wrap">
        <?php if (!empty($atts['testi_avatar'])): ?>
            <div class="testi-avatar">
                <?php echo acumec_get_image_croped( $atts['testi_avatar'],'thumbnail'); ?>
            </div>
        <?php endif; ?>
            <div class="testi-content">
                <?php if (!empty($atts['testi_name'])): ?>
                    <h4 class="testi-name" style="<?php echo esc_attr($color); ?>"><?php echo esc_attr($atts['testi_name']); ?></h4>
                <?php endif; ?>
                <?php if (!empty($atts['testi_position'])): ?>
                    <div class="testi-position">
                        <?php echo esc_attr($atts['testi_position']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($atts['testi_signature'])): ?>
            <div class="testi-sign">
                <?php echo acumec_get_image_croped( $atts['testi_signature'],'full'); ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>
 
 
             
 
