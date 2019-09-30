<?php
if (!defined('ABSPATH')) {
    //First catches the Apache users
    header("HTTP/1.0 404 Not Found");
    //This should catch FastCGI users
    header("Status: 404 Not Found");
    die();
}

class EF4CMSAction
{
    public static $is_added = false;

    public static function add_action()
    {
        $self = new self();
        $self->do_add_action();
    }

    public function do_add_action()
    {
        if (self::$is_added)
            return;
        self::$is_added = true;

        if ( !class_exists('WP_Filesystem') ) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
        add_action('vc_before_init',array($this,'cms_add_class_libs'));
        add_action('vc_plugins_loaded', array($this, 'cms_add_default_params'));
        add_action('vc_after_init', array($this, 'cmsShortcodeAddParams'),11);
        add_action('vc_after_init', array($this, 'cms_add_more_shortcode'));
    }

    function cms_add_class_libs()
    {
        $class_libs_use = array(
            'cms_base',
            'ef4_base'
        );
        $class_libs_use = apply_filters('cms-class-libs-list',$class_libs_use);
        foreach ($class_libs_use as $class)
            $this->require_file(CMS_DIR.'class/'.$class.'.php');

    }
    function cms_add_default_params()
    {
        //add shortcode param
        $shortcode_params_file = array(
            'cms_template',
            'cms_template_img',
            'img'
        );
        $shortcode_params_file = apply_filters('cms-shortcode-types-list', $shortcode_params_file);
        foreach ($shortcode_params_file as $type)
            $this->require_file(CMS_INCLUDES . 'types/' . $type . '.php');
        //add icon lib
        $icon_libs_file = array(
            'pe7stroke',
            //'glyphicons',
            'rticon'
        );
        $icon_libs_file = apply_filters('cms-icon-libs-list', $icon_libs_file);
        foreach ($icon_libs_file as $lib)
            $this->require_file(CMS_INCLUDES . 'fontlibs/' . $lib . '.php');
    }
    function cms_add_more_shortcode()
    {
        //add more shorcode
        $shortcodes = array(
            'cms_carousel',
            'cms_grid',
            'cms_fancybox',
            'cms_fancybox_single',
            'cms_counter',
            'cms_progressbar',
            //
            'ef4_cms_grid',
        );
        $shortcodes = apply_filters('cms-shorcode-list', $shortcodes);
        foreach($shortcodes as $shortcode)
            $this->require_file(CMS_DIR . 'shortcodes/' . $shortcode . '.php');
    }
    function cmsShortcodeAddParams(){
        $extra_params_folder = get_template_directory() . '/vc_params';
        $files = EF4Functions::cmsFileScanDirectory($extra_params_folder,'/cms_.*\.php/');
        if(!empty($files)){
            foreach($files as $file){
                if(WPBMap::exists($file->name)){
                    if(isset($params)){
                        unset($params);
                    }
                    include $file->uri;
                    if(isset($params) && is_array($params)){
                        foreach($params as $param){
                            if(is_array($param)){
                                $param['group'] = __('Template', CMS_NAME);
                                $param['edit_field_class'] = isset($param['edit_field_class'])? $param['edit_field_class'].' cms_custom_param vc_col-sm-12 vc_column':'cms_custom_param vc_col-sm-12 vc_column';
                                $param['class'] = 'cms-extra-param';
                                if(isset($param['template']) && !empty($param['template'])){
                                    if(!is_array($param['template'])){
                                        $param['template'] = array($param['template']);
                                    }
                                    $param['dependency'] = array("element"=>"cms_template", "value" => $param['template']);

                                }
                                vc_add_param($file->name, $param);
                            }
                        }
                    }
                }
            }
        }
    }
    protected function require_file($path)
    {
        if (file_exists($path))
            require_once $path;
    }
}