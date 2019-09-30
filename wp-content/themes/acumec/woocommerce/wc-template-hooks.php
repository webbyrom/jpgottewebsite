<?php
/**
 * WooCommerce Template Hooks
 *
 * Action/filter hooks used for WooCommerce functions/templates.
 *
 * @author      Knight
 * @category    Core
 * @package     WooCommerce/Templates
 * @version     30.1.x
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

add_filter( 'woocommerce_show_page_title' , 'acumec_woo_hide_page_title' );
 
function acumec_woo_hide_page_title() {
	return false;
}

/**
 * Remove all default css style
 * add_filter('woocommerce_enqueue_styles', '__return_empty_array');
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');


/* Remove style of plugin WooCommerce Quantity Increment */
add_action('wp_enqueue_scripts', 'wcqi_dequeue_quantity');
function wcqi_dequeue_quantity()
{
    wp_dequeue_style('wcqi-css');
}

/**
 * Shop sidebar
 */
function acumec_shop_sidebar(){
    global $opt_theme_options;

    $_sidebar = 'full';

    if(isset($opt_theme_options['woo_loop_layout']))
        $_sidebar = $opt_theme_options['woo_loop_layout'];
    
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_sidebar = 'full';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'left' )
        $_sidebar = 'left';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'right' )
        $_sidebar = 'right';
    return 'is-sidebar-' . esc_attr($_sidebar);
}


function acumec_loop_columns(){
	global $opt_theme_options;
    if(!isset($opt_theme_options['woo_loop_layout']))
        return '4';
        
    $is_sidebar = acumec_shop_sidebar();

    if(isset($_GET['cols']) && trim($_GET['cols']) == 2 )
            return '2';
        if(isset($_GET['cols']) && trim($_GET['cols']) == 3 )
            return '3';
        if(isset($_GET['cols']) && trim($_GET['cols']) == 4 )
            return '4';
    if($is_sidebar == 'is-sidebar-full'){
        return $opt_theme_options['shop_columns_full'];
    }else{
        return $opt_theme_options['shop_columns'];
    }
}



/**
 * Shop product sidebar
 */
function acumec_shop_single_sidebar(){
    global $opt_theme_options;

    $_sidebar = 'full';

    if(isset($opt_theme_options['woo_single_layout']))
        $_sidebar = $opt_theme_options['woo_single_layout'];
    
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_sidebar = 'full';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'left' )
        $_sidebar = 'left';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'right' )
        $_sidebar = 'right';
    return 'is-sidebar-' . esc_attr($_sidebar);
}

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 11);

add_action('woocommerce_before_shop_loop_item', 'acumec_wc_loop_open', 0);
if (!function_exists('acumec_wc_loop_open')) {
    function acumec_wc_loop_open()
    {
        echo '<div class="wc-product-wrap clearfix">';
    }
}
add_action('woocommerce_after_shop_loop_item', 'acumec_wc_loop_close', 99999);
if (!function_exists('acumec_wc_loop_close')) {
    function acumec_wc_loop_close()
    {
        echo '</div>';
    }
}


/* add div wrap image */
add_action('woocommerce_before_shop_loop_item_title', 'acumec_wc_loop_image_open', 0);
if (!function_exists('acumec_wc_loop_image_open')) {
    function acumec_wc_loop_image_open()
    {
        echo '<div class="wc-img-wrap" onclick = "">';
    }
}
//remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20);

