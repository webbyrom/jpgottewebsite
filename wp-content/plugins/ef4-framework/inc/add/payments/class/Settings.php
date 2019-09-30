<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/24/2018
 * Time: 3:42 PM
 */

namespace ef4_payment;


class Settings
{
    const SETTINGS_PREFIX = 'ef4-payments';
    const MENU_SLUG = 'ef4-payments';
    private $settings_allow = [
        'js_field'               => 'json',
        'js_group_field'         => 'json',
        'js_special_field'       => 'json',
        'items_data'             => 'json',
        'form_type'              => 'string',
        'form_validate'          => 'json',
        'payment_fields'         => 'json',
        'payment_data_wrap'      => 'json',
        'meta_name_swap'         => 'json',
        'payment_api_config'     => 'json',
        'payment_success_action' => 'json',
        'payment_disable_action' => 'json',
        'payment_create_action'  => 'json',
    ];
    private $options_extend = [
        'payment_attach_post_types' => 'array',
        'paypal_api_client_id'      => 'protect',
        'paypal_api_client_secret'  => 'protect',
        'paypal_type'               => 'string',
        'stripe_secret_key'         => 'protect',
        'stripe_publishable_key'    => 'protect',
        'default_currency'          => 'string',
        'default_amount_mask'       => 'string',
    ];
    protected $data_struct = [];

