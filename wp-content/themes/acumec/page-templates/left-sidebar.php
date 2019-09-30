<?php
/**
 * Template Name: Left Sidebar
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 * @author Fox
 */

get_header(); ?>

<div id="primary" class="container">
    <div class="row sidebar-left">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <div id="main" class="site-main" >

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'single-templates/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    // End the loop.
                endwhile;
                ?>

            </div><!-- .site-main -->
        </div>
        <?php if ( is_active_sidebar( 'sidebar-page' ) ) : 
             echo '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 wow fadeInUp">';
                echo '<div  id="widget-area" class="widget-area" role="complementary">';
                    dynamic_sidebar( 'sidebar-page' ); 
                echo '</div>';
            echo '</div>';?>
        <?php endif; ?>
    </div>
</div><!-- .content-area -->

<?php get_footer(); ?>
