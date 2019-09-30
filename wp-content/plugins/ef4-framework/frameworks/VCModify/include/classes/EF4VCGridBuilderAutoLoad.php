<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 9/19/2017
 * Time: 10:44 AM
 */
class EF4VCGridBuilderAutoLoad
{
    protected static $instance = false;
    public static function instance()
    {
        if(!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }
    public function __construct()
    {
        if(!self::$instance)
            add_action('vc_after_init',array($this,'autoloadShortcode'));
    }
    public function autoloadShortcode()
    {
        foreach (EF4VCGrid::$shortcode_add_list as $shorcode)
        {
            $class_name = 'EF4VCGridBuilder_'.$shorcode;
            if(class_exists($class_name) && is_subclass_of($class_name,EF4VCGridBuilder::class))
            {
                $instance = new $class_name();
                add_shortcode($shorcode,array($instance,'do_shortcode'));
            }
        }
    }
}



EF4VCGridBuilderAutoLoad::instance();
