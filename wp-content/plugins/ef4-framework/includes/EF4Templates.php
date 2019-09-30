<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/30/2017
 * Time: 3:33 PM
 */
if (!defined('ABSPATH')) {
    //First catches the Apache users
    header("HTTP/1.0 404 Not Found");
    //This should catch FastCGI users
    header("Status: 404 Not Found");
    die();
}

class EF4Templates
{
    const PREFIX = 'ef4-cms';
    const HTML_PREFIX = 'html';
    const OUTPUT_SUFFIX = 'output';
    public $shortcode;
    protected $full_prefix;
    public $attrs = array();
    public $html = array();

    public function __construct($shorcode, array $attrs = array())
    {
        $params = array(
            'shortcode' => '',
            'attrs'     => array()
        );
        if (is_string($shorcode))
            $params['shortcode'] = $shorcode;
        if (is_array($shorcode))
            $params = array_merge($params, $shorcode);
        if (!empty($attrs))
            $params['attrs'] = $attrs;
        $this->init($params);
    }

    protected function init(array $args)
    {
        $this->full_prefix = $this->merge_name(self::PREFIX, $args['shortcode']);
        $this->attrs = $args['attrs'];
    }

    public function merge_name(...$str)
    {
        if (count($str) < 1)
            return '';
        $separator = '-';
        $result = $str[0];
        for ($i = 1; $i < count($str); $i++)
            $result .= $separator . $str[$i];
        return $result;
    }

    public function setHtml($name, $html)
    {
        $this->html[$name] = $html;
    }

    public function setHtmls(array $htmls_type)
    {
        foreach ($htmls_type as $key => $html)
            $this->html[$key] = $html;
    }

    public function getTemplate($echo = false)
    {
        ob_start();
        $main_html = $this->get_html('main');
        $this->fill_template($main_html, array('main'));
        echo $main_html;
        $output = ob_get_clean();
        $output = preg_replace('/{{(.*)}}/i', '', $output);
        $result = $this->return_value($output, self::OUTPUT_SUFFIX);
        if ($echo)
            echo $result;
        return $result;
    }

    protected function fill_template(&$html, array $loop_protect = array())
    {
        preg_match_all('/{{[\s]{0,}[a-zA-Z0-9_-]{1,}[\s]{0,}}}/i', $html, $matches);
        if (is_array($matches) && !empty($matches[0]) && is_array($matches[0])) {
            foreach ($matches[0] as $raw_param) {
                $param = trim($raw_param, "{} ");
                if (in_array($param, $loop_protect))
                    $html_seg = "Infinity Loop Detect !!!";
                else {
                    $html_seg = $this->get_html($param);
                    $this->fill_template($html_seg, array_merge($loop_protect, array($param)));
                }
                $html = preg_replace('/{{[\s]{0,}(' . $param . ')[\s]{0,}}}/i', $html_seg, $html);
            }
        }
        return $html;
    }

    protected function get_html($name)
    {
        $html = (isset($this->html[$name])) ? $this->html[$name] : '';
        $suffix = $this->merge_name(self::HTML_PREFIX, $name);
        return $this->return_value($html, $suffix);
    }

    protected function return_value($value, $suffix = '')
    {
        $filter = $this->merge_name($this->full_prefix, $suffix);
        ob_start();
        $value = apply_filters($filter, $value, $this->attrs);
        $value = ob_get_clean() . $value;
        return $value;
    }
}