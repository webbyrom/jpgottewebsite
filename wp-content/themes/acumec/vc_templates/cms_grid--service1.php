<?php 
    global $opt_meta_options;
        if(isset($atts['show_more']) && $atts['show_more']):  
            wp_register_script( 'cms-loadmore-js', get_template_directory_uri().'/assets/js/cms_loadmore.js', array('jquery') ,'1.0',true);
            wp_localize_script('cms-loadmore-js', 'ajax_data', array('url' => admin_url('admin-ajax.php'),'add' => 'new_reservation'));
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
                    'masonry' => $atts['layout'],
                    'loadmore_text' => esc_html__( 'Load more', 'acumec' )
                )
            );
            wp_enqueue_script( 'cms-loadmore-js' ); 
        endif; 
        $radius = $bg_overlay = '';
        if (!empty($atts['radius']) && $atts['radius']) {
            $radius = 'border-radius: '.$atts['radius'].';';
        } 
        if (!empty($atts['bg_overlay']) && $atts['bg_overlay']) {
            $bg_overlay = 'background: '.$atts['bg_overlay'].';';
        } 
        $word_number = !empty($atts['word_number']) ? $atts['word_number'] : '15';


?>
<div class="cms-grid-wraper cms-grid-service-1 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>"> 
    <div class="row <?php echo esc_attr($atts['grid_class']);?>">
        <?php
        $posts = $atts['posts'];
        $img_size = !empty($atts['img_size']) ? $atts['img_size']  : 'full';
        while($posts->have_posts()){
            $posts->the_post();
            $item = explode(' ',$atts['item_class']); 
            if($item[1] == 'col-lg-2.4') {$item[1] = str_replace($item[1], "",'lg-5');}
            if($item[2] == 'col-md-2.4') {$item[2] = str_replace($item[2], "",'md-5');} 
            $class_item = $item[0].' '.$item[1].' '.$item[2].' '.$item[3].' '.$item[4]; 
            $bg1 = $bg2 = $t_color = $t_colorhover = "";
            if (!empty($opt_meta_options['service_background']['rgba'])) {
                $bg1 = "background: ".$opt_meta_options['service_background']['rgba'].";";
            }
            if (!empty($opt_meta_options['service_image']['url'])) {
                $bg2 = "background: url(".$opt_meta_options['service_image']['url'].");";
            }
            if (!empty($atts['tcolor'])) {
               $t_color = "color: ".$atts['tcolor'].";";
            }
            if (!empty($atts['tcolor_hover'])) {
               $t_colorhover = "background: ".$atts['tcolor_hover'].";";
            }
            ?>
            <div class="cms-grid-item <?php echo esc_attr($class_item);?>">
                <div class="cms-grid-service" style=" <?php echo esc_attr($radius);?> ">
                    <div class="service-bg1" style="<?php echo esc_attr($bg1); ?>"></div>
                    <div class="service-bg2" style="<?php echo esc_attr($bg2); ?>">
                        <div class="overlay" style="<?php echo esc_attr($t_colorhover); ?>"></div>
                    </div>
                    <div class="service-item mutted-hover text-center" onclick="">
                        <?php 
                            if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size, false)):
                                $class = ' has-thumbnail';
                            else:
                                $class = ' no-image';
                            endif;
                        ?>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="item-media"  >   
                                <div class="media-links">
                                    <div class="cms-grid-media"><?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()), $img_size); ?></div>   
                                </div>                           
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="item-content text-center">
                        <?php the_title( '<h4 class="entry-title" style="'.esc_attr($t_color).'" ><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
                        <?php if (!empty($atts['show_des'])): ?>
                            <div class="item-description" style="<?php echo esc_attr($t_color); ?>">
                                <?php echo acumec_grid_limit_words(strip_tags(get_the_excerpt()),$word_number);?> 
                            </div> 
                        <?php endif; ?>                   
                    </div>
                </div>      
            </div>
            <?php
        }
        ?>
    </div>
    <?php 
        if(!empty($atts['show_more']) && $atts['show_more'] == 1)
            echo '<div class="loadmore text-center"><div class="cms_pagination grid-loadmore "></div></div>';
    ?>
    <?php if (!empty($atts['show_pagination']) && $atts['show_pagination'] == '1'): ?>
         <?php  acumec_paging_nav();?>
    <?php endif ?>
</div>