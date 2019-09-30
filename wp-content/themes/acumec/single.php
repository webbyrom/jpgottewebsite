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
get_header(); ?>
<div id="primary" class="container ">
    <div class="row row-single <?php echo esc_attr($_get_sidebar); ?>">
        <div class="<?php acumec_post_class(); ?>">
            <div id="main" class="site-main" >

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    // Include the single content template.
                    get_template_part( 'single-templates/single/content', get_post_format() );
                    // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                    // Get single post nav.
                    acumec_post_nav();
                    // End the loop.
                endwhile;
                ?>
                
            </div>
        </div><!-- #main -->
        
        <?php  
          if($_get_sidebar != 'is-sidebar-full'):
            get_sidebar();
          endif; ?>

    </div>
</div><!-- #primary -->

<?php get_footer(); ?>