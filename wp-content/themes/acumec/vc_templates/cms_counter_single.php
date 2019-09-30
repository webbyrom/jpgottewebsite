<?php 
    $icon_name = $iconClass = $bg_color ='';
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name])?$atts[$icon_name]:'';
    $icon_color = $text_color = $border_color = $desc = '';
    if (!empty($atts['color_icon'])) {
        $icon_color ='color: '.$atts['color_icon'].';';
    }
    if ((!empty($atts['color_text']))) {
        $text_color = 'color: '.$atts['color_text'].';';
    }

    if ((!empty($atts['color_border']))) {
        $border_color = 'background: '.$atts['color_border'].';';
    }
    if ((!empty($atts['color_counter']))) {
        $desc = 'color: '.$atts['color_counter'].';';
    }
    $icon_color = !empty($atts['color_icon']) ? 'color: '.$atts['color_icon'].';' : '';
    if (!empty($atts['border_style']) && $atts['border_style'] == 'horizontal' ) {
        $border_style = $atts['border_style'];
    }
    else {
        $border_style = 'vertical';
    }
    if ($atts['counter_style'] == 'style-3'): 
        $bg_color = !empty($atts['bg_color']) ? 'background: ' .$atts['bg_color']. ';' :'';
    endif;
?>
<?php if (!empty($atts['counter_style'])): ?>
<div class="cms-counter-wraper <?php echo esc_attr($atts['template']);?> <?php echo esc_attr($atts['counter_style']);?>" id="<?php echo esc_attr($atts['html_id']);?>" style="<?php echo esc_attr( $bg_color ); ?>">	
    <?php if ($atts['counter_style'] == 'style-1'): ?>
        <div class="cms-counter-single"> 
            <?php if (!empty($atts['border']) && $atts['border'] == 1): ?>
                <div class="border-right <?php echo esc_attr($border_style); ?> " style=" <?php echo esc_attr($border_color); ?> "></div>
            <?php endif ?>   
            <div class="counter-content">
                <?php if($atts['c_title']):?>
                    <h3 class="counter-title" style = "<?php echo esc_attr($text_color);?> <?php if(!empty($atts['fsize1'])):?> 
                        font-size:<?php echo esc_attr($atts['fsize1']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fweight1'])): ?> 
                        font-weight:<?php echo esc_attr($atts['fweight1']); ?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['lheight1'])): ?> 
                        line-height:<?php echo esc_attr($atts['lheight1']); ?>; 
                    <?php endif; ?>"><?php echo apply_filters('the_title',$atts['c_title']);?></h3>
                <?php endif;?>
                 <div id="counter_<?php echo esc_attr($atts['html_id']);?>" class="cms-counter <?php echo esc_attr(strtolower($atts['type']));?>" data-suffix="<?php echo esc_attr($atts['suffix']);?>" data-prefix="<?php echo esc_attr($atts['prefix']);?>" data-type="<?php echo esc_attr(strtolower($atts['type']));?>" data-digit="<?php echo esc_attr($atts['digit']);?>" style = "<?php echo esc_attr($desc);?><?php if(!empty($atts['fsize'])):?> 
                        font-size:<?php echo esc_attr($atts['fsize']);?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['fweight'])): ?> 
                        font-weight:<?php echo esc_attr($atts['fweight']); ?>;
                    <?php endif; ?>
                    <?php if(!empty($atts['lheight'])): ?> 
                        line-height:<?php echo esc_attr($atts['lheight']); ?>; 
                    <?php endif; ?>">
                </div>
                <?php if (!empty($atts['counter_type']) && $atts['counter_type'] == 'counter_image'): ?>
                    <?php if (!empty($atts['image'])): ?>
                        <div class="counter-image">
                            <?php echo acumec_get_image_croped($atts['image'],'full'); ?>
                        </div> 
                    <?php endif; ?>
                <?php else: ?>
                    <?php if( $iconClass ): ?>
                        <span class="cms-icon" style = "<?php echo esc_attr($icon_color);?>"><i class="<?php echo esc_attr($iconClass); ?>"></i></span>
                    <?php endif; ?>
                <?php endif; ?>                
            </div>       
        </div>
    <?php endif; ?>
    <?php if ($atts['counter_style'] == 'style-2'): ?>  
        <div class="cms-counter-single clearfix"> 
            <?php if (!empty($atts['border']) && $atts['border'] == 1): ?>
                <div class="border-right <?php echo esc_attr($border_style); ?>" style=" <?php echo esc_attr($border_color); ?> "></div>
            <?php endif; ?>  
            <div class="counter-content">
                <div id="counter_<?php echo esc_attr($atts['html_id']);?>" class="cms-counter <?php echo esc_attr(strtolower($atts['type']));?>" data-suffix="<?php echo esc_attr($atts['suffix']);?>" data-prefix="<?php echo esc_attr($atts['prefix']);?>" data-type="<?php echo esc_attr(strtolower($atts['type']));?>" data-digit="<?php echo esc_attr($atts['digit']);?>" style = "<?php echo esc_attr($desc);?>"><?php echo esc_attr($atts['digit']);?>
                </div>
                <?php if($atts['c_title']):?>
                    <h3 class="counter-title" style = "<?php echo esc_attr($text_color);?>"><?php echo apply_filters('the_title',$atts['c_title']);?></h3>
                <?php endif;?>
                <?php if (!empty($atts['counter_content'])) {?>
                    <div class="counter-des" style = "<?php echo esc_attr($text_color);?>">
                        <?php echo esc_attr($atts['counter_content']); ?>
                    </div>
                <?php } ?>
            </div>       
        </div>
    <?php endif; ?>  
    <?php if ($atts['counter_style'] == 'style-3'): ?>  
        <div class="cms-counter-single-style3">
            <div class="cms-counter-single clearfix">   
                <?php if (!empty($iconClass)): ?>
                    <div class="counter-icon">
                        <i class="<?php echo esc_attr($iconClass); ?>" style="<?php echo esc_attr($icon_color); ?>"></i>
                    </div>
                <?php endif; ?>           
                <div class="counter-content">
                    <div class="counter-content-wrap">
                        <h2 id="counter_<?php echo esc_attr($atts['html_id']);?>" class="cms-counter <?php echo esc_attr(strtolower($atts['type']));?>" data-suffix="<?php echo esc_attr($atts['suffix']);?>" data-prefix="<?php echo esc_attr($atts['prefix']);?>" data-type="<?php echo esc_attr(strtolower($atts['type']));?>" data-digit="<?php echo esc_attr($atts['digit']);?>" style = "<?php echo esc_attr($desc);?>"><?php echo esc_attr($atts['digit']);?>
                        </h2>
                        <?php if($atts['c_title']):?>
                            <h3 class="counter-title" style = "<?php echo esc_attr($text_color);?>"><?php echo apply_filters('the_title',$atts['c_title']);?></h3>
                        <?php endif;?>
                    </div>    
                </div>       
            </div>
        </div>  
    <?php endif; ?>             
</div>
<?php endif; ?>