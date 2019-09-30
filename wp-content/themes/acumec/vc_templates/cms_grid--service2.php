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
                    'loadmore_text' => esc_html__( 'Much more', 'acumec' )
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
<div class="cms-grid-wraper cms-grid-service-2 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>"> 
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
            $t_color = "";

            if (!empty($atts['tcolor'])) {
               $t_color = "color: ".$atts['tcolor'].";";
            }
            $c_color = !empty($atts['ccolor']) ? 'color: ' . $atts['ccolor'] . ';' : '';
            ?>
            <div class="cms-grid-item <?php echo esc_attr($class_item);?>">
                <div class="cms-grid-service">
                    <div class="service-item mutted-hover" onclick="">
                        <?php 
                            if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size, false)):
                                $class = ' has-thumbnail';
                            else:
                                $class = ' no-image';
                                $thumbnail = '';
                                $image_url = '#';
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
                    <div class="item-content">
                        <?php the_title( '<h5 class="service-title" style="'.esc_attr($t_color).'" ><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
                        <?php if (!empty($atts['show_des'])): ?>
                            <div class="item-description" style="<?php echo esc_attr($t_color); ?>">
                                <?php echo acumec_grid_limit_words(strip_tags(get_the_excerpt()),$word_number);?> 
                            </div> 
                        <?php endif; ?>
                        <?php if (!empty($atts['show_list'])): ?>
                            <div class="item-list" style="<?php echo esc_attr($c_color); ?>">
                                <ul class="service-list">
                                    <?php if (!empty($opt_meta_options['service_item_1']) ): ?>
                                        <li> <span style="<?php echo esc_attr($c_color); ?>"><?php echo esc_attr($opt_meta_options['service_item_1']); ?></span></li>
                                    <?php endif; ?>    
                                    <?php if (!empty($opt_meta_options['service_item_2']) ): ?>
                                        <li> <span style="<?php echo esc_attr($c_color); ?>"><?php echo esc_attr($opt_meta_options['service_item_2']); ?></span></li>
                                    <?php endif; ?>   
                                    <?php if (!empty($opt_meta_options['service_item_3']) ): ?>
                                        <li> <span style="<?php echo esc_attr($c_color); ?>"><?php echo esc_attr($opt_meta_options['service_item_3']); ?></span></li>
                                    <?php endif; ?>   
                                    <?php if (!empty($opt_meta_options['service_item_4']) ): ?>
                                        <li> <span style="<?php echo esc_attr($c_color); ?>"><?php echo esc_attr($opt_meta_options['service_item_4']); ?></span></li>
                                    <?php endif; ?>   
                                    <?php if (!empty($opt_meta_options['service_item_5']) ): ?>
                                        <li> <span style="<?php echo esc_attr($c_color); ?>"><?php echo esc_attr($opt_meta_options['service_item_5']); ?></span></li>
                                    <?php endif; ?>   
                                </ul> 
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