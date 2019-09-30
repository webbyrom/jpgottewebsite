<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/9/2018
 * Time: 8:55 AM
 */
class EF4Base
{
    protected static $plugin_dir = '';
    protected static $plugin_folder_name = '';
    protected static $plugin_url = '';
    const ASSET_DIR = 'assets2';
    protected $error = [];
    protected $admin_assets_enqueued = false;
    private $dynamic_template = [];
    protected $currencies_data = [];

    protected function init()
    {
        add_action('admin_notices', [$this, 'admin_notice__error']);
        add_action('admin_enqueue_scripts', [$this, 'register_assets'], 1);
        add_action('wp_enqueue_scripts', [$this, 'register_assets'], 1);
        add_filter('ef4_get_currency_data', [$this, 'get_currency_data'], 10, 2);
        add_filter('ef4_create_amount', [$this, 'parse_amount'], 10, 2);
        add_filter('ef4_dynamic_action_allow', [$this, 'get_dynamic_action_allow'], 5);
       // add_filter('ef4_parse_query', [$this, 'filter_take_data_from_string_query'], 5);
        add_filter('ef4_replace_data', [$this, 'replace_data'], 5,2);
    }

    function filter_take_data_from_string_query()
    {

    }
    public function save_log_to($id)
    {
        \ef4\Log::$post_save_log = $id;
    }
    public function add_error_log($key='',$add_info = [])
    {
        \ef4\Log::add_err($key,$add_info);
    }
    public function get_dynamic_action_allow($result)
    {
        return [
            ''    => __('None', 'ef4-framework'),
            'add' => __('Add (convert to number)', 'ef4-framework'),
            'sub' => __('Subtract (convert to number)', 'ef4-framework'),
        ];
    }

    public function get_hash($obj = '', $length = 8)
    {
        if (!is_string($obj))
            $origin = json_encode($obj);
        else
            $origin = $obj;
        return substr(md5($origin), 0, $length);
    }

    public function do_dynamic_action($data)
    {
        $args = wp_parse_args($data, [
            'name'         => '',
            'target'       => '',
            'type'         => '',
            'params'       => '',
            'data_sources' => []
        ]);
        $params_raw = $this->query_params_string_to_array($args['params']);
        $params = [];
        foreach ($params_raw as $index => $param) {
            $test_param = explode(':', $param, 2);
            if (count($test_param) !== 2) {
                $params[$index] = $param;
                continue;
            }
            list($source, $key) = $test_param;
            $test_source = explode('-', $source, 2);
            if (count($test_source) == 2) {
                $source = $test_source[0];
                $key = $test_source[1] . ':' . $key;
                $type = 'post';
            } else {
                $type = 'array';
            }
            if (empty($args['data_sources'][$source])) {
                $this->error_do_dynamic_action('empty_source', __('Empty Source for action', 'ef4-framework'));
                return;
            }
            switch ($type) {
                case 'post':
                    $container = $args['data_sources'][$source];
                    if (!$container instanceof WP_Post) {
                        $this->error_do_dynamic_action('empty_source', __('Type required source post', 'ef4-framework'));
                        return;
                    }
                    $params[$index] = $this->parse_post_data($key, $container);
                    break;
                case 'array':
                    $container = $args['data_sources'][$source];
                    if (!is_array($container)) {
                        $this->error_do_dynamic_action('empty_source', __('Type required source array', 'ef4-framework'));
                        return;
                    }
                    $params[$index] = isset($container[$key]) ? $container[$key] : '';
                    break;
            }
        }
        $type_modify_data = ['add','sub'];
        if (in_array($args['type'], $type_modify_data)) {
            $this->action_modify_post_data($args['type'], $args['target'], $params, $args['data_sources']);
        }

    }

    function action_modify_post_data($type, $target, array $params, array $sources)
    {
        $target_check = explode('-', $target);
        if (count($target_check) !== 2 || empty($sources[$target_check[0]]) || !($sources[$target_check[0]] instanceof WP_Post)) {
            $this->error_do_dynamic_action('invalid_target');
            return;
        }
        $post = $sources[$target_check[0]];
        $target_param = $target_check[1];
        $param_check = explode(':', $target_param, 2);
        if (count($param_check) !== 2) {
            $this->error_do_dynamic_action('invalid_target');
            return;
        }
        list($target_type, $query) = $param_check;
        switch ($type) {
            case 'add':
                $value = floatval($this->parse_post_data($target_param, $post));
                foreach ($params as $param) {
                    $value += floatval($param);
                }
                $value = $this->parse_float_val($value);
                break;
            case 'sub':
                $value = floatval($this->parse_post_data($target_param, $post));
                foreach ($params as $param) {
                    $value -= floatval($param);
                }
                $value = $this->parse_float_val($value);
                break;
            case 'push_array':
                $value =$this->parse_post_data($target_param, $post,'array');
                if(!is_array($value))
                    $value = [];
                foreach ($params as $param) {
                    $value[]= $this->parse_post_data($target_param, $post,'array');
                }
                break;
        }
        if (empty($value)) {
            $this->error_do_dynamic_action('type_not_support');
            return;
        }
        $temp_target = explode('-',$target_type);
        $target_type = $temp_target[0];
        $sub_type = (isset($temp_target[1])) ? $temp_target[1] : '';
        switch ($target_type) {
            case 'meta':
                update_post_meta($post->ID, $query, $value);
                break;
            case 'subpost':
                switch ($sub_type)
                {
                    case 'meta':
                        $id = $this->parse_post_data("subpost-post:id|[id]-meta:{$query}",$post);
                        update_post_meta($id,$query,$value);
                        break;
                }
                break;
        }
    }
    function error_do_dynamic_action($key, $message = '')
    {
        var_dump([$key, $message = '']);
    }

    public function is_valid_value($value, $params)
    {
        $queries = explode(' | ', $params);
        foreach ($queries as $query) {
            $query = explode(':', $query);
            $key = trim($query[0]);
            $add = isset($query[1]) ? trim($query[1]) : '';
            switch ($key) {
                case 'required':
                    if (empty($value))
                        return false;
                    break;
                case 'email':
                    if (empty($value))
                        break;
                    if (!is_email($value))
                        return false;
                    break;
                case 'number':
                    if (empty($value))
                        break;
                    if (!is_numeric($value))
                        return false;
                    $args = wp_parse_args($this->query_params_string_to_array($add), [
                        'min' => '',
                        'max' => ''
                    ]);
                    if (!empty($args['min']) && ($value) < ($args['min']))
                        return false;
                    if (!empty($args['max']) && ($value) > ($args['max']))
                        return false;
                    break;
                case 'length':
                    if (empty($value))
                        break;
                    $args = wp_parse_args($this->query_params_string_to_array($add), [
                        'min' => '',
                        'max' => ''
                    ]);
                    if (!empty($args['min']) && strlen($value) < intval($args['min']))
                        return false;
                    if (!empty($args['max']) && strlen($value) > intval($args['max']))
                        return false;
                    break;
                case 'regex':
                    if (empty($value))
                        break;
                    preg_match_all($add, $value, $matches);
                    if (empty($matches[0]) || $value !== $matches[0][0])
                        return false;
                    break;
            }
        }
        return true;
    }

