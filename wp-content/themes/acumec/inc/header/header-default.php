<?php
/**
 * @name : Default Header
 * @package : Spyropress
 * @author : Knight
 */
?>
<?php
$theme_options = acumec_get_theme_option();
$meta_options = acumec_get_meta_option();
$border = '';
?>

<?php acumec_header_top();?>
<?php  if( (!is_page() && !empty($theme_options['border_bottom']) && $theme_options['border_bottom'] == '1') || (is_page() && !empty($meta_options['header_layout']) && !empty($meta_options['border_bottom']) && $meta_options['border_bottom'] == '1') ) {
        $border = 'border-header';
    }
$container_class = 'container';
if( (!is_page() && !empty($theme_options['header_middle_full_width']) && $theme_options['header_middle_full_width'] == '1') || (is_page() && !empty($meta_options['header_layout']) && !empty($meta_options['header_middle_full_width']) && $meta_options['header_middle_full_width'] == '1'))
    $container_class = 'container-fullwidth';
?>
<?php if ( (!is_page() && !empty($theme_options['enable_header']) && $theme_options['enable_header'] =='1') || (is_page() && !empty($meta_options['enable_header']) && $meta_options['enable_header'] =='1') || (empty($theme_options['enable_header'])) ) : ?>

<div id="header_middle" class="header-middle layout1">
    <div class="<?php echo esc_attr($container_class);?>">
        <div class="header-middle-wrap clearfix">
            <div id="cshero-header-logo" class="site-branding header-main-center">
                <?php acumec_header_logo(); ?>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-navigation" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button> 
            </div> 
            <?php if ( is_active_sidebar( 'header-top-right-1' )  ) : ?>
            <div class="header-middle-right clearfix">
                <?php dynamic_sidebar('header-top-right-1'); ?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>

<div id="cshero-header" class="<?php acumec_header_class('header-main'); ?> <?php echo esc_html($border); ?> ">
    <div class="container">
        <div class="header-main-wrap">
            <div class="header-main-left-menu">
                <div id="cshero-header-navigation" class="header-navigation">
                    <nav id="site-navigation" class="collapse main-navigation">
                        <?php acumec_header_navigation(); ?>
                    </nav> 
                </div>
            </div>
            <?php if ( !empty($theme_options['enable_download']) && $theme_options['enable_download'] == '1' && !empty($theme_options['select_file_type'])) : ?>
                <?php if ($theme_options['select_file_type'] == '1' && !empty($theme_options['file_upload']['url']) ): ?>
                    <div class="header-main-right hidden-xs hidden-sm hidden-md">
                        <div class="header-main-right-wrap">
                            <a href="<?php echo esc_url($theme_options['file_upload']['url']); ?>"> <?php echo esc_attr($theme_options['download_title']); ?> </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($theme_options['select_file_type'] == '2' && !empty($theme_options['link_download'])): ?>
                        <div class="header-main-right hidden-xs hidden-sm hidden-md">
                            <div class="header-main-right-wrap">
                                <a href="<?php echo esc_url($theme_options['link_download']); ?>"> <?php echo esc_attr($theme_options['download_title']); ?> </a>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endif ?>  
            <?php endif ?>     
        </div> 
    </div>
</div>
<?php endif; ?>