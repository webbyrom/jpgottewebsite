<?php

class EF4Unit
{

    const PREFIX = 'ef4u';

    protected static $instances = array();
    public static function instance(EF4Service $service)
    {
        $class = get_class($service);
        if (array_key_exists($class, self::$instances) && self::$instances[$class] instanceof self)
            $instance = self::$instances[$class];
        else {
            $instance = new self($service);
            self::$instances[$class] = $instance;
        }
        return $instance;
    }


    protected $_data = array();
    protected $_raw_data = array();
    protected $service;
    protected $_default_data = array();
    public function __construct(EF4Service $service)
    {
        $this->service = $service;
        $option_name = $this->get_name();
        $this->_raw_data = $this->_data = get_option($option_name, array());
    }
    public function set_default_units(array $list_unit_default)
    {
        $default = array();
        foreach ($list_unit_default as $name => $data)
        {
            if($this->service->validate($name) && $this->service->validate($data,'array'))
                $default[$name]=$data;
        }
        $this->_default_data = $default;
        return $this;
    }
    public function get_name()
    {
        return $this->service->merge_name(self::PREFIX, str_replace('ef4', '', $this->service->plugin_name), 'data');
    }
    public function reset_data()
    {
        $this->_data = $this->_raw_data;
        return $this;
    }
    public function create($unit_name ,array $data)
    {
        $new_unit = array();
        foreach ($data as $key => $value)
        {
            if($this->service->validates([$key,$value]))
                $new_unit[$key] = $value;
        }
        $this->_data[$unit_name] = $new_unit;
        return $this->save();
    }
    public function get($unit_name)
    {
        $unit = array();
        if(isset($this->_data[$unit_name]))
            $unit = $this->_data[$unit_name];
        return $unit;
    }
    public function save()
    {
        $option_name = $this->get_name();
        if (update_option($option_name, $this->_data))
            $this->_raw_data = $this->_data;
        return $this;
    }
    public function to_array()
    {
        return $this->_data;
    }
    public function to_obj()
    {
        return (object) $this->_data;
    }

}