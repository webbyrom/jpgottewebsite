<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/6/2018
 * Time: 3:39 PM
 */
namespace ef4;
class Templates
{
    public $script_template;

    public function init()
    {
        add_action('ef4_admin_template', [$this, 'admin_template']);
    }

    function admin_template($args = [])
    {
        if (empty($args['type']))
            return;
        $call_name = 'admin_template_' . $args['type'];
        $attr = [];
        $attr['class'] = ['row'];
        if (!empty($args['dependency'])) {
            $attr['class'][] = 'ef4-dependency';
            $attr['data-match'] = $this->build_dependency_match($args['dependency']);
        }
        if (is_callable([$this, $call_name])) {
            ?>
        <div <?php $this->show_attrs($attr) ?>>
            <?php $this->$call_name($args); ?>
            </div><?php

        }

    }

    function build_dependency_match($args)
    {
        //  [
        //      'element'=>'field',
        //      'value'=>[],
        //      'compare'=>'='
        //  ]
        $param = wp_parse_args($args, [
            'element' => '',
            'value'   => '',
            'compare' => '='
        ]);
        $query = [];
        $values = (is_array($param['value'])) ? $param['value'] : [$param['value']];
        $relation = '{||}';
        foreach ($values as $value) {
            switch ($param['compare']) {
                case '*=':
                    $query[] = "el{#{$param['element']}}{*=}{$value}";
                    $relation = '{||}';
                    break;
                case '!=':
                    $query[] = "el{#{$param['element']}}{!=}{$value}";
                    $relation = '{&&}';
                    break;
                case '=':
                default:
                    $query[] = "el{#{$param['element']}}{=}{$value}";
                    $relation = '{||}';
                    break;
            }
        }
        return join($relation, $query);
    }

    function table_field_template($args)
    {
        $param = wp_parse_args($args, [
            'id'    => '',
            'type'  => '',
            'label' => '',
        ]);
        $attr = [
            'class' => ['form-group', 'no-margin-bot']
        ];
        ob_start();
        ?>
        <div <?php $this->show_attrs($attr) ?>><?php
        switch ($param['type']) {
            case 'numeric':
                ?>
                <div class="form-line">
                <input type="number" class="form-control input-field" placeholder=""
                       id="{_unique_id_}_<?php echo esc_attr($param['id']) ?>">
                </div><?php
                break;
            case 'text':
            case 'textfield':
                ?>
                <div class="form-line">
                <input type="text" class="form-control input-field" placeholder=""
                       id="{_unique_id_}_<?php echo esc_attr($param['id']) ?>">
                </div><?php
                break;
            case 'textarea':
                ?>
                <div class="form-line">
                <textarea class="form-control input-field" placeholder=""
                          id="{_unique_id_}_<?php echo esc_attr($param['id']) ?>"></textarea>
                </div><?php
                break;
            case 'dropdown':
            case 'select':
                ?>
                <select class="form-control input-field" id="{_unique_id_}_<?php echo esc_attr($param['id']) ?>">
                    <?php foreach ($param['options'] as $slug => $title): ?>
                        <option value="<?php echo esc_attr($slug) ?>"><?php echo esc_html($title) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
                break;
            case 'checkbox':
                ?>
                <input type="checkbox" class="chk-col-light-blue input-field"
                       placeholder="" id="{_unique_id_}_<?php echo esc_attr($param['id']) ?>">
                <label for="{_unique_id_}_<?php echo esc_attr($param['id']) ?>"><?php if (!empty($param['label'])) echo esc_html($param['label']) ?></label>
                <?php
                break;
        }
        ?></div><?php
        if (!empty($description))
            $this->show_description($description);
        return ob_get_clean();
    }

