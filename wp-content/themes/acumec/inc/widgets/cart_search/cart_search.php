<?php
if (!function_exists('register_ef4_widget')) return;

class acumec_Widget_Cart_Search extends WP_Widget
{
    protected $settings;

    public function __construct()
    {
        extract(array(
            'id_base'        => 'acumec_widget_cart_search',
            'name'           => esc_html__('Cart & Search', 'acumec'),
            'widget_options' => array(
                'description' => esc_html__('Display the user\'s Cart and Search form in the sidebar.', 'acumec')
            )
        ));
        parent::__construct($id_base, $name, $widget_options);
        //
        $this->settings = array(
            'title'                    => array(
                'type'  => 'text',
                'std'   => esc_html__('Cart & Search', 'acumec'),
                'label' => esc_html__('Title', 'acumec')
            ),
            'show_cart'                => array(
                'type'  => 'checkbox',
                'std'   => 1,
                'label' => esc_html__('Show Cart', 'acumec'),
            ),
            'show_cart_contents_count' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__('Show Count', 'acumec'),
            ),
            'show_cart_subtotal'       => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__('Show Subtotal', 'acumec'),
            ),
            'show_search'              => array(
                'type'  => 'checkbox',
                'std'   => 1,
                'label' => esc_html__('Show Search', 'acumec'),
            ),
            'search_bar_style'         => array(
                'type'    => 'select',
                'std'     => 'dropdown',
                'options' => array(
                    'dropdown' => esc_html__('Dropdown', 'acumec'),
                    'popup'    => esc_html__('Popup', 'acumec')
                ),
                'label'   => esc_html__('Search Bar Style', 'acumec'),
            ),
            'add_class'                => array(
                'type'  => 'text',
                'std'   => '',
                'label' => esc_html__('Add Class', 'acumec')
            )
        );
        add_action('wp_enqueue_scripts', array($this, 'widget_scripts'));
        $this->widget_filter();
    }

    function widget_scripts()
    {
        wp_enqueue_script('wpacumec-widget_cart_search_scripts', get_template_directory_uri() . '/inc/widgets/cart_search/cart_search.js', array('jquery'), '1.0.1', true);
        wp_enqueue_style('wpacumec-widget_cart_search_scripts', get_template_directory_uri() . '/inc/widgets/cart_search/cart_search.css');
    }

    function widget_filter()
    {
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'woocommerce_header_add_to_cart_fragment'));
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'woocommerce_header_add_to_cart_content'));
    }

    public function update($new_instance, $old_instance)
    {

        $instance = $old_instance;

        if (empty($this->settings)) {
            return $instance;
        }

        // Loop settings and get values to save.
        foreach ($this->settings as $key => $setting) {
            if (!isset($setting['type'])) {
                continue;
            }

            // Format the value based on settings type.
            switch ($setting['type']) {
                case 'number' :
                    $instance[$key] = absint($new_instance[$key]);

                    if (isset($setting['min']) && '' !== $setting['min']) {
                        $instance[$key] = max($instance[$key], $setting['min']);
                    }

                    if (isset($setting['max']) && '' !== $setting['max']) {
                        $instance[$key] = min($instance[$key], $setting['max']);
                    }
                    break;
                case 'textarea' :
                    $instance[$key] = wp_kses(trim(wp_unslash($new_instance[$key])), wp_kses_allowed_html('post'));
                    break;
                case 'checkbox' :
                    $instance[$key] = empty($new_instance[$key]) ? 0 : 1;
                    break;
                default:
                    $instance[$key] = sanitize_text_field($new_instance[$key]);
                    break;
            }
        }

        return $instance;
    }

    public function form($instance)
    {

        if (empty($this->settings)) {
            return;
        }

        foreach ($this->settings as $key => $setting) {

            $class = isset($setting['class']) ? $setting['class'] : '';
            $value = isset($instance[$key]) ? $instance[$key] : $setting['std'];

            switch ($setting['type']) {

                case 'text' :
                    ?>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($setting['label']); ?></label>
                        <input class="widefat <?php echo esc_attr($class); ?>"
                               id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                               name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="text"
                               value="<?php echo esc_attr($value); ?>"/>
                    </p>
                    <?php
                    break;

                case 'number' :
                    ?>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($setting['label']); ?></label>
                        <input class="widefat <?php echo esc_attr($class); ?>"
                               id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                               name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="number"
                               step="<?php echo esc_attr($setting['step']); ?>"
                               min="<?php echo esc_attr($setting['min']); ?>"
                               max="<?php echo esc_attr($setting['max']); ?>" value="<?php echo esc_attr($value); ?>"/>
                    </p>
                    <?php
                    break;

                case 'select' :
                    ?>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($setting['label']); ?></label>
                        <select class="widefat <?php echo esc_attr($class); ?>"
                                id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                                name="<?php echo esc_attr($this->get_field_name($key)); ?>">
                            <?php foreach ($setting['options'] as $option_key => $option_value) : ?>
                                <option value="<?php echo esc_attr($option_key); ?>" <?php selected($option_key, $value); ?>><?php echo esc_html($option_value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <?php
                    break;

                case 'textarea' :
                    ?>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($setting['label']); ?></label>
                        <textarea class="widefat <?php echo esc_attr($class); ?>"
                                  id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                                  name="<?php echo esc_attr($this->get_field_name($key)); ?>" cols="20"
                                  rows="3"><?php echo esc_textarea($value); ?></textarea>
                        <?php if (isset($setting['desc'])) : ?>
                            <small><?php echo esc_html($setting['desc']); ?></small>
                        <?php endif; ?>
                    </p>
                    <?php
                    break;

                case 'checkbox' :
                    ?>
                    <p>
                        <input class="checkbox <?php echo esc_attr($class); ?>"
                               id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                               name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="checkbox"
                               value="1" <?php checked($value, 1); ?> />
                        <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($setting['label']); ?></label>
                    </p>
                    <?php
                    break;

                // Default: run an action
                default :
                    do_action('woocommerce_widget_field_' . $setting['type'], $key, $value, $setting, $instance);
                    break;
            }
        }
    }

    public function widget($args, $instance)
    {
        extract(shortcode_atts($instance, $args));
        //if ( is_cart() || is_checkout() ) return;
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $hide_if_empty = empty($instance['hide_if_empty']) ? 0 : 1;
        $before_title = isset($before_title) ? $before_title : '';
        $after_title = isset($after_title) ? $after_title : '';
        $woocommerce = function_exists('WC') ? WC() : 0;
        $wc_fragment = apply_filters('woocommerce_add_to_cart_fragments', array());
        $widget_args = array(
            'before_widget'            => isset($before_widget) ? $before_widget : '',
            'after_widget'             => isset($after_widget) ? $after_widget : '',
            'title'                    => !empty($title) ? $before_title . $title . $after_title : '',
            'add_class'                => isset($instance['add_class']) ? $instance['add_class'] : '',
            'show_search'              => isset($instance['show_search']) ? $instance['show_search'] : 1,
            'search_bar_style'         => isset($instance['search_bar_style']) ? $instance['search_bar_style'] : 'popup',
            'show_cart'                => ($woocommerce && isset($instance['show_cart'])) ? $instance['show_cart'] : 0,
            'show_cart_contents_count' => isset($instance['show_cart_contents_count']) ? $instance['show_cart_contents_count'] : 0,
            'show_cart_subtotal'       => isset($instance['show_cart_subtotal']) ? $instance['show_cart_subtotal'] : 0,
            'shop_cart_dropdown'       => isset($wc_fragment['div.shopping_cart_dropdown']) ? $wc_fragment['div.shopping_cart_dropdown'] : '',
            'cart_contents_count'      => ($woocommerce) ? ' ' . $woocommerce->cart->get_cart_contents_count() : '',
            'cart_subtotal'            => ($woocommerce) ? '' . $woocommerce->cart->get_cart_subtotal() : '',
        );
        ob_start();
        require_once('cart_search/widget-base.php');
        echo ob_get_clean();
    }

    public function woocommerce_header_add_to_cart_fragment($fragments)
    {
        if (function_exists('is_woocommerce')) {
            $woocommerce = WC();
            ob_start();
            ?>
            <span class="cart_total"><?php echo '' . $woocommerce->cart->cart_contents_count; ?></span>
            <?php
            $fragments['span.cart_total'] = ob_get_clean();
            return $fragments;
        }
            
    }

    public function get_query_var_from_url($var, $url)
    {
        $raw = explode('?', $url);
        if (count($raw) < 2)
            return '';
        $raw = explode('&', $raw[1]);
        foreach ($raw as $args) {
            if(strpos($args,'amp;') === 0)
                $val = substr($args,4);
            else
                $val = $args;
            $seg = explode('=', $val);
            if(count($seg)<2)
                continue;
            if ($seg[0] == $var)
                return $seg[1];
        }
        return '';
    }

    public function woocommerce_header_add_to_cart_content($fragments)
    {
        if (function_exists('is_woocommerce')) {
            $woocommerce = WC();
            //
            $cart = $woocommerce->cart->get_cart();
            $including_tax = get_option('woocommerce_tax_display_cart') == 'excl';
            $products = array();
            foreach ($cart as $key => $item) {
                $_product = $item['data'];
                if (!$_product->exists() || $item['quantity'] == 0) {
                    continue;
                }
                $price = ($including_tax) ? wc_get_price_including_tax($_product) : wc_get_price_excluding_tax($_product);
                $price = apply_filters('woocommerce_cart_item_price_html', wc_price($price), $item, $key);
                $permalink = $_product->get_permalink();
                $image = $_product->get_image();
                $title = apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product);
                $data = function_exists('wc_get_formatted_cart_item_data')? wc_get_formatted_cart_item_data( $item ): WC()->cart->get_item_data($item);
                $quantity = '<span class="quantity">' . sprintf('%s &times; %s', $item['quantity'], $price) . '</span>';
                $quantity = apply_filters('woocommerce_widget_cart_item_quantity', $quantity, $item, $key);
                $remove_link = (function_exists('wc_get_cart_remove_url'))? wc_get_cart_remove_url( $key ):  WC()->cart->get_remove_url( $key );
                $id = $_product->get_id();
                $sku = $_product->get_sku();
                $remove_id = $this->get_query_var_from_url('remove_item', $remove_link);
                $products[] = compact(array(
                    'id',
                    'sku',
                    'remove_id',
                    'permalink',
                    'image',
                    'title',
                    'data',
                    'quantity',
                    'remove_link'
                ));
            }
            //
            $is_cart_empty = sizeof($woocommerce->cart->get_cart()) <= 0;
            $list_class = array('cart_list', 'product_list_widget');
            $cart_subtotal = $woocommerce->cart->get_cart_subtotal();
            //
            $dropdown_attrs = array(
                'is_cart_empty' => sizeof($woocommerce->cart->get_cart()) <= 0,
                'list_class'    => implode(' ', $list_class),
                'products'      => $products,
                'cart_subtotal' => $cart_subtotal,
                'cart_url'      => wc_get_cart_url(),
                'checkout_url'  => wc_get_checkout_url()
            );
            //
            ob_start();
            require_once('cart_search/shop_cart_dropdown.php');
            $fragments['div.shopping_cart_dropdown'] = ob_get_clean();
            return $fragments;
        }
            
    }
}

function acumec_register_cart_search_widget()
{
    register_ef4_widget('acumec_Widget_Cart_Search');
}

add_action('widgets_init', 'acumec_register_cart_search_widget');

