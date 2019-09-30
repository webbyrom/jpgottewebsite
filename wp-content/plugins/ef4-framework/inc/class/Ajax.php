<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/11/2018
 * Time: 10:38 AM
 */
namespace ef4;
class Ajax
{
    protected $handle = [];
    public function init()
    {

    }
    public function after_init()
    {
        $admin_actions = [
            'ef4_save_settings'=>[$this,'save_settings'],
            'ef4_save_cpt_settings'=>[$this,'save_cpt_settings'],
            'ef4_import_settings'=>[$this,'import_settings'],
        ];
        $extend_admin_ajax_action = apply_filters('ef4_admin_ajax_handle',[]);
        if(!is_array($extend_admin_ajax_action))
            $extend_admin_ajax_action = [];
        $admin_actions = array_merge($extend_admin_ajax_action,$admin_actions);

        $public_actions = [

        ];
        $extend_public_ajax_action = apply_filters('ef4_public_ajax_handle',[]);
        if(!is_array($extend_public_ajax_action))
            $extend_public_ajax_action = [];
        $public_actions = array_merge($extend_public_ajax_action,$public_actions);
        $this->handle = array_merge($admin_actions,$public_actions);
        foreach ($admin_actions as $key => $callback )
        {
            if(is_callable($callback))
            {
                add_action("wp_ajax_{$key}",[$this,'doing_ajax']);
            }
        }
        foreach ($public_actions as $key => $callback )
        {
            if(is_callable($callback))
            {
                add_action("wp_ajax_{$key}",[$this,'doing_ajax']);
                add_action("wp_ajax_nopriv_{$key}",[$this,'doing_ajax']);
            }
        }
    }
    public function doing_ajax()
    {
        $action = $_POST['action'];
        if(array_key_exists($action,$this->handle))
        {
            //before callback
            //nothing

            //call ajax handle
            $callback = $this->handle[$action];
            call_user_func($callback);
            //after call,success ajax
            Log::save_log();
            die();
        }
    }
    private function verify($action,array $required = array())
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], $action))
            die(403);
        foreach ($required as $field)
            if(!isset($_POST[$field]))
                die(403);
    }
    public function  import_settings()
    {
        $this->verify('ef4-import-settings',['post_type']);
        if(empty($_FILES['file_data']))
            return;
        $file = $_FILES['file_data'];
        if($file['error'] == UPLOAD_ERR_OK
            && is_uploaded_file($file['tmp_name']))
        {
            $data = file_get_contents($file['tmp_name']);
        }
        if(empty($data))
            return;
        $post_type = $_POST['post_type'];
        $result = apply_filters('ef4_import_custom_settings_data',false,[
            'post_type'=>$post_type,
            'data'=>$data
        ]);
        if($result)
        {
            $response = [
                'success'=>'success'
            ];
        }
        else
        {
            $response = [
                'success'=>'fail'
            ];
        }
        echo json_encode($response);
    }
    public function save_cpt_settings()
    {
        $this->verify('ef4-cpt-settings',['post_type']);
        $result = [];
        $ignore = ['nonce','action','post_type'];
        foreach ($_POST as $key => $value)
        {
            if(in_array($key,$ignore))
                continue;
            if(apply_filters('ef4_custom_save_settings',false,[
                'post_type'=>$_POST['post_type'],
                'name'=>$key,
                'value'=>$value
            ]))
                $result[$key] = $value;
        }
        echo json_encode([
            'success'=>'success',
            'saved'=>$result
        ]);
    }
    public function save_settings()
    {
        $this->verify('ef4-settings');
        $new_settings_data = [];
        foreach ($_POST as $key => $value)
        {
            $new_settings_data[$key] = $value;
        }
        foreach ($new_settings_data as $key => $value)
        {
            if(!ef4()->save_setting($key,$value))
                unset($new_settings_data[$key]);
        }
        $response = [
            'success'=>'success',
            'updated'=>array_keys($new_settings_data)
        ];
        echo json_encode($response);
    }
}