<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css_animation
 * @var $css
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_text
 */
$el_class = $el_id = $css = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'wpb_text_column wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$style = '';
if(!empty($font_size) || !empty($font_weight) || !empty($font_family) || !empty($line_height) || !empty($color) || !empty($font_style) || !empty($letter_spacing)){
    $style .= ' style="';
        if(!empty($font_size)){
            if(strpos($font_size,'px') == false) $font_size.='px';
            $style .= 'font-size :'.$font_size.';';
        } 
        if(!empty($font_weight)){
            $style .= 'font-weight :'.$font_weight.';'; 
        } 
        if(!empty($font_family)){
            $style .= 'font-family :'.$font_family.';'; 
        } 
        if(!empty($font_style)){
            $style .= 'font-style :'.$font_style.';';
        } 
        if(!empty($line_height)){
            if(strpos($line_height,'px') == false) $line_height.='px';
            $style .= 'line-height :'.$line_height.';'; 
        } 
        if(!empty($color)){
            $style .= 'color :'.$color.';'; 
        } 
        if(!empty($letter_spacing)){
            $style .= 'letter-spacing :'.$letter_spacing.';';
        }
    $style .= '"';
}

$output = '
    <div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>
        <div class="wpb_wrapper"'.$style.'>
            ' . wpb_js_remove_wpautop( $content, true ) . '
        </div>
    </div>
';

echo acumec_html($output);

 