<?php
/**
 * The template for displaying Search Results pages
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */

/* get side-bar position. */
$_get_sidebar = acumec_archive_sidebar();

get_header(); ?>

<div id="primary" class="container">
    <div class="row <?php echo esc_attr($_get_sidebar); ?>">
        <div class="<?php acumec_archive_class(); ?>">
            <div id="main" class="site-main" >

            <?php if ( have_posts() ) :

                /* Start the Loop */
                while ( have_posts() ) : the_post();

                    get_template_part( 'single-templates/content/content','search' );

                endwhile;

                /* get paging_nav. */
                acumec_paging_nav();

            else :

                get_template_part( 'single-templates/search', 'not-found' );

            endif; ?>

            </div><!-- #content -->
        </div><!-- #primary -->

        <?php
        if($_get_sidebar != 'is-sidebar-full'):
            get_sidebar();
        endif; ?>

    </div>
</div>

<?php get_footer(); ?>