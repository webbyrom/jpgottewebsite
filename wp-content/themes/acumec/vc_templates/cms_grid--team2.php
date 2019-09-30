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
        $radius = $bg_overlay = $color = $bgsocial = $colorsocial = '';
        if (!empty($atts['border_radius']) && $atts['border_radius']) {
            $radius = 'border-radius: '.$atts['border_radius'].';';
        } 
        if (!empty($atts['bg_overlay']) && $atts['bg_overlay']) {
            $bg_overlay = 'background: '.$atts['bg_overlay'].';';
        } 
        if (!empty($atts['bg_social']) && $atts['bg_social']) {
            $bgsocial = 'background: '.$atts['bg_social'].';';
        } 
        if (!empty($atts['color_social']) && $atts['color_social']) {
            $colorsocial = 'color: '.$atts['color_social'].';';
        } 
        if (!empty($atts['title_color']) && $atts['title_color']) {
            $color = 'color: '.$atts['title_color'].';';
        } 
?>
<div class="cms-grid-wraper cms-grid-team-2 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>"> 
    <div class="row <?php echo esc_attr($atts['grid_class']);?>">
        <?php
        $posts = $atts['posts'];
        $img_size = !empty($atts['img_size']) ? $atts['img_size']  : '370x230';
        while($posts->have_posts()){
            $posts->the_post();
            $item = explode(' ',$atts['item_class']); 
            if($item[1] == 'col-lg-2.4') {$item[1] = str_replace($item[1], "",'lg-5');}
            if($item[2] == 'col-md-2.4') {$item[2] = str_replace($item[2], "",'md-5');} 
            $class_item = $item[0].' '.$item[1].' '.$item[2].' '.$item[3].' '.$item[4]; 
            ?>
            <div class="cms-grid-item <?php echo esc_attr($class_item);?>">
                <div class="cms-grid-item-wrap">
                    <div class="team-item mutted-hover text-center" onclick="">
                        <?php 
                            if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size, false)):
                                $class = ' has-thumbnail';
                            else:
                                $class = ' no-image';
                            endif;
                        ?>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="item-media" style=" <?php echo esc_attr($radius);?>" >                                   
                                <div class="media-links">
                                    <div class="cms-grid-media"><?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()),$img_size); ?> </div>   
                                </div>
                                
                                 <?php if (!empty($opt_meta_options['team_link']) && !empty($opt_meta_options['team_link_title'])): ?>
                                    <div class="media-overlay" style="<?php echo esc_attr($bg_overlay); ?>">
                                        <?php if (!empty($atts['show_button']) && $atts['show_button'] == 1): ?>
                                            <div class="team-btn">
                                                <a class="team-button btn btn-theme-primary btn-round" href="<?php echo esc_attr($opt_meta_options['team_link']); ?>" title="<?php echo esc_attr($opt_meta_options['team_link_title']); ?>"><?php if (!empty($opt_meta_options['team_link_title'])): ?><i class="<?php echo esc_attr($opt_meta_options['team_link_icon']); ?>"></i><?php endif; ?><?php echo esc_attr($opt_meta_options['team_link_title']); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div> 
                                <?php endif; ?>
                            
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="item-content">
                        <div class="item-content-wrap">
                            <div class="item-heading">
                                <?php the_title( '<h4 class="entry-title" style="'. esc_attr($color) .'"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?> 
                                <?php if (!empty($opt_meta_options['team_position']) ): ?>
                                    <div class="team-position">
                                        <?php echo esc_attr( $opt_meta_options['team_position'] ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>   
                            <?php if (!empty($atts['show_social']) && $atts['show_social'] == 1): ?>
                                <?php if (!empty($opt_meta_options['social_enable']) && $opt_meta_options['social_enable'] == 1 ): ?>
                                    <ul class="team-social">
                                        <?php if (!empty($opt_meta_options['team_social_icon_1']) ): ?>
                                            <li><?php if (!empty($opt_meta_options['team_social_1']) ): ?>
                                                <a style="<?php echo esc_attr($bgsocial) ?><?php echo esc_attr($colorsocial); ?>" href="<?php echo esc_attr($opt_meta_options['team_social_1']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_1']); ?>"></i></a> <?php else: ?>
                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_1']); ?>"></i>
                                                <?php endif; ?> 
                                            </li>
                                            <li><?php if (!empty($opt_meta_options['team_social_2']) ): ?>
                                                <a style="<?php echo esc_attr($bgsocial) ?><?php echo esc_attr($colorsocial); ?>" href="<?php echo esc_attr($opt_meta_options['team_social_2']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_2']); ?>"></i></a> <?php else: ?>
                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_2']); ?>"></i>
                                                <?php endif; ?> 
                                            </li>
                                            <li><?php if (!empty($opt_meta_options['team_social_3']) ): ?>
                                                <a style="<?php echo esc_attr($bgsocial) ?><?php echo esc_attr($colorsocial); ?>" href="<?php echo esc_attr($opt_meta_options['team_social_3']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_3']); ?>"></i></a> <?php else: ?>
                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_3']); ?>"></i>
                                                <?php endif; ?> 
                                            </li>
                                            <li><?php if (!empty($opt_meta_options['team_social_4']) ): ?>
                                                <a style="<?php echo esc_attr($bgsocial) ?><?php echo esc_attr($colorsocial); ?>" href="<?php echo esc_attr($opt_meta_options['team_social_4']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_4']); ?>"></i></a> <?php else: ?>
                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_4']); ?>"></i>
                                                <?php endif; ?> 
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?> 
                            <?php endif; ?>
                        </div>
                        <?php if ( (!empty($atts['show_email']) && $atts['show_email'] == 1) || (!empty($atts['show_phone']) && $atts['show_phone'] == 1) || (!empty($atts['show_location']) && $atts['show_location'] == 1) ): ?>
                            <ul class="team-contact">
                                <?php if (!empty($atts['show_email']) && $atts['show_email'] == 1): ?>
                                     <?php if (!empty($opt_meta_options['team_email'])): ?>
                                        <li style="<?php echo esc_attr($color); ?>"><span class="title fa fa-envelope"></span><span ><?php echo esc_attr( $opt_meta_options['team_email'] ); ?></span></li>
                                    <?php endif; ?>
                                <?php endif ?>
                                <?php if (!empty($atts['show_phone']) && $atts['show_phone'] == 1): ?>
                                    <?php if (!empty($opt_meta_options['team_phone'])): ?>
                                        <li style="<?php echo esc_attr($color); ?>"><span class="title fa fa-phone"></span><span><?php echo esc_attr( $opt_meta_options['team_phone'] ); ?></span></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (!empty($atts['show_location']) && $atts['show_location'] == 1): ?>
                                    <?php if (!empty($opt_meta_options['team_location'])): ?>
                                        <li style="<?php echo esc_attr($color); ?>"><span class="title fa fa-map-marker"></span><span><?php echo esc_attr( $opt_meta_options['team_location'] ); ?></span></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
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