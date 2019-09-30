<?php 
    $classes=array('progressbar');
    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
 
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name])?$atts[$icon_name]:'';
    $layout = '';
    if (empty($atts['layout_style'])) {
        $layout = 'layout_style1';
    }
    else {
        $layout = $atts['layout_style'];
    }
    $title_color = '';
    if (!empty($atts['title_color'])) {
        $title_color = 'color:'.$atts['title_color'].';';
    }
?>
<div class="cms-progress-wraper <?php echo esc_attr($layout); ?> <?php echo esc_attr($css_class);?> <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <div class="cms-progress-body">
        <?php
            $bg_color_bar = "";
            $item_class = 'cms-progress-item-wrap';
            $item_title     = $atts['item_title'];
            $show_value     = ($atts['show_value']=='true')?true:false;
            $value          = $atts['value'];
            $value_suffix   = $atts['value_suffix'];
            if (!empty($atts['bg_color_bar'])) {
                $bg_color_bar = 'background-color:'.$atts['bg_color_bar'].';';
            }
            $color          = 'background-color: '.$atts['color'].';';
            $height         = 'height: '.$atts['height'].';';
            $border_radius  = !empty($atts['border_radius']) ? 'border-radius: '.$atts['border_radius'].';' : '';
            $vertical       = ($atts['mode']=='vertical')?true:false;
            $striped        = ($atts['striped']=='yes')?true:false;
            ?>
            <div class="<?php echo esc_attr($item_class);?>">
                <?php if($iconClass):?>
                    <i class="<?php echo esc_attr($iconClass);?>"></i>
                <?php endif;?>
                <?php if($item_title):?>
                <div class="cms-progress-header clearfix" style="<?php echo esc_attr($title_color); ?>"> 
                    <?php switch($layout){ 
                        case 'layout_style1':?>
                        <h5 class="cms-progress-title">
                            <?php echo apply_filters('the_title',$item_title);?>
                        </h5>
                        <?php if($show_value): ?>
                            <div class="cms-progress-value">
                                <?php echo esc_attr($value.$value_suffix);?>
                            </div> 
                        <?php endif; break;  ?>
                    <?php case 'layout_style2': ?>
                        <h6 class="cms-progress-title">
                            <?php echo apply_filters('the_title',$item_title);?>
                        </h6>
                        <?php if($show_value): ?>
                            <span class="cms-progress-value"> - (<?php echo esc_attr($value.$value_suffix);?>)</span> 
                        <?php endif; 
                    break;}?>
                </div>  
                <?php endif;?>
                <div class="cms-progress progress <?php if($striped){echo ' progress-striped';}?>" 
                    style="<?php echo esc_attr($bg_color_bar);?> <?php echo esc_attr($height);?> <?php echo esc_attr($border_radius);?>" >
                    <div id="item-<?php echo esc_attr($atts['html_id']); ?>" 
                        class="progress-bar" role="progressbar" 
                        data-valuetransitiongoal="<?php echo esc_attr($value); ?>" 
                        style="<?php echo esc_attr($color);?> <?php echo esc_attr($border_radius);?>"
                        >  
                    </div>
                    
                </div>
			</div>
    </div>
</div>