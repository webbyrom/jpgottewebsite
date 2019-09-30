<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/9/2018
 * Time: 2:36 PM
 */

$all_post_types = get_post_types();
$ignore = ['attachment', 'revision', 'customize_changeset', 'oembed_cache', 'user_request', 'nav_menu_item', 'custom_css'];
$post_types = [];
foreach ($all_post_types as $slug => $name) {
    if (in_array($slug, $ignore))
        continue;
    $post_types[$slug] = $name;
}


$templates = [
    [
        'type'        => 'checkbox',
        'heading'     => __('Post type attach', 'ef4-framework'),
        'id'          => 'payment_attach_post_types',
        'cols'        => '3',
        'options'     => $post_types,
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('payment_attach_post_types'),
        'description' => __('Post type select in there will add custom payment form in edit page.', 'ef4-framework')
    ],
    [
        'type'        => 'select',
        'heading'     => __('Default currency (default_currency)', 'ef4-framework'),
        'id'          => 'default_currency',
        'options'     => ef4()->parse_options_select( 'currencies'),
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('default_currency'),
        'description' => __('This field just effect when post empty "currency" meta and use for default value of new post.', 'ef4-framework')
    ],
    [
        'type'        => 'select',
        'heading'     => __('Symbol position (default_amount_mask)', 'ef4-framework'),
        'id'          => 'default_amount_mask',
        'options'     => ef4()->parse_options_select('amount_mask'),
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('default_amount_mask'),
        'description' => __('This field just effect when post empty "currency" meta and use for default value of new post.', 'ef4-framework')
    ],
    [
        'type'        => 'header',
        'heading'     => __('Paypal Config', 'ef4-framework'),
    ],
    [
        'type'        => 'textfield',
        'heading'     => __('Api Client ID', 'ef4-framework'),
        'id'          => 'paypal_api_client_id',
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('paypal_api_client_id'),
    ],
    [
        'type'        => 'textfield',
        'heading'     => __('Api Client Secret', 'ef4-framework'),
        'id'          => 'paypal_api_client_secret',
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('paypal_api_client_secret'),
    ],
    [
        'type'        => 'select',
        'heading'     => __('Type', 'ef4-framework'),
        'id'          => 'paypal_type',
        'class'=>'input-settings',
        'options'     => [
            'sandbox'=>__('Sandbox','ef4-framework'),
            'live'=>__('Live','ef4-framework'),
        ],
        'value'=>ef4()->get_setting('paypal_type'),
    ],
    [
        'type'        => 'header',
        'heading'     => __('Stripe Config', 'ef4-framework'),
    ],
    [
        'type'        => 'textfield',
        'heading'     => __('Secret Key', 'ef4-framework'),
        'id'          => 'stripe_secret_key',
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('stripe_secret_key'),
    ],
    [
        'type'        => 'textfield',
        'heading'     => __('Publishable Key', 'ef4-framework'),
        'id'          => 'stripe_publishable_key',
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('stripe_publishable_key'),
    ],
];
foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}

