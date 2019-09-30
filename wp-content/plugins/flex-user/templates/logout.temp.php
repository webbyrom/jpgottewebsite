<?php
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 25/6/2017
	 * Time: 8:59 AM
	 */
	
	$current_url = ( ! empty( $refer ) ) ? $refer : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$atts = wp_parse_args( $atts, array(
		'logout_text' => esc_html__( 'Logout', fsUser()->domain )
	) );
?>
<aside class="widget widget_logout">
    <a href="<?php echo wp_logout_url($current_url) ?>"><?php echo esc_attr( $atts['logout_text'] ) ?></a>
</aside>
