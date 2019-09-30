<?php 
	vc_remove_param('cms_progressbar', 'width');
	vc_remove_param('cms_progressbar', 'mode');
	vc_remove_param('cms_progressbar', 'bg_color');
 ?>
<?php 
	$params = array( 
		array(
			'type' => 'dropdown',
	        'heading' => esc_html__("Layout Style", 'acumec'),
	        'param_name' => 'layout_style',
	        'value' => array(
	            'Layout 1' => 'layout_style1',
	            'Layout 2' => 'layout_style2',
	        ),
	        'std' => 'layout_style1',
		), 
		array(
	   		"type"       => "colorpicker",
	        "heading"    => esc_html__("Background Color bar", 'acumec'),
	        "param_name" => "bg_color_bar",
	        "value"      => "",
	        'group' => esc_html__( 'Settings ', 'acumec' ),
		),
		array(
	   		"type"       => "colorpicker",
	        "heading"    => esc_html__("Title Color", 'acumec'),
	        "param_name" => "title_color",
	        "value"      => "",
	        'group' => esc_html__( 'Settings ', 'acumec' ),
		),
    );
	vc_add_param("cms_progressbar", array(
   		'type' => 'css_editor',
	    'heading' => esc_html__( 'CSS box', 'acumec' ),
	    'param_name' => 'css',
	    'group' => esc_html__( 'Design Options', 'acumec' ),
	));   
?>