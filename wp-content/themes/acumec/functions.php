<?php
/**
 * Theme Framework functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 1170;
/**
 * CMS Theme setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * CMS Theme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since 1.0.0
 */
function acumec_setup() {

	// load language.
	load_theme_textdomain( 'acumec' , get_template_directory() . '/languages' );

	// Adds title tag
	add_theme_support( "title-tag" );
	
	// Add woocommerce
	add_theme_support('woocommerce');
	
	// Adds custom header
	add_theme_support( 'custom-header' );
	
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'video', 'audio' , 'gallery', 'quote','link','status') );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'acumec' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array('default-color' => 'e6e6e6') );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support('post-thumbnails');
    set_post_thumbnail_size(370, 9999, true); // Limited height, hard crop
    $theme_options = [
        'large_size_w'        => 1170,
        'large_size_h'        => 465,
        'large_crop'          => 1, 
        'medium_size_w'       => 770,
        'medium_size_h'       => 447,
        'medium_crop'         => 1,
        'thumbnail_size_w'    => 380,
        'thumbnail_size_h'    => 380,
        'thumbnail_crop'      => 1,
    ];
    foreach ($theme_options as $option => $value) {
        if (get_option($option, '') != $value)
            update_option($option, $value);
    }
   	add_image_size('acumec-770-770', 770,770, true);
   	add_image_size('acumec-770-375', 770,375, true);
   	add_image_size('acumec-1920-683', 1920,683, true);
	/*
	 * This theme styles the visual editor to resemble the theme style, 
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'assets/css/editor-style.css' ) );
}

add_action( 'after_setup_theme', 'acumec_setup' );

/* make shop image size*/
add_action('init', 'acumec_change_default_woo_thumb_size');
function acumec_change_default_woo_thumb_size(){
 register_activation_hook('woocommerce/woocommerce.php', 'acumec_woocommerce_image_dimensions');
}
function acumec_woocommerce_image_dimensions() {
    global $pagenow;
    $catalog = array(
        'width'     => '380',   // px
        'height'    => '360',   // px
        'crop'      => 1        // true
    );
    $single = array(
        'width'     => '760',   // px 
        'height'    => '600',   // px
        'crop'      => 1        // true
    );
    $thumbnail = array(
        'width'     => '200',   // px
        'height'    => '200',   // px
        'crop'      => 1        // true
    );
  
    update_option( 'shop_catalog_image_size', $catalog );       
    update_option( 'shop_single_image_size', $single );         
    update_option( 'shop_thumbnail_image_size', $thumbnail );   
}

/**
 * Call default WC single image gallery
 */
function acumec_wc_single_gallery()
{
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider'); 
}

add_action('after_setup_theme', 'acumec_wc_single_gallery');
/**
 * support shortcodes
 * @return array
 */
function acumec_shortcodes(){
	return array(
		'cms_carousel',
		'cms_grid',
		'cms_fancybox_single',
		'cms_counter_single',
		'cms_progressbar'
	);
}

add_action('vc_after_init', 'acumec_vc_after');
function acumec_vc_after() {
    require( get_template_directory() . '/vc_params/vc_row.php' );
    require( get_template_directory() . '/vc_params/vc_column_text.php' );
    vc_add_shortcode_param( 'cms_time', 'acumec_time_settings_field', get_template_directory_uri() . '/assets/js/jquery.datetimepicker.js' );
}

function acumec_time_settings_field($settings, $value) {
    return '<div class="date-field cms_datetime_block" data-type="datetime" data-format="m/d/Y H:i">'
        	.'<input type="text" name="'.esc_attr( $settings['param_name']).'" class="wpb_vc_param_value wpb-textinput" value="'.esc_attr( $value ).'"/>'
        .'</div>';
}

/**
 * Add new elements for VC
 * 
 * @author FOX
 */
