<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 9/16/2017
 * Time: 8:51 AM
 */
class EF4VCGrid
{
//vc_map => add shortcode
//vc_add_param => add param to shortcode
//vc_remove_param =>remove param
//vc_update_shortcode_param => update
    protected static $instance = false;
    protected static $shortcodes_add = array(); //1
    protected static $params_remove = array();  //2
    protected static $params_add = array();     //3
    protected static $params_update = array();  //4

    public static $shortcode_add_list = array();
    public static function add_shortcode(array $attributes)
    {
        if (!isset($attributes['base']) || empty($attributes['base'])) {
            trigger_error(__('Wrong vc_map object. Base attribute is required'), E_USER_ERROR);
            die();
        }
        $base = $attributes['base'];
        if (array_key_exists($base, self::$shortcodes_add)) {
            trigger_error(__('Duplicate vc shortcode.'), E_USER_ERROR);
            die();
        }
        self::$shortcodes_add[$base] = $attributes;
        self::$shortcode_add_list[] = $base;
    }

    public static function add_param($shortcode = '', array $attributes, array $options = array())
    {
        if (empty($shortcode) || !is_string($shortcode)) {
            trigger_error(__('Wrong shortcode name.'), E_USER_ERROR);
            die();
        }
        if (!array_key_exists($shortcode, self::$params_add))
            self::$params_add[$shortcode] = array(
                array(
                    'options' => $options,
                    'param'   => $attributes
                )
            );
        else
            self::$params_add[$shortcode][] = array(
                'options' => $options,
                'param'   => $attributes
            );
    }

    public static function remove_param($shortcode = '', $attribute_name = '')
    {
        if (empty($shortcode) || !is_string($shortcode)) {
            trigger_error(__('Wrong shortcode name.'), E_USER_ERROR);
            die();
        }
        if (!array_key_exists($shortcode, self::$params_remove))
            self::$params_remove[$shortcode] = array(
                $attribute_name
            );
        else
            self::$params_remove[$shortcode][] = $attribute_name;
    }

    public static function update_shortcode_param($shortcode = '', array $attributes)
    {
        if (empty($shortcode) || !is_string($shortcode)) {
            trigger_error(__('Wrong shortcode name.'), E_USER_ERROR);
            die();
        }
        if (!array_key_exists($shortcode, self::$params_update))
            self::$params_update[$shortcode][$attributes['param_name']] = $attributes;
        else {
            self::$params_update[$shortcode][$attributes['param_name']] = array_merge(self::$params_update[$shortcode][$attributes['param_name']], $attributes);
        }
    }

