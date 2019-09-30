<?php 
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    $classes=array('cms-custom-heading');

    $icon_name = $iconClass = "";
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name]) ? $atts[$icon_name] : '';

    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );

    $border = $color = $color1 = $color2 = $icon_ = $imagew = '';

    if(!empty($title_color)){
        $color = 'color: '.$title_color.';';
    } 
    if(!empty($main_title_color)){
        $color2 = 'color: '.$main_title_color.';';
    } 
    if(!empty($icon_color)){
        $icon_ = 'color: '.$icon_color.';';
    }
    if (!empty($image_width)) {
        $imagew = 'width: '.$image_width.';';
    }
    
?>
    <div class="<?php echo esc_attr($css_class);?> <?php echo esc_attr($heading_style); ?> <?php if(!empty($atts['ttext-align'])) echo esc_html($atts['ttext-align']); ?>">

        
        <?php 
        if ($heading_style != 'style3') {
            if ($heading_style == 'style2' ) {?>            
                <div class="title-heading" style = "<?php echo esc_attr( $color); ?>
                    <?php if(!empty($atts['ffamily'])):?> 
                        font-family:<?php echo esc_attr($atts['ffamily']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fsize'])):?> 
                        font-size:<?php echo esc_attr($atts['fsize']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fweight'])): ?> 
                        font-weight:<?php echo esc_attr($atts['fweight']); ?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['lheight'])): ?> 
                        line-height:<?php echo esc_attr($atts['lheight']); ?>; 
                    <?php endif; ?>
                    <?php if(!empty($atts['spacing'])): ?> 
                        letter-spacing:<?php echo esc_attr($atts['spacing']); ?>; 
                    <?php endif; ?>
                " >
                <?php if (!empty($atts['media_type']) && $atts['media_type'] == 'icon'): ?>
                    <?php if (!empty($iconClass)) : ?>
                        <span class="<?php echo esc_attr($iconClass);?>" style=" <?php echo esc_attr($icon_); ?>"></span>
                    <?php endif; ?>
                <?php else: 
                    if (!empty($atts['image_type'])) :
                        $attachment_image = wp_get_attachment_image_src($atts['image_type'], 'thumbnail');
                        $image_url = $attachment_image[0];
                    ?>
                    <img src="<?php echo esc_url($image_url); ?>" style=" <?php echo esc_attr($imagew); ?> ">
                <?php endif;endif; ?>
                    <?php echo esc_html($atts['title']); ?>
                </div>
        <?php } else {

            if(!empty($atts['title'])){ ?>
                <div class="title-heading" style = "<?php echo esc_attr($color)?>">
                    <?php echo esc_html($atts['title']);?>
                </div>
            <?php } 
            }
        }else {?>

            <div class="title-heading" style = "<?php echo esc_attr($color)?>
                    <?php if(!empty($atts['ffamily'])):?> 
                        font-family:<?php echo esc_attr($atts['ffamily']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fsize'])):?> 
                        font-size:<?php echo esc_attr($atts['fsize']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fweight'])): ?> 
                        font-weight:<?php echo esc_attr($atts['fweight']); ?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['lheight'])): ?> 
                        line-height:<?php echo esc_attr($atts['lheight']); ?>; 
                    <?php endif; ?>
                    <?php if(!empty($atts['spacing'])): ?> 
                        letter-spacing:<?php echo esc_attr($atts['spacing']); ?>; 
                    <?php endif; ?> ">
                <?php if (!empty($atts['image_type']) || !empty($atts['icon_type']) ): ?>
                    <div class="media">
                        <?php if (!empty($atts['media_type']) && $atts['media_type'] == 'icon'): ?>
                            <?php if (!empty($iconClass)) : ?>
                                <i class="<?php echo esc_attr($iconClass);?>" style=" <?php echo esc_attr($icon_); ?>"></i>
                            <?php endif; ?>
                        <?php else: 
                            if (!empty($atts['image_type'])) :
                                $attachment_image = wp_get_attachment_image_src($atts['image_type'], 'thumbnail');
                                $image_url = $attachment_image[0];
                            ?>
                            <img src="<?php echo esc_url($image_url); ?>" style=" <?php echo esc_attr($imagew); ?> ">
                        <?php endif;endif; ?>
                    </div>
                <?php endif ?>
                
                <div class="title-heading-wrap">
                    <?php if ((!empty($atts['title1']))): ?>
                        <span class="title-before"><?php echo esc_html($atts['title1']); ?></span>
                    <?php endif; ?>
                    <?php if ((!empty($atts['title']))): ?>
                        <span class="title" style = "<?php echo esc_attr($color2)?> 
                        <?php if(!empty($atts['fweightm'])): ?> 
                        font-weight:<?php echo esc_attr($atts['fweightm']); ?>;
                    <?php endif; ?>"><?php echo esc_html($atts['title']); ?></span>
                    <?php endif; ?>
                    <?php if ((!empty($atts['title2']))): ?>
                        <span class="title-after"><?php echo esc_html($atts['title2']); ?></span>
                    <?php endif; ?>
                </div>
                    
            </div>
        <?php } ?>     
    </div>
     
 
 
 
             
 
