<?php
$theme_options = acumec_get_theme_option();
$meta_options = acumec_get_meta_option();
$container_class = 'container';
if( !empty($theme_options['header_top_full_width']) && $theme_options['header_top_full_width'] == '1')
    $container_class = 'container-fullwidth';
?>
<div id="header_top" class="header-top layout2">
    <div class="<?php echo esc_attr($container_class);?>">
        <div class="header-top-wrap clearfix">

            <?php if ( is_active_sidebar( 'header-top-left' )  ) : ?>
            <div class="header-top-left"> 
                <?php dynamic_sidebar('header-top-left'); ?>
            </div>
            <?php endif;?>
            <?php if ( is_active_sidebar( 'header-top-center' )  ) : ?>
            <div class="header-top-center clearfix">
                <?php dynamic_sidebar('header-top-center'); ?>
            </div>
            <?php endif;?>
            <?php if ( is_active_sidebar( 'header-top-right' )  ) : ?>
            <div class="header-top-right clearfix">
                <?php dynamic_sidebar('header-top-right'); ?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>