if (!function_exists('acumec_wc_loop_image_close')) {
    function acumec_wc_loop_image_close()
    {
        echo '</div>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'acumec_wc_loop_image_close', 9999);

add_action('woocommerce_before_shop_loop_item_title', 'acumec_wc_loop_add_to_cart_open', 11);
function acumec_wc_loop_add_to_cart_open(){
    echo '<div class="wc-loop-cart-wrap"><div class="wc-cart-wrap">';
}
add_action('woocommerce_before_shop_loop_item_title', 'acumec_wc_loop_add_to_cart_close', 99);
function acumec_wc_loop_add_to_cart_close(){
    echo '</div></div>';
}

/* add div wrap product content after image overlay */
add_action('woocommerce_shop_loop_item_title', 'acumec_wc_loop_content_open', 1);
function acumec_wc_loop_content_open(){
    echo '<div class="wc-loop-content-wrap">';
}
add_action('woocommerce_after_shop_loop_item', 'acumec_wc_loop_content_close', 999999);
function acumec_wc_loop_content_close(){
    echo '</div>';
}

/**
 * Change title structure
 * woocommerce_after_shop_loop_item hook.
 *
 * @hooked acumec_woocommerce_template_loop_product_title - 10
 */
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'acumec_woocommerce_template_loop_product_title', 10);
if (!function_exists('acumec_woocommerce_template_loop_product_title')) {
    function acumec_woocommerce_template_loop_product_title()
    {
        the_title('<h5 class="wc-loop-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h5>' );
    }
}
add_action('woocommerce_shop_loop_item_title', 'acumec_woocommerce_template_loop_product_excerpt', 11);
function acumec_woocommerce_template_loop_product_excerpt(){
    global $post;
    if ( ! $post->post_excerpt ) {
        return;
    }
    ?>
    <div class="product-description">
        <?php echo apply_filters( 'woocommerce_short_description', acumec_grid_limit_words(strip_tags($post->post_excerpt),10) ); ?>
    </div>
    <?php  
}

/**
 * Change Add to cart button text 
 * acumec_wc_loop_add_to_cart_text
*/
//add_filter( 'woocommerce_product_add_to_cart_text' , 'acumec_wc_loop_add_to_cart_text' );
function acumec_wc_loop_add_to_cart_text() {
    global $product;
    $product_type = $product->get_type();
    $product_id = $product-> get_id();
    switch ( $product_type ) {
        case 'external':
            return esc_html__('Add To Cart','acumec');
        break;
        case 'grouped':
            return esc_html__('Add To Cart','acumec');
        break;
        case 'simple':
            return esc_html__('Add To Cart','acumec');
        break;
        case 'variable':
            return esc_html__('Select Options','acumec');
        break;
        default:
            return esc_html__('Add To Cart','acumec');
    }
}


/**
 * Change title structure
 * woocommerce_after_shop_loop hook.
 *
 * @hooked acumec_woocommerce_pagination - 10
 */
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'acumec_woocommerce_pagination', 10);
if (!function_exists('acumec_woocommerce_pagination')) {
    function acumec_woocommerce_pagination()
    {
        global $wp_query;
        if ( $wp_query->max_num_pages <= 1 ) {
        	return;
        }
        ?>
        <nav class="woocommerce-pagination">
            <div class="pagination">
        	<?php
        		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
        			'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
        			'format'       => '',
        			'add_args'     => false,
        			'current'      => max( 1, get_query_var( 'paged' ) ),
        			'total'        => $wp_query->max_num_pages,
        			'prev_text'    => '<i class="fa fa-angle-left"></i>',
        			'next_text'    => '<i class="fa fa-angle-right"></i>',
        			'type'         => 'list',
        			'end_size'     => 3,
        			'mid_size'     => 3,
        		) ) );
        	?>
            </div>
        </nav>
        <?php
    }
}


