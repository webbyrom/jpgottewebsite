<?php 
     
    /* get categories */
    $taxo = 'category';
    $_category = array();
    if(!isset($atts['cat']) || $atts['cat']==''){
        $args = [
            'taxonomy'     => $taxo,
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
    
    $classes=array('cms-category');
    if(!empty($atts['css'])){
        $classes[]=vc_shortcode_custom_css_class($atts['css']);
    }     
    $css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $classes ) ), $this->settings['base'], $atts ) );
    $image = $bg_pagination = '';
    $word_number = !empty($atts['word_number']) ? $atts['word_number'] : '22';
    $image_size = !empty($atts['image_size']) ? $atts['image_size'] : '1920x683';
    $bg_color = !empty($atts['bg_color']) ? 'background: '.$atts['bg_color'].';' : '';
    $t_color = !empty($atts['t_color']) ? 'color: '.$atts['t_color'].';' : '';
    $animate_class = !empty($atts['animation_effect']) ? $atts['animation_effect'] : ''; 
    $duration = !empty($atts['data_wow_duration'])?'data-wow-duration='.$atts['data_wow_duration'].' ':'';
    $delay  = !empty($atts['data_wow_delay'])?'data-wow-delay='.$atts['data_wow_delay'].' ':'';
    if (!empty($atts['show_image']) && $atts['show_image'] == 1) {
        $image = "next-prev-image";
    }
?>

    <div class="cms-post-carousel cms-carousel owl-carousel next-prev-title <?php echo esc_attr($image); ?>" id="<?php echo esc_attr($atts['html_id']);?>" >
        <?php
        $posts = $atts['posts'];
        global $opt_meta_options;
        while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = $category->slug;
            }
            if ( has_post_thumbnail()):
                ?>
                <div class="carousel-item vertical-item <?php echo implode(' ', $groups);?>">
            		<div class="item-media">
            			<div id="post-<?php the_ID(); ?>" <?php post_class('post-carousel'); ?>>
                            
                                <?php 
                                    $thumbnails = wp_get_attachment_image_src( get_post_thumbnail_id(), 'acumec-1920-683' );                           
                                    $thumbnail = $thumbnails[0]; 
                                ?>
                                <div class="post-thumbnail" style="background-image: url(<?php echo esc_attr($thumbnail); ?>);">
                                <div class="bg_overlay" style="<?php echo esc_attr($bg_color); ?>"></div>     
                                </div>
                                                               
                                <div class="blog-content">     
                                    <div  class="blog-content-wrap">
                                        <header class="entry-header">
                                        <?php if (!empty($atts['subtitle']) && $atts['subtitle'] == 1): ?>
                                            <?php if (!empty($opt_meta_options['opt-subtitle'])): ?>
                                                <h3 <?php echo esc_attr($duration); ?> <?php echo esc_attr($delay); ?> class="sub-title <?php echo esc_attr($animate_class);?>" style="<?php echo esc_attr($t_color); ?>"><?php echo esc_attr($opt_meta_options['opt-subtitle']); ?></h3>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                            <?php the_title( '<h2 '.esc_attr($duration). esc_attr($delay).' class="entry-title '.esc_attr($animate_class).'" style="'. esc_attr($t_color).'"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>      
                                        </header><!-- entry-header  --> 
                                        <?php if (!empty($atts['blog_description']) && $atts['blog_description'] == '1'): ?>
                                            <div <?php echo esc_attr($duration); ?> <?php echo esc_attr($delay); ?> class="<?php echo esc_attr($animate_class);?> entry-content" style="<?php echo esc_attr($t_color); ?>">
                                                <p>
                                                    <?php
                                                    /* translators: %s: Name of current post */
                                                    echo acumec_grid_limit_words(strip_tags(get_the_excerpt()),$word_number);
                                                    wp_link_pages( array(
                                                        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages', 'acumec' ) . '</span>',
                                                        'after'       => '</div>',
                                                        'link_before' => '<span>',
                                                        'link_after'  => '</span>',
                                                    ) );
                                                    ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>     
                                        <?php if (!empty($atts['readmore']) && $atts['readmore'] == '1'): ?>
                                            <div <?php echo esc_attr($duration); ?> <?php echo esc_attr($delay); ?> class="<?php echo esc_attr($animate_class);?> post-readmore">
                                                <footer class="entry-footer">
                                                    <a class="btn btn-theme-primary btn-round" href="<?php the_permalink(); ?>"><?php esc_html_e('Get Start Now', 'acumec') ?></a>
                                                </footer><!-- .entry-footer -->
                                            </div>        
                                        <?php endif;?>   
                                    </div>               
                                </div><!-- .entry-content -->   
                             
                        </div><!-- #post-## -->
            		</div>
            	</div>
            <?php endif; ?>
<?php  }?>
    
    </div>
<span class="hidden hidden-val"></span>