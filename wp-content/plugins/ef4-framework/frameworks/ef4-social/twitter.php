<?php

class EF4_Twitter_API
{
    protected $api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    protected $auth_validated = false;
    protected $cache_limit = '30 min';
    protected $args = [
        'count'                  => "100",
        'exclude_replies'        => "true",
        'include_rts'            => "false",
        'oauth_consumer_key'     => "",  // get from config : oauth_consumer_key
        'oauth_nonce'            => "",
        'oauth_signature_method' => "HMAC-SHA1",
        'oauth_timestamp'        => "",  // time()
        'oauth_token'            => "",  // get from config : oauth_access_token
        'oauth_version'          => "1.0",
        'screen_name'            => "",
        'trim_user'              => "false"
    ];
    protected $config = [
        'oauth_consumer_key'    => '',
        'oauth_consumer_secret' => '',
        'oauth_access_token'    => '',
        'oauth_access_secret'   => ''
    ];
    protected static $instance;

    public function __construct($auth = [], $cache_limit = '30 min')
    {
        if (!empty($auth))
            $this->set_auth($auth);
        else {
//take config from latest twitter plugin
            $parse = [
                'consumer_key'    => 'twitter_api_consumer_key',
                'consumer_secret' => 'twitter_api_consumer_secret',
                'access_token'    => 'twitter_api_access_key',
                'access_secret'   => 'twitter_api_access_secret',
            ];
            $data = [];
            foreach ($parse as $key => $opt) {
                $value = get_option($opt);
                if (empty($value)) {
                    $data = false;
                    break;
                }
                $data[$key] = $value;
            }
            if (!empty($data))
                $this->set_auth($data);
        }
        $this->cache_limit = $cache_limit;
    }

    public static function instance($auth = '', $cache_limit = '30 min')
    {
        if (!self::$instance instanceof self)
            self::$instance = new self($auth, $cache_limit);
        return self::$instance;
    }

