<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/23/2017
 * Time: 4:47 PM
 */
if (!defined('ABSPATH')) {
    //First catches the Apache users
    header("HTTP/1.0 404 Not Found");
    //This should catch FastCGI users
    header("Status: 404 Not Found");
    die();
}

class EF4Functions
{
    public static $cms_html_id = array();
    public static function get_request($name,$default = '')
    {
        if(isset($_REQUEST[$name]))
            return $_REQUEST[$name];
        return $default;
    }
    public static function get_vc_sorting_allow()
    {
        return array(
            esc_html__('Sort by ID',CMS_NAME)=> 'ID',
            esc_html__('Sort by date',CMS_NAME)=> 'Date',
            esc_html__('Sort by author',CMS_NAME)=> 'author',
            esc_html__('Sort by title',CMS_NAME)=> 'title',
            esc_html__('Sort by modified',CMS_NAME)=> 'modified',
            esc_html__('Random sorting',CMS_NAME)=> 'rand',
            esc_html__('Sort by comments count',CMS_NAME)=> 'comment_count',
            esc_html__('Sort by menu order',CMS_NAME)=> 'menu_order',
        );
    }
    public static function asset($path)
    {
        if(substr($path,0,1)!== '/')
            $path = '/'.$path;
        return untrailingslashit(EF4_URL).'/assets'.$path;
    }
    public static function parse_vc_sorting_allow_value($str_value)
    {
        $result = array();
        if(!(!empty($str_value) && is_string($str_value)))
            return $result;
        $temp = explode(',',$str_value);
        $all_allow = self::get_vc_sorting_allow();
        foreach ($all_allow as $key => $value)
        {
            if(in_array($value,$temp))
                $result[$key] = $value;
        }
        return $result;
    }
    public static function convert_sources_vc_to_array($source)
    {
        $result = array();
        $temp_source = explode('|', $source);
        foreach ($temp_source as $seg) {
            $temp = explode(':', $seg);
            if (count($temp) < 2)
                continue;
            list($key, $val) = $temp;
            $result[$key] = $val;
        }
        return $result;
    }

    public static function convert_array_to_sources_vc(array $arr)
    {
        $result = '';
        $first = true;
        foreach ($arr as $key => $value) {
            if ($first)
                $first = false;
            else
                $result .= '|';
            $result .= $key . ':' . $value;
        }
        return $result;
    }

    public static function cmsFileScanDirectory($dir, $mask, $options = array(), $depth = 0)
    {
        $options += array(
            'nomask'    => '/(\.\.?|CSV)$/',
            'callback'  => 0,
            'recurse'   => TRUE,
            'key'       => 'uri',
            'min_depth' => 0,
        );

        $options['key'] = in_array($options['key'], array('uri', 'filename', 'name')) ? $options['key'] : 'uri';
        $files = array();
        if (is_dir($dir) && $handle = opendir($dir)) {
            while (FALSE !== ($filename = readdir($handle))) {
                if (!preg_match($options['nomask'], $filename) && $filename[0] != '.') {
                    $uri = "$dir/$filename";
                    if (is_dir($uri) && $options['recurse']) {
                        // Give priority to files in this folder by merging them in after any subdirectory files.
                        $files = array_merge(self::cmsFileScanDirectory($uri, $mask, $options, $depth + 1), $files);
                    } elseif ($depth >= $options['min_depth'] && preg_match($mask, $filename)) {
                        // Always use this match over anything already set in $files with the
                        // same $$options['key'].
                        $file = new stdClass();
                        $file->uri = $uri;
                        $file->filename = $filename;
                        $file->name = pathinfo($filename, PATHINFO_FILENAME);
                        $files[$filename] = $file;
                    }
                }
            }
            closedir($handle);
        }
        return $files;
    }

    public static function cmsGetCategoriesByPostID($post_ID = null, $taxo = 'category')
    {
        $term_cats = array();
        $categories = get_the_terms($post_ID, $taxo);
        if ($categories) {
            foreach ($categories as $category) {
                $term_cats[] = get_term($category, $taxo);
            }
        }
        return $term_cats;
    }

