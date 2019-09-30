<?php 
    /* get categories */
        $has_readmore = "";
        $taxo = 'category';
        $_category = array();
        if(!isset($atts['cat']) || $atts['cat']==''){
            $terms = get_terms($taxo);
            foreach ($terms as $cat){
                $_category[] = $cat->term_id;
            }
        } else {
            $_category  = explode(',', $atts['cat']); 
        }
        $atts['categories'] = $_category;
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
                    'loadmore_text' => esc_html__( 'View More Post', 'acumec' )
                )
            );
            wp_enqueue_script( 'cms-loadmore-js' ); 
        endif; 
        if (!empty($atts['show_more']) && $atts['show_more'] =='1') {
            $has_readmore = 'has-readmore';
        }
        $word_number = !empty($atts['word_number']) ? $atts['word_number'] : '22';
        
?>
<div class="cms-grid-wraper <?php echo esc_attr($atts['template']);?> <?php echo esc_attr($atts['layout']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php  if($atts['layout']=='masonry'): ?>
    <?php if($atts['filter']=="true"):?>
        <div class="cms-grid-filter">
            <ul class="cms-filter-category list-unstyled list-inline">
                <li><a class="active" href="#" data-group="all"><?php echo esc_html('All'); ?></a></li>
                <?php 
                if(is_array($atts['categories']))
                foreach($atts['categories'] as $category):?>
                    <?php $term = get_term( $category, $taxo );?>
                    <li><a href="#" data-group="<?php echo esc_attr('category-'.$term->slug);?>">
                            <?php echo esc_html($term->name);?>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    <div class="row cms-grid <?php echo esc_attr($atts['grid_class']);?>" >
        <?php
        
        $posts = $atts['posts'];
        while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            $groups[] = '"all"';
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = '"category-'.$category->slug.'"';
            }
            $item = explode(' ',$atts['item_class']); 
            if($item[1] == 'col-lg-2.4') {$item[1] = str_replace($item[1], "",'lg-5');}
            if($item[2] == 'col-md-2.4') {$item[2] = str_replace($item[2], "",'md-5');} 
            $class_item = $item[0].' '.$item[1].' '.$item[2].' '.$item[3].' '.$item[4]; 
            ?>

            <div class="<?php echo esc_attr($class_item);?> <?php echo esc_attr($has_readmore); ?>" data-groups='[<?php echo implode(',', $groups);?>]' >
                <article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog'); ?>>
                    <div class="blog-outer">    
                        <div class="blog-wrap blog-column">
                            <div class="blog-content">
                                <header class="entry-header">
                                    <?php acumec_archive_detail(); ?>
                                    <?php if(is_sticky())
                                        echo '<span class="post-sticky"><span class="pe-7s-pin"></span></span>';
                                    ?>
                                    <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>      
                                </header><!-- .entry-header -->
                                <?php if (!empty($atts['show_des'])): ?>
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
                                <?php acumec_archive_author(); ?>
                            <?php if(isset($atts['show_readmore']) && $atts['show_readmore'] == 1): ?>
                                <?php acumec_archive_readmore(); ?>
                            <?php endif; ?>
                            </div><!-- .entry-content -->   
                        </div>      
                    </div>
                </article><!-- #post-## -->
            </div>
            <?php
        }
        ?>
    </div>
    <?php endif; ?>

    <?php  if($atts['layout']=='basic'): ?>
    <div class="row cms-grid <?php echo esc_attr($atts['grid_class']);?>">
        <?php
        $posts = $atts['posts'];
        $size = ($atts['layout']=='basic')?'thumbnail':'medium';
        while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            $groups[] = '"all"';
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = '"category-'.$category->slug.'"';
            }
            $item = explode(' ',$atts['item_class']); 
            if($item[1] == 'col-lg-2.4') {$item[1] = str_replace($item[1], "",'lg-5');}
            if($item[2] == 'col-md-2.4') {$item[2] = str_replace($item[2], "",'md-5');} 
            $class_item = $item[0].' '.$item[1].' '.$item[2].' '.$item[3].' '.$item[4]; 
            ?>
            <div class="<?php echo esc_attr($class_item);?> " data-groups='[<?php echo implode(',', $groups);?>]'>
                <article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog'); ?>>
                    <div class="blog-outer">    
                        <div class="blog-wrap blog-column">
                            <div class="blog-content">
                                <header class="entry-header">
                                    <?php acumec_archive_detail(); ?>
                                    <?php if(is_sticky())
                                        echo '<span class="post-sticky"><span class="pe-7s-pin"></span></span>';
                                    ?>
                                    <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>      
                                </header><!-- .entry-header -->
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
                                <?php acumec_archive_author(); ?>
                            <?php if(isset($atts['show_readmore']) && $atts['show_readmore'] == 1): ?>
                                <?php acumec_archive_readmore(); ?>
                            <?php endif; ?>
                            </div><!-- .entry-content -->   
                        </div>      
                    </div>
                </article><!-- #post-## -->
            </div>
            <?php
        }
        ?>
    </div>
    <?php endif; ?>
    <?php 
        if(!empty($atts['show_more']) && $atts['show_more'] == 1)
            echo '<div class="loadmore text-center"><div class="cms_pagination grid-loadmore "></div></div>';
    ?>
    <?php if (!empty($atts['show_pagination']) && $atts['show_pagination'] == '1'): ?>
        <?php  acumec_paging_nav();?>
    <?php endif; ?>
</div>
