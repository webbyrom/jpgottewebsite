<?php
/**
 * The template for displaying Author Archive pages
 *
 * Used to display archive-type pages for posts by an author.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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

				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();

						get_template_part( 'single-templates/content/content', get_post_format() );

					endwhile; // end of the loop.

					/* blog nav. */
					acumec_paging_nav();

				else :
					/* content none. */
					get_template_part( 'single-templates/content', 'none' );

				endif; ?>

			</div><!-- #content -->
		</div>

		<?php
		if($_get_sidebar != 'is-sidebar-full'):
			get_sidebar();
		endif; ?>

	</div>
</div><!-- #primary -->

<?php get_footer(); ?>