    public function init()
    {
        add_filter('ef4_custom_menu_settings', [$this, 'attach_metabox_settings_menu']);
        add_filter('ef4_custom_settings_tabs', [$this, 'attach_metabox_settings_tabs'], 10, 2);
        add_filter('ef4_settings_tabs', [$this, 'add_settings_tabs']);
        add_filter('ef4_settings_options_allow', [$this, 'add_settings_options'], 10, 1);
        add_filter('ef4_custom_save_settings', [$this, 'save_settings'], 10, 2);
        add_filter('ef4_custom_get_settings', [$this, 'get_settings'], 10, 2);
        add_filter('ef4_payment_post_types_attach', [$this, 'get_post_types_attach']);

        add_filter('ef4_custom_settings_allow', [$this, 'get_settings_allow'], 10, 2);
        add_filter('ef4_custom_get_data_structure', [$this, 'get_option_structure'], 10, 2);


        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('ef4_admin_menu_register', [$this, 'add_settings_menu']);


    }
    public function after_init()
    {
        global $pagenow, $post;
        if ($post instanceof \WP_Post && $post->post_type == 'ef4_payment' && $pagenow == 'edit.php')
        {
            add_action('restrict_manage_posts', [$this, 'custom_payments_list_filter'], 99);
            add_action('pre_get_posts', [$this, 'edit_manager_custom_orderby']);
        }
    }
    function edit_manager_custom_orderby($query)
    {
        if (!is_admin())
            return;
        $use_get = wp_parse_args($_GET,[
            'e-ps'=>'',
            'e-pt'=>'',
            'e-pamin'=>'',
            'e-pamax'=>'',
            'e-pc'=>'',
        ]);
        $meta_query = [];

        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : '';
        if (!empty($orderby) && !empty($order) && !is_string($orderby) && !is_numeric($orderby))
            return;
        $post_type = $query->get('post_type');
        $fields = apply_filters('ef4_custom_get_settings', [], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        if (in_array($orderby, array_column($fields, 'id'))) {
            $query->set('meta_key', $orderby);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', $order);
        }
    }
    function custom_payments_list_filter()
    {
        $source_options = ['0' => __('All source', 'ef4-framework')];
        $source_allows = apply_filters('ef4_payment_post_types_attach', []);
        foreach ($source_allows as $item) {
            $source_options[$item] = $item;
        }
        $payment_type_options = array_merge(['0' => __('All payment type', 'ef4-framework')], apply_filters('ef4_payment_type_support', []));
        $customs = [
            [
                'type'    => 'select',
                'id'      => 'filter-by-source',
                'name'    => 'e-ps',
                'title'   => __('Filter by source', 'ef4-framework'),
                'options' => $source_options
            ],
            [
                'type'    => 'select',
                'id'      => 'filter-by-payment_type',
                'name'    => 'e-pt',
                'title'   => __('Payment type', 'ef4-framework'),
                'options' => $payment_type_options
            ],
            [
                'type'        => 'text',
                'id'          => 'filter-by-amount-min',
                'name'        => 'e-pamin',
                'title'       => __('Min amount', 'ef4-framework'),
                'placeholder' => __('Min amount', 'ef4-framework')
            ],
            [
                'type'        => 'text',
                'id'          => 'filter-by-amount-max',
                'name'        => 'e-pamax',
                'title'       => __('Max amount', 'ef4-framework'),
                'placeholder' => __('Max amount', 'ef4-framework')
            ],
            [
                'type'        => 'text',
                'id'          => 'filter-by-currency',
                'name'        => 'e-pc',
                'title'       => __('Currency', 'ef4-framework'),
                'placeholder' => __('All Currency (usd,eur,...)')
            ],
        ];
        foreach ($customs as $custom) {
            switch ($custom['type']) {
                case 'select':
                    ?>
                    <label for="<?php echo esc_attr($custom['id']) ?>"
                           class="screen-reader-text"><?php echo esc_html($custom['title']) ?></label>
                    <select name="<?php echo esc_attr($custom['name']) ?>" id="<?php echo esc_attr($custom['id']) ?>">
                        <?php
                        $current_val = !empty($_GET[$custom['name']]) ? $_GET[$custom['name']] : '0';
                        foreach ($custom['options'] as $value => $title) {
                            ?>
                            <option
                            value="<?php echo esc_attr($value) ?>" <?php selected($value, $current_val) ?>><?php echo esc_html($title) ?></option><?php
                        } ?>
                    </select>
                    <?php
                    break;
                case 'text':
                case 'textfield':
                    ?>
                    <label for="<?php echo esc_attr($custom['id']) ?>"
                           class="screen-reader-text"><?php echo esc_html($custom['title']) ?></label>
                    <input type="text" name="<?php echo esc_attr($custom['name']) ?>"
                           id="<?php echo esc_attr($custom['id']) ?>"
                           placeholder="<?php echo esc_attr($custom['placeholder']) ?>">
                    <?php
                    break;
            }
        }
    }


    function get_option_structure($result = [], $name)
    {
        if (!array_key_exists($name, $this->data_struct)) {
            switch ($name) {
                case 'payment_success_action':
                case 'payment_create_action':
                case 'payment_disable_action':
                case 'payment_fail_action':
                    $result = [
                        [
                            'heading' => __('Name', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'name',
                            'is_key'  => true
                        ],
                        [
                            'heading' => __('Types', 'ef4-framework'),
                            'type'    => 'select',
                            'id'      => 'type',
                            'options' => apply_filters('ef4_dynamic_action_allow', []),
                        ],
                        [
                            'heading' => __('Target Effect', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'target',
                        ],
                        [
                            'heading' => __('Params', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'params',
                        ],
                    ];
                    break;
                case 'js_field':
                case 'js_group_field':
                case 'js_special_field':
                    $result = [
                        [
                            'heading' => __('Field ID', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'field',
                            'is_key'  => true
                        ],
                        [
                            'heading' => __('Source', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'source',
                        ],
                    ];
                    break;
                case "meta_name_swap":
                    $result = [
                        [
                            'heading' => __('Meta name(unique)', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'meta_name',
                            'is_key'  => true
                        ],
                        [
                            'heading' => __('Form name', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'form_name',
                        ],
                        [
                            'heading' => __('Title on Admin View', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'title',
                        ],
                    ];
                    break;
                case 'form_validate':
                    $result = [
                        [
                            'heading' => __('Meta name', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'meta_name',
                        ],
                        [
                            'heading' => __('Validate', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'validate',
                        ],
                        [
                            'heading' => __('Message On Fail', 'ef4-framework'),
                            'type'    => 'textfield',
                            'id'      => 'message',
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

    function get_settings_allow($raw = [], $post_type = '')
    {
        if (!is_array($raw))
            $raw = [];
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        if (in_array($post_type, $post_types))
            $raw = array_merge($raw, array_keys($this->settings_allow));
        return $raw;
    }

    function add_settings_menu($setting_page)
    {
        add_submenu_page(
            $setting_page,
            __('Payments', 'ef4-framework'),
            __('Payments', 'ef4-framework'),
            'manage_options',
            'edit.php?post_type=ef4_payment');
    }

    function add_metabox()
    {
        add_meta_box(
            'ef4_payment-box',
            __('Informations', 'ef4-framework'),
            array($this, 'render_information_metabox'),
            'ef4_payment',
            'normal',
            'high'
        );
        add_meta_box(
            'ef4_payment_log-box',
            __('Process Log', 'ef4-framework'),
            array($this, 'render_log_metabox'),
            'ef4_payment',
            'normal',
            'high'
        );
    }

    function render_log_metabox($post)
    {
        ef4()->get_templates('admin/post-types/ef4_payment/log.php', ['post' => $post]);
    }

    function render_information_metabox($post)
    {
        ef4()->get_templates('admin/post-types/ef4_payment/info.php', ['post' => $post]);
    }

    public function attach_metabox_settings_tabs($tabs = [], $post_type = '')
    {
        if (!is_array($tabs))
            $tabs = [];
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        if (in_array($post_type, $post_types)) {
            $tabs['form-editor'] = __('Form Template', 'ef4-framework');
            $tabs['payment-data'] = __('Payment Data', 'ef4-framework');
            $tabs['form-actions'] = __('Form Actions', 'ef4-framework');
        }
        return $tabs;
    }

    function attach_metabox_settings_menu($menus = [])
    {
        if (!is_array($menus))
            $menus = [];
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        foreach ($post_types as $post_type)
            if (!in_array($post_type, $menus))
                $menus[] = $post_type;
        return $menus;
    }

    public function add_settings_tabs($tabs)
    {
        $tabs['payments'] = __('Payments Settings', 'ef4-framework');
        return $tabs;
    }

    public function add_settings_options($options)
    {
        if (!is_array($options))
            $options = [];
        $options = array_merge($options, $this->options_extend);
        return $options;
    }

    function get_settings($default = '', $args = [])
    {
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        $args = wp_parse_args($args, [
            'post_type' => '',
            'name'      => '',
        ]);
        $name_allow = array_keys($this->settings_allow);
        if (!in_array($args['post_type'], $post_types) || !in_array($args['name'], $name_allow))
            return $default;
        $result = get_option(ef4()->merge_name(self::SETTINGS_PREFIX, $args['post_type'], $args['name']), $default);;
        //modify some input type before get
        switch ($args['name']) {
            case 'payment_fields':
                $force_default = [
                    'amount'         => "amount",
                    'card_number'    => "card_number",
                    'card_exp_month' => "card_exp_month",
                    'card_exp_year'  => "card_exp_year",
                    'card_cvc'       => "card_cvc",
                ];
                if (!is_array($result))
                    $result = [];
                foreach ($force_default as $key => $value) {
                    if (empty($result[$key]))
                        $result[$key] = $value;
                }
                break;
            case 'items_data':
                $force_default = [
                    'name'          => "post:title",
                    'currency'      => "meta:currency",
                    'sample_amount' => "meta:sample_amount",
                    'price'         => "meta:price",
                    'max_quantity'  => "meta:max_quantity",
                    'max_stock'     => "meta:max_stock",
                    'sold'          => "meta:sold",
                ];
                if (!is_array($result))
                    $result = [];
                foreach ($force_default as $key => $value) {
                    if (empty($result[$key]))
                        $result[$key] = $value;
                }
                break;
//            case 'js_field':
//            case 'js_group_field':
//                $fields = [];
//                if (!is_array($result))
//                    $result = [];
//                foreach ($result as $field) {
//                    $fields[$field['field']] = $field;
//                }
////                sort($fields);
//                $result = $fields;
//                break;
        }
        return $result;
    }

    function save_settings($result = false, $args = [])
    {
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
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
                if (!is_array($args['value']))
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

    function get_post_types_attach($default = [])
    {
        $attach = trim(ef4()->get_setting('payment_attach_post_types', ''));
        return empty($attach) ? [] : explode(',', $attach);
    }
}