<?php 
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    $classes=array('cms-message-box',vc_shortcode_custom_css_class( $css ));
     
    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
     
?>
<?php
if(!empty($title) || !empty($content)):
$message_type = !empty($message_type) ? $message_type : 'alert-success';  
$title = !empty($title) ? $title : '';
$message = !empty($message) ? $message : '';

?>
<div class="<?php echo esc_attr($css_class);?>">
    
    <div class="alert <?php echo esc_attr($message_type);?>" role="alert">
		<button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
			<span class="sr-only">Close</span>
		</button>
		<h4><?php echo esc_html($title);?></h4> <?php echo esc_html($message);?>
	</div>
</div>
<?php endif;?>
     
 
 
 
             
 
