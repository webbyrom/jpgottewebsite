<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/24/2018
 * Time: 3:25 PM
 */
namespace ef4;
class Log
{
    public static $error = [];
    public static $log=[];
    public static $post_save_log = '';
    protected static $error_tag;
    protected static $log_tag;
    public static function save_log()
    {
        $id = self::$post_save_log;
        if(is_numeric($id))
        {
            if(!empty(self::$error))
            {
                $err_log = get_post_meta($id,'ef4_error',true);
                if(!is_array($err_log))
                    $err_log = [];
                $err_log[time()]=self::$error;
                update_post_meta($id,'ef4_error',$err_log);
            }
            if(!empty(self::$log))
            {
                $log = get_post_meta($id,'ef4_log',true);
                if(!is_array($log))
                    $log = [];
                $log [time()] = self::$log;
                update_post_meta($id,'ef4_log',$log);
            }

        }
    }
    protected static function set_log_code()
    {
        self::$log_tag = [
            ''=> '' ,
        ];
    }
    public static function add_log($key,$add_info = '')
    {
        if(!is_array(self::$log_tag))
            self::set_log_code();
        self::$log[]=[
            'type'=>$key,
            'message'=> (array_key_exists($key,self::$log_tag)) ? self::$log_tag[$key] : '',
            'add_info'=>$add_info
        ];
    }
    protected static function set_error_code()
    {
        self::$error_tag = [
            'stripe_rate_limit'=>__('Too many requests made to the API too quickly','ef4-framework'),
            'stripe_invalid_request'=> __('Invalid parameters were supplied to Stripe\'s API','ef4-framework') ,
            'stripe_authentication'=> __('Authentication with Stripe\'s API failed ,maybe you changed API keys recently','ef4-framework') ,
            'stripe_api_connection'=> __('Network communication with Stripe failed','ef4-framework') ,
            'stripe_base'=>__('Display a very generic error to the user, and maybe send yourself an email','ef4-framework')  ,
            'stripe_unknown'=>  __('Something else happened, completely unrelated to Stripe','ef4-framework'),
            'validate_card_invalid'=> __('Invalid Card','ef4-framework') ,
            'missing_api'=> __('Required class not loaded','ef4-framework') ,
            'missing_card_token'=> __('Please validate card info before charge','ef4-framework') ,
            'protect_invalid_function_params'=> sprintf(__('User %d take error in %s','ef4-framework'),get_current_user_id(),date('Y/m/d H:i:s')) ,
            'paypal_one_time_payment_fail'=> __('Take error when try to create payment via paypal','ef4-framework') ,
            'paypal_create_purchased_payment_fail'=> __('Take error when try to create purchased payment via paypal','ef4-framework') ,
            ''=> '' ,
        ];
    }
    public static function add_err($key,$add_info = '')
    {
        if(!is_array(self::$error_tag))
            self::set_error_code();
        self::$error[]=[
            'type'=>$key,
            'message'=> (array_key_exists($key,self::$error_tag)) ? self::$error_tag[$key] : '',
            'add_info'=>$add_info
        ];
    }
}