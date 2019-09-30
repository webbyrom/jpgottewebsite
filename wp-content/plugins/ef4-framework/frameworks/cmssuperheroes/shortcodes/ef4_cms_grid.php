<?php
vc_map(
    array(
        "name"     => __("EF4 CMS Grid", CMS_NAME),
        "base"     => "ef4_cms_grid",
        "class"    => "vc-cms-grid",
        "category" => __("EF4 Cms Shortcodes", CMS_NAME),
        "params"   => array(
            array(
                "type"       => "loop",
                "heading"    => __("Source", CMS_NAME),
                "param_name" => "source",
                'settings'   => array(
                    'size'     => array('hidden' => false, 'value' => 10),
                    'order_by' => array('value' => 'date')
                ),
                "group"      => __("Source Settings", CMS_NAME),
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __("Layout Type", CMS_NAME),
                "param_name" => "layout",
                "value"      => array(
                    "Basic"   => "basic",
                    "Masonry" => "masonry",
                ),
                "group"      => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"             => "dropdown",
                "heading"          => __("Columns XS Devices", CMS_NAME),
                "param_name"       => "col_xs",
                "edit_field_class" => "vc_col-sm-3 vc_column",
                "value"            => array(1, 2, 3, 4, 6, 12),
                "std"              => 1,
                "group"            => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"             => "dropdown",
                "heading"          => __("Columns SM Devices", CMS_NAME),
                "param_name"       => "col_sm",
                "edit_field_class" => "vc_col-sm-3 vc_column",
                "value"            => array(1, 2, 3, 4, 6, 12),
                "std"              => 2,
                "group"            => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"             => "dropdown",
                "heading"          => __("Columns MD Devices", CMS_NAME),
                "param_name"       => "col_md",
                "edit_field_class" => "vc_col-sm-3 vc_column",
                "value"            => array(1, 2, 3, 4, 6, 12),
                "std"              => 3,
                "group"            => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"             => "dropdown",
                "heading"          => __("Columns LG Devices", CMS_NAME),
                "param_name"       => "col_lg",
                "edit_field_class" => "vc_col-sm-3 vc_column",
                "value"            => array(1, 2, 3, 4, 6, 12),
                "std"              => 4,
                "group"            => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __("Filter", CMS_NAME),
                "param_name" => "filter",
                "value"      => array(
                    __("Enable", CMS_NAME)  => "true",
                    __("Disable", CMS_NAME) => "false"
                ),
//                "dependency" => array(
//                    "element" => "layout",
//                    "value"   => "masonry"
//                ),
                "group"      => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __("Pagination type", CMS_NAME),
                "param_name" => "paginate",
                "value"      => array(
                    __('Paged', CMS_NAME)     => "default",
                    __('None', CMS_NAME)      => 'none',
                    __('Load More', CMS_NAME) => "click",
                ),
                'std'        => 'none',
                "group"      => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __("Sorting", CMS_NAME),
                "param_name" => "sorting",
                "value"      => array(
                    __("Enable", CMS_NAME)  => "true",
                    __("Disable", CMS_NAME) => "false"
                ),
                'std'        => 'false',
                "group"      => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Allow Sort Type", CMS_NAME),
                "param_name" => "sort_type_allow",
                "value"      => EF4Functions::get_vc_sorting_allow(),
                "dependency" => array(
                    "element" => "sorting",
                    "value"   => "true"
                ),
                "group"      => __("Grid Settings", CMS_NAME)
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Extra Class", CMS_NAME),
                "param_name"  => "class",
                "value"       => "",
                "description" => __("", CMS_NAME),
                "group"       => __("Template", CMS_NAME)
            ),
        )
    )
);

