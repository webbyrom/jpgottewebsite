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
<article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog wow fadeInUp'); ?>>
 	<div class="blog-outer">
 		<div class="blog-wrap">
 			<div class="row"> 
 				<div class="col-sm-12">
 					<div class="post-link">
 						<?php acumec_post_link_archive(); ?>	
 					</div>	
				</div>	
			</div>
 		</div> 		
 	</div>
</article><!-- #post-## -->