/* Single Product */
/* add div wrap image / summary */
add_action('woocommerce_before_single_product_summary', 'acumec_woo_wrap_image_summary_open', 0);
if (!function_exists('acumec_woo_wrap_image_summary_open')) {
    function acumec_woo_wrap_image_summary_open()
    {
        echo '<div class="img-summary-wrap row clearfix">';

    }
}
add_action('woocommerce_after_single_product_summary', 'acumec_woo_wrap_image_summary_close', 0);
if (!function_exists('acumec_woo_wrap_image_summary_close')) {
    function acumec_woo_wrap_image_summary_close()
    {
        echo '</div></div>';
    }
}
add_action('woocommerce_before_single_product_summary', 'acumec_woo_wrap_image_open', 1);
add_action('woocommerce_before_single_product_summary', 'acumec_woo_wrap_image_close', 999999);
if (!function_exists('acumec_woo_wrap_image_open')) {
    function acumec_woo_wrap_image_open()
    {
        echo '<div class="wc-single-img-wrap col-md-6 col-lg-6">';
    }
}
if (!function_exists('acumec_woo_wrap_image_close')) {
    function acumec_woo_wrap_image_close()
    {
        echo '</div><div class="col-md-6 col-lg-6">';
    }
}

/*
* Custom image gallery Style
*/
if (!function_exists('acumec_custom_wc_single_gallery')) {
    function acumec_custom_wc_single_gallery()
    {
        $options = array(
            'rtl'            => is_rtl(),
            'animation'      => 'slide',
            'smoothHeight'   => true,
            'directionNav'   => true,
            'controlNav'     => 'thumbnails',
            'slideshow'      => false,
            'animationSpeed' => 500,
            'animationLoop'  => false, // Breaks photoswipe pagination if true.
        );
        return $options;
    }
}
//add_filter('woocommerce_single_product_carousel_options', 'acumec_custom_wc_single_gallery');
 
/* Change title structure */
if (!function_exists('woocommerce_template_single_title')) {
    /**
     * Output the product title.
     *
     * @subpackage  Product
     */
    
    function woocommerce_template_single_title() {
        global $opt_theme_options;
        if($opt_theme_options['page_title_layout'] == '7') { 
            the_title('<h3 class="product-title">', '</h3>');
        }     
    }
}

/**
 * Change title structure
 * woocommerce_single_product_summary hook.
 *
 * @hooked woocommerce_template_single_rating - 10
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action('woocommerce_single_product_summary', 'acumec_woocommerce_template_single_rating', 15);
if (!function_exists('acumec_woocommerce_template_single_rating')) {
    function acumec_woocommerce_template_single_rating()
    {
        global $product;
        if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
        	return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        
        if ( $rating_count > 0 ) : ?>
        
        	<div class="woocommerce-product-rating">
        		<?php echo wc_get_rating_html( $average, $rating_count ); ?>
        		<?php if ( comments_open() ) : ?><a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s Review', '%s Reviews', $review_count, 'acumec' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a><?php endif ?>
        	</div>
        
        <?php endif; ?>
        <?php
    }
}


add_action('woocommerce_review_before_comment_meta', 'acumec_review_before_comment_meta_open', 0);
if (!function_exists('acumec_review_before_comment_meta_open')) {
    function acumec_review_before_comment_meta_open()
    {
        echo '<div class="comment-meta-wrap">';
    }
}

add_action('woocommerce_review_meta', 'acumec_woocommerce_review_meta_close', 999);
if (!function_exists('acumec_woocommerce_review_meta_close')) {
    function acumec_woocommerce_review_meta_close()
    {
        echo '</div>';
    }
}

/* Move process checkout button */
remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
add_action('woocommerce_cart_actions', 'woocommerce_button_proceed_to_checkout', 20);
 
 
add_action('woocommerce_checkout_before_customer_details', 'acumec_woocommerce_checkout_before_customer_details', 0);
if (!function_exists('acumec_woocommerce_checkout_before_customer_details')) {
    function acumec_woocommerce_checkout_before_customer_details()
    {
        echo '<div class="row"><div class="col-sx-12 col-sm-12 col-md-6">';
    }
}

