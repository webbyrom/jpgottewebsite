<?php
vc_map(array(
    "name" => 'Pricing Table Template',
    "base" => "cms_pricing_table",
    "icon" => "cs_icon_for_vc",
    "category" =>  esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    "description" =>  '',
    "params" => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pricing Style', 'acumec' ),
            'param_name' => 'pricing_style',
            'value' => array(
                esc_html__( 'Style 1', 'acumec' ) => 'style1',
                esc_html__( 'Style 2', 'acumec' ) => 'style2',
            ), 
            'std' =>'style1'          
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Is Active?", 'acumec'),
            'param_name' => 'is_active',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Media Options Type', 'acumec' ),
            'value' => array(
                esc_html__( 'Icon', 'acumec' ) => 'option_icon',
                esc_html__( 'Image', 'acumec' ) => 'option_image',
            ),
            'param_name' => 'option_type',
            'dependency' => array(
                'element' => 'pricing_style',
                'value' => array(
                    'style2',
                ),
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'acumec' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'acumec' ) => 'fontawesome',
                esc_html__( 'Flat Icon', 'acumec' ) => 'flaticon',
                esc_html__( 'Open Iconic', 'acumec' ) => 'openiconic',
                esc_html__( 'P7 Stroke', 'acumec' ) => 'pe7stroke',
                esc_html__( 'RT Icon', 'acumec' ) => 'rticon',
            ),
            'param_name' => 'icon_type',
            'description' => esc_html__( 'Select icon library.', 'acumec' ),
            'dependency' => array(
                'element' => 'option_type',
                'value' => array(
                    'option_icon',
                ),
            ),
        ),
        array(
            'type'       => 'iconpicker',
            'heading'    => esc_html__( 'Icon Item', 'acumec' ),
            'param_name' => 'icon_fontawesome',
            'value'      => '',
            'settings'   => array(
                'emptyIcon'    => true, 
                'type'         => 'fontawesome',
                'iconsPerPage' => 200,  
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value'   => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        ),
        array(
            'type'       => 'iconpicker',
            'heading'    => esc_html__( 'Icon Item', 'acumec' ),
            'param_name' => 'icon_flaticon',
            'value'      => '',
            'settings'   => array(
                'emptyIcon'    => true, 
                'type'         => 'flaticon',
                'iconsPerPage' => 200,  
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value'   => 'flaticon',
            ),
            'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        ),
        array(
            'type'       => 'iconpicker',
            'heading'    => esc_html__( 'Icon Item', 'acumec' ),
            'param_name' => 'icon_openiconic',
            'value'      => '',
            'settings'   => array(
                'emptyIcon'    => true, 
                'type'         => 'openiconic',
                'iconsPerPage' => 200,  
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value'   => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        ),
        array(
            'type'       => 'iconpicker',
            'heading'    => esc_html__( 'Icon Item', 'acumec' ),
            'param_name' => 'icon_pe7stroke',
            'value'      => '',
            'settings'   => array(
                'emptyIcon'    => true, 
                'type'         => 'pe7stroke',
                'iconsPerPage' => 200,  
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value'   => 'pe7stroke',
            ),
            'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        ),
        array(
            'type'       => 'iconpicker',
            'heading'    => esc_html__( 'Icon Item', 'acumec' ),
            'param_name' => 'icon_rticon',
            'value'      => '',
            'settings'   => array(
                'emptyIcon'    => true, 
                'type'         => 'rticon',
                'iconsPerPage' => 200,  
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value'   => 'rticon',
            ),
            'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'acumec' ),
            'param_name' => 'image2',
            'value' => '',
            'dependency' => array(
                'element' => 'option_type',
                'value' => array(
                    'option_image',
                ),
            ),
            'description' => esc_html__( 'Select image from media library.', 'acumec' ),
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'acumec' ),
            'param_name' => 'image',
            'value' => '',
            'dependency' => array(
                'element' => 'pricing_style',
                'value' => array(
                    'style1',
                ),
            ),
            'description' => esc_html__( 'Select image from media library.', 'acumec' ),
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Color", 'acumec'),
            "param_name" => "tcolor",
            "value"      => "",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Align', 'acumec' ),
            'value' => array(
                esc_html__( 'Left', 'acumec' ) => 'text-left',
                esc_html__( 'Center', 'acumec' ) => 'text-center',
                esc_html__( 'right', 'acumec' ) => 'text-right',
            ),
            'param_name' => 'align',
            'dependency' => array(
                'element' => 'pricing_style',
                'value' => array(
                    'style1',
                ),
            ),
        ),
        array(
            "type" => "textfield",
            "heading" =>esc_html__("Title",'acumec'),
            "param_name" => "title",
            'description' => esc_html( 'premium'),
        ),
        array(
            "type" => "textfield",
            "heading" =>esc_html__("Sub title",'acumec'),
            "param_name" => "sub_title",
            'description' => esc_html( 'per month'),
        ),
        array(
            "type" => "textfield",
            "heading" =>esc_html__("Pricing",'acumec'),
            "param_name" => "pricing",
            'description' => esc_html( 'Pricing'),
        ),
        array(
            "type" => "textfield",
            "heading" =>esc_html__("Prefix Pricing",'acumec'),
            "param_name" => "prefix_pricing",
            'description' => esc_html( 'Prefix Pricing'),
        ),
        array(
            "type" => "textfield",
            "heading" =>esc_html__("Sub Pricing",'acumec'),
            "param_name" => "sub_pricing",
            'description' => esc_html( 'Sub Pricing'),
        ),
        array(
            "type" => "textarea",
            "heading" =>esc_html__("Description",'acumec'),
            "param_name" => "description",
            'dependency' => array(
                'element' => 'pricing_style',
                'value' => array(
                    'style2',
                ),
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Bottom Overlap", 'acumec'),
            'param_name' => 'overlap',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
            'dependency' => array(
                'element' => 'pricing_style',
                'value' => array(
                    'style1',
                ),
            ),
        ),
        array(
            'type' => 'param_group',
            'heading' => esc_html__( 'Features', 'acumec' ),
            'param_name' => 'features',
            'description' => esc_html__( 'Enter values for feature', 'acumec' ),
            'value' => urlencode( json_encode( array(
                array( 
                    'values' => esc_html__( 'Value', 'acumec' ),
                ),
            ) ) ),
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" =>esc_html__("Attribute name",'acumec'),
                    "param_name" => "feature_name",
                    'admin_label' => true,
                ),
            ),
        ), 
        array(
            "type"        => "vc_link",
            "heading"     => esc_html__("URL (Link)",'acumec'),
            "param_name"  => "link",
            "value"       => "",
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__(" Button Background", 'acumec'),
            "param_name" => "bg_button",
            "value"      => "",
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__(" Button Color", 'acumec'),
            "param_name" => "color_button",
            "value"      => "",
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__( 'CSS box', 'acumec' ),
            'param_name' => 'css',
            'group'      => esc_html__( 'Design Options', 'acumec' ),
        ), 
    )
));

class WPBakeryShortCode_cms_pricing_table extends CmsShortCode
{
    protected function content($atts, $content = null){
        return parent::content($atts, $content);
    }
    
}

?>