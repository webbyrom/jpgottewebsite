<?php
vc_map(array(
    'name' => 'CMS Testimonial',
    'base' => 'cms_testimonial',
    'icon' => 'cs_icon_for_vc',
    'category' => esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    'description' => esc_html__('Add Testimonial', 'acumec'),
    'params' => array(
        array(
            'type'          => 'attach_image',
            'heading'       => esc_html__( 'Testimonial Image', 'acumec' ),
            'param_name'    => 'testi_avatar',
            'value'         => '',
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Background",'acumec'),
            "param_name" => "bg_testimonial",
            "value" => array(
                esc_html__('Image','acumec') => 'image',
                esc_html__('Color','acumec') => 'color',
            ),
            "std" => 'image',
        ),
        array(
            'type'          => 'attach_image',
            'heading'       => esc_html__( 'Background Image', 'acumec' ),
            'param_name'    => 'bgimage',
            'value'         => '',
            'dependency' => array(
                'element' => 'bg_testimonial',
                'value' => array(
                    'image',
                ),
            ),
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Background Color", 'acumec'),
            "param_name" => "bgcolor",
            "value"      => "",
            'dependency' => array(
                'element' => 'bg_testimonial',
                'value' => array(
                    'color',
                ),
            ),
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Description', 'acumec' ),
            'param_name'    => 'testi_des',
            'value'         => '',
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Testimonial name', 'acumec' ),
            'param_name'    => 'testi_name',
            'admin_label'   => true,
            'value'         => '',
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Testimonial Position', 'acumec' ),
            'param_name'    => 'testi_position',
            'value'         => '',
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Alignment', 'acumec' ),
            'param_name' => 'alignment',
            'value' => array(
                esc_html__( 'Left', 'acumec' ) => 'left',
                esc_html__( 'Center', 'acumec' ) => 'center',
                esc_html__( 'Right', 'acumec' ) => 'right',
            ), 
            'std' =>'left'          
        ),
        array(
            'type'          => 'attach_image',
            'heading'       => esc_html__( 'Testimonial Signature', 'acumec' ),
            'param_name'    => 'testi_signature',
            'value'         => ''
        ),
        array(
            'type'          => 'colorpicker',
            'heading'       => esc_html__( 'Testimonial text color', 'acumec' ),
            'param_name'    => 'tcolor',
            'value'         => ''
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Background Border Radius', 'acumec' ),
            'param_name'    => 'testi_borderradius',
            'value'         => '',
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__( 'CSS box', 'acumec' ),
            'param_name' => 'css',
            'group'      => esc_html__( 'Design Options', 'acumec' ),
        ),    
    )
));

class WPBakeryShortCode_cms_testimonial extends CmsShortCode
{
    protected function content($atts, $content = null){
        return parent::content($atts, $content);
    }  
}
?>