<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 10/31/2017
 * Time: 3:39 PM
 */

$libs_use = [
    'ef4-metafiles',
    'ef4-userinfo',
    'ef4-metabox',
    'ef4-meta-template',
    'ef4-unit',
];
foreach ($libs_use as $lib) {
    require_once "inc/{$lib}.php";
}

class EF4Service
{
    protected static $instance;
    const UNDEFINED = 'undefined';
    public $plugin_name;
    public $plugin_dir;
    public $plugin_url;
    public $metabox;
    public $metafiles;
    protected static $admin_tabs;
    protected static $has_admin_tabs;

    public function __construct()
    {
        $this->init();
        do_action('ef4_service_loaded');
    }
    public function register_assets()
    {
        wp_register_style('jquery-datetimepicker', EF4Functions::asset('/css/jquery.datetimepicker.css'));
        wp_register_script('jquery-datetimepicker', EF4Functions::asset('/js/jquery.datetimepicker.js'));
    }
    public function register_admin_assets()
    {
        wp_register_style('ef4_service_admin_css',EF4Functions::asset('css/ef4_service.css'),array(),'1.0.0');
        wp_register_script('ef4_metafiles_admin_js',EF4Functions::asset('js/ef4_metafiles_admin.js'),array('jquery'),'1.0.0',true);
        wp_register_script('ef4_metabox_admin_js',EF4Functions::asset('js/ef4_service_admin_settings.js'),array('jquery'),'1.0.0',true);
        wp_register_style('jquery-datetimepicker', EF4Functions::asset('/css/jquery.datetimepicker.css'));
        wp_register_script('jquery-datetimepicker', EF4Functions::asset('/js/jquery.datetimepicker.js'));
    }
    public function plugin_dir_path($path)
    {
        $raw_path = plugin_dir_path(str_replace('\\','/',$path));
        $temp = explode('wp-content/plugins/',$raw_path);
        $temp2 = explode('/',$temp[1]);
        $plugin_dir = $temp2[0];
        $remove_path = substr($temp[1],strlen($plugin_dir));
        if(!empty($temp[1]))
            $raw_path = substr($raw_path,0,strlen($remove_path)*-1);
        return trailingslashit($raw_path);
    }
    public function plugin_dir_url($path)
    {
        $raw_path = plugin_dir_url($path);
        $temp = explode('wp-content/plugins/',$raw_path);
        $temp2 = explode('/',$temp[1]);
        $plugin_dir = $temp2[0];
        $remove_path = substr($temp[1],strlen($plugin_dir));
        if(!empty($temp[1]))
            $raw_path = substr($raw_path,0,strlen($remove_path)*-1);
        return trailingslashit($raw_path);
    }
    public function enqueue_admin_assets()
    {
        wp_enqueue_script('jquery-datetimepicker');
        wp_enqueue_style('jquery-datetimepicker');
        wp_enqueue_style('ef4_service_admin_css');
        wp_enqueue_script('ef4_metabox_admin_js');
        wp_enqueue_script('ef4_metafiles_admin_js');
    }
    private function init()
    {
        if ($this->is_direct_call()) {
            add_action('all_admin_notices', array($this, 'show_admin_tabs'));
            add_action('admin_enqueue_scripts',array($this,'register_admin_assets'),1);
            add_action('admin_enqueue_scripts',array($this,'enqueue_admin_assets'),15);
            add_action('wp_enqueue_scripts',array($this,'register_assets'),1);
        }
    }
    public function get_metabox($post_type = '')
    {
        if(empty($post_type) && count($this->metabox) === 1)
        {
            return end($this->metabox);
        }
        if(!empty($this->metabox[$post_type]))
            return $this->metabox[$post_type];
        return false;
    }
    public function get_metafiles($post_type = '')
    {
        if(empty($post_type) && count($this->metafiles) === 1)
        {
            return end($this->metafiles);
        }
        if(!empty($this->metafiles[$post_type]))
            return $this->metafiles[$post_type];
        return false;
    }
    public static function instance()
    {
        if (!(self::$instance instanceof self))
            self::$instance = new self();
        return self::$instance;
    }
    public function defines(array $arr)
    {
        foreach ($arr as $key => $value)
            $this->define($key, $value);
    }
    public function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
    public function sort_admin_tabs($a, $b)
    {
        if (!array_key_exists('priority', $a)) {
            $a['priority'] = 10;
        }

        if (!array_key_exists('priority', $b)) {
            $b['priority'] = 10;
        }

        if ($a['priority'] == $b['priority']) {
            return 0;
        }

        return $a['priority'] < $b['priority'] ? -1 : 1;
    }
    public function register_admin_tabs(array $tab_info_list)
    {
        foreach ($tab_info_list as $tab_info)
            $this->register_admin_tab($tab_info);
    }
    public function register_admin_tab($tab_info)
    {
        if (!$this->validate($tab_info, 'array') || !$this->validate($tab_info['id']))
            return false;
        if (!$this->validate(self::$admin_tabs, 'array'))
            self::$admin_tabs = array();
        self::$admin_tabs[$tab_info['id']] = $tab_info;
    }
    public function show_admin_tabs()
    {
        if (self::$has_admin_tabs)
            return;
        $current_page_id = get_current_screen()->id;
        $current_user = wp_get_current_user();
        if (!is_admin() || !in_array('administrator', $current_user->roles)) {
            return;
        }
        $admin_tabs = $this->validate(self::$admin_tabs, 'array') ? self::$admin_tabs : array();
        $admin_tabs = apply_filters('ef4_admin_tabs_info', $admin_tabs);
        usort($admin_tabs, array($this, 'sort_admin_tabs'));
        $has_tab = false;
        ob_start(); ?>
        <h2 class="nav-tab-wrapper ef4-nav-tab-wrapper">
            <?php foreach ($admin_tabs as $tab) :
                if (!empty($tab['pages']) && !in_array($current_page_id, $tab['pages']))
                    continue;
                $has_tab = true;
                $class = ($tab["id"] == $current_page_id) ? "nav-tab nav-tab-active" : "nav-tab";
                ?><a href="<?php echo esc_url(admin_url($tab["link"])) ?>"
                     class="<?php echo esc_attr($class) ?>  nav-tab-<?php echo esc_attr($tab["id"]) ?>">
                <?php echo esc_html($tab["name"]) ?></a>
            <?php endforeach ?>
        </h2> <?php
        $output = ob_get_clean();
        if ($has_tab) {
            echo $output;
        }
        self::$has_admin_tabs = true;
    }
    public function get_var($var_name, $stack, $default = '')
    {
        if (is_array($stack) && key_exists($var_name, $stack))
            return $stack[$var_name];
        if (is_object($stack) && property_exists($stack, $var_name))
            return $stack->$var_name;
        return $default;
    }
    public function merge_name(...$args)
    {
        $result = '';
        foreach ($args as $str) {
            if (empty($str))
                continue;
            $result .= '_' . trim($str, '_');
        }
        return trim($result, '_');
    }
    public function merge_path($path1, ...$paths)
    {
        $result = untrailingslashit($path1);
        foreach ($paths as $path) {
            $use_path = $path;
            if (substr($path, 0, 1) === '/')
                $use_path = substr($path, 1);
            $result .= '/' . $use_path;
        }
        return ($result);
    }
    public function validate($val, $type = 'string', $not_empty = true)
    {
        if ($not_empty && empty($val))
            return false;
        switch ($type) {
            case 'string':
                if (!is_string($val))
                    return false;
                break;
            case 'array':
                if (!is_array($val))
                    return false;
                break;
            default:
                break;
        }
        return true;
    }
    public function validates(array $vals, $type = 'string', $not_empty = true)
    {
        foreach ($vals as $val) {
            if (!$this->validate($val, $type, $not_empty))
                return false;
        }
        return true;
    }

