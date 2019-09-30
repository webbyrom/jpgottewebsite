<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/24/2018
 * Time: 3:55 PM
 */

namespace ef4_payment;


class Api
{
    public function init()
    {
        add_filter('ef4_payment_create_amount', [$this, 'filter_create_amount'], 5, 3);
        add_filter('ef4_get_related_payment', [$this, 'filter_get_related_payment'], 5, 2);
        add_filter('ef4_payment_type_support', [$this, 'filter_payment_type_support'], 5, 2);
        add_filter('ef4_payment_type_settings', [$this, 'filter_payment_chose_settings'], 5, 2);
    }
    function filter_payment_chose_settings($result = [], $post_type = '')
    {
        $result = [
            'default'=>'paypal',
            'support'=>[
                'paypal' => __('Paypal', 'ef4-framework'),
                'stripe' => __('Stripe', 'ef4-framework'),
                'custom' => __('Custom', 'ef4-framework'),
            ],
            'notice'=>[]
        ];
        if(!empty($post_type))
        {
            //for payment type choose
            $settings = apply_filters(
                'ef4_custom_get_settings',
                '',
                ['post_type' => $post_type, 'name' => 'payment_fields']
            );
            $settings = wp_parse_args($settings, ['payment_types_allow' => '']);
            $paymen_types_allow = explode(',', $settings['payment_types_allow']);
            foreach ($result['support'] as $slug => $title) {
                if (!in_array($slug, $paymen_types_allow))
                    unset($result['support'][$slug]);
            }
            // set default
            $settings = apply_filters(
                'ef4_custom_get_settings',
                '',
                ['post_type' => $post_type, 'name' => 'payment_api_config']
            );
            $settings = wp_parse_args($settings,[
                'payment_type_default'=>'',
                'paypal_notice'=>'',
                'stripe_notice'=>'',
                'custom_notice'=>'',
            ]);
            $result['default']=$settings['payment_type_default'];
            if(!array_key_exists($result['default'],$result['support']))
            {
                $keys = array_keys($result['support']);
                $result['default'] = !empty($keys[0]) ? $keys[0] : '';
            }
            // get notice
            if(!empty($settings['paypal_notice']))
                $result['notice']['paypal'] = $settings['paypal_notice'];
            if(!empty($settings['stripe_notice']))
                $result['notice']['stripe'] = $settings['stripe_notice'];
            if(!empty($settings['custom_notice']))
                $result['notice']['custom'] = $settings['custom_notice'];
        }
        return $result;
    }
    function filter_payment_type_support($result = [], $post_type = '')
    {
        $result = [
            'paypal' => __('Paypal', 'ef4-framework'),
            'stripe' => __('Stripe', 'ef4-framework'),
            'custom' => __('Custom', 'ef4-framework'),
        ];
        if (!empty($post_type)) {
            $settings = apply_filters(
                'ef4_custom_get_settings',
                '',
                ['post_type' => $post_type, 'name' => 'payment_fields']
            );
            $settings = wp_parse_args($settings, ['payment_types_allow' => '']);
            $paymen_types_allow = explode(',', $settings['payment_types_allow']);
            foreach ($result as $slug => $title) {
                if (!in_array($slug, $paymen_types_allow))
                    unset($result[$slug]);
            }
        }
        return $result;
    }
    function filter_create_amount($default = '', $amount = '', $params = [])
    {
        $result = $default;
        $args = wp_parse_args($params, [
            'currency'         => '',
            'meta_currency'    => '',
            'amount_mask'      => '',
            'meta_amount_mask' => '',
            'post'             => ''
        ]);
        //when empty amount just return default
        $amount = ef4()->parse_float_val($amount);
        //try take currency from params

        $currency = $args['currency'];
        if (empty($currency))
            $currency = ef4()->try_take_local_config('currency', $args['post'], $args['meta_currency']);
        //all way fail just return default
        if (empty($currency))
            return $result;
        $amount_mask = $args['amount_mask'];
        if (empty($amount_mask))
            $amount_mask = ef4()->try_take_local_config('amount_mask', $args['post'], $args['meta_amount_mask']);
        if (empty($amount_mask))
            return $result;
        return ef4()->parse_amount($result, [
            'amount'   => $amount,
            'mask'     => $amount_mask,
            'currency' => $currency
        ]);
    }
    function filter_get_related_payment($default = [], $post, $add_params = [])
    {
        $params = wp_parse_args($add_params, [
            'allow_pending' => false,
            'return'        => 'meta',
            'limit' => '',
        ]);
        $use_post = ef4()->get_post($post);
        $args = [
            'post_type'   => 'ef4_payment',
            'post_status' => ($params['allow_pending']) ? ['publish', 'pending'] : 'publish',
            'meta_query'  => [
                [
                    'key'     => 'items',
                    'value'   => "s:2:\"id\";i:{$use_post->ID};",
                    'compare' => 'LIKE',
                ]
            ]
        ];
        if(!is_numeric($params['limit']))
            $args['posts_per_page'] = intval($params['limit']);
        $wp_query = new \WP_Query($args);
        $result = [];
        if (empty($wp_query->posts))
            return $result;
        switch ($params['return']) {
            case 'id':
                foreach ($wp_query->posts as $payment)
                    $result[] = $payment->ID;
                break;
            case 'post':
                $result = $wp_query->posts;
                break;
            default:
                $post_type =  $use_post->post_type;
                $meta_fields_swap = apply_filters(
                    'ef4_custom_get_settings',
                    [],
                    ['post_type' => $post_type, 'name' => 'meta_name_swap']
                );
                $custom_fields = array_column($meta_fields_swap,'meta_name');
                $other_fields = ['request_url','payment_type','items','amount','amount_preview','currency','description'];
                $fields = array_merge($custom_fields,$other_fields);
                foreach ($wp_query->posts as $payment) {
                    if(!$payment instanceof \WP_Post)
                        continue;
                    $record = [];
                    foreach ($fields as $field)
                    {
                        $record[$field] = get_post_meta($payment->ID,$field,true);
                    }
                    $record['id'] = $payment->ID;
                    $record['title']=$payment->post_title;
                    $record['time']=$payment->post_date;
                    $record['status']=$payment->post_status;
                    $result[] = $record;
                }
                break;
        }
        return $result;
    }
}