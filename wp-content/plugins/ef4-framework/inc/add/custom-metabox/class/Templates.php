<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/2/2018
 * Time: 3:51 PM
 */

namespace ef4_metabox;


class Templates
{
    public $script_template;

    public function init()
    {
        add_action('ef4_metabox_template', [$this, 'admin_template']);
    }

    function admin_template($args = [])
    {
        if (empty($args['type']))
            return;
        $args = wp_parse_args($args, [
            'id'          => '',
            'label'       => '',
            'description' => '',
            'type'        => '',
        ]);
        if (empty($args['class']))
            $args['class'] = [];
        if (!is_array($args['class']))
            $args['class'] = [$args['class']];
        $args['class'][] = $args['type'];
        switch ($args['type']) {
            case 'date':
                $args['class'][] = 'flatpickr-date';
                break;
            case 'datetime':
                $args['class'][] = 'flatpickr-datetime';
                break;
        }
        $call_name = 'admin_template_' . $args['type'];
        if (!is_callable([$this, $call_name]))
            $call_name = 'admin_template_text';
        $attr = [];
        $attr['class'] = ['row'];
        if (!empty($args['dependency'])) {
            $attr['class'][] = 'ef4-dependency';
            $attr['data-match'] = $this->build_dependency_match($args['dependency']);
        }
        if (is_callable([$this, $call_name])) {
            ?>
        <div <?php $this->show_attrs($attr) ?>>
            <div class="fields-group">
                <div class="col-lg-2">
                    <label for="ef4_<?php echo esc_attr($args['id']) ?>">
                        <?php echo esc_html((!empty($args['label'])) ? $args['label'] : $args['id']) ?>
                    </label>
                </div>
                <div class="col-lg-10">
                    <div class="form-group no-margin-bot">
                        <?php $this->$call_name($args);
                        $this->show_description($args['description']); ?>
                    </div>
                </div>
            </div>
            </div><?php

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

    function admin_template_textarea($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'form-control';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        ?>
        <div class="form-line">
            <textarea <?php $this->show_attrs($attr) ?>><?php echo esc_html($field['value']) ?></textarea>
        </div>
        <?php
    }

    function admin_template_gallery($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['type'] = 'hidden';
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'gallery-value';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        $attr['value'] = $field['value'];
        ob_start();
        ?>
        <div class="col-lg-4">
             <span class="thumbnail">
                  <img src="{{_url_}}"/>
             </span>
        </div>
        <?php
        $template = ob_get_clean();
        $raw_data = explode(',', $field['value']);
        $urls = [];
        foreach ($raw_data as $img_id) {
            $check = (is_numeric($img_id)) ? wp_get_attachment_url($img_id) : '';
            if (!empty($check))
                $urls[] = $check;
        }
        ?>
        <div class="gallery-picker-group">
            <div class="row">
                <div class="gallery-preview col-lg-12"
                     data-template="<?php echo esc_attr($template) ?>">
                    <?php if (!empty($urls)) foreach ($urls as $index => $url) {
                        if ($index % 3 === 0) {
                            ?><div class="row"><?php
                        }
                        echo wp_kses_post(str_replace('{{_url_}}', $url, $template));
                        if ($index % 3 === 2 || $index == count($urls) - 1) {
                            ?></div><?php
                        }
                    } ?>
                </div>
            </div>
            <div class="row">
                <input <?php $this->show_attrs($attr) ?>>
                <div class="btn waves-effect gallery-select"
                     data-title="<?php esc_html_e('Select Gallery', 'ef4-framework') ?>">
                    <?php esc_html_e('Add/Edit Gallery', 'ef4-framework') ?></div>
                <div class="btn waves-effect gallery-remove"><?php esc_html_e('Clear Gallery', 'ef4-framework') ?></div>
            </div>
        </div>
        <?php
    }

    function admin_template_image($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['type'] = 'hidden';
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'image-value';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        $attr['value'] = $field['value'];
        $img = (is_numeric($field['value'])) ? wp_get_attachment_url($field['value']) : '';
        ?>
        <div class="image-picker-group">
            <div class="row">
                <div class="col-lg-6">
                    <span class="image-preview">
                        <?php if (!empty($img)) : ?>
                            <img src="<?php echo esc_attr($img); ?>"/>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="row">
                <input <?php $this->show_attrs($attr) ?>>
                <div class="btn waves-effect image-select"
                     data-title="<?php esc_html_e('Select Image', 'ef4-framework') ?>">
                    <?php esc_html_e('Add/Edit Image', 'ef4-framework') ?></div>
                <div class="btn waves-effect image-remove"><?php esc_html_e('Remove Image', 'ef4-framework') ?></div>
            </div>
        </div>
        <?php
    }

    function admin_template_checkbox($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['type'] = 'hidden';
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'checkbox-save';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        $attr['value'] = $field['value'];
        $options_is_post_id = (strpos($field['options'], 'post_type:') !== false) ? true : false;
        $options = ef4()->parse_options_select($field['options']);
        ?>
        <div class="checkbox-group">
        <?php
        $index = 0;
        $cols = !empty($field['cols']) ? $field['cols'] : 1;
        $max_col = intval($cols);
        if ($max_col < 1)
            $max_col = 1;
        $value = $field['value'];
        $id = 'ef4_' . $field['id'];
        $values = explode(',', $value);
        foreach ($options as $val => $label) {
            if ($index % $max_col === 0) {
                $need_end = true;
                ?>
                <div class="row"><?php
            }
            $id_cb = uniqid($id);
            $current_col = intval(12 / $max_col);
            $class_col = 'col-lg-' . $current_col;
            ?>
            <div class="<?php echo esc_attr($class_col) ?> no-margin-bot">
                <input type="checkbox"
                       data-value="<?php echo esc_attr($val) ?>"
                       id="<?php echo esc_attr($id_cb) ?>" <?php checked(true, in_array($val, $values)) ?>
                       class="chk-col-light-blue">
                <label for="<?php echo esc_attr($id_cb) ?>"><?php echo esc_html($label) ?></label>
                <?php if ($options_is_post_id && is_numeric($val)) {
                    ?><a
                    href="<?php echo get_edit_post_link($val) ?>"><?php esc_html_e('Edit', 'ef4-framework') ?></a><?php
                } ?>
            </div>
            <?php
            if ($index % $max_col === $max_col - 1) {
                $need_end = false;
                ?></div><?php
            }
            $index++;
        }
        if (!empty($need_end)) {
            ?></div><?php
        }
        ?>
        <input <?php $this->show_attrs($attr) ?>>
        </div>
        <?php
    }

    function admin_template_color($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['type'] = 'text';
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'color-field input-field input-settings';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        $attr['value'] = $field['value']
        ?>
        <div class="content-box">
            <input <?php $this->show_attrs($attr) ?>>
        </div>
        <?php
    }

    function admin_template_text($args)
    {
        $field = wp_parse_args($args, [
            'class' => '',
            'attr'  => '',
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['type'] = 'text';
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'form-control';
        if ($field['type'] == 'number') {
            $attr['type'] = 'number';
            $attr['step'] = (!empty($field['step'])) ? $field['step'] : '1';
        }
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        $attr['value'] = $field['value'];
        ?>
        <div class="form-line">
            <input <?php $this->show_attrs($attr) ?>>
        </div>
        <?php
    }

    function admin_template_select($args)
    {
        $field = wp_parse_args($args, [
            'class'   => '',
            'attr'    => '',
            'options' => ''
        ]);
        $attr = (!empty($field['attr'])) ? $field['attr'] : [];
        if (!is_array($attr))
            $attr = [$attr];
        $attr['class'] = !empty($field['class']) ? $field['class'] : [];
        if (!is_array($attr['class']))
            $attr['class'] = [$attr['class']];
        $attr['class'][] = 'form-control';
        $attr['id'] = $attr['name'] = "ef4_{$field['id']}";
        if (empty($attr['data-live-search']))
            $attr['data-live-search'] = "true";
        $options = (is_array($field['options'])) ? $field['options'] : ef4()->parse_options_select($field['options']);

        ?>
        <select <?php $this->show_attrs($attr) ?>>
            <?php foreach ($options as $value => $title): ?>
                <option value="<?php echo esc_attr($value) ?>" <?php selected($field['value'], $value) ?>><?php echo esc_html($title) ?></option>
            <?php endforeach; ?>
        </select>
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

    function build_dependency_match($args)
    {
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


}