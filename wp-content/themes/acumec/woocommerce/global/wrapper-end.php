<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
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
switch ( $template ) {
	default :
		echo '</div>'; 
			if($is_sidebar_cls != 'is-sidebar-full'): 
			?>
			<div class="col-md-3 col-sm-12 col-xs-12 woo-sidebar">
    			<div id="secondary" class="main-side-bar widget-area">
    	    		<?php if ( is_active_sidebar( 'sidebar-shop' ) ) : ?>							
    					<?php dynamic_sidebar( 'sidebar-shop' ); ?>
    				<?php else: ?>
    					<?php dynamic_sidebar( 'sidebar-1' ); ?>
    				<?php endif; ?>
    			</div><!-- #secondary -->
            </div>
            <?php endif; 
		echo '</div>';
		echo '</div>';
		break;
}
