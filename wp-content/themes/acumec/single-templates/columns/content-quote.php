<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
?>
<?php  global $opt_theme_options, $opt_meta_options; ?>
<?php $word_number = '20';
	if (!empty($opt_theme_options['word_number'])) {
		$word_number = $opt_theme_options['word_number'];
	}
 ?>
<article <?php post_class('acumec-blog'); ?>>
 	<div class="blog-outer">
 		<div class="blog-wrap">
 			<div class="row">
 				<div class="col-sm-12">
	 			<?php   if(empty($opt_meta_options['opt-quote-content'])) {
	 				?>
	 				<?php acumec_post_archive_before(); ?>
						<?php if ( has_post_thumbnail()): ?>
							<div class="post-thumbnail">
								<a href="#"><?php acumec_post_thumbnail('medium'); ?></a>		
							</div>
						<?php endif; ?>	
						<div class="blog-content">
							<header class="entry-header">
								<?php if(is_sticky())
						            echo '<span class="post-sticky"><span class="pe-7s-pin"></span></span>';
						        ?>

								<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
							</header><!-- .entry-header -->
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
						</div><!-- .entry-content -->	
	 				<?php
	 				} else{ ?>
 				<?php  
					$style =''; 
				    if(has_post_thumbnail()){
				        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				        $image_url = esc_url($image[0]);
				        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
				    }

				    if(empty($opt_meta_options['opt-quote-content'])){
				        acumec_post_thumbnail();
				        return;
				    }

				    $opt_meta_options['opt-quote-title'] = !empty($opt_meta_options['opt-quote-title']) ? esc_html($opt_meta_options['opt-quote-title']) : '' ;
				    $quote_sub_title = !empty($opt_meta_options['opt-quote-sub-title']) ? esc_html($opt_meta_options['opt-quote-sub-title']) : '' ;
					?>
					<div class="post-quote">
					    <div class="entry-wrap" <?php echo ''.$style;?>>
					        <div class="entry-inside">
					        	<div class="entry-header">
					                <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
					            </div>
					            <?php  
					                echo '<blockquote class ="quote-meta">'.'<p class = "quote-content">'.esc_html($opt_meta_options['opt-quote-content']).'</p><p class="quote-title">'.esc_html($opt_meta_options['opt-quote-title']).'</p><p class="quote-subtitle">'.wp_kses_post($quote_sub_title).'</p></blockquote>'; 
					            ?>
					        </div>
					    </div>
					</div>
				<?php } ?>
				</div>
			</div>
 		</div> 		
 	</div>
</article><!-- #post-## -->