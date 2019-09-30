<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/24/2018
 * Time: 3:40 PM
 */

namespace ef4_payment;


class Payments
{
    private $scripts_data = [];
    protected $stripe_card_token;

    public function init()
    {
        add_filter('ef4_paypal_api_context_data', [$this, 'filter_paypal_api_context'], 5, 2);
        add_filter('ef4_stripe_api_key_data', [$this, 'filter_stripe_api_key'], 5, 2);

        add_action('init', [$this, 'attach_cpts']);
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('ef4_payment_completed', array($this, 'actions_after_payment_complete'));
        add_action('ef4_payment_disabled', array($this, 'actions_after_payment_disable'));
//
        add_filter('ef4_get_payment_form_data', [$this, 'get_payment_form_data'], 10, 2);
        add_action('wp_footer', [$this, 'attach_payments_form']);
        add_action( 'transition_post_status',  [$this,'call_action_payment_modify_status'], 10, 3 );
    }
    public function call_action_payment_modify_status($new_status, $old_status, $post)
    {
        if(get_post_type($post) !== "ef4_payment")
            return;
        if($new_status == "publish")
        {
            do_action('ef4_payment_completed',$post->ID);
        }
        if($new_status !== "publish" && $old_status == "publish")
        {
            do_action('ef4_payment_disabled',$post->ID);
        }
    }
    public function after_init()
    {
        $this->attach_template_path();
    }

    function filter_paypal_api_context($context_args, $source)
    {
        $post_type_allow = apply_filters('ef4_payment_post_types_attach', []);
        if (!in_array($source, $post_type_allow)) {
            return $context_args;
        }
        return [
            'client_id'     => ef4()->get_setting('paypal_api_client_id', '', false),
            'client_secret' => ef4()->get_setting('paypal_api_client_secret', '', false),
            'type'          => ef4()->get_setting('paypal_type', ''),
        ];
    }

    function filter_stripe_api_key($key_args, $source)
    {
        $post_type_allow = apply_filters('ef4_payment_post_types_attach', []);
        if (!in_array($source, $post_type_allow)) {
            return $key_args;
        }
        return [
            'key_secret'      => ef4()->get_setting('stripe_secret_key', '', false),
            'key_publishable' => ef4()->get_setting('stripe_publishable_key', '', false),
        ];
    }

