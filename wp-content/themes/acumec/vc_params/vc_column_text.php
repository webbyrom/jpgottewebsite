<?php
/**
 * Add row params
 * 
 * @author Knight
 * @since 1.0.0
 */

vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Font size",'acumec'),
    "param_name" => "font_size",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Font Weight",'acumec'),
    "param_name" => "font_weight",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Font Style",'acumec'),
    "param_name" => "font_style",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Font Family",'acumec'),
    "param_name" => "font_family",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Line height",'acumec'),
    "param_name" => "line_height",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param("vc_column_text", array(
    "type" => "textfield",
    "heading" => esc_html__("Letter spacing (0.3px, 0.03em)",'acumec'),
    "param_name" => "letter_spacing",
    "value" => "",
    "group" => esc_html__("Other setting",'acumec')
));
vc_add_param('vc_column_text', array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => esc_html__("Color", 'acumec'),
    "param_name" => "color",
    "value" => "",
	'group' => esc_html__("Other setting",'acumec'),
)); 