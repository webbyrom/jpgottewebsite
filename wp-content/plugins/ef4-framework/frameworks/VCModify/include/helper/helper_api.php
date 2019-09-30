<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 9/16/2017
 * Time: 9:35 AM
 */
//vc_map => add shortcode
//vc_add_param => add param to shortcode
//vc_remove_param =>remove param
//vc_update_shortcode_param => update
function ef4_vc_grid_map(array $attribute)
{
    EF4VCGrid::add_shortcode($attribute);
}
function ef4_vc_grid_add_param($shortcode,array $attribute,array $options = array())
{
    EF4VCGrid::add_param($shortcode,$attribute,$options);
}
function ef4_vc_grid_remove_param($shortcode = '', $attribute_name = '')
{
    EF4VCGrid::remove_param($shortcode,$attribute_name);
}
function ef4_vc_grid_update_shortcode_param($shortcode = '', array $attributes)
{
    EF4VCGrid::update_shortcode_param($shortcode,$attributes);
}
