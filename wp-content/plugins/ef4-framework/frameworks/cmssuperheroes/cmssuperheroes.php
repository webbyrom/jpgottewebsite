<?php
/**
 * Author: Luan Nguyen, Cong Nguyen , Vhieu
 * Author URI: http://ncluan.com
 * Copyright 2007-2014 Cmssuperheroes.com. All rights reserved.
 */
if (!defined('ABSPATH')) {
    //First catches the Apache users
    header("HTTP/1.0 404 Not Found");
    //This should catch FastCGI users
    header("Status: 404 Not Found");
    die();
}
new CmssuperheroesCore();
class CmssuperheroesCore
{
    public function __construct()
    {
        /**
         * Init function, which is run on site init and plugin loaded
         */
        require_once CMS_DIR . '/helper/action.php';
        EF4CMSAction::add_action();
        load_plugin_textdomain(CMS_NAME, false, CMS_LANGUAGES);
        add_filter('style_loader_tag', array($this, 'cmsValidateStylesheet'));

        /**
         * Enqueue Scripts on plugin
         */
        add_action('wp_enqueue_scripts', array($this, 'cms_register_style'));
        add_action('wp_enqueue_scripts', array($this, 'cms_register_script'));
        add_action('admin_enqueue_scripts', array($this, 'cms_admin_script'));
        /**
         * widget text apply shortcode
         */
        add_filter('widget_text', 'do_shortcode');
    }

    /**
     * replace rel on stylesheet (Fix validator link style tag attribute)
     */
    function cmsValidateStylesheet($src)
    {
        if (strstr($src, 'widget_search_modal-css') || strstr($src, 'owl-carousel-css') || strstr($src, 'vc_google_fonts')) {
            return str_replace('rel', 'property="stylesheet" rel', $src);
        } else {
            return $src;
        }
    }

    /**
     * Function register script on plugin
     */
    function cms_register_script()
    {
        wp_register_script('modernizr', CMS_JS . 'modernizr.min.js', array('jquery'));
        wp_register_script('waypoints', CMS_JS . 'waypoints.min.js', array('jquery'));
        wp_register_script('imagesloaded', CMS_JS . 'jquery.imagesloaded.js', array('jquery'));
        wp_register_script('jquery-shuffle', CMS_JS . 'jquery.shuffle.js', array('jquery', 'modernizr', 'imagesloaded'));
        wp_register_script('cms-jquery-shuffle', CMS_JS . 'jquery.shuffle.cms.js', array('jquery-shuffle'));
        wp_register_script('ef4_cms_grid', CMS_JS . 'ef4_cms_grid.js', array('jquery-shuffle'), '1.0.0', true);
    }

    function cms_admin_script()
    {
        wp_enqueue_style('font-stroke7', CMS_CSS . 'Pe-icon-7-stroke.css', array(), '1.2.0');
        wp_enqueue_style('font-rticon', CMS_CSS . 'rticon.css', array(), '1.0.0');
    }

    function cms_register_style()
    {
        wp_register_style('font-stroke7', CMS_CSS . 'Pe-icon-7-stroke.css', array(), '1.2.0');
        wp_register_style('font-rticon', CMS_CSS . 'rticon.css', array(), '1.0.0');
    }
}