    function register_assets()
    {
        $ver = '1.1.' . ef4()->hash_to_number(md5_file(ef4()->plugin_dir() . 'inc/add/payments/assets/js/scripts.js'));
        wp_register_script('ef4-payments',
            ef4()->plugin_url() . 'inc/add/payments/assets/js/scripts.js',
            [],
            $ver);
    }
    function actions_after_payment_disable($payment_id)
    {
        $payment = get_post($payment_id);
        if (!$payment instanceof \WP_Post)
            return;
        $id = $payment->ID;
        ef4()->save_log_to($payment_id);
        $post_type = get_post_meta($id, 'items_source', true);
        $actions = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'payment_disable_action']
        );
        $items = get_post_meta($id, 'items', true);
        if (!is_array($items)) {
            ef4()->add_error_log('paypal_action_invalid_data_source', ['required' => 'array', 'current' => $items]);
            return;//need trigger error
        }
        foreach ($actions as $action) {
            foreach ($items as $item) {
                $data_sources = [
                    'payment_item' => $item,
                    'item'         => get_post($item['id']),
                    'payment'      => $payment
                ];
                $action['data_sources'] = $data_sources;
                ef4()->do_dynamic_action($action);
            }
        }
    }
    function actions_after_payment_complete($payment_id)
    {
        $payment = get_post($payment_id);
        if (!$payment instanceof \WP_Post)
            return;
        $id = $payment->ID;
        ef4()->save_log_to($payment_id);
        $post_type = get_post_meta($id, 'items_source', true);
        $actions = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'payment_success_action']
        );
        $items = get_post_meta($id, 'items', true);
        if (!is_array($items)) {
            ef4()->add_error_log('paypal_action_invalid_data_source', ['required' => 'array', 'current' => $items]);
            return;//need trigger error
        }
        foreach ($actions as $action) {
            foreach ($items as $item) {
                $data_sources = [
                    'payment_item' => $item,
                    'item'         => get_post($item['id']),
                    'payment'      => $payment
                ];
                $action['data_sources'] = $data_sources;
                ef4()->do_dynamic_action($action);
            }
        }
    }

    function attach_cpts()
    {
        $display = 'Payment';
        $display_s = 'Payments';
        $labels = array(
            "name"               => __("EF4 $display_s", 'ef4-framework'),
            "singular_name"      => __("$display_s: ", 'ef4-framework'),
            "menu_name"          => __("$display_s", 'ef4-framework'),
            "name_admin_bar"     => __("$display_s", 'ef4-framework'),
            "add_new"            => __("Add $display", 'ef4-framework'),
            "add_new_item"       => __("Add New $display: ", 'ef4-framework'),
            "new_item"           => __("New $display: ", 'ef4-framework'),
            "edit_item"          => __("Edit $display: ", 'ef4-framework'),
            "view_item"          => __("View $display: ", 'ef4-framework'),
            "all_items"          => __("All $display_s", 'ef4-framework'),
            "search_items"       => __("Search $display", 'ef4-framework'),
            "parent_item_colon"  => __("Parent $display:", 'ef4-framework'),
            "not_found"          => __("No $display_s found.", 'ef4-framework'),
            "not_found_in_trash" => __("No $display_s found in Trash.", 'ef4-framework'),
        );

        $args = array(
            'labels'              => $labels,
            'description'         => __('Description.', 'ef4-framework'),
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
//            'rewrite'             => array( 'slug' => $name ),
            'taxonomies'          => array(),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'exclude_from_search' => true,
            'supports'            => array('title', 'excerpt'),
//            'menu_icon'           => 'dashicons-cart'
        );
        register_post_type('ef4_payment', $args);
    }

    public function attach_payments_form()
    {
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        wp_enqueue_script('ef4-payments');
        wp_enqueue_script('ef4-front');
        wp_enqueue_style('ef4-front');
        wp_localize_script('ef4-payments', 'ef4_payments', [
            'items'    => $this->scripts_data,
            'settings' => [
                'action'  => 'ef4_payments_form_submit',
                'nonce'   => wp_create_nonce('ef4-payments'),
                'ajaxurl' => admin_url('admin-ajax.php'),
            ]
        ]);
        foreach ($post_types as $post_type) {
            ef4()->get_templates('public/form.php', compact('post_type'));
        }
    }

    function get_payment_form_data($default = [], $post = '')
    {
        $result = $default;
        $post_types = apply_filters('ef4_payment_post_types_attach', []);
        $posts = [];
        if (is_array($post)) {
            foreach ($post as $post_e) {
                $cr_post = ef4()->get_post($post_e, false);
                if (empty($cr_post))
                    continue;
                if (empty($post_type))
                    $post_type = get_post_type($cr_post);
                if (get_post_type($cr_post) !== $post_type)
                    continue;
                $posts[] = $cr_post;
            }
        } else {
            $cr_post = ef4()->get_post($post, false);
            if ($cr_post) {
                $posts[] = $cr_post;
                $post_type = get_post_type($cr_post);
            }
        }
        if (empty($posts) || !in_array($post_type, $post_types))
            return $result;
        $target = ef4()->get_hash($post_type);

        $data = $this->attach_scripts_data($post_type, $posts);
        $result['data-target'] = $target;
        $result['class'] = 'ef4-payments-trigger';
        $result['data-options'] = $data;
        return $result;
    }

    function attach_scripts_data($post_type, $posts)
    {
        $items_fields = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'js_field']
        );
        if (!is_array($items_fields))
            $items_fields = [];
        $items = [];
        $ids = [];
        $group = false;
        $group_fields = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'js_group_field']
        );
        $special_fields = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'js_special_field']
        );
        if(!is_array($special_fields))
            $special_fields = [];
        $special = [];
        $form_type = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'form_type']
        );
        if (!is_array($group_fields))
            $group_fields = [];
        foreach ($posts as $post) {
            if ($form_type == 'donate' && count($items) > 0)
                break;
            if (!$post instanceof \WP_Post)
                continue;
            if (!is_array($group)) {
                $group = [
                    'all' => []
                ];
                foreach ($group_fields as $key => $field) {
                    $group['all'][$key] = ef4()->parse_post_data($field['source'], $post);
                }
            }
            $item = [];
            foreach ($items_fields as $key => $field) {
                $item[$key] = ef4()->parse_post_data($field['source'], $post);
            }
            foreach ($special_fields as $key => $field) {
                $currenct_value = ef4()->parse_post_data($field['source'], $post);
                $currenct_value = ef4()->parse_options_select($currenct_value);
                if (!is_array($currenct_value))
                    continue;
                $s_key = $field['field'];
                if (empty($special[$s_key]))
                    $special[$s_key] = [];
                $special[$s_key] +=$currenct_value;
            }
            $id = (isset($item['id'])) ? $item['id'] : $post->ID;
            $items[$id] = $item;
            $ids[] = $post->ID;
        }
        $result = ef4()->get_hash($ids);
        $this->scripts_data[ef4()->get_hash($post_type)][$result] = [
            'items'    => $items,
            'group'    => $group,
            'special'  => $special,
            'settings' => [
                'form_type'        => apply_filters(
                    'ef4_custom_get_settings',
                    'purchase',
                    ['post_type' => $post_type, 'name' => 'form_type']
                ),
                'form_item_source' => $post_type
            ]
        ];
        return $result;
    }

    function attach_template_path()
    {
        ef4()->add_template_path('admin', ef4()->plugin_dir() . 'inc/add/payments/templates/admin');
        ef4()->add_template_path('public', ef4()->plugin_dir() . 'inc/add/payments/templates/public', 'add');
        ef4()->add_template_path('public/payments/', get_template_directory() . '/ef4-templates/payments/', 'theme');
        ef4()->add_template_path('public/default', get_template_directory() . '/ef4-templates/payments/default', 'theme');
    }
}