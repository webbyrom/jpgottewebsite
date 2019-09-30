<?php
extract(wp_parse_args($dropdown_attrs, array(
    'is_cart_empty' => 0,
    'list_class'    => '',
    'products'      => array(),
    'cart_subtotal' => '',
    'cart_url'      => '',
    'checkout_url'  => ''
)));
?>
<div class="shopping_cart_dropdown">
    <div class="shopping_cart_dropdown_inner">
        <ul class="cart_list product_list_widget">
            <?php if (!$is_cart_empty) : ?>
                <?php foreach ($products as $product) :
                    extract(wp_parse_args($product, array(
                        'id'          => '',
                        'sku'         => '',
                        'permalink'   => '',
                        'image'       => '',
                        'title'       => '',
                        'data'        => '',
                        'quantity'    => '',
                        'remove_link' => '',
                        'remove_id'   => ''

                    )));
                    ?>
                    <li class="<?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item')); ?>">
                        <?php
                        echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                            '<a href="%s" class="remove" title="%s" data-remove_id="%s" data-product_sku="%s"><i class="fa fa-close"></i></a>',
                            $remove_link,
                            esc_html__('Remove this item', 'acumec'),
                            $remove_id,
                            $sku
                        ));
                        ?>
                        <div class="item-wrap">
                            <div class="mini-cart-media">
                                <a class="img-left" href="<?php echo esc_url($permalink); ?>">
                                    <?php echo esc_html($image); ?>
                                </a>
                            </div>
                            <div class="product-desc">
                                <h5><?php echo esc_html($title); ?></h5>
                                <?php echo acumec_html($data); ?>
                                <?php echo acumec_html($quantity); ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li class="cart-list clearfix"><?php esc_html_e('No products in the cart.', 'acumec'); ?></li>
            <?php endif; ?>
        </ul>
    </div>
    <p class="total">
        <span class="total"><?php esc_html_e('Total: ', 'acumec'); ?>
            <span><?php echo acumec_html($cart_subtotal); ?></span></span>
    </p>
    <p class="button">
        <a href="<?php echo esc_url($cart_url) ?>"
           class="btn btn-theme-primary "><?php esc_html_e('Cart', 'acumec'); ?></a>
        <?php if (!$is_cart_empty): ?>
            <a href="<?php echo esc_url($checkout_url) ?>"
               class="btn btn-theme-primary "><?php esc_html_e('Checkout', 'acumec'); ?></a>
        <?php endif; ?>
    </p>


</div>