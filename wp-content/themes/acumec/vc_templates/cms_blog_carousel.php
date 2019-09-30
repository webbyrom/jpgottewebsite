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
   $word_number = !empty($atts['word_number']) ? $atts['word_number'] : '22';
  
?>

    <div class="cms-blog-carousel cms-carousel owl-carousel next-prev-image" id="<?php echo esc_attr($atts['html_id']);?>" >
        <?php
        $posts = $atts['posts'];
        while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = $category->slug;
            }
              
            ?>
            <?php if ( has_post_thumbnail()): ?>
            <div class="carousel-item vertical-item <?php echo implode(' ', $groups);?>">
        		<div class="item-media">
        			<article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog'); ?>>
                        <div class=" row">
                            <div class="col-sm-6">
                                <div class="blog-wrap">
                                    
                                        <div class="post-thumbnail">
                                            <a href="<?php echo esc_url(get_permalink()) ; ?>"> <?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()),'770x378'); ?></a>       
                                        </div>
                                    
                                    <div class="blog-content">     
                                        <?php if (!empty($atts['blog_date']) && $atts['blog_date'] == '1'): ?>
                                            <div class="blog-date">
                                                 <a href="<?php echo esc_attr(get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d'))); ?>"><span class="archive-day"><?php echo esc_attr(get_the_date('d')); ?></span><span class="archive-month"><?php echo esc_attr(get_the_date('M')); ?></span><span><?php echo esc_attr(get_the_date('Y')); ?></span></a>
                                            </div>
                                       <?php endif ?>
                                       <div class="blog-content-wrap">
                                           <?php if ( (!empty($atts['blog_author']) && $atts['blog_author'] == '1') || (!empty($atts['blog_comment']) && $atts['blog_comment'] == '1') ): ?>
                                                <div class="blog-meta-before">
                                                    <ul class="meta-list">
                                                        <?php if (!empty($atts['blog_author']) && $atts['blog_author'] == '1'): ?>
                                                            <li class="meta-author"><?php echo esc_html__('Posted in','acumec'); ?> <?php the_author_posts_link(); ?></li>
                                                        <?php endif; ?>
                                                        <?php if (!empty($atts['blog_comment']) && $atts['blog_comment'] == '1'): ?>
                                                           <?php  
                                                            $comments_number = get_comments_number();
                                                            if ( '1' === $comments_number ) {?>
                                                                <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _x( '01 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                                                           <?php }else{?>
                                                                 <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                                                            </a></li>
                                                            <?php } ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php endif ?>
                                            <header class="entry-header">
                                                <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>      
                                                <?php if ((!empty($atts['blog_categories']) && $atts['blog_categories'] == '1') || (!empty($atts['blog_tag']) && $atts['blog_tag'] == '1') ): ?>
                                                    <div class="blog-meta-after">
                                                        <ul class="meta-list">
                                                        <?php if (!empty($atts['blog_categories']) && $atts['blog_categories'] == '1'): ?>
                                                            <li class="detail-terms"> <?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                                                        <?php endif ?>
                                                        <?php if (has_tag() && !empty($atts['blog_tag']) && $atts['blog_tag'] == '1'): ?>
                                                             <li class="detail-tags"><span class="fa fa-tag"></span><?php the_tags('', ', ' ); ?></li>
                                                        <?php endif ?>   
                                                        </ul>
                                                    </div>
                                                <?php endif ?>
                                            </header><!-- entry-header  --> 
                                            <?php if (!empty($atts['blog_description']) && $atts['blog_description'] == '1'): ?>
                                                <div class="entry-content">
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
                                            <?php endif ?>     
                                            <?php if (!empty($atts['readmore']) && $atts['readmore'] == '1'): ?>
                                                <?php acumec_archive_readmore(); ?>        
                                            <?php endif ?>   
                                        </div>               
                                    </div><!-- .entry-content -->   
                                </div>     
                            </div>           
                        </div>
                    </article><!-- #post-## -->
        		</div>
        	</div>
            <?php endif; ?> 
<?php
        }
?>
    </div>
