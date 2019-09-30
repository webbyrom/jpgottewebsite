<?php 
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    $classes=array('cms-pricing-table',vc_shortcode_custom_css_class( $css ));
    $link = (isset($atts['link'])) ? $atts['link'] : '';
    $link = vc_build_link( $link );
    $use_link = false;
    if ( strlen( $link['url'] ) > 0 ) {
        $use_link = true;
        $a_href = $link['url'];
        $a_title = !empty($link['title']) ? $link['title'] : 'Get Started' ;
        $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
    }
    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
    $features = (array) vc_param_group_parse_atts($features );  
    $bg = $cl = $tcolor = '';
    if (!empty($atts['bg_button'])) {
        $bg = 'background: '.esc_attr($atts['bg_button']).';';
    }
    if (!empty($atts['color_button'])) {
        $cl = 'color: '.esc_attr($atts['color_button']).';';
    }
    if (!empty($atts['tcolor'])) {
        $tcolor = 'color: '.esc_attr($atts['tcolor']).';';
    }
    $t_align = !empty($atts['align']) ? $atts['align'] : "";
    $overlap = "";
    if(!empty($atts['overlap']) && $atts['overlap'] == 1) {
      $overlap = 'overlap';
    }
?>
<?php if(!empty($title)): ?>
<div class="<?php echo esc_attr($css_class);?> <?php echo esc_attr($pricing_style); ?> <?php echo esc_attr($overlap); ?> <?php echo ( isset($is_active) && $is_active =='1' ) ? 'active' : 'default';?> <?php echo esc_attr($t_align); ?>">
    <?php switch ($pricing_style) {
        case 'style1':
            if(!empty($title)): ?>
                <div class="title">
                    <h3 style=" <?php echo esc_attr($tcolor); ?>"><?php echo esc_html($title ); ?></h3> 
                </div>
            <?php endif; ?> 
            <?php if(!empty($sub_title)): ?>   
                <p class="sub_title"><?php echo esc_html($sub_title); ?></p>
            <?php endif; ?>
            <?php if(!empty($pricing)): ?> 
                <?php if (!empty($prefix_pricing)): ?>
                   <div class="pricing"><span class="prefix_pricing"><?php echo esc_attr( $prefix_pricing); ?></span><?php echo esc_html($pricing); ?>
                <?php else: ?>
                    <div class="pricing"><?php echo esc_html($pricing); ?>
               <?php endif ?>  
                </div>
                <?php if (!empty($sub_pricing)): ?>
                    <div class="sub_pricing"><?php echo esc_html($sub_pricing); ?></div>
                <?php endif; ?>
             <?php endif; ?>
             <?php if ( $image > 0 ) :
                $thumbnail = wp_get_attachment_image_src($image, 'full');
                $image_src = $thumbnail[0];
             ?> 
             <div class="pricing-thumbnail">
                <img src="<?php echo esc_url($image_src);?>" />
             </div>  
             <?php endif; ?>
             <?php  if( !empty($features)): ?>
                <ul class="features-list list-unstyled">
                    <?php foreach($features as $feature): 
                        if(!empty($feature['feature_name'] )): ?>
                        <li><i class="fa fa-check" style=" <?php echo esc_attr($tcolor); ?>"></i><?php echo esc_attr($feature['feature_name'] ); ?></li>
                    <?php endif;
                         endforeach; ?>
                </ul>
                <?php if (isset($a_title)): ?>
                  <a href="<?php echo esc_url($a_href);?>" class="btn btn-theme-primary btn-pricing" style="<?php echo esc_attr($bg); ?> <?php echo esc_attr($cl); ?>"><?php echo !empty($a_title) ? esc_html($a_title): esc_html__('Purchase Plan','acumec');?></a>
                <?php endif ?>
            <?php endif; 
        break;
        case 'style2':
          if (!empty($option_type) && $option_type == 'option_image') {
            if ( $image2 > 0 ) :
                $thumbnail = wp_get_attachment_image_src($image2, 'full');
                $image_src = $thumbnail[0];
             ?> 
            <div class="pricing-thumbnail">
                <img src="<?php echo esc_url($image_src);?>" />
            </div>  
            <?php endif; 
          }
          else {
            $icon_name = $iconClass = "";
            $icon_name = "icon_" . $atts['icon_type'];
            $iconClass = isset($atts[$icon_name]) ? $atts[$icon_name] : '';?>
              <div class="pricing-icon" style=" <?php echo esc_attr($tcolor); ?>">
                <span class="<?php echo esc_attr($iconClass); ?>"></span>
              </div> 
            <?php

          }
            
            if(!empty($title)): ?>
                <div class="title">
                    <h3 style=" <?php echo esc_attr($tcolor); ?>"><?php echo esc_html($title ); ?></h3> 
                </div>
            <?php endif; ?> 
            <?php if(!empty($sub_title)): ?>   
                <p class="sub_title" style=" <?php echo esc_attr($tcolor); ?>"><?php echo esc_html($sub_title); ?></p>
            <?php endif; ?>
            <?php if(!empty($pricing)): ?> 
                <?php if (!empty($prefix_pricing)): ?>
                   <div class="pricing" style=" <?php echo esc_attr($tcolor); ?>"><span class="prefix_pricing"><?php echo esc_attr( $prefix_pricing); ?></span><?php echo esc_html($pricing); ?>
                <?php else: ?>
                    <div class="pricing" style=" <?php echo esc_attr($tcolor); ?>"><?php echo esc_html($pricing); ?>
               <?php endif ?> 
                <?php if (!empty($sub_pricing)): ?>
                    <span class="sub_pricing" style=" <?php echo esc_attr($tcolor); ?>"><?php echo esc_html($sub_pricing); ?></span>
                <?php endif; ?> 
                </div>
                
            <?php endif; ?>
             <?php if (!empty($atts['description'])): ?>
                 <div class="description" style=" <?php echo esc_attr($tcolor); ?>">
                     <?php echo esc_html($description); ?>
                 </div>
             <?php endif ?>
             <?php  if( !empty($features)): ?>
                <ul class="features-list list-unstyled">
                    <?php foreach($features as $feature): 
                        if(!empty($feature['feature_name'] )): ?>
                        <li style=" <?php echo esc_attr($tcolor); ?>"><i class="fa fa-check" style=" <?php echo esc_attr($tcolor); ?>"></i><?php echo esc_attr($feature['feature_name'] ); ?></li>
                    <?php endif;
                         endforeach; ?>
                </ul>
                <?php if (isset($a_title)): ?>
                  <a href="<?php echo esc_url($a_href);?>" class="btn btn-theme-primary btn-pricing" style="<?php echo esc_attr($bg); ?> <?php echo esc_attr($cl); ?>"><?php echo !empty($a_title) ? esc_html($a_title): esc_html__('Purchase Plan','acumec');?></a>
                <?php endif ?>
            <?php endif; 
            break;
        default:
            # code...
            break;
    } ?>
    
</div>
<?php endif; ?>
 
 
             
 
