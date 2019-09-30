<?php
/**
 * demo data.
 *
 * config.
 */
add_filter('ef3-theme-options-opt-name', 'acumec_set_demo_opt_name');

function acumec_set_demo_opt_name(){
    return 'opt_theme_options';
}

add_filter('ef3-replace-content', 'acumec_replace_content', 10 , 2);

function acumec_replace_content($replaces, $attachment){
    return array(
        '/tax_query:/' => 'remove_query:',
        '/categories:/' => 'remove_query:', 
    );
}

add_filter('ef3-replace-theme-options', 'acumec_replace_theme_options');

function acumec_replace_theme_options(){
    return array(
        'dev_mode' => 0,
    );
}
add_filter('ef3-enable-create-demo', 'acumec_enable_create_demo');

function acumec_enable_create_demo(){
    return false;
}

add_action('ef3-import-finish', 'acumec_set_woo_page');
/**
 * move post to trash
 */
add_action('ef3-import-start', 'acumec_move_trash', 1);
if(!function_exists('acumec_move_trash')){
    function acumec_move_trash(){
        wp_trash_post(1);
        wp_trash_post(2);
    }
}
add_action('ef3-export-finish', 'acumec_export_extra_option');
function acumec_export_extra_option($folder_dir){
    global $wp_filesystem;
    $file = $folder_dir . 'extra-options.json';
    $options = [
        'date_format',
        'time_format',
        'default_category',
        'posts_per_page',
        'show_on_front',
        'page_on_front',
        'page_for_posts',
        'wp_user_roles'
    ];
    
    $file_contents = array();
    foreach ( $options as $option_name ) {
        $file_contents[ $option_name ] = get_option( $option_name );
    }
    if ( $file_contents !== false ) {
        $file_contents = json_encode( $file_contents );
        $wp_filesystem->put_contents( $file, $file_contents, FS_CHMOD_FILE );
    }
}
/* Replace dev site url with curren site url 
 * replace in content options, post meta
 * 
*/
add_action('ef3-import-start', 'acumec_import_start', 10, 2);
function acumec_import_start($id, $part){
    global $wp_filesystem;
    if ( class_exists('EF4Framework') ) {
        /* replace content url */
        $file_content = $part . 'content/content-data.xml';
        $data_content = file_ef4_get_contents($file_content);
        $data_content = preg_replace(
            array(
                '/http:\/\/dev\.joomexp\.com\/wordpress\/acumec/',
            ), 
            site_url(), 
            $data_content
        );
        $wp_filesystem ->put_contents($file_content, $data_content);
        /* replace attach file url */
        $file_attach = $part . 'content/attachment-data.xml';
        $data_attach = file_ef4_get_contents($file_attach);
        $data_attach = preg_replace(
            array(
                '/http:\/\/dev\.joomexp\.com\/wordpress\/acumec/',
            ), 
            site_url(), 
            $data_attach
        );
        $wp_filesystem ->put_contents($file_attach, $data_attach);
    }
}
add_action('ef3-import-start', 'acumec_move_trash', 1);
if(!function_exists('acumec_move_trash')){
    function acumec_move_trash(){
        wp_trash_post(1);
        wp_trash_post(2);
    }
}
/**
 * Extra option 
 * Update option for Extensions option like: WooCommerce, Newsletter, ...
 *
*/
add_action('ef3-import-finish', 'acumec_import_extra_option',1,2);
function acumec_import_extra_option($id,$folder_dir){
    $file = $folder_dir . 'extra-options.json';
    if ( file_exists( $file ) && class_exists('EF4Framework') ) {
        $file_contents = json_decode( file_ef4_get_contents( $file ), true );
        foreach ( $file_contents as $option_name => $option_values ) {
            update_option( $option_name, $option_values );
        }
    }
}
/**
 * Set woo page.
 *
 * get array woo page title and update options.
 *
 * @author Chinh Duong Manh
 * @since 1.0.0
 */
function acumec_set_options_page($id,$folder_dir){
    $opt_pages = array(
        'page_on_front'                 => 'Home 1',
        'wp_page_for_privacy_policy'    => 'Privacy Policy',
        'woocommerce_shop_page_id'      => 'Shop',
        'woocommerce_cart_page_id'      => 'Cart',
        'woocommerce_checkout_page_id'  => 'Checkout',
        'woocommerce_myaccount_page_id' => 'My Account',
        'woocommerce_terms_page_id'     => 'Terms and conditions',
    );
 
    foreach ($opt_pages as $key => $opt_page){
        $page = get_page_by_title($opt_page);
        if(!isset($page->ID))
            continue;
        update_option($key, $page->ID);
    }
}
add_action('ef3-import-finish', 'acumec_set_options_page');
/**
 * Crop image
 */
add_action('ef3-import-finish', 'acumec_crop_images',99);
function acumec_crop_images() {
    $query = array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'inherit',
    );
    $media = new WP_Query($query);
    if ($media->have_posts()) {
        foreach ($media->posts as $image) {
            if (strpos($image->post_mime_type, 'image/') !== false) {
                $image_path = get_attached_file($image->ID);
                $metadata = wp_generate_attachment_metadata($image->ID, $image_path);
                wp_update_attachment_metadata($image->ID, $metadata);
            }
        }
    }
}