    public static function instance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'main_action'));
    }

    public function main_action()
    {
        add_filter('vc_grid_item_shortcodes', array($this, 'do_filter_vc_grid'));
    }

    //main action
    public function do_filter_vc_grid($shortcodes)
    {
        $this->action_add_shortcode($shortcodes);
        $this->action_remove_params($shortcodes);
        $this->action_add_params($shortcodes);
        $this->action_update_params($shortcodes);
        return $shortcodes;
    }

    protected function action_add_shortcode(&$shortcodes)
    {
        foreach (self::$shortcodes_add as $base => $attributes) {
            if (array_key_exists($base, $shortcodes)) {
                trigger_error(__('Duplicate vc shortcode.'), E_USER_ERROR);
                die();
            }
            if (!isset($attributes['post_type']) || empty($attributes['post_type'])) {
                $attributes['post_type'] = Vc_Grid_Item_Editor::postType();
            }
            $shortcodes[$base] = $attributes;
        }
    }

    protected function action_remove_params(&$shortcodes)
    {
        foreach (self::$params_remove as $base => $params) {
            if (!array_key_exists($base, $shortcodes))
                continue;
            foreach ($shortcodes[$base]['params'] as $index => $param)
            {
                if(in_array($param['param_name'],$params))
                    unset($shortcodes[$base]['params'][$index]);
            }
        }
    }

    protected function action_add_params(&$shortcodes)
    {
        foreach (self::$params_add as $base => $params) {
            if (!array_key_exists($base, $shortcodes))
                continue;
            $this->refill_index_shortcode_params($shortcodes[$base], $params);
        }
    }

    protected function action_update_params(&$shortcodes)
    {
        foreach (self::$params_update as $base => $params) {
            if (!array_key_exists($base, $shortcodes))
                continue;
            foreach ($shortcodes[$base]['params'] as $index => $param) {
                if (!array_key_exists($param['param_name'], $params))
                    continue;
                $shortcodes[$base]['params'][$index] = array_merge($param, $params[$param['param_name']]);
            }
        }
    }

    protected function refill_index_shortcode_params(&$shortcode, array $params)
    {
        $inserts_constrain_arr = array(
            'before' => array(),//element | [index] => param
            'after'  => array(),//element | [index] => param
        );
        if (!isset($shortcode['params']))
            $shortcode['params'] = array();
        $shortcode_param_with_name = array();//element | [param_name]=> param
        foreach ($shortcode['params'] as $param) {
            $shortcode_param_with_name[$param['param_name']] = $param;
        }
        foreach ($params as $info) {
            if (key_exists('before', $info['options'])) {
                if (!isset($inserts_constrain_arr['before'][$info['options']['before']]))
                    $inserts_constrain_arr['before'][$info['options']['before']] = array($info['param']);
                else
                    $inserts_constrain_arr['before'][$info['options']['before']][] = $info['param'];
            } elseif (key_exists('after', $info['options'])) {
                if (!isset($inserts_constrain_arr['after'][$info['options']['after']]))
                    $inserts_constrain_arr['after'][$info['options']['after']] = array($info['param']);
                else
                    $inserts_constrain_arr['after'][$info['options']['after']][] = $info['param'];
            } else {
                $shortcode['params'][] = $info['param'];
            }
            $shortcode_param_with_name[$info['param']['param_name']] = $info['param'];
        }
        $shortcode_params_order = array_keys($shortcode_param_with_name);
        $params_constrain = array();//element | [param_name] => ['before'=>'','after'=>'','before_count','after_count'];
        foreach ($inserts_constrain_arr as $type => $constrains) {
            foreach ($constrains as $key => $params_constrains_element) {
                foreach ($params_constrains_element as $param_constrain_element) {
                    $param_set_constrain = $param_constrain_element['param_name'];
                    $this->set_constrain_params($params_constrain, $param_set_constrain, $type, $key);
                    $this->move_array_element($shortcode_params_order, $param_set_constrain, $type, $key, $params_constrain[$param_set_constrain]);
                }
            }
        }
        $new_params_order = array();
        foreach ($shortcode_params_order as $name) {
            $new_params_order[] = $shortcode_param_with_name[$name];
        }
        $shortcode['params'] = $new_params_order;
    }

    protected function set_constrain_params(&$constrain_params_array, $key_set, $constrain_type = "before|after", $key_constrain)
    {
        $type = ($constrain_type === 'before') ? 'before' : 'after';
        if (!isset($constrain_params_array[$key_constrain]))
            $constrain_params_array[$key_constrain] = array(
                'before'       => '',
                'after'        => '',
                'before_count' => 0,
                'after_count'  => 0
            );
        if (!isset($constrain_params_array[$key_set])) {
            $constrain_params_array[$key_set] = array(
                'before'       => '',
                'after'        => '',
                'before_count' => 0,
                'after_count'  => 0
            );
        }
        $constrain_params_array[$key_set][$type] = $key_constrain;
        $constrain_params_array[$key_constrain][$type . '_count'] += $constrain_params_array[$key_set]['before_count'] + $constrain_params_array[$key_set]['after_count'] + 1;
    }

    protected function move_array_element(&$array, $value_move, $type = 'before', $value_target, $constrain)
    {
        $move_index = array_search($value_move, $array);
        if ($move_index === false || array_search($value_target, $array) === false)
            return;
        $before_count = $constrain['before_count'];
        $after_count = $constrain['after_count'];
        $count_move = 1 + $before_count + $after_count;
        $move_index = $move_index - $before_count;
        $group_move = array_slice($array, $move_index, $count_move);
        array_splice($array, $move_index, $count_move);
        $target_index = array_search($value_target, $array);
        $real_index = ($type === 'after') ? $target_index + 1 : $target_index;
        array_splice($array, $real_index, 0, $group_move);
    }

    protected function get_index_of_param($shortcode_params, $param_name)
    {
        foreach ($shortcode_params as $key => $param)
            if ($param['param_name'] === $param_name)
                return $key;
        return -1;
    }
}