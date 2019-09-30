<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 4/27/2018
 * Time: 3:49 PM
 */
namespace ef4;
class Settings
{
    const MENU_SLUG = 'ef4-settings';
    protected $default_settings =[
        'main'=>'',
        'custom'=>''
    ];
    public function init()
    {
        add_action('admin_menu', [$this,'register_menu_page'] );
        add_filter('ef4_get_option',[$this,'filter_get_options'],20,2);
        add_filter('ef4_custom_get_settings',[$this,'filter_get_custom_options'],20,2);
    }
    public function after_init()
    {
        $this->load_default_settings();
        $this->load_default_custom_settings();
    }
    function filter_get_custom_options($value = '', $args)
    {
        $args = wp_parse_args($args, [
            'post_type' => '',
            'name'      => '',
        ]);
        $post_type = $args['post_type'];
        $name = $args['name'];
        $is_success = false;
        if(is_array($value))
            $value = $this->maybe_assign_array_key($value,$name,$is_success);
        $value = $this->maybe_sort_dynamic_table_data($value);
        if(!is_array($this->default_settings['custom']))
            $this->default_settings['custom'] = [];
        if(!array_key_exists($post_type,$this->default_settings['custom']))
            return $value;
        if(!is_array($this->default_settings['custom'][$post_type]))
            $this->default_settings['custom'][$post_type] = [];
        if(!array_key_exists($name,$this->default_settings['custom'][$post_type]))
            return $value;
        $default_setting_force = $this->default_settings['custom'][$post_type][$name];
        if(is_array($default_setting_force))
        {
            $is_dynamic_table_data  = false;
            foreach ($default_setting_force as  $key => $default)
            {
                if(is_array($default))
                    $is_dynamic_table_data = true;
                break;
            }
            if(!$is_dynamic_table_data)
            {
                return array_merge($value,$default_setting_force);
            }
            foreach ($default_setting_force as $key => $default)
            {
                $current_value = (isset($value[$key]) && is_array($value[$key])) ? $value[$key] : [];
                $current_value = $this->parse_custom_record_struc($current_value,$name);
                $value[$key] = array_merge($current_value,$default);
            }
            return $this->maybe_sort_dynamic_table_data($value);
        }
        return $value = $default_setting_force;
    }
    function parse_custom_record_struc($record=[],$name)
    {
        $struc = apply_filters('ef4_custom_get_data_structure',[],$name);
        if(empty($struc))
            return $record;
        $keys = [];
        foreach($struc as $field)
        {
            $keys[] = $field['id'];
        }
        foreach ($keys as $key)
        {
            if(!array_key_exists($key,$record))
                $record[$key] = '';
        }
        return $record;
    }
    function maybe_sort_dynamic_table_data($raw)
    {
        if(!is_array($raw))
            return $raw;
        $is_dynamic_table = true;
        $sort = [];
        $keys = array_keys($raw);
        sort($keys);
        foreach ($keys as $key)
        {
            if(!is_string($key) && is_numeric($key))
            {
                $is_dynamic_table = false;
                break;
            }
            $sort[$key] = $raw[$key];
        }
        if($is_dynamic_table)
            return $sort;
        return $raw;
    }
    function maybe_assign_array_key($raw = [],$name = '', &$is_success)
    {
        $check = false;
        if(!is_array($raw))
            return [];
        foreach ($raw as $key => $value)
        {
            if(!is_string($key) && is_numeric($key))
            {
                $check = true ;
                break;
            }
        }
        $result = $raw;
        if($check)
        {
            $struc = apply_filters('ef4_custom_get_data_structure',[],$name);
            if(empty($struc))
                return $raw;
            $key_name = '';
            foreach ($struc as $field)
            {
                if(!empty($field['is_key']))
                {
                    $key_name = $field['id'];
                    break;
                }
            }
            if(empty($key_name))
                return $raw;
            $result = [];
            foreach ($raw as $key => $record)
            {
                $result[$record[$key_name]] = $record;
            }
            $is_success = true;
        }
        return $result;
    }
    function filter_get_options($value,$name)
    {
        if(empty($this->default_settings['main']))
            return $value;
        $default = $this->default_settings['main'];
        if(!array_key_exists($name,$default))
            return $value;
        $settings_type = apply_filters('ef4_settings_options_allow',[]);
        $type = (isset($settings_type[$name])) ? $settings_type[$name] : '';
        $attach = $default[$name];
        switch ($type)
        {
            case 'array':
                if(!is_array($value))
                    $value = explode(',',$value);
                if(!is_array($attach))
                    $attach = explode(',',$attach);
                $result = array_merge($value,$attach);
                $result = array_unique($result);
                $result = join(',',$result);
                break;
            default :
                $result = $attach;
                break;
        }
        return $result;
    }
    function load_default_settings()
    {
        $dir = get_template_directory().'/ef4-framework/';
        $map = [
            'main'=>$dir.'settings.php',
        ];
        foreach ($map as $key => $path)
        {
            $this->default_settings[$key] = $this->maybe_take_setting_from_file($path);
        }
    }
    function load_default_custom_settings()
    {
        $prefix = 'ef4-framework/post_type/';
        $theme = wp_get_theme();
        $files = $theme->get_files('json',4, true);
        $defaults = [];
        foreach ($files as $relative => $path)
        {
            if(strpos($relative,$prefix) !== 0 )
                continue;
            $name = substr($relative,strlen($prefix),-5);
            if(sanitize_title($name) !== $name)
                continue;
            $defaults[$name] = $path;
        }
        $settings = [];
        foreach ($defaults as $post_type => $path)
        {
            $content = file_get_contents($path);
            $data = @json_decode(trim($content),true);
            if(!is_array($data))
                continue;
            $settings[$post_type] = $data;
        }
        if(!is_array($this->default_settings))
            $this->default_settings = [];
        $this->default_settings['custom']=$settings;
    }
    function maybe_take_setting_from_file($path)
    {
        $result = [];
        if(file_exists($path))
            $result = require_once $path;
        return $result;
    }
    function register_menu_page() {
        $dev_mode = apply_filters('ef4_show_settings_menu',true);
        if(!$dev_mode)
            return;
        $setting_page = self::MENU_SLUG;
        add_menu_page(
            __('EF4Framework Settings','ef4-framework'),
            __('EF4Framework','ef4-framework'),
            'manage_options',
            $setting_page,
            array($this,'page_settings'),
            '',
            98
        );
        //custom menu
        $post_types = apply_filters('ef4_custom_menu_settings',[]);
        if(!empty($post_types))
        {
            add_submenu_page(
                $setting_page,
                __('Custom Settings','ef4-framework'),
                __('Custom Settings','ef4-framework'),
                'manage_options',
                ef4()->merge_name(self::MENU_SLUG,'main'),
                [$this, 'page_custom_menu_setting']);
        }
        do_action('ef4_admin_menu_register',$setting_page);
    }
    function page_custom_menu_setting()
    {
        if(empty($_GET['page']) || $_GET['page']!==  ef4()->merge_name(self::MENU_SLUG,'main'))
            return;
        ef4()->get_templates('admin/cpt-settings.php') ;
    }
    function page_settings()
    {
        ef4()->get_templates('admin/settings.php') ;
    }
}
