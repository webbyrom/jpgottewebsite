<?php
    $classes=array('fancyboxe-single');
    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
 
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
    
    $icon_name = $iconClass = $a_href = $a_title = $a_target = $image_url = $link = $box = '';
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name]) ? $atts[$icon_name] : '';
    
    $link = (isset($atts['link'])) ? $atts['link'] : '';
    $link = vc_build_link( $link );
    $use_link = false;
    if ( strlen( $link['url'] ) > 0 ) {
        $use_link = true;
        $a_href = $link['url'];
        $a_title = !empty($link['title'])?$link['title']: esc_html__('Read More','acumec');
        $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
    }
     
    if (!empty($atts['image'])) {
        $attachment_image = wp_get_attachment_image_src($atts['image'], 'thumbnail');
        $image_url = $attachment_image[0];
    }
  
    $fancy_style = ( !empty($atts['fancy_style']) ) ? $atts['fancy_style'] : 'style-0';
    $animate_class = !empty($atts['animation_effect']) ? $atts['animation_effect'] : ''; 
    $duration = !empty($atts['data_wow_duration'])?'data-wow-duration='.$atts['data_wow_duration']:'';
    $delay  = !empty($atts['data_wow_delay'])?'data-wow-delay='.$atts['data_wow_delay']:'';
    $bgcolor = ''; 
    $border1 = '';
    if (!empty($atts['border']) && $atts['border']) {
        $border1 = 'border';
    } 
        if (!empty($atts['bg_color1'])) { 
            $bgcolor = 'background: '.$atts['bg_color1'].';';
        } 
        $icon_size = !empty($atts['icon_size']) ? 'font-size: ' .$atts['icon_size']. ';' : '';
        $title_size = !empty($atts['title_size']) ? 'font-size: ' .$atts['title_size']. '; ' : '';
        $title_height = !empty($atts['title_height']) ? 'line-height: ' .$atts['title_height']. ';' : '';
        $content_size = !empty($atts['content_size']) ? 'font-size: ' .$atts['content_size']. '; ' : '';
        $content_style = !empty($atts['content_style']) ? 'font-style: ' .$atts['content_style']. '; ' : '';
        $content_height = !empty($atts['content_height']) ? 'font-style: ' .$atts['content_height']. '; ' : '';
    ?>
    <div <?php echo esc_attr($duration); ?> <?php echo esc_attr($delay); ?> class="cms-fancy-single-wraper <?php echo esc_attr($css_class);?> <?php echo esc_attr($atts['template']);?> <?php echo esc_attr($animate_class);?> clearfix" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php
    switch ($fancy_style) {
        case 'style-2': 
        $icon = $text_c = $icon_color = "";
        if(!empty($atts['bg_icon']) && $atts['bg_icon']) {
            $icon = 'background: '.$atts['bg_icon'].';';
        } 
        if(!empty($atts['color_icon']) && $atts['color_icon']) {
            $icon_color = 'color: '.$atts['color_icon'].';';
        } 
        if(!empty($atts['text_color']) && $atts['text_color']) {
            $text_c = 'color: '.$atts['text_color'].'; ';
        } 
        ?>
            <div class="cms-fancybox-item fancy-style2 <?php echo esc_attr($border1);?>">
                <?php if (!empty($iconClass)) : ?>
                    <div class="fancy-icon">
                        <div class="fancy-media fancy-icon-wrap" style=" <?php echo esc_attr($bgcolor); ?> <?php echo esc_attr($icon); ?> ">
                            <i class="<?php echo esc_attr($iconClass);?>" style=" <?php echo esc_attr($icon_color); ?> <?php echo esc_attr($icon_size); ?> "></i>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if (!empty($image_url)): ?>
                        <div class="fancy-media fancy-image" >
                            <div class="fancy-thumbnail-wrap">
                                <img src="<?php echo esc_url($image_url);?>"/>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endif; ?>
                <div class="item-content ">
                    <div class="fancy-content" style="<?php echo esc_attr($text_c); ?>" >
                        <?php 
                        if(!empty($atts['title_item'])){ 
                            if (!$use_link) {
                                echo '<h4 class="fancy-title" style = "'.$text_c.$title_size.$title_height.'">';
                                    echo esc_html($atts['title_item']);
                                echo '</h4>';
                            }else {
                               echo '<h4 class="fancy-title" style="'.$text_c.$title_size.$title_height.'"><a href="'.$a_href.'" title="'.$a_title.'">';
                                    echo esc_html($atts['title_item']);
                                echo '</a></h4>'; 
                            }    
                        } 
                        ?>
                        <?php
                        if(!empty($content)):?>
                            <div class="content" style="<?php echo esc_attr($content_size); ?> <?php echo esc_attr($content_style); ?> <?php echo esc_attr($content_height); ?>">
                                <?php echo wpb_js_remove_wpautop( $content, true );?>            
                            </div>  
                        <?php endif; ?>       
                    </div>
                </div>
            </div> 
        <?php
        break;
        }
        ?>
    </div>