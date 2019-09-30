<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 9/18/2017
 * Time: 5:17 PM
 */
class EF4_WPBakeryShortCode_VC_Basic_Grid extends WPBakeryShortCode_VC_Basic_Grid
{
    public function __construct()
    {
        $settings = WPBMap::getShortCode('vc_basic_grid');
        parent::__construct($settings);
    }

    public function renderAjax($vc_request_param)
    {

        $this->items = array(); // clear this items array (if used more than once);
        $id = isset($vc_request_param['shortcode_id']) ? $vc_request_param['shortcode_id'] : false;
        if (!isset($vc_request_param['page_id'])) {
            return json_encode(array('status' => 'Nothing found'));
        }
        if ($id) {
            $shortcode = $this->findPostShortcodeById($vc_request_param['page_id'], $id);
        } else {
            /**
             * @deprecated since 4.4.3 due to invalid logic in hash algorithm
             */
            $hash = isset($vc_request_param['shortcode_hash']) ? $vc_request_param['shortcode_hash'] : false;
            $shortcode = $this->findPostShortcodeByHash($vc_request_param['page_id'], $hash);
        }
        if (!is_array($shortcode)) {
            return json_encode(array('status' => 'Nothing found'));
        }
        visual_composer()->registerAdminCss();
        visual_composer()->registerAdminJavascript();
        // Set post id
        $this->post_id = (int)$vc_request_param['page_id'];
        $shortcode_atts = $shortcode['atts'];
        $this->shortcode_content = $shortcode['content'];
        $this->buildAtts($shortcode_atts, $shortcode['content']);
        $this->buildItems();

        return $this->renderItems();
    }

    private function renderItems()
    {
        $output = $items = '';
        $this->buildGridSettings();
        $atts = $this->atts;
        $settings = $this->grid_settings;
        $filter_terms = $this->filter_terms;
        $is_end = isset($this->is_end) && $this->is_end;
        $css_classes = 'vc_grid vc_row' . esc_attr($atts['gap'] > 0 ? ' vc_grid-gutter-' . (int)$atts['gap'] . 'px' : '');
        if (is_array($this->items) && !empty($this->items)) {
            //require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );
            $grid_item = new EF4_Vc_Grid_Item();
            $grid_item->setGridAttributes($atts);
            $grid_item->setIsEnd($is_end);
            // add template name
            $vc_template_name = $atts['item'];
            if (is_numeric($atts['item'])) {
                $custom_template = get_post($atts['item'], ARRAY_A);
                if (!empty($custom_template))
                    $vc_template_name = $custom_template['post_title'];
            }
            $grid_item->setTemplateName($vc_template_name);
            //end add template name
            $grid_item->setTemplateById($atts['item']);
            $output .= $grid_item->addShortcodesCustomCss();
            ob_start();
            wp_print_styles();
            $output .= ob_get_clean();
            $attributes = array(
                'filter_terms' => $filter_terms,
                'atts'         => $atts,
                'grid_item',
                $grid_item,
            );
            // add modify vc grid filter
            $vc_filter = apply_filters('vc_basic_grid_template_filter', vc_get_template('shortcodes/vc_basic_grid_filter.php', $attributes), $attributes);
            $vc_filter = apply_filters('EF4_Vc_Grid_template_filter', $vc_filter, $atts, $filter_terms);
            $output .= $vc_filter;
            // end grid filter
            while (have_posts()) {
                the_post();
                $items .= $grid_item->renderItem(get_post());
            }
            wp_reset_postdata();
        }
        $items = apply_filters($this->shortcode . '_items_list', $items);
        $output .= $this->renderPagination($atts['style'], $settings, $items, $css_classes);

        return $output;
    }
}

class EF4_Vc_Grid_Item extends Vc_Grid_Item
{
    public function setTemplateName($template_name)
    {
        $this->grid_atts['vc_template'] = $template_name;
    }

    /**
     * Generates html with template's variables for rendering new project.
     *
     * @param $template
     */
    public function parseTemplate($template)
    {
        $this->mapShortcodes();
        WPBMap::addAllMappedShortcodes();
        $attr = ' width="' . $this->gridAttribute('element_width', 12) . '"'
            . ' is_end="' . ('true' === $this->isEnd() ? 'true' : '') . '"';
        $template = preg_replace('/(\[(\[?)vc_gitem\b)/', '$1' . $attr, $template);
        $template = apply_filters('EF4_Vc_Grid_Item_raw_template', $template, $this->grid_atts);
        $this->html_template .= do_shortcode($template);
        $this->html_template = apply_filters('EF4_Vc_Grid_Item_template', $this->html_template, $this->grid_atts);
    }

    /**
     * Regexp for variables.
     * @return string
     */

    /**
     * Render item by replacing template variables for exact post.
     *
     * @param WP_Post $post
     *
     * @return mixed
     */
    function renderItem(WP_Post $post)
    {
        $pattern = array();
        $replacement = array();
        $this->addAttributesFilters();
        foreach ($this->getTemplateVariables() as $var) {
            $pattern[] = '/' . preg_quote($var[0], '/') . '/';
            $replacement[] = preg_replace('/\\$/', '\\\$', $this->attribute($var[1], $post, isset($var[3]) ? trim($var[3]) : ''));
        }
        return preg_replace($pattern, $replacement, do_shortcode($this->html_template));
    }
}

