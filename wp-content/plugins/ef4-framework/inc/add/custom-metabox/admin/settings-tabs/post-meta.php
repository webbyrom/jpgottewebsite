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
        'id'          => 'metabox_attach_post_types',
        'cols'        => '3',
        'options'     => $post_types,
        'class'=>'input-settings',
        'value'=>ef4()->get_setting('metabox_attach_post_types'),
        'description' => __('Post type select in there will add custom metabox in edit page.', 'ef4-framework')
    ],
];
foreach ($templates as $field) {
    do_action('ef4_admin_template', $field);
}

