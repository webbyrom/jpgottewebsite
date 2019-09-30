<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 4/27/2018
 * Time: 10:19 AM
 */
require_once 'base.php';
require_once 'add/payments/init.php';
require_once 'add/custom-metabox/init.php';
class EF4Core extends EF4Base
{
    private static $instance;
    protected static $lib = [];
    private static $is_inited = false;
    const SETTINGS_PREFIX = 'ef4';

    public static function instance()
    {
        if (self::$instance instanceof self)
            return self::$instance;
        self::$instance = new self();
        self::$instance->init();
        return self::$instance;
    }

    public function init()
    {
        if (self::$is_inited)
            return;
        parent::init();
        $this->load_payment_api_lib();
        $this->load_abs_lib();
        $this->load_lib();

        // for some action need lib loaded
        $this->after_init();
    }
    function after_init(){
        foreach (self::$lib as $name => $class)
        {
            if(is_callable([$class,'after_init']))
            {
                $class->after_init();
            }
        }
    }
    function load_payment_api_lib()
    {
        $libs = [
            'paypal' => [
                'path'  => $this->plugin_dir() . "inc/api/paypal/autoload.php",
                'class' => [
                    '\PayPal\Rest\ApiContext'
                ]
            ],
            'stripe'=>[
                'path'  => $this->plugin_dir() . "inc/api/stripe/init.php",
                'class' => [
                    '\Stripe\Charge'
                ]
            ]
        ];
        foreach ($libs as $name => $data) {
            $check = true;
            foreach ($data['class'] as $class)
            {
                if(class_exists($class))
                {
                    $check = false;
                    break;
                }
            }
            if($check)
                require_once $data['path'];
        }
    }
    function load_abs_lib()
    {
        $class_required = [
            'EF4PaymentApi',
            //'PaypalApi'
        ];
        //check for file exist
        $inc = [];
        foreach ($class_required as $class) {
            $path = $this->plugin_dir() . "inc/class/abs/{$class}.php";
            if (!file_exists($path))
                $this->add_error("File {$path} not found");
            $inc[] = $path;
        }
        if (count($inc) !== count($class_required)) return;
        //require file
        foreach ($inc as $path)
            require_once $path;
    }
    function load_lib()
    {
        $class_required = [
            'Settings',
            'Templates',
            'API',
//            'MetaBox',
            'Ajax',
            'Log',
            'PaypalApi',
            'StripeApi',
        ];
        //check for file exist
        $inc = [];
        foreach ($class_required as $class) {
            $path = $this->plugin_dir() . "inc/class/{$class}.php";
            if (!file_exists($path))
                $this->add_error("File {$path} not found");
            $inc[] = $path;
        }
        if (count($inc) !== count($class_required)) return;
        //require file
        foreach ($inc as $path)
            require_once $path;
        //create instance of class
        foreach ($class_required as $class) {
            $class_name = '\\ef4\\' . $class;
            self::$lib[$class] = $lib_instance = new $class_name();
            if (is_callable([$lib_instance, 'init']))
                $lib_instance->init();
        }

        $extends_lib = apply_filters('ef4_extends_library',[]);
        foreach ($extends_lib as $slug => $class_name)
        {
            if(!class_exists($class_name))
                continue;
            self::$lib[$slug] = $lib_instance = new $class_name();
            if (is_callable([$lib_instance, 'init']))
                $lib_instance->init();
        }
    }

    public function settings_map($just_value = false)
    {
        $allow_key = [
        ];
        $allow_key = apply_filters('ef4_settings_options_allow', $allow_key);
        $raw_map = [

        ];
        foreach ($allow_key as $key => $type)
            $raw_map[$key] = $key;
        if ($just_value)
            return array_values($raw_map);
        return $raw_map;
    }

    function lib($name)
    {
        if (array_key_exists($name, self::$lib))
            return self::$lib[$name];
        return null;
    }

    public function get_settings()
    {
        $settings = [];
        $keys = $this->settings_map(true);
        foreach ($keys as $key) {
            $settings[$key] = get_option($this->merge_name(self::SETTINGS_PREFIX, $key), '');
        }
        return $settings;
    }

    public function get_setting($name, $default = '',$protect = true)
    {
        $value = $default;
        if (in_array($name, $this->settings_map(true)))
            $value = get_option($this->merge_name(self::SETTINGS_PREFIX, $name), $default);
        $value = apply_filters('ef4_get_option', $value, $name);
        return $value;
    }

    public function save_setting($name, $value)
    {
        if (in_array($name, $this->settings_map(true))) {
            //fix some field
            $value = apply_filters('ef4_save_option', $value, $name);
            update_option($this->merge_name(self::SETTINGS_PREFIX, $name), $value);
            return true;
        }
        return false;
    }
}

if (!function_exists('ef4')) {
    function ef4()
    {
        return EF4Core::instance();
    }

    ef4();
}