    public function query_params_string_to_array($query)
    {
        $query = trim($query);
        $result = [];
        $reg_key = '/\[(.*)\]/';
        $query = explode(',', $query);
        foreach ($query as $item) {
            $item_check = explode('-', $item, 2);
            if (count($item_check) !== 2) {
                $result[] = $item_check[0];
                continue;
            }
            preg_match_all($reg_key, $item_check[0], $matches);
            if (!empty($matches[0]) && $matches[0][0] == $item_check[0]) {
                $result[substr($item_check[0], 1, strlen($item_check[0]) - 2)] = $item_check[1];
                continue;
            }
            $result[] = $item;
        }
        return $result;
    }

    public function verify_ajax_request($nonce_action, array $required = array())
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], $nonce_action))
            die(403);
        foreach ($required as $field)
            if (!isset($_POST[$field]))
                die(403);
    }

    public function hash_to_number($str)
    {
        $arr = str_split($str);
        $result = 0;
        foreach ($arr as $el) {
            $result += ord($el);
        }
        return $result;
    }

    public function parse_post_data($query, WP_Post $post,$type_result = 'string')
    {
        $value = trim($query);
        $check_function = explode('|', $query, 2);
        if (count($check_function) > 1)
            return $this->parse_post_data_by_function(trim($check_function[0]), trim($check_function[1]), $post,$type_result);
        $take_data = explode(':', $query, 2);
        if (count($take_data) > 1) {
            list($type, $key) = $take_data;
            switch ($type) {
                case 'post':
                    $value = $this->take_post_data($key, $post);
                    break;
                case 'meta':
                    $value = get_post_meta($post->ID, $key, true);
                    break;
            }
        }
        $temp_type = explode('-',$type_result,2);
        $type_result = $temp_type[0];
        $params = isset($temp_type[1]) ? $temp_type[1] : '';
        switch ($type_result)
        {
            case 'array':
                if(!is_array($value))
                    $value = [];
                break;
            case 'int':
                $value = intval($value);
                break;
            case 'float':
                if(empty($params))
                    $params = 2;
                $value = $this->parse_float_val($value,$params);
                break;
            default:
                if(!is_numeric($value) && !is_string($value))
                    $value = '';
                break;
        }

        return $value;
    }

    public function take_post_data($name, WP_Post $post)
    {
        $value = '';
        switch ($name) {
            case 'title':
                $value = $post->post_title;
                break;
            case 'content':
                $value = $post->post_content;
                break;
            case 'id':
                $value = $post->ID;
                break;
            default :
                if (property_exists($post, $name))
                    $value = $post->$name;
                break;
        }
        return $value;
    }

    function replace_data($str, $data)
    {
        $sources = wp_parse_args($data, [
            'item'    => '',
            'payment' => ''
        ]);
        $mask = str_replace(['{{', '}}','[',']'], ['{${', '}$}','{+{','}+}'], $str);
        preg_match_all('/{\${[a-zZ-Z0-9 =>{}|:_+-]+}\$}/', $mask, $matches);
        if (empty($matches[0]))
            return $str;
        foreach ($matches[0] as $match) {
            $raw = str_replace(['{+{','}+}'],['[',']'],$match);
            $raw = trim(substr($raw, 3, strlen($raw) - 6));
            $check_func =  explode('=>',$raw,2);
            if(count($check_func) == 2)
            {
                $fun = trim($check_func[0]);
                $raw = trim($check_func[1]);
            }
            else
            {
                $fun = '';
            }
            $raw = explode('|', $raw , 2);

            $value = '';
            $query = '';
            if (count($raw) !== 2)
            {
                $key = trim($raw[0]);
            }
            else
            {
                $key = trim($raw[0]);
                $query = trim($raw[1]);
            }
//            $func_check = explode('-',$key);
            switch ($key) {
                case 'site_name':
                    $value = get_bloginfo('name');
                    break;
                case 'home_url':
                    $value = home_url('');
                    break;
                default :
                    if($sources[$key] instanceof WP_Post)
                        $value = $this->parse_post_data($query, $sources[$key]);
                    elseif(is_array($sources[$key]))
                        $value = isset($sources[$key][$query]) ? $sources[$key][$query] : '';
                    elseif(is_string($sources[$key])|| is_numeric($sources[$key]))
                        $value = $sources[$key];
                    break;
            }
            switch ($fun)
            {
                case 'hash':
                    if(!empty($value))
                        $value = $this->get_hash($value);
                    break;
            }
            $mask = str_replace($match, $value, $mask);
        }
        return $mask;
    }

    function parse_post_data_by_function($fun_query, $query, WP_Post $post,$type='string')
    {
        $fun_check = explode('-', $fun_query, 2);
        if (count($fun_check) > 1) {
            $name = trim($fun_check[0]);
            $params = trim($fun_check[1]);
        } else
            $name = $fun_query;
        switch ($name) {
            case 'select_options':
                $raw_options = $this->parse_post_data($query,$post);
                $options = $this->parse_options_select($raw_options,$post);
                return json_encode($options);
                break;
            case 'subpost':
                $data = wp_parse_args($this->query_params_string_to_array($query), [
                    'id' => '',
                ]);
                $subpost = get_post($this->parse_post_data( $data['id'],$post,$type));
                if (!$subpost instanceof WP_Post || empty($params))
                    return '';
                return $this->parse_post_data($params, $subpost,$type);
                break;
            case 'amount':
                $data = $this->query_params_string_to_array($query);
                foreach ($data as $key=>$param)
                {
                    $data[$key]=$this->parse_post_data($param,$post);
                }
                if (isset($data['currency'])) {
                    $currency = empty($data['currency']) ? 'usd' : $data['currency'];
                    $data_currency = $this->get_currency_data([], $currency);
                    $data = array_merge($data, $data_currency);
                }
                $data = wp_parse_args($data, [
                    'mask'   => '',
                    'amount' => '',
                    'full'   => '',
                    'short'  => '',
                    'symbol' => '',
                ]);
                if (empty($data['mask']))
                    $data['mask'] = '{{amount}}{{symbol}}';
                return str_replace(
                    ['{{amount}}', '{{full}}', '{{short}}', '{{symbol}}'],
                    [$data['amount'], $data['full'], $data['short'], $data['symbol']],
                    $data['mask']);
                break;
            case 'date':
                if (empty($params))
                    $params = get_option('date_format');
                $item = $this->parse_post_data($query, $post);
                if (empty($item))
                    return '';
                if (!is_numeric($item))
                    $item = strtotime($item);
                return date($params, $item);
                break;
            case 'currency':
                if (empty($params))
                    $params = 'short';
                $item = $this->parse_post_data($query, $post);
                $currency_data = ef4()->get_currency_data([], $item);
                if (!array_key_exists($params, $currency_data))
                    return 'error';
                return $currency_data[$params];
                break;
            case 'min':
                $query = explode(',', $query);
                $items = [];
                foreach ($query as $item) {
                    $el = $this->parse_post_data($item, $post);
                    if (!empty($params) && $params == 'notempty' && $el == '')
                        continue;
                    $el = $this->parse_float_val($el);
                    $items[] = $el;
                }
                return min($items);
                break;
            case 'max':
                $query = explode(',', $query);
                $items = [];
                foreach ($query as $item) {
                    $items[] = $this->parse_float_val($this->parse_post_data($item, $post));
                }
                return max($items);
                break;
            case 'math':
                $reg = '/{[*\/+-]}/';
                preg_match_all('/{[*\/+-]}/', $query, $match);
                $match = $match[0];
                $items = preg_split($reg, $query);
                if (count($match) !== count($items) - 1)
                    return 'error';
                $value = 0;
                for ($i = 0; $i < count($items); $i++) {
                    if ($i == 0) {
                        $value = floatval($this->parse_post_data($items[0], $post));
                        continue;
                    }
                    $item = floatval($this->parse_post_data($items[$i], $post));
                    $op = $match[$i - 1];
                    switch ($op) {
                        case '{+}':
                            $value += $item;
                            break;
                        case '{-}':
                            $value -= $item;
                            break;
                        case '{*}':
                            $value *= $item;
                            break;
                        case '{/}':
                            $value /= $item;
                            break;
                    }
                }
                return $this->parse_float_val($value);
                break;
        }
        return '';
    }

    public function parse_float_val($data, $decimal = 2)
    {
        $decimal = intval($decimal)>0 ? intval($decimal) : 1;
        $result = number_format(floatval($data),$decimal,'.',',');
        $result = str_replace(',','',$result);
        if (strpos($result, '.') !== false) {
            $result = trim($result, '0');
            $result = trim($result, '.');
        }
        return $result;
    }

    public function get_post($post_args = '', $get_global = true)
    {
        global $post;
        $result = '';
        if (is_numeric($post_args))
            $result = get_post($post_args);
        elseif ($post_args instanceof WP_Post)
            $result = $post_args;
        if (empty($result) && $get_global)
            $result = $post;
        return $result;
    }

    public function merge_name()
    {
        $name = '';
        $params = func_get_args();
        foreach ($params as $param) {
            if (!is_string($param))
                continue;
            $param_use = sanitize_title($param);
            if (empty(trim($param_use)))
                continue;
            if (!empty($name))
                $name .= '_';
            $name .= $param_use;
        }
        return $name;
    }

    public function current_url(array $args = [])
    {
        $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;
        return add_query_arg($args, $current_url);
    }

    function parse_amount($result = '', array $args = [])
    {
        $args = wp_parse_args($args, [
            'amount'   => '',
            'mask'     => '',
            'currency' => ''
        ]);
        $args['amount'] = intval($args['amount']);
        $default_data = [
            'symbol' => '$',
            'full'   => 'USD',
            'short'  => 'USD'
        ];
        if (!is_array($args['currency']))
            $currency_data = apply_filters('ef4_get_currency_data', $default_data, $args['currency']);
        else
            $currency_data = wp_parse_args($args['currency'], $default_data);
        return str_replace(
            ['{{amount}}', '{{short}}', '{{full}}', '{{symbol}}'],
            [number_format_i18n($args['amount']), $currency_data['short'], $currency_data['full'], $currency_data['symbol']],
            $args['mask']);
    }

    /**
     * @param $currency string
     * @return array
     */
    public function get_currency_data($result = [], $currency = '')
    {
        $currency = strtoupper($currency);
        $currencies = [
            'full'   => $this->get_currencies('{{full}}'),
            'symbol' =>$this->get_currencies('{{symbol}}'),
            'short'  => $this->get_currencies('{{short}}'),
        ];
        $currency_short = 'USD';
        foreach ($currencies as $type => $data) {
            if (in_array($currency, $data)) {
                $flip_data = array_flip($data);
                $currency_short = $flip_data[$currency];
                break;
            }
        }
        $result = [
            'symbol' => $currencies['symbol'][$currency_short]['title'],
            'short'  => $currencies['short'][$currency_short]['title'],
            'full'   => $currencies['full'][$currency_short]['title'],
        ];
        return $result;
    }

    public function get_currencies($mask = "{{full}} ({{short}})")
    {
        if (!empty($this->currencies_data[$mask]))
            return $this->currencies_data[$mask];
        $stripe_allow_raw = 'USD,AED,AFN*,ALL,AMD,ANG,AOA*,ARS*,AUD,AWG,AZN,BAM,BBD,BDT,BGN,BIF,BMD,BND,BOB*,BRL*,BSD,BWP,BZD,' .
            'CAD,CDF,CHF,CLP,CNY,COP*,CRC*,CVE*,CZK*,DJF*,DKK,DOP,DZD,EGP,ETB,EUR,FJD,FKP*,GBP,GEL,GIP,GMD,GNF*,GTQ*,GYD,HKD,' .
            'HNL*,HRK,HTG,HUF*,IDR,ILS,INR*,ISK,JMD,JPY,KES,KGS,KHR,KMF,KRW,KYD,KZT,LAK*,LBP,LKR,LRD,LSL,MAD,MDL,MGA' .
            ',MKD,MMK,MNT,MOP,MRO,MUR*,MVR,MWK,MXN,MYR,MZN,NAD,NGN,NIO*,NOK,NPR,NZD,PAB*,PEN*,PGK,PHP,PKR,PLN,PYG*,' .
            'QAR,RON,RSD,RUB,RWF,SAR,SBD,SCR,SEK,SGD,SHP*,SLL,SOS,SRD*,STD,SVC*,SZL,THB,TJS,TOP,TRY,TTD,TWD,TZS,' .
            'UAH,UGX,UYU*,UZS,VND,VUV,WST,XAF,XCD,XOF*,XPF*,YER,ZAR,ZMW';
        $stripe_allow_raw = explode(',', $stripe_allow_raw);
        $stripe_allow = [];
        foreach ($stripe_allow_raw as $code) {
            $code = trim($code, '*');
            if (!empty($code))
                $stripe_allow[] = $code;
        }
        $currency_raw = array(
            "AED" => array("title" => __("United Arab Emirates dirham", 'ef4-framework'), "symbol" => "&#x62f;.&#x625;"),
            "AFN" => array("title" => __("Afghan afghani", 'ef4-framework'), "symbol" => "&#x60b;"),
            "ALL" => array("title" => __("Albanian lek", 'ef4-framework'), "symbol" => "L"),
            "AMD" => array("title" => __("Armenian dram", 'ef4-framework'), "symbol" => "AMD"),
            "ANG" => array("title" => __("Netherlands Antillean guilder", 'ef4-framework'), "symbol" => "&fnof;"),
            "AOA" => array("title" => __("Angolan kwanza", 'ef4-framework'), "symbol" => "Kz"),
            "ARS" => array("title" => __("Argentine peso", 'ef4-framework'), "symbol" => "&#36;"),
            "AUD" => array("title" => __("Australian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "AWG" => array("title" => __("Aruban florin", 'ef4-framework'), "symbol" => "&fnof;"),
            "AZN" => array("title" => __("Azerbaijani manat", 'ef4-framework'), "symbol" => "AZN"),
            "BAM" => array("title" => __("Bosnia and Herzegovina convertible mark", 'ef4-framework'), "symbol" => "KM"),
            "BBD" => array("title" => __("Barbadian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "BDT" => array("title" => __("Bangladeshi taka", 'ef4-framework'), "symbol" => "&#2547;&nbsp;"),
            "BGN" => array("title" => __("Bulgarian lev", 'ef4-framework'), "symbol" => "&#1083;&#1074;."),
            "BHD" => array("title" => __("Bahraini dinar", 'ef4-framework'), "symbol" => ".&#x62f;.&#x628;"),
            "BIF" => array("title" => __("Burundian franc", 'ef4-framework'), "symbol" => "Fr"),
            "BMD" => array("title" => __("Bermudian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "BND" => array("title" => __("Brunei dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "BOB" => array("title" => __("Bolivian boliviano", 'ef4-framework'), "symbol" => "Bs."),
            "BRL" => array("title" => __("Brazilian real", 'ef4-framework'), "symbol" => "&#82;&#36;"),
            "BSD" => array("title" => __("Bahamian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "BTC" => array("title" => __("Bitcoin", 'ef4-framework'), "symbol" => "&#3647;"),
            "BTN" => array("title" => __("Bhutanese ngultrum", 'ef4-framework'), "symbol" => "Nu."),
            "BWP" => array("title" => __("Botswana pula", 'ef4-framework'), "symbol" => "P"),
            "BYR" => array("title" => __("Belarusian ruble", 'ef4-framework'), "symbol" => "Br"),
            "BZD" => array("title" => __("Belize dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "CAD" => array("title" => __("Canadian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "CDF" => array("title" => __("Congolese franc", 'ef4-framework'), "symbol" => "Fr"),
            "CHF" => array("title" => __("Swiss franc", 'ef4-framework'), "symbol" => "&#67;&#72;&#70;"),
            "CLP" => array("title" => __("Chilean peso", 'ef4-framework'), "symbol" => "&#36;"),
            "CNY" => array("title" => __("Chinese yuan", 'ef4-framework'), "symbol" => "&yen;"),
            "COP" => array("title" => __("Colombian peso", 'ef4-framework'), "symbol" => "&#36;"),
            "CRC" => array("title" => __("Costa Rican col&oacute;n", 'ef4-framework'), "symbol" => "&#x20a1;"),
            "CUC" => array("title" => __("Cuban convertible peso", 'ef4-framework'), "symbol" => "&#36;"),
            "CUP" => array("title" => __("Cuban peso", 'ef4-framework'), "symbol" => "&#36;"),
            "CVE" => array("title" => __("Cape Verdean escudo", 'ef4-framework'), "symbol" => "&#36;"),
            "CZK" => array("title" => __("Czech koruna", 'ef4-framework'), "symbol" => "&#75;&#269;"),
            "DJF" => array("title" => __("Djiboutian franc", 'ef4-framework'), "symbol" => "Fr"),
            "DKK" => array("title" => __("Danish krone", 'ef4-framework'), "symbol" => "DKK"),
            "DOP" => array("title" => __("Dominican peso", 'ef4-framework'), "symbol" => "RD&#36;"),
            "DZD" => array("title" => __("Algerian dinar", 'ef4-framework'), "symbol" => "&#x62f;.&#x62c;"),
            "EGP" => array("title" => __("Egyptian pound", 'ef4-framework'), "symbol" => "EGP"),
            "ERN" => array("title" => __("Eritrean nakfa", 'ef4-framework'), "symbol" => "Nfk"),
            "ETB" => array("title" => __("Ethiopian birr", 'ef4-framework'), "symbol" => "Br"),
            "EUR" => array("title" => __("Euro", 'ef4-framework'), "symbol" => "&euro;"),
            "FJD" => array("title" => __("Fijian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "FKP" => array("title" => __("Falkland Islands pound", 'ef4-framework'), "symbol" => "&pound;"),
            "GBP" => array("title" => __("Pound sterling", 'ef4-framework'), "symbol" => "&pound;"),
            "GEL" => array("title" => __("Georgian lari", 'ef4-framework'), "symbol" => "&#x10da;"),
            "GGP" => array("title" => __("Guernsey pound", 'ef4-framework'), "symbol" => "&pound;"),
            "GHS" => array("title" => __("Ghana cedi", 'ef4-framework'), "symbol" => "&#x20b5;"),
            "GIP" => array("title" => __("Gibraltar pound", 'ef4-framework'), "symbol" => "&pound;"),
            "GMD" => array("title" => __("Gambian dalasi", 'ef4-framework'), "symbol" => "D"),
            "GNF" => array("title" => __("Guinean franc", 'ef4-framework'), "symbol" => "Fr"),
            "GTQ" => array("title" => __("Guatemalan quetzal", 'ef4-framework'), "symbol" => "Q"),
            "GYD" => array("title" => __("Guyanese dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "HKD" => array("title" => __("Hong Kong dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "HNL" => array("title" => __("Honduran lempira", 'ef4-framework'), "symbol" => "L"),
            "HRK" => array("title" => __("Croatian kuna", 'ef4-framework'), "symbol" => "Kn"),
            "HTG" => array("title" => __("Haitian gourde", 'ef4-framework'), "symbol" => "G"),
            "HUF" => array("title" => __("Hungarian forint", 'ef4-framework'), "symbol" => "&#70;&#116;"),
            "IDR" => array("title" => __("Indonesian rupiah", 'ef4-framework'), "symbol" => "Rp"),
            "ILS" => array("title" => __("Israeli new shekel", 'ef4-framework'), "symbol" => "&#8362;"),
            "IMP" => array("title" => __("Manx pound", 'ef4-framework'), "symbol" => "&pound;"),
            "INR" => array("title" => __("Indian rupee", 'ef4-framework'), "symbol" => "&#8377;"),
            "IQD" => array("title" => __("Iraqi dinar", 'ef4-framework'), "symbol" => "&#x639;.&#x62f;"),
            "IRR" => array("title" => __("Iranian rial", 'ef4-framework'), "symbol" => "&#xfdfc;"),
            "IRT" => array("title" => __("Iranian toman", 'ef4-framework'), "symbol" => "&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;"),
            "ISK" => array("title" => __("Icelandic kr&oacute;na", 'ef4-framework'), "symbol" => "kr."),
            "JEP" => array("title" => __("Jersey pound", 'ef4-framework'), "symbol" => "&pound;"),
            "JMD" => array("title" => __("Jamaican dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "JOD" => array("title" => __("Jordanian dinar", 'ef4-framework'), "symbol" => "&#x62f;.&#x627;"),
            "JPY" => array("title" => __("Japanese yen", 'ef4-framework'), "symbol" => "&yen;"),
            "KES" => array("title" => __("Kenyan shilling", 'ef4-framework'), "symbol" => "KSh"),
            "KGS" => array("title" => __("Kyrgyzstani som", 'ef4-framework'), "symbol" => "&#x441;&#x43e;&#x43c;"),
            "KHR" => array("title" => __("Cambodian riel", 'ef4-framework'), "symbol" => "&#x17db;"),
            "KMF" => array("title" => __("Comorian franc", 'ef4-framework'), "symbol" => "Fr"),
            "KPW" => array("title" => __("North Korean won", 'ef4-framework'), "symbol" => "&#x20a9;"),
            "KRW" => array("title" => __("South Korean won", 'ef4-framework'), "symbol" => "&#8361;"),
            "KWD" => array("title" => __("Kuwaiti dinar", 'ef4-framework'), "symbol" => "&#x62f;.&#x643;"),
            "KYD" => array("title" => __("Cayman Islands dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "KZT" => array("title" => __("Kazakhstani tenge", 'ef4-framework'), "symbol" => "KZT"),
            "LAK" => array("title" => __("Lao kip", 'ef4-framework'), "symbol" => "&#8365;"),
            "LBP" => array("title" => __("Lebanese pound", 'ef4-framework'), "symbol" => "&#x644;.&#x644;"),
            "LKR" => array("title" => __("Sri Lankan rupee", 'ef4-framework'), "symbol" => "&#xdbb;&#xdd4;"),
            "LRD" => array("title" => __("Liberian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "LSL" => array("title" => __("Lesotho loti", 'ef4-framework'), "symbol" => "L"),
            "LYD" => array("title" => __("Libyan dinar", 'ef4-framework'), "symbol" => "&#x644;.&#x62f;"),
            "MAD" => array("title" => __("Moroccan dirham", 'ef4-framework'), "symbol" => "&#x62f;.&#x645;."),
            "MDL" => array("title" => __("Moldovan leu", 'ef4-framework'), "symbol" => "MDL"),
            "MGA" => array("title" => __("Malagasy ariary", 'ef4-framework'), "symbol" => "Ar"),
            "MKD" => array("title" => __("Macedonian denar", 'ef4-framework'), "symbol" => "&#x434;&#x435;&#x43d;"),
            "MMK" => array("title" => __("Burmese kyat", 'ef4-framework'), "symbol" => "Ks"),
            "MNT" => array("title" => __("Mongolian t&ouml;gr&ouml;g", 'ef4-framework'), "symbol" => "&#x20ae;"),
            "MOP" => array("title" => __("Macanese pataca", 'ef4-framework'), "symbol" => "P"),
            "MRO" => array("title" => __("Mauritanian ouguiya", 'ef4-framework'), "symbol" => "UM"),
            "MUR" => array("title" => __("Mauritian rupee", 'ef4-framework'), "symbol" => "&#x20a8;"),
            "MVR" => array("title" => __("Maldivian rufiyaa", 'ef4-framework'), "symbol" => ".&#x783;"),
            "MWK" => array("title" => __("Malawian kwacha", 'ef4-framework'), "symbol" => "MK"),
            "MXN" => array("title" => __("Mexican peso", 'ef4-framework'), "symbol" => "&#36;"),
            "MYR" => array("title" => __("Malaysian ringgit", 'ef4-framework'), "symbol" => "&#82;&#77;"),
            "MZN" => array("title" => __("Mozambican metical", 'ef4-framework'), "symbol" => "MT"),
            "NAD" => array("title" => __("Namibian dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "NGN" => array("title" => __("Nigerian naira", 'ef4-framework'), "symbol" => "&#8358;"),
            "NIO" => array("title" => __("Nicaraguan c&oacute;rdoba", 'ef4-framework'), "symbol" => "C&#36;"),
            "NOK" => array("title" => __("Norwegian krone", 'ef4-framework'), "symbol" => "&#107;&#114;"),
            "NPR" => array("title" => __("Nepalese rupee", 'ef4-framework'), "symbol" => "&#8360;"),
            "NZD" => array("title" => __("New Zealand dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "OMR" => array("title" => __("Omani rial", 'ef4-framework'), "symbol" => "&#x631;.&#x639;."),
            "PAB" => array("title" => __("Panamanian balboa", 'ef4-framework'), "symbol" => "B/."),
            "PEN" => array("title" => __("Peruvian nuevo sol", 'ef4-framework'), "symbol" => "S/."),
            "PGK" => array("title" => __("Papua New Guinean kina", 'ef4-framework'), "symbol" => "K"),
            "PHP" => array("title" => __("Philippine peso", 'ef4-framework'), "symbol" => "&#8369;"),
            "PKR" => array("title" => __("Pakistani rupee", 'ef4-framework'), "symbol" => "&#8360;"),
            "PLN" => array("title" => __("Polish z&#x142;oty", 'ef4-framework'), "symbol" => "&#122;&#322;"),
            "PRB" => array("title" => __("Transnistrian ruble", 'ef4-framework'), "symbol" => "&#x440;."),
            "PYG" => array("title" => __("Paraguayan guaran&iacute;", 'ef4-framework'), "symbol" => "&#8370;"),
            "QAR" => array("title" => __("Qatari riyal", 'ef4-framework'), "symbol" => "&#x631;.&#x642;"),
            "RON" => array("title" => __("Romanian leu", 'ef4-framework'), "symbol" => "lei"),
            "RSD" => array("title" => __("Serbian dinar", 'ef4-framework'), "symbol" => "&#x434;&#x438;&#x43d;."),
            "RUB" => array("title" => __("Russian ruble", 'ef4-framework'), "symbol" => "&#8381;"),
            "RWF" => array("title" => __("Rwandan franc", 'ef4-framework'), "symbol" => "Fr"),
            "SAR" => array("title" => __("Saudi riyal", 'ef4-framework'), "symbol" => "&#x631;.&#x633;"),
            "SBD" => array("title" => __("Solomon Islands dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "SCR" => array("title" => __("Seychellois rupee", 'ef4-framework'), "symbol" => "&#x20a8;"),
            "SDG" => array("title" => __("Sudanese pound", 'ef4-framework'), "symbol" => "&#x62c;.&#x633;."),
            "SEK" => array("title" => __("Swedish krona", 'ef4-framework'), "symbol" => "&#107;&#114;"),
            "SGD" => array("title" => __("Singapore dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "SHP" => array("title" => __("Saint Helena pound", 'ef4-framework'), "symbol" => "&pound;"),
            "SLL" => array("title" => __("Sierra Leonean leone", 'ef4-framework'), "symbol" => "Le"),
            "SOS" => array("title" => __("Somali shilling", 'ef4-framework'), "symbol" => "Sh"),
            "SRD" => array("title" => __("Surinamese dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "SSP" => array("title" => __("South Sudanese pound", 'ef4-framework'), "symbol" => "&pound;"),
            "STD" => array("title" => __("S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra", 'ef4-framework'), "symbol" => "Db"),
            "SYP" => array("title" => __("Syrian pound", 'ef4-framework'), "symbol" => "&#x644;.&#x633;"),
            "SZL" => array("title" => __("Swazi lilangeni", 'ef4-framework'), "symbol" => "L"),
            "THB" => array("title" => __("Thai baht", 'ef4-framework'), "symbol" => "&#3647;"),
            "TJS" => array("title" => __("Tajikistani somoni", 'ef4-framework'), "symbol" => "&#x405;&#x41c;"),
            "TMT" => array("title" => __("Turkmenistan manat", 'ef4-framework'), "symbol" => "m"),
            "TND" => array("title" => __("Tunisian dinar", 'ef4-framework'), "symbol" => "&#x62f;.&#x62a;"),
            "TOP" => array("title" => __("Tongan pa&#x2bb;anga", 'ef4-framework'), "symbol" => "T&#36;"),
            "TRY" => array("title" => __("Turkish lira", 'ef4-framework'), "symbol" => "&#8378;"),
            "TTD" => array("title" => __("Trinidad and Tobago dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "TWD" => array("title" => __("New Taiwan dollar", 'ef4-framework'), "symbol" => "&#78;&#84;&#36;"),
            "TZS" => array("title" => __("Tanzanian shilling", 'ef4-framework'), "symbol" => "Sh"),
            "UAH" => array("title" => __("Ukrainian hryvnia", 'ef4-framework'), "symbol" => "&#8372;"),
            "UGX" => array("title" => __("Ugandan shilling", 'ef4-framework'), "symbol" => "UGX"),
            "USD" => array("title" => __("United States dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "UYU" => array("title" => __("Uruguayan peso", 'ef4-framework'), "symbol" => "&#36;"),
            "UZS" => array("title" => __("Uzbekistani som", 'ef4-framework'), "symbol" => "UZS"),
            "VEF" => array("title" => __("Venezuelan bol&iacute;var", 'ef4-framework'), "symbol" => "Bs F"),
            "VND" => array("title" => __("Vietnamese &#x111;&#x1ed3;ng", 'ef4-framework'), "symbol" => "&#8363;"),
            "VUV" => array("title" => __("Vanuatu vatu", 'ef4-framework'), "symbol" => "Vt"),
            "WST" => array("title" => __("Samoan t&#x101;l&#x101;", 'ef4-framework'), "symbol" => "T"),
            "XAF" => array("title" => __("Central African CFA franc", 'ef4-framework'), "symbol" => "Fr"),
            "XCD" => array("title" => __("East Caribbean dollar", 'ef4-framework'), "symbol" => "&#36;"),
            "XOF" => array("title" => __("West African CFA franc", 'ef4-framework'), "symbol" => "Fr"),
            "XPF" => array("title" => __("CFP franc", 'ef4-framework'), "symbol" => "Fr"),
            "YER" => array("title" => __("Yemeni rial", 'ef4-framework'), "symbol" => "&#xfdfc;"),
            "ZAR" => array("title" => __("South African rand", 'ef4-framework'), "symbol" => "&#82;"),
            "ZMW" => array("title" => __("Zambian kwacha", 'ef4-framework'), "symbol" => "ZK"),
        );
        $currency_allow = [];
        foreach ($currency_raw as $key => $item) {
            if (!in_array($key, $stripe_allow))
                continue;
            $currency_allow[$key] = $item;
        }
        foreach ($currency_allow as $short => $currency) {
            $currency_allow[$short]['title'] = str_replace(['{{full}}', '{{short}}', '{{symbol}}'], [$currency['title'], $short, $currency['symbol']], $mask);
        }
        return $this->currencies_data[$mask] = wp_parse_args(apply_filters('ef4_currency', $currency_allow, $mask), [
            "USD" => array("title" => str_replace(['{{full}}', '{{short}}', '{{symbol}}'], ["United States dollar", 'USD', "&#36;"], $mask), "symbol" => "&#36;")
        ]);
    }

    function register_assets()
    {
        $plugins = [
            'animate-css',
            'autosize',
            //'bootstrap',
            'bootstrap3',
            'bootstrap-material-datetimepicker',
            'bootstrap-notify',
            'bootstrap-select',
            'bootstrap-tagsinput',
            'gmaps',
            'momentjs',
            'multi-select',
            'nestable',
            'node-waves',
            'nouislider',
            'sweetalert',
            'validate',
            'flatpickr'
        ];
        foreach ($plugins as $plugin) {
            $this->assets_plugin($plugin);
        }
        //css
        wp_register_style('materialize', $this->assets('css/materialize.min.css'));
        wp_register_style('ef4-admin-custom', $this->assets('css/custom.css'));
        wp_register_style('ef4-admin-style', $this->assets('css/style.css'));
        wp_register_style('ef4-front', $this->assets('css/public.css'));
        wp_register_style('font-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
        //js
        wp_register_script('ef4-admin-admin', $this->assets('js/admin.js'));
        wp_register_script('ef4-admin-templates', $this->assets('js/templates.js'));
        wp_register_script('ef4-front', $this->assets('js/scripts.js'));
        wp_register_script('ef4-admin-settings', $this->assets('js/settings.js'));
    }

    public function enqueue_admin_assets()
    {
        if ($this->admin_assets_enqueued)
            return;
        $this->admin_assets_enqueued = true;
        $scripts = [
            'ef4-front',
            'ef4-admin-admin',
            'wp-color-picker',
            'ef4-admin-templates',
            'ef4-admin-settings',
        ];
        $styles = [
            'ef4-front',
            'wp-color-picker',
            'ef4-admin-style',
            'ef4-admin-custom',
            'font-material-icons'
        ];
        $plugins = [
            'animate-css',
            'autosize',
//            'bootstrap',
            'bootstrap3',
            'flatpickr',
            'bootstrap-select',
            'bootstrap-tagsinput',
            'momentjs',
            'multi-select',
            'node-waves',
        ];
        foreach ($styles as $style)
            wp_enqueue_style($style);
        foreach ($scripts as $script)
            wp_enqueue_script($script);
        $this->enqueue_plugins($plugins);
        wp_localize_script('ef4-admin-settings', 'ef4', [
            'url' => [
                'ajax' => admin_url('admin-ajax.php'),
                'home' => home_url(),
                'api'  => get_rest_url()
            ],
        ]);
    }

    public function enqueue_plugins($name)
    {
        if (!is_array($name))
            $plugins = [$name];
        else
            $plugins = $name;
        foreach ($plugins as $plugin) {
            $this->assets_plugin($plugin, 'enqueue');
        }
    }

    public function assets_plugin($plugin_name = '', $type = 'register')
    {
        $styles = [];
        $scripts = [];
        switch ($plugin_name) {
            case 'flatpickr':
                $styles = array_merge($styles, [
                    'flatpickr/flatpickr.min.css',
                ]);
                $scripts = array_merge($scripts, [
                    'flatpickr/flatpickr.js'
                ]);
                break;
            case 'animate-css':
                $styles = array_merge($styles, [
                    'animate-css/animate.min.css'
                ]);
                break;
            case 'autosize':
                $scripts = array_merge($scripts, [
                    'autosize/autosize.min.js'
                ]);
                break;
            case 'bootstrap3':
                $styles = array_merge($styles, [
                    'bootstrap/css/bootstrap.min.css',
                ]);
                $scripts = array_merge($scripts, [
                    'bootstrap/js/bootstrap.min.js'
                ]);
                break;
            case 'bootstrap':
                $styles = array_merge($styles, [
                    'bootstrap4/bootstrap.min.css',
                ]);
                $scripts = array_merge($scripts, [
                    'bootstrap4/bootstrap.min.js'
                ]);
                $ver = '4.1.1';
                break;
            case 'bootstrap-material-datetimepicker':
                $styles = array_merge($styles, [
                    'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'
                ]);
                $scripts = array_merge($scripts, [
                    ['bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js', ['momentjs']]
                ]);
                break;
            case 'bootstrap-notify':
                $scripts = array_merge($scripts, [
                    'bootstrap-notify/bootstrap-notify.min.js'
                ]);
                break;
            case 'bootstrap-select':
                $styles = array_merge($styles, [
                    'bootstrap-select/css/bootstrap-select.css'
                ]);
                $scripts = array_merge($scripts, [
                    'bootstrap-select/js/bootstrap-select.js'
                ]);
                break;
            case 'bootstrap-tagsinput':
                $styles = array_merge($styles, [
                    'bootstrap-tagsinput'           => 'bootstrap-tagsinput/bootstrap-tagsinput.css',
                    'bootstrap-tagsinput-typeahead' => 'bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css'
                ]);
                $scripts = array_merge($scripts, [
                    'bootstrap-tagsinput/bootstrap-tagsinput.min.js'
                ]);
                break;
            case 'gmaps':
                $scripts = array_merge($scripts, [
                    'gmaps/gmaps.js',
                    'gmaps/Gruntfile.js',
                ]);
                break;
            case 'momentjs':
                $scripts = array_merge($scripts, [
                    'momentjs/moment.js'
                ]);
                break;
            case 'multi-select':
                $styles = array_merge($styles, [
                    'multi-select/css/multi-select.css'
                ]);
                $scripts = array_merge($scripts, [
                    'multi-select/js/jquery.multi-select.js'
                ]);
                break;
            case 'nestable':
                $styles = array_merge($styles, [
                    'nestable/jquery-nestable.css'
                ]);
                $scripts = array_merge($scripts, [
                    'nestable/jquery.nestable.js'
                ]);
                break;
            case 'node-waves':
                $styles = array_merge($styles, [
                    'node-waves/waves.min.css'
                ]);
                $scripts = array_merge($scripts, [
                    'node-waves/waves.min.js'
                ]);
                break;
            case 'nouislider':
                $styles = array_merge($styles, [
                    'nouislider/nouislider.min.css'
                ]);
                $scripts = array_merge($scripts, [
                    'nouislider/nouislider.js'
                ]);
                break;
            case 'sweetalert':
                $styles = array_merge($styles, [
                    'sweetalert/sweetalert.css'
                ]);
                $scripts = array_merge($scripts, [
                    'sweetalert/sweetalert.min.js'
                ]);
                break;
            case 'validate':
                $scripts = array_merge($scripts, [
                    'validate/validate.js'
                ]);
                break;
        }
        $name_key_scripts = [];
        $name_key_styles = [];
        foreach ($scripts as $key => $url) {
            if (is_numeric($key))
                $name_key_scripts[$plugin_name] = $url;
            else
                $name_key_scripts[$key] = $url;
        }
        foreach ($styles as $key => $url) {
            if (is_numeric($key))
                $name_key_styles[$plugin_name] = $url;
            else
                $name_key_styles[$key] = $url;
        }
        switch ($type) {
            case 'register':
                foreach ($name_key_scripts as $name => $path) {
                    if (!is_array($path))
                        $path = [$this->assets('plugins/' . $path)];
                    else
                        $path[0] = $this->assets('plugins/' . $path[0]);
                    call_user_func_array('wp_register_script', array_merge([$name], $path));
                }
                foreach ($name_key_styles as $name => $path) {
                    if (!is_array($path))
                        $path = [$this->assets('plugins/' . $path)];
                    else
                        $path[0] = $this->assets('plugins/' . $path[0]);
                    call_user_func_array('wp_register_style', array_merge([$name], $path));
                }
                break;
            case 'enqueue':
                foreach ($name_key_scripts as $name => $path)
                    wp_enqueue_script($name);
                foreach ($name_key_styles as $name => $path)
                    wp_enqueue_style($name);
                break;
        }
    }

    public function plugin_folder_name()
    {
        if (!empty(self::$plugin_folder_name))
            $folder_name = self::$plugin_folder_name;
        else {
            $max = strlen(__FILE__);
            $dir = __FILE__;
            for ($i = 0; $i < $max; $i++) {
                $parent_dir = dirname($dir);
                //check if parent is plugins and grand is wp-content will return;
                if (basename($parent_dir) == 'plugins' && basename(dirname($parent_dir)) == 'wp-content')
                    break;
                $dir = $parent_dir;
            }
            $folder_name = self::$plugin_folder_name = basename($dir);
        }
        if (empty($folder_name))
            $folder_name = self::$plugin_folder_name = 'ef4-framework';
        return $folder_name;
    }

    function query_string_to_array($str, $separator = ',')
    {
        $result = [];
        $arr_query = explode($separator, $str);
        foreach ($arr_query as $query) {
            $query_check = explode('=', $query, 2);
            if (count($query_check) !== 2)
                continue;
            $result[$query_check[0]] = $query_check[1];
        }
        return $result;
    }

    function maybe_get_global_value($str)
    {
        $key = trim($str, '{}');
        $value = $str;
//        if(str)
        switch ($key) {
            case 'post_id':
                $value = get_the_ID();
                break;
        }
        return $value;
    }

    function try_take_local_config($name, $post = '', $meta_wrap = '')
    {
        $result = '';
        $use_post = $this->get_post($post);
        if (!$use_post instanceof WP_Post)
            return $result;
        $id = $use_post->ID;
        if (!empty($meta_wrap))
        {
            if(strpos($meta_wrap,':') !== false)
                $result =  $this->parse_post_data($meta_wrap,$use_post);
            else
                $result = get_post_meta($id, $meta_wrap, true);
        }
        if (empty($result))
            $result = get_post_meta($id, $name, true);
        if (empty($result))
            $result = ef4()->get_setting("default_{$name}");
        return $result;
    }

    function parse_options_select($str,$post = '')
    {
        $options = [];
        $lines = preg_split('/\r\n|[\r\n]/', $str);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line))
                continue;
            $line = apply_filters('ef4_select_options_parse', $line);
            if (is_array($line)) {
                $options = array_merge($options, $line);
                continue;
            }
            $line = trim($line);
            $test_number = explode('-', $line);
            if (count($test_number) == 3 && $test_number[0] == 'number') {
                $line = 'number_range';
                $params = [
                    'min' => intval($test_number[1]),
                    'max' => intval($test_number[2])
                ];
            }
            if (strpos($line, 'post_type:') === 0) {
                $post_type = explode(':', $line, 2)[1];
                $add_params_check = explode('|where:', $post_type, 2);
                if (count($add_params_check) > 1) {
                    $post_type = $add_params_check[0];
                    $add_params = $this->query_string_to_array($add_params_check[1]);
                }
                $line = 'post_type';
            }
            switch ($line) {
                case 'amount_mask':
                    $add = [
                        '{{amount}}{{symbol}}'  => __('Right - Example: 100$', 'ef4-framework'),
                        '{{amount}} {{symbol}}' => __('Right with space - Example: 100 $', 'ef4-framework'),
                        '{{symbol}}{{amount}}'  => __('Left - Example: $100', 'ef4-framework'),
                        '{{symbol}} {{amount}}' => __('Left with space - Example: $ 100', 'ef4-framework')
                    ];
                    $options = array_merge($options, $add);
                    break;
                case 'currencies':
                    $currencies = $this->get_currencies("{{symbol}} - {{short}} - {{full}}");
                    foreach ($currencies as $short => $currency) {
                        $options[$short] = $currency['title'];
                    }
                    break;
                case 'month':
                    for ($i = 1; $i < 13; $i++)
                        $options[$i] = $i;
                    break;
                case 'number_range':
                    for ($i = $params['min']; $i <= $params['max']; $i++) {
                        $options[$i] = $i;
                    }
                    break;
                case 'post_type':
                    $args = [
                        'post_type'      => $post_type,
                        'posts_per_page' => -1,
                    ];
                    if (!empty($add_params)) {
                        foreach ($add_params as $key => $value) {
                            if (empty($args['meta_query']))
                                $args['meta_query'] = ['relation' => 'AND'];
                            $args['meta_query'][] = [
                                'key'   => $key,
                                'value' => $this->maybe_get_global_value($value)
                            ];
                        }
                    }
                    $query = new WP_Query($args);
                    foreach ($query->posts as $post) {
                        $options[$post->ID] = "{$post->post_title} (ID: {$post->ID})";
                    }
                    break;
                default:
                    $line = explode('=', $line, 2);
                    if (count($line) > 1)
                        $options[trim($line[0])] = trim($line[1]);
                    break;

            }
        }
        //extend mask
//        if($post instanceof WP_Post)
//        {
//            foreach ($options as $key => $value)
//            {
//                switch ($value)
//                {
//                    case '{{amount|key}}':
//                }
//            }
//        }
        return $options;
    }

    public function plugin_dir($end_by_slash = true)
    {
        if (!empty(self::$plugin_dir))
            $plugin_dir = self::$plugin_dir;
        else {
            $max = strlen(__FILE__);
            $dir = __FILE__;
            $plugin_folder = $this->plugin_folder_name();
            for ($i = 0; $i < $max; $i++) {
                $parent_dir = dirname($dir);
                //check if parent is plugins and grand is wp-content will return;
                if (basename($dir) == $plugin_folder && basename($parent_dir) == 'plugins' && basename(dirname($parent_dir)) == 'wp-content')
                    break;
                $dir = $parent_dir;
            }
            $plugin_dir = self::$plugin_dir = $dir;
        }
        return ($end_by_slash) ? trailingslashit($plugin_dir) : untrailingslashit($plugin_dir);
    }

    public function plugin_url($end_by_slash = true)
    {
        if (!empty(self::$plugin_url))
            $plugin_url = self::$plugin_url;
        else {
            $plugin_url = plugins_url($this->plugin_folder_name());
        }
        return ($end_by_slash) ? trailingslashit($plugin_url) : untrailingslashit($plugin_url);
    }

    public function assets($relatve_path)
    {
        $relatve_path = str_replace('\\', '/', $relatve_path);
        $relatve_path = trim($relatve_path, '/');
        return $this->plugin_url() . self::ASSET_DIR . '/' . $relatve_path;
    }

    public function templates_path($relatve_path)
    {
        $relatve_path = str_replace('\\', '/', $relatve_path);
        $relatve_path = trim($relatve_path, '/');
        $role_allow = ['theme','add','base'];
        for($i = 0;$i<count($role_allow);$i++)
        {
            if(empty($this->dynamic_template[$role_allow[$i]]))
                continue;
            $swap_paths = array_keys($this->dynamic_template[$role_allow[$i]]);
            foreach ($swap_paths as $prefix) {
                if (strpos($relatve_path, $prefix) === 0) {
                    foreach ($this->dynamic_template[$role_allow[$i]][$prefix] as $add_path) {
                        $check_path = $add_path . substr($relatve_path, strlen($prefix));
                        if (file_exists($check_path)) {
                            $path = $check_path;
                            $break = true;
                            break;
                        }
                    }
                }
                if (!empty($break))
                {
                    break;
                }
            }
            if (!empty($break))
            {
                break;
            }
        }
        return !empty($path) ? $path : $this->plugin_dir() . 'templates/' . $relatve_path;
    }

    public function add_template_path($origin, $local_path,$role = 'add')
    {
        $allow_role = ['base','add','theme'];
        if(!in_array($role,$allow_role))
            $role = $allow_role[0];
        if (!file_exists($local_path))
            return;
        if(empty($this->dynamic_template[$role]))
            $this->dynamic_template[$role] = [];
        if (empty($this->dynamic_template[$role][$origin]))
            $this->dynamic_template[$role][$origin] = [];
        $this->dynamic_template[$role][$origin][] = $local_path;
    }

    public function is_templates_exists($path)
    {
        $path_check = $this->templates_path($path);
        if (file_exists($path_check)) {
            return true;
        }
        else
            return false;
    }
    public function get_templates($path, array $args = [])
    {
        if($this->is_templates_exists($path))
        {
            $path_76428346832746827 = $this->templates_path($path);
            extract($args);
            include $path_76428346832746827;
        }
        else
            echo '';// "Template file not found \"{$path}\"";
    }
    public function try_get_template($args)
    {
        $query = wp_parse_args($args,[
            'path'=>'',
            'default'=>'',
            'args'=>[]
        ]);
        if($this->is_templates_exists($query['path']))
        {
            $this->get_templates($query['path'],$query['args']);
        }
        else
        {
            if(!empty($query['default']))
                $this->get_templates($query['default'],$query['args']);
        }
    }
    function add_error($message)
    {
        $this->error[] = $message;
    }

    function admin_notice__error()
    {
        if (empty($this->error))
            return;
        $class = 'notice notice-error';
        ?>
        <div class="<?php echo esc_attr($class) ?>">
            <h3><?php esc_html_e('EF4 Error', 'ef4-framework') ?></h3>
            <?php foreach ($this->error as $item): ?>
                <p><?php echo esc_html($item) ?></p>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
