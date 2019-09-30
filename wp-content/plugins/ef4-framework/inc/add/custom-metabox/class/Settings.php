<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/2/2018
 * Time: 3:22 PM
 */

namespace ef4_metabox;


class Settings
{
    const MENU_PREFIX = 'ef4-metabox';
    const METABOX_ID_PREFIX = 'ef4-metabox';
    const SETTINGS_PREFIX = 'ef4-metabox';
    private $settings_allow = [
        'meta_fields' => 'json',
    ];
    private $options_extend = [
        'metabox_attach_post_types' => 'array'
    ];
    protected $data_struct = [];
    public function init()
    {
        add_filter('ef4_custom_menu_settings', [$this, 'attach_metabox_settings_menu']);
        add_filter('ef4_custom_settings_tabs', [$this, 'attach_metabox_settings_tabs'], 10, 2);
        add_filter('ef4_custom_meta_post_types_attach', [$this, 'get_post_types_attach']);

        add_filter('ef4_settings_options_allow', [$this, 'add_settings_options'], 11, 1);
        add_filter('ef4_settings_tabs', [$this, 'add_settings_tabs']);
        add_filter('ef4_custom_save_settings', [$this, 'save_settings'], 10, 2);
        add_filter('ef4_custom_get_settings', [$this, 'get_settings'], 10, 2);
        add_filter('ef4_custom_settings_allow',[$this,'get_settings_allow'],10,2);
        add_filter('ef4_custom_get_data_structure',[$this,'get_option_structure'],10,2);
    }
    function get_settings_allow($raw = [],$post_type = '')
    {
        if(!is_array($raw))
            $raw = [];
        $post_types = $this->get_post_types_attach();
        if(in_array($post_type,$post_types))
            $raw = array_merge($raw,array_keys($this->settings_allow));
        return $raw;
    }
    function get_option_structure($result = [],$name)
    {
        if(!array_key_exists($name,$this->data_struct))
        {
            switch ($name)
            {
                case 'meta_fields':
                    $metabox_field_type = [
                        'text'     => __('Text', 'ef4-framework'),
                        'textarea' => __('TextArea', 'ef4-framework'),
                        'select'   => __('Select', 'ef4-framework'),
                        'checkbox'   => __('Checkbox', 'ef4-framework'),
                        'date'   => __('Date', 'ef4-framework'),
                        'datetime'   => __('Datetime', 'ef4-framework'),
                        'number'   => __('Number', 'ef4-framework'),
                        'color'   => __('Color', 'ef4-framework'),
                        'image'   => __('Image', 'ef4-framework'),
                        'gallery'   => __('Gallery', 'ef4-framework'),
                    ];
                    $result = [
                        [
                            'heading' => __('ID (unique)', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'id',
                            'is_key'  => true
                        ],
                        [
                            'heading' => __('Type', 'ef4-framework'),
                            'type'    => 'select',
                            'id'      => 'type',
                            'options' => $metabox_field_type
                        ],
                        [
                            'heading' => __('Label', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'label',
                        ],
                        [
                            'heading'     => __('Options', 'ef4-framework'),
                            'type'        => 'textarea',
                            'id'          => 'options',
                            'description' => [
                                __('This field use for "Select/Checkbox" type','ef4-framework'),
                                __('An option per line , type like [value] => [label] or "number-min-max" to generate list number','ef4-framework'),
                                __('Example : 1 = One','ef4-framework')
                            ],
                            'dependency'=>[
                                'element'=>'type',
                                'value'=>['checkbox','select']
                            ]
                        ],
                        [
                            'heading'     => __('Default', 'ef4-framework'),
                            'type'        => 'textarea',
                            'id'          => 'default',
                            'description' => [
                                __('Default value of field','ef4-framework'),
                            ]
                        ],
                        [
                            'heading'     => __('Description', 'ef4-framework'),
                            'type'        => 'textarea',
                            'id'          => 'description',
                            'description' => [
                                __('Description add to Meta field','ef4-framework'),
                            ]
                        ],
                        [
                            'heading'     => __('Step', 'ef4-framework'),
                            'type'        => 'text',
                            'id'          => 'step',
                            'description' => [
                                __('Add to number type to allow float number','ef4-framework'),
                            ],
                        ],
                        [
                            'heading' => __('Show in Manager', 'ef4-framework'),
                            'type'    => 'checkbox',
                            'id'      => 'show_in_manager',
                            'label'=>'Yes',
                            'description'=>__('When checked will add new custom field and orderby on manager post type page.','ef4-framework')
                        ],
                        [
                            'heading' => __('Readonly', 'ef4-framework'),
                            'type'    => 'checkbox',
                            'id'      => 'readonly',
                            'label'=>'Yes',
                        ],
                    ];
                    break;
                default:
                    return $result;
                    break;
            }
            $this->data_struct[$name] = $result;
        }
        return $this->data_struct[$name];
    }


    function get_settings($default = '', $args = [])
    {
        $post_types = $this->get_post_types_attach();
        $args = wp_parse_args($args, [
            'post_type' => '',
            'name'      => '',
        ]);
        $name_allow = array_keys($this->settings_allow);
        if (!in_array($args['post_type'], $post_types) || !in_array($args['name'], $name_allow))
            return $default;
        $result = get_option(ef4()->merge_name(self::SETTINGS_PREFIX, $args['post_type'], $args['name']), $default);;
        //modify some input type before get
//        switch ($args['name']) {
//            case 'meta_fields':
//                $fields = [];
//                if (!is_array($result))
//                    $result = [];
//                foreach ($result as $field) {
//                    $fields[$field['id']] = $field;
//                }
////                sort($fields);
//                $result = $fields;
//                break;
//        }
        return $result;
    }

    function save_settings($result = false, $args = [])
    {
        $post_types = $this->get_post_types_attach();
        $args = wp_parse_args($args, [
            'post_type' => '',
            'name'      => '',
            'value'     => ''
        ]);
        $name_allow = array_keys($this->settings_allow);
        if (!in_array($args['post_type'], $post_types) || !in_array($args['name'], $name_allow))
            return $result;
        switch ($this->settings_allow[$args['name']]) {
            case 'json':
                if(!is_array($args['value']))
                    $value = json_decode(stripslashes($args['value']), true);
                else
                    $value = $args['value'];
                break;
            default:
                $value = $args['value'];
                break;
        }
        update_option(ef4()->merge_name(self::SETTINGS_PREFIX, $args['post_type'], $args['name']), $value);
        return true;
    }
    function add_settings_tabs($tabs)
    {
        $tabs['post-meta'] = __('Custom Meta', 'ef4-framework');
        return $tabs;
    }
    function get_post_types_attach()
    {
        $attach = trim(ef4()->get_setting('metabox_attach_post_types', ''));
        return empty($attach) ? [] : explode(',', $attach);
    }
    function attach_metabox_settings_tabs($tabs = [], $post_type = '')
    {
        if (!is_array($tabs))
            $tabs = [];
        $post_types =apply_filters('ef4_custom_meta_post_types_attach', []);
        if (in_array($post_type, $post_types))
        {
            $tabs['meta-fields'] = __('Meta Fields', 'ef4-framework');
            //$tabs['admin-editor'] = __('Meta Quick Editor', 'ef4-framework');
        }
        return $tabs;
    }

    function attach_metabox_settings_menu($menus = [])
    {
        if (!is_array($menus))
            $menus = [];
        $post_types = apply_filters('ef4_custom_meta_post_types_attach', []);
        foreach ($post_types as $post_type)
            if (!in_array($post_type, $menus))
                $menus[] = $post_type;
        return $menus;
    }
    function add_settings_options($options)
    {
        if (!is_array($options))
            $options = [];
        $options = array_merge($options, $this->options_extend);
        return $options;
    }
}