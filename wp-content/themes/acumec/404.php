<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
$theme_options = acumec_get_theme_option(); 
get_header(); ?>
     
<div id="primary" class="container ">           
    <div id="main" class="site-main text-center">
        <div class="error-404 not-found">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php if (!empty($theme_options['404_image'])): ?>
                        <img src="<?php echo esc_url($theme_options['404_image']['url']) ?>"/>
                    <?php endif ?>
                    <h1 class="page-title-404"><?php echo !empty($theme_options['page_404_title'])? $theme_options['page_404_title'] : esc_html__( 'OH, CRAP', 'acumec' ); ?></h1>
                    <p ><?php echo !empty($theme_options['page_404_message'])? $theme_options['page_404_message'] : esc_html__( 'We Can\'t Seem to Find the Page
You\'re Looking for.', 'acumec' ); ?></p>
                    <?php if(isset($theme_options['page_404_button']) && $theme_options['page_404_button']=='1'): ?>
                        <div class="btn-action">
                            <a href="<?php echo esc_attr($theme_options['link_404_button']); ?>" class="btn-404 btn btn-theme-primary btn-round" style=""><?php echo esc_html__( 'VIEW ALL PROJECT', 'acumec' )?></a>
                        </div>  
                    <?php endif; ?>
                    <?php if(isset($theme_options['error_search']) && $theme_options['error_search']=='1'): ?>               
                            <div class="error-search">
                                <?php get_search_form(); ?>
                            </div>    
                    <?php endif; ?>
                </div> 
            </div>  
        </div><!-- .error-404 -->
    </div><!-- .site-main -->           
</div><!-- .content-area -->
    
<?php get_footer(); ?>