class WPBakeryShortCode_ef4_cms_grid extends EF4CmsShortCode
{
    protected function content($atts, $content = null)
    {
        global $wp_query, $post;
        $atts_extra = shortcode_atts(array(
            'source'   => '',
            'col_lg'   => 4,
            'col_md'   => 3,
            'col_sm'   => 2,
            'col_xs'   => 1,
            'layout'   => 'basic',
            'sorting'  => 'false',
            'sort_type_allow',
            'paginate' => 'none',
            'filter'   => 'true',
            'not__in'  => 'false',
            'class'    => '',
        ), $atts);
        $atts = array_merge($atts_extra, $atts);

        //media script
        wp_enqueue_style('wp-mediaelement');
        wp_enqueue_script('wp-mediaelement');
        wp_enqueue_script('ef4_cms_grid', CMS_JS . 'ef4_cms_grid.js', array('jquery'), '1.0.0', true);
        $html_id = cmsHtmlID('cms-grid2');
        $source = $atts['source'];
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }
        //
        $source_arr = EF4Functions::convert_sources_vc_to_array($source);
        $sort_by = EF4Functions::get_request('ef4-sort_by','default');
        $sort_type = EF4Functions::get_request('ef4-sort_type','DESC');
        $source_arr['order'] = $sort_type;
        if(!empty($sort_by) && $sort_by!== 'default')
            $source_arr['order_by'] = $sort_by;
        $source = EF4Functions::convert_array_to_sources_vc($source_arr);
        //
        if (isset($atts['not__in']) && $atts['not__in']) {
            list($args, $wp_query) = vc_build_loop_query($source, get_the_ID());
        } else {
            list($args, $wp_query) = vc_build_loop_query($source);
        }
        //default categories selected
        $args['cat_tmp'] = isset($args['cat']) ? $args['cat'] : '';
        // if select term on custom post type, move term item to cat.
        if(!empty($source_arr['tax_query']))
            $args['tax_query']= $source_arr['tax_query'];
        if ($paged > 1) {
            $args['paged'] = $paged;
            $wp_query = new WP_Query($args);
        }
        $atts['cat'] = isset($args['cat_tmp']) ? $args['cat_tmp'] : '';
        $atts['limit'] = isset($args['posts_per_page']) ? $args['posts_per_page'] : 5;
        /* get posts */
        $atts['posts'] = $wp_query;


        $col_lg = 12 / $atts['col_lg'];
        $col_md = 12 / $atts['col_md'];
        $col_sm = 12 / $atts['col_sm'];
        $col_xs = 12 / $atts['col_xs'];
        $atts['item_class'] = "cms-grid-item col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-xs-{$col_xs}";
        $atts['grid_class'] = "cms-grid";
        if ($atts['layout'] == 'masonry') {
            //wp_enqueue_script('cms-jquery-shuffle');
            $atts['grid_class'] .= " cms-grid-{$atts['layout']}";
        }
        $atts['html_id'] = $html_id;

        $taxo = 'category';
        $_category = array();
        if (!isset($atts['cat']) || $atts['cat'] == '') {
            $terms = get_terms($taxo);
            foreach ($terms as $cat) {
                $_category[] = $cat->term_id;
            }
        } else {
            $_category = explode(',', $atts['cat']);
        }
        $atts['categories'] = $_category;
        add_filter('ef4-cms-ef4_cms_grid-html-main',array($this,'html_seg_main'),5,2);
        $this->addHtml('main_class','cms-grid-wraper ');
        $this->addHtml('main_id',esc_attr($atts['html_id']));
        $this->addHtml('add_attrs','');
        add_filter('ef4-cms-ef4_cms_grid-html-filter',array($this,'html_seg_filter'),5,2);
        add_filter('ef4-cms-ef4_cms_grid-html-posts',array($this,'html_seg_post'),5,2);
        add_filter('ef4-cms-ef4_cms_grid-html-paginate',array($this,'html_seg_paginate'),5,2);
        add_filter('ef4-cms-ef4_cms_grid-html-sorting',array($this,'html_seg_sorting'),5,2);
        $this->addHtml('max_paginate','data-max-page="' . esc_attr($atts['posts']->max_num_pages) . '"');

