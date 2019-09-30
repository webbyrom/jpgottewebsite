<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/24/2018
 * Time: 4:26 PM
 */

namespace ef4_payment;

use ef4\EF4PaymentApi;

class Ajax
{
    public function init()
    {
        add_filter('ef4_public_ajax_handle', [$this, 'add_public_ajax_action']);
        add_filter('ef4_admin_ajax_handle', [$this, 'add_admin_ajax_action']);
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

    function add_public_ajax_action($handle)
    {
        if (!is_array($handle))
            $handle = [];
        $handle['ef4_payments_form_submit'] = [$this, 'form_ajax_handle'];
        return $handle;
    }

    function add_admin_ajax_action($handle)
    {
        if (!is_array($handle))
            $handle = [];
        $handle['ef4_payment_check_paypal_payment_status'] = [$this, 'check_paypal_status_ajax_handle'];
        $handle['ef4_payment_execute_paypal_payment'] = [$this, 'execute_paypal_payment_ajax_handle'];
        $handle['ef4_payment_check_stripe_payment_status'] = [$this, 'check_stripe_payment_charge_ajax_handle'];
        return $handle;
    }

    function check_stripe_payment_charge_ajax_handle()
    {
        ef4()->verify_ajax_request('ef4-check-stripe-charge', ['payment_id', 'charge_id']);
        $payment_api = $this->get_payment_lib('stripe');
        $result = $payment_api->check_charge($_POST['payment_id'], $_POST['charge_id']);
        if ($result)
            echo json_encode(['success' => 'success']);
    }

    function check_paypal_status_ajax_handle()
    {
        ef4()->verify_ajax_request('ef4-check-paypal-payment', ['payment_id', 'paypal_id']);
        $payment_api = $this->get_payment_lib('paypal');
        $result = $payment_api->check_payment_status($_POST['payment_id'], $_POST['paypal_id']);
        if ($result)
            echo json_encode(['success' => 'success']);
    }

    function execute_paypal_payment_ajax_handle()
    {
        ef4()->verify_ajax_request('ef4-check-paypal-payment', ['payment_id', 'paypal_id']);
        $result = false;
        $payment_api = $this->get_payment_lib('paypal');
        $payment_id = $_POST['payment_id'];
        $paypal_payment_id = $_POST['paypal_id'];
        ef4()->save_log_to($payment_id);
        $payment_id_saved = get_post_meta($payment_id, $payment_api->meta_key('payment_id'), true);
        $payer_id = get_post_meta($payment_id, $payment_api->meta_key('payer_id'), true);
        if ($payment_id_saved !== $paypal_payment_id) {
            ef4()->add_error_log('protect_invalid_function_params', ['function' => 'check_payment_charge', 'charge_id' => $paypal_payment_id]);
        } else {
            $payment_api->execute_payment($paypal_payment_id, $payer_id, $payment_id);
            $result = true;
        }
        if ($result)
            echo json_encode(['success' => 'success']);
    }

    function form_ajax_handle()
    {
        ef4()->verify_ajax_request('ef4-payments', ['data']);
        $data = wp_parse_args($_POST['data'], [
            'form-type'        => '',
            'form-item-source' => ''
        ]);
        $result = '';
        switch ($data['form-type']) {
            case 'purchase':
                $data = wp_parse_args($data, [
                    'form-items' => '',
                ]);
                $items = [];
                $form_items = explode(',', $data['form-items']);
                foreach ($form_items as $form_item) {
                    $quantity_field = 'quantity-of-' . $form_item;
                    if (!isset($data[$quantity_field]) || ($quantity = intval($data[$quantity_field])) <= 0)
                        continue;
                    $items[$form_item] = [
                        'id'       => $form_item,
                        'quantity' => $quantity
                    ];
                }
                $result = $this->form_purchase_handle($items, $data);
                break;
            case 'donate':
                $data = wp_parse_args($data, [
                    'form-items' => '',
                ]);
                $donate_id = explode(',', $data['form-items'])[0];
                $result = $this->form_donate_handle($donate_id, $data);
                break;
        }
        $this->render_form_ajax_result($result);
    }

    public function render_form_ajax_result($params)
    {
        if (!is_array($params) || empty($params['status'])) {
            $params = ['status' => 'fail', 'key' => 'payment_undefined'];
        }
        switch ($params['status']) {
            case 'fail':
                break;
        }
        ob_start();
        ef4()->get_templates("public/payments/{$params['source']}/result.php");
        $message = ob_get_clean();
        $data_replace = [
            'payment' => $params['payment'],
        ];
//        if($pa)
        $message = apply_filters('ef4_replace_data', $message, $data_replace);
        $result = [
            'status'  => $params['status'],
            'action'  => $params['action'],
            'message' => $message,
        ];
        echo json_encode($result);
    }

    function take_custom_field_from_request($post_type, $raw_data = [])
    {
        $meta_name_swap = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'meta_name_swap']
        );
        $required_field = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'payment_fields']
        );
        $form_type = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'form_type']
        );
        $payment_meta = [];
        switch ($form_type) {
            case 'donate':
                $amount_key = $required_field['amount'];
                if (empty($raw_data[$amount_key]))
                    return [
                        'status' => 'fail',
                        'key'    => 'payment_form_donate_empty_amount',
                    ];
                if (!is_numeric($raw_data[$amount_key]))
                    return [
                        'status' => 'fail',
                        'key'    => 'payment_form_modify_by_client'
                    ];
                $amount = ef4()->parse_float_val($raw_data[$amount_key]);
                $payment_meta['amount'] = $amount;
                $map_meta_to_form = [];
                break;
        }
        switch ($raw_data['payment_type']) {
            case 'stripe':
                $stripe_required = ['card_number', 'card_exp_month', 'card_exp_year', 'card_cvc'];
                foreach ($stripe_required as $key) {
                    if (empty($raw_data[$required_field[$key]]))
                        return [
                            'status' => 'fail',
                            'key'    => 'payment_stripe_missing_card_info',
                        ];
                    $payment_meta[$key] = $raw_data[$required_field[$key]];
                }
                break;
        }
        foreach ($meta_name_swap as $swapper) {
            $map_meta_to_form[$swapper['meta_name']] = $swapper['form_name'];
            $payment_meta[$swapper['meta_name']] = isset($raw_data[$swapper['form_name']]) ? trim($raw_data[$swapper['form_name']]) : '';
        }
        $fields_validate = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'form_validate']
        );
        $error_message = [];
        $fields_error = [];
        foreach ($fields_validate as $field) {
            $value = (isset($payment_meta[$field['meta_name']])) ? $payment_meta[$field['meta_name']] : '';
            if (!ef4()->is_valid_value($value, $field['validate'])) {
                $error_message[] = $field['message'];
                if (array_key_exists($field['meta_name'], $map_meta_to_form))
                    $fields_error[] = $map_meta_to_form[$field['meta_name']];
            }
        }
        if (!empty($error_message))
            return [
                'status' => 'fail',
                'data'   => [
                    'fields'   => $fields_error,
                    'messages' => $error_message
                ]
            ];
        else
            return [
                'status' => 'success',
                'data'   => $payment_meta
            ];
    }

    function form_donate_handle($donate_id = '', $data = [])
    {
        $payments_data = wp_parse_args($data, [
            'payment_type'     => '',
            'form-item-source' => '',
            'form-request-url' => '',
        ]);
        $result = [];
        $post_type = $payments_data['form-item-source'];
        $try_take_data = $this->take_custom_field_from_request($post_type, $payments_data);
        if ($try_take_data['status'] !== 'success')
            return [
                'status' => $try_take_data['status'],
                'key'    => 'payment_form_validate',
                'data'   => $try_take_data['data']
            ];
        $payment_meta = $try_take_data['data'];
        $items_math_data = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'items_data']
        );
        $args = [
            'post_status' => 'publish',
            'post_type'   => $post_type,
            'post__in'    => [$donate_id]
        ];
        $wp_query = new \WP_Query($args);
        $posts = $wp_query->posts;
        if (count($posts) !== 1) {
            return [
                'status' => 'fail',
                'key'    => 'payment_form_modify_by_client'
            ];
        }
        $donate_post = $posts[0];
        $donate_info = [
            'id'       => $donate_post->ID,
            'name'     => ef4()->parse_post_data($items_math_data['name'], $donate_post),
            'currency' => ef4()->try_take_local_config('currency', $donate_post, $items_math_data['currency']),
            'quantity' => 1,
            'price'    => $payment_meta['amount'],
        ];
        $payment_types_support = apply_filters('ef4_payment_type_support', [], $post_type);
        if (!array_key_exists($payments_data['payment_type'], $payment_types_support))
            return [
                'status' => 'fail',
                'key'    => 'payment_invalid_payment_type',
            ];
        //check required field of specific
        switch ($payments_data['payment_type']) {
            case 'paypal':
                break;
            case 'stripe':
                $check = $this->check_required_payment_stripe($post_type, $payment_meta);
                if ($check !== true)
                    return $check;
                break;
            case 'custom':
                break;
        }
        $parse_data_from_form = [
            'request_url'  => 'form-request-url',
            'payment_type' => 'payment_type',
        ];
        foreach ($parse_data_from_form as $meta_key => $data_name) {
            $payment_meta[$meta_key] = $payments_data[$data_name];
        }
        $items = [$donate_post->ID => $donate_info];
        $payment_meta['currency'] = $donate_info['currency'];
        $payment_meta['amount_preview'] = apply_filters('ef4_payment_create_amount', $payment_meta['amount'] . $donate_info['currency'],
            $payment_meta['amount'], ['post' => $donate_post]);
        $payment_meta['items_source'] = $post_type;
        $payment_meta['items'] = $items;
        $payment_mask = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_data_wrap']
        );
        $payment_meta['description'] = apply_filters('ef4_replace_data', $payment_mask['description'], [
            'item'    => $donate_post,
            'payment' => $payment_meta
        ]);
        $payment_mask = wp_parse_args($payment_mask, [
            'title' => __('Empty Title Mask Payment', 'ef4-framework'),
        ]);
        $post_title = apply_filters('ef4_replace_data', $payment_mask['title'], [
            'item'    => $donate_post,
            'payment' => $payment_meta
        ]);
        $post_content_raw = [
            'items' => $items,
            'meta'  => $payment_meta
        ];
        $post_data = array(
            'post_type'    => 'ef4_payment',
            'post_status'  => 'pending',
            'post_content' => json_encode($post_content_raw),
            'post_title'   => $post_title
        );
        $payment_id = wp_insert_post($post_data, true);
        if ($payment_id instanceof \WP_Error) {
            return [
                'status' => 'fail',
                'key'    => 'payment_create_payment_post_fail',
                'data'   => $payment_id->get_error_message()
            ];
        }
        update_post_meta($payment_id,'raw_content_encoded',base64_encode(json_encode($post_content_raw)));
        ef4()->save_log_to($payment_id);
        foreach ($payment_meta as $key => $value) {
            update_post_meta($payment_id, $key, $value);
        }
        $payment_meta['payment_id'] = $payment_id;
        switch ($payment_meta['payment_type']) {
            case 'paypal':
                $result = $this->do_payment_via_paypal($post_type, $items, $payment_meta);
                break;
            case 'stripe':
                $result = $this->do_payment_via_stripe($post_type, $items, $payment_meta);
                break;
            case 'custom' :
                $result = [
                    'status'  => 'success',
                    'source'  => $post_type,
                    'payment' => $payment_meta
                ];
                break;
        }
        return $result;
    }

    function form_purchase_handle($items = [], $data = [])
    {
        $payments_data = wp_parse_args($data, [
            'payment_type'     => '',
            'form-item-source' => '',
            'form-request-url' => '',
        ]);
        $result = [];
        $post_type = $payments_data['form-item-source'];
        $try_take_data = $this->take_custom_field_from_request($post_type, $data);
        if ($try_take_data['status'] !== 'success')
            return [
                'status' => $try_take_data['status'],
                'key'    => 'payment_form_validate',
                'data'   => $try_take_data['data']
            ];
        $payment_meta = $try_take_data['data'];
        $items_math_data = apply_filters(
            'ef4_custom_get_settings',
            [],
            ['post_type' => $post_type, 'name' => 'items_data']
        );
        $args = [
            'post_status' => 'publish',
            'post_type'   => $post_type,
            'post__in'    => array_column($items, 'id')
        ];
        $wp_query = new \WP_Query($args);
        $posts = $wp_query->posts;
        if (count($posts) !== count($items) || empty($items)) {
            return [
                'status' => 'fail',
                'key'    => 'payment_form_modify_by_client'
            ];
        }
        foreach ($posts as $post) {
            $items[$post->ID]['post'] = $post;
        }
        $out_stock = [];
        $first_item = '';
        foreach ($items as $index => $item) {
            $post = $items[$index]['post'];
            $items[$index]['name'] = ef4()->parse_post_data($items_math_data['name'], $post);
            $items[$index]['currency'] = ef4()->parse_post_data($items_math_data['currency'], $post);
            $items[$index]['price'] = ef4()->parse_float_val(ef4()->parse_post_data($items_math_data['price'], $post));
            $max_quantity = intval(ef4()->parse_post_data($items_math_data['max_quantity'], $post));
            $max_stock = intval(ef4()->parse_post_data($items_math_data['max_stock'], $post));
            $sold = intval(ef4()->parse_post_data($items_math_data['sold'], $post));
            $remaing_stock = $max_stock - $sold;
            if ($remaing_stock < $items[$index]['quantity']) {
                $out_stock[] = $items[$index]['id'];
            }
            if ($max_quantity > 0 && $item['quantity'] > $max_quantity) {
                return [
                    'status' => 'fail',
                    'key'    => 'payment_form_modify_by_client'
                ];
            }
            if (empty($first_item))
                $first_item = $items[$index];
            unset($items[$index]['post']);
        }
        if (!empty($out_stock)) {
            return [
                'status' => 'fail',
                'key'    => 'payment_out_stock_in_process_time',
                'data'   => $out_stock
            ];
        }
        $payment_types_support = apply_filters('ef4_payment_type_support', [], $post_type);
        if (!array_key_exists($payments_data['payment_type'], $payment_types_support))
            return [
                'status' => 'fail',
                'key'    => 'payment_invalid_payment_type',
            ];

        //check required field of specific
        switch ($payments_data['payment_type']) {
            case 'paypal':
                break;
            case 'stripe':
                $check = $this->check_required_payment_stripe($post_type, $payment_meta);
                if ($check !== true)
                    return $check;
                break;
        }
        //store payment
        $total_amount = 0;
        foreach ($items as $product) {
            $total_amount += $product['price'] * $product['quantity'];
        }
//        $payment_meta['amount'] = $total_amount;
        $parse_data_from_form = [
            'request_url'  => 'form-request-url',
            'payment_type' => 'payment_type',
//            ''=>'',
//            ''=>'',
        ];
        foreach ($parse_data_from_form as $meta_key => $data_name) {
            $payment_meta[$meta_key] = $payments_data[$data_name];
        }
        $payment_meta['total_amount'] = $total_amount;
        $payment_meta['amount'] = $total_amount;
        $payment_meta['amount_preview'] = apply_filters('ef4_payment_create_amount', $total_amount . $first_item['currency'],
            $total_amount, ['post' => $first_item['post']]);
        $payment_meta['currency'] = $first_item['currency'];
        $payment_meta['items_source'] = $post_type;
        $payment_meta['items'] = $items;
        $payment_mask = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_data_wrap']
        );
        $payment_meta['description'] = apply_filters('ef4_replace_data', $payment_mask['description'], [
            'item'    => $first_item['post'],
            'payment' => $payment_meta
        ]);
        $payment_mask = wp_parse_args($payment_mask, [
            'title' => __('Empty Title Mask Payment', 'ef4-framework'),
        ]);
        $post_title = apply_filters('ef4_replace_data', $payment_mask['title'], [
            'item'    => $first_item['post'],
            'payment' => $payment_meta
        ]);
        $post_content_raw = [
            'items' => $items,
            'meta'  => $payment_meta
        ];
        $post_data = array(
            'post_type'    => 'ef4_payment',
            'post_status'  => 'pending',
            'post_content' => json_encode($post_content_raw),
            'post_title'   => $post_title
        );
        $payment_id = wp_insert_post($post_data, true);
        if ($payment_id instanceof \WP_Error) {
            return [
                'status' => 'fail',
                'key'    => 'payment_create_payment_post_fail',
                'data'   => $payment_id->get_error_message()
            ];
        }
        update_post_meta($payment_id,'raw_content_encoded',base64_encode(json_encode($post_content_raw)));
        ef4()->save_log_to($payment_id);
        foreach ($payment_meta as $key => $value) {
            update_post_meta($payment_id, $key, $value);
        }
        $payment_meta['payment_id'] = $payment_id;
        switch ($payment_meta['payment_type']) {
            case 'paypal':
                $result = $this->do_payment_via_paypal($post_type, $items, $payment_meta);
                break;
            case 'stripe':
                $result = $this->do_payment_via_stripe($post_type, $items, $payment_meta);
                break;
            case 'custom' :
                $result = [
                    'status'  => 'success',
                    'source'  => $post_type,
                    'payment' => $payment_meta
                ];
                break;
        }
        return $result;
    }

    function do_payment_via_paypal($post_type, $items, $payment_meta)
    {
        $api = $this->get_payment_lib('paypal');
        if (!$api instanceof EF4PaymentApi)
            return [
                'status' => 'fail',
                'key'    => 'payment_api_missing',
                'data'   => 'paypal'
            ];
        $payment_api_config = apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_api_config']
        );
        $payment_meta['context_source'] = $post_type;
        $redirect_back = $payment_api_config['paypal_redirect_back'];
        switch ($redirect_back) {
            case 'redirected':
                $payment_meta['redirect_back'] = $payment_meta['request_url'];
                break;
            case 'home':
            default:
                if (is_numeric($redirect_back))
                    $payment_meta['redirect_back'] = get_permalink($redirect_back);
                else
                    $payment_meta['redirect_back'] = home_url('');
                break;
        }; // setting with options
        $payment_meta['no_shipping'] = $payment_api_config['paypal_no_shipping']; // setting with options
        $approval_url = $api->create_purchased_payment($items, $payment_meta);
        $payment_meta['paypal_redirect'] = $approval_url;
        $result = [
            'status'  => 'success',
            'action'  => [
                'type' => 'redirect',
                'data' => [
                    'url'   => $approval_url,
                    'delay' => 5
                ],
            ],
            'source'  => $post_type,
            'payment' => $payment_meta,
        ];
        return $result;
    }

    function check_required_payment_stripe($post_type, $payment_meta)
    {
        $stripe = $this->get_payment_lib('stripe');
        if (!$stripe instanceof EF4PaymentApi)
            return [
                'status' => 'fail',
                'key'    => 'payment_api_missing',
                'data'   => 'stripe'
            ];
        $card_info = [
            'number'    => $payment_meta['card_number'],
            'exp_month' => $payment_meta['card_exp_month'],
            'exp_year'  => $payment_meta['card_exp_year'],
            'cvc'       => $payment_meta['card_cvc'],
            'source'    => $post_type
        ];
        $card_token = $stripe->get_card_token($card_info);
        if (!$stripe->is_valid_token($card_token)) {
            return [
                'status' => 'fail',
                'key'    => 'payment_stripe_invalid_card_info',
                'data'   => 'stripe'
            ];
        }
        $this->stripe_card_token = $card_token;
        return true;
    }

    function do_payment_via_stripe($post_type, $items, $payment_meta)
    {
        $stripe = $this->get_payment_lib('stripe');
        if (!$stripe instanceof EF4PaymentApi)
            return [
                'status' => 'fail',
                'key'    => 'payment_api_missing',
                'data'   => 'stripe'
            ];
        $card_token = $this->stripe_card_token;
        if (!$stripe->is_valid_token($card_token)) {
            return [
                'status' => 'fail',
                'key'    => 'payment_stripe_invalid_card_info_2',
                'data'   => 'stripe'
            ];
        }
        $payment_meta['source'] = $post_type;
        $payment_meta['card_token'] = $card_token;
        $result = $stripe->create_purchased_payment($items, $payment_meta);
        if ($result === 'success')
            $result = [
                'status'  => 'success',
                'source'  => $post_type,
                'payment' => $payment_meta
            ];
        return $result;
    }

    function get_payment_lib($type)
    {
        $lib = null;
        switch ($type) {
            case 'paypal':
                $lib = ef4()->lib('PaypalApi');
                break;
            case 'stripe':
                $lib = ef4()->lib('StripeApi');
                break;
        }
        return $lib;
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

}