add_action('vc_before_init', 'acumec_vc_before');
function acumec_vc_before(){
   	require( get_template_directory() . '/vc_params/vc_custom.php' );	
    if(!class_exists('CmsShortCode'))
        return ;
	require( get_template_directory() . '/inc/elements/zo_masonry.php' );
	require( get_template_directory() . '/inc/elements/cms_button.php' );
	require( get_template_directory() . '/inc/elements/cms_message_box.php' );
	require( get_template_directory() . '/inc/elements/cms_custom_heading.php' );
	require( get_template_directory() . '/inc/elements/cms_googlemap.php' );
	require( get_template_directory() . '/inc/elements/cms_testimonial.php' );
	require( get_template_directory() . '/inc/elements/cms_testimonial_carousel.php' );
	require( get_template_directory() . '/inc/elements/cms_social.php' );
	require( get_template_directory() . '/inc/elements/cms_client_carousel.php' );	
	require( get_template_directory() . '/inc/elements/cms_pricing_table.php' );
	require( get_template_directory() . '/inc/elements/cms_blog_carousel.php' );
	require( get_template_directory() . '/inc/elements/cms_countdown.php' );
	require( get_template_directory() . '/inc/elements/cms_post_carousel.php' );
    add_filter('cms-shorcode-list', 'acumec_shortcodes');
     
}

/* require widget*/

require( get_template_directory() . '/inc/widgets/cart_search/cart_search.php' ); 
require( get_template_directory() . '/inc/widgets/cms_socials.php' );
require( get_template_directory() . '/inc/widgets/recent_post_v2.php' );
require( get_template_directory() . '/inc/widgets/cms_image.php' );
require( get_template_directory() . '/inc/widgets/cms_recentpost.php' );
require( get_template_directory() . '/inc/widgets/flickrphotos.php' );
require( get_template_directory() . '/inc/widgets/cms_testimonials.php' );

function acumec_open_sans() {
    $fonts_url = '';
    $opensans = _x('on','Opensans font: on or off','acumec');
     if ( 'off' !== $opensans ) {
        $query_args = array(
        'family' =>  'Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i', 
        'subset' => urlencode( 'latin,latin-ext' )
        );
      }
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    return esc_url_raw( $fonts_url );
}
function acumec_lato() {
    $fonts_url = '';
    $lato = _x('on','Lato font: on or off','acumec');
     if ( 'off' !== $lato ) {
        $query_args = array(
        'family' =>  'Lato:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i', 
        'subset' => urlencode( 'latin,latin-ext' )
        );
      }
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    return esc_url_raw( $fonts_url );
}
function acumec_poppins() {
    $fonts_url = '';
    $poppins = _x('on','Poppins font: on or off','acumec');
     if ( 'off' !== $poppins ) {
        $query_args = array(
        'family' =>  'Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i', 
        'subset' => urlencode( 'latin,latin-ext' )
        );
      }
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    return esc_url_raw( $fonts_url );
}
function acumec_montserrat() {
    $fonts_url = '';
    $montserrat = _x('on','Montserrat font: on or off','acumec');
     if ( 'off' !== $montserrat ) {
        $query_args = array(
        'family' =>  'Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i', 
        'subset' => urlencode( 'latin,latin-ext' )
        );
      }
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    return esc_url_raw( $fonts_url );
}
/**
 * Enqueue scripts and styles for front-end.
 * @author Fox
 * @since CMS SuperHeroes 1.0
 */
