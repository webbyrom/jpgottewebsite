<?php 
    /* get categories */
    $taxonomy = 'project_category';
    global $opt_meta_options;
    $_category = array();
    if(!isset($atts['cat']) || $atts['cat']==''){
        $args = [
            'taxonomy'     => $taxonomy,
            'parent'        => 0,
            'hide_empty'    => true           
        ];
        $terms = get_terms($args);
        foreach ($terms as $cat){
            $_category[] = $cat->term_id;
        }
         
    } else {
        $_category  = explode(',', $atts['cat']);
    }
    $atts['categories'] = $_category;
    wp_enqueue_style( 'wp-mediaelement' );
    wp_enqueue_script( 'wp-mediaelement' );
     
    wp_register_script( 'cms-loadmore-js', get_template_directory_uri().'/assets/js/cms_loadmore.js', array('jquery') ,'1.0',true);
    // What page are we on? And what is the pages limit?
    global $wp_query;

    $max = $wp_query->max_num_pages;
    $limit = $atts['limit'];
    $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    // Add some parameters for the JS.
    $current_id =  str_replace('-','_',$atts['html_id']);
    wp_localize_script(
        'cms-loadmore-js',
        'cms_more_obj'.$current_id,
        array(
            'startPage' => $paged,
            'maxPages' => $max,
            'total' => $wp_query->found_posts,
            'perpage' => $limit,
            'nextLink' => next_posts($max, false),
            'masonry' => 'masonry',  
            'loadmore_text' => esc_html__( 'View All Project', 'acumec' )
        )
    );
    wp_enqueue_script( 'cms-loadmore-js' ); 
    $overlap = $border = '';
    if (!empty($atts['top_overlap']) && $atts['top_overlap'] == '1') {
        $overlap = 'top-overlap';
    }

    if (!empty($atts['border']) && $atts['border'] == '1') {
        $border = 'border';
    }
    $filter_color = !empty($atts['filter_color']) ? 'color: '. $atts['filter_color'] .';': '';
    $tcolor_button = !empty($atts['tcolor_button']) ? 'color: '. $atts['tcolor_button'] .';' : '';
    $bg_button = !empty($atts['bg_button']) ? 'background: '. $atts['bg_button'] .';' : '';
    $padding = !empty($atts['padding']) ? 'margin-top: '.$atts['padding'].'px;' : '';
