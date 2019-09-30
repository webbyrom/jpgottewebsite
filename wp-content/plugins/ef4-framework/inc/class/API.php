<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 6/11/2018
 * Time: 10:48 AM
 */

namespace ef4;

class API
{
    public function init()
    {
        add_filter('ef4_get_export_custom_settings_data',[$this,'export_custom_settings'],10,2);
        add_filter('ef4_import_custom_settings_data',[$this,'import_custom_settings'],10,3);
    }
    function import_custom_settings($result = false,$args = [])
    {
        $params =  wp_parse_args($args,[
            'post_type'=>'',
            'data'=>''
        ]);
        $post_type = $params['post_type'];
        $import = @json_decode(base64_decode($params['data']),true);
        if(!is_array($import))
            return $result;
        $import = wp_parse_args($import,[
            'type'=>'',
            'data'=>''
        ]);
        $data = $import['data'];
        if(!is_array($data))
            return false;
        switch ($import['type'])
        {
            case 'custom_single_post_type':
                $result = [];
                foreach ($data as $key => $value)
                {
                    $result[$key] = apply_filters(
                        'ef4_custom_save_settings',
                        false,
                        ['post_type' => $post_type, 'name' => $key,'value'=>$value]
                    );
                }
                break;
        }
        return $result;
    }
    function export_custom_settings($result='', $args = [])
    {
        $params =  wp_parse_args($args,[
            'post_type'=>''
        ]);
        $post_type = $params['post_type'];
        $setting_name = apply_filters('ef4_custom_settings_allow',[],$post_type);
        $result = [
            'type'=>'custom_single_post_type',
            'data'=>''
        ];
        $data = [];
        foreach ($setting_name as $name)
        {
            $data[$name] = apply_filters(
                'ef4_custom_get_settings',
                '',
                ['post_type' => $post_type, 'name' => $name]
            );
        }
        $result['data']=$data;
        return base64_encode(json_encode($result));
    }
}
//nito,hender,transpress