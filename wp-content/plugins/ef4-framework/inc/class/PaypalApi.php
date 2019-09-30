<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/28/2018
 * Time: 4:46 PM
 */
namespace ef4;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\InputFields;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\WebProfile;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaypalApi extends EF4PaymentApi
{
    const META_PREFIX = 'paypal_data_';
    static protected $post_type_allows = ['ef4_payment'];
    protected $redirect_back_args = ['paymentId', 'token', 'PayerID', 'success'];
    protected $api_context = [];

    function init()
    {
        //handle when client redirect back from paypal
        add_action('init', [$this, 'check_paypal_redirect_back']);
        //http://dev.joomexp.com/wordpress/charitywalk/?post_type=tribe_events&success=false&token=EC-1FS49927971199611
        //paypal error redirect
    }

    public function create_purchased_payment(array $purchased_items, array $payment_data)
    {
        parent::create_purchased_payment($purchased_items, $payment_data);
        $payment_data = wp_parse_args($payment_data, [
            'currency'       => '',
            'context_source' => '',
            'description'    => '',
            'redirect_back'  => '',
            'no_shipping'    => '',
            'payment_id'     => '',
        ]);
        $apiContext = $this->getApiContext($payment_data['context_source']);
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $itemList = new ItemList();
        $items = [];
        $total_amount = 0;
        foreach ($purchased_items as $purchased_item) {
            $purchased_item = wp_parse_args($purchased_item, [
                'name'     => '',
                'currency' => '',
                'quantity' => '',
                'price'    => '',
            ]);
            $item = new Item();
            $price = ef4()->parse_float_val($purchased_item['price']);
            $quantity = intval($purchased_item['quantity']);
            $total_amount += $price * $quantity;
            $item->setName($purchased_item['name'])
                ->setCurrency(strtoupper($purchased_item['currency']))
                ->setQuantity($quantity)
                ->setPrice($price);
            $items[] = $item;
        }
        $total_amount = ef4()->parse_float_val($total_amount);
        $itemList->setItems($items);
        $amount = new Amount();
        $amount->setCurrency(strtoupper($payment_data['currency']))
            ->setTotal($total_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setInvoiceNumber(uniqid());
        if (!empty($payment_data['description']))
            $transaction->setDescription($payment_data['description']);
        $redirect_back_raw = explode('#',$payment_data['redirect_back'])[0];
        $baseUrl = remove_query_arg($this->redirect_back_args, $redirect_back_raw);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(add_query_arg(['success' => 'true'], $baseUrl))
            ->setCancelUrl(add_query_arg(['success' => 'false'], $baseUrl));
        $payment = new Payment();
        $payment->setIntent("authorize")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        if ($payment_data['no_shipping'] == 'yes') {
            $this->set_no_shipping($payment, $apiContext);
        }
        try {
            $payment->create($apiContext);
            $approvalUrl = $payment->getApprovalLink();
            $paypal_payment_data = [
                time() => json_decode($payment->toJSON(), true)
            ];
            update_post_meta($payment_data['payment_id'], $this->meta_key('payment_id'), $payment->getId());
            update_post_meta($payment_data['payment_id'], $this->meta_key('payment'), $paypal_payment_data);
            update_post_meta($payment_data['payment_id'], $this->meta_key('approval_url'), $approvalUrl);
        } catch (\Exception $ex) {
            $approvalUrl = false;
            Log::add_err('paypal_create_purchased_payment_fail', $payment_data);
        }
        return $approvalUrl;
    }

    function check_paypal_redirect_back()
    {
        $required_params = $this->redirect_back_args;
        foreach ($required_params as $param)
            if (empty($_GET[$param]))
                return;
        $is_success = ($_GET['success'] == 'true') ? true : false;
        $params = [
            'success'    => $is_success,
            'type'       => $is_success ? 'approved' : 'cancel',
            'token'      => $_GET['token'],
            'payer_id'   => $_GET['PayerID'],
            'payment_id' => $_GET['paymentId']
        ];
        if ($is_success) {
            $args = [
                'post_type'   => self::$post_type_allows,
                'post_status' => ['pending', 'publish', 'private'],
                'meta_query'  => [
                    'relation' => 'AND',
                    [
                        'key'     => $this->meta_key('payer_redirect_back'),
                        'compare' => 'NOT EXISTS',
                    ],
                    [
                        'key'   => $this->meta_key('payment_id'),
                        'value' => $params['payment_id'],
                    ],
                ]
            ];
            $query = new \WP_Query($args);
            if (!empty($query->posts)) {
                $payment = $query->posts[0];
                $payment_id = $payment->ID;
                Log::$post_save_log = $payment_id;
                $payer_status = get_post_meta($payment_id, $this->meta_key('payer_redirect_back'), true);
                if (!is_array($payer_status))
                    $payer_status = [];
                $payer_status[time()] = $params['type'];
                update_post_meta($payment_id, $this->meta_key('payer_redirect_back'), $payer_status);
                if ($params['type'] == 'approved') {
                    try {
                        $result = $this->execute_payment($params['payment_id'], $params['payer_id'], $payment_id);
                        $execution_status = ($result instanceof Payment) ? 'success' : 'error';
                        if ($execution_status == 'success') {
                            $this->check_payment_status($payment_id, $params['payment_id']);
                        }
                    } catch (\Exception $e) {
                        Log::add_err('payment_paypal_try_execute_fail', $e->getMessage());
                    }
                }
            }
        }
        do_action('ef4_paypal_api_payper_redirect_back', $is_success, $params);
        wp_redirect(remove_query_arg($required_params));
        die();
    }

    function execute_payment($paypal_payment_id, $payer_id, $payment_id)
    {
        $result = false;
        $post_type = get_post_meta($payment_id,'items_source',true);
        if (!in_array(get_post_type($payment_id), self::$post_type_allows))
            return $result;
        $apiContext = $this->getApiContext($post_type);
        $payment = Payment::get($paypal_payment_id, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);
        try {
            $result = $payment->execute($execution, $apiContext);
            $payment_execution_status = get_post_meta($payment_id, $this->meta_key('payment_execution'), true);
            if (!is_array($payment_execution_status))
                $payment_execution_status = [];
            $payment_execution_status[time()] = 'success';
            update_post_meta($payment_id, $this->meta_key('payment_execution'), $payment_execution_status);
            update_post_meta($payment_id, $this->meta_key('payer_id'), $payer_id);
            wp_publish_post($payment_id);
        } catch (\Exception $ex) {
            Log::add_err('paypal_execute_payment_fail', $ex);
        }
        return $result;
    }

    function check_payment_status($payment_id, $paypal_payment_id)
    {
        $post_type = get_post_meta($payment_id,'items_source',true);
        if (!in_array(get_post_type($payment_id), self::$post_type_allows)) {
            Log::add_err('protect_invalid_function_params', ['function' => 'check_payment_status', 'payment_id' => $paypal_payment_id]);
            return false;
        }
        Log::$post_save_log = $payment_id;
        $payment_id_saved = get_post_meta($payment_id, $this->meta_key('payment_id'), true);
        if ($payment_id_saved !== $paypal_payment_id) {
            Log::add_err('protect_invalid_function_params', ['function' => 'check_payment_charge', 'charge_id' => $paypal_payment_id]);
            return false;
        }
        try {
            $apiContext = $this->getApiContext($post_type);
            $payment = Payment::get($paypal_payment_id, $apiContext);
            $payment_data_saved = get_post_meta($payment_id, $this->meta_key('payment'), true);
            $payment_data_saved[time()] = json_decode($payment->toJSON(), true);
            update_post_meta($payment_id, $this->meta_key('payment'), $payment_data_saved);
            return true;
        } catch (\Exception $ex) {
            Log::add_err('paypal_check_payment', $paypal_payment_id);
        }
        return false;
    }


    function set_no_shipping(Payment $payment, $apiContext)
    {
        $id = get_option('ef4_paypal_no_shipping_ep_id', '');
        if (empty($id))
            $id = $this->create_no_shipping_web_profile($apiContext);
        if (!empty($id)) {
            $payment->setExperienceProfileId($id);
        }
    }

    function create_no_shipping_web_profile($apiContext)
    {
        // Parameters for input fields customization.
        $inputFields = new InputFields();
// Enables the buyer to enter a note to the merchant on the PayPal page during checkout.
        $inputFields->setAllowNote(true)
            // Determines whether or not PayPal displays shipping address fields on the experience pages. Allowed values: 0, 1, or 2. When set to 0, PayPal displays the shipping address on the PayPal pages. When set to 1, PayPal does not display shipping address fields whatsoever. When set to 2, if you do not pass the shipping address, PayPal obtains it from the buyer’s account profile. For digital goods, this field is required, and you must set it to 1.
            ->setNoShipping(1)
            // Determines whether or not the PayPal pages should display the shipping address and not the shipping address on file with PayPal for this buyer. Displaying the PayPal street address on file does not allow the buyer to edit that address. Allowed values: 0 or 1. When set to 0, the PayPal pages should not display the shipping address. When set to 1, the PayPal pages should display the shipping address.
            ->setAddressOverride(0);
// #### Payment Web experience profile resource
        $webProfile = new WebProfile();
// Name of the web experience profile. Required. Must be unique
        $webProfile->setName(get_bloginfo('name') . ' ' . uniqid())
            // Parameters for flow configuration.
            //->setFlowConfig($flowConfig)
            // Parameters for style and presentation.
            //->setPresentation($presentation)
            // Parameters for input field customization.
            ->setInputFields($inputFields)
            // Indicates whether the profile persists for three hours or permanently. Set to `false` to persist the profile permanently. Set to `true` to persist the profile for three hours.
            ->setTemporary(false);
        $request = clone $webProfile;
        try {
            // Use this call to create a profile.
            $id = $webProfile->create($apiContext)->getId();
            update_option('ef4_paypal_no_shipping_ep_id', $id);
        } catch (PayPalConnectionException $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            $id = false;
            Log::add_err('paypal_create_web_profile_failed');
        }
        return $id;
    }

    public function meta_key($name)
    {
        return self::META_PREFIX . $name;
    }

    function get_redirect_back_url($payment_data)
    {
        $page = zodonations()->get_setting('paypal_return_page');
        if (is_numeric($page))
            $url = get_permalink($page);
        elseif ($page == 'back')
            $url = $payment_data['donated_url'];
        if (empty($url))
            $url = home_url();
        $url = explode('#', $url)[0];
        $url = remove_query_arg($this->redirect_back_args, $url);
        return $url;
    }

    /**
     * Helper method for getting an APIContext for all calls
     * @return \PayPal\Rest\ApiContext
     */
    function getApiContext($source)
    {
        if (!is_array($this->api_context))
            $this->api_context = [];
        if (isset($this->api_context[$source]) && ($this->api_context[$source] instanceof ApiContext))
            return $this->api_context[$source];
        $data_api_context = apply_filters('ef4_paypal_api_context_data', [
            'client_id'     => '',
            'client_secret' => '',
            'type'          => '',
        ], $source);
        $clientId = $data_api_context['client_id'];
        $clientSecret = $data_api_context['client_secret'];
        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );
        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration
        $sanbox_config = [
            'mode'           => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName'   => '../PayPal.log',
            'log.LogLevel'   => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled'  => true,
            //'cache.FileName' => '/PaypalCache' // for determining paypal cache directory
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        ];
        $live_config = [
            'mode'           => 'live',
            'log.LogEnabled' => true,
            'log.FileName'   => '../PayPal.log',
            'log.LogLevel'   => 'INFO', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled'  => true,
            //'cache.FileName' => '/PaypalCache' // for determining paypal cache directory
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        ];
        if ($data_api_context['type'] == 'live')
            $apiContext->setConfig($live_config);
        else
            $apiContext->setConfig($sanbox_config);
        // Partner Attribution Id
        // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
        // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
        // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');
        return $this->api_context[$source] = $apiContext;
    }
}