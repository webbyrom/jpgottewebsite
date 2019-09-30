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
<?php  global $opt_theme_options, $opt_meta_options;
$word = !empty($opt_theme_options['word_number']) ? $opt_theme_options['word_number'] : '20';?>
<article id="post-<?php the_ID(); ?>" <?php post_class('acumec-blog wow fadeInUp'); ?>>
    <header class="entry-header">
        <?php if(is_sticky())
            echo '<span class="post-sticky"><span class="pe-7s-pin"></span></span>';
        ?>
        <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
        <div class="entry-meta">
            <?php acumec_archive_detail1(); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->
    <div class="entry-content">
      <p>
        <?php
        /* translators: %s: Name of current post */
        echo acumec_grid_limit_words(strip_tags(get_the_excerpt()),$word);
        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages', 'acumec' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
        ) );
        ?>
      </p>
    </div><!-- .entry-content -->

    <?php acumec_archive_author(); ?>
    <?php acumec_archive_readmore(); ?>
</article><!-- #post-## -->
