<?php

/**
 * Base shortcode for all LessTheme Shortcodes
 */
class EF4CmsShortCode extends WPBakeryShortCode
{
    protected $htmls = array();
    protected $reset_query = false;

    protected function addHtml($name, $value)
    {
        $name .= '';
        $value .= '';
        $this->htmls[$name] = $value;
    }

    protected function content($atts, $content = null)
    {
        $template = new EF4Templates($this->shortcode, $atts);
        $template->setHtmls($this->htmls);
        $html = $template->getTemplate();
        $this->before_exit();
        return $html;
    }

    protected function before_exit()
    {
        global $wp_query;
        if ($this->reset_query)
            $wp_query = new WP_Query();
    }

}