    public function get_data($take_raw = true)
    {
        if (!$this->auth_validated)
            return false;
        $cache = get_option('ef4_twitter_api_cache', []);
        $cache_default = [
            'auth'  => $this->get_auth_hash(),
            'cache' => [] // array: $hash => ['time'=>'' , 'data']
        ];
        if (!is_array($cache) || empty($cache['auth']) || $cache['auth'] !== $cache_default['auth'])
            $cache = $cache_default;
        $key = $this->get_query_hash();
        $take_new = false;
        $take_new = (!array_key_exists($key, $cache['cache'])) ? true : $take_new;
        if (empty($this->cache_limit))
            $take_new = true;
        else
            $take_new = (!$take_new && strtotime('-' . $this->cache_limit) > $cache['cache'][$key]['time']) ? true : $take_new;
        if ($take_new) {
            $request = $this->http_request();
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);
            $cache['cache'][$key] = ['time' => time(), 'data' => base64_encode(json_encode($data))];
        } else {
            $data = json_decode(base64_decode($cache['cache'][$key]['data']), true);;
        }
        if ($take_new) {
            update_option('ef4_twitter_api_cache', $cache);
        }
        $result = [];
        foreach ($data as $raw) {
            if(count($result) >= $this->args['count'])
                break;
            $temp = $this->try_take_twitter_data($raw);
            if ($temp)
                $result[] = $temp;
        }
        if (!$take_raw) {
            foreach ($result as &$raw)
                unset($raw['raw_data']);
        }
        return $result;
    }

    protected function try_take_twitter_data($raw)
    {
        $result = [
            'text'         => '',
            'created'      => '',
            'created_html' => '',
            'url'          => '',
            'name'         => '',
            'username'     => '',
            'raw_data'     => []
        ];
        $is_fail = false;
        $is_fail = (!is_array($raw)) ? true : $is_fail;
        $is_fail = (!isset($raw['created_at'])) ? true : $is_fail;
        $is_fail = (!isset($raw['id_str'])) ? true : $is_fail;
        $is_fail = (!isset($raw['user'])) ? true : $is_fail;
        $is_fail = (!isset($raw['text'])) ? true : $is_fail;
        if ($is_fail)
            return false;
        $text = $raw['text'];
        if (isset($raw['entities']) && is_array($raw['entities'])) {
            $entities = wp_parse_args($raw['entities'], [
                'hashtags'      => [],
                'symbols'       => [],
                'user_mentions' => [],
                'urls'          => [],
                'media'         => []
            ]);
            foreach ($entities as $key => $value) {
                $function = "replace_{$key}_tweet";
                if (is_callable([$this, $function]))
                    $this->$function($text, $value);
            }
        }
        $result['text'] = $text;
//for created
        $created_at = $raw['created_at'];
        $date = apply_filters('ef4_twitter_render_date', $created_at);
        if ($date === $created_at) {
            $time = strtotime($created_at);
            $date = esc_html($this->twitter_api_relative_date($time));
            $date = '<time datetime="' . date_i18n('Y-m-d H:i:sO', $time) . '">' . $date . '</time>';
        }
        $result['created_html'] = $date;
        $result['created'] = strtotime($created_at);
        $result['name'] = $raw['user']['name'];
        $result['username'] = $raw['user']['screen_name'];
        $result['user_url'] = "https://twitter.com/{$raw['user']['screen_name']}";
// for url
        $result['url'] = "https://twitter.com/{$raw['user']['screen_name']}/status/{$raw['id_str']}";
        $result['raw_data'] = $raw;
        return $result;
    }

    function twitter_api_relative_date($strdate)
    {
// get universal time now.
        static $t, $y, $m, $d, $h, $i, $s, $o;
        if (!isset($t)) {
            $t = time();
            sscanf(gmdate('Y m d H i s', $t), '%u %u %u %u %u %u', $y, $m, $d, $h, $i, $s);
        }
// get universal time of tweet
        $tt = is_int($strdate) ? $strdate : strtotime($strdate);
        if (!$tt || $tt > $t) {
// slight difference between our clock and Twitter's clock can cause problem here - just pretend it was zero seconds ago
            $tt = $t;
            $tdiff = 0;
        } else {
            sscanf(gmdate('Y m d H i s', $tt), '%u %u %u %u %u %u', $yy, $mm, $dd, $hh, $ii, $ss);
// Calculate relative date string
            $tdiff = $t - $tt;
        }
// Less than a minute ago?
        if ($tdiff < 60) {
            return __('Just now', 'twitter-api');
        }
// within last hour? X minutes ago
        if ($tdiff < 3600) {
            $idiff = (int)floor($tdiff / 60);
            return sprintf(_n('%u minute ago', '%u minutes ago', $idiff, 'twitter-api'), $idiff);
        }
// within same day? About X hours ago
        $samey = ($y === $yy) and
        $samem = ($m === $mm) and
        $samed = ($d === $dd);
        if (!empty($samed)) {
            $hdiff = (int)floor($tdiff / 3600);
            return sprintf(_n('About an hour ago', 'About %u hours ago', $hdiff, 'twitter-api'), $hdiff);
        }
        $tf = get_option('time_format') or $tf = 'g:i A';
// within 24 hours?
        if ($tdiff < 86400) {
            return __('Yesterday at', 'twitter-api') . date_i18n(' ' . $tf, $tt);
        }
// else return formatted date, e.g. "Oct 20th 2008 9:27 PM" */
        $df = get_option('date_format') or $df = 'M jS Y';
        return date_i18n($df . ' ' . $tf, $tt);
    }

    public function replace_hashtags_tweet(&$text, $data = '')
    {
        if (empty($data) || !is_array($data))
            return;
//https://twitter.com/hashtag/{tag}?src=hash
        foreach ($data as $hashtag) {
            $tag = '#' . $hashtag['text'];
            $hash_url = "https://twitter.com/hashtag/{$hashtag['text']}?src=hash";
            $html = sprintf('<a href="%s" class="twitter-hashtag"><s>#</s><b>%s</b></a>', $hash_url, trim($tag, '#'));
            $html = apply_filters('ef4_twitter_hashtags_replace', $html, $hash_url, $tag);
            $tag_exp = $tag;
            $text = join($html, explode($tag_exp, $text));
        }
    }

    public function replace_user_mentions_tweet(&$text, $data = '')
    {
        if (empty($data) || !is_array($data))
            return;
//https://twitter.com/hashtag/{tag}?src=hash
        foreach ($data as $user_mentions) {
            $text_replace = '@' . $user_mentions['screen_name'];
            $type_url = "https://twitter.com/{$user_mentions['screen_name']}";
            $html = sprintf('<a href="%s" ><s>@</s><b>%s</b></a>', $type_url, trim($text_replace, '@'));
            $html = apply_filters('ef4_twitter_user_mentions_replace', $html, $type_url, $text_replace);
            $text_replace_exp = $text_replace;
            $text = join($html, explode($text_replace_exp, $text));
        }
    }

    public function replace_urls_tweet(&$text, $data = '')
    {
        if (empty($data) || !is_array($data))
            return;
        foreach ($data as $item) {
            $text_replace = $item['url'];
            $html = sprintf('<a href="%s" target="_blank"><span class="js-display-url">%s</span></a>', $item['url'], $item['display_url']);
            $html = apply_filters('ef4_twitter_urls_replace', $html, $item['url'], $item['display_url']);
            $text_replace_exp = $text_replace;
            $text = join($html, explode($text_replace_exp, $text));
        }
    }

    public function replace_media_tweet(&$text, $data = '')
    {
        if (empty($data) || !is_array($data))
            return;
        foreach ($data as $item) {
            $text_replace = $item['url'];
            $html = sprintf('<a href="%s" target="_blank" rel="nofollow">%s</a>', $item['url'], $item['display_url']);
            $html = apply_filters('ef4_twitter_media_replace', $html, $item['url'], $item['display_url']);
            $text_replace_exp = $text_replace;
            $text = join($html, explode($text_replace_exp, $text));
        }
    }

    protected function http_request($endpoint = '', array $conf = [])
    {
        if (empty($endpoint))
            $endpoint = $this->build_request_url();
        if (empty($conf))
            $conf = ['method' => 'GET', 'redirection' => 0,];
        $http = wp_remote_request($endpoint, $conf);
        if ($http instanceof WP_Error) {
            return false;
        }
        return $http;
    }

    public function set_params($args = [])
    {
//$screen_name, $count, $rts, $ats, $pop = 0
        $params = wp_parse_args($args, [
            'screen_name' => '',
            'count'       => 2,
            'rts'         => '',
            'ats'         => '',
            'pop'         => 0
        ]);
        extract($params);
        if (!empty($num))
            $count = $num;
        $trim_user = false;
        $include_rts = !empty($rts);
        $exclude_replies = empty($ats);
        if ($exclude_replies || !$include_rts || $pop) {
            $params['count'] = 100;
        } else {
            $params['count'] = max($count, 2);
        }
        $params_add = compact('exclude_replies', 'include_rts', 'count', 'trim_user', 'screen_name');
        foreach ($params_add as $key => $value)
            $this->args[$key] = $value;
        return $this;
    }

    public function get_auth_hash()
    {
        $auth = $this->config;
        asort($auth);
        return md5(json_encode($auth));
    }

    public function set_cache_limit($cache = '30 min')
    {
        $this->cache_limit = $cache;
        return $this;
    }

    public function set_auth($args = [])
    {
        $default = [
            'oauth_consumer_key'    => '',
            'oauth_consumer_secret' => '',
            'oauth_access_token'    => '',
            'oauth_access_secret'   => ''
        ];
        $auth = [];
        foreach (array_keys($args) as $key) {
            if (strpos($key, 'oauth_') !== 0)
                $key_use = 'oauth_' . $key;
            else
                $key_use = $key;
            if (!array_key_exists($key_use, $default))
                return $this;
            $auth[$key_use] = $args[$key];
        }
        $this->auth_validated = true;
        $this->config = $auth;
        $this->args['oauth_consumer_key'] = $this->get_config('consumer_key');
        $this->args['oauth_token'] = $this->get_config('access_token');
        return $this;
    }

    function build_request_url()
    {
        $origin_count =  $this->args['count'];
        $this->args['count'] = $origin_count*2;
        $this->sign_hmac('GET', $this->api_url);
        $result = $this->api_url . '?' . $this->serialize();
        $this->args['count'] = $origin_count;
        return $result;
    }

    function get_query_hash()
    {
        $query = $this->args;
        $query['url'] = $this->api_url;
        $unread_fields = ['oauth_nonce', 'oauth_timestamp', 'oauth_signature'];
        foreach ($unread_fields as $field)
            unset($query[$field]);
        asort($query);
        return md5(json_encode($query));
    }

    private function get_config($name)
    {
        $use_name = "oauth_{$name}";
        if (isset($this->config[$use_name]))
            return $this->config[$use_name];
        return '';
    }

    private function normalize()
    {
        $flags = SORT_STRING | SORT_ASC;
        ksort($this->args, $flags);
        foreach ($this->args as $k => $a) {
            if (is_array($a)) {
                sort($this->args[$k], $flags);
            }
        }
        return $this->args;
    }

    public function serialize()
    {
        return self::urlencode_params($this->args);
    }

    private static function urlencode_params(array $args)
    {
        $pairs = array();
        foreach ($args as $key => $val) {
            $pairs[] = rawurlencode($key) . '=' . rawurlencode($val);
        }
        return str_replace('%7E', '~', implode('&', $pairs));
    }

    private static function urlencode($val)
    {
        return str_replace('%7E', '~', rawurlencode($val));
    }

    public function sign_hmac($http_method, $http_rsc)
    {
        $this->args['oauth_signature_method'] = 'HMAC-SHA1';
        $this->args['oauth_timestamp'] = sprintf('%u', time());
        $this->args['oauth_nonce'] = sprintf('%f', microtime(true));
        unset($this->args['oauth_signature']);
        $this->normalize();
        $str = $this->serialize();
        $str = strtoupper($http_method) . '&' . self::urlencode($http_rsc) . '&' . self::urlencode($str);
        $key = self::urlencode($this->get_config('consumer_secret')) . '&' . self::urlencode($this->get_config('access_secret'));
        $this->args['oauth_signature'] = base64_encode(hash_hmac('sha1', $str, $key, true));
        return $this->args;
    }
}

function ef4_latest_twitter()
{
    return EF4_Twitter_API::instance();
}