    function admin_template_dynamic_table($args = [])
    {
        $ex_args = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'heading'     => '',
            'attr'        => '',
            'description' => '',
            'layout'      => '',
            'value'       => '',
            'has_order'   => false,
            'params'      => [
                [
                    'class'   => '',
                    'id'      => '',
                    'heading' => '',
                    'attr'    => ''
                ]//field
            ],
        ]);
        extract($ex_args);
        if (!is_array($params))
            return;
        $input_class = ['input-field', 'table-data-field'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (empty($layout))
            $layout = [intval(10 / count($params))];
        elseif (!is_array($layout))
            $layout = explode(',', $layout);
        if (!is_string($value))
            $value = json_encode($value);
        if (!is_array($attr))
            $attr = [];
        $attr['id'] = $id;
        $attr['name'] = $id;
        $attr['type'] = 'hidden';
        $attr['class'] = $input_class;
        $attr['value'] = $value;
        ?>
        <div class="fields-group row dynamic-table">
            <h3 class="header"><?php echo esc_html($heading) ?></h3>
            <table class="col-lg-12 materialize">
                <?php
                $mask = "{{_th_}}";
                ob_start();
                ?>
                <tr class='single-field' data-id='{_unique_id_}'><?php echo esc_html($mask) ?>
                    <?php if ($has_order) {
                        ?>
                        <td class='col-lg-1 order'>
                            <div class='form-group no-margin-bot'>
                                <div class="form-line">
                                    <input type="number" class="form-control input-field" min="1" max="1000" placeholder=""
                                           id="{_unique_id_}_order">
                                </div>
                            </div>
                        </td><?php
                    } ?>
                    <td class='col-lg-1 special-action'>
                        <div class='form-group'>
                            <div class='btn waves-effect btn-remove-field'>
                                <div class='dashicons dashicons-trash'></div>
                            </div>
                        </div>
                    </td>
                </tr><?php
                $template = ob_get_clean();
                ob_start();
                $col_mask = '{{_col_}}';
                $field_mask = '{{_field_}}';
                ?>
                <tr class='single-field extend-editor' data-id='{_unique_id_}'>
                    <td colspan="<?php echo esc_attr($col_mask) ?>">
                        <div class="row view-port">
                            <?php echo esc_html($field_mask) ?>
                        </div>
                    </td>
                </tr><?php
                $extends_template = ob_get_clean();
                ob_start();
                $label_mask = '{{_label_}}';
                $input_mask = '{{_input_}}';
                $attr_mask = '{{_attr_}}';
                ?>
                <div <?php echo esc_html($attr_mask) ?>>
                    <div class="col-lg-3 align-right">
                        <label class="custom_label"><?php echo esc_html($label_mask) ?></label>
                    </div>
                    <div class="col-lg-9 align-left "><?php echo esc_html($input_mask) ?></div>
                </div>

                <?php echo esc_html($field_mask);
                $extends_field = ob_get_clean();
                ?>
                <tr><?php
                    $col_used = 0;
                    foreach ($params as $index => $param) {
                        if ($param['type'] == 'extends')
                            continue;
                        $layout_col = !empty($layout[$index]) ? $layout[$index] : end($layout);
                        if ($layout_col == 'group') {
                            $layout_col = 10 - $col_used;
                            $label = '';
                            ob_start();
                            ?>
                        <td class="col-lg-<?php echo esc_attr($layout_col) ?>">
                                <div class="form-group">
                                    <div class="btn waves-effect btn-extend-editor">
                                        <div class="dashicons dashicons-arrow-down"></div>
                                    </div>
                                </div>
                            </td><?php
                            $template_col = ob_get_clean();
                            $break = true;
                        } else {
                            $col_used += $layout_col;
                            $input = $this->table_field_template($param);
                            $label = $param['heading'];
                            $template_col = "<td class='col-lg-{$layout_col}'>{$input}</td>{$mask}";
                        }
                        $template = str_replace($mask, $template_col, $template);
                        ?>
                    <th class="<?php echo esc_attr("col-lg-{$layout_col}") ?>">
                        <label class="custom_label"><?php echo esc_html($label) ?></label>
                        </th><?php
                        if (!empty($break)) {
                            $extends_template = str_replace($col_mask, $index + 1, $extends_template);
                            break;
                        }
                    }
                    if (!empty($break)) {
                        for ($i = $index; $i < count($params); $i++) {
                            $label = $params[$i]['heading'];
                            $input = $this->table_field_template($params[$i]);
                            $dependency = '';
                            if (!empty($params[$i]['dependency'])) {
                                $dependency_params = $params[$i]['dependency'];
                                $dependency_params['element'] = '{_unique_id_}_' . $dependency_params['element'];
                                $dependency = 'class="ef4-dependency" data-match="' . $this->build_dependency_match($dependency_params) . '"';
                            }
                            $current_field = str_replace([$label_mask, $input_mask, $attr_mask], [$label, $input, $dependency], $extends_field);
                            $extends_template = str_replace($field_mask, $current_field, $extends_template);
                        }
                        $extends_template = str_replace($field_mask, '', $extends_template);
                        $template .= $extends_template;
                    }
                    $template = str_replace($mask, '', $template);
                    if ($has_order) { ?>
                        <th class="col-lg-1"><?php esc_html_e('Order', 'ef4-framework') ?></th>
                    <?php
                    }
                    ?>
                    <th class="col-lg-1"></th>
                </tr>
                <tr class="action-row">
                    <td colspan="<?php echo esc_attr(count($params)) ?>">
                        <input <?php $this->show_attrs($attr) ?>>
                        <div class="btn waves-effect btn-add-field"
                             data-template="<?php echo esc_attr($template) ?>"
                        ><?php esc_html_e('+ Add more fields', 'zodonations') ?></div>
                    </td>
                </tr>
            </table>
            <?php $this->show_description($description) ?>
        </div>
        <?php
    }

    function admin_template_group($args = [])
    {
        $group_args = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'heading'     => '',
            'description' => '',
            'attr'        => '',
            'params'      => []
        ]);
        do_action('ef4_admin_template', [
            'type'    => 'header',
            'heading' => $group_args['heading']
        ]);
        $description = $group_args['description'];
        $attrs = (is_array($group_args['attr'])) ? $group_args['attr'] : [];
        $attrs['class'] = $group_args['class'];
        $attrs['name'] = $attrs['id'] = $group_args['id'];
        $attrs['value'] = is_string($group_args['value']) ? $group_args['value'] : json_encode($group_args['value']);
        if (isset($attrs['type']))
            unset($attrs['type']);
        $gr_id = $group_args['id'];
        ?>
    <div class="ef4-groups" data-id="<?php echo esc_attr($gr_id) ?>">
        <?php $this->show_description($description) ?>

        <?php foreach ($group_args['params'] as $param) {
        $param = wp_parse_args($param, [
            'class' => '',
            'id'    => ''
        ]);
        $param['class'] .= ' group-element';
        $param['id'] = $gr_id . '-' . $param['id'];
        do_action('ef4_admin_template', $param);
    } ?><input type="hidden" <?php $this->show_attrs($attrs) ?> >
        </div><?php
    }

    function admin_template_header($args = [])
    {
        $args = wp_parse_args($args, [
            'heading'     => '',
            'description' => ''
        ]);
        $description = $args['description'];
        if (!empty($args['heading'])) {
            ?><h3 class="header"><?php echo esc_html($args['heading']) ?></h3><?php
        }
        $this->show_description($description);
    }

    function admin_template_textarea($args = [])
    {
        $params = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'rows'        => '',
            'heading'     => '',
            'description' => '',
            'attr'        => ''
        ]);
        extract($params);
        $input_class = ['input-field', 'form-control'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (!is_array($attr))
            $attr = [];
        $attr['id'] = $id;
        $attr['name'] = $id;
        $attr['class'] = $input_class;
        if (is_numeric($params['rows']))
            $params['rows'] = intval($params['rows']);
        ?>
        <div class="fields-group row">
            <div class="col-lg-2 no-margin-bot">
                <label class="custom_label" for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="col-lg-10">
                <div class="form-group no-margin-bot">
                    <div class="form-line">
                        <textarea <?php $this->show_attrs($attr) ?>><?php echo esc_html($value) ?></textarea>
                    </div>
                </div>
                <?php $this->show_description($description) ?>
            </div>
        </div>
        <?php
    }

    function show_attrs($atts = [])
    {
        foreach ($atts as $key => $value) {
            switch ($key) {
                case 'class':
                    if (is_array($value))
                        $value = join(' ', $value);
                    ?>class="<?php echo esc_attr($value) ?>"<?php
                    break;
                default:
                    if (is_array($value))
                        $value = json_encode($value);
                    echo esc_html($key); ?>="<?php echo esc_attr($value) ?>"<?php
                    break;
            }
        }
    }

    function show_description($description = '')
    {
        if (!is_array($description))
            $description = [$description];
        ?>
        <div class="description">
        <?php echo wp_kses_post(join('<br>', $description)); ?>
        </div><?php
    }

    function admin_template_select($args = [])
    {
        $params = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'heading'     => '',
            'description' => '',
            'options'     => [],
            'attr'        => []
        ]);
        extract($params);
        $input_class = ['input-field', 'form-control'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (!is_array($options))
            $options = [];
        if (!is_array($attr))
            $attr = [];
        $attrs = [
            'id'               => $id,
            'name'             => $id,
            'class'            => $input_class,
            'data-live-search' => 'true'
        ];
        $attrs = array_merge($attr, $attrs);
        ?>
        <div class="fields-group row">
            <div class="col-lg-2 no-margin-bot">
                <label class="custom_label" for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="col-lg-10">
                <div class="form-group no-margin-bot">
                    <select <?php $this->show_attrs($attrs) ?>>
                        <?php foreach ($options as $val => $title) {
                            ?>
                            <option
                            value="<?php echo esc_attr($val) ?>" <?php selected($val, $value) ?>><?php echo esc_html($title) ?></option><?php
                        } ?>
                    </select>
                </div>
                <?php if (!empty($description)) {
                    ?>
                    <div class="description">
                        <?php echo wp_kses_post(is_array($description) ? join('<br>', $description) : $description) ?>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
        <?php
    }

    function admin_template_textfield($args = [])
    {
        $params = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'heading'     => '',
            'description' => '',
            'attr'        => ''
        ]);
        extract($params);
        $input_class = ['input-field', 'form-control'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (!is_array($attr))
            $attr = [];
        $attr['id'] = $id;
        $attr['name'] = $id;
        $attr['class'] = $input_class;
        $attr['type'] = 'text';
        $attr['value'] = $value;

        ?>
        <div class="fields-group row">
            <div class="col-lg-2 no-margin-bot">
                <label class="custom_label" for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="col-lg-10">
                <div class="form-group no-margin-bot">
                    <div class="form-line">
                        <input <?php $this->show_attrs($attr) ?>>
                    </div>
                </div>
                <?php $this->show_description($description) ?>
            </div>
        </div>
        <?php
    }

    function admin_template_file($args = [])
    {
        $params = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'heading'     => '',
            'description' => '',
            'attr'        => ''
        ]);
        extract($params);
        $input_class = ['input-field', 'form-control'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (!is_array($attr))
            $attr = [];
        $attr['id'] = $id;
        $attr['name'] = $id;
        $attr['class'] = $input_class;
        $attr['type'] = 'file';
        $attr['value'] = $value;

        ?>
        <div class="fields-group row">
            <div class="col-lg-2 no-margin-bot">
                <label class="custom_label" for="<?php echo esc_attr($id) ?>"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="col-lg-10">
                <div class="form-group no-margin-bot">
                    <div class="form-line">
                        <input <?php $this->show_attrs($attr) ?>>
                    </div>
                </div>
                <?php $this->show_description($description) ?>
            </div>
        </div>
        <?php
    }

    function admin_template_checkbox($args = [])
    {
        $params = wp_parse_args($args, [
            'class'       => '',
            'id'          => '',
            'value'       => '',
            'options'     => '',
            'cols'        => '',
            'heading'     => '',
            'description' => '',
            'attr'        => ''
        ]);
        extract($params);
        $input_class = ['input-field', 'checkbox-save'];
        if (!empty($class))
            $input_class[] = trim($class);
        if (!is_array($options))
            $options = [];
        if (!is_array($attr))
            $attr = [];
        $attr['id'] = $id;
        $attr['name'] = $id;
        $attr['class'] = $input_class;
        $attr['type'] = 'hidden';
        $attr['value'] = $value;
        ?>
        <div class="fields-group row checkbox-group">
            <div class="col-lg-2 no-margin-bot"><label class="custom_label"><?php echo esc_html($heading) ?></label>
            </div>
            <div class="col-lg-10">
                <?php
                $index = 0;
                $max_col = intval($cols);
                if ($max_col < 1)
                    $max_col = 1;
                $values = explode(',', $value);
                foreach ($options as $val => $label) {
                    if ($index % $max_col === 0) {
                        $need_end = true;
                        ?><div class="row"><?php
                    }
                    $id_cb = uniqid($id);
                    $current_col = intval(12 / $max_col);
                    $class_col = 'col-lg-' . $current_col;
                    ?>
                    <div class="<?php echo esc_attr($class_col) ?> no-margin-bot">
                        <input type="checkbox" data-value="<?php echo esc_attr($val) ?>"
                               id="<?php echo esc_attr($id_cb) ?>" <?php checked(true, in_array($val, $values)) ?>
                               class="chk-col-light-blue">
                        <label for="<?php echo esc_attr($id_cb) ?>"><?php echo esc_html($label) ?></label>
                    </div>
                    <?php
                    if ($index % $max_col === $max_col - 1) {
                        $need_end = false;
                        ?></div><?php
                    }
                    $index++;
                }
                if (!empty($need_end))
                {
                ?></div><?php
            }
            ?>
            <input <?php $this->show_attrs($attr) ?>>
            <?php $this->show_description($description) ?>
        </div>
        </div>
        <?php
    }
}