<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/25/2018
 * Time: 10:18 AM
 */

if (empty($add_title))
    $add_title = '';
$templates = [
    [
        'type'      => 'dynamic_table',
        'id'        => $opt_id = 'meta_fields',
        'heading'   => sprintf(__('Custom Meta Fields %s', 'ef4-framework'), $add_title),
        'layout'    => '2,2,4,group',
        'class'     => 'input-settings',
        'has_order' => true,
        'value'     => apply_filters(
            'ef4_custom_get_settings',
            '',
            ['post_type' => $post_type, 'name' => $opt_id]
        ),
        'params'    => apply_filters('ef4_custom_get_data_structure', [], $opt_id),
    ]
];
foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}