<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     30.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>


	<div class="related products">
		<div class="related-heading">
			<h2><?php esc_html_e( 'Related products', 'acumec' ); ?></h2>
		</div>
		
		<div id="cms-related-product-<?php the_ID(); ?>" class="related-products">
        	<div id="related-product-carousel" class="cms-products-archive products cms-carousel owl-carousel has-title clearfix">
				<?php foreach ( $related_products as $related_product ) : ?>

					<?php
					 	$post_object = get_post( $related_product->get_id() );

						setup_postdata( $GLOBALS['post'] =& $post_object );

						wc_get_template_part( 'content', 'product' ); ?>

				<?php endforeach; ?>
			</div>
		</div>

	</div>

<?php endif;

wp_reset_postdata();
