<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @since 1.0.0
 */
?>
<?php global $opt_theme_options; ?>
<?php $category = get_the_terms( get_the_ID(), 'category', '', ', ' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-post-wrap ">
		<div class="entry-text">
			<?php acumec_post_detail_before(); ?>
			<div class="post-video">
				<?php acumec_post_audio();  ?>
			</div>
			<header class="entry-header">
			<?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
			<?php endif; ?>	
				<?php acumec_post_detail_after(); ?>
			</header><!-- entry-header  -->	
				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'acumec' ),
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
			<?php acumec_post_sharing(); ?>
			<?php acumec_post_tag(); ?>
	</div> 
		<?php  acumec_post_author(); ?>
		<?php if (!empty($opt_theme_options['single_related']) && $opt_theme_options['single_related'] == 1): ?>
			<?php acumec_post_related($category);?>
    	<?php endif ?>
</article><!-- #post-## -->
