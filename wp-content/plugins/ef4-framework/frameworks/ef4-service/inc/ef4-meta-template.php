<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 11/9/2017
 * Time: 2:20 PM
 */
class EF4MetaTemplate
{
    const PREFIX = 'ef4mt';
    protected $service;
    protected static $inited;
    protected static $instance;
    protected static $custom_field_add = array();

    public static function instance()
    {
        if (!self::$instance instanceof self)
            self::$instance = new self();
        return self::$instance;
    }

    public function __construct()
    {
        if (!self::$inited) {
            self::$inited = true;
            $fields_valid = self::get_field_types_support();
            $special_fields = array(
                'custom_select_options' => 'Custom Select Options'
            );
            $fields_valid = array_merge($fields_valid, $special_fields);
            foreach ($fields_valid as $field => $title) {
                $filter_name = self::create_filter_name($field);
                $field_callback = array($this, "template_field_{$field}");
                if (is_callable($field_callback))
                    add_filter($filter_name, array($this, "template_field_{$field}"));
                $editor_callback = array($this, "editor_local_field_{$field}");
                if (is_callable($editor_callback))
                    add_filter(self::create_editor_filter_name($field), $editor_callback);
            }
        }
    }

    public static function create_filter_name($field)
    {
        return ef4_service()->merge_name(self::PREFIX, 'field', $field);
    }

    public static function create_editor_filter_name($field)
    {
        return ef4_service()->merge_name(self::PREFIX, 'field_editor', $field);
    }

    public static function generate_fields(array $fields)
    {
        foreach ($fields as $field)
            self::generate_field($field);
    }

    public static function generate_field($field)
    {
        if (!is_array($field) || empty($field['type']))
            return;
        $filter_name = self::create_filter_name($field['type']);
        if (empty($field['param_name']))
            $field['param_name'] = 'undefined';
        if (empty($field['heading']))
            $field['heading'] = ef4_service()->title_case($field['param_name']);
        ?>
        <div class="rwmb-metabox ef4-field-wrapper" <?php if (!empty($field['dependency']) && is_array($field['dependency'])): ?>
            data-dependency="<?php echo esc_attr(self::build_dependency_str($field['dependency'])) ?>" style="display: none;"
        <?php endif; ?>>
            <?php apply_filters($filter_name, $field); ?>
        </div>
        <?php
    }

    public static function register_field(array $args)
    {
        if (empty($args['type']))
            return false;
        $type = $args['type'];
        if (array_key_exists($type, self::get_field_types_support()))
            return false;
        if (!is_array(self::$custom_field_add))
            self::$custom_field_add = array();
        $title = (empty($args['title'])) ? $type : $args['title'];
        self::$custom_field_add[$type] = $title;
        $filter_name = self::create_filter_name($type);
        if (isset($args['use']))
            add_filter($filter_name, $args['use']);
//        if(isset($args['editor']))
//            add_filter()
        return true;
    }

    public static function get_field_types_support()
    {
        $base_support = array(
            'textfield'      => 'TextField',
            'numeric'        => 'Numeric',
            'select'         => 'Select',
            'checkbox'       => 'Checkbox',
            'datetime'       => 'Datetime',
            'multi_checkbox' => 'Multi Checkbox',
            'color' => 'Color Picker',
            'image' => 'Image Picker',
        );
        $base_support = apply_filters('ef4mt_field_types', $base_support);
        foreach (self::$custom_field_add as $id => $title) {
            if (!array_key_exists($id, $base_support))
                $base_support[$id] = $title;
        }
        return $base_support;
    }

    public static function create_param_name($name)
    {
        return self::PREFIX . '_' . $name;
    }

    public static function generate_editor_field($field_type)
    {
        $filter = self::create_editor_filter_name($field_type);
        do_action($filter);
    }

    public function editor_local_field_textfield()
    {
        $field_local = array(
            array(
                'type'        => 'textfield',
                'heading'     => 'Placeholder',
                'placeholder' => 'Field Placeholder'
            )
        );
        self::generate_fields($field_local);
    }

