<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
extract( $atts );
/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $values
 * Shortcode class
 * @var $this WPBakeryShortCode_cms_images_carousel
 */
/* get Shortcode custom value */
    extract(shortcode_atts(array(
        'color_mode'    => '',
        'nav'           => true,
        'dots'          => false,
        'dotdata'       => false
    ), $atts));

$box = ''; 
$client = vc_map_get_attributes( $this->getShortcode(), $atts );
$values = (array) vc_param_group_parse_atts( $client['values'] );

$show_nav = $nav ? 'has-nav' : '';
$show_dots = $dots ? 'has-dots' : '';
$thumbnail = '';

$bgimage_url = '';
if (!empty($atts['image'])) {
    $bgattachment_image = wp_get_attachment_image_src($atts['image'], 'full');
    $bgimage_url = $bgattachment_image[0];
}

?>

<?php 
    
    if(isset($atts['border_image']) && $atts['border_image'])
        $box = 'border-image';
 
?>
<div class="cms-client-carousel">
    <div class="cms-carousel clearfix owl-carousel <?php echo esc_attr($show_nav.' '.$show_dots);?>" id="<?php echo esc_attr($atts['html_id']);?>">
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

                $image_url = '';
                if (!empty($value['client_image'])) {  
                    $attachment_image = wp_get_attachment_image_src($value['client_image'], 'thumbnail');
                    $image_url = $attachment_image[0];
                }
            ?>
            <div class="client-item <?php echo esc_attr($box); ?>">
                <?php
                if(!empty($image_url)){
                    ?>
                    <div class="client-image">
                        <?php if($use_link):?>
                           <a href="<?php echo esc_url($a_href);?>" target="<?php echo esc_attr($a_target);?>"><img src="<?php echo esc_url($image_url);?>" class="round"/></a>
                        <?php else:?>
						    <img src="<?php echo esc_url($image_url);?>" class="round"/>
                        <?php endif; ?>
					</div>
                    <?php
                }
                ?>
			</div>   
        <?php 
        }
        ?>
    </div>
</div>
 