    /**
     * Generator unique html id
     * @param $id : string
     */
    public static function cmsHtmlID($id)
    {
        $cms_html_id = self::$cms_html_id;
        $id = str_replace(array('_'), '-', $id);
        if (isset($cms_html_id[$id])) {
            $count = count($cms_html_id[$id]);
            $cms_html_id[$id][$count] = 1;
            $count++;
            self::$cms_html_id = $cms_html_id;
            return $id . '-' . $count;
        } else {
            $cms_html_id[$id] = array(1);
            self::$cms_html_id = $cms_html_id;
            return $id;
        }
    }
}


function cmsGetCategoriesByPostID($post_ID = null, $taxo = 'category')
{
    return EF4Functions::cmsGetCategoriesByPostID($post_ID, $taxo);
}

/**
 * Generator unique html id
 * @param $id  string
 */
function cmsHtmlID($id)
{
    return EF4Functions::cmsHtmlID($id);
}

function cmsFileScanDirectory($dir, $mask, $options = array(), $depth = 0)
{
    return EF4Functions::cmsFileScanDirectory($dir, $mask, $options, $depth);
}


/*
new update 4-mar-2019 
*/
if(!function_exists('register_ef4_widget')){
    function register_ef4_widget($widgets){
        return register_widget($widgets);
    }
}
if(!function_exists('unregister_ef4_widget')){
    function unregister_ef4_widget($widgets){
        return unregister_widget($widgets);
    }
}
if(!function_exists('file_ef4_get_contents')){
    function file_ef4_get_contents($data){
        return file_get_contents($data);
    }
}
if(!function_exists('base64_ef4_decode')){
    function base64_ef4_decode($data){
        return base64_decode($data);
    }
}
if(!function_exists('base64_ef4_encode')){
    function base64_ef4_encode($data){
        return base64_encode($data);
    }
}
if(!function_exists('htmlspecialchars_ef4_decode')){
    function htmlspecialchars_ef4_decode($data){
        return htmlspecialchars_decode($data);
    }
}

if(!function_exists('remove_ef4_filter')){
    function remove_ef4_filter($tag, $function_to_remove, $priority = 10){
        return remove_filter($tag, $function_to_remove, $priority);
    }
}
if(!function_exists('register_ef4_taxonomy')){
    function register_ef4_taxonomy($taxonomy, $object_type, $args){
        return register_taxonomy($taxonomy, $object_type, $args);
    }
}
if(!function_exists('register_ef4_post_type')){
    function register_ef4_post_type( $post_type, $args ){
        return register_post_type($post_type, $args);
    }
}

if(!function_exists('add_ef4_shortcode')){
    function add_ef4_shortcode($tag , $func ){
        return add_shortcode( $tag , $func );
    }
}

if(!function_exists('vc_add_ef4_shortcode_param')){
    function vc_add_ef4_shortcode_param($tag , $func ){
        return vc_add_shortcode_param( $tag , $func );
    }
}

if(!function_exists('disable_ef4_wp_calculate_image_src_set')){
    function disable_ef4_wp_calculate_image_src_set(){
        return add_filter( 'wp_calculate_image_srcset' , function(){return false;} );
    }
}

/**
 * Get plugin version
*/
function ef4_version(){
    $plugins_data = get_plugin_data( __FILE__);
    return $plugins_data['Version'];
}


/**
 * Dequeue some script/ style from 3rd plugin.
 *
 * Hooked to the wp_enqueue_scripts action, with a late priority (100),
 * so that it is after the script was enqueued.
 */
if(!function_exists('ef4_remove_scripts')){
    function ef4_remove_scripts($scripts=[]) {
        $scripts = apply_filters('ef4_remove_scripts', $scripts);
        if(empty($scripts)) return;
        foreach ($scripts as $script) {
            wp_dequeue_script($script);
            wp_deregister_script($script);
            wp_dequeue_style($script);
            wp_deregister_style($script);
        }
    }
    add_action( 'wp_enqueue_scripts', 'ef4_remove_scripts', 100 );
}

/* W3C Validator */
if(!function_exists('ef4_remove_type_attr')){
    add_filter('style_loader_tag', 'ef4_remove_type_attr', 10, 2);
    add_filter('script_loader_tag', 'ef4_remove_type_attr', 10, 2);
    function ef4_remove_type_attr($tag, $handle) {
        return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
    }
}
if(!function_exists('ef4_rev_remove_type_attr')){
    add_filter('revslider_add_setREVStartSize', 'ef4_rev_remove_type_attr', 10, 2);
    function ef4_rev_remove_type_attr($tag) {
        return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
    }
}