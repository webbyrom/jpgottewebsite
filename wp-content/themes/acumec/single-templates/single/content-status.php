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
<?php
 
$style ='';
if(has_post_thumbnail()){
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
    $image_url = esc_url($image[0]);
    $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-post-wrap post-status ">
		<div class="entry-text">
			<div class="entry-wrap" <?php echo ''.$style;?>>
				<header class="entry-header">
			        <?php acumec_post_status(); ?>
					<?php if ( (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout']  == 7 ) || ( (empty($opt_theme_options['single_page_title_layout']) || (!empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] < 2 ) )&& $opt_theme_options['page_title_layout'] == 7)  ): ?>
						<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
					<?php endif; ?>	

				</header><!-- .entry-header -->

		    </div>
		    <?php  acumec_post_detail();?>
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