        $this->reset_query = true;
        return parent::content($atts,$content);
    }

    public function html_seg_paginate($html,$atts)
    {
        switch ($atts['paginate']) {
            case 'default':
                $this->html_seg_paginate_default();
                break;
            case 'click':
                $this->html_seg_paginate_click();
                break;
        }
    }
    public function html_seg_main()
    {
        ?>
        <div class="{{main_class}}" {{ add_attrs }} id="{{ main_id }}">
            {{ sorting }}
            {{ filter }}
            {{ posts }}
            {{ paginate }}
        </div>
        <?php
    }

    public function html_seg_sorting($html,$atts)
    {
        $sorts_by = ( $atts['sorting'] === 'true' && !empty($atts['sort_type_allow'])) ? EF4Functions::parse_vc_sorting_allow_value($atts['sort_type_allow']) : '';
        if(empty($sorts_by))
            return;
        $sort_by = EF4Functions::get_request('ef4-sort_by','default');
        $sort_type = EF4Functions::get_request('ef4-sort_type','DESC');
        ?>
        <div class="ef4-cms-sorting" {{ max_paginate }}>
            <select class="ef4-cms-sort_by">
                <option value="default" ><?php echo esc_html('DEFAULT SORTING') ?></option>
                <?php foreach ($sorts_by as $key => $value): ?>
                    <option value="<?php echo esc_attr($value) ?>" <?php selected($value,$sort_by) ?>><?php echo esc_html($key) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="ef4-cms-sort_type">
                <option value="DESC" <?php selected($sort_type,'DESC') ?>><?php echo esc_html('DESCENDING') ?></option>
                <option value="ASC" <?php selected($sort_type,'ASC') ?>><?php echo esc_html('ASCENDING') ?></option>
            </select>
        </div>
        <?php
    }

    public function html_seg_paginate_default()
    {
        return the_posts_pagination(array(
            'prev_text' => esc_html('<'),
            'next_text' => esc_html('>'),
        ));
    }

    public function html_seg_paginate_click()
    {
        ?>
        <div class="ef4-cms-loadmore-click-handle text-center" {{ max_paginate }}>
            <div class="btn btn-primary"
                 data-state="ef4-state-has-more"><?php echo esc_html('LOAD MORE') ?></div>
            <div class="btn btn-primary"
                 data-state="ef4-state-loading"><?php echo esc_html('LOADING ...') ?></div>
            <div class="btn btn-primary hide"
                 data-state="ef4-state-no-more"><?php echo esc_html('No more posts to load.') ?></div>
        </div>
        <?php
    }


    public function html_seg_filter($html,$atts)
    {
        $taxo = 'category';
        if($atts['filter'] !== "true")
            return;
        $class_filter = ($atts['layout'] === 'masonry') ? "cms-grid-filter" : "cms-grid-filter-default" ;
        ?>
        <div class="<?php echo esc_attr($class_filter) ?>">
            <ul class="cms-filter-category list-unstyled list-inline">
                <li><a class="active" href="#" data-group="all"><?php echo esc_html('All'); ?></a></li>
                <?php
                if (is_array($atts['categories']))
                    foreach ($atts['categories'] as $category):?>
                        <?php $term = get_term($category, $taxo); ?>
                        <li><a href="#" data-group="<?php echo esc_attr('category-' . $term->slug); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
            </ul>
        </div>
    <?php
    }
    public function html_seg_post($html,$atts)
    {
        ?>
        <div class="row cms-grid <?php echo esc_attr($atts['grid_class']); ?>">
            <?php
            $taxo = 'category';
            $posts = $atts['posts'];
            $size = ($atts['layout'] == 'basic') ? 'thumbnail' : 'medium';
            while ($posts->have_posts()):
                $posts->the_post();
                $groups = array();
                $groups[] = '"all"';
                foreach (cmsGetCategoriesByPostID(get_the_ID(), $taxo) as $category) {
                    $groups[] = '"category-' . $category->slug . '"';
                }
                ?>

                <div class="cms-grid-item <?php echo esc_attr($atts['item_class']); ?>"
                     data-groups='[<?php echo implode(',', $groups); ?>]'>
                    <?php
                    if (has_post_thumbnail() && !post_password_required() && !is_attachment() && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
                        $class = ' has-thumbnail';
                        $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
                    else:
                        $class = ' no-image';
                        $thumbnail = '<img src="' . CMS_IMAGES . 'no-image.jpg" alt="' . get_the_title() . '" />';
                    endif;
                    ?>
                    <div class="cms-grid-media<?php echo esc_attr($class) ?>">
                        <?php echo __($thumbnail) ?>
                    </div>
                    <div class="cms-grid-title">
                        <?php the_title(); ?>
                    </div>
                    <div class="cms-grid-time">
                        <?php the_time('l, F jS, Y'); ?>
                    </div>
                    <div class="cms-grid-categories">
                        <?php echo get_the_term_list(get_the_ID(), $taxo, 'Category: ', ', ', ''); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
    }
}
