<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * @var $this WPBakeryShortCode_cms_images_carousel
 */
    extract($atts);


$testimonial = vc_map_get_attributes( $this->getShortcode(), $atts );
$values = (array) vc_param_group_parse_atts( $testimonial['values'] );

$position_color = $image = $t_color = $bg_color = '';
$show_nav = $nav ? 'has-nav' : '';
$show_dots = $dots ? 'has-dots' : '';
$layout_mode = !empty($atts['layout_mode']) ? $atts['layout_mode'] : '';
$l_item =  !empty($atts['large_items']) ? 'large-'.$atts['large_items'] : '';

if (!empty($atts['show_image']) && $atts['show_image'] == 1) {
        $image = "next-prev-image";
    }
$t_color = !empty($atts['t_color']) ? 'color: '.$atts['t_color'].';' : '';
$p_color = !empty($atts['p_color']) ? 'color: '.$atts['p_color'].';' : '';
$image_size = !empty($atts['image_size']) ? $atts['image_size'] : '140x140';
?>

<div class="cms-testimonial-wrap <?php  echo esc_attr($layout_mode);?> <?php echo esc_attr( $l_item ); ?>">
    <div class="cms-carousel owl-carousel <?php echo esc_attr($image); ?> <?php echo esc_attr($show_nav.' '.$show_dots);?> <?php echo esc_attr($position_color);?> " id="<?php echo esc_attr($atts['html_id']);?>">
    <?php switch($layout_mode){ 
       case 'layout1':
            ?>
            <?php
            foreach($values as $value){
                $link = (isset($value['link'])) ? $value['link'] : '';
                $link = vc_build_link( $link );
                $use_link = false; 
                if ( strlen( $link['url'] ) > 0 ) {
                    $use_link = true;
                    $a_href = $link['url'];
                    $a_title = $link['title'];
                    $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
                }             
                    $images = '';
                    if (!empty($value['testi_avatar'])) {  
                        $images = acumec_get_image_croped($value['testi_avatar'], $image_size);
                    }
                ?>
                <div class="testi-item">
                    <div class="testi-content">
                        <?php if(!empty($images)){?>
                            <div class="post-thumbnail">
                                <?php echo acumec_html($images); ?>
                            </div>
                        <?php } ?>
                        <div class="testi-content-wrap">
                            <?php if(!empty($value['text'])): ?>
                                <div class="testi-description" style="<?php echo esc_attr($t_color); ?>">
                                    <p><?php echo esc_html($value['text']);?></p>
                                </div>
                            <?php endif; ?>
                            <div class="testi-wrap">
                                <?php if(!empty($value['testi_name'])):?>    
                                    <?php if ($use_link): ?>
                                        <div class="testi-title" style="<?php echo esc_attr($t_color); ?>">
                                            <a href=" <?php echo esc_url($a_href); ?> " title=" <?php echo esc_attr($a_title); ?> ">
                                                <?php echo esc_html($value['testi_name']);?> 
                                            </a>                      
                                        </div>
                                    <?php else: ?>
                                        <div class="testi-title" style="<?php echo esc_attr($t_color); ?>">
                                            <?php echo esc_html($value['testi_name']);?>                       
                                        </div>
                                    <?php endif; ?>
                                <?php endif;?>
                                <?php 
                                if(!empty($value['testi_position'])):?>
                                    <div class="testi-position" style="<?php echo esc_attr($p_color); ?>">
                                        <?php echo esc_html($value['testi_position']);   ?>
                                    </div>
                                <?php endif; ?>   
                            </div>
                        </div>
                    </div>
                </div>   
            <?php 
                }    
            
        break;

    }
        ?>

    </div>
</div>