add_action('woocommerce_checkout_after_customer_details', 'acumec_woocommerce_checkout_after_customer_details', 999);
if (!function_exists('acumec_woocommerce_checkout_after_customer_details')) {
    function acumec_woocommerce_checkout_after_customer_details()
    {
        echo '</div>';
    }
}
add_action('woocommerce_checkout_before_order_review', 'acumec_woocommerce_checkout_before_order_review', 999);
if (!function_exists('acumec_woocommerce_checkout_before_order_review')) {
    function acumec_woocommerce_checkout_before_order_review()
    {
        echo '<div class="col-sx-12 col-sm-12 col-md-6">';
    }
}

add_action('woocommerce_checkout_after_order_review', 'acumec_woocommerce_checkout_after_order_review', 0);
if (!function_exists('acumec_woocommerce_checkout_after_order_review')) {
    function acumec_woocommerce_checkout_after_order_review()
    {
        echo '</div></div>';
    }
}

/**
 * Remove field label
 * add_filter( 'woocommerce_form_field_args' , 'acumec_override_woocommerce_form_field' );
 */
add_filter( 'woocommerce_form_field_args' , 'acumec_override_woocommerce_form_field' );
function acumec_override_woocommerce_form_field($args)
{
    $args['label'] = false;
    return $args;
}

/* Overide checkout field */
function acumec_override_checkout_fields( $fields ) {
    $fields['billing']['billing_first_name']['placeholder'] = esc_html__('First Name *','acumec');
    $fields['billing']['billing_last_name']['placeholder'] = esc_html__('Last Name *','acumec');
    $fields['billing']['billing_company']['placeholder'] = esc_html__('Company Name','acumec');
    $fields['billing']['billing_email']['placeholder'] = esc_html__('Email Address *','acumec');
    $fields['billing']['billing_phone']['placeholder'] = esc_html__('Phone *','acumec');
    $fields['billing']['billing_city']['placeholder'] = esc_html__('Town / City *','acumec');
    $fields['billing']['billing_postcode']['placeholder'] = esc_html__('Postcode *','acumec');
    $fields['billing']['billing_state']['placeholder'] = esc_html__('State *','acumec');
    $fields['billing']['billing_country']['placeholder'] = esc_html__('Country *','acumec');
    $fields['billing']['billing_address_1']['placeholder'] = esc_html__('Street *','acumec');

    $fields['shipping']['shipping_first_name']['placeholder'] = esc_html__('First Name *','acumec');
    $fields['shipping']['shipping_last_name']['placeholder'] = esc_html__('Last Name *','acumec');
    $fields['shipping']['shipping_company']['placeholder'] = esc_html__('Company Name','acumec');
    $fields['shipping']['shipping_city']['placeholder'] = esc_html__('Town / City *','acumec');
    $fields['shipping']['shipping_postcode']['placeholder'] = esc_html__('Postcode *','acumec');
    $fields['shipping']['shipping_state']['placeholder'] = esc_html__('State *','acumec');
    $fields['shipping']['shipping_country']['placeholder'] = esc_html__('Country *','acumec');
    
    $fields['account']['account_username']['placeholder'] = esc_html__('Username or email *','acumec');
    $fields['account']['account_password']['placeholder'] = esc_html__('Password *','acumec');
    $fields['account']['account_password-2']['placeholder'] = esc_html__('Retype Password *','acumec');

    $fields['order']['order_comments']['placeholder'] = esc_html__('Order Notes','acumec');

    /* Add Email/ Phone on Shipping fields*/
    $fields['shipping']['shipping_email'] = array(
		'label'     	=> esc_html__('Email Address', 'acumec'),
		'placeholder'   => _x('Email Address', 'placeholder', 'acumec'),
		'required'  	=> false,
		'class'     	=> array('form-row-first'),
		'clear'     	=> false
	);
    $fields['shipping']['shipping_phone'] = array(
		'label'     	=> esc_html__('Phone', 'acumec'),
		'placeholder'   => _x('Phone', 'placeholder', 'acumec'),
		'required'  	=> false,
		'class'     	=> array('form-row-last'),
		'clear'     	=> true,
		'order'			=> '6'
	);

    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'acumec_override_checkout_fields' );


