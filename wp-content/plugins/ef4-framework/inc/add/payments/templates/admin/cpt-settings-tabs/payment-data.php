<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/25/2018
 * Time: 10:18 AM
 */


$templates = [
    [
        'type'    => 'select',
        'heading' => __('Forms Type', 'ef4-framework'),
        'id'      => 'form_type',
        'class'   => 'input-settings',
        'options' => [
            'purchase' => __('Purchase', 'ef4-framework'),
            'donate'   => __('Donate', 'ef4-framework'),
        ],
        'value'   => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'form_type']
        )
    ],
    [
        'type'        => 'group',
        'heading'     => __('Payments Data Mask', 'ef4-framework'),
        'id'          => 'payment_data_wrap',
        'class'       => 'input-settings',
        'description' => [
            __('Mask will replace {{ query }} with payment data .', 'ef4-framework'),
            __(' - Use query like : source | key or post_data_query', 'ef4-framework'),
            __('Allow source :', 'ef4-framework'),
            __('- item : Take data from first item with post_dat_query', 'ef4-framework'),
            __('- payment : Meta data of payment (use key is "Meta Name")', 'ef4-framework'),
            __('Special query', 'ef4-framework'),
            __('- {{ site_name }} : replace with site name config in wordpress', 'ef4-framework'),
            __('- {{ home_url }} : replace with home url', 'ef4-framework'),
        ],
        'params'      => [
            [
                'type'    => 'textfield',
                'heading' => __('Title', 'ef4-framework'),
                'id'      => 'title',
            ],
            [
                'type'        => 'textfield',
                'heading'     => __('Description', 'ef4-framework'),
                'id'          => 'description',
                'description' => [
                    __('Value in this field will set to field "description" in payment after replace data', 'ef4-framework'),
                ],
            ],
        ],
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_data_wrap']
        )
    ],
    [
        'type'        => 'group',
        'heading'     => __('Items Data Source', 'ef4-framework'),
        'id'          => 'items_data',
        'class'       => 'input-settings',
        'description' => [
            __('Default value will take from same name of meta, if your use other meta name your need change it.', 'ef4-framework'),
        ],
        'params'      => [
            [
                'type'    => 'textfield',
                'heading' => __('Item Name Source', 'ef4-framework'),
                'id'      => 'name',
            ],
            [
                'type'    => 'textfield',
                'heading' => __('Currency', 'ef4-framework'),
                'id'      => 'currency',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Sample Amout', 'ef4-framework'),
                'id'         => 'sample_amount',
                'dependency' => [
                    'element' => 'form_type',
                    'value'   => 'donate'
                ]
            ]
            ,
            [
                'type'       => 'textfield',
                'heading'    => __('Price', 'ef4-framework'),
                'id'         => 'price',
                'dependency' => [
                    'element' => 'form_type',
                    'value'   => 'purchase'
                ]
            ],
            [
                'type'        => 'textfield',
                'heading'     => __('Max Quantity Per Payment', 'ef4-framework'),
                'id'          => 'max_quantity',
                'description' => __('This field just effect if is numeric and > 0', 'ef4-framework'),
                'dependency'  => [
                    'element' => 'form_type',
                    'value'   => 'purchase'
                ]
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Max Stock', 'ef4-framework'),
                'id'         => 'max_stock',
                'dependency' => [
                    'element' => 'form_type',
                    'value'   => 'purchase'
                ]
            ],
            [
                'type'        => 'textfield',
                'heading'     => __('Sold', 'ef4-framework'),
                'id'          => 'sold',
                'description' => __('If (stock - sold) < quantity will return false payment', 'ef4-framework'),
                'dependency'  => [
                    'element' => 'form_type',
                    'value'   => 'purchase'
                ]
            ],
        ],
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'items_data']
        )
    ],
    [
        'type'        => 'group',
        'heading'     => __('Payments Required Fields', 'ef4-framework'),
        'id'          => 'payment_fields',
        'class'       => 'input-settings',
        'description' => [
            __('Default value will take from same name send by form, if your use other name your need change it.', 'ef4-framework'),
        ],
        'params'      => [
            [
                'type'    => 'checkbox',
                'heading' => __('Payment Types Allow(payment_type)', 'ef4-framework'),
                'id'      => 'payment_types_allow',
                'cols'    => '1',
                'options' => apply_filters('ef4_payment_type_support', []),
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Amount (Donate form)', 'ef4-framework'),
                'id'         => 'amount',
                'dependency' => [
                    'element' => 'form_type',
                    'value'   => 'donate'
                ]
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Card Number (Stripe payment)', 'ef4-framework'),
                'id'         => 'card_number',
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'stripe',
                    'compare' => '*='
                ]
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Card Expired Month (Stripe payment)', 'ef4-framework'),
                'id'         => 'card_exp_month',
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'stripe',
                    'compare' => '*='
                ]
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Card Expired Year (Stripe payment)', 'ef4-framework'),
                'id'         => 'card_exp_year',
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'stripe',
                    'compare' => '*='
                ]
            ],
            [
                'type'       => 'textfield',
                'heading'    => __('Card CVC (Stripe payment)', 'ef4-framework'),
                'id'         => 'card_cvc',
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'stripe',
                    'compare' => '*='
                ]
            ],
        ],
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_fields']
        )
    ],
    [
        'type'    => 'group',
        'heading' => __('Payment Api config', 'ef4-framework'),
        'id'      => 'payment_api_config',
        'class'   => 'input-settings',
        'params'  => [
            [
                'type'    => 'select',
                'heading' => __('Payment type default', 'ef4-framework'),
                'id'      => 'payment_type_default',
                'description'=>__('This field just effect on first time customer open form.','ef4-framework'),
                'options' => apply_filters('ef4_payment_type_support', []),
            ],
            [
                'type'    => 'checkbox',
                'heading' => __('Paypal checkout set status "No shipping"', 'ef4-framework'),
                'id'      => 'paypal_no_shipping',
                'options' => [
                    'yes' => __('Enable', 'ef4-framework'),
                ],
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'paypal',
                    'compare' => '*='
                ]
            ],
            [
                'type'    => 'select',
                'heading' => __('Paypal redirect back (completed payment)', 'ef4-framework'),
                'id'      => 'paypal_redirect_back',
                'options' => ef4()->parse_options_select(join(PHP_EOL, [
                    'home = ' . __('Home page (*)'),
                    'redirected = ' . __('Page redirected (*)'),
                    'post_type:page'
                ])),
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'paypal',
                    'compare' => '*='
                ]
            ],
            [
                'type'    => 'textarea',
                'heading' => __('Paypal Notice', 'ef4-framework'),
                'id'      => 'paypal_notice',
                'description'=>__('This text will show in form when customer choose payment via paypal','ef4-framework'),
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'paypal',
                    'compare' => '*='
                ]
            ],
            [
                'type'    => 'textarea',
                'heading' => __('Stripe Notice', 'ef4-framework'),
                'id'      => 'stripe_notice',
                'description'=>__('This text will show in form when customer choose payment via stripe','ef4-framework'),
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'stripe',
                    'compare' => '*='
                ]
            ],
            [
                'type'    => 'textarea',
                'heading' => __('Custom Notice', 'ef4-framework'),
                'id'      => 'custom_notice',
                'description'=>__('This text will show in form when customer choose payment via custom','ef4-framework'),
                'dependency' => [
                    'element' => 'payment_fields-payment_types_allow',
                    'value'   => 'custom',
                    'compare' => '*='
                ]
            ],
        ],
        'value'   => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => 'payment_api_config']
        )
    ],
    [
        'type'        => 'dynamic_table',
        'id'          => $opt_name = 'meta_name_swap',
        'heading'     => __('Payment Meta name swap', 'ef4-framework'),
        'layout'      => '2,2,6',
        'class'       => 'input-settings',
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' =>$opt_name]
        ),
        'description' => [
            __('Fields edit in there and fields required of payment api save to database. Any field other will not save.', 'ef4-framework'),
        ],
        'params'      => apply_filters('ef4_custom_get_data_structure',[],$opt_name),
    ],
    [
        'type'        => 'dynamic_table',
        'id'          => $opt_name = 'form_validate',
        'heading'     => __('Form validate', 'ef4-framework'),
        'layout'      => '2,4,4',
        'class'       => 'input-settings',
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_name]
        ),
        'description' => [
            __('Please just use "Meta name" in "Payment Meta name swap".', 'ef4-framework'),
            __('Use " | " as separator (include space) to create multiple validate type on same row.', 'ef4-framework'),
            __('Can duplicate "Meta name" with other Validate to create other Message.', 'ef4-framework'),
        ],
        'params'      => apply_filters('ef4_custom_get_data_structure',[],$opt_name),
    ],
];

foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}


