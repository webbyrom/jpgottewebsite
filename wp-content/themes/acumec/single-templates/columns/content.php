<?php
/**
 * The template for displaying content
 *
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0 
 */
?>
<?php global $opt_theme_options; ?>
<?php $word_number = '20';
	if (!empty($opt_theme_options['word_number'])) {
		$word_number = $opt_theme_options['word_number'];
	}
 ?>
<article <?php post_class('acumec-blog'); ?>>
 	<div class="blog-outer">	
 		<div class="blog-wrap blog-column">
			<div class="blog-content">
				<?php acumec_post_archive_before(); ?>
				<?php if ( has_post_thumbnail()): ?>
					<div class="post-thumbnail">
						<a href="#"><?php acumec_post_thumbnail('medium'); ?></a>		
					</div>
				<?php endif; ?>	
				<header class="entry-header">
					<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>		
					
				</header><!-- entry-header  -->	
				<div class="entry-content">
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
					<?php acumec_post_archive_after(); ?>
				</div>					
			</div><!-- .entry-content -->	
 		</div> 		
 	</div>
</article><!-- #post-## -->