function acumec_front_end_scripts() {
    
	global $wp_styles, $opt_meta_options;

	wp_enqueue_style( 'acumec-open-sans-font', acumec_open_sans(), array(), null );

	wp_enqueue_style( 'acumec-lato-font',acumec_lato(), array(), null );

	wp_enqueue_style( 'acumec-poppins-font',acumec_poppins(), array(), null );

	wp_enqueue_style( 'acumec-montserrat-font',acumec_montserrat(), array(), null );

	/* Adds JavaScript Bootstrap. */
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.2');
		
	 /* Adds JavaScript Bootstrap. */
	wp_enqueue_script('wow-effect', get_template_directory_uri() . '/assets/js/wow.min.js', array( 'jquery' ), '1.0.1', true);	
	 
    wp_enqueue_script('owl-carousel',get_template_directory_uri().'/assets/js/owl.carousel.min.js',array('jquery'),'2.0.0b',true);
	/* Add main.js */
	wp_enqueue_script('acumec-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    wp_localize_script('acumec-main', 'ajax_data', array(
        'url' => admin_url('admin-ajax.php'),
        'add' => 'new_reservation'
    ));

    wp_enqueue_script('acumec-grid-pagination', get_template_directory_uri() . '/assets/js/cmsgrid.pagination.js', array('jquery'), '1.0.0', true);
    wp_localize_script('acumec-grid-pagination', 'ajax_data', array(
        'url' => admin_url('admin-ajax.php'),
        'add' => 'new_reservation'
    ));
    
	/* Add menu.js */
	wp_enqueue_script('acumec-menu', get_template_directory_uri() . '/assets/js/menu.js', array('jquery'), '1.0.0', true);

 /* Adds magnific popup. */
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true);
	
    /* one page. */
	if(is_page() && isset($opt_meta_options['enable_one_page']) && $opt_meta_options['enable_one_page']) {
		wp_register_script('jquery-singlePageNav', get_template_directory_uri() . '/assets/js/jquery.singlePageNav.js', array('jquery'), '1.2.0');
		wp_localize_script('jquery-singlePageNav', 'one_page_options', array('filter' => '.is-one-page', 'speed' => $opt_meta_options['page_one_page_speed']));
		wp_enqueue_script('jquery-singlePageNav');
	}
    
	/* Comment */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/** ----------------------------------------------------------------------------------- */
	
	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
	
	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css');

	wp_enqueue_style('wow-animate', get_template_directory_uri() . '/assets/css/animate.css');	

	wp_enqueue_style('owl-carousel',get_template_directory_uri().'/assets/css/owl.carousel.min.css','','2.0.0b','all');

	    /* Load magnific popup css*/
    wp_enqueue_style('magnific-popup-css', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.0.1');

  	/* Loads Stroke gap font */
	wp_enqueue_style('font-stroke-7-icon', get_template_directory_uri() . '/assets/css/pe-icon-7-stroke.css');

	/* Loads Stroke gap font */
	wp_enqueue_style('font-food-2', get_template_directory_uri() . '/assets/css/food-font-2.css');
	
	/* Loads Stroke gap font */
	wp_enqueue_style('font-flaticon', get_template_directory_uri() . '/assets/css/flaticon.css');

	/* Loads our main stylesheet. */
	wp_enqueue_style( 'acumec-style', get_stylesheet_uri());

	/* Loads the Internet Explorer specific stylesheet. */
	wp_enqueue_style( 'acumec-ie', get_template_directory_uri() . '/assets/css/ie.css');
	
	/* ie */
	$wp_styles->add_data( 'acumec-ie', 'conditional', 'lt IE 9' );
	
	/* Load static css*/
	wp_enqueue_style('acumec-static', get_template_directory_uri() . '/assets/css/static.css');
	


}

add_action( 'wp_enqueue_scripts', 'acumec_front_end_scripts' );



/**
 * load admin scripts.
 * 
 * @author FOX
 */
