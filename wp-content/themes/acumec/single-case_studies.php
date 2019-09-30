<?php
/**
 * The Template for displaying all single posts
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */

/* get side-bar position. */
global $opt_theme_options, $opt_meta_options; 
$_get_sidebar = acumec_post_sidebar();
get_header(); ?>
<div id="primary" class="container ">
    <div class="row row-single <?php echo esc_attr($_get_sidebar); ?>">
        <div class="<?php acumec_post_class(); ?>">
            <div id="main" class="site-main" >

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    // Include the single content template.
                     
                     $category = get_the_terms( get_the_ID(), 'case_studies_category', '', ', ' ); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-post-wrap ">
                            <div class="entry-text">
                                <header class="entry-header">
                                    <?php if (!empty($opt_meta_options['case_subttile'])): ?>
                                        <div class="case-subtitle">
                                            <?php echo esc_attr($opt_meta_options['case_subttile']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
                                        <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
                                    <?php endif; ?> 
                                        <?php if(taxonomy_exists('case_studies_category') && (!isset($opt_theme_options['single_categories']) || (isset($opt_theme_options['single_categories']) && $opt_theme_options['single_categories']))): ?>
                                            <ul class="archive_detail">
                                                <li class="detail-terms"><span class="fa fa-tag"></span> <?php echo get_the_term_list( get_the_ID(), 'case_studies_category', '', ', ', '' ); ?></li>
                                            </ul>   
                                        <?php endif; ?>
                                </header><!-- entry-header  --> 
                                <?php if ( has_post_thumbnail()): ?>
                                    <div class="post-thumbnail">
                                        <?php acumec_post_thumbnail(); ?>     
                                    </div>
                                <?php endif ?>                  
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