    public function template_field_textfield(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
//            'group'=>'default',
            'type'             => 'textfield',
//            'unit'=>'',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
            'placeholder'      => ''
//            'weight'=>''
        )));
        $prefix = self::PREFIX;
        $name = self::create_param_name($param_name);
        $id = $name;
        ?>
        <div class="rwmb-field rwmb-textfield-wrapper">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="rwmb-input">
                <input type="text"
                       id="<?php echo esc_attr($id) ?>"
                       class="rwmb-textfield ef4-input <?php echo esc_attr($edit_field_class) ?>"
                       name="<?php echo esc_attr($name) ?>"
                    <?php if (!empty($value)): ?>
                        value="<?php echo esc_attr($value) ?>"
                    <?php endif; ?>
                       placeholder="<?php echo esc_attr($placeholder) ?>"
                />
                <p class="description"><?php echo esc_html($description) ?></p>
            </div>
        </div>
        <?php
    }
    public function template_field_color(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
//            'group'=>'default',
            'type'             => 'textfield',
//            'unit'=>'',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
            'placeholder'      => ''
//            'weight'=>''
        )));
        $prefix = self::PREFIX;
        $name = self::create_param_name($param_name);
        $id = $name;
        ?>
        <div class="rwmb-field rwmb-textfield-wrapper">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="rwmb-input">
                <input type="text"
                       id="<?php echo esc_attr($id) ?>"
                       class="rwmb-textfield ef4-input ef4-field-color<?php echo esc_attr($edit_field_class) ?>"
                       name="<?php echo esc_attr($name) ?>"
                    <?php if (!empty($value)): ?>
                        value="<?php echo esc_attr($value) ?>"
                    <?php endif; ?>
                       placeholder="<?php echo esc_attr($placeholder) ?>"
                />
                <p class="description"><?php echo esc_html($description) ?></p>
            </div>
        </div>
        <?php
    }
    public function template_field_image(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
//            'group'=>'default',
            'type'             => 'textfield',
//            'unit'=>'',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
            'placeholder'      => ''
//            'weight'=>''
        )));
        $prefix = self::PREFIX;
        $name = self::create_param_name($param_name);
        $id = $name;
        wp_enqueue_media();
        ?>
        <div class="rwmb-field rwmb-textfield-wrapper">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="rwmb-input">
                <div class="pagebox">
                <span class="uploaded_image">
                    <?php if ( !empty($value) ) : ?>
                        <img src="<?php echo esc_url( $value ); ?>" />
                    <?php endif; ?>
                    </span>
                    <input type="text" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_url( $value ); ?>" class="featured_image_upload"/>
                    <input type="button" name="image_upload" value="<?php echo esc_html__( 'Upload Image','ef4-framework' ); ?>" class="button upload_image_button"/>
                    <input type="button" name="remove_image_upload" value="<?php echo esc_html__( 'Remove Image','ef4-framework' ); ?>" class="button remove_image_button"/>
                </div>
                <p class="description"><?php echo esc_html($description) ?></p>
            </div>
        </div>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="color_image"><?php echo esc_html__( 'Color Image','ef4-framework' ); ?></label>
            </th>
            <td>

            </td>
        </tr>
        <?php
    }
    public function editor_local_field_numeric()
    {
        $field_local = array(
            array(
                'type'        => 'numeric',
                'heading'     => 'Min',
                'placeholder' => 'Min value'
            ),
            array(
                'type'        => 'numeric',
                'heading'     => 'Max',
                'placeholder' => 'Max value'
            ),
            array(
                'type'        => 'numeric',
                'heading'     => 'Step',
                'placeholder' => 'Step value'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'Unit',
                'placeholder' => 'Unit id'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'Unit Default',
                'placeholder' => 'Unit default value'
            ),
        );
        self::generate_fields($field_local);
    }

    public function template_field_numeric(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'             => 'numeric',
            'unit'             => array(),
            'unit_selected'    => '',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
            'step'             => '',
//            'placeholder'      => ''
        )));
        $prefix = self::PREFIX;
        $name = self::create_param_name($param_name);
        $id = $name;
        $placeholder = !empty($placeholder) ? $placeholder : '';
        $unit_name = ef4_service()->merge_name($name, 'unit');
        $unit_id = ef4_service()->merge_name($id, 'unit');
        ?>

        <div class="rwmb-field">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>
            </div>
            <div class="rwmb-input">
                <input type="number" class="rwmb-number ef4-input" name="<?php echo esc_attr($name) ?>"
                       id="<?php echo esc_attr($id) ?>"
                       value="<?php echo esc_attr($value) ?>" step="1" min="0"
                       placeholder="<?php echo esc_attr($placeholder) ?>">
                <?php if (!empty($unit) && is_array($unit)) : ?>
                    <select name="<?php echo esc_attr($unit_name) ?>" id="<?php echo esc_attr($unit_id) ?>"
                            class="ef4-input">
                        <?php foreach ($unit as $unit_value => $unit_label): ?>
                            <option value="<?php echo esc_attr($unit_value) ?>" <?php selected($unit_value, $unit_selected) ?>><?php echo esc_attr($unit_label) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <p id="<?php echo esc_attr($id) ?>-description"
                   class="description"><?php echo esc_html($description) ?></p>
            </div>
        </div>

        <?php
    }

    public function editor_local_field_select()
    {
        $post_types = get_post_types('', 'objects');
        $post_type_options = array();
        $follow_data = array();
        foreach ($post_types as $post_type) {
            if ($post_type instanceof WP_Post_Type) {
                if (empty($post_type->taxonomies))
                    continue;
                $post_type_options[$post_type->name] = $post_type->label . " ({$post_type->name})";
                $follow_data[$post_type->name] = array();
                foreach ($post_type->taxonomies as $tax_name) {
                    $tax = get_taxonomy($tax_name);
                    if (!$tax instanceof WP_Taxonomy)
                        continue;
                    $follow_data[$post_type->name][$tax->name] = $tax->label . " ({$tax->name})";
                }
            }
        }
        $field_local = array(
            array(
                'type'       => 'select',
                'heading'    => 'Options',
                'param_name' => 'editor_options_select',
                'options'    => array(
                    'custom'     => 'Custom',
                    'post_type'  => 'Post Types',
                    'taxonomies' => 'Taxonomies',
                    'terms'      => 'Terms',
                    // 'special'    => 'Special Options',
                )
            ),
            array(
                'type'       => 'select',
                'heading'    => 'Post type of Taxonomies (Hide empty)',
                'param_name' => 'post_types',
                'options'    => $post_type_options,
                'dependency' => array(
                    'editor_options_select' => array(
                        'taxonomies', 'terms'
                    ),
                ),
            ),
            array(
                'type'        => 'select',
                'heading'     => 'Taxonomies of Terms',
                'param_name'  => 'taxonomies',
                'dependency'  => array(
                    'editor_options_select' => array(
                        'terms'
                    ),
                ),
                'follow_data' => array(
                    'post_types' => $follow_data
                )
            ),
            array(
                'type'       => 'custom_select_options',
                'heading'    => ' - Select Options',
                'param_name' => 'select_options',
                'dependency' => array(
                    'editor_options_select' => array(
                        'custom'
                    ),
                ),
            )
        );
        self::generate_fields($field_local);

    }

    public function template_field_custom_select_options(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'        => 'numeric',
            'heading'     => '',
            'param_name'  => '',
            'value'       => '',
            'element'     => array(),
            'description' => '',
            'dependency'  => array(),
        )));
        $name = self::create_param_name($param_name);
        $id = $name;

        ?>
        <div class="rwmb-field ef4-editor-select-group">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>
            </div>
            <div class="rwmb-input">
                <input type="hidden" class="ef4-editor-select-raw-option"
                       value="<?php echo esc_attr("<tr class='ef4-editor-select-single-option'>
                       <td><input type='text' class='ef4-editor-select-options' data-type='value'
                                  placeholder='value'></td>
                       <td> => </td>
                       <td><input type='text' class='ef4-editor-select-options' data-type='title'
                                  placeholder='title'></td>
                       <td><a style='width: 100%;text-align: center' href='#'
                              class='button btn-primary ef4-editor-select-options-remove'
                              onclick='return false'>-</a></td>
                   </tr>") ?>"/>
                <table>
                    <tbody>
                    <tr class="ef4-editor-select-single-option">
                        <td><input type="text" class="ef4-editor-select-options" data-type="value"
                                   placeholder="<?php echo esc_attr('value') ?>"></td>
                        <td> =></td>
                        <td><input type="text" class="ef4-editor-select-options" data-type="title"
                                   placeholder="<?php echo esc_attr('title') ?>"></td>
                        <td><a style="width: 100%;text-align: center" href="#"
                               class="button btn-primary ef4-editor-select-options-remove" onclick="return false">-</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><a style="width: 100%;text-align: center" href="#"
                                           class="button btn-primary ef4-editor-select-options-add"
                                           onclick="return false">+</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <input type="hidden" class="ef4-input ef4-editor-options-save" name="<?php echo esc_attr($name) ?>"
                   value="<?php echo esc_attr($value) ?>">
        </div>
        <?php
    }

    public function template_field_select(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'             => 'numeric',
            'unit'             => array(),
            'unit_selected'    => '',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'options'          => array(),
            'options_callback' => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => array(),
            'follow_data'      => array(),
            'placeholder'      => ''
        )));
        if (is_callable($options_callback)) {
            $options = call_user_func($options_callback);
        }
        $prefix = self::PREFIX;
        $name = self::create_param_name($param_name);
        $id = $name;
        ?>
        <div class="rwmb-field rwmb-select-wrapper">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>
            </div>
            <div class="rwmb-input">
                <select id="<?php echo esc_attr($id) ?>" class="rwmb-select ef4-input"
                        name="<?php echo esc_attr($name) ?>" <?php $this->create_follow_data_select($follow_data) ?> >
                    <?php foreach ($options as $op_val => $label): ?>
                        <option value="<?php echo esc_attr($op_val) ?>" <?php selected($op_val, $value) ?>><?php echo esc_html($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <p id="<?php echo esc_attr($id) ?>-description"
                   class="description"><?php echo esc_html($description) ?></p>
            </div>
        </div>
        <?php
    }

    public function template_field_checkbox(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'             => 'checkbox',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
        )));
        $name = self::create_param_name($param_name);
        $id = $name;
        ?>
        <div class="rwmb-field">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>

            </div>
            <div class="rwmb-input">
                <input type="checkbox" class="rwmb-yes-no ef4-input" name="<?php echo esc_attr($name) ?>"
                       id="<?php echo esc_attr($id) ?>" <?php checked('on', $value) ?>>
                <p id="<?php echo esc_attr($id) ?>-description"
                   class="description"><?php echo esc_html($description) ?></p></div>
        </div>
        <?php
    }

    public function template_field_datetime(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'             => 'checkbox',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'data_type'        => 'datetime',
            'data_format'      => 'm/d/Y H:i',
            'options'          => array(),
            'options_callback' => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
        )));
        if (empty($fields_options['data_format'])) {
            if ($data_type === 'date')
                $data_format = 'm/d/Y';
            elseif ($data_type === 'time')
                $data_format = 'H:i';
        }

        $name = self::create_param_name($param_name);
        $id = $name;
        ?>
        <div class="rwmb-field">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>
            </div>
            <div class="rwmb-input">
                <div class="date-field" data-type="<?php echo esc_attr($data_type) ?>"
                     data-format="<?php echo esc_attr($data_format) ?>">
                    <input type="text" name="<?php echo esc_attr($name) ?>" class=""
                           value="<?php echo esc_attr($value) ?>"/>
                </div>
            </div>
        </div>
        <?php
    }

    public function template_field_multi_checkbox(array $fields_options)
    {
        extract(wp_parse_args($fields_options, array(
            'type'             => 'checkbox',
            'heading'          => '',
            'param_name'       => '',
            'value'            => '',
            'options'          => array(),
            'options_callback' => '',
            'std'              => '',
            'edit_field_class' => '',
            'description'      => '',
            'dependency'       => '',
        )));
        if (is_callable($options_callback)) {
            $options = call_user_func($options_callback);
        }
        $name = self::create_param_name($param_name);
        $id = $name;
        $checked = explode(',', $value);
        ?>
        <div class="rwmb-field ef4-multi-checkbox-group">
            <div class="rwmb-label">
                <label for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading); ?></label>
            </div>
            <div class="rwmb-input">
                <?php foreach ($options as $op => $title): ?>
                    <input type="checkbox" class="rwmb-yes-no ef4-multi-checkbox-element"
                           value="<?php echo esc_attr($op) ?>"
                        <?php echo esc_attr((in_array($op, $checked)) ? 'checked="checked"' : '') ?>> <?php echo esc_html($title) ?>
                <?php endforeach; ?>
                <p id="<?php echo esc_attr($id) ?>-description"
                   class="description"><?php echo esc_html($description) ?></p></div>
            <input type="hidden" class="ef4-multi-checkbox-value" name="<?php echo esc_attr($name) ?>"
                   value="<?php echo esc_attr($value) ?>">
        </div>
        <?php
    }

    public static function build_dependency_str(array  $dependency)
    {
        $str = '';
        foreach ($dependency as $param => $values) {
            if (!is_array($values))
                $values = array($values);
            foreach ($values as $value)
                $str .= '|' . '"' . $param . '=' . $value . '"';
        }
        return trim($str, '|');
    }

    public function create_follow_data_select($data)
    {
        if (!is_array($data) || empty($data))
            return;
        $trigger = '';
        foreach ($data as $trigger_field => $data_trigger)
            $trigger .= "|\"{$trigger_field}\"";
        $trigger = trim($trigger, '|');
        ?> data-trigger="<?php echo esc_attr($trigger) ?>" data-follow="<?php echo esc_attr(json_encode($data)) ?>" <?php
    }
}

add_action('ef4_service_loaded', 'ef4_meta_template');
function ef4_meta_template()
{
    global $ef4_meta_template;
    if (!$ef4_meta_template instanceof EF4MetaTemplate)
        $ef4_meta_template = EF4MetaTemplate::instance();
    return $ef4_meta_template;
}
