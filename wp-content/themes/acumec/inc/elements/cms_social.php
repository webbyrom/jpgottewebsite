<?php
vc_map(array(
    "name" => 'Cms Social',
    "base" => "cms_social",
    "icon" => "cs_icon_for_vc",
    "category" =>  esc_html__('CmsSuperheroes Shortcodes', 'acumec'),
    "description" =>  '',
    "params" => array(  
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Style', 'acumec' ),
            "param_name" => "style",
            'value' => array(
                esc_html__( 'Style 1', 'acumec' ) => 'style1',
                esc_html__( 'Style 2', 'acumec' ) => 'style2',
            ),
            'std' => 'default',
        ),       
        array(
            'type' => 'param_group',
            'heading' => esc_html__( 'Options', 'acumec' ),
            'param_name' => 'options',
            'description' => esc_html__( 'Enter values for plan option', 'acumec' ),
            'value' => urlencode( json_encode( array(
                array(
                    'values' => esc_html__( 'Option', 'acumec' ),
                ),
            ) ) ),
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" =>esc_html__("Title",'acumec'),
                    "param_name" => "social_title",
                ),
                array(
                    "type" => "textfield",
                    "heading" =>esc_html__("Social Link",'acumec'),
                    "param_name" => "social_link",
                ),
                array(
                    "type" => "textfield",
                    "heading" =>esc_html__("Icon class (fa fa-facebook,... for Awesome Font)",'acumec'),
                    "param_name" => "icon_class",
                    "admin_label" => true,
                ),
            ),       
        ),

        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra Class",'acumec'),
            "param_name" => "class",
            "value" => "",
            "description" =>"",
        ),
    )
));
class WPBakeryShortCode_cms_social extends CmsShortCode
{
    protected function content($atts, $content = null){
        $atts_extra = shortcode_atts(array(
            'class' => '',
        ), $atts);
        $atts = array_merge($atts_extra, $atts);
         
        $class = $atts['class'];
          
        return parent::content($atts, $content);
    }
    
}
?>