function acumec_admin_scripts($hook){

	wp_enqueue_style('acumec-admin-style', get_template_directory_uri() . '/assets/css/admin-style.css');
	/* Loads Bootstrap stylesheet. */
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.3.0');
	
	wp_enqueue_style('font-food-2', get_template_directory_uri() . '/assets/css/food-font-2.css');

	wp_enqueue_style('font-flaticon', get_template_directory_uri() . '/assets/css/flaticon.css');

	$screen = get_current_screen();

	/* load js for edit post. */
	if($screen->post_type == 'post'){
		/* post format select. */
		wp_enqueue_script('post-format', get_template_directory_uri() . '/assets/js/post-format.js', array(), '1.0.0', true);
	}
	
    wp_enqueue_script('acumec-time', get_template_directory_uri() . '/assets/js/datetime-element.js');

    // Include theme styles for admin
    wp_enqueue_style( 'acumec-admin', get_template_directory_uri() . '/assets/css/admin.css' );
    wp_enqueue_script( 'acumec-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'media-upload' ) );
    wp_localize_script( 'acumec-admin', 'cargo_pifourMediaLocalize', array(
        'add_video' => esc_html__( 'Add Video', 'acumec' ),
        'add_audio' => esc_html__( 'Add Audio', 'acumec' ),
        'add_images' => esc_html__( 'Add Image(s)', 'acumec' ),
        'add_image' => esc_html__( 'Add Image', 'acumec' )
    ) );
}

add_action( 'admin_enqueue_scripts', 'acumec_admin_scripts', 1 );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Fox
 */
function acumec_widgets_init() {

	global $opt_theme_options;

	register_sidebar( array(
		'name' => esc_html__( 'Main Sidebar', 'acumec' ),
		'id' => 'sidebar-1',
		'description' => esc_html__( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Page Sidebar', 'acumec' ),
		'id' => 'sidebar-page',
		'description' => esc_html__( 'Pages Sidebar', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	if (class_exists('WooCommerce')) {
        register_sidebar(array(
            'name'          => esc_html__('WooCommerce Sidebar', 'acumec'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Appears in WooCommerce Archive page', 'acumec'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="wg-title"><span>',
            'after_title'   => '</span></h4>',
        ));
    }
    register_sidebar( array(
		'name' => esc_html__( 'Header Middle - Header Layout Default Sidebar', 'acumec' ),
		'id' => 'header-top-right-1',
		'description' => esc_html__( 'Appears on the top right of header', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Header Main Right - Header Layout 2 Sidebar', 'acumec' ),
		'id' => 'header-main-right',
		'description' => esc_html__( 'Appears on the main right of header', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Header Top Left Sidebar', 'acumec' ),
		'id' => 'header-top-left',
		'description' => esc_html__( 'Appears on the top left of header', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Header Top Center Sidebar', 'acumec' ),
		'id' => 'header-top-center',
		'description' => esc_html__( 'Appears on the top center of header', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Header Top Right Sidebar', 'acumec' ),
		'id' => 'header-top-right',
		'description' => esc_html__( 'Appears on the top right of header', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );

	/* Client logo footer */
	if ( !empty($opt_theme_options['enable_client_footer']) && $opt_theme_options['enable_client_footer'] == '1'){
        register_sidebar(array(
            'name'          => esc_html__('Client logo Footer', 'acumec'),
            'id'            => 'sidebar-client-logo',
            'description'   => esc_html__('Appears at top of footer', 'acumec'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="wg-title">',
            'after_title'   => '</h3>',
        ));
    }
	/* footer-top */
	if(!empty($opt_theme_options['footer-top-column'])) {

		for($i = 1 ; $i <= $opt_theme_options['footer-top-column'] ; $i++){
			register_sidebar(array(
				'name' => sprintf(esc_html__('Footer Top %s', 'acumec'), $i),
				'id' => 'sidebar-footer-top-' . $i,
				'description' => esc_html__('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'acumec'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="wg-title">',
				'after_title' => '</h3>',
			));
		}
	}
	if(!empty($opt_theme_options['footer-top-column-layout2'])) {

		for($i = 1 ; $i <= $opt_theme_options['footer-top-column-layout2'] ; $i++){
			register_sidebar(array(
				'name' => sprintf(esc_html__('Footer Top layout 2 - col %s', 'acumec'), $i),
				'id' => 'sidebar-footer-top-layout2-' . $i,
				'description' => esc_html__('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'acumec'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="wg-title">',
				'after_title' => '</h3>',
			));
		}
	}
	/* footer-bottom */
	register_sidebar( array(
		'name' => esc_html__( 'Footer Bottom Left Sidebar', 'acumec' ),
		'id' => 'sidebar-footer-bottom-left',
		'description' => esc_html__( 'Footer Bottom Sidebar', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Footer Bottom Right Sidebar', 'acumec' ),
		'id' => 'sidebar-footer-bottom-right',
		'description' => esc_html__( 'Footer Bottom Right Sidebar', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Newsletter Sidebar', 'acumec' ),
		'id' => 'sidebar-news-letter',
		'description' => esc_html__( 'Newsletter Sidebar', 'acumec' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="wg-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'acumec_widgets_init' );

/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since 1.0.0
 */
function acumec_comment_nav() {
    // Are there comments to navigate through?
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
	<div class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'acumec' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'acumec' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'acumec' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</div><!-- .comment-navigation -->
	<?php
	endif;
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0.0
 */
function acumec_paging_nav() {
    // Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	// Set up paginated links.
	$links = paginate_links( array(
			'base'     => $pagenum_link,
			'total'    => $GLOBALS['wp_query']->max_num_pages,
			'current'  => $paged,
			'mid_size' => 1,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => '<i class="fa fa-angle-double-left"></i>',
			'next_text' => '<i class="fa fa-angle-double-right"></i>',
	) );

	if ( $links ) :

	?>
	<div class="navigation paging-navigation clearfix" role="navigation">
			<div class="pagination loop-pagination">
				<?php echo wp_kses_post($links); ?>
			</div><!-- .pagination -->
	</div><!-- .navigation -->
	<?php
	endif;
}

// function display number view of posts.
function acumec_get_post_viewed($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count;
}
// function to count views.
add_action( 'wp_head', 'acumec_set_post_view' );
function acumec_set_post_view(){
	if( is_single() ){ 
		$postID = get_the_ID();
		$count_key = 'post_views_count';
		$count = intval(get_post_meta($postID, $count_key, true));
		if(!$count){
			$count = 1;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, $count);
		}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
}

function acumec_product_nav() {
    global $post; 
    
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
        return;
    ?>
	<nav class="navigation product-navigation">
		<div class="nav-links clearfix">
			<?php
			$prev_post = get_previous_post();
			if (!empty( $prev_post )): 
            ?>
            <a class="pro-prev left btn btn-theme-default" href="<?php echo esc_url(get_permalink( $prev_post->ID )); ?>"><?php echo esc_html__('Previous','acumec'); ?></a>   
			<?php endif; ?>
			<?php
			$next_post = get_next_post();
			if ( is_a( $next_post , 'WP_Post' ) ) { 
                ?>
    			<a class="pro-next right btn btn-theme-default" href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><?php echo esc_html__('Next','acumec'); ?></a>   
			<?php } ?>
		</div> 
	</nav> 
	<?php
}

function acumec_limit_words($string) {
    global $opt_theme_options;
    if(isset($opt_theme_options['excerpt_length']) && !empty($opt_theme_options['excerpt_length']) && (int) $opt_theme_options['excerpt_length'] > 0){
        $word_limit =  $opt_theme_options['excerpt_length'];
        if(is_sticky()) $word_limit = 22;
        $words = explode(' ', $string, ($word_limit + 1));
        if (count($words) > $word_limit) {
            array_pop($words);
        }
        return implode(' ', $words).'...';
    }else{
        return $string.'...';
    }
}

function acumec_grid_limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }
    return implode(' ', $words).'...';
}

/* Remove [...] after excerpt text */
function acumec_new_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'acumec_new_excerpt_more');

/**
* Display navigation to next/previous post when applicable.
*
* @since 1.0.0
*/
function acumec_post_nav() {
     global $post,$opt_theme_options;
     
    if(!isset($opt_theme_options['single_post_nav']) ||  ( isset($opt_theme_options['single_post_nav']) && !$opt_theme_options['single_post_nav']))
        return;

    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
        return;
    ?>
	<nav class="navigation post-navigation">
		<div class="row nav-links clearfix">
			<?php
			$prev_post = get_previous_post();
			if (!empty( $prev_post )): 
            $thumbnail_bg = get_the_post_thumbnail_url($prev_post->ID, 'medium');
            $style='';
            if ( $thumbnail_bg ) {
                $style = 'style="background-image:url('.$thumbnail_bg.'); background-size: cover;"';
            }
            ?>
            <div class="col-sm-6">
                <div class="post-nav-wrap text-center" <?php echo ''.$style;?>>
                <a class="post-prev left" href="<?php echo esc_url(get_permalink( $prev_post->ID )); ?>"><?php echo esc_html__('Previous','acumec'); ?></a>
                <h3><a href="<?php echo esc_url(get_permalink( $prev_post->ID )); ?>"><?php echo esc_attr(get_the_title($prev_post->ID));?></a></h3>
                </div>  
            </div>
			<?php endif; ?>
            
			<?php
			$next_post = get_next_post();
			if ( is_a( $next_post , 'WP_Post' ) ) { 
    			$thumbnail_bg = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
                $style='';
                if ( $thumbnail_bg ) {
                    $style = 'style="background-image:url('.$thumbnail_bg.'); background-size: cover;"';
                }
                 ?>
                <div class="col-sm-6 text-right">
                    <div class="post-nav-wrap text-center" <?php echo ''.$style;?>>
    			     <a class="post-next right" href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><?php echo esc_html__('Next','acumec'); ?></a>
                     <h3><a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><?php echo esc_attr(get_the_title($next_post->ID));?></a></h3>
                    </div>  
                </div>  
			<?php } ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}


/**
 * Move comment form field to bottom
 */
function acumec_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'acumec_comment_field_to_bottom' ); 


/* Add Custom Comment */
function acumec_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
    ?>
    <<?php echo esc_attr($tag) ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
    <?php endif; ?>
    	
	    <div class="comment-author-image vcard">
	    	<?php echo get_avatar( $comment, 100 ); ?>
	    </div>
		<div class="comment-meta commentmetadata">
			<div class="comment_metadata">
				<div class="comment-author">
					<h5 class="comment-author-title"><?php echo get_comment_author_link(); ?>
					<span class="comment-date">
						<?php
							echo esc_attr(get_comment_date('F d, Y')).' at '.esc_attr(get_comment_date('g:i a'));
							// printf( _x( '%s ago', '%s = human-readable time difference', 'acumec' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); 
						?>
					</span>
					</h5>
					
		    	</div>
		    	<?php if ( $comment->comment_approved == '0' ) : ?>
		    	<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.' , 'acumec'); ?></em>
			    <?php endif; ?>

			    <div class="comment-main">
			    	<div class="comment-content">
			    		<?php comment_text(); ?>
			    		
			    	</div>
			    </div>
			</div>	
		</div>
    
    
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
    <?php
}

/* core functions. */
require_once( get_template_directory() . '/inc/functions.php' );

function acumec_generate_uiqueid( $length = 6 )
{
    return substr( md5( microtime() ), rand( 0, 26 ), $length );
}
/**
 * theme actions.
 */

/* add footer back to top. */
add_action('wp_footer', 'acumec_footer_back_to_top');

/* Woo commerce function */
if(class_exists('Woocommerce')){
    require get_template_directory() . '/woocommerce/wc-template-hooks.php';
}

function acumec_get_limit_str($str,$start,$limit,$add_tag = '...')
{
    $str = trim($str);
    if(strlen($str) <= $limit)
        return $str;
    return substr(wp_strip_all_tags($str),$start,$limit).$add_tag;
}
/** W3C Validator */ 
add_filter('style_loader_tag', 'acumec_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'acumec_remove_type_attr', 10, 2);
function acumec_remove_type_attr($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

add_filter('revslider_add_setREVStartSize', 'acumec_rev_remove_type_attr', 10, 2);
function acumec_rev_remove_type_attr($tag) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

/**
 * Support GutenBerg
 * @since 2.0
*/
require( get_template_directory() . '/inc/gutenberg/gutenberg.php' );
/* HTML function Theme check validator */
function acumec_html($html){
    return $html;
}

function acumec_check_plugin_version($class, $plugin_file_path){
	if( !function_exists('get_plugin_data') ){
	    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
    $plugin = class_exists($class) ? get_plugin_data(WP_PLUGIN_DIR.'/'.$plugin_file_path) : ['Version'=> '0'];
    $plugin['Version'] = str_replace('.', '', $plugin['Version']);
    return (int)$plugin['Version'];
}
function acumec_admin_notice(){
    $ef4_version = acumec_check_plugin_version('EF4Framework','ef4-framework/ef4-framework.php');
    if(class_exists('EF4Framework') && $ef4_version < 210){
        ?>
        <div class="notice notice-error ef4-notice">
            <?php  ?>
            <p>
                <strong>
                    <?php echo esc_html__('EF4 Framework plugin using is out of date.','acumec');?>
                </strong>
            </p>
            <p>
                <?php printf(__('<strong><a href="%s">Click here</a></strong> to Deactive and then Delete EF4 Framework to update it to latest version','acumec'),
                    esc_url(admin_url('plugins.php'))
                ); 
                ?>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'acumec_admin_notice', 0);

/**
 * Check framework/sytem plugin version to compatible with theme version
 * Action Deactive plugin
 * @since 2.0
*/
function deactivate_plugin_conditional() {
    $ef4_version = acumec_check_plugin_version('EF4Framework', 'ef4-framework/ef4-framework.php');
    if ( class_exists('EF4Framework') && $ef4_version < 210 ) {
        deactivate_plugins('ef4-framework/ef4-framework.php');    
    }
}
add_action( 'admin_init', 'deactivate_plugin_conditional' );

/**
 * Add Admin style
*/
add_action('admin_enqueue',function(){
	$admin_style = '<style>.ef4-notice strong{font-size: 30px;color: red;}</style>';
	return $admin_style;
});

/* Remvoe EF4 Settings */
add_filter('ef4_show_settings_menu', '__return_false');