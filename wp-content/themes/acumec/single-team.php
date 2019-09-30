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
            <div id="main" class="site-main single-team" >
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    // Include the single content template.
                     global $opt_theme_options, $opt_meta_options; ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-post-wrap ">
                            <div class="entry-text">         
                                <header class="entry-header row">
                                    <?php if ( has_post_thumbnail()): ?>
                                        <div class="header-left col-lg-5 col-md-6 col-sm-12">
                                            <div class="post-thumbnail">
                                                <?php echo acumec_get_image_croped(get_post_thumbnail_id(get_the_ID()),'370x440'); ?> 
                                            </div> 
                                        </div>
                                    <?php endif; ?>
                                    <div class="header-right col-lg-7 col-md-6 col-sm-12">
                                        <div class="header-right-wrap">
                                            <?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
                                                
                                                <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
                                            <?php endif; ?> 
                                            <?php if (!empty($opt_meta_options['team_position']) ): ?>
                                                <div class="team-position">
                                                    <?php echo esc_attr( $opt_meta_options['team_position'] ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($opt_meta_options['team_description']) ): ?>
                                                <div class="team-description">
                                                    <?php echo esc_attr( $opt_meta_options['team_description'] ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <ul class="team-contact">
                                                <?php if (!empty($opt_meta_options['team_email'])): ?>
                                                    <li><span class="title"><?php echo esc_html__('EMAIL ADDRESS: ','acumec') ?></span><span><?php echo esc_attr( $opt_meta_options['team_email'] ); ?></span></li>
                                                <?php endif; ?>
                                                <?php if (!empty($opt_meta_options['team_phone'])): ?>
                                                    <li><span class="title"><?php echo esc_html__('PHONE NO: ','acumec') ?></span><span><?php echo esc_attr( $opt_meta_options['team_phone'] ); ?></span></li>
                                                <?php endif; ?>
                                                <?php if (!empty($opt_meta_options['team_location'])): ?>
                                                    <li><span class="title"><?php echo esc_html__('LOCATION: ','acumec') ?></span><span><?php echo esc_attr( $opt_meta_options['team_location'] ); ?></span></li>
                                                <?php endif; ?>
                                            </ul>
                                            <div class="team-link">
                                                <?php if (!empty($opt_meta_options['team_link']) && !empty($opt_meta_options['team_link_title'])): ?>
                                                   <div class="team-btn">
                                                        <a class="team-button btn btn-theme-primary btn-round" href="<?php echo esc_attr($opt_meta_options['team_link']); ?>" title="<?php echo esc_attr($opt_meta_options['team_link_title']); ?>"><?php if (!empty($opt_meta_options['team_link_title'])): ?><i class="<?php echo esc_attr($opt_meta_options['team_link_icon']); ?>"></i><?php endif; ?><?php echo esc_attr($opt_meta_options['team_link_title']); ?></a>
                                                    </div> 
                                                <?php endif; ?>
                                                <?php if (!empty($opt_meta_options['social_enable']) && $opt_meta_options['social_enable'] == 1 ): ?>
                                                    <ul class="team-social">
                                                        <?php if (!empty($opt_meta_options['team_social_icon_1']) ): ?>
                                                            <li><?php if (!empty($opt_meta_options['team_social_1']) ): ?>
                                                                <a href="<?php echo esc_attr($opt_meta_options['team_social_1']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_1']); ?>"></i></a> <?php else: ?>
                                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_1']); ?>"></i>
                                                                <?php endif; ?> 
                                                            </li>
                                                            <li><?php if (!empty($opt_meta_options['team_social_2']) ): ?>
                                                                <a href="<?php echo esc_attr($opt_meta_options['team_social_2']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_2']); ?>"></i></a> <?php else: ?>
                                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_2']); ?>"></i>
                                                                <?php endif; ?> 
                                                            </li>
                                                            <li><?php if (!empty($opt_meta_options['team_social_3']) ): ?>
                                                                <a href="<?php echo esc_attr($opt_meta_options['team_social_3']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_3']); ?>"></i></a> <?php else: ?>
                                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_3']); ?>"></i>
                                                                <?php endif; ?> 
                                                            </li>
                                                            <li><?php if (!empty($opt_meta_options['team_social_4']) ): ?>
                                                                <a href="<?php echo esc_attr($opt_meta_options['team_social_4']); ?>"><i class="<?php echo esc_attr($opt_meta_options['team_social_icon_4']); ?>"></i></a> <?php else: ?>
                                                                <i class="<?php echo esc_attr($opt_meta_options['team_social_icon_4']); ?>"></i>
                                                                <?php endif; ?> 
                                                            </li>
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