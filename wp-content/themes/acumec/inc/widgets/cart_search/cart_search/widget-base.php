<?php
extract(wp_parse_args($widget_args, array(
    'before_widget'            => '',
    'after_widget'             => '',
    'title'                    => '',
    'add_class'                => '',
    'show_search'              => 1,
    'search_bar_style'         => 'dropdown',
    'show_cart'                => 0,
    'show_cart_contents_count' => 0,
    'show_cart_subtotal'       => 0,
    'shop_cart_dropdown'       => '',
    'cart_contents_count'      => '',
    'cart_subtotal'            => ''

)));
?>
<?php echo wp_kses_post($before_widget); ?>

        <div class="widget widget_cart_search_wrap <?php esc_attr($add_class) ?>">
            
            <?php if(!empty($title)):?>
                <h3 class="wg-title"><?php echo esc_html($title); ?> </h3>
            <?php endif; ?>
            <div class="header-search-cart cshero-header-cart-search clearfix">
                <?php if ($show_search): ?>
                        <a href="javascript:void(0);" class="icon_search_wrap cd-search-trigger" data-display=".widget_searchform_content" data-no_display=".shopping_cart_dropdown"><i class="fa fa-search"></i></a> 
                <?php endif; ?>
                <?php if ($show_cart): ?>
                    <a href="javascript:void(0)" class="icon_cart_wrap" data-display=".shopping_cart_dropdown" data-no_display=".widget_searchform_content"><i class="icon icon-food2-bag-1"></i>
                        <?php if ($show_cart_contents_count): ?>
                            <span class="cart_total">
                            <?php echo esc_html($cart_contents_count); ?>
                        </span>
                        <?php endif ?>
                        <?php if ($show_cart_subtotal): ?>
                            <span class="cart_total_cost">
                                <?php echo acumec_html($cart_subtotal); ?>
                            </span>
                        <?php endif ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php if ($show_cart): ?>
               <?php echo acumec_html($shop_cart_dropdown); ?>
            <?php endif; ?>
            <?php if ($show_search): ?>
                <?php if ($search_bar_style == 'dropdown'): ?>
                    <div class="widget_searchform_content <?php echo esc_attr($search_bar_style );?>">
                        <div class="widget_cart_search_wrap_default">
                            <div class="cshero-dropdown-search">
                                <?php get_search_form();?>
                            </div> 
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($search_bar_style == 'popup'): ?>
                    <div class="widget_searchform_content <?php echo esc_attr($search_bar_style );?>">
                        <div class="cshero-popup-search">
                            <?php get_search_form();?>
                        </div> 
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
<?php echo wp_kses_post($after_widget); ?>