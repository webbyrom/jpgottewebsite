<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     10.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$template = get_option( 'template' );

if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() )
    $is_sidebar_cls = acumec_shop_sidebar();
    else
    $is_sidebar_cls = acumec_shop_single_sidebar();
    $page_class = $is_sidebar_cls !='is-sidebar-full' ? 'has-sidebar col-md-9 col-sm-12' : 'has-sidebar col-md-12 col-sm-12';
    
switch ( $template ) {
	default :
	?>
		<div class="container">
		<div class="row <?php echo esc_attr($is_sidebar_cls); ?>">
		<div class="content-area <?php echo esc_attr($page_class); ?>">
	<?php
	break;
}
