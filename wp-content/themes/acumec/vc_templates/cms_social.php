<?php 
 
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$options=(array) vc_param_group_parse_atts($options );
  
?>
<?php if(!empty($options)): ?>
	<div class="cms-socials <?php echo esc_attr($atts['style']); ?> <?php echo esc_attr($class); ?>">
            <?php 
            foreach($options as $option): 
            	$social = '';
            	if (!empty($option['social_title'])) {
            		$social = $option['social_title'];
            	}
            	
                if( !empty($option['social_link']) && !empty($option['icon_class']))
                    echo '<a href="'.esc_url($option['social_link']).'" data-placement="top" title="'.esc_attr($social).'"><i class="'.$option['icon_class'].'"></i></a>';
            endforeach; 
             ?>
	</div>
<?php endif; ?>