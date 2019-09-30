<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
?>
    </div><!-- .site-content -->
    <?php
    global $opt_theme_options;
    $theme_options = acumec_get_theme_option(); 
    $meta_options = acumec_get_meta_option();
    $style = '';
    ?>  
    <?php if (!empty($theme_options['enable_footer']) && $theme_options['enable_footer'] =='1'): ?>
        <?php if (!is_page() || ( is_page() && !empty($meta_options['enable_footer']) && $meta_options['enable_footer'] =='1') ): ?>
            <?php  $style = $theme_options['footer-style'];
                if (!empty($meta_options['footer-style'])) {
                    $style = $meta_options['footer-style'];
                } ?>
                <footer id="footer" class="site-footer <?php echo esc_attr($style); ?>">
                    <?php acumec_client_logo_footer(); ?> 
                <?php 
                    if ($style == 'layout1'){
                        acumec_footer_top();
                    }else {
                         acumec_footer_top1();
                    }                 
                    if ((!empty($theme_options['enable_footer_bottom']) && $theme_options['enable_footer_bottom'] == '1') || (is_page() && !empty($meta_options['enable_footer_bottom']) && $meta_options['enable_footer_bottom'] == '1')): ?> 
                            <div id="footer-bottom"  class="footer-bottom <?php acumec_footer_align(); ?> ">
                                <div class="container">
                                    <div class="row footer-row">
                                    <?php
                                        if(is_active_sidebar( 'sidebar-footer-bottom-left' ) ){  
                                        ?> 
                                        <div class= "footer-bottom-left col-lg-6 col-md-6 col-sm-12 col-xs-12">         
                                            <?php dynamic_sidebar( 'sidebar-footer-bottom-left' ); ?>                                        
                                        </div>
                                    <?php } ?>  
                                     <?php
                                        if(is_active_sidebar( 'sidebar-footer-bottom-right' ) ){  
                                        ?> 
                                        <div class= "footer-bottom-right col-lg-6 col-md-6 col-sm-12 col-xs-12">         
                                            <?php dynamic_sidebar( 'sidebar-footer-bottom-right' ); ?>                                        
                                        </div>
                                    <?php } ?>                   
                                    </div>
                                </div>
                            </div><!-- #footer-bottom -->
                    <?php endif; ?>
                        
                </footer><!-- .site-footer -->
        <?php endif; ?>
    <?php endif; ?>
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>