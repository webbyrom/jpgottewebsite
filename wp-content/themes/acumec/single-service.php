<?php
/**
 * The Template for displaying all single posts
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
$_get_sidebar = acumec_post_sidebar();
get_header(); ?>
<div id="primary" class="container ">
    <div class="row row-single <?php echo esc_attr($_get_sidebar); ?>">
        <div class="<?php acumec_post_class(); ?>">
            <div id="main" class="site-main single-service" >
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    // Include the single content template.
                     global $opt_theme_options, $opt_meta_options; ?>
                    <?php 
                        $thumbnail = "";
                        if(!empty($opt_meta_options['service_image']['url'])) {
                            $thumbnail = $opt_meta_options['service_image']['url']; 
                        } 
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-service-wrap ">
                            <div class="entry-text">            
                                        <header class="entry-header">
                                            <?php if ( !empty($thumbnail) ): ?>
                                            <div class="header-left">
                                                <div class="post-image">
                                                    <?php echo acumec_get_image_croped($opt_meta_options['service_image']['id'], '770x695'); ?>    
                                                </div> 
                                            </div>
                                            <?php endif; ?>
                                            <div class="header-right">
                                                <div class="header-right-wrap">
                                                    <?php if ( has_post_thumbnail()): ?>
                                                        <div class="post-thumbnail">
                                                            <?php acumec_post_thumbnail('full'); ?>    
                                                        </div> 
                                                    <?php endif; ?>
                                                    <div class="header-right-heading">
                                                        <?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
                                                            
                                                            <?php the_title( '<h3 class="service-title">', '</h3>' ); ?>
                                                        <?php endif; ?>
                                                        <?php if (!empty($opt_meta_options['service_type']) ): ?>
                                                            <div class="service-type">
                                                                <?php echo esc_attr( $opt_meta_options['service_type'] ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="service-cate">
                                                            <i class="fa fa-tag"></i><?php echo get_the_term_list( get_the_ID(), 'service_category', '', ', ', '' );?>
                                                        </div>
                                                        <?php if (!empty($opt_meta_options['list_enable']) && $opt_meta_options['list_enable'] == 1 ): ?>
                                                            <ul class="service-list">
                                                                <?php if (!empty($opt_meta_options['service_item_1']) ): ?>
                                                                    <li> <span><?php echo esc_attr($opt_meta_options['service_item_1']); ?></span></li>
                                                                <?php endif; ?>    
                                                                <?php if (!empty($opt_meta_options['service_item_2']) ): ?>
                                                                    <li> <span><?php echo esc_attr($opt_meta_options['service_item_2']); ?></span></li>
                                                                <?php endif; ?>   
                                                                <?php if (!empty($opt_meta_options['service_item_3']) ): ?>
                                                                    <li> <span><?php echo esc_attr($opt_meta_options['service_item_3']); ?></span></li>
                                                                <?php endif; ?>   
                                                                <?php if (!empty($opt_meta_options['service_item_4']) ): ?>
                                                                    <li> <span><?php echo esc_attr($opt_meta_options['service_item_4']); ?></span></li>
                                                                <?php endif; ?>   
                                                                <?php if (!empty($opt_meta_options['service_item_5']) ): ?>
                                                                    <li> <span><?php echo esc_attr($opt_meta_options['service_item_5']); ?></span></li>
                                                                <?php endif; ?>   
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>                                           
                                            </div>
                                        </header><!-- entry-header  --> 
                                                                                      
                                <?php
                                /* translators: %s: Name of current post */
                                the_content( sprintf(
                                    esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'acumec' ),
                                    the_title( '<span class="screen-reader-text">', '</span>', false )
                                ) );

                                wp_link_pages( array(
                                    'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'acumec' ) . '</span>',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                                ) );
                                ?>
                            </div>                  
                        </div> 
                    </article><!-- #post-## -->
                <?php acumec_post_nav(); ?>    
                <?php endwhile;?>
                
            </div>
        </div><!-- #main -->
         <?php  
          if($_get_sidebar != 'is-sidebar-full'):
            get_sidebar();
          endif; ?>
    </div>
</div><!-- #primary -->

<?php get_footer(); ?>