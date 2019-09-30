<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/25/2018
 * Time: 10:18 AM
 */

$actions_layout = '2,2,3,3';
$templates = [
    [
        'type'        => 'header',
        'description' => [
            __('Source allow :item,payment,payment_item.', 'ef4-framework'),
            __('Target post allow : item,payment', 'ef4-framework'),
        ],
    ],
    [
        'type'        => 'dynamic_table',
        'id'          => $opt_id ='payment_success_action',
        'heading'     => __('Action after payment complete (publish payment)', 'ef4-framework'),
        'layout'      => $actions_layout,
        'class'       => 'input-settings',
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'description' => '',
        'params'      =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
    ],
    [
        'type'        => 'dynamic_table',
        'id'          => $opt_id ='payment_disable_action',
        'heading'     => __('Action after payment disabled (unpublish payment)', 'ef4-framework'),
        'layout'      => $actions_layout,
        'class'       => 'input-settings',
        'value'       => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'description' => '',
        'params'      =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
    ],
//    [
//        'type'        => 'dynamic_table',
//        'id'          => $opt_id =  'payment_create_action',
//        'heading'     => __('Action when payment create', 'ef4-framework'),
//        'layout'      => $actions_layout,
//        'class'       => 'input-settings',
//        'value'       => apply_filters(
//            'ef4_custom_get_settings',
//            '',
//            ['post_type' => $post_type, 'name' => $opt_id]
//        ),
//        'description' => '',
//        'params'      =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
//    ],
//    [
//        'type'        => 'dynamic_table',
//        'id'          => $opt_id = 'payment_fail_action',
//        'heading'     => __('Action when payment fail', 'ef4-framework'),
//        'layout'      => $actions_layout,
//        'class'       => 'input-settings',
//        'value'       => apply_filters(
//            'ef4_custom_get_settings',
//            '',
//            ['post_type' => $post_type, 'name' => $opt_id]
//        ),
//        'description' =>'',
//        'params'      => apply_filters('ef4_custom_get_data_structure',[],$opt_id),
//    ],
];

foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}


