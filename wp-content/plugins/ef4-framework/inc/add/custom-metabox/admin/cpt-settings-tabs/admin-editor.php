<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/14/2018
 * Time: 1:43 PM
 */

if (empty($add_title))
    $add_title = '';
$templates = [
    [
        'type'    => 'select',
        'heading' => esc_html__('Forms Type', 'ef4-framework'),
        'id'      => 'target',
        'class'   => 'input-settings',
        'options' => [
            '' => esc_html__('Please choose target', 'ef4-framework'),
            'all' => sprintf(__('All Post (%s)', 'ef4-framework'),$post_type),
            'ids'   => sprintf(__('Ids (%s)', 'ef4-framework'),$post_type),
        ]
    ],
    [
        'type'    => 'textfield',
        'heading' => esc_html__('Target ids', 'ef4-framework'),
        'id'      => 'post_ids',
        'class'   => 'input-settings',
        'dependency'=>[
            'element'=>'target',
            'value'=>['ids']
        ],
        'description'=>[
            esc_html__('Input posts id, use comma as separator','ef4-framework'),
            sprintf(__('Just on ly allow id of post type %s','ef4-framework'),$post_type),
        ]
    ],

];
foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}