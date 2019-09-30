<?php
vc_map(array(
    "name"        => 'Cms Custom Heading',
    "base"        => "cms_custom_heading",
    "icon"        => "cs_icon_for_vc",
    "category"    =>  esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    "description" =>  '',
    "params" => array(  
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Heading Style', 'acumec' ),
            'param_name' => 'heading_style',
            'value' => array(
                esc_html__( 'Style 1', 'acumec' ) => 'style1',
                esc_html__( 'Style 2', 'acumec' ) => 'style2',
                esc_html__( 'Style 3', 'acumec' ) => 'style3',
            ), 
            'std' =>'style1'          
        ),

        array(
            "type"       => "dropdown",
            "heading"    => esc_html__("Choose Media Type",'acumec'),
            "param_name" => "media_type",
            'value' => array(
                esc_html__( 'Image', 'acumec' ) => 'image',
                esc_html__( 'Icon', 'acumec' ) => 'icon',
            ),
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type" => "attach_image",
            "param_name" => "image_type",
            "heading" => esc_html__("Image Item",'acumec'),
            'dependency' => array(
                'element' => 'media_type',
                'value' => array(
                    'image',
                ),
            ),
        ),
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Image Width",'acumec'),
            "param_name" => "image_width",
            'dependency' => array(
                'element' => 'media_type',
                'value' => array(
                    'image',
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
                'element' => 'media_type',
                'value' => array(
                    'icon',
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
            "type"       => "textfield",
            "heading"    => esc_html__("Title Before",'acumec'),
            "param_name" => "title1",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Title",'acumec'),
            "param_name" => "title",
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Title After",'acumec'),
            "param_name" => "title2",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Font Weight Main Title",'acumec'),
            "param_name" => "fweightm",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Font Family",'acumec'),
            "param_name" => "ffamily",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Font Weight",'acumec'),
            "param_name" => "fweight",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Font Size",'acumec'),
            "param_name" => "fsize",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Line Height",'acumec'),
            "param_name" => "lheight",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ),
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Letter Spacing",'acumec'),
            "param_name" => "spacing",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "dropdown",
            "heading"    => esc_html__("Text Align",'acumec'),
            "param_name" => "ttext-align",
            'value' => array(
                esc_html__( 'Left', 'acumec' ) => 'text-left',
                esc_html__( 'Center', 'acumec' ) => 'text-center',
                esc_html__( 'Right', 'acumec' ) => 'text-right',
            ),
        ),
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Icon color (For icon be selected)", 'acumec'),
            "param_name" => "icon_color",
            "value"      => "",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style2',
                    'style3',
                ),
            ),
        ), 
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Title color", 'acumec'),
            "param_name" => "title_color",
            "value"      => "",
        ), 
        array(
            "type"       => "colorpicker",
            "class"      => "",
            "heading"    => esc_html__("Main Title color  ", 'acumec'),
            "param_name" => "main_title_color",
            "value"      => "",
            'dependency' => array(
                'element' => 'heading_style',
                'value' => array(
                    'style3',
                ),
            ),
        ), 
        
         
 
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__( 'CSS box', 'acumec' ),
            'param_name' => 'css',
            'group'      => esc_html__( 'Design Options', 'acumec' ),
        ),
        
    )
));
class WPBakeryShortCode_cms_custom_heading extends CmsShortCode
{
    protected function content($atts, $content = null){
        $html_id = cmsHtmlID('cms-custom-heading');
         
        $atts['html_id'] = $html_id; 
        return parent::content($atts, $content);
    }
  
}
 

?>