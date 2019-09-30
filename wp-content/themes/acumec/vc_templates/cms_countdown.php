<?php	
	/* Jquery Countdown libs */
	wp_enqueue_script('acumec-plugin', get_template_directory_uri() . '/assets/js/jquery.plugin.min.js', array( 'jquery' ), '1.0.1', false);
    wp_enqueue_script('acumec-countdown-plugin', get_template_directory_uri() . '/assets/js/jquery.countdown.min.js', array( 'acumec-plugin' ), '2.0.2', true);

	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );
	$gmt_offset = get_option( 'gmt_offset' );
?>
<?php if (!empty($date_count_down)): ?>  
	<div class="cms-countdown">
		<div class="cms-countdown-bar cms-countdown-time" data-count="<?php echo (!empty($date_count_down)) ? date('Y,m,d,H,i,s', strtotime($date_count_down)) : ''; ?>" data-timezone="<?php echo esc_attr($gmt_offset); ?>"></div>
    </div>
<?php else: ?>
	<div class="required clearfix">
		<h1><?php esc_html_e('Please choose your time remaining first!','acumec'); ?></h1>
	</div>
<?php endif ?>