?>
    <div class="cms-grid-wraper zo-masonry-wrapper cms-projects <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
            
        <?php if( isset($atts['filter']) && $atts['filter'] == 1 ) :?>

            <div class="zo-masonry-filter">
                <ul class="zo-filter-category list-unstyled list-inline ">
                    <?php $count_posts = wp_count_posts('project'); ?>
                    <li><a class="active" href="#" data-group="all" title="<?php echo esc_attr($count_posts->publish); ?>" style="<?php echo esc_attr($filter_color); ?>"><?php esc_html_e('All','acumec')?></a></li>
                    <?php foreach($atts['categories'] as $category):?>
                        <?php $term = get_term( $category, $taxonomy );?>
                        <li><a href="#" data-group="<?php echo esc_attr('category-'.$term->slug);?>" data-placement="top" title="<?php echo esc_attr($term->count); ?>" style="<?php echo esc_attr($filter_color); ?>">
                                <?php echo esc_attr($term->name);?>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>

            </div>
        <?php endif;?>
        <div class="zo-masonry cms-grid-masonry cms-grid">
            <?php
            $posts = $atts['posts'];
            $i = 0;
            while($posts->have_posts()){
                $posts->the_post();
                $groups = array();
                $groups[] = '"all"';
                foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxonomy) as $category){
                    $groups[] = '"category-'.$category->slug.'"';
                }
                $size = zo_masonry_size($atts['post_id'] , $atts['html_id'], $i);
              
                ?>
                <div class="zo-masonry-item cms-grid-item item-w<?php echo esc_attr($size['width']); ?> item-h<?php echo esc_attr($size['height']); ?>"
                         data-groups='[<?php echo implode(',', $groups);?>]' data-index="<?php echo esc_attr($i); ?>" data-id="<?php echo esc_attr($atts['post_id']); ?>" onclick="">
                    <?php 
                        if(has_post_thumbnail()):
                            if (!empty($g_style) && $g_style == 'style2') {
                                $thumbnails = wp_get_attachment_image_src( get_post_thumbnail_id(), 'acumec-770-375' );  
                            }
                            else {
                                $thumbnails = wp_get_attachment_image_src( get_post_thumbnail_id(), 'acumec-770-770' );
                            }
                            
                            $thumbnail = $thumbnails[0];
                            $full_img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                            $full_src = esc_url($full_img[0]);
                            $img_style = 'background-image: url("'.esc_url($thumbnail).'");background-position: center;background-repeat: no-repeat;background-size: cover;' ;
                        endif;
                        $overlay_bg = $text_color = '';
                        if (!empty($atts['overlay_bg'])) {
                            $overlay_bg = 'background: '. $atts['overlay_bg'].';';
                        }
                        if (!empty($atts['text_color'])) {
                            $text_color = 'color: '. $atts['text_color'].';';
                        }
                    ?>
                    <div class="zo-masonry-inner" style ="<?php echo esc_attr($img_style); ?>">
                        <div class="grid-item-wrap" onclick="" style = "<?php echo esc_attr($overlay_bg); ?>">
                            <div class="cms-grid-inner">
                                <div class="item-content">
                                    <div class="taxs" style="<?php echo esc_attr($text_color);?>">
                                        <?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ', '' ); ?>    
                                    </div>
                                    <?php the_title( '<h4 class="entry-title" style="'.esc_attr($text_color).'"><a href="'.esc_url(get_permalink()).'">', '</a></h4>' ); ?>
                                    <?php if ( (!empty($atts['show_btn_full']) && $atts['show_btn_full'] == 1) || (!empty($atts['show_btn_link']) && $atts['show_btn_link'] == 1) ): ?>
                                        <div class="grid-link cms-gallerys">
                                        <?php if (!empty($atts['show_btn_full']) && $atts['show_btn_full'] == 1): ?>
                                            <a class="item-popup magic-popups" href="<?php echo esc_url($full_src);?>" style=" <?php echo esc_attr($tcolor_button) ?> <?php echo esc_attr($bg_button); ?>"> <span class="fa fa-search"></span></a>
                                        <?php endif; ?>
                                        <?php if (!empty($atts['show_btn_link']) && $atts['show_btn_link'] == 1): ?>
                                            <a href="<?php echo esc_url(get_permalink()) ; ?>" style=" <?php echo esc_attr($tcolor_button) ?> <?php echo esc_attr($bg_button); ?>"><span class="fa fa-link"></span> </a>   
                                        <?php endif; ?>        
                                        </div>
                                    <?php endif ?>
                                        
                                </div> 
                            </div>                  
                        </div>
                    </div>
                </div>
                <?php
                $i++;

            }
            ?>
        </div>
        <?php if( !empty($atts['show_more']) && $atts['show_more']){?> 
            <div class="cms_pagination grid-loadmore text-center"></div> 
        <?php } ?>
            <?php if (!empty($atts['show_pagination']) && $atts['show_pagination'] == '1'): ?>
                <?php  if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
                    return;
                }

                $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
                $pagenum_link = html_entity_decode( get_pagenum_link() );
                $query_args   = array();
                $url_parts    = explode( '?', $pagenum_link );

                if ( isset( $url_parts[1] ) ) {
                    wp_parse_str( $url_parts[1], $query_args );
                }

                $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
                $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

                // Set up paginated links.
                $links = paginate_links( array(
                        'base'     => $pagenum_link,
                        'total'    => $GLOBALS['wp_query']->max_num_pages,
                        'current'  => $paged,
                        'mid_size' => 1,
                        'add_args' => array_map( 'urlencode', $query_args ),
                        'prev_text' => '<i class="fa fa-angle-double-left"></i>',
                        'next_text' => '<i class="fa fa-angle-double-right"></i>',
                ) );

                if ( $links ) :

                ?>
                <div class="navigation paging-navigation clearfix" role="navigation" style="<?php echo esc_attr($padding); ?>">
                        <div class="pagination loop-pagination">
                            <?php echo wp_kses_post($links); ?>
                        </div><!-- .pagination -->
                </div><!-- .navigation -->
                <?php
            endif;?>
        <?php endif ?>
    </div>
    