    public function register_metafiles($post_type)
    {
        if ($this->is_direct_call())
            return false;
        if (empty($this->metafiles) || !is_array($this->metafiles))
            $this->metafiles = array();
        if (!empty($this->metafiles[$post_type]) && ($this->metafiles[$post_type] instanceof EF4MetaFiles))
            return $this->metafiles;
        $this->metafiles[$post_type] = new EF4MetaFiles($this, $post_type);
        return $this->metafiles[$post_type];
    }

    public function register_metabox($post_type)
    {
        if ($this->is_direct_call())
            return false;
        if (empty($this->metabox) || !is_array($this->metabox))
            $this->metabox = array();
        if (!empty($this->metabox[$post_type]) && ($this->metabox[$post_type] instanceof EF4Metabox))
            return $this->metabox;
        $this->metabox[$post_type] = new EF4Metabox($this, $post_type);
        return $this->metabox[$post_type];
    }
    public function get_template($template,array $params = array())
    {
       extract($params);
       $theme_path = $this->merge_path(get_template_directory(),$this->plugin_name,$template).'.php';
       if(file_exists($theme_path))
            include $theme_path;
       else
       {
           $path = $this->merge_path($this->plugin_dir,'templates',$template).'.php';
           if(file_exists($path))
               include $path;
       }
    }
    public function assets($path)
    {
        if(file_exists($this->merge_path(get_template_directory(),'assets',$this->plugin_name,$path)))
            return $this->merge_path(get_template_directory_uri(),'assets',$this->plugin_name,$path);
        else
            return $this->merge_path($this->plugin_url, 'assets', $path);
    }

    public function assets_dir($path)
    {
        return $this->merge_path($this->plugin_dir, 'assets', $path);
    }

    public function is_direct_call()
    {
        if (empty($this->plugin_name) || $this->plugin_name == self::UNDEFINED)
            return true;
        else
            return false;
    }

    public function get_unit($unit_name)
    {
        $unit_obj = EF4Unit::instance($this);
        return $unit_obj->get($unit_name);
    }

    public function is_user_has_any_caps($caps)
    {
        $caps_check = (is_array($caps)) ? $caps : array($caps);
        $check = (in_array('guest', $caps_check)) ? true : false;
        foreach ($caps_check as $cap) {
            if (current_user_can($cap)) {
                $check = true;
                break;
            }
        }
        return $check;
    }

    public function title_case($text)
    {
        $text = preg_replace('/[-_ ]+/', ' ', $text);
        $text = trim($text);
        $text_segs = explode(' ', $text);
        $text = '';
        foreach ($text_segs as $seg) {
            $text .= ' ' . strtoupper(substr($seg, 0, 1)) . substr($seg, 1);
        }
        return trim($text);
    }
}

function ef4_service()
{
    global $ef4_service;
    if (empty($ef4_service))
        $ef4_service = EF4Service::instance();
    return $ef4_service;
}
ef4_service();
