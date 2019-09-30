<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     30.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
  
$theme_options = acumec_get_theme_option();
get_header(); ?>
        	<?php
        		/**
        		 * woocommerce_before_main_content hook.
        		 *
        		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
        		 * @hooked woocommerce_breadcrumb - 20
        		 */
        		do_action( 'woocommerce_before_main_content' );
        	?>
    
    		<?php while ( have_posts() ) : the_post(); ?>
                <?php 
                    global $product;
                    $related = wc_get_related_products($product->get_ID());  

                    if($related){
                        global $cms_carousel;
                        wp_enqueue_style('owl-carousel',get_template_directory_uri().'/assets/css/owl.carousel.min.css','','2.0.0b','all');
                        wp_enqueue_script('owl-carousel',get_template_directory_uri().'/assets/js/owl.carousel.min.js',array('jquery'),'2.0.0b',true);
                        wp_enqueue_script('owl-carousel-cms',get_template_directory_uri().'/assets/js/owl.carousel.cms.js',array('jquery'),'1.0.0',true);
                        $cms_carousel['related-product-carousel'] = array(
                            'loop' => 'true',
                            'mouseDrag' => 'true',
                            'nav' => 'true',
                            'dots' => 'false',
                            'margin' => 30,
                            'autoplay' => 'false',
                            'autoplayTimeout' => 2000,
                            'smartSpeed' => 1500,
                            'autoplayHoverPause' => 'false',
                            'navText' => array('<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'),
                            'responsive' => array(
                                0 => array(
                                "items" => 1,
                                ),
                                768 => array(
                                    "items" => 2,
                                    ),
                                992 => array(
                                    "items" => 3,
                                    ),
                                1200 => array(
                                    "items" => 3,
                                    )
                                )
                        );
                        
                        //$cms_carousel['upsells-product-carousel'] = $cms_carousel['related-product-carousel'];
                        wp_localize_script('owl-carousel-cms', "cmscarousel", $cms_carousel);
                        wp_enqueue_script('owl-carousel-cms');
                    }
                 ?>
    
    			<?php wc_get_template_part( 'content', 'single-product' ); ?>
    
    		<?php endwhile; // end of the loop. ?>
    
        	<?php
        		/**
        		 * woocommerce_after_main_content hook.
        		 *
        		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
        		 */
        		do_action( 'woocommerce_after_main_content' );
        	?>
        	<?php
        		/**
        		 * woocommerce_sidebar hook.
        		 *
        		 * @hooked woocommerce_get_sidebar - 10
        		 */
        		//do_action( 'woocommerce_sidebar' );?>
                <?php remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar'); ?>
    
<?php get_footer(); ?>
