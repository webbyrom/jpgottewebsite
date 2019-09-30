<?php
vc_map(array(
    "name" => 'CMS Button',
    "base" => "cms_button",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    "description" => esc_html__('Show social from theme option', 'acumec'),
    "params" => array(
        array(
        	"type" => "textfield",
            "heading" => esc_html__("Title",'acumec'),
            "param_name" => "title",
            "value" => "",
            "admin_label" => true,
            "description" => esc_html__("Title",'acumec'),
        ),
        array(
        	'type' => 'vc_link',
            'heading' => esc_html__( 'URL (Link)', 'acumec' ),
            'param_name' => 'link',
            'description' => esc_html__( 'Add link to button.', 'acumec' ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Button Style",'acumec'),
            "param_name" => "btn_style",
            "value" => array(
                'Theme' => 'btn-theme',
                'Bootstrap' => 'btn-bootstrap', 
            ),
            "std" => 'btn-theme',
        ),
        array(
        	"type" => "dropdown",
        	"heading" => esc_html__("Type",'acumec'),
        	"param_name" => "btn_type_bootstrap",
        	"value" => array(
                'Primary' => 'btn-primary',
                'Success' => 'btn-success',
                'Info' => 'btn-info',
                'Warning' => 'btn-warning',
                'Danger' => 'btn-danger'
            ),
            "std" => 'btn-primary',
            'dependency' => array(
                'element' => 'btn_style',
                'value' => 'btn-bootstrap',
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Type",'acumec'),
            "param_name" => "btn_type_theme",
            "value" => array(
                'Default' => 'btn-theme-default',
                'Primary' => 'btn-theme-primary',
            ),
            "std" => 'btn-theme-default',
            'dependency' => array(
                'element' => 'btn_style',
                'value' => 'btn-theme',
            ),
        ),
        array(
        	"type" => "dropdown",
        	"heading" => esc_html__("Size",'acumec'),
        	"param_name" => "size",
        	"value" => array(
                esc_html__('Large','acumec') => 'btn-lg',
                esc_html__('Medium','acumec') => 'btn-md',
                esc_html__('Small','acumec') => 'btn-sm',
                esc_html__('Mini','acumec') => 'btn-mn',
                esc_html__('Default','acumec') => 'btn-sdefault',
                esc_html__('Custom','acumec')  => 'btn-custom',
            ),
            "std" => 'btn-md',
            "description" => esc_html__( 'Select button size.', 'acumec' ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Spacing ",'acumec'),
            "param_name" => "spacing",
            "value" => "",
            "admin_label" => true,
            "description" => esc_html__("Padding ( ex: 15px 45px 15px 45px )",'acumec'),
            'dependency' => array(
                'element' => 'size',
                'value' => 'btn-custom',
            ),
        ),
        array(
        	"type" => "dropdown",
        	"heading" => esc_html__("Alignment",'acumec'),
        	"param_name" => "align",
        	"value" => array(
                'inline' => 'inline',
                'left' => 'left',
                'right' => 'right',
                'center' => 'center',
            ),
            "std" => 'inline',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Set full width button?", 'acumec'),
            'param_name' => 'button_block',
            'value' => array(
                'Yes' => true
            ),
            'dependency' => array(
                'element' => 'align',
                'value' => array(
                    'left',
                    'right',
                    'center',
                ),
            ),
            'std' => false,
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Border Radius",'acumec'),
            "param_name" => "border_radius",
            "value" => "",
            "admin_label" => true,
            "description" => esc_html__("Border Radius with 10px, 20px, 50%...",'acumec'),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Button With Background Transparent", 'acumec'),
            'param_name' => 'button_bg',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Color With Background Transparent ",'acumec'),
            "param_name" => "color_transparent",
            "value" => array(
                esc_html__('Dark','acumec') => 'dark',
                esc_html__('Light','acumec') => 'light',
            ),
            'dependency' => array(
                'element' => 'button_bg',
                'value' => array(
                    '1',
                ),
            ),
            "std" => 'dark',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Add icon", 'acumec'),
            'param_name' => 'add_icon',
            'value' => array(
                'Yes' => true
            ),
            'std' => false,
        ),
        array(
        	"type" => "dropdown",
        	"heading" => esc_html__("Icon Alignment",'acumec'),
        	"param_name" => "i_align",
        	"value" => array(
                'Left' => 'left',
                'Right' => 'right',
            ),
            'dependency' => array(
                'element' => 'add_icon',
                'value' => array(
                    '1',
                ),
            ),
            "std" => 'left',
        ),
        
        array(
            'type' => 'dropdown',
        	'heading' => esc_html__( 'Icon library', 'acumec' ),
        	'value' => array(
        		esc_html__( 'Font Awesome', 'acumec' ) => 'fontawesome',
                esc_html__( 'P7 Stroke', 'acumec' ) => 'pe7stroke',
        	),
        	'param_name' => 'icon_type',
        	'description' => esc_html__( 'Select icon library.', 'acumec' ),
            'dependency' => array(
                'element' => 'add_icon',
                'value' => array(
                    '1',
                ),
            ),
        ),
        array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon Item', 'acumec' ),
			'param_name' => 'icon_fontawesome',
            'value' => '',
			'settings' => array(
				'emptyIcon' => true,  
				'type' => 'fontawesome',
				'iconsPerPage' => 200,  
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'fontawesome',
			),
			'description' => esc_html__( 'Select icon from library.', 'acumec' ),
		 
		),
        array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon Item', 'acumec' ),
			'param_name' => 'icon_pe7stroke',
            'value' => '',
			'settings' => array(
				'emptyIcon' => true, 
				'type' => 'pe7stroke',
				'iconsPerPage' => 200,  
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'pe7stroke',
			),
			'description' => esc_html__( 'Select icon from library.', 'acumec' ),	 
		),
         
        array(
        	"type" => "textfield",
            "heading" => esc_html__("Class",'acumec'),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("Class",'acumec'),
        ), 
        array(
        	'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'acumec' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design Options', 'acumec' ),
        )
    )
));
class WPBakeryShortCode_cms_button extends CmsShortCode
{
    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}