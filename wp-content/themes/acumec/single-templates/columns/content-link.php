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
<article <?php post_class('acumec-blog'); ?>>
 	<div class="blog-outer">
 		<div class="blog-wrap">
 			<div class="row"> 
 				<div class="col-sm-12">
 					<div class="post-link">
					<?php
					 
					    $style ='';
					    if(has_post_thumbnail()){
					        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					        $image_url = esc_url($image[0]);
					        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
					    }
					?>
					    <div class="entry-wrap" <?php echo ''.$style;?>>
					        <div class="entry-inside">
					            <header class="entry-header">     
					                <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
					                <?php acumec_archive_detail1(); ?>
					            </header><!-- .entry-header -->
					            <div class="icon-link">
					                <span class="fa fa-link"></span>
					            </div>
					            <div class="archive-link">
					                <?php acumec_archive_link(); ?>
					            </div>  
					        </div> 
					    </div>	
 					</div>	
				</div>	
			</div>
 		</div> 		
 	</div>
</article><!-- #post-## -->