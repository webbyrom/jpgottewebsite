<?php 
    /* get categories */
    $taxonomy = 'project_category';
    global $opt_theme_options, $opt_meta_options, $wp_embed,$service_image_size_index;
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
    $g_style = !empty($atts['g_style']) ? $atts['g_style'] : '';
?>
    <div class="cms-grid-wraper cms-grid-projects <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>"> 
        <?php  if($atts['layout']=='masonry'): ?>
            <?php if($atts['filter']=="true"):?>
                <div class="cms-grid-filter">
                    <ul class="cms-filter-category list-unstyled list-inline">
                        <li><a class="active" href="#" data-group="all"><?php echo esc_html('All'); ?></a></li>
                        <?php 
                        if(is_array($atts['categories']))
                        foreach($atts['categories'] as $category):?>
                            <?php $term = get_term( $category, $taxonomy );?>
                            <li><a href="#" data-group="<?php echo esc_attr('category-'.$term->slug);?>">
                                    <?php echo esc_html($term->name);?>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>
        <?php endif; ?>
        <?php $bottom = !empty($atts['bottom']) ? $atts['bottom'] . ';' : ''; ?>
        <div class="row cms-grid <?php echo esc_attr($atts['grid_class']);?>" style="<?php if (!empty($atts['bottom'])): ?><?php echo 'margin: -'.esc_attr($bottom); ?><?php endif; ?>">
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
                $item = explode(' ',$atts['item_class']); 
                if($item[1] == 'col-lg-2.4') {$item[1] = str_replace($item[1], "",'lg-5');}
                if($item[2] == 'col-md-2.4') {$item[2] = str_replace($item[2], "",'md-5');} 
                $class_item = $item[0].' '.$item[1].' '.$item[2].' '.$item[3].' '.$item[4]; 
                ?>
                <div class="<?php echo esc_attr($class_item);?>" style="<?php if (!empty($atts['bottom'])): ?><?php echo 'padding: '.esc_attr($bottom); ?><?php endif; ?>" data-groups='[<?php echo implode(',', $groups);?>]' onclick="">
                    <?php   
                        $current_size = $image_size_arr = '';
                        if(!empty($atts['images_size'])){
                            $image_size_arr = explode(',',$atts['images_size']);
                            $current_size = $image_size_arr[$i];
                        }
                        if(has_post_thumbnail()){
                            $thumbnail = acumec_get_image_croped(get_post_thumbnail_id( get_the_ID() ),$current_size);
                        }else{
                            $thumbnail = '<img src="'.esc_url(get_template_directory_uri().'/assets/images/no-image.jpg').'" alt="'.get_the_title().'"/>';
                        }
                        $full_img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                        $full_src = esc_url($full_img[0]);
                        $overlay_bg = $text_color = '';
                        if (!empty($atts['bg_overlay'])) {
                            $overlay_bg = 'background: '. $atts['bg_overlay'].';';
                        }
                        if (!empty($atts['t_color'])) {
                            $text_color = 'color: '. $atts['t_color'].';';
                        }
                    ?>

                    <div class="cms-project-inner" >
                        <div class="project-thumbnail">
                            <?php echo acumec_html($thumbnail); ?>
                        </div>
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
                if($i >= count($image_size_arr)) {
                    $i = 0;
                }
            }
            ?>                        
        </div>
        <?php $spacing = !empty($atts['spacing_pagination']) ? 'margin-top: '.$atts['spacing_pagination'].';' : '';
        if( !empty($atts['show_more']) && $atts['show_more'] == 1){?> 
            <div class="cms_pagination grid-loadmore text-center" style="<?php echo esc_attr($spacing); ?>"></div> 
        <?php } ?>
        <?php if ($atts['show_pagination'] == '1'): ?>
            <?php  acumec_paging_nav();?>
        <?php endif; ?>
    </div>
    