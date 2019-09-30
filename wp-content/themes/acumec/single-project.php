<?php
/**
 * The Template for displaying all single posts
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */

/* get side-bar position. */
$_get_sidebar = acumec_post_sidebar();
global $opt_meta_options;
get_header(); ?>
<div id="primary" class="container ">
    <div class="row row-single <?php echo esc_attr($_get_sidebar); ?>">
        <div class="<?php acumec_post_class(); ?>">
            <div id="main" class="site-main single-project" >
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    // Include the single content template.
                    global $opt_theme_options; ?>
                    <?php $category = get_the_terms( get_the_ID(), 'project_category', '', ', ' ); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="project-header">
                            <div class="project-header-left">
                                <div class="project-cate">
                                    <?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ', '' );?>
                                </div>
                                <?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
                                    <header class="entry-header">
                                        <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
                                    </header><!-- .entry-header --> 
                                <?php endif; ?> 
                            </div>
                            <?php if (!empty($opt_meta_options['project_link_launch']) ): ?>
                                <div class="project-header-right">
                                    <a class="btn-launch btn btn-theme-primary btn-md" href="<?php echo esc_attr($opt_meta_options['project_link_launch']); ?>" ><?php echo esc_attr($opt_meta_options['project_link_title']); ?></a>
                                </div>
                            <?php endif ?>                                                    
                        </div>
                        <div class="single-content">
                             
                            <div class="entry-post-wrap ">
                                <?php if (!empty($opt_meta_options['project_description'])): ?>
                                    <div class="project-description">
                                       <?php echo esc_attr($opt_meta_options['project_description']); ?>                    
                                    </div> 
                                <?php endif ?>  
                                <?php if ( has_post_thumbnail()): ?>
                                    <div class="post-thumbnail">
                                        <?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()),'1170x485'); ?>      
                                    </div>
                                <?php endif; ?>
                                <div class="client-information">
                                    <div class="client-information-wrap">
                                        <div class="client-information-box">
                                            <?php if (!empty($opt_meta_options['project_client_title'])): ?>
                                                <h4 class="project-title"><?php echo esc_attr($opt_meta_options['project_client_title']); ?></h4>
                                            <?php endif ?>
                                            <div class="information-wrap">
                                                <?php if (!empty($opt_meta_options['project_client_image']['url']) || !empty($opt_meta_options['project_client_name'])): ?>
                                                    <div class="information-item">
                                                        <div class="client">
                                                            <?php if (!empty($opt_meta_options['project_client_image']['url']) ): ?>
                                                                <div class="client-thumbnail">
                                                                     <?php 
                                                                     echo acumec_get_image_croped($opt_meta_options['project_client_image']['id'],'thumbnail'); ?>
                                                                    
                                                                </div>
                                                            <?php endif ?>
                                                            <?php if (!empty($opt_meta_options['project_client_name']) ): ?>
                                                                <div class="client-content">
                                                                    <h5 class="client-title"><?php echo esc_attr($opt_meta_options['project_client_name']); ?></h5>
                                                                    <?php if (!empty($opt_meta_options['project_client_position']) ): ?>
                                                                        <div class="client-position">
                                                                            <?php echo esc_attr($opt_meta_options['project_client_position']); ?>
                                                                        </div>
                                                                    <?php endif; ?> 
                                                                </div>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                    <div class="information-item">
                                                        <?php if (!empty($opt_meta_options['project_link_demo']) ): ?>
                                                            <div class="information-item-wrap">
                                                                <span><?php echo esc_html__('Live Demo: ','acumec'); ?></span><span><a href="<?php echo esc_attr($opt_meta_options['project_link_demo']); ?>" ><?php echo esc_attr($opt_meta_options['project_link_demo']); ?></a></span>
                                                            </div>
                                                        <?php endif ?>  
                                                        <?php if (!empty($opt_meta_options['project_start_date']) ): ?>
                                                            <div class="information-item-wrap">
                                                                <span><?php echo esc_html__('Start Date: ','acumec'); ?></span><span><?php echo esc_attr($opt_meta_options['project_start_date']); ?></span>
                                                            </div>
                                                        <?php endif ?>  
                                                    </div>
                                                    <div class="information-item">
                                                        <?php if (!empty($opt_meta_options['project_category']) ): ?>
                                                            <div class="information-item-wrap">
                                                                <span><?php echo esc_html__('Category: ','acumec'); ?></span><span><?php echo esc_attr($opt_meta_options['project_category']); ?></span>
                                                            </div>
                                                        <?php endif ?>  
                                                        <?php if (!empty($opt_meta_options['project_end_date']) ): ?>
                                                            <div class="information-item-wrap">
                                                                <span><?php echo esc_html__('End Date: ','acumec'); ?></span><span><?php echo esc_attr($opt_meta_options['project_end_date']); ?></span>
                                                            </div>
                                                        <?php endif ?>  
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>      
                                    </div>           
                                </div>
                                <div class="entry-text">
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
                        </div>
                    </article><!-- #post-## -->
                    <?php acumec_post_nav(); ?>
                <?php endwhile; ?>              
            </div>
        </div>
        <?php  
            if($_get_sidebar != 'is-sidebar-full'):
                get_sidebar();
            endif; 
        ?>
    </div>
    
</div>            

<?php get_footer(); ?>