<?php 

$word_number = !empty($atts['word_number']) ? $atts['word_number'] : '22';
global $opt_meta_options;
$c_color = !empty($atts['category_color']) ? 'color: '.$atts['category_color'].';' : '';
?>
<div class="cms-carousel owl-carousel next-prev-custom <?php echo esc_attr($atts['template']);?> " id="<?php echo esc_attr($atts['html_id']);?>">
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
                
            <article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog'); ?>>
                <div class="blog-outer">    
                    <div class="blog-wrap blog-column">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-thumbnail">
                                <a href="<?php echo esc_url(get_permalink()) ; ?>"><?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()),'770x534'); ?></a>     
                            </div>
                        <?php endif; ?>
                        <div class="blog-content">
                            <header class="entry-header">  
                                <?php if(is_sticky())
                                    echo '<span class="post-sticky"><span class="pe-7s-pin"></span></span>';
                                ?>
                                <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
                            <?php if (!empty($atts['show_categories']) &&  $atts['show_categories']=='1'): ?>
                                <div class="entry-meta">
                                    <ul class="archive_detail" style="<?php echo esc_attr($c_color); ?> ">  
                                        <?php if(taxonomy_exists('case_studies_category') && (!empty($atts['show_categories']) &&  $atts['show_categories']=='1')): ?>
                                            <li class="detail-terms"><span class="fa fa-tag"></span> <?php echo get_the_term_list( get_the_ID(), 'case_studies_category', '', ', ', '' ); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div><!-- .entry-meta -->
                            <?php endif ?>      
                            </header><!-- .entry-header -->
                            <?php if (!empty($atts['show_description']) && $atts['show_description'] =='1'): ?>
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
                            <?php endif; ?>  
                        </div><!-- .entry-content -->   
                    </div>      
                </div>
            </article><!-- #post-## -->

        </div>
        <?php
        $index++;
    }
    ?>
</div>
 