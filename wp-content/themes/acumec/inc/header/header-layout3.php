<?php
/**
 * @name : Header
 * @package : Spyropress
 * @author : Knight
 */
?>
<?php 
$theme_options = acumec_get_theme_option();
$meta_options = acumec_get_meta_option();
$border ='';
$fullwidth = 'container';
 ?>
 <?php  
if( (!is_page() && !empty($theme_options['border_bottom']) && $theme_options['border_bottom'] == '1') || (is_page() && !empty($meta_options['header_layout']) && !empty($meta_options['border_bottom']) && $meta_options['border_bottom'] == '1') ) {
        $border = 'border-header';
    }
 if ((!is_page() && !empty($theme_options['menu_fullwidth']) && $theme_options['menu_fullwidth'] == '1') || (is_page() && !empty($meta_options['header_layout']) && !empty($meta_options['menu_fullwidth']) && $meta_options['menu_fullwidth'] == '1')) {
        $fullwidth = 'container-fullwidth';
    }
 $border_color = !empty($theme_options['border_color']) ? 'border-color: '.$theme_options['border_color'].';' : '' ;
    
 ?>
 <?php acumec_header_top();?>
 <?php if ( (!is_page() && !empty($theme_options['enable_header']) && $theme_options['enable_header'] =='1') || (is_page() && !empty($meta_options['enable_header']) && $meta_options['enable_header'] =='1') ): ?>
 
<div id="cshero-header" class="<?php acumec_header_class('header-main'); ?> <?php echo esc_html($border); ?>" style ="<?php echo esc_attr($border_color) ?>">
    <div class="<?php echo esc_attr($fullwidth); ?>">
        <div class="header-main-wrap">
            <div id="cshero-header-logo" class="site-branding header-main-center">
                <?php acumec_header_logo2(); ?>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-navigation" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button> 
            </div>                    
            <div class="header-main-right-menu">
                <div id="cshero-header-navigation" class="header-navigation">
                    <nav id="site-navigation" class="collapse main-navigation">
                        <?php acumec_header_navigation(); ?>
                    </nav> 
                </div>
            </div>
        </div> 
    </div>
</div>
<?php endif; ?>
