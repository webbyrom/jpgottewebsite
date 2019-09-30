<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/25/2018
 * Time: 10:18 AM
 */

$templates = [
    [
        'type'    => 'dynamic_table',
        'id'      => $opt_id = 'js_field',
        'heading' => __('JS Items field (items)', 'ef4-framework'),
        'layout'  => '2,6',
        'class'   => 'input-settings',
        'value'   => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'params'  =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
    ],
    [
        'type'    => 'dynamic_table',
        'id'      => $opt_id = 'js_group_field',
        'heading' => __('JS Group field (group) - just take data of first element.', 'ef4-framework'),
        'layout'  => '2,6',
        'class'   => 'input-settings',
        'value'   => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'params'  =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
    ],
    [
        'type'    => 'dynamic_table',
        'id'      => $opt_id = 'js_special_field',
        'heading' => __('JS Special field (special)', 'ef4-framework'),
        'description'=>[
            __('required Source data is convertible to array (options type)', 'ef4-framework'),
//            __('Data will merge (unique array) all items in form', 'ef4-framework')
        ],
        'layout'  => '2,6',
        'class'   => 'input-settings',
        'value'   => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'params'  =>  apply_filters('ef4_custom_get_data_structure',[],$opt_id),
    ]
];

//   Load template from [theme-dir]/ef4-templates/payments/<?php echo esc_html($post_type)
foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}


