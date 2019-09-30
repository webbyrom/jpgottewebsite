<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
?>
<?php  global $opt_theme_options; ?>
<div  <?php post_class(); ?>>
	<div class="entry-content">

			<?php the_content(); ?>
			<?php 
				wp_link_pages( array(
				'before'      => '<div class="page-links clearfix"><span class="page-links-title">' . esc_html__( 'Pages:', 'acumec' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				) ); 
			?>

	</div><!-- .entry-content -->

	<?php if(!empty( $opt_theme_options['page_show_frontend_editor']) && $opt_theme_options['page_show_frontend_editor'] == '1'):?>
		<footer class="entry-meta">

				<?php edit_post_link( esc_html__( 'Edit Page', 'acumec' ), '<span class="edit-link">', '</span>' ); ?>

		</footer><!-- .entry-meta -->
	<?php endif; ?>
</div><!-- #post -->
