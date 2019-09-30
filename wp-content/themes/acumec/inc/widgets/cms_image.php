<?php defined( 'ABSPATH' ) or exit();
/**
 * Simple Image Widget
 * This widget allows you to pick image and show at the front-end with some options
 *
 * @author knightdev
 * @version 1.0
 */
if (!function_exists('register_ef4_widget')) return;
class Cms_Image_Widget extends WP_Widget {
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
        parent::__construct(
            'cms_image', // Base ID
            esc_html__( 'Cms Contact', 'acumec' ), // Name
            array(
                'description' => esc_html__( 'Add image with some options and optional link.', 'acumec' ),
                'customize_selective_refresh' => true
            ) // Args
        );
    }


    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance )
    {
        extract( $args, EXTR_SKIP );
        
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => '',
                'link' => '',
                'link_title' => '',
                'target' => '',
                'size' => 'thumbnail',
                'name'  => '',
                'description' => '',
            )
        );

        echo wp_kses_post($before_widget);

        
        $size = empty( $instance['size'] ) ? 'thumbnail' : strval( $instance['size'] );
        $link_title = !empty($instance['link_title']) ? $instance['link_title'] : 'Read More';
        $bg_image = '';
        if ( ! empty( $instance['image'] ) ) {
            $image = ( is_numeric( $instance['image'] ) && $instance['image'] > 0 ) ? intval( $instance['image'] ) : 0;
            if ( $image > 0 ) {     
                $alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
                $alt = empty( $alt ) ? '' : strval( $alt );
                $image_src = wp_get_attachment_image_src( $image, $size );
            }
            $bg_image = 'background-image: url('.$image_src[0].');background-size: cover; background-repeat: no-repeat;';
        }
        echo '<div class= "content" style= "'.$bg_image.'">';
                

        if ( ! empty( $instance['title'] ) || !empty($instance['link']) || !empty($instance['description'])){
            echo '<div class = "text">'; 
            if ( ! empty( $title ) ) {
                echo wp_kses_post($before_title . $title . $after_title);
            } 
            if ( ! empty( $instance['title'] ) ) {
                echo '<h3 class="wg-title">'.$instance['title'].'</h3>';
            }

            if ( ! empty( $instance['description'] ) )
            {
                echo '<p class="description">'.$instance['description'].'</p>';
            }

            if ( ! empty( $instance['link'] ) ) {
                $target = ( '' === $instance['target'] ) ? '_self' : '_blank';
                echo '<a class="btn-cms-image btn btn-round" href="' . esc_url( $instance['link'] ) . '" target="' . esc_attr( $target ) . '">';
                echo esc_attr($link_title);
                echo '</a>';
            }
            echo '</div>';
        }

        echo '</div>';

        echo wp_kses_post($after_widget);
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['image'] = strip_tags( $new_instance['image'] );
        $instance['link'] = esc_url( strip_tags( $new_instance['link'] ) );
        $instance['link_title'] = strip_tags( $new_instance['link_title'] );
        $instance['size'] = strip_tags( $new_instance['size'] );
        $instance['target'] = strip_tags( $new_instance['target'] );
        $instance['description'] = sanitize_textarea_field( $new_instance['description'] );
        return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => 0,
                'link' => '',
                'link_title' => '',
                'target' => '',
                'size' => 'thumbnail',
                'name' => '',
                'description' => '',
            )
        );

        $image_holder = $this->get_field_id( 'images' ) . '-' . acumec_generate_uiqueid();
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image      = intval( $instance['image'] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php esc_html_e( 'Align:', 'acumec' ); ?></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">
                <option value=""><?php esc_html_e( 'Default', 'acumec' ); ?></option>
                <option value="left" <?php selected( "left", $instance['align'] ); ?>><?php esc_html_e( 'Left', 'acumec' ); ?></option>
                <option value="center" <?php selected( "center", $instance['align'] ); ?>><?php esc_html_e( 'Center', 'acumec' ); ?></option>
                <option value="right" <?php selected( "right", $instance['align'] ); ?>><?php esc_html_e( 'Right', 'acumec' ); ?></option>
            </select>
        </p>
        <div class="redexp-widget-image">
            <label><?php esc_html_e( 'Image', 'acumec' ); ?></label>
            <ul class="redexp-mu-images" id="<?php echo esc_attr( $image_holder ); ?>" data-img-mu-field="<?php echo esc_attr($this->get_field_id( 'image' )); ?>"><?php
            
                $attachment_image = wp_get_attachment_image_src( $image, 'thumbnail' );
                if ( ! empty( $attachment_image ) )
                {
                    printf(
                        '<li data-id="%1$s" style="background-image:url(%2$s);">',
                        esc_attr( $image ),
                        esc_url( $attachment_image[0] )
                    );

                    printf(
                        '<a class="image-edit" href="#" onclick="RedExpMedia.Image.edit(event,%s);"><i class="dashicons dashicons-edit"></i></a>',
                        esc_attr( '"' . $image_holder . '"' )
                    );

                    printf(
                        '<a class="image-delete" href="#" onclick="RedExpMedia.Image.remove(event,%s);"><i class="dashicons dashicons-trash"></i></a>',
                        esc_attr( '"' . $image_holder . '"' )
                    );

                    echo '</li>';
                }

                printf(
                    '<li data-id="0"><a class="image-add" href="#" onclick="RedExpMedia.Image.add(event,%s);"><i class="dashicons dashicons-plus-alt"></i></a></li>',
                    esc_attr( '"' . $image_holder . '"' )
                );

            ?></ul>
            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size:', 'acumec' ); ?></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>">
                <?php
                    $image_sizes = apply_filters( 'image_size_names_choose', array(
                        'thumbnail' => esc_html__( 'Thumbnail', 'acumec' ),
                        'medium'    => esc_html__( 'Medium', 'acumec' ),
                        'large'     => esc_html__( 'Large', 'acumec' ),
                        'full'      => esc_html__( 'Full Size', 'acumec' )
                    ) );
                    foreach ( $image_sizes as $size => $text )
                    {
                        printf(
                            '<option value="%1$s" %2$s">%3$s</option>',
                            esc_attr( $size ),
                            selected( $size, $instance['size'], false ),
                            esc_html( $text )
                        );
                    }
                ?>
            </select>
            
        </p>
            <p class="howto"><?php echo esc_html__( 'Add image size string, defaults include "thumbnail", "medium", "large", "full", or your defined size.', 'acumec' ); ?></p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link', 'acumec' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_url( $instance['link'] ); ?>" /></p>

            <p class="howto"><?php echo esc_html__( 'Add link for image', 'acumec' ); ?></p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'link_title' ) ); ?>"><?php echo esc_html__( 'Link Title', 'acumec' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_title' ) ); ?>" value="<?php echo esc_attr( $instance['link_title'] ); ?>" /></p>

            <input type="hidden" name="<?php echo esc_attr($this->get_field_name( 'image' ) ); ?>" id="<?php echo esc_attr($this->get_field_id( 'image' ) ); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>"/>
        </div>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php checked( "1", $instance['target'] );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open link in new tab?', 'acumec' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'acumec' ); ?></label>
            <textarea  class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
        </p>
        <?php
    }
}
function acumec_register_recent_image_widget(){
    register_ef4_widget('Cms_Image_Widget');
}
add_action( 'widgets_init', 'acumec_register_recent_image_widget' );