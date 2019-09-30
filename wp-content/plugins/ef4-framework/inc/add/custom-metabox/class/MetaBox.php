<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/25/2018
 * Time: 9:01 AM
 */

namespace ef4_metabox;


class MetaBox
{
    const MENU_PREFIX = 'ef4-metabox';
    const METABOX_ID_PREFIX = 'ef4-metabox';
    const SETTINGS_PREFIX = 'ef4-metabox';
    private $settings_allow = [
        'meta_fields' => 'json',
    ];
    private $options_extend = [
        'metabox_attach_post_types' => 'array'
    ];
    protected $data_struct = [];
    public function __construct()
    {

    }

    public function init()
    {
        add_filter('ef4_get_post_meta', [$this, 'get_post_meta'], 10, 3);
        add_action('add_meta_boxes', array($this, 'add_information_metabox'));
        add_action('save_post', array($this, 'save_custom_meta'));
    }
    public function after_init()
    {
        ef4()->add_template_path('admin', ef4()->plugin_dir() . 'inc/add/custom-metabox/admin');
        $this->modify_post_type_admin_manager();
    }

    function save_option_filter($value, $name)
    {
        if (array_key_exists($name, $this->settings_allow)) {
            $type = $this->settings_allow[$name];
            switch ($type) {
                case 'json':
                    if (!is_array($value))
                        $value = json_decode($value, true);
                    if (!is_array($value))
                        $value = [];
                    break;
            }
        }
        return $value;
    }

    function modify_post_type_admin_manager()
    {
        global $pagenow;
        if ($pagenow !== 'edit.php')
            return;
        $post_types = apply_filters('ef4_custom_meta_post_types_attach', []);
        if (empty($post_types))
            return;
        foreach ($post_types as $post_type) {
            add_filter("manage_edit-{$post_type}_columns", array($this, "edit_manager_column_columns"));
            add_action("manage_{$post_type}_posts_custom_column", array($this, "edit_manager_custom_columns"));
            add_filter("manage_edit-{$post_type}_sortable_columns", [$this, 'edit_manager_set_sortable_columns']);
        }
        add_action('pre_get_posts', [$this, 'edit_manager_custom_orderby']);
    }

