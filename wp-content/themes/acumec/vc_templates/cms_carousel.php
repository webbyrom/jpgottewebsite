<?php 
$style= 'style-1';
$word_number = !empty($atts['word_number']) ? $atts['word_number'] : '22';
global $opt_meta_options;
?>
<div class="cms-carousel owl-carousel next-prev-custom <?php echo esc_attr($atts['template']);?> <?php echo esc_attr($style); ?> " id="<?php echo esc_attr($atts['html_id']);?>">
    <?php
    $posts = $atts['posts'];
    $index = 1; 
    while($posts->have_posts()){
        $posts->the_post();
        ?>
        <div class="cms-carousel-item">
            <?php 
                if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', false)):
                    $thumbnail = get_the_post_thumbnail(get_the_ID(),'medium');
                endif;       
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog-grid wow fadeInUp'); ?>>
                    <div class="blog-grid-outer">
                        <div class="blog-grid">
                            <div class="row">
                            <?php if (!empty($thumbnail)): ?>
                                <div class="blog-grid-thumbnail">
                                    <div class="post-thumbnail">
                                        <a href="<?php echo esc_url(get_permalink()) ; ?>"><?php echo wp_kses_post($thumbnail); ?></a>       
                                    </div>
                                </div> 
                            <?php endif ?>                      
                                <div class="blog-grid-wrap">
                                    <div class="blog-content">
                                        <header class="entry-header">
                                            <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
                                            <?php 
                                                $_year  = get_the_time('Y'); 
                                                $_month = get_the_time('m'); 
                                                $_day   = get_the_time('d');
                                             ?>  
                                             <a class="blog-date" href="<?php echo esc_attr(get_day_link( $_year, $_month, $_day)); ?>"><i class="fa fa-clock-o"></i><span><?php echo esc_attr(get_the_date('F d, Y')); ?></span></a>                         
                                        </header><!-- .entry-header -->
                                        <?php if (!empty($atts['show_description']) && $atts['show_description'] == '1'): ?>
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
                                        <?php endif ?> 
                                        <?php if (!empty($atts['show_more']) && $atts['show_more'] == '1'): ?>
                                            <footer class="entry-footer">
                                                <a class="btn btn-theme-default" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'acumec') ?><i class="fa fa-angle-double-right"></i></a>
                                            </footer><!-- .entry-footer -->
                                        <?php endif ?>
                                    </div><!-- .entry-content -->   
                                </div>          
                            </div>
                        </div>      
                    </div>
                </article><!-- #post-## -->

        </div>
        <?php
        $index++;
    }
    ?>
</div>
 