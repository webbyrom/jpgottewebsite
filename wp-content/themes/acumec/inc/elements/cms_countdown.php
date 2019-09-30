<?php
vc_map(
	array(
		"name" => esc_html__("CMS Countdown",  'acumec'),
	    "base" => "cms_countdown",
	    "class" => "vc-cms-countdown",
	    "category" => esc_html__("CmsSuperheroes Shortcodes",  'acumec'),
	    "params" => array(
	        array(
	            "type" => "cms_time",
	            "heading" => esc_html__("Date count down", 'acumec'),
	            "param_name" => "date_count_down",
	            "value" => "",
	            'admin_label' => true,
	            'dependency'=>array(
                	'callback'=>'trigger_datetimepicker_field'
            	),
	        ),
	    )
	)
);
class WPBakeryShortCode_cms_countdown extends CmsShortCode{ 
	protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}