    function edit_manager_custom_orderby($query)
    {
        if (!is_admin())
            return;
        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : '';
        if (!empty($orderby) && !empty($order) && !is_string($orderby) && !is_numeric($orderby))
            return;
        $post_type = $query->get('post_type');
        $fields = apply_filters('ef4_custom_get_settings', [], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        if (in_array($orderby, array_column($fields, 'id'))) {
            $query->set('meta_key', $orderby);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', $order);
        }
    }

    function edit_manager_custom_columns($column)
    {

        $meta = apply_filters('ef4_get_post_meta', []);;
        $key = $column;
        if (array_key_exists($key, $meta)) {
            $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
            $fields = apply_filters('ef4_custom_get_settings',[], [
                'post_type' => $post_type,
                'name'      => 'meta_fields'
            ]);
            if (!empty($fields[$key])) {
                $field = $fields[$key];
                if ($field['type'] == 'select' && strpos($field['options'], 'post_type:') !== false) {
                    $post_id = $meta[$key];
                    if (is_numeric($post_id))
                        $value = '<a href="' . get_edit_post_link($post_id) . '">' . get_the_title($post_id) . "</a> (ID: {$post_id})";
                }
            }
            echo wp_kses_post(isset($value) ? $value : $meta[$key]);
        }
    }

    function edit_manager_set_sortable_columns($columns)
    {
        $post_type = $_GET['post_type'];
        $fields = apply_filters('ef4_custom_get_settings',[], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        foreach ($fields as $field) {
            if (!empty($field['show_in_manager']) && $field['show_in_manager'] == 'yes')
                $columns[$field['id']] = $field['id'];
        }
        return $columns;
    }

    function edit_manager_column_columns($columns)
    {
        $hight_p_col = ['cb', 'title'];
        $post_type = !empty($_GET['post_type']) ? $_GET['post_type'] : 'post';
        $fields = apply_filters('ef4_custom_get_settings',[], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        $use_columns = [];
        foreach ($columns as $slug => $col) {
            if (in_array($slug, $hight_p_col))
                $use_columns[$slug] = $col;
        }
        foreach ($fields as $field) {
            if (!empty($field['show_in_manager']) && $field['show_in_manager'] == 'yes')
                $use_columns[$field['id']] = (!empty($field['label'])) ? $field['label'] : $field['id'];
        }
        foreach ($columns as $slug => $col) {
            if (!in_array($slug, $hight_p_col))
                $use_columns[$slug] = $col;
        }
        return $use_columns;
    }

    function save_custom_meta($post_id)
    {
        $post_type = get_post_type($post_id);
        $fields = apply_filters('ef4_custom_get_settings',[], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        foreach ($fields as $field) {
            $key = 'ef4_' . $field['id'];
            if (!isset($_POST[$key]))
                continue;
            $value = $_POST[$key];
            switch ($field['type']) {
                case 'datetime':
                case 'date':
                    $value = strtotime($value);
                    break;
            }
            update_post_meta($post_id, $field['id'], $value);
        }
    }

    function get_post_meta($result = [], $_post = '', $take_all = false)
    {
        if (is_numeric($_post))
            $id = $_post;
        elseif ($_post instanceof \WP_Post)
            $id = $_post->ID;
        else
            $id = get_the_ID();
        if (empty($id))
            return $result;
        $post_type = get_post_type($id);
        $fields = apply_filters('ef4_custom_get_settings',[], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        if ($take_all) {
            $all_meta = get_post_meta($id);
            $meta = [];
            foreach ($all_meta as $key => $value) {
                $meta[$key] = maybe_unserialize($value[0]);
            }
        } else {
            $meta = [];
            foreach ($fields as $field)
                $meta[$field['id']] = get_post_meta($id, $field['id'], true);
        }

        foreach ($fields as $field) {
            if (empty($meta[$field['id']]))
                continue;
            $value = isset($meta[$field['id']]) ?  $meta[$field['id']] : '';
            if(empty($value) && !empty($field['default']))
            {
                $value = $field['default'];
            }
            switch ($field['type']) {
                case 'date':
                    if (!empty($value) && is_numeric($value))
                        $value = date('Y-m-d', $value);
                    else
                        $value = date('Y-m-d', strtotime($value));
                    break;
                case 'datetime':
                    if (!empty($value) && is_numeric($value))
                        $value = date('Y-m-d H:i', $value);
                    else {
                        $value = date('Y-m-d H:i', strtotime($value));
                    }
                    break;
            }
            $meta[$field['id']] = $value;
        }
        return wp_parse_args($meta, $result);
    }

    function add_information_metabox()
    {
        $post_types = apply_filters('ef4_custom_meta_post_types_attach', []);
        $default_title = __('EF4 Custom Meta', 'ef4-framework');
        foreach ($post_types as $post_type) {
            $settings = apply_filters('ef4_custom_get_settings',$default = '', $args = [
                'post_type' => $post_type,
                'name'      => 'meta_fields'
            ]);
            if (empty($settings))
                continue;
            add_meta_box(
                ef4()->merge_name(self::METABOX_ID_PREFIX),
                apply_filters('ef4_metabox_title', $default_title, $post_type),
                [$this, 'render_metabox'],
                $post_type
            );
        }
    }

    function render_metabox($post)
    {
        $post_type = get_post_type($post);
        $fields = apply_filters('ef4_custom_get_settings',[], [
            'post_type' => $post_type,
            'name'      => 'meta_fields'
        ]);
        ef4()->get_templates('admin/cpt-metabox.php', compact(['fields', 'post']));
    }


}