<?php
/**
 * Add row params
 * 
 * @author Knight
 * @since 1.0.0
 */

vc_add_param('vc_row', array(
    'type' => 'checkbox',
    'heading' => esc_html__("Overlay opacity", 'acumec'),
    'param_name' => 'overlay_opacity',
    'value' => array(
        'Yes' => true,
    ),
    'std' => false,
    'group' => esc_html__("Design Options",'acumec'),
));
vc_add_param("vc_row", array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => esc_html__("Overlay Color", 'acumec'),
    "param_name" => "overlay_color",
    "value" => "",
    'dependency' => array(
        'element' => 'overlay_opacity',
        'value' => array(
            '1',
        ),
    ),
    'group' => esc_html__("Design Options", 'acumec'),
));
vc_add_param('vc_row', array(
    'type' => 'checkbox',
    'heading' => esc_html__("Background image fixed", 'acumec'),
    'param_name' => 'bg_fixed',
    'value' => '',
    'group' => esc_html__("Design Options",'acumec'),
));

vc_add_param('vc_row', array(
    'type' => 'checkbox',
    'heading' => esc_html__("Visible overflow", 'acumec'),
    'param_name' => 'visible_overflow',
    'value' => '',
    'group' => esc_html__("Other setting",'acumec'),
));
 